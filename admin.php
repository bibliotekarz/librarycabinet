<?php
require 'config.php';
session_start();

$librarian_login_sanitized = filter_input(INPUT_POST, 'librarian_login', FILTER_SANITIZE_EMAIL);
$librarian_login_sanitized = strtolower($librarian_login_sanitized);

// password hashing 
if (isset($_POST['librarian_pass'])) {
    $librarian_pass = password_hash($_POST['librarian_pass'], PASSWORD_ARGON2I, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3]);
}
///////////////////////////////

class MyDB extends SQLite3
{
    function __construct($dbfile)
    {
        $this->open($dbfile);
    }
}

$db = new MyDB($dbfile);

// librarians' account support form 

$action_librarian = filter_input(INPUT_POST, 'librarian', FILTER_VALIDATE_INT);
$librarian_action_message_class="";


function check_user($db, $librarian_login_sanitized){
    $stm = $db->prepare("SELECT COUNT(*) as count FROM librarian WHERE librarian_name = :librarian_name");
    $stm->bindValue(':librarian_name', $librarian_login_sanitized);
    $score = $stm->execute();
    $count = $score->fetchArray(1)['count'];
    return $count;
}

// 0 add, 1 remove, 2 update 
if ($action_librarian === 0) {
    if (!empty($librarian_login_sanitized) && !empty($_POST['librarian_pass'])) {

        $count_user = check_user($db, $librarian_login_sanitized);
        if ($count_user == 0 ) {
            $stm = $db->prepare("INSERT INTO librarian (librarian_name, librarian_pass) VALUES (:librarian_sanitized, :librarian_pass )");
            $stm->bindValue(':librarian_sanitized', $librarian_login_sanitized);
            $stm->bindValue(':librarian_pass', $librarian_pass);
            $stm->execute();
            $librarian_action_message = $info['add_librarian_account'] . $librarian_login_sanitized . "<br>";
        } else {
            $librarian_action_message = $info['account_exists'] . $librarian_login_sanitized . "<br>";
        }
    } else {

        $librarian_action_message_class = "alert";
        $librarian_action_message = $info['wrong_email_password'];
    }
} elseif ($action_librarian == 1) {
    
    $count_all_librarian = $db->querySingle("SELECT COUNT(*) as count FROM librarian");
    if ($count_all_librarian <= 1) {
        $librarian_action_message_class = "alert";
        $librarian_action_message = $info['last_librarian'] . "<br>";
    } else {
        if (!empty($librarian_login_sanitized)) {
            $stm = $db->prepare("DELETE from librarian where librarian_name = :librarian_sanitized");
            $stm->bindValue(':librarian_sanitized', $librarian_login_sanitized);
            $stm->execute();
            $librarian_action_message = $info['account_deleted'] . $librarian_login_sanitized . "<br>";
        } else {
            $librarian_action_message_class = "alert";
            $librarian_action_message = $info['enter_correctly_account'];
        }
    }
} elseif ($action_librarian == 2) {

    if (!empty($librarian_login_sanitized) && !empty($_POST['librarian_pass'])) {
        
        $count_user = check_user($db, $librarian_login_sanitized);
        if ($count_user > 0) {
            $stm = $db->prepare("UPDATE librarian set librarian_pass = :librarian_pass where librarian_name = :librarian_sanitized");
            $stm->bindValue(':librarian_sanitized', $librarian_login_sanitized);
            $stm->bindValue(':librarian_pass', $librarian_pass);
            $stm->execute();
            $librarian_action_message = $info['password_librarian_changed'] . $librarian_login_sanitized . "<br>";
        } else {
            $librarian_action_message = $info['account_not_exist'] . $librarian_login_sanitized . "<br>";
        }
    } else {
        $librarian_action_message_class = "alert";
        $librarian_action_message = $info['wrong_email_password'];
    }
}

// list of librarians 
$stm = $db->query("SELECT librarian_name FROM librarian");
while ($row = $stm->fetchArray(1)) {
    $librarian_name = $row['librarian_name'];
    $librarian = "<li>" . $librarian_name . ".</li>";
    $all_librarians .= $librarian;
}


// machine management form  

$action_machine = filter_input(INPUT_POST, 'machine', FILTER_VALIDATE_INT);
$unit_id  = filter_input(INPUT_POST, 'machine_id', FILTER_VALIDATE_INT);
$unit_name = filter_input(INPUT_POST, 'machine_name', FILTER_SANITIZE_STRING);
$unit_address = filter_input(INPUT_POST, 'machine_address', FILTER_SANITIZE_STRING);
$unit_size = filter_input(INPUT_POST, 'machine_size', FILTER_VALIDATE_INT);
$unit_column = filter_input(INPUT_POST, 'machine_column', FILTER_VALIDATE_INT);
$machine_action_message_class="";

function check_machine($db, $unit_id){
    $stm = $db->prepare("SELECT COUNT(*) as count FROM unit WHERE unit_id = :unit_id");
    $stm->bindValue(':unit_id', $unit_id);
    $score = $stm->execute();
    $count = $score->fetchArray(1)['count'];
    return $count;
}

// 0 add, 1 remove, 2 update

if ($action_machine === 0) {
    if (empty($unit_id) && !empty($unit_name) && !empty($unit_address) && !empty($unit_size) && !empty($unit_column)) {

        $stm = $db->prepare("INSERT INTO unit (unit_name, unit_address, number_box, number_columns) VALUES (:unit_name, :unit_address, :unit_size, :unit_column )");
        $stm->bindValue(':unit_name', $unit_name);
        $stm->bindValue(':unit_address', $unit_address);
        $stm->bindValue(':unit_size', $unit_size);
        $stm->bindValue(':unit_column', $unit_column);
        $stm->execute();
        $machine_action_message = $info['machine_added'] . $unit_name . "<br>";
    } else {

        $machine_action_message_class = "alert";
        $machine_action_message = $info['not_all_data_entered_correctly'];
    }
} elseif ($action_machine == 1) {
    $count_all_machines = $db->querySingle("SELECT COUNT(*) as count FROM unit");

    if ($count_all_machines <= 1) {
        $machine_action_message_class = "alert";
        $machine_action_message = $info['last_machine'] . "<br>";
    } else {
        if (!empty($unit_id)) {
            
            $count_machine = check_machine($db, $unit_id);
            if ($count_machine > 0) {

                $stm = $db->prepare("DELETE from unit where unit_id = :unit_id");
                $stm->bindValue(':unit_id', $unit_id);
                $stm->execute();
                $machine_action_message = $info['machine_deleted']  . $unit_id . "<br>";
            } else {
                $machine_action_message_class = "alert";
                $machine_action_message = $info['no_machine_with_id'] . $unit_id . "<br>";
            }
        } else {
            $machine_action_message_class = "alert";
            $machine_action_message = $info['enter_correct_id_machine'];
        }
    }
} elseif ($action_machine == 2) {
    if (!empty($unit_id) && !empty($unit_name) && !empty($unit_address) && !empty($unit_size) && !empty($unit_column)) {

        $count_machine = check_machine($db, $unit_id);
        if ($count_machine > 0) {
            $stm = $db->prepare("UPDATE unit set unit_name = :unit_name, unit_address = :unit_address, number_box = :unit_size, number_columns= :unit_column where unit_id = :unit_id ");
            $stm->bindValue(':unit_id', $unit_id);
            $stm->bindValue(':unit_name', $unit_name);
            $stm->bindValue(':unit_address', $unit_address);
            $stm->bindValue(':unit_size', $unit_size);
            $stm->bindValue(':unit_column', $unit_column);
            $stm->execute();
            $machine_action_message = $info['machine_data_changed'] . $unit_id . "<br>";
        } else {
            $machine_action_message_class = "alert";
            $machine_action_message = $info['no_machine'] . $unit_id . "<br>";
        }
    } else {
        $machine_action_message_class = "alert";
        $machine_action_message = $info['not_all_data_entered_correctly'];
    }
}


// machine list 
$stm = $db->query("SELECT unit_id, unit_name, unit_address, number_box FROM unit");
while ($row = $stm->fetchArray(1)) {
    $unit_id = $row['unit_id'];
    $unit_name = $row['unit_name'];
    $unit_address = $row['unit_address'];
    $unit_size = $row['number_box'];
    $unit = "<li>" . $info['id'] . $unit_id . ". " . $unit_name . ". " . $unit_address .". ". $info['lockers'] . $unit_size . ".</li>";
    $all_units .= $unit;
}


////////////////////

echo $page_head . "\n\t\t<title>" . $info['admin_title']; ?></title>
</head>

<body>
    <?php
    if (isset($_SESSION["name"])) {

    ?>
        <header class="page-header">
            <h1><?php echo $info['admin_message']; ?></h1>
            <?php echo $info['admin_login_info'] . " <b>" . $_SESSION['name']; ?></b>.<br>
        </header>
        <nav>
            <ul>
                <li><?php echo $info['admin_logout']; ?></li>
                <li><?php echo $info['machine_page']; ?></li>
            </ul>
        </nav>

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
                    <div class="<?php echo $machine_action_message_class; ?>">
                        <h2><?php echo $machine_action_message; ?></h2>
                    </div>
                    <form name="admin_machines" method="post" action="" class="">
                        <div class="flekser">
                            <label for="machine_id"><?php echo $info['admin_machine_id']; ?></label>
                            <input type="tekst" class="" id="id-machine_id" name="machine_id">
                        </div>
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
                        <div class="flekser">
                            <label for="machine_column"><?php echo $info['admin_machine_column']; ?></label>
                            <input type="number" class="" id="id-machine_column" name="machine_column">
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
    <script src="./script.js"></script>
</body>

</html>