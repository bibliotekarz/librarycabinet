<?php
require 'config.php';
session_start();
/*
print_r($_POST);
echo "<br> powyżej post <bR><bR><bR>";
print_r($_GET);
echo "<br> powyżej get <bR><bR><bR>";
print_r($_SESSION);
echo "<br> powyżej session <bR><bR><bR>";
*/

///////////////////////////////

class MyDB extends SQLite3
{
    function __construct($dbfile)
    {
        $this->open($dbfile);
    }
}

$db = new MyDB($dbfile);

$selected_unit = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);


// machine list 
$stm = $db->query("SELECT unit_id, unit_name, unit_address, number_box FROM unit");
$counter_unit = 0;
while ($row = $stm->fetchArray(1)) {
    $unit_id = $row['unit_id'];
    $unit_name = $row['unit_name'];
    $unit_address = $row['unit_address'];
    $unit_size = $row['number_box'];
    $unit = "<li><a href=\"?id=$unit_id\">$unit_name, $unit_address.</a></li>";
    $all_units .= $unit;
    $counter_unit++;
}

if ($counter_unit == 1){
    $selected_unit = $unit_id;
};

function selected_machine($db, $selected_unit)
{
    $unit_name = "";
    $unit_address = "";
    $number_box = "";
    $stm = $db->prepare("SELECT * from unit where unit_id = :unit_id");
    $stm->bindValue(':unit_id', $selected_unit);
    $score = $stm->execute();

    while ($row = $score->fetchArray(1)) {
        $unit_name = $row['unit_name'];
        $unit_address = $row['unit_address'];
        $number_columns = $row['number_columns'];
        $number_box = $row['number_box'];
    };
    $numbox = 1;
    $k = 1;
    $r = 0;
    $drawing_start = "\n<div  class=\"edycja\">\n<div id=\"row_$r\">";
    $drawing_body = "";
    while ($numbox <= $number_box) {
        $drawing_body = $drawing_body . "<span class=\"machine-box\"> $numbox </span> ";
        if ($k < $number_columns) {
            $k++;
            $r++;
        } else {
            $k = 1;
            $drawing_body = $drawing_body . "</div>\n<div id=\"row_$r\">";
        }
        $numbox++;
    }
    $drawing_stop = "</div></div>\n";
    $drawing_selected_machine = $drawing_start . $drawing_body . $drawing_stop;
    $unit_info = array($drawing_selected_machine, $unit_name, $unit_address);


    return $unit_info;
}

$unit_info = selected_machine($db, $selected_unit);

$select_machine = $unit_info[1] . " " . $unit_info[2];

$all_units_after = "";
if (is_int($selected_unit)) {
    $unit_template = $unit_info[0];
    $all_units_after = $all_units;
    $all_units = "";
    $select_machine_after = $info['select_machine'];
} else {
    $select_machine = $info['select_machine'];
    $unit_template = "";
    $select_machine_after = "";
}


/////////////////////

echo $page_head . "\n\t\t<title>" . $info['machine_title']; ?></title>
</head>

<body>
    <?php
    if (isset($_SESSION["name"])) {
    ?>
        <header class="page-header">
            <h1><?php echo $info['machine_message']; ?></h1>
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
                <h3 class=""><?php echo $select_machine; ?></h3>
                <?php echo $all_units; ?>
            </section>
            <section>
                <h3 class=""><?php echo $unit_template;  ?></h3>
                <h3><?php echo $select_machine_after; ?></h3>
                <?php echo $all_units_after; ?>
            </section>
        </main>
    <?php

    } else {
        echo '<header class="page-header">
        <h1>' . $info["machine_message"] . '</h1>
        <h2><a href="login.php">' . $info["login_first"] . '</a></h2>
    </header>';
    }

    ?>
    <script src="./script.js"></script>
</body>

</html>