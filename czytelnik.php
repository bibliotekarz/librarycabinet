<?php

$dbfile = '../librarycabinet.db';

class MyDB extends SQLite3
{
    function __construct($dbfile)
    {
        $this->open($dbfile);
    }
}



if (count($_POST) > 0) {
    $db = new MyDB($dbfile);
    print_r($_POST);
    $sql = 'SELECT box_nr FROM "user" where user_id = ' . $_POST["user_id"];
    $row = $db->querySingle($sql);
    //    $row = $db->query($sql);

    if (is_int ($row) ) {
        $dane = "Skrytka użytkownika to " . $row;
    } else {
        $dane = "Nie ma skrytki z zawartością dla Użytkownika z identyfikatorem " . $_POST["user_id"];
    }
} else {

    $dane = ' <form method="post" action="" align="center">

    <h3 align="center">podaj id użytkownika</h3>
    Userid :<br>
    <input type="text" name="user_id">
    <br>
    <br><br>
    <input type="submit" name="submit" value="Submit">
    <input type="reset">
</form>';
}


?>
<html>

<head>
    <title>Numer skrytki użytkownika</title>
</head>

<body>

    <?php
    echo $dane;
    ?>




</body>

</html>