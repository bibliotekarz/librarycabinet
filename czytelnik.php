<?php
$dbfile = './librarycabinet.db';

class MyDB extends SQLite3
{
    function __construct($dbfile)
    {
        $this->open($dbfile);
    }
}

$user_id_sanitized = 0;
$info = [
    "title_info" => "Numer skrytki użytkownika ",
    "no_user_id" => "Nie ma skrytki z zawartością dla Użytkownika o identyfikatorze ",
    "bad_user" => "Nie ma takiego użytkownika. <br>Jako identyfikator użytkownika podaj adres email albo numer użytkownika. ",
    "start_user" => "Zaczynamy :) ",
    "box_found" => "Nr skrytki użytkownika ",
    "head_info" => "Wprowadź adres email lub nr użytkownika żeby sprawdzić numer swojej skrytki z książkami ",
    "send_button" => "Wyślij",
    "librarycabinet" => "Książkomat ",
];


$has_book  = 0;

if (count($_POST) > 0) {
    $user_id_sanitized_int = filter_var($_POST["user_id"], FILTER_VALIDATE_INT);
    $user_id_sanitized_email = filter_var($_POST["user_id"], FILTER_VALIDATE_EMAIL);
    $user_id_sanitized_email = strtolower($user_id_sanitized_email);

    $user_id_sanitized = $user_id_sanitized_email . $user_id_sanitized_int;

    if (is_string($user_id_sanitized)) {
        $db = new MyDB($dbfile);
        $stm = $db->prepare('SELECT box_nr FROM "user" where user_id = :user_id ');
        $stm->bindValue(':user_id', $user_id_sanitized);
        $score = $stm->execute();
        $array_loop = "";
        while ($rowy = $score->fetchArray(1)) {
            $rowy_str = implode($rowy);
            $array_loop = $array_loop . "<span class='box-number'> " . $rowy_str . " </span>\n";
            $has_book = 1;
        }
    }

    if ($has_book == 1) {
        // user posiada książkę w skrytce
        $status = "statusoff";
        $status1 = "statuson";
        $tresc = "<h3>" . $info['box_found'] . $user_id_sanitized . "</h3>\n" . $array_loop;
    } elseif (strlen($user_id_sanitized) > 5) {
        // user dobry skrytki brak
        $status = "statuson";
        $status1 = "statuson";
        $tresc = "<h3 class='alert'>" . $info['no_user_id'] . $user_id_sanitized . "</h3>";
    } else {
        // user nie przeszedł walidacji /puste /litera /mniej cyfr niż 6
        $status = "statuson";
        $status1 = "statuson";
        $tresc = "<h3 class='alert'>" . $info['bad_user'] . "</h3>";
    }
} else {
    // brak danych w zmiennej post
    $status = "statuson";
    $status1 = "statuson";
    $tresc =  "<h2>" . $info['start_user'] . "</h2>";
}

$library_name = "nazwa beki";
$library_adress ="adres ksiazkomatu";


?>
<html>

<head>
    <title><?php echo $info['title_info']; ?></title>

    <link rel="stylesheet" type="text/css" href="../style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <header class="page-header">
        <h1><?php echo $info['librarycabinet'] . $library_name; ?></h1>
        <h2><?php echo $library_adress; ?></h2>
    </header>
    <main role="main">
        <section class="edycja <?php echo $status1; ?>"> 
            <div>
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