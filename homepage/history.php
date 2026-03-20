<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    die("Error: user_id not found in session.");
}

$conn = new mysqli("localhost","root","","user_system");
if ($conn->connect_error) die("DB Error");

$user_id = (int) $_SESSION['user_id'];

$sql = "
SELECT er.*,

sb.title AS s_title,
sb.language AS s_language,
sb.type AS s_type,
sb.book_condition AS s_condition,
sb.description AS s_description,
sb.front_img AS s_front,
sb.back_img AS s_back,

rb.title AS r_title,
rb.language AS r_language,
rb.type AS r_type,
rb.book_condition AS r_condition,
rb.description AS r_description,
rb.front_img AS r_front,
rb.back_img AS r_back

FROM exchange_requests er
LEFT JOIN books sb ON er.sender_book_id = sb.id
LEFT JOIN books rb ON er.receiver_book_id = rb.id
WHERE er.sender_id = $user_id
   OR er.receiver_id = $user_id
ORDER BY er.created_at DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sent Exchange Requests</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f7;
}
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
    position: relative;
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
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
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
.container {
    width: 60%;
    margin: 40px auto;
}
.section-title {
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 25px;
}
.post {
    background: #fff;
    padding: 20px;
    margin-bottom: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    border-left: 5px solid #2575fc;
    position: relative;
}
.post-images {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 15px;
}
.post-images img {
    width: 180px;
    border-radius: 8px;
}
.exchange-status {
    margin-top: 15px;
    padding: 10px;
    border-radius: 8px;
    font-weight: bold;
    text-align: center;
}
.pending { background:#fff3cd; color:#856404; }
.accepted { background:#d4edda; color:#155724; }
.rejected { background:#f8d7da; color:#721c24; }
.completed { background:#d1ecf1; color:#0c5460; }

/* 🔹 TIME STYLE (NEW, ONLY ADDITION) */
.post-time {
    position: absolute;
    top: 12px;
    right: 18px;
    font-size: 12px;
    color: #888;
}

/* Flex row for books */
.book-row {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}
.book-column {
    flex: 1;
    min-width: 250px;
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
<div class="section-title">History</div>

<?php if ($result->num_rows == 0): ?>
    <p>You have not sent any exchange requests.</p>
<?php endif; ?>

<?php while($row = $result->fetch_assoc()): ?>

<div class="post">

<!-- 🔹 TIME DISPLAY -->
<div class="post-time">
    <?= date("d M Y, h:i A", strtotime($row['created_at'])); ?>
</div>

<div class="book-row">

<!-- My Book -->
<div class="book-column">
<h3>My Book</h3>
<div class="post-images">
<?php if($row['s_front']): ?><img src="<?= $row['s_front'] ?>"><?php endif; ?>
<?php if($row['s_back']): ?><img src="<?= $row['s_back'] ?>"><?php endif; ?>
</div>
<p><strong>Title:</strong> <?= $row['s_title'] ?></p>
<p><strong>Language:</strong> <?= $row['s_language'] ?></p>
<p><strong>Type:</strong> <?= ucfirst($row['s_type']) ?></p>
<p><strong>Condition:</strong> <?= ucfirst($row['s_condition']) ?></p>
<p><strong>Description:</strong> <?= $row['s_description'] ?></p>
</div>

<!-- Requested Book -->
<div class="book-column">
<h3>Requested Book</h3>
<div class="post-images">
<?php if($row['r_front']): ?><img src="<?= $row['r_front'] ?>"><?php endif; ?>
<?php if($row['r_back']): ?><img src="<?= $row['r_back'] ?>"><?php endif; ?>
</div>
<p><strong>Title:</strong> <?= $row['r_title'] ?></p>
<p><strong>Language:</strong> <?= $row['r_language'] ?></p>
<p><strong>Type:</strong> <?= ucfirst($row['r_type']) ?></p>
<p><strong>Condition:</strong> <?= ucfirst($row['r_condition']) ?></p>
<p><strong>Description:</strong> <?= $row['r_description'] ?></p>
</div>

</div>

<div class="exchange-status <?= $row['status'] ?>">
STATUS: <?= strtoupper($row['status']) ?>
</div>

</div>

<?php endwhile; ?>
</div>

</body>
</html>