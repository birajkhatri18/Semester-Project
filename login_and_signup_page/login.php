<?php
session_start();
include("config.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

  $email    = mysqli_real_escape_string($conn, $_POST['email']);
  $password = $_POST['password'];

  $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
  $row = mysqli_fetch_assoc($result);

  if($row && password_verify($password, $row['password'])){

    // STORE FULL USER DETAILS IN SESSION
    $_SESSION['email'] = $row['email'];
    $_SESSION['firstname'] = $row['firstname'];
    $_SESSION['lastname']  = $row['lastname'];

    // IMPORTANT → store user ID correctly
    // Check which column exists in your database: Id or id
    $_SESSION['user_id'] = isset($row['Id']) ? $row['Id'] : $row['id'];

    header("Location: ../homepage/home.php");
    exit();

  } else {

    $_SESSION['error'] = "Invalid Email or Password!!";
    header("Location: login.php");
    exit();
  }
}

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']); 
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="login.css">
</head>

<body>

  <div class="login-box" id="login">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
    <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- <?php if(isset($error)) echo "<p class='error'>$error</p>";?> -->
    <form method="post" >
      <div class="form-group">
        <label for="Email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your Email" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your Password"  required>
      </div>
      <button type="submit" name="login" class="login-btn">Login</button>
      <div class="signup">
        Don’t have an account? <a href="signup.php">Sign Up</a>
        <a href="../index.html">Back</a>
      </div>
    </form>
  </div>

</body>

</html>