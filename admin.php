<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
            "http://www.w3.org/TR/REC-html40/strict.dtd">
<html>
<head>
<title>uMovies</title>
<style type="text/css">
@import url(uMovies.css);
</style>
<style>

</style>
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
// need to start a session to track login status and create a global db password
// this is just a filler to show admin login functionality
// could just fully implement uploads and figure out password storage and functionality later
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['admin-password'] ?? '';
    if ($password === 'letmein') {
        echo '<h2>Welcome, Administrator!</h2><p>You have successfully logged in.</p>';
        echo '<form method="post" action="admin.php" enctype="multipart/form-data">';
        echo '<label for="upload-file">Upload a file:</label> ';
        echo '<input type="file" id="upload-file" name="upload-file"> ';
        echo '<button type="submit" name="upload-btn">Upload</button>';
        echo '</form>';
    } else {
        echo '<h2>Administrator Access</h2>';
        echo '<p style="color:red;">Incorrect password. Try again.</p>';
        echo '<form method="post" action="admin.php">';
        echo '<label for="admin-password">Password:</label>';
        echo '<input type="password" id="admin-password" name="admin-password" required>';
        echo '<button type="submit">Login</button>';
        echo '</form>';
    }
} else {
?>
<h2>Administrator Access</h2>
<form method="post" action="admin.php">
    <label for="admin-password">Password:</label>
    <input type="password" id="admin-password" name="admin-password" required>
    <button type="submit">Login</button>
</form>
<?php } ?>

<p><copyright>Carter Salapka & Kevin Farnsworth &copy; 2027</copyright></p>
</div>
</body>
</html>