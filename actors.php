<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
            "http://www.w3.org/TR/REC-html40/strict.dtd">
<html>
<head>
<title>uMovies :: Movies</title>
<style type="text/css">
@import url(uMovies.css);
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
Welcome to <em>uMovies</em>, your destination for information on <a href="movies.php" title="access movies information">movies</a>, <a href="actors.php" title="access actors information">actors</a> and <a href="directors.php" title="access directors information">directors</a>.
</p>

<h2>Browsing All Actors</h2>

<p>
<?php
@$actorsdb = new mysqli( '127.0.0.1','uMoviesUser','anonymous','uMovies');
@$actorsdb->set_charset("utf8");

if ($actorsdb->connect_errno) {
    echo '<h3>Database Access Error!</h3>';
}
else {
    $select = 'select * from actors';

    switch (@$_GET['order']) {
        case 'name':
        case 'gender': $select .= ' order by '.$_GET['order'];
    }

    $result = $actorsdb->query( $select );
    $rows   = $result->num_rows;

    echo "<table class=\"uMovies\">\n";
    echo "<tr>\n";
    echo "<th></th>";
    echo "<th><a href=\"actors.php?order=name\" /> Name </a></th>";
    echo "<th><a href=\"actors.php?order=gender\" /> Gender </a></th>";
    echo "<tr>\n";
    if ($rows == 0) {
        echo "<tr>\n";
        echo "<td colspan=\"3\">No Actors to Display</td>";
        echo "</tr>\n";
    }
    else {
        for ($i=0; $i<$rows; $i++) {
            $row = $result->fetch_assoc();
            echo "<tr class=\"highlight\">";
            echo "<td>".($i+1)."</td>";
            echo "<td><a href=\"movie.php?name=".$row['name']."\" />".$row['name']."</a></td>";
            echo"<td>".$row['gender']."</td>";
            echo "</tr>\n";
        }
    }
    echo "</table>\n";

    $result->free();
    $actorsdb->close();
}

?>
</p>

<p><copyright>Carter Salapka & Kevin Farnsworth &copy; 2025</copyright></p>
</div>

</body>
</html>
