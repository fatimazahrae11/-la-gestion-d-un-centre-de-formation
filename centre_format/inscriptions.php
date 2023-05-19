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
<div class="navbar-nav">
<div class="dropdown mr-auto">
<button class="btn nav-link btn mr-2 dropdown-toggle" id="loginBtn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
<img src="./imgs/edit.png" alt="Lock icon" style="width: 30px; height: 30px; margin-right: 5px; filter: invert(100%);">
</button>
<ul class="dropdown-menu" aria-labelledby="loginBtn">
<li><a class="dropdown-item" href="<?php echo isset($_SESSION["id_appr"]) ? './profile.php' : './signUp.php'; ?>">Edit Profile</a></li>
<li><a class="dropdown-item" href="./inscriptions.php">My Inscriptions</a></li>
<li><hr class="dropdown-divider"></li>
<li><a class="dropdown-item" href="./logOut.php">Log Out</a></li>
</ul>
</div>
</div>
</nav>
<!---------------------------PHP-------------------------------------------------->
<?php 
include_once './db.php';
session_start();
if (!isset($_SESSION['id_appr'])) {
exit();
}

$id_appr = $_SESSION['id_appr'];


$stmt = $pdo->prepare(
"SELECT start_date, end_date, t.tr_name, s.id_session, r.validate
FROM sessions s
INNER JOIN registration r ON s.id_session = r.id_session 
INNER JOIN trainer t ON s.id_trainer = t.id_trainer 
WHERE r.id_appr = ?"
);
$stmt->execute([$id_appr]);
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<table>
<tr>
<th>Start Date</th>
<th>End Date</th>
<th>Trainer Name</th>
<th>Validation</th>

</tr>
<?php foreach ($sessions as $session): ?>
<tr>
<td><?php echo $session['start_date']; ?></td>
<td><?php echo $session['end_date']; ?></td>
<td><?php echo $session['tr_name']; ?></td>
</td>
<td>//</td>
</tr>
<?php endforeach; ?>
</table>

</table>















<!---------------------------FOOTER-------------------------------------------------->
<footer class="bg-dark text-light" style="margin-top: 10%;">
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
/* Define styles for the table */
table {
border-collapse: collapse;
width: 100%;
font-family: Arial, sans-serif;
font-size: 14px;
margin-top: 10%;
}

th, td {
text-align: left;
padding: 8px;
}

tr:nth-child(even) {
background-color: #f2f2f2;
}

th {
background-color: #00eda4;
color: white;
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
</body>
</html>