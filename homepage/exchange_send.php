<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","user_system");
if ($conn->connect_error) die("DB Error");

$conn->query("
    UPDATE users 
    SET last_seen_send = NOW() 
    WHERE id = {$_SESSION['user_id']}
");

$userId = (int) $_SESSION['user_id'];

/*
-----------------------------------
AUTO EXPIRE AFTER 24 HOURS
-----------------------------------
*/
$conn->query("
    UPDATE exchange_requests
    SET status = 'expired'
    WHERE status = 'pending'
      AND created_at < (NOW() - INTERVAL 24 HOUR)
");

/*
-----------------------------------
FETCH SENT PENDING REQUESTS
-----------------------------------
*/
$result = $conn->query("
    SELECT 
        er.*,
        b.title,
        b.front_img,

        us.firstname AS sender_fname,
        us.lastname  AS sender_lname,

        ur.firstname AS receiver_fname,
        ur.lastname  AS receiver_lname

    FROM exchange_requests er
    JOIN books b ON er.sender_book_id = b.id
    JOIN users us ON er.sender_id = us.id
    JOIN users ur ON er.receiver_id = ur.id

    WHERE er.sender_id = $userId
      AND er.status = 'pending'
    ORDER BY er.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Sent Exchanges</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f7;
}

/* NAVBAR */
.navbar {
    width: 100%;
    height: 70px;
    background: linear-gradient(to right, #6a11cb, #2575fc);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 30px;
    box-sizing: border-box;
    color: white;
}
.logo {
    font-size: 32px;
    font-weight: 900;
}
.logo span {
    background: white;
    color: #2575fc;
    padding: 6px 12px;
    border-radius: 10px;
}
.user-center {
    font-size: 20px;
    font-weight: bold;
}
.cancel-btn {
    background: #2c3e50;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    font-weight: bold;
}
.cancel-btn:hover {
    background: #1a242f;
}

/* CONTAINER */
.container {
    width: 60%;
    margin: 40px auto;
}
.section-title {
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 25px;
}

/* CARD */
.post {
    background:#fff;
    padding:20px;
    margin-bottom:25px;
    border-radius:12px;
    box-shadow:0 3px 12px rgba(0,0,0,.1);
    border-left:5px solid #2575fc;
    position: relative;
}
.post img {
    width:180px;
    border-radius:8px;
    margin-bottom: 12px;
}

/* TIME */
.timestamp {
    position:absolute;
    top:12px;
    right:18px;
    font-size:12px;
    color:#888;
}

/* STATUS */
.status-box {
    margin-top: 12px;
    background:#fff3cd;
    color:#856404;
    padding:10px;
    border-radius:8px;
    font-weight:bold;
    text-align:center;
}

/* 🔹 NAME STYLE (ONLY NEW STYLE) */
.name-row {
    margin: 8px 0 14px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.name-badge {
    background: #eef2ff;
    color: #1e3a8a;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold;
}
</style>
</head>

<body>

<div class="navbar">
    <div class="logo"><span>Swaply</span></div>
    <div class="user-center">
        <?= htmlspecialchars($_SESSION['firstname']." ".$_SESSION['lastname']); ?> 👋
    </div>
    <a href="home.php" class="cancel-btn">Back</a>
</div>

<div class="container">
<div class="section-title">Sent Exchange Requests</div>

<?php if ($result->num_rows == 0): ?>
    <p style="color:#777;">No pending exchange requests.</p>
<?php endif; ?>

<?php while($row = $result->fetch_assoc()): ?>
<div class="post">

    <div class="timestamp">
        Sent: <?= date("d M Y, h:i A", strtotime($row['created_at'])) ?>
    </div>

    <?php if (!empty($row['front_img'])): ?>
        <img src="<?= htmlspecialchars($row['front_img']) ?>">
    <?php endif; ?>

    <h3>Book title: <?= htmlspecialchars($row['title']) ?></h3>

    <!-- 🔹 SENDER & RECEIVER NAMES -->
    <div class="name-row">
        <div class="name-badge">
            <i class="fa-solid fa-user"></i>
            Sender: <?= htmlspecialchars($row['sender_fname']." ".$row['sender_lname']) ?>
        </div>

        <div class="name-badge">
            <i class="fa-solid fa-user-check"></i>
            Receiver: <?= htmlspecialchars($row['receiver_fname']." ".$row['receiver_lname']) ?>
        </div>
    </div>

    <div class="status-box">
        STATUS: PENDING
    </div>

</div>
<?php endwhile; ?>

</div>

</body>
</html>