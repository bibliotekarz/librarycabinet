<?php
require 'config.php';
session_start();


echo $page_head . "\n\t\t<title>" . $info['admin_title']; ?></title>
</head>

<body>
    <?php
    if ($_SESSION["name"]) {

    ?>
        <header class="page-header">
            <h1><?php echo $info['admin_message']; ?></h1>
            <?php echo $info['admin_login_info'] . " <b>" . $_SESSION['name']; ?></b>.<br>
            <?php echo $info['admin_logout']; ?><br>
        </header>

        <main role="main">
            <section>
                <h2 class=""><?php echo $info['admin_librarian_managment']; ?></h2>
                <div class="edycja">
                    <form name="admin_librarians" method="post" action="" class="">
                        <div class="flekser">
                            <label for="librarian_login"><?php echo $info['login_name']; ?></label> <input type="email" class="" id="id-librarian_login" name="librarian_login" value="<!-- z bazy albo puste -->">
                        </div>
                        <div class="flekser">
                            <label for="librarian_pass"><?php echo $info['login_pass']; ?></label> <input type="password" class="" id="id-librarian_pass" name="librarian_pass">
                        </div>
                        <div>
                            <div>
                                <input type="radio" id="librarian_add" name="librarian" value="add"> <label for="librarian_add"><?php echo $info['add'] . " " . $info['account']; ?></label>
                            </div>
                            <div>
                                <input type="radio" id="librarian_remove" name="librarian" value="remove"> <label for="librarian_remove"><?php echo $info['remove'] . " " . $info['account']; ?></label>
                            </div>
                            <div>
                                <input type="radio" id="librarian_update" name="librarian" value="update"> <label for="librarian_update"><?php echo $info['update'] . " " . $info['password']; ?></label>
                            </div>
                        </div><br>
                        <input type="submit" name="librarian_submit" value="<?php echo $info['send_button']; ?>">
                    </form>
                </div>
                <h3><?php echo $info['admin_list_librarians']; ?></h3>
                <ul>
                    <li>adrian</li>
                    <li>antoni</li>
                    <li>jarek</li>
                </ul>
            </section>

            <section>
                <h2 class=""><?php echo $info['admin_managing_machines']; ?></h2>
                <div class="edycja">
                    <form name="admin_machines" method="post" action="" class="">
                        <div class="flekser">
                            <label for="machine_name"><?php echo $info['admin_machine_name']; ?></label>
                            <input type="tekst" class="" id="id-machine_name" name="machine_name">
                        </div>
                        <div class="flekser">
                            <label for="machine_address"><?php echo $info['admin_machine_address']; ?></label>
                            <input type="text" class="" id="id-machine_address" name="machine_address">
                        </div>
                        <div class="flekser">
                            <label for="machine_size"><?php echo $info['admin_machine_size']; ?></label>
                            <input type="number" class="" id="id-machine_size" name="machine_size">
                        </div>
                        <div>
                            <div>
                                <input type="radio" id="machine_add" name="machine" value="add">
                                <label for="machine_add"><?php echo $info['add'] . " " . $info['machine']; ?></label>
                            </div>
                            <div>
                                <input type="radio" id="machine_remove" name="machine" value="remove">
                                <label for="machine_remove"><?php echo $info['remove'] . " " . $info['machine']; ?></label>
                            </div>
                            <div>
                                <input type="radio" id="machine_update" name="machine" value="update">
                                <label for="machine_update"><?php echo $info['update'] . " " . $info['machine']; ?></label>
                            </div>
                        </div><br>
                        <input type="submit" name="machine_submit" value="<?php echo $info['send_button']; ?>">
                    </form>
                </div>
                <h3><?php echo $info['admin_list_machines']; ?></h3>
                <ul>
                    <li>Beka nr1, ul.wolska 3, skrytek: 40 </li>
                    <li>Beka nr3, ul.okop 12, skrytek: 12 </li>
                    <li>Beka nr22, ul.błotna 3, skrytek: 32 </li>
                </ul>
            </section>
        </main>
    <?php
    } else {
        echo '<header class="page-header">
        <h1>' . $info['admin_message'] . '</h1>
        <h2><a href="login.php">' . $info["login_first"] . '</a></h2>
    </header>';
    }

    ?>

</body>

</html>





<?php
// register.php
//$pepper = getConfigVariable("pepper");
$_POST['password'] = "aa";
$pwd = $_POST['password'];
$pwd_hashed = password_hash($pwd, PASSWORD_ARGON2ID);
echo  "<br>" . $_POST['password'] . " <-- hasło <br> " . $pwd_hashed . "<-- hashed <br>";
// add_user_to_database($username, $pwd_hashed);
if (password_verify($pwd, $pwd_hashed)) {
    echo 'Password hash ' . $pwd_hashed . ' is valid!';
} else {
    echo 'Invalid password.';
}
?>