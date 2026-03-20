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

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #0288d1, #fad0c4);
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 25px;
            width: 100%;
            max-width: 600px;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        h2 {
            margin-top: 0;
            font-size: 24px;
            text-align: center;
            color: #2575fc;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
            color: #333;
        }

        input[type="text"],
        select,
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
            padding: 10px;
        }

        .img-row {
            display: flex;
            gap: 12px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .img-box {
            flex: 1;
            min-width: 140px;
            background: #e0f0ff;
            border: 1px dashed #bbb;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            transition: 0.3s;
        }

        .img-box img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 6px;
            display: block;
            margin-top: 10px;
        }

        .buttons {
            margin-top: 20px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        button {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            min-width: 120px;
            transition: .3s;
        }

        .btn-update {
            background: #2575fc;
            color: #fff;
        }

        .btn-update:hover {
            background: #1a5fc8;
        }

        .btn-reset {
            background: #e3e3e3;
        }

        .btn-reset:hover {
            background: #cfcfcf;
        }

        .btn-cancel {
            background: #dc3545;
            color: white;
        }

        .btn-cancel:hover {
            background: #a71d2a;
        }

        @media (max-width: 500px) {
            .img-row {
                flex-direction: column;
            }
        }
    </style>
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
