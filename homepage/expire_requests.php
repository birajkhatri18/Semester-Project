<?php
$conn = new mysqli("localhost","root","","user_system");

/* Expire pending requests older than 24 hours */
$expireTime = 24; // in hours

$pending = $conn->query("
    SELECT id, sender_book_id, receiver_book_id 
    FROM exchange_requests 
    WHERE status='pending' AND created_at < NOW() - INTERVAL $expireTime HOUR
");

while($row = $pending->fetch_assoc()){
    /* Mark as expired */
    $conn->query("UPDATE exchange_requests SET status='expired', updated_at=NOW() WHERE id={$row['id']}");

    /* Make books available again */
    $conn->query("UPDATE books SET exchanged=0 WHERE id IN ({$row['sender_book_id']}, {$row['receiver_book_id']})");
}