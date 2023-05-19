<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<title>Eduverse</title>
</head>
<body>
<!---------------------------NAV-------------------------------------------------->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="font-size: large; ">
<a class="logo navbar-brand" href="#"><img src="./imgs/logo_transparent.png" style="width: 150px; height: 150px;" alt="Logo"></a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse justify-content-center" id="navbarNav">
<ul class="navbar-nav">
<li class="nav-item active">
<a class="nav-link" href="#">Home</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#">About</a>
</li>
<li class="nav-item">
<a class="nav-link" href="./catalog.php">catalog</a>
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
<!---------------------------LOGIN-------------------------------------------------->
<?php
require_once './db.php';
session_start();

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
<!---------------------------SECTION1-------------------------------------------------->

<main>
<div class="container" style="margin-top: 9rem; margin-bottom: 9rem;">
<div class="row">
<div class="col-md-6">
<h1 style="font-weight: bolder; font-size:1.5cm;">EduVerse For All</h1><br>
<p style="font-weight: light; font-size: 0.90cm;">Do not look any further, we’ve got what you’re looking for</p><br>
<a href="./catalog.php">
<button type="button" id="btn" class="btn text-light" style=" font-size:larger; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
View Courses</button></a>
</div>
<div class="col-md-6">
<img src="./imgs/svg1.jpg" class="float-right" style="width: 100%; height:auto;" alt="Your Image">
</div>
</div>
</div>
</main>

<!---------------------------STYLE-------------------------------------------------->

<style>
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

h1 {
background-image: linear-gradient(to right, #6a7df1, #00eda4);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
}
#btn{
background-image: linear-gradient(to right, #6a7df1, #00eda4);

}
#btn:hover{
background-image: linear-gradient(to right, #00eda4, #6a7df1);
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