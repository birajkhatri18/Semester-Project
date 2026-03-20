<?php
session_start();
if (!isset($_SESSION['user_id'])) die("Unauthorized");

$conn = new mysqli("localhost","root","","user_system");
if ($conn->connect_error) die("DB Error");

$id = (int)$_POST['id'];

/* Get the request first */
$request = $conn->query("SELECT sender_book_id, receiver_book_id FROM exchange_requests WHERE id = $id")->fetch_assoc();

if($request){
    /* Reject request */
    $stmt = $conn->prepare("
        UPDATE exchange_requests
        SET status = 'rejected',
            updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    /* Make books available again */
    $conn->query("UPDATE books SET exchanged=0 WHERE id IN ({$request['sender_book_id']}, {$request['receiver_book_id']})");
}

header("Location: exchange_receive.php");
exit;