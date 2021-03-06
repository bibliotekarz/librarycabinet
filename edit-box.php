<?php
require 'config.php';
session_start();

class MyDB extends SQLite3
{
    function __construct($dbfile)
    {
        $this->open($dbfile);
    }
}

$db = new MyDB($dbfile);

$selected_unit_get = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$selected_box_get = filter_input(INPUT_GET, 'box', FILTER_VALIDATE_INT);
$selected_unit_post = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$selected_box_post = filter_input(INPUT_POST, 'box', FILTER_VALIDATE_INT);

$selected_unit = $selected_unit_get . $selected_unit_post;
$selected_box = $selected_box_get . $selected_box_post;


$end_date = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_ADD_SLASHES);
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$secret_code = filter_input(INPUT_POST, 'secret_code', FILTER_VALIDATE_INT);
$id_user = filter_input(INPUT_POST, 'id_user', FILTER_SANITIZE_STRING);
$box = filter_input(INPUT_POST, 'box', FILTER_VALIDATE_INT);
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($selected_unit < 1 || $selected_box < 1) {
    header("Location:machine.php");
}

$unit_name = "";
$unit_address = "";
$stm = $db->prepare("SELECT unit_name, unit_address from unit where unit_id = :unit_id");
$stm->bindValue(':unit_id', $selected_unit);
$score = $stm->execute();

while ($row = $score->fetchArray(1)) {
    $unit_name = $row['unit_name'];
    $unit_address = $row['unit_address'];
};


function test_box($db, $selected_unit, $selected_box)
{
    $stm = $db->prepare("SELECT * from user where unit_id = :unit_id");
    $stm->bindValue(':unit_id', $selected_unit);
    $score_box = $stm->execute();

    $box_nr_all = array();
    while ($row = $score_box->fetchArray(1)) {
        $box_nr = $row['box_nr'];
        array_push($box_nr_all, $box_nr);
    }

    if (in_array($selected_box, $box_nr_all)) {
        $do_it = "update";
    } else {
        $do_it = "insert";
    }
    return $do_it;
}


function update_box($db, $end_date, $title, $secret_code, $id_user, $box, $id)
{
    $stm = $db->prepare("UPDATE user set date_insertion = :end_date, title = :title, access_code = :secret_code, user_id = :id_user where box_nr = :box and unit_id = :id");
    $stm->bindValue(':end_date', $end_date);
    $stm->bindValue(':title', $title);
    $stm->bindValue(':secret_code', $secret_code, SQLITE3_TEXT);
    $stm->bindValue(':id_user', $id_user);
    $stm->bindValue(':box', $box);
    $stm->bindValue(':id', $id);
    $stm->execute();
}

function insert_box($db, $end_date, $title, $secret_code, $id_user, $box, $id)
{
    $stm = $db->prepare("INSERT INTO user (date_insertion, title, access_code, user_id, box_nr, unit_id) VALUES (:end_date, :title, :secret_code, :id_user, :box, :id)");
    $stm->bindValue(':end_date', $end_date);
    $stm->bindValue(':title', $title);
    $stm->bindValue(':secret_code', $secret_code, SQLITE3_TEXT);
    $stm->bindValue(':id_user', $id_user);
    $stm->bindValue(':box', $box);
    $stm->bindValue(':id', $id);
    $stm->execute();
}

function selected_box($db, $selected_unit, $selected_box)
{
    $stm = $db->prepare("SELECT * from user where box_nr = :box_nr and unit_id = :unit_id");
    $stm->bindValue(':box_nr', $selected_box);
    $stm->bindValue(':unit_id', $selected_unit);
    $score_box = $stm->execute();

    $date_insertion = "";
    $title = "";
    $access_code = "";
    $user_id = "";
    while ($row = $score_box->fetchArray(1)) {
        $date_insertion = $row['date_insertion'];
        $title = $row['title'];
        $access_code = $row['access_code'];
        $user_id = $row['user_id'];
    }
    $box_data = array($title, $access_code, $date_insertion, $user_id);
    return $box_data;
}

$do_it = test_box($db, $selected_unit, $selected_box);

if (isset($_SESSION["name"]) && $do_it == "update" && isset($box)) {
    update_box($db, $end_date, $title, $secret_code, $id_user, $box, $id);
} elseif (isset($_SESSION["name"]) && $do_it == "insert" && isset($box)) {
    insert_box($db, $end_date, $title, $secret_code, $id_user, $box, $id);
}

$box_info = selected_box($db, $selected_unit, $selected_box);


echo $page_head . "\n\t\t<title>" . $info['machine_title']; ?></title>

</head>

<body>
    <?php
    if (isset($_SESSION["name"])) {
    ?>

        <header class="page-header">
            <h1><?php echo $info['content_update']; ?></h1>
            <?php echo $info['admin_login_info'] . " <b>" . $_SESSION['name']; ?></b>.<br>
        </header>
        <nav>
            <ul>
                <li><?php echo $info['admin_logout']; ?></li>
                <li><?php echo $info['admin_page']; ?></li>
                <li><?php echo $info['machine_page']; ?></li>
            </ul>
        </nav>
        <main role="main">
            <section>
                <h3 class=""><?php echo $unit_name . ", " . $unit_address; ?></h3>
            </section>
            <form action="./edit-box.php" method="post">
                <div class="edycja">

                    <h2 id="box_info" class="alert"></h2>
                    <div class="flekser"><span class="box-number"><?php echo $selected_box; ?></span>
                        <!--  TODO: to make some extra books in one safe  
                            div class="panel-checkboxes"><label>box full<br><input type="checkbox"></label></div -->
                        <div class="panel-checkboxes">
                            <input type="submit" id="clear_fields" value="<?php echo $info['clear_fields']; ?>">
                        </div>
                    </div>
                    <div class="descriptive-container">
                        <div class="descriptive-container-end"><label for="title" class=""><?php echo $info['book_title']; ?></label></div>
                        <div class=""><input class="" id="box_title" name="title" value="<?php echo $box_info[0]; ?>" type="text"></div>
                        <div class="descriptive-container-end"><label for="secret_code" class=""><?php echo $info['lockbox_code']; ?></label></div>
                        <div class=""><input class="" id="box_secret_code" name="secret_code" value="<?php echo $box_info[1]; ?>" type="number"></div>
                        <div class="descriptive-container-end"><label for="end_date" class=""><?php echo $info['date_emptying']; ?></label></div>
                        <div class=""><input class="" id="box_end_date" name="end_date" value="<?php echo $box_info[2]; ?>" type="date"></div>
                        <div class="descriptive-container-end"><label for="id_user" class=""><?php echo $info['reader_id']; ?></label></div>
                        <div class=""><input class="" id="box_id_user" name="id_user" value="<?php echo $box_info[3]; ?>" type="text"></div>
                        <input type="hidden" value="<?php echo $selected_box; ?>" name="box">
                        <input type="hidden" value="<?php echo $selected_unit; ?>" name="id">
                        <input type="hidden" value="<?php echo $info['cache_emptied']; ?>" id="cache_emptied">
                        <input type="hidden" value="<?php echo $info['data_saved']; ?>" id="data_saved">
                        <input type="hidden" value="<?php echo $info['fill_fields']; ?>" id="fill_fields">
                    </div>
                </div>
                <input type="submit" id="btn_submit" value="<?php echo $info['save_changes']; ?>">
            </form>
        </main>
    <?php

    } else {
        echo '<header class="page-header">
        <h1>' . $info["machine_message"] . '</h1>
        <h2><a href="login.php">' . $info["login_first"] . '</a></h2>
    </header>';
    }

    ?>
    <script src="js/script-box.js"></script>
</body>

</html>