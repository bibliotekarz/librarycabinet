<?php
$dbfile = './librarycabinet.db';

class MyDB extends SQLite3
{
    function __construct($dbfile)
    {
        $this->open($dbfile);
    }
}

$info = [
    "title_info" => "Numer skrytki użytkownika ",
    "no_user_id" => "Nie ma skrytki z zawartością dla Użytkownika o identyfikatorze ",
    "bad_user" => "Nie ma takiego użytkownika. <br>Jako identyfikator użytkownika podaj adres email albo numer użytkownika. ",
    "start_user" => "Zaczynamy :) ",
    "box_found" => "Nr skrytki użytkownika ",
    "head_info" => "Wprowadź adres email lub nr użytkownika żeby sprawdzić numer swojej skrytki z książkami ",
    "send_button" => "Wyślij",
    "librarycabinet" => "Książkomat ",
    "indefinite" => "nieokreślony."
];

$user_id_sanitized = 0;
$has_book  = 0;
$library_name = $info['indefinite'];
$library_address = "";

if (count($_POST) > 0) {
    $user_id_sanitized_int = filter_var($_POST["user_id"], FILTER_VALIDATE_INT);
    $user_id_sanitized_email = filter_var($_POST["user_id"], FILTER_VALIDATE_EMAIL);
    $user_id_sanitized_email = strtolower($user_id_sanitized_email);
    // TODO: additional support for emails from idn domains 
    $user_id_sanitized = $user_id_sanitized_email . $user_id_sanitized_int;

    if (is_string($user_id_sanitized)) {
        $db = new MyDB($dbfile);
        $stm = $db->prepare('SELECT box_nr FROM "user" WHERE user_id = :user_id ');
        $stm->bindValue(':user_id', $user_id_sanitized);
        $score = $stm->execute();
        // TODO:  add support for multiple library cabinets 
        $stm = $db->prepare('SELECT unit_name, unit_address FROM unit INNER JOIN user ON user.unit_id = unit.unit_id WHERE user.user_id = :user_id LIMIT 1;');
        $stm->bindValue(':user_id', $user_id_sanitized);
        $address = $stm->execute();
        $array_loop = "";
        while ($rows = $score->fetchArray(1)) {
            $rows_str = implode($rows);
            $array_loop = $array_loop . "<span class='box-number'> " . $rows_str . " </span>\n";
            $has_book = 1;
        }
        while ($rows = $address->fetchArray(1)) {
            $library_name = $rows['unit_name'];
            $library_address = $rows['unit_address'];
        }
    }

    if ($has_book == 1) {
        // user has the book in the locker 
        $status = "statusoff";
        $status1 = "statuson";
        $tresc = "<h3>" . $info['box_found'] . $user_id_sanitized . "</h3><div class=\"flekserc\">\n" . $array_loop . "</div>";
    } elseif (strlen($user_id_sanitized) > 5) {
        // user good locker no 
        $status = "statuson";
        $status1 = "statuson";
        $tresc = "<h3 class='alert'>" . $info['no_user_id'] . $user_id_sanitized . "</h3>";
    } else {
        // user has not passed validation / empty / letter / fewer digits than 6 
        $status = "statuson";
        $status1 = "statuson";
        $tresc = "<h3 class='alert'>" . $info['bad_user'] . "</h3>";
    }
} else {
    // no post variable 
    $status = "statuson";
    $status1 = "statuson";
    $tresc =  "<h2>" . $info['start_user'] . "</h2>";
}


?>
<html>

<head>
    <title><?php echo $info['title_info']; ?></title>

    <link rel="stylesheet" type="text/css" href="./style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <header class="page-header">
        <h1><?php echo $info['librarycabinet'] . $library_name; ?></h1>
        <h2><?php echo $library_address; ?></h2>
    </header>
    <main role="main">
        <section class="edycja <?php echo $status1; ?>">
            <div class="">
                <?php echo $tresc; ?>
            </div>
        </section>
        <section class="edycja <?php echo $status; ?>">
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