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

if ($counter_unit == 1) {
    $selected_unit = $unit_id;
};

function selected_machine($db, $selected_unit, $info)
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
    $drawing_start = "\n<div  class=\"edycja\">\n<!-- div id=\"row_$r\" -->";
    $drawing_body = "";
    while ($numbox <= $number_box) {
        ///////// single box content start
        $stm = $db->prepare("SELECT unit_name, unit_address from unit where unit_id = :unit_id");
        $stm->bindValue(':unit_id', $selected_unit);
        $score = $stm->execute();

        while ($row = $score->fetchArray(1)) {
            $unit_name = $row['unit_name'];
            $unit_address = $row['unit_address'];
        };

        $stm_box = $db->prepare("SELECT * from user where box_nr = :numbox and unit_id = :unit_id");
        $stm_box->bindValue(':numbox', $numbox);
        $stm_box->bindValue(':unit_id', $selected_unit);
        $score_box = $stm_box->execute();

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

        // TODO: probably in the future, many items will be added to one box
        // $box_full = "<!-- div class=\"panel-checkboxes\"><label>" . $info['box_full'] . "<br><input type=\"checkbox\"></label></div -->\n";
        $box_full = "";
        if (strlen($date_insertion) > 1) {
            $single_box = "<div class=\"machine-box pelna  \">
        <div class=\"\"><a href=\"edit-box.php?box=$numbox&id=$selected_unit\"><span class=\"box-number\">$numbox</span></a>
        $box_full</div><a href=\"edit-box.php?box=$numbox&id=$selected_unit\" class=\"decoration-none\">
        <div>" . $info['book_title'] . " <span class=\"main-data\">$title</span>
        </div>
        <div>" . $info['lockbox_code'] . " <span class=\"main-data\">$access_code</span></div>
        <div>" . $info['date_emptying'] . " <span class=\"highlighted-description\">$date_insertion</span></div>
        <div>" . $info['reader_id'] . " <span class=\"highlighted-description\">$user_id</span></div>
        </a>\n</div>";
        } else {
            $single_box = "<div class=\"grid-item pusta\">
        <div class=\"\"><a href=\"edit-box.php?box=$numbox&id=$selected_unit\"><span class=\"box-number\">$numbox</span></a>
        $box_full<p class=\"alert\"><a href=\"edit-box.php?box=$numbox&id=$selected_unit\">" . $info['box_empty'] . "</a></p>
        </div>\n</div>";
        }

        ///////// single box content end

        // consider whether the column layout makes sense  
        $drawing_body = $drawing_body . "<!-- span class=\"machine-box\" --> $single_box <!-- /span --> ";
        if ($k < $number_columns) {
            $k++;
            $r++;
        } else {
            $k = 1;
            $drawing_body = $drawing_body . "<!-- /div>\n<div id=\"row_$r\" -->";
        }
        $numbox++;
    }
    $drawing_stop = "<!-- /div --></div>\n";
    $drawing_selected_machine = $drawing_start . $drawing_body . $drawing_stop;
    $unit_info = array($drawing_selected_machine, $unit_name, $unit_address);


    return $unit_info;
}

$unit_info = selected_machine($db, $selected_unit, $info);

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
                <li><?php echo $info['machine_page']; ?></li>
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
</body>

</html>