<?php
session_start();
if (!isset($_SESSION['user_id'])) die("Unauthorized");

$conn = new mysqli("localhost","root","","user_system");
if ($conn->connect_error) die("DB Error");

$receiver_id = $_SESSION['user_id'];
$exchange_id = intval($_POST['id']);
$receiver_phone = $_POST['receiver_phone'];

/* 1️⃣ Get exchange details */
$get = $conn->query("
    SELECT * FROM exchange_requests
    WHERE id = $exchange_id AND receiver_id = $receiver_id
");

if ($get->num_rows == 0) die("Invalid request");

$ex = $get->fetch_assoc();

/* 2️⃣ Update exchange → COMPLETED */
$conn->query("
    UPDATE exchange_requests SET
        receiver_phone = '$receiver_phone',
        status = 'completed',
        completed_at = NOW()
    WHERE id = $exchange_id
");

/* 3️⃣ Remove both books from users (mark exchanged) */
$conn->query("
    UPDATE books SET exchanged = 1
    WHERE id IN ({$ex['sender_book_id']}, {$ex['receiver_book_id']})
");

/* 4️⃣ Redirect */
header("Location: exchange_complete.php");
exit();
?>
