<?php function login(string $username = '', string $password = '') { ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Login</title>
</head>
<body>
    <nav>
        <div class="title">
            <img src="../images/logo.png" alt="UIN Alauddin" width="44" height="44">
            <div class="text">
                <h2>Fakultas Sains & Teknologi</h2>
                <h1>Sistem Informasi</h1>
            </div>
        </div>
    </nav>
    <main id="content">
        <form action="login.php" method="POST" autocomplete="off" id="login">
            <header>
                <h1>login</h1>
            </header>
            <main>
                <label for="username">
                    username
                    <div class="input-field">
                        <i class="md-icons">person</i>
                        <!-- <input id="username" type="text" autocomplete="username" required value="<?= $username ?>"> -->
                        <input name="username" id="username" type="text" autocomplete="" required value="<?= $username ?>">
                    </div>
                </label>
                <label for="password">
                    password
                    <div class="input-field">
                        <i class="md-icons">vpn_key</i>
                        <!-- <input id="password" type="password" autocomplete="current-password" required value="<?= $password ?>"> -->
                        <input name="password" id="password" type="password" autocomplete="" required value="<?= $password ?>">
                    </div>
                </label>
                <div class="button-field">
                    <button type="submit">login</button>
                    <button type="submit" formaction="../signup/index.php" formmethod="POST" formenctype="application/x-www-form-urlencoded">sign up</button>
                </div>
            </main>
        </form>
    </main>
    <footer>
        <div></div>
        <p>Copyright &copy; 2020</p>
        <div class="attributes">
            <div>
                Icons made by <a href="https://www.flaticon.com/authors/icongeek26" title="Icongeek26">
                    Icongeek26
                </a> from <a href="https://www.flaticon.com/" title="Flaticon">
                    www.flaticon.com
                </a>
            </div>
        </div>
    </footer>
</body>
<!-- <script src="index.js" defer></script> -->
</html>
<?php } ?>