<?php
require 'config.php';
session_start();
?>

<html>

<head>
    <title><?php echo $info['librarian_title']; ?></title>
    <link rel="stylesheet" type="text/css" href="../style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

    <?php
    if ($_SESSION["name"]) {
    
    echo "Witaj " . $_SESSION['name']. ". Kliknij żeby <a href='logout.php' title='Logout'>wylogować";

    } else {
        echo "<h2><a href='login.php'>" . $info['login_first'] . "</a></h2>";
    }
        ?>
</body>

</html>