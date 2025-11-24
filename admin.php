<?php

if (!session_start()) {
    die("Could not start session");
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
            "http://www.w3.org/TR/REC-html40/strict.dtd">
<html>
<head>
<title>uMovies</title>
<style type="text/css">
@import url(uMovies.css);
</style>

<script>

function validateAdminForm() {
    const pw = document.getElementById("admin-password").value;
    if (pw.trim() === "") {
        alert("Password cannot be empty.");
        return false;
    }
    return true;
}
</script>

</head>
<body>

<div id="links">
<a href="./">Home<span> Access the database of movies, actors and directors. Free to all!</span></a>
<a href="admin.php">Administrator<span> Administrator access. Password required.</span></a>
</div>

<div id="content">
<h1>uMovies&trade;</h1>
<p>
Welcome to <em>uMovies</em>, your destination for information on 
<a href="movies.php" title="access movies information">movies</a>, 
<a href="actors.php" title="access actors information">actors</a> 
and <a href="directors.php" title="access directors information">directors</a>.
</p>
<p>
</p>


<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

echo "
<h2>Administrator Access</h2>
<form method='post' action='admin.php' onsubmit='return validateAdminForm()'>
    <label for='admin-password'>Password:</label>
    <input type='password' id='admin-password' name='admin-password'>
    <button type='submit'>Login</button>
</form>
";

} else {
    $password = $_POST['admin-password'] ?? '';
    $_SESSION['admin']['password'] = $password;

    try {
    $db = new mysqli("127.0.0.1", "uMoviesAdmin", $password, "uMovies");

    if ($db->connect_errno) {
        throw new Exception("Database connection failed");
    }

} catch (mysqli_sql_exception $e) {
    echo "<h3 style='color:black;'>Invalid administrator password.</h3>";
    exit();
}

    if ($mysqli->connect_errno) {

        echo "<h2 style='color:black;'>Incorrect Password</h2>";
        echo "<p>The password you entered does not match the administrator account.</p>";

        echo "
        <form method='post' action='admin.php' onsubmit='return validateAdminForm()'>
            <label for='admin-password'>Password:</label>
            <input type='password' id='admin-password' name='admin-password'>
            <button type='submit'>Login</button>
        </form>
        ";

    } else {

        $_SESSION['admin']['loggedin'] = true;

        echo "<h2>Welcome, Administrator!</h2>";
        echo "<p>You have successfully logged in.</p>";

        echo "<h3>Upload Movie File</h3>";
        echo '
        <form method="post" action="upload.php" enctype="multipart/form-data">
            <input type="file" name="upload-file" required>
            <button type="submit">Upload</button>
        </form>
        ';

        echo "<h3>Delete ALL Movie Information</h3>";
        echo '
        <form method="post" action="delete.php">
            <button type="submit" style="color:red;">Delete All Data</button>
        </form>
        ';
    }
}
?>

<p><copyright>Carter Salapka & Kevin Farnsworth &copy; 2027</copyright></p>
</div>
</body>
</html>