<?php
header("X-Clacks-Overhead: GNU Terry Pratchett");
require 'config.php';

session_start();

if (count($_POST) > 0) {
    $user_id_sanitized = filter_var($_POST["user_name"], FILTER_VALIDATE_EMAIL);

    class MyDB extends SQLite3
    {
        function __construct($dbfile)
        {
            $this->open($dbfile);
        }
    }

    $db = new MyDB($dbfile);
    $stm = $db->prepare("SELECT * FROM librarian WHERE librarian_name = :user_id");
    $stm->bindValue(':user_id', $user_id_sanitized);
    $score = $stm->execute();
    $row = $score->fetchArray(1);

    if (strlen($_POST['password']) > 0 && strlen($user_id_sanitized) > 0 && isset($row['librarian_pass'])) {
        if (password_verify($_POST["password"], $row['librarian_pass'])) {
            $_SESSION["id"] = $row['librarian_id'];
            $_SESSION["name"] = $row['librarian_name'];
            $_SESSION["pass"] = $row['librarian_pass'];
        } else {
            $message = "<h3 class='alert'>" . $info['login_alert'] . "</h3>";
        }
    } else {
        $message = "<h3 class='alert'>" . $info['login_alert'] . "</h3>";
    }
}
if (isset($_SESSION["id"])) {

    header("Location:machine.php");
}

echo $page_head . "\n\t\t<title>" . $info['login_title']; ?></title>
</head>

<body>
    <header class="page-header">
        <h1><?php echo $info['login_message']; ?></h1>
    </header>
    <main role="main">
        <section class="edycja">
            <form name="login" method="post" action="" class="">
                <?php echo ($message != "") ? $message : ''; ?>
                <div class="flekser">
                    <label for="user_name"><?php echo $info['login_name']; ?></label>
                    <input type="text" name="user_name">
                </div>
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