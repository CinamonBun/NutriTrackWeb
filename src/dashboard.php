<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}

$username = $_SESSION['username'];
include 'config.php';

if ($stmt = mysqli_prepare($conn, "SELECT fullname FROM user WHERE username = ? LIMIT 1")) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fullname);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Dashboard</title>
    </head>
    <body>
        <?php
        if (isset($_SESSION['username'])) {
            echo "<h1 class='text-4xl font-bold'>Dashboard</h1>";
            echo "<p class='text-2xl font-bold'>Welcome, " . $_SESSION['username'] . "</p>";
            echo "<a class='text-blue-500 hover:text-blue-700' href='logout.php'>Logout</a>";
            if (!empty($fullname)) {
                echo "<p class='text-xl'>Full name: " . htmlspecialchars($fullname) . "</p>";
            }
        } else {
            header("Location: signin.php");
            exit;
        }
        ?>
    </body>
</html> 