<?php
include_once './db.php';
session_start();

if (isset($_SESSION["id_appr"])) {
exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$phone = trim($_POST['phone']);


$error = '';

$name_regex = '/^.{1,30}$/';
$email_regex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
$password_regex = '/^.{8,10}$/';
$phone_regex = '/^[0-9]{10}$/';

if (empty($name) || empty($email) || empty($password) || empty($phone)) {
$error = 'Please enter all the values';
} elseif (!preg_match($name_regex, $name)) {
$error = 'Invalid name format';
} elseif (!preg_match($email_regex, $email)) {
$error = 'Invalid email format';
} elseif (!preg_match($password_regex, $_POST['password'])) {
$error = 'Invalid password format';
} elseif (!preg_match($phone_regex, $phone)) {
$error = 'Invalid phone number format';
} else {
// Check if the email already exists in the database
$select = "SELECT * FROM apprentice WHERE email = :email";
$stmt = $pdo->prepare($select);
$stmt->execute(['email' => $email]);

if ($stmt->rowCount() > 1) {
$error = 'Email already exist';
} else {
// Insert data into the database
$insert = "INSERT INTO apprentice (name,  email, password, phone) VALUES (:name,  :email, :password, :phone)";
$stmt = $pdo->prepare($insert);
$stmt->execute([
'name' => $name,
'email' => $email,
'password' => $password,
'phone' => $phone,
]); 


// Check if the insertion was successful
if ($stmt->rowCount() > 0) {
$_SESSION["id_appr"] = $pdo->lastInsertId();
header("location: ./catalog.php");
exit(); // Exit the script after redirecting
} else {
$error = 'Failed to register apprentice';
}
}
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Roboto:wght@500&display=swap" rel="stylesheet"/>
<title>Eduverse</title>
</head>

<body>

<!--------------------------------------NAV------------------------------------------->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="font-size: large;">
<a class="logo navbar-brand" href="#"><img src="./imgs/logo_transparent.png" style="width: 150px; height: 150px;" alt="Logo"></a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse justify-content-center" id="navbarNav">
<ul class="navbar-nav">
<li class="nav-item active">
<a class="nav-link" href="./home.php">Home</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#">About</a>
</li>
<li class="nav-item">
<a class="nav-link" href="catalog.php">catalog</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#">Contact</a>
</li>
</ul>
</div>
<div class="navbar-nav">
<a class="btn nav-link btn mr-2" id="loginBtn">
<img src="./imgs/open-padlock.png" alt="Lock icon" style="width: 22px; height: 22px; margin-right: 5px; filter: invert(100%);">
Log in
</a>
<a class="btn nav-link btn btn-outline-light mr-2" href="./signUp.php">
<img src="./imgs/profile.png" alt="profile icon" style="width: 22px; height: 22px; margin-right: 5px; filter: invert(17%) sepia(97%) saturate(7677%) hue-rotate(169deg) brightness(118%) contrast(120%);">
Register</a>
</div>
</nav>
<!--------------------------------------FORM------------------------------------------->

<section class="login-form">
<form method="post">
<h1 class="text-center mt-3">Welcome to <strong style="color:#6a7df1">Eduverse</strong></h1>
<label for="name">Full Name</label>
<input type="text" name="name" id="name" placeholder="Enter your full name" value="<?php if(isset($_POST['name'])){ echo $_POST['name'];}?>" required/>
<label for="email">Email</label>
<input type="email" name="email" id="email" placeholder="Enter your email address" value="<?php if(isset($_POST['email'])){ echo $_POST['email'];}?>" required/>
<label for="phone">phone</label>
<input type="number" name="phone" id="phone" placeholder="Enter your phone number" value="<?php if(isset($_POST['phone'])){ echo $_POST['phone'];}?>" required/>
<label for="password">Password</label>
<input type="password" name="password" id="password" placeholder="Enter your password" value="<?php if(isset($_POST['password'])){ echo $_POST['password'];}?>" required/>
<p class="text-danger errorMsg"><?php if (isset($error)) {echo $error;} ?></p>
<button class="loginBtn" name="loginBtn">Sign Up</button>
</form>
</section>
<!--------------------------------------FORM------------------------------------------->
<?php

// Check if user is already logged in and redirect to catalog page
if (isset($_SESSION["id_appr"])) {
header('Location: catalog.php');
exit();
}

$error = '';

if (isset($_POST['loginBtn'])) {
if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirmPassword'])) {
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

if ($password != $confirmPassword) {
$error = 'Passwords do not match';
} else {
$query = "SELECT * FROM `apprentice` WHERE email=:email";
$stmt = $pdo->prepare($query);
$stmt->execute(['email' => $email]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
$id_appr = $result['id_appr'];
$name = $result['name'];
$foundEmail = $result['email'];
$passwrd = $result['password'];

if ($password != $passwrd) {
$error = 'Wrong password';
} else {
session_regenerate_id();
$_SESSION["id_appr"] = $id_appr;
$_SESSION["name"] = $name;
$_SESSION["email"] = $foundEmail;

// Redirect to catalog page.
header("Location: catalog.php");
exit();
}
} else {
$error = 'Email does not exist';
}
}
} else {
$error = 'Please fill all inputs';
}
}
?>

<div id="myModal" class="modal">
<div class="modal-content">
<span class="close">&times;</span>
<h2>Welcome! <strong style="color:#6a7df1">LOGIN</strong></h2>
<?php if (!empty($error)): ?>
<div class="error" style="color:RED; font-weight:400"><?php echo $error; ?></div>
<?php endif; ?>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
<label for="email">Email</label>
<input type="email" placeholder="Enter your email address" id="email" name="email" required><br><br>
<label for="password">Password</label>
<input type="password" placeholder="Enter your password" id="password" name="password" required><br><br>
<label for="password">Confirm Password</label>
<input placeholder="Confirm Your password" type="password" name="confirmPassword" required><br><br>
<input type="submit" name="loginBtn"></input>
</form>
</div>
</div>

<script>
var modal = document.getElementById("myModal");
var btn = document.getElementById("loginBtn");
var span = document.getElementsByClassName("close")[0];
btn.onclick = function() {
modal.style.display = "block";
}
span.onclick = function() {
modal.style.display = "none";
}
window.onclick = function(event) {
if (event.target == modal) {
modal.style.display = "none";
}
}
</script>
<style>
.login-form {
display: block;
left: 0;
top: 0;
width: 100%;
height: 100%;
overflow: auto;
background-color: rgba(0, 0, 0, 0.6);
}

.login-form form {
background-color: #fff;
margin: 10% auto;
padding: 20px;
border: 1px solid #888;
width: 30%;
height: 70%;
border-radius: 10px;
box-shadow: 0 0 20px rgba(0, 0, 0, 0.3), 0 0 10px rgba(0, 0, 0, 0.2);
color: #000;
font-weight: 300;
}
.login-form .cancelBtn {
float: right;
font-size: 28px;
font-weight: bold;
background-color: transparent;
}
.login-form .cancelBtn:hover,
.login-form .cancelBtn:focus {
color: black;
text-decoration: none;
cursor: pointer;
}

.login-form h1 {
text-align: center;
opacity: 0.6;
}

.login-form input[type="text"],
.login-form input[type="email"],
.login-form input[type="password"],
.login-form input[type="number"] {
display: block;
width: 100%;
padding: 8px;
border-radius: 25px;
border: none;
border-bottom: 2px solid #969696;
margin-bottom: 30px;
background-color: transparent;
transition: border-bottom 0.4s ease-in-out;
}

.login-form input[type="text"]:hover,
.login-form input[type="email"]:hover,
.login-form input[type="password"]:hover,
.login-form input[type="number"]:hover {
border-bottom-color: #00eda4;
}

.login-form .errorMsg {
margin-top: -10px;
margin-bottom: 10px;
}

.login-form .loginBtn {
background-color: #00eda4;
color: #fff;
cursor: pointer;
width: 40%;
margin: 0 auto;
display: block;
padding: 10px 20px;
border-radius: 25px;
border: none;
font-size: 1.1rem;
margin-top: 20px;
margin-bottom: 10px;
transition: all 0.3s ease-in-out;
}
.login-form .loginBtn:hover {
background-color: #6a7df1;
color: #fff;
border: 2px solid #6a7df1;
transform: translateY(-5px);

}

.login-form .toSigneUp {
font-size: 1rem;
color: #C19A6B;
text-align: center;
}

.login-form .blue {
font-size: 1rem;
color: #00eda4;
}
*{
padding: 0;
margin: 0;
box-sizing: border-box;
font-family: 'jost', sans-serif;
list-style: none;
text-decoration: none;
scroll-behavior: smooth;
}
.btn{
margin-right: 3rem;
}
.logo{
margin-left: 3rem;
}
.navbar-nav .nav-item .nav-link:hover {
color: #00eda4;
}
.nav-link.btn-light {
color: #969696;
}
.nav-link.btn-outline-light {
border: 2px solid #00eda4;
color: #00eda4;
}
.nav-link.btn:hover {
border: 2px solid #00eda4;
color: #00eda4;
}
.nav-link.btn-outline-light:hover {
background-color: #00eda4;
color: #fff;
}

.modal {
display: none;
position: fixed;
z-index: 1;
left: 0;
top: 0;
width: 100%;
height: 100%;
overflow: auto;
background-color: rgba(0, 0, 0, 0.6);
}

.modal-content {
background-color: #fff;
margin: 5% auto;
padding: 20px;
border: 1px solid #888;
width: 30%;
height: 70%;
border-radius: 10px;
box-shadow: 0 0 20px rgba(0, 0, 0, 0.3), 0 0 10px rgba(0, 0, 0, 0.2);
color: #000;
}

.close {
color: #aaa;
float: right;
font-size: 28px;
font-weight: bold;
}

.close:hover,
.close:focus {
color: black;
text-decoration: none;
cursor: pointer;
}

h2 {
text-align: center;
}

label {
display: block;
margin-bottom: 5px;
}

input[type="email"],
input[type="password"],
input[type="submit"] {
display: block;
width: 100%;
padding: 8px;
border-radius: 4px;
border: none;
margin-bottom: 8px;
background-color: transparent;
border-bottom: 2px solid #969696;
transition: border-bottom 0.4s ease-in-out;
}
input[type="email"]:hover,
input[type="password"]:hover,
input[type="submit"]:hover {
border-bottom-color: #00eda4;
}
input[type="submit"] {
background-color: #00eda4;
color: #fff;
cursor: pointer;
width: 30%;
margin: 0 auto;
transition: all 0.3s ease-in-out;
}
input[type="submit"]:hover{
background-color: #6a7df1;
color: #fff;
border: 2px solid #6a7df1;
transform: translateY(-5px);
}
/* Target the icon elements inside the anchor tags */
.col-lg-2 a i,
.col-md-6 a i {
transition: color 0.3s ease; /* Add smooth transition effect */
}

/* Change the color of the icons on hover */
.col-lg-2 a:hover i,
.col-md-6 a:hover i {
color: #00eda4;
}
.text-light {
text-decoration: none;
}

</style>

<footer class="bg-dark text-light">
<div class="container py-5">
<div class="row">
<div class="col-lg-4 col-md-6 mb-4 mb-md-0">
<h2 class="text-uppercase mb-4"><strong style="background-image: linear-gradient(to right, #6a7df1, #00eda4);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;">EduVerse</strong> </h2>
<p>
This is some example text for the footer content.
</p>
</div>
<div class="col-lg-3 col-md-6 mb-4 mb-md-0">
<h5 class="text-uppercase mb-4">Links</h5>
<ul class="list-unstyled">
<li>
<a href="#" class="text-light">https://simplonline.co/1</a>
</li>
<li>
<a href="#" class="text-light">https://solicode.co/</a>
</li>
<li>
<a href="#" class="text-light">https://www.ofppt.ma/</a>
</li>
</ul>
</div>
<div class="col-lg-3 col-md-6 mb-4 mb-md-0">
<h5 class="text-uppercase mb-4">Contact</h5>
<ul class="list-unstyled">
<li>
<a href="#" class="text-light">aouad.fatima.z.solicode@gmail.com</a>
</li>
<li>
+212 697 257 7177
</li>
<li>
xyz example address
</li>
</ul>
</div>
<div class="col-lg-2 col-md-6 mb-4 mb-md-0">
<h5 class="text-uppercase mb-4">Social</h5>
<ul class="list-unstyled">
<li>
<a href="#" class="text-light"><i class="fab fa-facebook-f"></i> </a>
</li>
<li>
<a href="" class="text-light"><i class="fab fa-twitter"></i> </a>
</li>
<li>
<a href="#" class="text-light"><i class="fab fa-linkedin-in"></i> </a>
</li>
<li>
<a href="#" class="text-light"><i class="fab fa-instagram"></i> </a>
</li>
</ul>
</div>
</div>
</div>
<div class="bg-dark text-center py-3">Copyright
&copy; EduVerse 2023
</div>
</footer>
</body>
</html>

