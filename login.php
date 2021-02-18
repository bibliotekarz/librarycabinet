<?php
require 'config.php';
session_start();
$message = "";
if (count($_POST) > 0) {


    class MyDB extends SQLite3
    {
        function __construct($dbfile)
        {
            $this->open($dbfile);
        }
    }
    // TODO: sanityzacja loginu 

    $db = new MyDB($dbfile);
    $stm = $db->prepare("SELECT * FROM librarian WHERE librarian_name = '" . $_POST["user_name"] . "' and librarian_pass = '" . $_POST["password"] . "'");
    // TODO:        $stm->bindValue(':user_id', $user_id_sanitized);
    $score = $stm->execute();
    $row = $score->fetchArray(1);
    if (is_array($row)) {
        $_SESSION["id"] = $row['librarian_id'];
        $_SESSION["name"] = $row['librarian_name'];
    } else {
        $message = "<h3 class='alert'>" . $info['login_alert'] . "</h3>";
    }
}
if (isset($_SESSION["id"])) {
    header("Location:admin.php");
}
?>
<html>

<head>
    <title><?php echo $info['login_title']; ?></title>

    <link rel="stylesheet" type="text/css" href="./style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <main role="main">
        <h3 class=""><?php echo $info['login_title']; ?></h3>
        <section class="edycja">
            <form name="login" method="post" action="" class="">
                    <?php echo ($message != "") ? $message : ''; ?>

                <div class="flekser">
                    <label for="user_name"><?php echo $info['login_name']; ?></label>
                    <input type="text" name="user_name">
                </div>
                <br>
                <div class="flekser">
                    <label for="password"><?php echo $info['login_pass']; ?></label>
                    <input type="password" name="password">
                </div>
                <br>
                <input type="submit" name="submit" value="<?php echo $info['send_button']; ?>">
                <input type="reset">

            </form>
        </section>
    </main>
</body>

</html>