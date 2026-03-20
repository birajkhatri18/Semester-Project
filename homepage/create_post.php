<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    die("Error: user_id not found in session.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Book Post</title>
    <link rel="stylesheet" href="css/create_post.css">
</head>

<body>

<div class="navbar">
    <div class="logo"><span>Swaply</span></div>
    <div class="user-center">
        <?= htmlspecialchars($_SESSION['firstname'] . " " . $_SESSION['lastname']); ?> 👋
    </div>
</div>

<div class="container">
    <h2>Create Book Post</h2>

    <!-- IMAGE UPLOAD ERROR -->
    <?php if (!empty($_SESSION['upload_error'])): ?>
        <div class="upload-error" id="uploadError">
            <?= htmlspecialchars($_SESSION['upload_error']); ?>
        </div>
        <?php unset($_SESSION['upload_error']); ?>
    <?php endif; ?>

    <form id="bookForm" action="save_post.php" method="POST" enctype="multipart/form-data">

        <label>Book Images (Front & Back)</label>
        <div class="img-row">

            <div class="img-box">
                <div class="img-title">Front Cover</div>
                <input type="file" id="frontImg" name="front_img" accept="image/*">
                <img id="frontPreview" alt="Front Preview">
            </div>

            <div class="img-box">
                <div class="img-title">Back Cover</div>
                <input type="file" id="backImg" name="back_img" accept="image/*">
                <img id="backPreview" alt="Back Preview">
            </div>

        </div>

        <label>Book Title *</label>
        <input type="text" name="title" placeholder="Book Title"required>

        <label>Language *</label>
        <input type="text" name="language" placeholder="Language" required>

        <label>Type *</label>
        <select name="type" required>
            <option value="">Select...</option>
            <option value="textbook">Textbook</option>
            <option value="novel">Novel</option>
            <option value="story">Story</option>
            <option value="magazine">Magazine</option>
            <option value="other">Other</option>
        </select>

        <label>Condition *</label>
        <div class="options">
            <label><input type="radio" name="condition" value="new" required> New</label>
            <label><input type="radio" name="condition" value="used"> Used</label>
            <label><input type="radio" name="condition" value="old"> Old</label>
            <label><input type="radio" name="condition" value="damaged"> Damaged</label>
        </div>

        <label>Description</label>
        <textarea name="description" placeholder="Write something about book."></textarea>

        <div class="buttons">
            <button type="submit" class="btn-submit">Submit</button>
            <button type="reset" class="btn-reset">Reset</button>
            <button type="button" class="btn-cancel" onclick="window.location.href='home.php'">Cancel</button>
        </div>
    </form>
</div>

<script>
/* -------- IMAGE PREVIEW -------- */
function loadPreview(input, imgId) {
    const file = input.files[0];
    const img = document.getElementById(imgId);

    if (file && file.type.startsWith("image/")) {
        img.src = URL.createObjectURL(file);
        img.style.display = "block";

        // AUTO HIDE ERROR IF EXISTS
        const errorBox = document.getElementById("uploadError");
        if (errorBox) {
            errorBox.style.display = "none";
        }
    } else {
        img.style.display = "none";
    }
}

document.getElementById("frontImg").addEventListener("change", function () {
    loadPreview(this, "frontPreview");
});

document.getElementById("backImg").addEventListener("change", function () {
    loadPreview(this, "backPreview");
});

/* -------- RESET FORM -------- */
document.getElementById("bookForm").addEventListener("reset", function () {
    setTimeout(() => {
        document.getElementById("frontPreview").style.display = "none";
        document.getElementById("backPreview").style.display = "none";

        const errorBox = document.getElementById("uploadError");
        if (errorBox) {
            errorBox.style.display = "none";
        }
    }, 0);
});
</script>

</body>
</html>