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
];

$costam = [];
// $tresc = $info['start_user'];
$costam[0]['box_nr'] = "";

if (count($_POST) > 0) {
    $user_id_sanitized_int = filter_var($_POST["user_id"], FILTER_VALIDATE_INT);
    $user_id_sanitized_email = filter_var($_POST["user_id"], FILTER_VALIDATE_EMAIL);
    $user_id_sanitized_email = strtolower($user_id_sanitized_email);

    if (is_int($user_id_sanitized_int)) {
        $sent_condition = "number";
    } elseif (stristr($user_id_sanitized_email, '@')) {
        $sent_condition = "email";
    } else {
        $sent_condition = "empty";
    }

    $user_id_sanitized = $user_id_sanitized_email . $user_id_sanitized_int;

    if (is_string($user_id_sanitized)) {
        $db = new MyDB($dbfile);
        $stm = $db->prepare('SELECT box_nr FROM "user" where user_id = :user_id ');
        $stm->bindValue(':user_id', $user_id_sanitized);
        $score = $stm->execute();

        while ($rowy = $score->fetchArray(1)) {
            $costam[] = $rowy;
        }
    } // else {
    //    $row = "";
    // }


    foreach ($costam as $v1) {
        foreach ($v1 as $v2) {
            echo "wynik $v2 <br>\n";
        }
    }
    
    // :TODO: dopracować warunki
    if (is_numeric($costam[0]['box_nr'])) {
        // user posiada książkę w skrytce
        $status = "statusoff33";
        $status1 = "statuson34";
        foreach ($costam as $v1) {
            foreach ($v1 as $v2) {
                $array_loop = $array_loop . "<span class='box-number'>$v2 </span>\n";
            }
        }
        $tresc = "<h3>" . $info['box_found'] . $user_id_sanitized . "</h3>\n" . $array_loop;
    } elseif (strlen($user_id_sanitized) > 5) { // przejmuje wszystkich
        // user dobry skrytki brak
        $status = "statuson38";
        $status1 = "statuson39";
        $tresc = "<h3 class='alert'>" . $info['no_user_id'] . $user_id_sanitized . "</h3>";
    } else {
        // user nie przeszedł walidacji /puste /litera /mniej cyfr niż 6
        $status = "statuson38";
        $status1 = "statuson39";
        $tresc = "<h3 class='alert'>" . $info['bad_user'] . "</h3>";
    }
} else {
    // brak danych w zmiennej post
    $status = "statuson83";
    $status1 = "statuson84";
    $tresc =  "<h2>" . $info['start_user'] . "</h2>";
}



?>
<html>

<head>
    <title><?php echo $info['title_info']; ?></title>

    <link rel="stylesheet" type="text/css" href="../style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <header class="page-header">
        <h1>Książkomat biblioteka dla dzieci nr 2</h1>
        <h2>ulica Błotna 44</h2>
    </header>
    <main role="main">
        <section class="edycja "> <?php echo " staus section 2 " . $status1; ?>
            <div>
                <?php echo $tresc; ?>
            </div>
        </section>
        <section class="edycja"><?php echo " status sekcja 1 " . $status; ?>
            <form method="post" action="" class="">
                <div class="flekser">
                    <h4><?php echo $info['head_info']; ?></h4>
                    <div class=""><input class="" name="user_id" name="id-user"><!-- type="number" -->
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