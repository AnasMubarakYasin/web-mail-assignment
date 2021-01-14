<?php

$dataMailIn = [];
$dataMailOut = [];
$dataMailDeadline = [];

string: $username = '';
int: $count = 0;

function render() {

    global $dataMailDeadline, $dataMailOut, $dataMailIn, $username, $count;

    if ($dataMailIn && $dataMailOut && $dataMailDeadline) {
        $mailInAlreadyRead = $dataMailIn['already_read'];
        $mailInCount = $dataMailIn['count'];
        $mailOutAlreadyRead = $dataMailOut['already_read'];
        $mailOutCount = $dataMailOut['count'];
        $mailDeadlineAlreadyRead = $dataMailDeadline['already_read'];
        $mailDeadlineCount = $dataMailDeadline['count'];
    } else {
        $mailInAlreadyRead = 0;
        $mailInCount = 0;
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Dashboard</title>
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
        <div class="vertical-space"></div>
        <div class="system">
            <div class="notification">
                <i class="md-icons">notifications</i>
            </div>
            <div class="account">
                <i class="md-icons">person</i>
                <span class="name"><?= $username ?></span>
            </div>
            <div class="logout">
                <a href="../logout/index.php?username=<?= $username ?>" title="keluar" class="md-icons">logout</a>
            </div>
        </div>
    </nav>
    <main>
        <div class="title-board">
            <h1>Dashboard</h1>
            <div class="hr"></div>
            <h2>Surat - total <?= $count ?></h2>
        </div>
        <div class="list-card">
            <a href="../surat-masuk/index.php?user=<?= $username ?>" class="card">
                <img src="../images/mail in.svg" alt="surat masuk" width="44" height="44">
                <h1>masuk</h1>
                <h2>jumlah <?= $mailInCount ?></h2>
                <span><?= $mailInAlreadyRead ?></span>
            </a>
            <a href="../surat-keluar/index.php?user=<?= $username ?>" class="card">
                <img src="../images/mail out.svg" alt="surat keluar" width="44" height="44">
                <h1>keluar</h1>
                <h2>jumlah <?= $mailOutCount ?></h2>
                <span><?= $mailOutAlreadyRead ?></span>
            </a>
            <a href="../surat-tenggat-waktu/index.php?user=<?= $username ?>" class="card">
                <img src="../images/mail time.svg" alt="surat tenggat waktu" width="44" height="44">
                <h1>tenggat waktu</h1>
                <h2>jumlah <?= $mailDeadlineCount ?></h2>
                <span><?= $mailDeadlineAlreadyRead ?></span>
            </a>
        </div>
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
</html>
<?php } ?>
