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
<!---------------------------SECTION1-------------------------------------------------->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="font-size: large;  margin-left: 0;">
<a class="logo navbar-brand" href="./home.php"><img src="./imgs/logo_transparent.png" style="width: 150px; height: 150px;" alt="Logo"></a>
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
<form method="get" action="">
<div class="search-wrapper">
<input type="text" placeholder="Search..." name="search" id="search-input"/>
<button type="submit" class="search"></button>
</div>
</form>

<div class="navbar-nav">
<div class="dropdown mr-auto">
<button class="btn nav-link btn dropdown-toggle" id="loginBtn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
<img src="./imgs/edit.png" alt="Lock icon" style="width: 30px; height: 30px; margin-right: 5px; filter: invert(100%);">
</button>
<ul class="dropdown-menu" aria-labelledby="loginBtn">
<li><a class="dropdown-item" href="./profile.php">Edit Profile</a></li>
<li><a class="dropdown-item" href="./inscriptions.php">My Inscriptions</a></li>
<li><hr class="dropdown-divider"></li>
<li><a class="dropdown-item" href="./logOut.php">Log Out</a></li>
</ul>
</div>
</div>
</nav>
<!---------------------------SECTION1-------------------------------------------------->
<?php
include_once './db.php';
session_start();

if (isset($_SESSION["id_training"])) {
exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM training WHERE 1=1";

if (!empty($search)) {
$sql .= " AND (subject LIKE CONCAT('%', ?, '%') OR category LIKE CONCAT('%', ?, '%'))";
$params = array($search, $search);
} else {
$params = array();
}

try {
$sql = "SELECT * FROM training WHERE 1=1";

if (!empty($search)) {
$sql .= " AND (subject LIKE CONCAT('%', ?, '%') OR category LIKE CONCAT('%', ?, '%'))";
$params = array($search, $search);
} else {
$params = array();
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Fetch all the matching results as an associative array
$trainings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
echo "Error fetching trainings: " . $e->getMessage();
}
?>

<!-- Iterate through the matching results and display them as cards using HTML and CSS -->
<main style="margin-right: 2cm;">
<div class="container-fluid">
<div class="row justify-content-center" style="padding-bottom: 5em; padding-top: 3em; margin-left: 50px;">
<?php if (!empty($trainings)): ?>
<?php foreach ($trainings as $training): ?>
<div class="col-md-4 my-3" >
<div class="card" style="background-image: url('./imgs/yaa.jpg');background-repeat: no-repeat;background-size: cover;position:relative; height: 105%">
<div class="card-content">
<div class="card-body">
<h5 class="card-title text-center" style="font-weight: 900;"><?php echo $training['subject']; ?></h5>
<p class="card-text text-center" style="font-weight: 700;">category: <?php echo $training['category']; ?></p>
<p class="card-text text-center">Hourly rate: <?php echo $training['hourly_rate']; ?></p>
<a href="./details.php?id_training=<?php echo $training['id_training']; ?>">
<button id="addBtn_<?php echo $training['id_training']; ?>"
class="addBtn btn"
style="float:right;
border-radius:10px;
box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
width: 30%;
background-color: #00eda4;
color:#fff">
<i class="fas fa-plus"></i> Read More
</button>
</a>
</div>
</div>
</div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="col-md-12 my-2">
<p>No trainings found</p>
</div>
<?php endif; ?>
</div>
</div>
</main>




<!---------------------------SECTION1-------------------------------------------------->

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
.dropdown {
margin-right: 160px;
}
.card::before {
  content: "";
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: white;
  opacity: 0.5;
}
.card-content {
  position: relative; 
  z-index: 1; }
.card:hover {
box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}
.card-title:hover,
.addBtn:hover {
cursor: pointer;
background-image: linear-gradient(to right, #6a7df1, #00eda4);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
}

.btn{
margin-right: 1rem;
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

.search-wrapper {
position: absolute;
margin: auto;
top: 100%;
left: 0;
right: 0;
bottom: 0;
width: 300px;
height: 100px;
}
.search {
position: absolute;
margin: auto;
top: 0;
right: 0;
bottom: 0;
left: 0;
width: 80px;
height: 80px;
background:  #6a7df1;
border-radius: 50%;
transition: all 1s;
z-index: 4;
box-shadow: 0 0 25px 0 rgba(0, 0, 0, 0.4);
}
.search:hover {
cursor: pointer;
}
.search::before {
content: "";
position: absolute;
margin: auto;
top: 22px;
right: 0;
bottom: 0;
left: 22px;
width: 12px;
height: 2px;
background: white;
transform: rotate(45deg);
transition: all .5s;
}
.search::after {
content: "";
position: absolute;
margin: auto;
top: -5px;
right: 0;
bottom: 0;
left: -5px;
width: 25px;
height: 25px;
border-radius: 50%;
border: 2px solid white;
transition: all .5s;
}
input {
font-family: 'jost', monospace;
position: absolute;
margin: auto;
top: 0;
right: 0;
bottom: 0;
left: 0;
width: 50px;
height: 50px;
outline: none;
border: none;
background:  #303030;
color: white;
text-shadow: 0 0 10px  #303030;
padding: 0 80px 0 20px;
border-radius: 30px;
box-shadow: 0 0 25px 0 #303030,
0 20px 25px 0 rgba(0, 0, 0, 0.2);
transition: all 1s;
opacity: 0;
z-index: 5;
font-weight: bolder;
letter-spacing: 0.1em;
}
input:hover {
cursor: pointer;
}
input:focus {
width: 300px;
opacity: 1;
cursor: text;
}
input:focus ~ .search {
right: -250px;
background: #151515;
z-index: 6;
}
input:focus ~ .search::before {
top: 0;
left: 0;
width: 25px;
}
input:focus ~ .search::after {
top: 0;
left: 0;
width: 25px;
height: 2px;
border: none;
background: white;
border-radius: 0%;
transform: rotate(-45deg);
}
input::placeholder {
color: white;
opacity: 0.5;
font-weight: bolder;
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