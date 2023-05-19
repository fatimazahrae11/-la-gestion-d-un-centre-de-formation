<?php 
session_start();
include_once './db.php'; 

if (!isset($_SESSION['id_appr'])) {
exit();
}

$id_appr = $_SESSION['id_appr'];

// Fetch apprentice data from the database.
$stmt = $pdo->prepare("SELECT * FROM apprentice WHERE id_appr = :id");
$stmt->execute([
'id' => $id_appr,
]);

if ($stmt->rowCount() > 0) {
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$name = $row['name'];
$email = $row['email'];
$phone = $row['phone'];
}

if (isset($_POST['loginBtn'])) {

// Update apprentice data in the database.
$update_stmt = $pdo->prepare("UPDATE apprentice SET name=:name,
email=:email, phone=:phone  WHERE id_appr=:id");

$update_stmt->execute([
'name' => $_POST['name'],
'email' => $_POST['email'],
'phone' => $_POST['phone'],
'id' => $id_appr,
]);
// Redirect to the catalog page.
header("location: ./catalog.php");
exit();
}
?>
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
<link rel="stylesheet" href="./style.css">
<title>Eduverse</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="font-size: large;">
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
<a class="nav-link" href="catalog.php">catalog</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#">Contact</a>
</li>
</ul>
</div>
</nav>
<!---------------------------NAV-------------------------------------------------->

<section class="login-form" >
<form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
<span class="close">&times;</span>
<h1 class="text-center mt-3"><strong style="background-image: linear-gradient(to right, #6a7df1, #00eda4);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;"> Edit your profile</strong> </h1>
<input placeholder="Full Name" type="text" name="name" value="<?php echo $name; ?>" />
<input placeholder="Email" type="text" name="email" value="<?php echo $email; ?>" />
<input placeholder="Phone" type="text" name="phone" value="<?php echo $phone; ?>" />
<input type="submit" name="loginBtn"></input>
</form>
</section>
<!---------------------------NAV-------------------------------------------------->
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