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
    "admin_message" => "Zarządzanie bibliotekarzami i lokalizacjami książkomatów.",
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
    "update"=>"Aktualizuj",
    "id"=>" Id. ",
    "add_librarian_account"=>"Dodano konto bibliotekarza ",
    "account_exists"=>"Istnieje już konto bibliotekarza ",
    "wrong_email_password"=>"Niewłaściwy email lub hasło",
    "account_deleted"=>"Skasowano konto bibliotekarza ",
    "enter_correctly_account"=>"Wprowadź poprawnie konto do skasowania",
    "password_librarian_changed"=>"Zmieniono hasło do konta bibliotekarza ",
    "account_not_exist"=>"Nie istnieje konto bibliotekarza ",
    "machine_added"=>"Dodano książkomat ",
    "not_all_data_entered_correctly"=>"BŁAD nie wszystkie dane wprowadzono poprawnie.",
    "machine_deleted"=>"Skasowano książkomat o id: ",
    "no_machine_with_id"=>"Nie ma książkomatu o id: ",
    "enter_correct_id_machine"=>"Wprowadź poprawne id książkomatu do skasowania",
    "machine_data_changed"=>"Zmieniono dane książkomatu ",
    "no_machine"=>"Nie istnieje książkomat ",
    "lockers"=>". Skrytek ",
    "last_machine"=>"Nie możesz skasować ostatniego książkomatu.",
    "last_librarian"=>"Nie możesz skasować ostatniego bibliotekarza.",
    "machine_title"=>"Strona zarządzania skrytkami w książkomacie.",
    "machine_message"=>"Zarządzanie skrytkami w książkomacie.",
    "login_message"=>"Zaloguj sie do systemu obsługi książkomatów.",
    "admin_page"=>"<a href='admin.php' title='Zarządzaj bibliotekarzami i książkomatami'>Zarządzaj bibliotekarzami i książkomatami.</a>",
    "machine_page"=>"<a href='machine.php' title='Zarządzaj skrytkami w książkomacie'>Zarządzaj skrytkami w książkomacie.</a>",
    "select_machine"=>"Wybierz książkomat",
    "default_machine"=>"Domyślny książkomat",
    "content_update"=>"Aktualizacja zawartości skrytki",
    "restore_data"=>"Przywróć dane",
    "book_title"=>"Tytuł",
    "lockbox_code"=>"Kod do skrytki",
    "date_emptying"=>"Data opróżnienia",
    "reader_id"=>"Identyfikator użytkownika",
    "save_changes"=>"Zapisz zmiany",
    "clear_fields"=>"Opróżnij skrzynkę"
];
// $info['machine_page']

$user_id_sanitized = 0;
$has_book  = 0;
$library_name = $info['indefinite'];
$library_address = "";
$dbfile = '../librarycabinet.db';
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
        <link rel=\"stylesheet\" type=\"text/css\" href=\"../style.css\" />
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
        <link rel=\"icon\" type=\"image/svg+xml\" href=\"../librarycabinet.svg\">
        <link rel=\"mask-icon\" href=\"../librarycabinet.svg\" color=\"#66cd00\">";    
