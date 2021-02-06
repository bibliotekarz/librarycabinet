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
$costam = [];
$costam[1]['box_nr'] = "";

if (count($_POST) > 0) {
    $db = new MyDB($dbfile);
    $user_id_sanitized = filter_var($_POST["user_id"], FILTER_VALIDATE_INT);
    if (is_int($user_id_sanitized)) {

        $sql = 'SELECT box_nr FROM "user" where user_id = ' . $user_id_sanitized;
        $row = $db->querySingle($sql);

        $stm = $db->prepare('SELECT box_nr FROM "user" where user_id = :user_id ');
        $stm->bindValue(':user_id', $user_id_sanitized);
        $score = $stm->execute();

        while ($rowy = $score->fetchArray(1)) {
            $costam[] = $rowy;
        }

    } else {
        $row = "";
        
    }

    echo "<br>costam start<br>";
    echo "<pre>";
   print_r($costam);
    echo "</pre>";

    foreach ($costam as $v1) {
        foreach ($v1 as $v2) {
            echo "$v2 <br>\n";

        }
    }

    // $comma_separated = implode(",", $costam[0]);

    // print_r ($comma_separated);


    echo "+++++++++++++" . $costam[1]['box_nr'];

    if (is_int($costam[1]['box_nr'])) {
        
        // user posiada książkę w skrytce
        $status = "statusoff33";
        $status1 = "statuson34";
        $query_result = " jest wynik z row linia 35";
    } else {
        // user nie przeszedł walidacji albo nie ma nic w skrytce
        $status = "statuson38";
        $status1 = "statuson39";
        $query_result = " brak wyniku z row linia 40";
    }
} else {
    // brak danych w zmiennej post
    $status = "statuson44";
    $status1 = "statuson45";
    $query_result = " brak post linia 46";
}

$info = [
    "title_info" => "Numer skrytki użytkownika ",
    "no_user_id" => "Nie ma skrytki z zawartością dla Użytkownika o identyfikatorze ",
    "bad_user" => "Nie ma takiego użytkownika. <br>Jako identyfikator użytkownika podaj tylko cyfry.",
    "start_user" => "Zaczynamy :)",
    "box_found" => "Nr skrytki użytkownika ",
    "head_info" => "Wprowadź nr użytkownika żeby sprawdzić numer swojej skrytki z książkami ",
    "send_button" => "Wyślij",
];

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
                <?php
                // :TODO: zmienić warunki/zasady obsługi kilku skrytek
                if ($user_id_sanitized > 0) {
                    if (is_numeric($costam[2]['box_nr'])) {
                        echo "<h3>" . $info['box_found'] . $user_id_sanitized . "</h3>";
                        foreach ($costam as $v1) {
                            foreach ($v1 as $v2) {
                                echo "<span class='box-number'>$v2 </span>\n";
                            }
                        }
                        echo " <br>" . $query_result . " <br>";
                    } else {
                        echo "<h3 class='alert'>" . $info['no_user_id'] . $user_id_sanitized . "</h3>";
                        echo " <br>" . $query_result . " <br>";
                    }
                } elseif (count($_POST) <= 0) {

                    echo  "<h2>" . $info['start_user'] . "</h2>";
                    echo " <br>" . $query_result . " <br>";
                } else {

                    echo "<h3 class='alert'>" . $info['bad_user'] . "</h3>";
                    echo " <br>" . $query_result . " <br>";
                };
                ?>
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