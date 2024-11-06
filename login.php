<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles/login_register.css" />
        <link rel="stylesheet" href="styles/header.css" />
        <?php require ('scripts/login.php');?>
        <title>SIGN IN</title>
    </head>
    <body>
        <header>
            <a href="index.php" class="navitem"><img src="images/logo_steam.svg" alt="logo_steam" /></a>
            <a href="index.php" class="navitem">GAMES</a>
            <a href="developers_publishers.php?title=developers" class="navitem">DEVELOPERS</a>
            <a href="developers_publishers.php?title=publishers" class="navitem">PUBLISHERS</a>
            <a href="login.php" class="navitem">LOGIN</a>
        </header>
        <main>
            <label for="signin">SIGN IN</label>
            <form name="signin" method="POST">
                <input type="text" placeholder="username" name="username" required />
                <input type="password" placeholder="password" name="password" required />
                <input type="submit" value="SIGN IN" />
            </form>
            <p>Don't have an account yet? Create it <a href="register.php">here</a>!</p>
        </main>
    </body>
</html>