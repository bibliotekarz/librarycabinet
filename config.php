<?php
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
$dbfile = './librarycabinet.db';