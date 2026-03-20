<?php 
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../login_and_signup_page/login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    die("Error: user_id not found in session.");
}

$conn = new mysqli("localhost", "root", "", "user_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
/* ---------- EXCHANGE NOTIFICATION COUNTS ---------- */

/* Pending received requests */
$receiveCount = 0;
$res1 = $conn->query("
    SELECT COUNT(*) AS total
    FROM exchange_requests er
    JOIN users u ON u.id = er.receiver_id
    WHERE er.receiver_id = {$_SESSION['user_id']}
      AND er.status = 'pending'
      AND (u.last_seen_receive IS NULL 
           OR er.created_at > u.last_seen_receive)
");
if ($row = $res1->fetch_assoc()) {
    $receiveCount = (int)$row['total'];
}

/* Pending sent requests */
$sendCount = 0;
$res2 = $conn->query("
    SELECT COUNT(*) AS total
    FROM exchange_requests er
    JOIN users u ON u.id = er.sender_id
    WHERE er.sender_id = {$_SESSION['user_id']}
      AND er.status = 'pending'
      AND (u.last_seen_send IS NULL 
           OR er.created_at > u.last_seen_send)
");
if ($row = $res2->fetch_assoc()) {
    $sendCount = (int)$row['total'];
}

/* ---------- SEARCH LOGIC ---------- */
$searchType = "";
if (isset($_GET['type']) && $_GET['type'] != "") {
    $searchType = $conn->real_escape_string($_GET['type']);
}

/* Fetch books and check for existing exchange requests */
$sql = "
    SELECT 
        books.*, 
        users.firstname, 
        users.lastname, 
        users.email,
        ex.id AS exchange_id,
        ex.status AS exchange_status
    FROM books
    JOIN users ON books.user_id = users.id
    LEFT JOIN exchange_requests ex 
        ON ex.sender_id = {$_SESSION['user_id']}
        AND ex.receiver_id = books.user_id
        AND ex.sender_book_id = books.id
        AND ex.status IN ('pending','accepted')
    WHERE books.exchanged = 0
      AND books.user_id != {$_SESSION['user_id']}
";

if ($searchType != "") {
    $sql .= "
        ORDER BY 
        (books.type = '$searchType') DESC,
        books.id DESC
    ";
} else {
    $sql .= " ORDER BY books.id DESC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Swaply</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
/* ---------------- BODY ---------------- */
body { margin: 0; font-family: Arial, sans-serif; background: #f4f6f7; }
.navbar { width:100%; height:70px; background:linear-gradient(to right,#6a11cb,#2575fc); display:flex; justify-content:space-between; align-items:center; padding:0 40px; box-sizing:border-box; color:white; position:relative; }
.logo { font-size:32px; font-weight:900; letter-spacing:2px; }
.logo span { background:#fff; color:#2575fc; padding:6px 12px; border-radius:10px; }
.user-center { position:absolute; left:50%; transform:translateX(-50%); font-size:20px; font-weight:bold; color:#fff; }
.logout-btn { background-color:#dc3545; color:white; border:none; padding:10px 18px; border-radius:8px; cursor:pointer; font-weight:bold; transition:0.3s; }
.logout-btn:hover { background-color:#a71d2a; }

/* ---------------- LAYOUT ---------------- */
.main-container { display:grid; grid-template-columns:25% 50% 25%; height:calc(100vh - 70px); overflow:hidden; }
.col { padding:25px; box-sizing:border-box; overflow:hidden; display:flex; flex-direction:column; justify-content:space-between; }
.left-col { background:#fff; border-right:2px solid #e0e0e0; }
.center-col { background:#fafafa; overflow-y:auto; }
.right-col { background:#fff; border-left:2px solid #e0e0e0; }
.section-title { font-size:22px; font-weight:bold; margin-bottom:20px; }
.btn-block { display:flex; flex-direction:column; gap:15px; }
.menu-btn { width:100%; padding:12px 20px; background:#2c3e50; border:none; color:white; border-radius:6px; cursor:pointer; font-size:16px; transition:0.3s; display:flex; align-items:center; gap:10px; }
.menu-btn:hover { background:#1a242f; transform:translateY(-3px); }

/* ---------------- POSTS ---------------- */
.post { position:relative; background:#fff; padding:18px; margin-bottom:20px; border-radius:12px; box-shadow:0 3px 12px rgba(0,0,0,0.1); border-left:5px solid #2575fc; box-sizing:border-box; }
.post .owner-info { margin-bottom:10px; font-size:14px; color:#555; }
.post .owner-info b { color:#2575fc; }
.post-images { display:flex; gap:20px; flex-wrap:wrap; margin-bottom:15px; }
.post-images img { width:200px; height:auto; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.15); }
.post h3 { margin:0 0 10px 0; color:#2575fc; }
.post p { margin:5px 0; font-size:15px; }
.post .exchange-btn { display:block; width:95%; text-align:center; margin-top:12px; margin-bottom:8px; padding:12px; background:#28a745; color:white; text-decoration:none; border-radius:6px; font-weight:bold; transition:0.3s; }
.post .exchange-btn:hover { background:#218838; }
.post .timestamp { position:absolute; top:10px; right:15px; font-size:12px; color:#999; }

/* ---------------- SEARCH BAR ---------------- */
.search-bar { display:flex; align-items:center; gap:10px; background:#fff; padding:10px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:20px; }
.search-bar select { flex:1; padding:10px; border:1px solid #ccc; border-radius:6px; font-size:15px; }
.search-bar button { background:#2575fc; color:#fff; border:none; padding:10px 18px; border-radius:6px; cursor:pointer; font-weight:bold; }
.search-bar button:hover { opacity:0.9; }

/* FOOTERS */
.left-footer { text-align:center; color:#666; font-size:14px; border-top:1px solid #ddd; padding-top:10px; margin-top:20px; }
.right-footer { text-align:center; border-top:1px solid #ddd; padding-top:10px; margin-top:20px; color:#666; }
.right-col .btn-block { display:flex; flex-direction:column; gap:15px; }
/* 🔔 NOTIFICATION BADGE */
.badge {
    background: #dc3545;
    color: white;
    font-size: 12px;
    font-weight: bold;
    padding: 3px 8px;
    border-radius: 50px;
    margin-left: auto;
}
.menu-btn {
    display: flex;
    align-items: center;
    gap: 10px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo"><span>Swaply</span></div>
    <div class="user-center">
        Welcome, <?= htmlspecialchars($_SESSION['firstname'] . " " . $_SESSION['lastname']); ?> 👋
    </div>
    <a href="logout.php"><button class="logout-btn">Logout</button></a>
</div>

<!-- MAIN LAYOUT -->
<div class="main-container">

    <!-- LEFT COLUMN -->
    <div class="col left-col">
        <div>
            <div class="section-title">Menu</div>
            <div class="btn-block">
                <a href="create_post.php" style="text-decoration:none;">
                    <button class="menu-btn"><i class="fas fa-plus"></i>Create Post</button>
                </a>
                <a href="my_post.php" style="text-decoration:none;">
                    <button class="menu-btn"><i class="fas fa-book"></i>My Posts</button>
                </a>
            </div>
        </div>
        <div class="left-footer">
            <i class="fa-regular fa-copyright"></i>
            2026 Swaply — All Rights Reserved.
        </div>
    </div>

    <!-- CENTER COLUMN -->
    <div class="col center-col">
        <div class="section-title">Posts</div>
        <form method="GET">
            <div class="search-bar">
                <i class="fas fa-magnifying-glass" style="color:#2575fc;font-size:18px;"></i>
                <select name="type">
                    <option value="">Search by Type</option>
                    <option value="novel" <?=($searchType=="novel")?"selected":""?>>Novel</option>
                    <option value="magazine" <?=($searchType=="magazine")?"selected":""?>>Magazine</option>
                    <option value="story" <?=($searchType=="story")?"selected":""?>>Story</option>
                    <option value="textbook" <?=($searchType=="textbook")?"selected":""?>>Textbook</option>
                </select>
                <button type="submit">Search</button>
            </div>
        </form>

        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="post">

            <div class="timestamp">
                <?= date("d M Y, h:i A", strtotime($row['created_at'])); ?>
            </div>

            <div class="owner-info">
                <b><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></b><br>
                <?= htmlspecialchars($row['email']) ?>
            </div>

            <div class="post-images">
                <?php if (!empty($row["front_img"])): ?>
                    <img src="<?= htmlspecialchars($row["front_img"]) ?>" alt="Front Image">
                <?php endif; ?>
                <?php if (!empty($row["back_img"])): ?>
                    <img src="<?= htmlspecialchars($row["back_img"]) ?>" alt="Back Image">
                <?php endif; ?>
            </div>

            <h3>Title: <?= htmlspecialchars($row["title"]) ?></h3>
            <p><strong>Language:</strong> <?= htmlspecialchars($row["language"]) ?></p>
            <p><strong>Type:</strong> <?= htmlspecialchars(ucfirst($row["type"])) ?></p>
            <p><strong>Condition:</strong> <?= htmlspecialchars(ucfirst($row["book_condition"])) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($row["description"]) ?></p>

            <?php if ($row['exchange_id']): ?>
                <button class="exchange-btn" style="background:#6c757d;cursor:not-allowed;" disabled>
                    Request Sent
                </button>
            <?php else: ?>
                <a href="exchange.php?id=<?= $row['id'] ?>" class="exchange-btn">Exchange</a>
            <?php endif; ?>

        </div>
        <?php endwhile; ?>

    </div>

    <!-- RIGHT COLUMN -->
    <div class="col right-col">
        <div class="btn-block">
            <div class="section-title">Exchange</div>
            <a href="exchange_receive.php" style="text-decoration:none;">
                <button class="menu-btn"><i class="fas fa-inbox"></i>Exchange Receive
                <?php if ($receiveCount > 0): ?>
            <span class="badge"><?= $receiveCount ?></span>
        <?php endif; ?> </button>
            </a>
            <a href="exchange_send.php" style="text-decoration:none;">
                <button class="menu-btn"><i class="fas fa-paper-plane"></i>Send Exchange
                <?php if ($sendCount > 0): ?>
            <span class="badge"><?= $sendCount ?></span>
        <?php endif; ?>
            </button>
            </a>
            <a href="history.php" style="text-decoration:none;">
                <button class="menu-btn"><i class="fas fa-clock"></i>History</button>
            </a>
            <a href="exchange_complete.php" style="text-decoration:none;">
                <button class="menu-btn"><i class="fa-solid fa-circle-check"></i>Exchange Complete</button>
            </a>
        </div>
        <div class="right-footer">
            Read | Share | Repeat
        </div>
    </div>

</div>
</body>
</html>