<?php
session_start();
if (!isset($_SESSION['user_id'])) die("Unauthorized");

$conn = new mysqli("localhost","root","","user_system");
$user_id = $_SESSION['user_id'];

$sql = "
SELECT er.*,
u1.firstname AS sender_fn, u1.lastname AS sender_ln,
u2.firstname AS receiver_fn, u2.lastname AS receiver_ln,

sb.title AS s_title, sb.language AS s_language, sb.type AS s_type,
sb.book_condition AS s_condition, sb.description AS s_description,
sb.front_img AS s_front, sb.back_img AS s_back,

rb.title AS r_title, rb.language AS r_language, rb.type AS r_type,
rb.book_condition AS r_condition, rb.description AS r_description,
rb.front_img AS r_front, rb.back_img AS r_back,

er.sender_phone, er.receiver_phone

FROM exchange_requests er
JOIN users u1 ON er.sender_id = u1.id
JOIN users u2 ON er.receiver_id = u2.id
JOIN books sb ON er.sender_book_id = sb.id
JOIN books rb ON er.receiver_book_id = rb.id
WHERE (er.sender_id = $user_id OR er.receiver_id = $user_id)
AND er.status = 'completed'
ORDER BY er.completed_at DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Exchange History</title>
<link rel="stylesheet" href="css/history.css">
<style>

</style>
</head>

<body>
<div class="navbar">
    <div class="logo"><span>Swaply</span></div>
    <div class="user-center"><?= htmlspecialchars($_SESSION['firstname']." ".$_SESSION['lastname']); ?> 👋</div>
    <a href="home.php" class="cancel-btn">Back</a>
</div>

<div class="container">
<div class="section-title">Exchange Complete History</div>

<?php if ($result->num_rows == 0): ?>
<p style="text-align:center;">No completed exchanges yet.</p>
<?php endif; ?>

<?php while($row = $result->fetch_assoc()): ?>
<div class="post">

<b><?= $row['sender_fn']." ".$row['sender_ln'] ?></b> ↔ <b><?= $row['receiver_fn']." ".$row['receiver_ln'] ?></b>

<div class="exchange-grid">
    <!-- Sender Book -->
    <div>
        <h3>Sender Book</h3>
        <div class="post-images">
            <?php if($row['s_front']): ?><img src="<?= $row['s_front'] ?>" alt="Sender Front"><?php endif; ?>
            <?php if($row['s_back']): ?><img src="<?= $row['s_back'] ?>" alt="Sender Back"><?php endif; ?>
        </div>
        <p><strong>Title:</strong> <?= $row['s_title'] ?></p>
        <p><strong>Language:</strong> <?= $row['s_language'] ?></p>
        <p><strong>Type:</strong> <?= ucfirst($row['s_type']) ?></p>
        <p><strong>Condition:</strong> <?= ucfirst($row['s_condition']) ?></p>
        <p><strong>Description:</strong> <?= $row['s_description'] ?></p>
    </div>

    <!-- Receiver Book -->
    <div>
        <h3>Receiver Book</h3>
        <div class="post-images">
            <?php if($row['r_front']): ?><img src="<?= $row['r_front'] ?>" alt="Receiver Front"><?php endif; ?>
            <?php if($row['r_back']): ?><img src="<?= $row['r_back'] ?>" alt="Receiver Back"><?php endif; ?>
        </div>
        <p><strong>Title:</strong> <?= $row['r_title'] ?></p>
        <p><strong>Language:</strong> <?= $row['r_language'] ?></p>
        <p><strong>Type:</strong> <?= ucfirst($row['r_type']) ?></p>
        <p><strong>Condition:</strong> <?= ucfirst($row['r_condition']) ?></p>
        <p><strong>Description:</strong> <?= $row['r_description'] ?></p>
    </div>
</div>

<div class="phone-info">
<p><strong>Sender Phone:</strong> <?= $row['sender_phone'] ?> | <strong>Receiver Phone:</strong> <?= $row['receiver_phone'] ?></p>
</div>

<div class="status-box">
Completed
</div>

</div>
<?php endwhile; ?>
</div>
</body>
</html>
