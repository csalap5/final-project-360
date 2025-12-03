<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ensure logged in
if (!isset($_SESSION['admin']['loggedin']) || $_SESSION['admin']['loggedin'] !== true) {
    die("Access denied.");
}

$password = $_SESSION['admin']['password'] ?? '';
$db = new mysqli("127.0.0.1", "uMoviesAdmin", $password, "uMovies");
if ($db->connect_errno) die("DB connection failed.");

$db->query("DELETE FROM performed_in");
$db->query("DELETE FROM directed_by");
$db->query("DELETE FROM actors");
$db->query("DELETE FROM directors");
$db->query("DELETE FROM movies");
$db->close();
?>
<!DOCTYPE html>
<html>
<head>
<title>uMovies :: Administrator</title>
<style>@import url("uMovies.css");</style>
</head>
<body>

<div id="links">
<a href="./">Home</a>
<a href="admin.php">Administrator</a>
</div>

<div id="content">
<h1>uMovies&trade;</h1>
<p>
Welcome to <em>uMovies</em>, your destination for information on <a href="movies.php" title="access movies information">movies</a>, <a href="actors.php" title="access actors information">actors</a> and <a href="directors.php" title="access directors information">directors</a>.
</p>

<h2>Administrator Menu</h2>
<h3>Deleting Information</h3>
<h3>All data deleted!</h3>

<form action="admin.php" method="get">
    <button type="submit">Back to Administrator Menu</button>
</form>

<p><copyright>Carter Salapka & Kevin Farnsworth &copy; 2025</copyright></p>

</div>
</body>
</html>