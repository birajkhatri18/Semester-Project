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

// --------- GET POST DATA -----------
if (!isset($_GET['id'])) {
    die("Post ID missing.");
}

$post_id = intval($_GET['id']);

$sql = "SELECT * FROM books WHERE id = $post_id AND user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("Post not found or you do not have permission.");
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book Post</title>
    <link rel="stylesheet" href="css/edit_post.css">
</head>

<body>

    <div class="container">
        <h2>Edit Book Post</h2>

        <form id="editForm" action="update_post.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?= $post['id'] ?>">

            <!-- Images -->
            <label>Book Images (Front & Back)</label>
            <div class="img-row">

                <div class="img-box">
                    <div class="img-title">Front Cover</div>
                    <input type="file" id="frontImg" name="front_img" accept="image/*">
                    <img id="frontPreview" src="<?= $post['front_img'] ?>" alt="Front Image">
                </div>

                <div class="img-box">
                    <div class="img-title">Back Cover</div>
                    <input type="file" id="backImg" name="back_img" accept="image/*">
                    <img id="backPreview" src="<?= $post['back_img'] ?>" alt="Back Image">
                </div>

            </div>

            <label>Book Title</label>
            <input type="text" name="title" value="<?= $post['title'] ?>" required>

            <label>Language</label>
            <input type="text" name="language" value="<?= $post['language'] ?>" required>

            <label>Type</label>
            <select name="type" required>
                <option value="textbook" <?= $post['type'] == 'textbook' ? 'selected' : '' ?>>Textbook</option>
                <option value="novel" <?= $post['type'] == 'novel' ? 'selected' : '' ?>>Novel</option>
                <option value="story" <?= $post['type'] == 'story' ? 'selected' : '' ?>>Story</option>
                <option value="magazine" <?= $post['type'] == 'magazine' ? 'selected' : '' ?>>Magazine</option>
                <option value="other" <?= $post['type'] == 'other' ? 'selected' : '' ?>>Other</option>
            </select>

            <label>Condition</label>
            <div class="options">
                <label><input type="radio" name="condition" value="new" <?= $post['book_condition'] == 'new' ? 'checked' : '' ?>> New</label>
                <label><input type="radio" name="condition" value="used" <?= $post['book_condition'] == 'used' ? 'checked' : '' ?>> Used</label>
                <label><input type="radio" name="condition" value="old" <?= $post['book_condition'] == 'old' ? 'checked' : '' ?>> Old</label>
                <label><input type="radio" name="condition" value="damaged" <?= $post['book_condition'] == 'damaged' ? 'checked' : '' ?>> Damaged</label>
            </div>

            <label>Description</label>
            <textarea name="description"><?= $post['description'] ?></textarea>

            <div class="buttons">
                <button type="submit" class="btn-update">Update</button>
                <button type="reset" class="btn-reset">Reset</button>
                <button type="button" class="btn-cancel" onclick="window.location.href='my_post.php'">Cancel</button>
            </div>

        </form>
    </div>

    <script>
        // Preview updated image
        function loadPreview(input, imgId) {
            const file = input.files[0];
            const img = document.getElementById(imgId);
            if (file) {
                img.src = URL.createObjectURL(file);
                img.style.display = "block";
            }
        }

        document.getElementById("frontImg").onchange = function () {
            loadPreview(this, "frontPreview");
        };
        document.getElementById("backImg").onchange = function () {
            loadPreview(this, "backPreview");
        };

        // Reset preview to original images
        document.getElementById("editForm").addEventListener("reset", function () {
            setTimeout(() => {
                document.getElementById("frontPreview").src = "<?= $post['front_img'] ?>";
                document.getElementById("backPreview").src = "<?= $post['back_img'] ?>";
            }, 0);
        });
    </script>

</body>
</html>
