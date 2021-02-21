<?php
require 'config.php';
session_start();
/*
print_r($_POST);
echo "<br> powyżej post <bR><bR><bR>";
print_r($_SESSION);
echo "<br> powyżej session <bR><bR><bR>";
*/

$librarian_login_sanitized = filter_input(INPUT_POST, 'librarian_login', FILTER_SANITIZE_EMAIL);
$librarian_login_sanitized = strtolower($librarian_login_sanitized);

// password hashing 
$librarian_pass = password_hash($_POST['librarian_pass'], PASSWORD_ARGON2I, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3]);

///////////////////////////////

class MyDB extends SQLite3
{
    function __construct($dbfile)
    {
        $this->open($dbfile);
    }
}

$db = new MyDB($dbfile);

// form handling librarians 
// 0 add, 1 remove, 2 update
// TODO: add UNIQUE NOT NULL constraints 

$action_librarian = filter_input(INPUT_POST, 'librarian', FILTER_VALIDATE_INT);

if ($action_librarian === 0) {
    if (!empty($librarian_login_sanitized) && !empty($_POST['librarian_pass'])) {
        $stm = $db->prepare("INSERT INTO librarian (librarian_name, librarian_pass) VALUES (:librarian_sanitized, :librarian_pass )");
        $stm->bindValue(':librarian_sanitized', $librarian_login_sanitized);
        $stm->bindValue(':librarian_pass', $librarian_pass);
        $stm->execute();
        $librarian_action_message = "Dodano konto bibliotekarza " . $librarian_login_sanitized . "<br>";
    } else {
        $librarian_action_message_class = "alert";
        $librarian_action_message = "Niewłaściwy email lub hasło";
    }
} elseif ($action_librarian == 1) {
    if (!empty($librarian_login_sanitized)) {
        $stm = $db->prepare("DELETE from librarian where librarian_name = :librarian_sanitized");
        $stm->bindValue(':librarian_sanitized', $librarian_login_sanitized);
        $stm->execute();
        $librarian_action_message = "Skasowano konto bibliotekarza " . $librarian_login_sanitized . "<br>";
    } else {
        $librarian_action_message_class = "alert";
        $librarian_action_message = "Wprowadź poprawnie konto do skasowania";
    }
} elseif ($action_librarian == 2) {
    if (!empty($librarian_login_sanitized) && !empty($_POST['librarian_pass'])) {
        $stm = $db->prepare("UPDATE librarian set librarian_pass = :librarian_pass where librarian_name = :librarian_sanitized");
        $stm->bindValue(':librarian_sanitized', $librarian_login_sanitized);
        $stm->bindValue(':librarian_pass', $librarian_pass);
        $stm->execute();
        $librarian_action_message = "Zmieniono hasło do konta bibliotekarza " . $librarian_login_sanitized . "<br>";
    } else {
        $librarian_action_message_class = "alert";
        $librarian_action_message = "Niewłaściwy email lub hasło";
    }
}

// machine form handling 

// machine list 
$stm = $db->query("SELECT unit_name, unit_address, number_box FROM unit");
while ($row = $stm->fetchArray(1)) {
    $unit_name = $row['unit_name'];
    $unit_address = $row['unit_address'];
    $number_box = $row['number_box'];
    $unit = "<li>" . $unit_name . ". " . $unit_address . ". Skrytek " . $number_box . ".</li>";
    $all_units .= $unit;
}

// list of librarians 
$stm = $db->query("SELECT librarian_name FROM librarian");
while ($row = $stm->fetchArray(1)) {
    $librarian_name = $row['librarian_name'];
    $librarian = "<li>" . $librarian_name . ".</li>";
    $all_librarians .= $librarian;
}
////////////////////

echo $page_head . "\n\t\t<title>" . $info['admin_title']; ?></title>
</head>

<body>
    <?php
    if ($_SESSION["name"]) {

    ?>
        <header class="page-header">
            <h1><?php echo $info['admin_message']; ?></h1>
            <?php echo $info['admin_login_info'] . " <b>" . $_SESSION['name']; ?></b>.<br>
            <?php echo $info['admin_logout']; ?><br>
        </header>

        <main role="main">
            <section>
                <h2 class=""><?php echo $info['admin_librarian_managment']; ?></h2>
                <div class="edycja">
                    <div class="<?php echo $librarian_action_message_class; ?>">
                        <h2><?php echo $librarian_action_message; ?></h2>
                    </div>
                    <form name="admin_librarians" method="post" action="" class="">
                        <div class="flekser">
                            <label for="librarian_login"><?php echo $info['login_name']; ?></label> <input type="text" class="" id="id-librarian_login" name="librarian_login">
                        </div>
                        <div class="flekser">
                            <label for="librarian_pass"><?php echo $info['login_pass']; ?></label> <input type="password" class="" id="id-librarian_pass" name="librarian_pass">
                        </div>
                        <div>
                            <div>
                                <input type="radio" id="librarian_add" name="librarian" value="0"> <label for="librarian_add"><?php echo $info['add'] . " " . $info['account']; ?></label>
                            </div>
                            <div>
                                <input type="radio" id="librarian_remove" name="librarian" value="1"> <label for="librarian_remove"><?php echo $info['remove'] . " " . $info['account']; ?></label>
                            </div>
                            <div>
                                <input type="radio" id="librarian_update" name="librarian" value="2"> <label for="librarian_update"><?php echo $info['update'] . " " . $info['password']; ?></label>
                            </div>
                        </div><br>
                        <input type="submit" name="librarian_submit" value="<?php echo $info['send_button']; ?>">
                    </form>
                </div>
                <h3><?php echo $info['admin_list_librarians']; ?></h3>
                <ul>
                    <?php echo $all_librarians; ?>
                </ul>
            </section>

            <section>
                <h2 class=""><?php echo $info['admin_managing_machines']; ?></h2>
                <div class="edycja">
                    <form name="admin_machines" method="post" action="" class="">
                        <div class="flekser">
                            <label for="machine_name"><?php echo $info['admin_machine_name']; ?></label>
                            <input type="tekst" class="" id="id-machine_name" name="machine_name">
                        </div>
                        <div class="flekser">
                            <label for="machine_address"><?php echo $info['admin_machine_address']; ?></label>
                            <input type="text" class="" id="id-machine_address" name="machine_address">
                        </div>
                        <div class="flekser">
                            <label for="machine_size"><?php echo $info['admin_machine_size']; ?></label>
                            <input type="number" class="" id="id-machine_size" name="machine_size">
                        </div>
                        <div>
                            <div>
                                <input type="radio" id="machine_add" name="machine" value="0">
                                <label for="machine_add"><?php echo $info['add'] . " " . $info['machine']; ?></label>
                            </div>
                            <div>
                                <input type="radio" id="machine_remove" name="machine" value="1">
                                <label for="machine_remove"><?php echo $info['remove'] . " " . $info['machine']; ?></label>
                            </div>
                            <div>
                                <input type="radio" id="machine_update" name="machine" value="2">
                                <label for="machine_update"><?php echo $info['update'] . " " . $info['machine']; ?></label>
                            </div>
                        </div><br>
                        <input type="submit" name="machine_submit" value="<?php echo $info['send_button']; ?>">
                    </form>
                </div>
                <h3><?php echo $info['admin_list_machines']; ?></h3>
                <ul>
                    <?php echo $all_units; ?>
                </ul>
            </section>
        </main>
    <?php
    } else {
        echo '<header class="page-header">
        <h1>' . $info['admin_message'] . '</h1>
        <h2><a href="login.php">' . $info["login_first"] . '</a></h2>
    </header>';
    }

    ?>

</body>

</html>