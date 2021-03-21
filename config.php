<?php
// ISO 639-1 Language Codes
$lang = "pl";
//$lang = "en";

require 'lang/' . $lang . '.php';

$user_id_sanitized = 0;
$has_book  = 0;
$library_name = "";
$library_address = "";
$dbfile = 'librarycabinet.db';
$message = "";
$all_units = "";
$all_librarians = "";
$librarian_action_message = "";
$machine_action_message = "";

// default page header 
$page_head = "<!DOCTYPE html>
<html lang=\"$lang\">
    <head>
        <meta charset=\"UTF-8\" />
        <link rel=\"stylesheet\" type=\"text/css\" href=\"../style.css\" />
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
        <link rel=\"icon\" type=\"image/svg+xml\" href=\"librarycabinet.svg\">
        <link rel=\"mask-icon\" href=\"librarycabinet.svg\" color=\"#66cd00\">";
