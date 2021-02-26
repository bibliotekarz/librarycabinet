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
    "indefinite" => "nieokreślony.",
    "librarian_title" => "Serwis techniczny książkomatu",
    "login_title" => "Strona logowania",
    "login_first" => "Najpierw się zaloguj",
    "login_alert" => "Zły email lub hasło",
    "login_name" => "Email Bibliotekarza",
    "login_pass" => "Hasło Bibliotekarza",
    "admin_title" => "Strona administracji",
    "admin_message" => "Strona zarządzania bibliotekarzami i lokalizacjami książkomatów",
    "admin_login_info" => "Jesteś zalogowany jako ",
    "admin_logout" => "Kliknij żeby się <a href='logout.php' title='Logout'>wylogować.</a>",
    "admin_librarian_managment"=>"Zarządzanie bibliotekarzami",
    "admin_managing_machines"=>"Zarządzanie książkomatami",
    "admin_list_librarians"=> "Lista bibliotekarzy",
    "admin_list_machines"=> "Lista książkomatów",
    "admin_machine_id"=>"Id książkomatu",
    "admin_machine_name"=>"Nazwa książkomatu",
    "admin_machine_address"=>"Adres książkomatu",
    "admin_machine_size"=>"Ilość skrytek",
    "admin_machine_column"=> "Ilość kolumn",
    "account"=>"konto",
    "password"=>"hasło",
    "machine"=>"książkomat",
    "add"=>"Dodaj",
    "remove"=>"Usuń",
    "update"=>"Aktualizuj"
];
// $info['login_name']

$user_id_sanitized = 0;
$has_book  = 0;
$library_name = $info['indefinite'];
$library_address = "";
$dbfile = './librarycabinet.db';
$message = "";
$all_units = "";
$all_librarians = "";
$librarian_action_message ="";
$machine_action_message ="";

// default page header 
$page_head = "<!DOCTYPE html>
<html lang=\"pl-PL\">
    <head>
        <meta charset=\"UTF-8\" />
        <link rel=\"stylesheet\" type=\"text/css\" href=\"./style.css\" />
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
