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
    header('Location: signUp.php');
    exit();
}

if (!isset($_GET["id_training"])) {
    exit();
}

$id_training = $_GET["id_training"];

// Select session data and trainer name using id_training 
$stmt = $pdo->prepare(
    "SELECT sessions.start_date, sessions.end_date, sessions.state, 
    sessions.max_places, trainer.tr_name, sessions.id_session, registrants 
    FROM sessions 
    INNER JOIN trainer ON sessions.id_trainer = trainer.id_trainer 
    WHERE sessions.id_training = ?"
);

$stmt->execute([$id_training]);
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$id_appr = $_SESSION['id_appr'];

$stmt2 = $pdo->prepare("SELECT COUNT(*) AS count FROM registration r
                        INNER JOIN sessions s ON r.id_session = s.id_session
                        WHERE id_appr = ?
                        AND YEAR(s.start_date) = YEAR(CURRENT_DATE())");

$stmt2->execute([$id_appr]);
$result = $stmt2->fetch(PDO::FETCH_ASSOC);
$count = $result['count'];


//---FIRST CONDITION---
if (isset($_POST['register'])) {
    // Insert the registration into the registration table
    $stmt3 = $pdo->prepare(
        "INSERT INTO registration (id_appr, id_session, validate, inscrip_date) 
        VALUES (?, ?, ?, NOW())"
    );
    
    $stmt3->execute([$id_appr, $_POST['id_session'], 0]);
    
    $stmt5 = $pdo->prepare("SELECT registrants, max_places FROM sessions WHERE id_session = ?");
    $stmt5->execute([$_POST['id_session']]);
    $result = $stmt5->fetch(PDO::FETCH_ASSOC);
    
    $registrants = $result['registrants'];
    $max_places = $result['max_places'];
    
    // Check if there are available places for the session
    if ($registrants < $max_places) {
        // Increment the registrants value in the sessions table
        $new_registrants = $registrants + 1;
        
        if ($new_registrants <= $max_places) {
            $stmt6 = $pdo->prepare("UPDATE sessions SET registrants = ? WHERE id_session = ?");
            $stmt6->execute([$new_registrants, $_POST['id_session']]);
            
            // Check if the new value of registrants is equal to the max places for the session, then update the state column in the sessions table
            if ($new_registrants == $max_places) {
                $stmt7 = $pdo->prepare("UPDATE sessions SET state = 'inscription achevée' WHERE id_session = ?");
                $stmt7->execute([$_POST['id_session']]);
            }
        }
    }
    
    // Display a success message
    $success_url = "inscriptions.php?id_training={$id_training}";
    echo "<div class='alert alert-success' role='alert'>You have successfully registered for this session. <a href='{$success_url}'>Click here</a> to go to inscription page.</div>";

} elseif ($count >= 2) {
    //-----------------3RD CONDITION
    // Display an error message if the user has already registered for two sessions
    echo '<div class="alert alert-danger" role="alert">You have already registered for the maximum number of sessions (2).</div>';
}
?>


<!--------------------------------------------HTML------------------------------------------------------>
<h2 class="text-center mt-3">Sessions</h2>
<table>
<thead>
<tr>
<th>Start date</th>
<th>End date</th>
<th>State</th>
<th>Max places</th>
<th>Trainer</th>
<th>Registrants</th>
<th>Join Training</th>
</tr>
</thead>
<tbody>
<?php foreach ($sessions as $session): ?>
<tr>
<td><?php echo $session['start_date']; ?></td>
<td><?php echo $session['end_date']; ?></td>
<td><?php echo $session['state']; ?></td>
<td><?php echo $session['max_places']; ?></td>
<td><?php echo $session['tr_name']; ?></td>
<td><?php echo $session['registrants']; ?></td>
<td style="text-align: center;">
<?php if (!isset($_SESSION['id_appr'])): ?>
<button name="register" type="button" style="border-radius:10px;box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);width: 45%;background-color: #303030;margin-right: 10px;color:#fff" disabled="disabled" title="Please log in to join this training">
<?php echo "Join Training"; ?>
</button>
<?php elseif ($session['state'] === 'annulée' || $session['state'] === 'cloturée' || $session['state'] === 'inscription achevée' || $session['state'] === 'en cours'): ?>
<?php elseif ($session['start_date'] < '2023-01-01' || $session['end_date'] > '2023-12-31'): ?>
<!-- Do not display button for sessions outside date range -->
<?php else: ?>

<?php
// ---SECOND CONDITION---
$stmt4 = $pdo->prepare(
"SELECT * FROM registration r 
INNER JOIN sessions s ON r.id_session = s.id_session 
WHERE r.id_appr = ? 
AND ((s.start_date <= ? AND s.end_date >= ?) 
OR (s.start_date >= ? AND s.start_date <= ?)) 
AND s.id_session != ?");
$stmt4->execute([
$id_appr, 
$session['start_date'], 
$session['end_date'], 
$session['start_date'], 
$session['end_date'], 
$session['id_session']]);
$rows = $stmt4->fetchAll(PDO::FETCH_ASSOC);
?>

<form method="POST">
<input type="hidden" name="id_appr" value="<?php echo $id_appr; ?>">
<input type="hidden" name="id_session" value="<?php echo $session['id_session']; ?>">
<input type="hidden" name="id_training" value="<?php echo $id_training; ?>">

<?php if (count($rows) > 0): ?>
<button name="register" type="submit" style="border-radius:10px;box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);width: 45%;background-color: #ff0000;margin-right: 10px;color:#fff"onclick="alert('You have already registered for a session that overlaps with this session.');" disabled="disabled" title="You have already registered for a session that overlaps with this session."
><?php echo "Join Training"; ?></button>

<?php elseif  ($count >= 2): ?> <!-------------3rd²--------------->
<button name="register" type="submit" style="border-radius:10px;box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);width: 45%;background-color: #ff0000;margin-right: 10px;color:#fff"onclick="alert('You have already registered for the maximum number of sessions (2).');" disabled="disabled"
title="You have already registered for the maximum number of sessions (2)."
><?php echo "Join Training"; ?></button>
<?php else: ?>
<button name="register" type="submit" style="border-radius:10px;box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);width: 45%;background-color: #6a7df1;margin-right: 10px;color:#fff"
><?php echo "Join Training"; ?></button>
<?php endif; ?>
</form><?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</main>
<!---------------------------FOOTER-------------------------------------------------->
<footer class="bg-dark text-light">
<div class="container py-5">
<div class="row">
<div class="col-lg-4 col-md-6 mb-4 mb-md-0">
<h2 class="text-uppercase mb-4"><strong style="background-image: linear-gradient(to right, #6a7df1, #00eda4);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;">EduVerse</strong></h2>
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
table {
border-collapse: collapse;
width: 100%;
margin: 10% auto;
}
th, td {
text-align: left;
padding: 8px;
}
th {
background-color: #006699;
color: white;
font-weight: bold;
}
tr:nth-child(even) {
background-color: #f2f2f2;
}
tr:hover {
background-color: #ffffcc;
}
.col-lg-2 a i,
.col-md-6 a i {
transition: color 0.3s ease; 
}
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