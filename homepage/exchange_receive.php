<?php
session_start();
if (!isset($_SESSION['user_id'])) die("Unauthorized");

$conn = new mysqli("localhost","root","","user_system");
if ($conn->connect_error) die("DB Error");

$conn->query("
    UPDATE users 
    SET last_seen_receive = NOW() 
    WHERE id = {$_SESSION['user_id']}
");

$user_id = $_SESSION['user_id'];

$sql = "
SELECT er.*,
u.firstname, u.lastname,
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
JOIN users u ON er.sender_id = u.id
JOIN books sb ON er.sender_book_id = sb.id
JOIN books rb ON er.receiver_book_id = rb.id
WHERE er.receiver_id = $user_id
AND er.status = 'pending'
ORDER BY er.created_at DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Incoming Exchange Requests</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body { margin:0; font-family:Arial; background:#f4f6f7; }

.navbar {
    height:70px;
    background:linear-gradient(to right,#6a11cb,#2575fc);
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 30px;
    color:white;
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
.back-btn {
    background: #2c3e50;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    font-weight: bold;
}
.back-btn:hover {
    background: #1a242f;
}

.container { width:60%; margin:40px auto; }

.section-title {
    font-size:26px;
    font-weight:bold;
    margin-bottom:25px;
}

.post {
    background:#fff;
    padding:20px;
    margin-bottom:25px;
    border-radius:12px;
    box-shadow:0 3px 12px rgba(0,0,0,.1);
    border-left:5px solid #2575fc;
}

.post-images {
    display:flex;
    gap:20px;
    margin-bottom:15px;
}

.post-images img {
    width:180px;
    border-radius:8px;
}

.exchange-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:25px;
}

/* ===== BUTTON STYLES ===== */
.action-btns {
    margin-top:20px;
    display:flex;
    gap:15px;
}

.accept-btn {
    width:100%;
    padding:12px;
    background:#28a745;
    color:white;
    border:none;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;
}
.accept-btn:hover { background:#218838; }

.reject-btn {
    width:100%;
    padding:12px;
    background:#dc3545;
    color:white;
    border:none;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;
}
.reject-btn:hover { background:#c82333; }

/* ===== PHONE INPUT (HIDDEN INITIALLY) ===== */
.phone-box {
    display:none;
    margin-top:10px;
}

.phone-box input {
    width:100%;
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
}
</style>

<script>
function showPhone(id) {
    document.getElementById("phone_"+id).style.display = "block";
    document.getElementById("btn_"+id).innerText = "Confirm Accept";
}
</script>
</head>

<body>

<div class="navbar">
    <div class="logo"><span>Swaply</span></div>
    <a href="home.php" class="back-btn" >Back</a>
</div>

<div class="container">
<div class="section-title">Incoming Exchange Requests</div>

<?php if ($result->num_rows == 0): ?>
<p>No pending exchange requests.</p>
<?php endif; ?>

<?php while($row = $result->fetch_assoc()): ?>

<div class="post">

<b>From:</b> <?= htmlspecialchars($row['firstname']." ".$row['lastname']) ?><br><br>

<div class="exchange-grid">

<!-- YOUR BOOK -->
<div>
<h3>Your Book</h3>
<div class="post-images">
<?php if($row['r_front']): ?><img src="<?= $row['r_front'] ?>"><?php endif; ?>
<?php if($row['r_back']): ?><img src="<?= $row['r_back'] ?>"><?php endif; ?>
</div>
<p><b>Title:</b> <?= $row['r_title'] ?></p>
<p><b>Language:</b> <?= $row['r_language'] ?></p>
<p><b>Type:</b> <?= ucfirst($row['r_type']) ?></p>
<p><b>Condition:</b> <?= ucfirst($row['r_condition']) ?></p>
<p><b>Description:</b> <?= $row['r_description'] ?></p>
</div>

<!-- OFFERED BOOK -->
<div>
<h3>Offered Book</h3>
<div class="post-images">
<?php if($row['s_front']): ?><img src="<?= $row['s_front'] ?>"><?php endif; ?>
<?php if($row['s_back']): ?><img src="<?= $row['s_back'] ?>"><?php endif; ?>
</div>
<p><b>Title:</b> <?= $row['s_title'] ?></p>
<p><b>Language:</b> <?= $row['s_language'] ?></p>
<p><b>Type:</b> <?= ucfirst($row['s_type']) ?></p>
<p><b>Condition:</b> <?= ucfirst($row['s_condition']) ?></p>
<p><b>Description:</b> <?= $row['s_description'] ?></p>
</div>

</div>

<div class="action-btns">

<!-- ACCEPT -->
<form method="POST" action="exchange_accept.php" style="flex:1;">
<input type="hidden" name="id" value="<?= $row['id'] ?>">

<button type="button"
        id="btn_<?= $row['id'] ?>"
        class="accept-btn"
        onclick="showPhone(<?= $row['id'] ?>)">
Accept
</button>

<div class="phone-box" id="phone_<?= $row['id'] ?>">
    <input type="text"
           name="receiver_phone"
           maxlength="10"
           pattern="[0-9]{10}"
           required
           placeholder="Enter 10-digit phone">
    <button class="accept-btn" style="margin-top:10px;">Confirm</button>
</div>
</form>

<!-- REJECT -->
<form method="POST" action="exchange_decline.php" style="flex:1;">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<button class="reject-btn">Reject</button>
</form>

</div>
</div>

<?php endwhile; ?>
</div>

</body>
</html>
