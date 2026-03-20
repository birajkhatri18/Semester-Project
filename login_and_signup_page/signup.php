<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Save old values (except confirm password)
    $_SESSION['old'] = [
        'firstname' => $_POST['firstname'],
        'lastname'  => $_POST['lastname'],
        'email'     => $_POST['email'],
        'password'  => $_POST['password']
    ];

    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname  = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);

    $password_raw = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ✅ Password must contain letters AND numbers
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $password_raw)) {
        $_SESSION['password_error'] = "Password must contain letters and numbers and be at least 6 characters.";
        header("Location: signup.php");
        exit();
    }

    // Confirm password check
    if ($password_raw !== $confirm_password) {
        $_SESSION['confirm_error'] = "Passwords do not match!";
        header("Location: signup.php");
        exit();
    }

    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    $checkUser = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($checkUser) > 0) {
        $_SESSION['error'] = "Email already exists!!";
        header("Location: signup.php");
        exit();
    }

    $sql = "INSERT INTO users (firstname, lastname, email, password)
            VALUES ('$firstname', '$lastname', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        unset($_SESSION['old']);
        $_SESSION['email'] = $email;
        header("Location: login.php");
        exit();
    }
}

// Errors
$error = $_SESSION['error'] ?? "";
$confirmError = $_SESSION['confirm_error'] ?? "";
$passwordError = $_SESSION['password_error'] ?? "";

unset($_SESSION['error'], $_SESSION['confirm_error'], $_SESSION['password_error']);

// Old values
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sign Up</title>
<link rel="stylesheet" href="signUp.css">
</head>

<body>

<div class="signup-box">
<h2>Create Account</h2>

<?php if ($error): ?>
<p class="error"><?php echo $error; ?></p>
<?php endif; ?>

<form method="post" onsubmit="return validateForm();">

<div class="name-fields">
<div class="form-group">
<label>First Name:</label>
<input type="text" name="firstname"
value="<?php echo htmlspecialchars($old['firstname'] ?? ''); ?>" placeholder="First Name" required>
</div>

<div class="form-group">
<label>Last Name:</label>
<input type="text" name="lastname"
value="<?php echo htmlspecialchars($old['lastname'] ?? ''); ?>" placeholder="Last Name" required>
</div>
</div>

<div class="form-group">
<label>Email:</label>
<input type="email" id="email" name="email"
value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" placeholder="Email" required>

<div id="emailError" class="email-error">
Email must include at least one number and end with <strong>@gmail.com</strong>
</div>
</div>

<div class="form-group">
<label>Create Password:</label>
<input type="password" id="password" name="password"
value="<?php echo htmlspecialchars($old['password'] ?? ''); ?>" placeholder="Password" required>

<div id="passwordError" class="email-error">
Password must contain letters and numbers (minimum 6 characters)
</div>

<?php if ($passwordError): ?>
<p class="confirm-error"><?php echo $passwordError; ?></p>
<?php endif; ?>
</div>

<div class="form-group">
<label>Confirm Password:</label>
<input type="password" id="confirm_password" name="confirm_password" placeholder="Retype Password" required>

<?php if ($confirmError): ?>
<p class="confirm-error"><?php echo $confirmError; ?></p>
<?php endif; ?>
</div>

<button type="submit">Sign Up</button>
</form>

<div class="login-link">
Already have an account? <a href="login.php">Login</a>
</div>

</div>

<script>
function validateForm() {

  const emailInput = document.getElementById("email");
  const emailError = document.getElementById("emailError");
  const passwordInput = document.getElementById("password");
  const passwordError = document.getElementById("passwordError");

  const email = emailInput.value.trim();
  const password = passwordInput.value;

  const numberPattern = /[0-9]/;
  const endsWithGmail = email.endsWith("@gmail.com");

  const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d).{6,}$/;

  let valid = true;

  if (!numberPattern.test(email) || !endsWithGmail) {
    emailError.style.display = "block";
    emailInput.style.borderColor = "red";
    valid = false;
  } else {
    emailError.style.display = "none";
    emailInput.style.borderColor = "#ccc";
  }

  if (!passwordPattern.test(password)) {
    passwordError.style.display = "block";
    passwordInput.style.borderColor = "red";
    valid = false;
  } else {
    passwordError.style.display = "none";
    passwordInput.style.borderColor = "#ccc";
  }

  return valid;
}
</script>

</body>
</html>