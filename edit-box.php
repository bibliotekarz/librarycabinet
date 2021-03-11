<?php
require 'config.php';
session_start();

print_r($_POST);
echo "<br> powyżej post <bR><bR><bR>";
print_r($_GET);
echo "<br> powyżej get <bR><bR><bR>";
print_r($_SESSION);
echo "<br> powyżej session <bR><bR><bR>";


///////////////////////////////

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

$unit_name = "";
$unit_address = "";
$stm = $db->prepare("SELECT unit_name, unit_address from unit where unit_id = :unit_id");
$stm->bindValue(':unit_id', $selected_unit);
$score = $stm->execute();

while ($row = $score->fetchArray(1)) {
    $unit_name = $row['unit_name'];
    $unit_address = $row['unit_address'];
};

$end_date = $_POST [end-date];
$title = $_POST [title];
$secret_code = $_POST [secret-code];
$id_user = $_POST [id-user];
$box = $_POST [box];
$id = $_POST [id];



function update_box($db, $end_date, $title, $secret_code, $id_user, $box, $id){

// TODO: not working 
        $stm = $db->prepare("UPDATE user set date_insertion = :end_date, title = :title, access_code = :secret_code, user_id = :id_user where box_nr = :box and unit_id = :id");
        $stm->bindValue(':end_date', $end_date);
        $stm->bindValue(':title', $title);
        $stm->bindValue(':secret_code', $secret_code);
        $stm->bindValue(':id_user', $id_user);
        $stm->bindValue(':box', $box);
        $stm->bindValue(':id', $id);
        $stm->execute();
}

function selected_box($db, $selected_unit, $selected_box){

    $score_box = $db->query("SELECT * from user where box_nr = $selected_box and unit_id = $selected_unit");
    //    $stm_box->bindValue(':unit_id', $selected_unit);
    //    $score_box = $stm_box->execute();
    $date_insertion = "";
    $title = "";
    $access_code = "";
    $user_id = "";
    while ($row_box = $score_box->fetchArray(1)) {
        $date_insertion = $row_box['date_insertion'];
        $title = $row_box['title'];
        $access_code = $row_box['access_code'];
        $user_id = $row_box['user_id'];
    };
    $box_data = array($title, $access_code, $date_insertion, $user_id);


    return $box_data;
}

$box_info = selected_box($db, $selected_unit, $selected_box);

/////////////////////

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
            </ul>
        </nav>
        <main role="main">
            <section>
                <h3 class=""><?php echo $unit_name . ", " . $unit_address; ?></h3>
            </section>
            <form action="./edit-box.php" method="post">
                <div class="edycja">

                    <div class="flekser"><span class="box-number"><?php echo $selected_box; ?></span>
                        <!-- div class="panel-checkboxes"><label>skrytka pełna<br><input type="checkbox"></label></div -->
                        <div class="panel-checkboxes"><input type="reset"  id="" value="przywróć dane"><br>
                        <input type="button" id="clear_fields" value="wyczyść pola"></div>
                    </div>
                    <div class="descriptive-container">
                        <div class="descriptive-container-end"><label for="title">Tytuł</label></div>
                        <div class=""><input class="" type="text" name="title" id="box-title" value="<?php echo $box_info[0]; ?>">
                        </div>
                        <div class="descriptive-container-end"><label for="secret-code" class="">kod do skrytki</label></div>
                        <div class=""><input class="" id="box-secret-code" name="secret-code" value="<?php echo $box_info[1]; ?>" type="number"></div>
                        <div class="descriptive-container-end"><label for="end-date" class="">data opróżnienia</label></div>
                        <div class=""><input class="" id="box-end-date" name="end-date" value="<?php echo $box_info[2]; ?>" type="date"></div>
                        <div class="descriptive-container-end"><label for="title" class="">id czytelnika</label></div>
                        <div class=""><input class="" id="box-id-user" name="id-user" value="<?php echo $box_info[3]; ?>"></div>
                        <input type="hidden" value="<?php echo $selected_box; ?>" name="box">
                        <input type="hidden" value="<?php echo $selected_unit; ?>" name="id">
                    </div>
                </div>

                <button type="submit" value="Zapisz zmiany i idź na stronę główną">Zapisz zmiany i idź na stronę główną</button>
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
    <script src="./script-box.js"></script>
</body>

</html>