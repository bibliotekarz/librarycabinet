<?php
require 'config.php';
session_start();
/*
print_r($_POST);
echo "<br> powyżej post <bR><bR><bR>";
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
    $unit = "<li>" . $info['id'] . $unit_id . ". " . $unit_name . ". " . $unit_address . $info['lockers'] . $unit_size . ".</li>";
    $all_units .= $unit;
}

// TODO: add support $select_machine
if (false){
$select_machine = "<h2 class=\"\">" . $info['select_machine'] . "</h2>" . $all_units ;
}else{
    $select_machine = "<h2 class=\"\">" . $info['default_machine'] . "</h2>";
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
            <?php echo $select_machine; ?>
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