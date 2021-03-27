<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/svg+xml" href="img/librarycabinet.svg">
        <link rel="mask-icon" href="img/librarycabinet.svg" color="#66cd00">

        <title>Strona główna obsługi książkomatów</title>
</head>

<body>
    <header class="page-header">
            <h1>Obsługa Książkomatów</h1>
        </header>
        <nav>
            <ul>
                <?php //TODO: add translate ?>
                <li><a href='machine.php' title='Zarządzaj skrytkami w książkomacie'>Sprawdź czy masz coś skrytkach w książkomacie.</a></li>
                <li><a href='admin.php' title='Zarządzaj bibliotekarzami i książkomatami'>Dla bibliotekarzy.</a></li>
                <li>Kliknij żeby się <a href='logout.php' title='Logout'>wylogować.</a></li>
            </ul>
        </nav>
        <main role="main">
        </main>
        <footer>
            <a href="https://github.com/bibliotekarz/librarycabinet-analog" alt="Link to source">Library Cabinet Analog</a></footer>
        </footer>
    <script src="js/script-box.js"></script>
</body>

</html>