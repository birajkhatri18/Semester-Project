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

// Check if POST data exists
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = intval($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);
    $language = $conn->real_escape_string($_POST['language']);
    $type = $conn->real_escape_string($_POST['type']);
    $condition = $conn->real_escape_string($_POST['condition']);
    $description = $conn->real_escape_string($_POST['description']);

    // Get existing images
    $sql = "SELECT front_img, back_img FROM books WHERE id = $post_id AND user_id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows === 0) {
        header("Location: my_post.php");
        exit();
    }

    $post = $result->fetch_assoc();
    $front_img = $post['front_img'];
    $back_img = $post['back_img'];

    // Handle front image upload
    if (!empty($_FILES['front_img']['name'])) {
        $front_tmp = $_FILES['front_img']['tmp_name'];
        $front_name = 'uploads/' . time() . '_front_' . basename($_FILES['front_img']['name']);
        if (move_uploaded_file($front_tmp, $front_name)) {
            $front_img = $front_name;
        }
    }

    // Handle back image upload
    if (!empty($_FILES['back_img']['name'])) {
        $back_tmp = $_FILES['back_img']['tmp_name'];
        $back_name = 'uploads/' . time() . '_back_' . basename($_FILES['back_img']['name']);
        if (move_uploaded_file($back_tmp, $back_name)) {
            $back_img = $back_name;
        }
    }

    // Update the post in DB
    $update_sql = "UPDATE books SET 
        title='$title', 
        language='$language', 
        type='$type', 
        book_condition='$condition', 
        description='$description', 
        front_img='$front_img', 
        back_img='$back_img' 
        WHERE id=$post_id AND user_id=$user_id";

    $conn->query($update_sql);

    // Redirect silently to my_post.php
    header("Location: my_post.php");
    exit();
} else {
    header("Location: my_post.php");
    exit();
}
?>
