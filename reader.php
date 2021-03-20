<?php
header("X-Clacks-Overhead: GNU Terry Pratchett");
require 'config.php';
/*
print_r($_POST);
echo "<br> powyżej post <bR><bR><bR>";
*/

class MyDB extends SQLite3
{
    function __construct($dbfile)
    {
        $this->open($dbfile);
    }
}

$db = new MyDB($dbfile);

function unit_name($db, $user_id_sanitized)
{
    // TODO:  add support for multiple library cabinets 
    $stm = $db->prepare('SELECT unit_name, unit_address, user.box_nr as user_box, user.access_code as user_code FROM unit INNER JOIN user ON user.unit_id = unit.unit_id WHERE user.user_id = :user_id ORDER BY user.unit_id, user.box_nr;');
    $stm->bindValue(':user_id', $user_id_sanitized);
    $address = $stm->execute();

    $unit_data_all = array();
    while ($rows = $address->fetchArray(1)) {
        $library_name = $rows['unit_name'];
        $library_address = $rows['unit_address'];
        $user_box = $rows['user_box'];
        $user_code = $rows['user_code'];
        if (strlen($user_code) > 0) {
            $unit_data = array("box" => $user_box, "name" => $library_name, "address" => $library_address);
            array_push($unit_data_all, $unit_data);
        } else {
            $unit_data_all = "";
        }
    }
    return $unit_data_all;
}

if (count($_POST) > 0) {
    $user_id_sanitized_int = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

    // FIXME: To filter IDN domains in emails the server must support "intl" php module 
    /* 
    $user_id_email = explode("@", $_POST['user_id']);
    $host = idn_to_ascii($user_id_email[1]);
    $user = strtolower($user_id_email[0]);
    $user_id_processed_email = $user."@".$host;
    $user_id_filtered_email = filter_var($user_id_processed_email, FILTER_VALIDATE_EMAIL);
    */

    $user_id_filtered_email = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_EMAIL);
    $user_id_filtered_email = strtolower($user_id_filtered_email);

    $user_id_sanitized = $user_id_filtered_email . $user_id_sanitized_int;


    if (is_string($user_id_sanitized)) {
        $unit_data_all = unit_name($db, $user_id_sanitized);
    }

    
        if (isset($unit_data_all[0]['box'])) {

        // user has the book in the locker 
        $status = "statuson";
        $status1 = "statusoff";

        $i = 0;
        $user_message_all = "";
        while ($i < count($unit_data_all)) {

            $library_name = $unit_data_all[$i]['name'];
            $library_address = $unit_data_all[$i]['address'];
            $user_box = $unit_data_all[$i]['box'];

            $user_message = "<li>Skrytka nr " . $user_box . " w " . $library_name . ", " . $library_address . "</li>";
            $user_message_all = $user_message_all . $user_message;
            $i++;
        }

        $tresc = "<h3>" . $info['box_found'] . $user_id_sanitized . " </h3><div class=\"flekserc\">\n<ul>" . $user_message_all . "</ul></div>";
     
        $header_info = $library_name;


    } elseif (strlen($user_id_sanitized) > 5) {

        // user good locker no 
        $status = "statuson";
        $status1 = "statuson";
        $tresc = "<h3 class='alert'>" . $info['no_user_id'] . $user_id_sanitized . " </h3>";
        $header_info = $info['login_message'];
    } else {
        // user has not passed validation / empty / letter / fewer digits than 6 
        $status = "statuson";
        $status1 = "statuson";
        $tresc = "<h3 class='alert'>" . $info['bad_user'] . " </h3>";
        $header_info = $info['login_message'];
    }
} else {
    // no post variable 
    $status = "statusoff";
    $status1 = "statuson";
    $tresc =  "<h2>" . $info['start_user'] . " </h2>";
    $header_info = $info['login_message'];
}

echo $page_head . "\n\t\t<title>" . $info['title_info']; ?></title>
</head>

<body>
    <header class="page-header">
        <h1><?php echo $header_info; ?></h1>
        <h2 class="<?php echo $status; ?>"><?php echo $library_address; ?></h2>
    </header>
    <main role="main"> 
        <section class="edycja <?php echo $status; ?>">
            <div class="">
                <?php echo $tresc; ?>
            </div>
        </section>
        <section class="edycja <?php echo $status1; ?>">
            <form method="post" action="" class="">
                <div class="flekser">
                    <h4><?php echo $info['head_info']; ?></h4>
                    <div class=""><input class="" name="user_id" name="id-user">
                    </div>
                </div>
                <div>
                    <input type="submit" name="submit" value="<?php echo $info['send_button']; ?>">
                </div>
            </form>
        </section>

    </main>
</body>

</html>