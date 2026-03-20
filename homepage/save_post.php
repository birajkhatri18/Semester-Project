<?php
session_start();

/* ---------- AUTH CHECK ---------- */
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    die("Error: user_id not found in session.");
}

/* ---------- DB CONNECTION ---------- */
$conn = new mysqli("localhost", "root", "", "user_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* ---------- RESET ERROR ---------- */
$_SESSION['upload_error'] = "";

/* ---------- IMAGE UPLOAD FUNCTION ---------- */
function uploadImage($file, $upload_dir)
{
    $allowed_types = ["image/jpeg", "image/png", "image/gif", "image/webp"];
    $allowed_ext   = ["jpg", "jpeg", "png", "gif", "webp"];
    $max_size      = 2 * 1024 * 1024; // 2MB

    if ($file["error"] !== 0) {
        $_SESSION['upload_error'] = "Image upload error.";
        return false;
    }

    if ($file["size"] > $max_size) {
        $_SESSION['upload_error'] = "Image size must be less than 2MB.";
        return false;
    }

    $mime = mime_content_type($file["tmp_name"]);
    if (!in_array($mime, $allowed_types)) {
        $_SESSION['upload_error'] =
            "Only image files are allowed (JPG, PNG, GIF, WEBP).";
        return false;
    }

    $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) {
        $_SESSION['upload_error'] = "Invalid image file type.";
        return false;
    }

    $new_name = time() . "_" . uniqid() . "." . $ext;
    $path = $upload_dir . $new_name;

    if (move_uploaded_file($file["tmp_name"], $path)) {
        return $path;
    }

    $_SESSION['upload_error'] = "Failed to upload image.";
    return false;
}

/* ---------- SAVE POST ---------- */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title          = $conn->real_escape_string($_POST["title"]);
    $language       = $conn->real_escape_string($_POST["language"]);
    $type           = $conn->real_escape_string($_POST["type"]);
    $book_condition = $conn->real_escape_string($_POST["condition"]);
    $description    = $conn->real_escape_string($_POST["description"]);
    $user_id        = $_SESSION['user_id'];

    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $front_img = "";
    $back_img  = "";

    if (!empty($_FILES["front_img"]["name"])) {
        $front_img = uploadImage($_FILES["front_img"], $upload_dir);
        if ($front_img === false) {
            header("Location: create_post.php");
            exit();
        }
    }

    if (!empty($_FILES["back_img"]["name"])) {
        $back_img = uploadImage($_FILES["back_img"], $upload_dir);
        if ($back_img === false) {
            header("Location: create_post.php");
            exit();
        }
    }

    $sql = "INSERT INTO books
        (title, language, type, book_condition, description, front_img, back_img, user_id, created_at)
        VALUES
        ('$title', '$language', '$type', '$book_condition', '$description', '$front_img', '$back_img', '$user_id', NOW())";

    if ($conn->query($sql)) {
        header("Location: home.php");
        exit();
    } else {
        $_SESSION['upload_error'] = "Database error.";
        header("Location: create_post.php");
        exit();
    }
}
?>