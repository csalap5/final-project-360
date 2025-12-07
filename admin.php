<?php
if (!session_start()) {
    die("Could not start session");
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin']);
    session_write_close();
    header("Location: ./");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
<head>
<title>uMovies :: Administration</title>
<style>@import url(uMovies.css);</style>

<script>
function validateAdminForm() {
    const pw = document.getElementById("admin-password").value;
    if (pw.trim() === "") {
        alert("Password cannot be empty.");
        return false;
    }
    return true;
}

function copyFileNameToTextInput() {
    let fileInput = document.getElementById('upload-file');
    let textInput = document.getElementById('movie-file-name');
    if (fileInput.files.length > 0) {
        textInput.value = fileInput.files[0].name;
    } else {
        textInput.value = '';
    }
}
</script>
</head>

<body>

<div id="links">
    <a href="admin.php?logout=1">Home</a>
    <a href="admin.php">Administrator</a>
</div>

<div id="content">

<h1>uMovies&trade;</h1>
<p>
Welcome to <em>uMovies</em>, your destination for information on <a href="movies.php" title="access movies information">movies</a>, <a href="actors.php" title="access actors information">actors</a> and <a href="directors.php" title="access directors information">directors</a>.
</p>

<?php

if (!isset($_SESSION['admin']['loggedin']) || $_SESSION['admin']['loggedin'] !== true) {

    // If it's a GET request, show login form
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo "
        <h2>Administrator Access</h2>
        <form method='post' action='admin.php' onsubmit='return validateAdminForm()'>
            <label for='admin-password'>Password:</label>
            <input type='password' id='admin-password' name='admin-password'>
            <button type='submit'>Login</button>
        </form>
        ";
        echo "<p><copyright>Carter Salapka & Kevin Farnsworth &copy; 2025</copyright></p></div></body></html>";
        exit();
    }

    // Otherwise it's POST — validate the password
    $password = $_POST['admin-password'] ?? '';
    $_SESSION['admin']['password'] = $password;

    try {
        $db = new mysqli("127.0.0.1", "uMoviesAdmin", $password, "uMovies");
        if ($db->connect_errno) throw new Exception("Database connection failed");
    } catch (Exception $e) {
        echo "<h3 style='color:black;'>Invalid administrator password.</h3>";
        echo "</div></body></html>";
        exit();
    }

    $_SESSION['admin']['loggedin'] = true;

    echo "<h2>Welcome, Administrator!</h2>";
}

else {
    echo "<h2>Welcome Back, Administrator!</h2>";
}

?>

<h3>Upload Movie File</h3>
<form method="post" action="upload.php" enctype="multipart/form-data">

    <label>Data File:</label>
    <input 
        type="text" 
        id="movie-file-name" 
        name="movie-file-name" 
        placeholder="Enter file name" 
        required
    >
     <label for="upload-file" style="cursor: pointer;">
        <button type="button" style="cursor: pointer;" onclick="document.getElementById('upload-file').click(); return false;">
            Choose File
        </button>
    </label>
    <input 
        type="file" 
        id="upload-file" 
        name="upload-file" 
        required
        onchange="copyFileNameToTextInput()"
        style="display: none;"
    >
    <button type="submit" style="cursor: pointer;">Upload</button>
</form>

<h3>Delete All Movie Information</h3>
<form method="post" action="delete.php" onsubmit="return confirm('Are you sure you want to delete all movie information?')">
    <button type="submit" style="color:red; cursor: pointer;">Delete All Data</button>
</form>

<p><copyright>Carter Salapka & Kevin Farnsworth &copy; 2025</copyright></p>

</div>
</body>
</html>