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


// machine list 
$stm = $db->query("SELECT unit_id, unit_name, unit_address, number_box FROM unit");
while ($row = $stm->fetchArray(1)) {
    $unit_id = $row['unit_id'];
    $unit_name = $row['unit_name'];
    $unit_address = $row['unit_address'];
    $unit_size = $row['number_box'];
    $unit = "<li><a href=\"?id=$unit_id\">$unit_name, $unit_address.</a></li>";
    $all_units .= $unit;
}

function selected_machine($ror)
{
return " wyrysuj książkomat $ror  <br>";

}

$selected_unit = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);



if (is_int($selected_unit)) {
    
// TODO: look for a solution so that the prepare does not make a mistake 
    $stm = $db->query("SELECT unit_name, unit_address FROM unit Where unit_id = $selected_unit ");
    // $stm = $db->prepare("SELECT unit_name, unit_address from unit where unit_id = :unit_id");
    // $stm->bindValue(':unit_id', $selected_unit);
    // $stm->execute();
    while ($row = $stm->fetchArray(1)) {
        $unit_name = $row['unit_name'];
        $unit_address = $row['unit_address'];
        $select_machine = $unit_name . " " . $unit_address;
    }
    $all_units = "";
    $unit_template = selected_machine($selected_unit);
} else {
    $select_machine =  $info['select_machine'];
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
            </ul>
        </nav>
        <main role="main">
            <section>
                <h2 class=""><?php echo $select_machine; ?></h2>
                <?php echo $all_units; ?>
            </section>
            <section>
                <h2 class=""><?php echo $unit_template; ?></h2>
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