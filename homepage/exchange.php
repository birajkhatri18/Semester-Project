<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","user_system");
if ($conn->connect_error) die("DB Error");

$user_id = $_SESSION['user_id'];

/* Book user wants */
$receiver_book_id = intval($_GET['id']);

$target = $conn->query("
    SELECT b.*, u.firstname, u.lastname 
    FROM books b 
    JOIN users u ON b.user_id = u.id
    WHERE b.id = $receiver_book_id
")->fetch_assoc();

/* IMPORTANT: receiver (book owner) */
$receiver_id = $target['user_id'];

/* User's own books */
$myBooks = $conn->query("
    SELECT * FROM books 
    WHERE user_id = $user_id AND exchanged = 0
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Exchange Book</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
/* YOUR ORIGINAL STYLE — UNCHANGED */
body { margin:0; font-family:Arial; background:#f4f6f7; }
.navbar {
    height:70px; background:linear-gradient(to right,#6a11cb,#2575fc);
    display:flex; justify-content:space-between; align-items:center;
    padding:0 30px; color:white;
}
.logo {
    font-size: 32px;
    font-weight: 900;
}
.logo span {
    background:white; color:#2575fc;
    padding:6px 12px; border-radius:10px;
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
.post {
    background:#fff; padding:20px; border-radius:12px;
    box-shadow:0 3px 12px rgba(0,0,0,.1);
    border-left:5px solid #2575fc;
}
select,input { width:100%; padding:10px; margin-top:8px; }
.btn {
    margin-top:20px; padding:12px;
    background:#2575fc; color:white;
    border:none; width:100%; border-radius:8px;
}
.popup-bg {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,.5);
    justify-content:center; align-items:center;
}
.popup-box {
    background:white; padding:25px;
    width:320px; border-radius:10px;
    text-align:center;
}

/*for auto close*/
@keyframes pop {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

</style>
</head>

<body>

<div class="navbar">
    <div class="logo"><span>Swaply</span></div>
    <a href="home.php" style="color:white;" class="back-btn">Back</a>
</div>

<div class="container">

<div class="post">
<h3>Book You Want</h3>
<p><b><?= htmlspecialchars($target['title']) ?></b></p>
<p><?= htmlspecialchars($target['language']) ?> | <?= htmlspecialchars($target['type']) ?></p>
</div>

<div class="post">
<h3>Select Your Book</h3>

<select id="myBook">
<option value="">-- Select Your Book --</option>

<?php while($b = $myBooks->fetch_assoc()): ?>
<option value="<?= htmlspecialchars(json_encode($b), ENT_QUOTES, 'UTF-8') ?>">
    <?= htmlspecialchars($b['title']) ?>
</option>
<?php endwhile; ?>

</select>

<div id="details" style="display:none;margin-top:15px;">
<p id="d_title"></p>
<p id="d_lang"></p>
<p id="d_type"></p>
<p id="d_cond"></p>
<p id="d_desc"></p>

<button class="btn" onclick="openPopup()">Send Request</button>
</div>
</div>
</div>

<!-- POPUP -->
<div class="popup-bg" id="popup">
<div class="popup-box">
<h3>Confirm Exchange</h3>
<input type="text" id="phone" placeholder="10-digit phone" maxlength="10">
<br><br>
<button class="btn" onclick="sendRequest()">Confirm</button>
<button class="btn" style="background:#dc3545" onclick="closePopup()">Cancel</button>
</div>
</div>

<script>
let selectedBook;

document.getElementById("myBook").addEventListener("change", function(){
    if(!this.value) return;

    selectedBook = JSON.parse(this.value);

    document.getElementById("details").style.display="block";
    d_title.innerHTML="<b>Title:</b> "+selectedBook.title;
    d_lang.innerHTML="<b>Language:</b> "+selectedBook.language;
    d_type.innerHTML="<b>Type:</b> "+selectedBook.type;
    d_cond.innerHTML="<b>Condition:</b> "+selectedBook.book_condition;
    d_desc.innerHTML="<b>Description:</b> "+selectedBook.description;
});

function openPopup(){
    popup.style.display="flex";
}

function sendRequest(){
    let phone = document.getElementById("phone").value;
    if(!/^\d{10}$/.test(phone)){
        alert("Phone must be 10 digits");
        return;
    }

    fetch("save_exchange_request.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:
        `sender_book_id=${selectedBook.id}
        &receiver_book_id=<?= $receiver_book_id ?>
        &receiver_id=<?= $receiver_id ?>
        &sender_phone=${phone}`
    })
    .then(res => res.text())
    .then(data => {
    const box = document.querySelector(".popup-box");

    box.innerHTML = `
        <h3 style="color:#28a745; animation: pop .4s ease;">Request Sent ✅</h3>
        <p style="margin-top:10px;color:#555;">
            Your exchange request has been sent successfully.
        </p>
        <p style="font-size:13px;color:#888;margin-top:10px;">
            Redirecting to home...
        </p>
    `;

    setTimeout(() => {
        location.href = "home.php";
    }, 2000);
});

}
function closePopup(){
    popup.style.display = "none";
    document.getElementById("phone").value = "";
}

</script>

</body>
</html>
