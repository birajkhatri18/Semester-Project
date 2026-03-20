<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit("Unauthorized");
}

$conn = new mysqli("localhost","root","","user_system");
if ($conn->connect_error) die("DB Error");

$sender_id        = $_SESSION['user_id'];
$receiver_id      = (int)$_POST['receiver_id'];
$sender_book_id   = (int)$_POST['sender_book_id'];
$receiver_book_id = (int)$_POST['receiver_book_id'];
$sender_phone     = $_POST['sender_phone'];

/* Validation */
if (!$sender_id || !$receiver_id || !$sender_book_id || !$receiver_book_id || !$sender_phone) {
    http_response_code(400);
    exit("Missing data");
}

/* --------- CHECK DUPLICATE --------- */
$check = $conn->prepare("
    SELECT id 
    FROM exchange_requests
    WHERE sender_id = ? 
      AND sender_book_id = ? 
      AND receiver_book_id = ? 
      AND status IN ('pending','accepted')
");
$check->bind_param("iii", $sender_id, $sender_book_id, $receiver_book_id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    http_response_code(409);
    exit("Exchange request already sent");
}

/* --------- INSERT REQUEST --------- */
$sql = "
INSERT INTO exchange_requests
(sender_id, receiver_id, sender_book_id, receiver_book_id, sender_phone, status, created_at)
VALUES (?, ?, ?, ?, ?, 'pending', NOW())
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiis", $sender_id, $receiver_id, $sender_book_id, $receiver_book_id, $sender_phone);
$stmt->execute();

/* --------- MARK BOOKS AS RESERVED --------- */
$conn->query("UPDATE books SET exchanged=1 WHERE id IN ($sender_book_id, $receiver_book_id)");

echo "success";