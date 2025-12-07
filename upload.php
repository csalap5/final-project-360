<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ensure logged in
if (!isset($_SESSION['admin']['loggedin']) || $_SESSION['admin']['loggedin'] !== true) {
    die("Access denied.");
}

// Validate upload
if (!isset($_FILES['upload-file']) || $_FILES['upload-file']['error'] !== UPLOAD_ERR_OK) {
    die("<h3>Error: No file uploaded or upload error.</h3>");
}

$tmpPath = $_FILES['upload-file']['tmp_name'];


// DB connection
$password = $_SESSION['admin']['password'] ?? '';
$db = new mysqli("127.0.0.1", "uMoviesAdmin", $password, "uMovies");
if ($db->connect_errno) die("DB connection failed.");
$db->set_charset('utf8mb4'); // Ensure DB uses UTF-8

$lines = [];
$file = fopen($tmpPath, "r");
if ($file) {
    while (($line = fgets($file)) !== false) {
        $line = trim($line);
        if ($line !== "") {
            // Only convert if not valid UTF-8
            if (!mb_check_encoding($line, 'UTF-8')) {
                $lines[] = mb_convert_encoding($line, 'UTF-8', 'ISO-8859-1');
            } else {
                $lines[] = $line;
            }
        }
    }
    fclose($file);
}

// Counters for results page
$movieAdd = $actorAdd = $directorAdd = $directionAdd = $performanceAdd = 0;
$movieTotal = $actorTotal = $directorTotal = $directionTotal = $performanceTotal = 0;

foreach ($lines as $line) {
    $name = $movie = $role = ""; // For result display
    $parts = explode("\t", $line);
    $type = $parts[0];

    if ($type === "movie") {
        $movieTotal++;
        $title = $parts[1];
        $year = $parts[2];

        $stmt = $db->prepare("INSERT IGNORE INTO movies (name, year) VALUES (?,?)");
        $stmt->bind_param("ss", $title, $year);
        $stmt->execute();

        if ($stmt->affected_rows > 0) $movieAdd++;

    } elseif ($type === "director") {
        $directorTotal++;
        $dname = $parts[1];
        $movie = $parts[2];
        $year = $parts[3];

        $stmt = $db->prepare("INSERT IGNORE INTO directors (name) VALUES (?)");
        $stmt->bind_param("s", $dname);
        $stmt->execute();

        $stmt = $db->prepare("INSERT IGNORE INTO directed_by (movie, year, director) VALUES (?,?,?)");
        $stmt->bind_param("sss", $movie, $year, $dname);
        $stmt->execute();

        if ($stmt->affected_rows > 0) $directionAdd++;
        if ($stmt->affected_rows > 0) $directorAdd++;

    } elseif ($type === "actor" || $type === "actress") {
        $actorTotal++;
        $name = $parts[1];
        $movie = $parts[2];
        $year = $parts[3];
        $role = $parts[4];
        $gender = ($type === "actor") ? "Male" : "Female";

        $stmt = $db->prepare("INSERT IGNORE INTO actors (name, gender) VALUES (?,?)");
        $stmt->bind_param("ss", $name, $gender);
        $stmt->execute();

        $stmt = $db->prepare("INSERT IGNORE INTO performed_in (actor, movie, year, role) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $name, $movie, $year, $role);
        $stmt->execute();

        if ($stmt->affected_rows > 0) $performanceAdd++;
        if ($stmt->affected_rows > 0) $actorAdd++;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>uMovies :: Administration</title>
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
<h3>Uploading Data File</h3>

<ul>
    <li>Added <?= $movieAdd ?> movies out of <?= $movieTotal ?> movie records (<?= $movieTotal - $movieAdd ?> failures)[Last added:<?= htmlspecialchars(rtrim($movie)) ?>]</li>
    <li>Added <?= $actorAdd ?> actors out of <?= $actorTotal ?> actor records (<?= $actorTotal - $actorAdd ?> failures)[Last added:<?= htmlspecialchars(rtrim($name)) ?>]</li>
    <li>Added <?= $directorAdd ?> directors out of <?= $directorTotal ?> director records (<?= $directorTotal - $directorAdd ?> failures)[Last added:<?= htmlspecialchars(rtrim($dname)) ?>]</li>
    <li>Added <?= $directionAdd ?> directions out of <?= $directorTotal ?> movie/director records (<?= $directorTotal - $directionAdd ?> failures)[Last added:<?= htmlspecialchars(rtrim($movie)) ?>
    /<?= htmlspecialchars(rtrim($dname)) ?>]</li>
    <li>Added <?= $performanceAdd ?> performances out of <?= $actorTotal ?> actor/movie/role records (<?= $actorTotal - $performanceAdd ?> failures)[Last added:<?= htmlspecialchars(rtrim($movie)) ?>
    /<?=htmlspecialchars(rtrim($name))?>/<?= htmlspecialchars(rtrim($role)) ?>]</li>
</ul>

<form action="admin.php" method="get">
    <button type="submit" style="cursor: pointer;">Back to Administrator Menu</button>
</form>

<p><copyright>Carter Salapka & Kevin Farnsworth &copy; 2025</copyright></p>

</div>
</body>
</html>