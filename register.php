<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles/login_register.css" />
        <link rel="stylesheet" href="styles/header.css" />
        <?php require ('scripts/register.php');?>
        <title>REGISTER</title>
    </head>
    <body>
        <header>
            <a href="index.php" class="navitem"><img src="images/logo_steam.svg" alt="logo_steam" /></a>
            <a href="index.php" class="navitem">GAMES</a>
            <a href="developers.php" class="navitem">DEVELOPERS</a>
            <a href="publishers.php" class="navitem">PUBLISHERS</a>
            <a href="login.php" class="navitem">LOGIN</a>
        </header>
        <main>
            <label for="register">REGISTER</label>
            <form name="register" method="post">
                <input type="text" placeholder="username" name="username" required />
                <input type="password" placeholder="password"  name="password" required />
                <input type="password" placeholder="repeat password" name="rpassword" required />
                <input type="submit" value="REGISTER" name="register" />
            </form>
        </main>
    </body>
</html>