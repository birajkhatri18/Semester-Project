<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    die("Error: user_id not found in session.");
}

$conn = new mysqli("localhost", "root", "", "user_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch only books that are not exchanged yet
$sql = "
    SELECT * FROM books 
    WHERE user_id = $user_id AND exchanged = 0
    ORDER BY id DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Posts</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f7;
}

/* ---------------- NAVBAR ---------------- */
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
    letter-spacing: 2px;
}

.logo span {
    background: white;
    color: #2575fc;
    padding: 6px 12px;
    border-radius: 10px;
}

/* Center username */
.user-center {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    font-size: 20px;
    font-weight: bold;
    text-align: center;
}

/* Back Button */
.cancel-btn {
    background: #2c3e50;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    font-weight: bold;
    transition: 0.3s;
}
.cancel-btn:hover {
    background: #1a242f;
}

/* ---------------- POSTS ---------------- */
.container {
    width: 60%;
    margin: 40px auto;
}

.section-title {
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 25px;
    color: #333;
}

.post {
    background: #fff;
    padding: 20px;
    margin-bottom: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    border-left: 5px solid #2575fc;
}

.post-images img {
    width: 200px;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.post-images {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 15px;
}

.post h3 {
    margin: 0 0 10px 0;
    color: #2575fc;
}

/* ---------------- BUTTONS ---------------- */
.action-btns {
    margin-top: 15px;
    display: flex;
    gap: 20px;
}

.edit-btn, .delete-btn {
    flex: 1;
    text-align: center;
    padding: 12px 0;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    color: white;
    transition: 0.3s;
    display: inline-block;
}

.edit-btn {
    background: #007bff;
}
.edit-btn:hover {
    background: #0056b3;
}

.delete-btn {
    background: #dc3545;
}
.delete-btn:hover {
    background: #b51f2f;
}

/* ---------------- DELETE POPUP ---------------- */
.popup-bg {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    backdrop-filter: blur(4px);
    justify-content: center;
    align-items: center;
}

.popup-box {
    width: 350px;
    background: white;
    padding: 25px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.pop-btns {
    margin-top: 20px;
    display: flex;
    gap: 15px;
}

.confirm-delete {
    flex: 1;
    background: #e60000;
    padding: 12px;
    border-radius: 8px;
    color: white;
    text-decoration: none;
    font-weight: bold;
}

.cancel-delete {
    flex: 1;
    background: #777;
    padding: 12px;
    border-radius: 8px;
    color: white;
    text-decoration: none;
    font-weight: bold;
}

/* ---------------- RESPONSIVE DESIGN ------------------- */
@media (max-width: 900px) {
    .container { width: 80%; }
    .navbar { padding: 0 20px; }
}

@media (max-width: 600px) {
    .navbar { height:auto; padding:15px; flex-direction:column; gap:10px; text-align:center; }
    .logo { font-size:22px; }
    .user-center { position:static; transform:none; font-size:18px; }
    .cancel-btn { width:90%; text-align:center; padding:10px; }
    .container { width:90%; margin:20px auto; }
    .post-images img { width:100%; max-width:300px; }
    .action-btns { flex-direction:column; }
}

@media (max-width: 400px) {
    .logo { font-size:20px; }
    .user-center { font-size:16px; }
    .post { padding:15px; }
    .popup-box { width:90%; }
}
</style>

<script>
function openDeletePopup(id) {
    document.getElementById("popup-bg").style.display = "flex";
    document.getElementById("deleteLink").href = "my_post.php?delete=" + id;
}
function closePopup() {
    document.getElementById("popup-bg").style.display = "none";
}
</script>

</head>
<body>

<div class="navbar">
    <div class="logo"><span>Swaply</span></div>

    <div class="user-center">
        <?= htmlspecialchars($_SESSION['firstname'] . " " . $_SESSION['lastname']); ?> 👋
    </div>

    <a href="home.php" class="cancel-btn">Back</a>
</div>

<div class="container">
    <div class="section-title">Your Recent Posts</div>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        
        <div class="post">

            <div class="post-images">
                <?php if(!empty($row["front_img"])): ?>
                    <img src="<?= htmlspecialchars($row["front_img"]) ?>" alt="Front">
                <?php endif; ?>

                <?php if(!empty($row["back_img"])): ?>
                    <img src="<?= htmlspecialchars($row["back_img"]) ?>" alt="Back">
                <?php endif; ?>
            </div>
            <h3>Title: <?= htmlspecialchars($row["title"]) ?></h3>
            <p><strong>Language:</strong> <?= htmlspecialchars($row["language"]) ?></p>
            <p><strong>Type:</strong> <?= htmlspecialchars(ucfirst($row["type"])) ?></p>
            <p><strong>Condition:</strong> <?= htmlspecialchars(ucfirst($row["book_condition"])) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($row["description"]) ?></p>

            <div class="action-btns">
                <a href="edit_post.php?id=<?= $row['id'] ?>" class="edit-btn">Edit Post</a>
                <a onclick="openDeletePopup(<?= $row['id'] ?>)" class="delete-btn" href="#">Delete Post</a>
            </div>

        </div>

        <?php endwhile; ?>
    <?php else: ?>
        <p>You have no available posts to exchange.</p>
    <?php endif; ?>
</div>

<!-- POPUP -->
<div class="popup-bg" id="popup-bg">
    <div class="popup-box">
        <h2>Are you sure?</h2>
        <p>This post will be permanently deleted.</p>

        <div class="pop-btns">
            <a id="deleteLink" class="confirm-delete">Delete</a>
            <a onclick="closePopup()" class="cancel-delete">Cancel</a>
        </div>
    </div>
</div>

</body>
</html>

<?php
/* ---- DELETE FUNCTION ---- */
if (isset($_GET['delete'])) {
    $post_id = intval($_GET['delete']);
    $delete_sql = "DELETE FROM books WHERE id = $post_id AND user_id = $user_id";

    if ($conn->query($delete_sql)) {
        echo "<script>window.location='my_post.php';</script>";
    }
}
?>
