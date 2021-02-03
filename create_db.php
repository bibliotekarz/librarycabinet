<?php

$file = './sample_database.sql';
$dbfile = './librarycabinet.db';

class MyDB extends SQLite3
{
    function __construct($dbfile)
    {
        $this->open($dbfile);
    }
}
if (file_exists($file) && (file_exists($dbfile) == false)) {
    $sql = file_get_contents($file);
    $db = new MyDB($dbfile);
    $db->exec($sql);
    rename($file, "used.sql");
    echo "Database created, file ". $file ." renamed to used.sql ";
} else {
    echo "Sql file ". $file ." is missing or database ". $dbfile ." already exists.";
    exit;
}
