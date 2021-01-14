<?php

$count = 0;
$data = [];
$username = '';

?>
<?php function render($const) {
global $count, $data, $username;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Surat Tenggat Waktu</title>
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
        <div class="vertical-space search">
            <form action="./" method="post">
                <label for="input">
                    <button class="md-icons">search</button>
                    <input name="search" id="input" type="search" placeholder="Search...">
                </label>
            </form>
        </div>
        <div class="system">
            <div class="notification">
                <i class="md-icons">notifications</i>
            </div>
            <div class="account">
                <i class="md-icons">person</i>
                <span class="name"><?= $username ?></span>
            </div>
            <div class="logout">
                <a href="../logout/index.php?username=<?= $username ?>" class="md-icons">logout</a>
            </div>
        </div>
    </nav>
    <main>
        <div class="title-board">
            <img src="../images/mail in.svg" alt="surat masuk" width="32" height="32">
            <h1>surat tenggat waktu</h1>
            <div class="hr"></div>
            <h2>jumlah <?= $count ?></h2>
        </div>
        <div class="tile-manager">
            <span class="index"></span>
            <span class="institute">institusi</span>
            <span class="numbere">nomor</span>
            <span class="subject">perihal</span>
            <span class="date">tanggal kegiatan</span>
            <span class="attachment">document</span>

            <?php foreach ($data as $key => $value): ?>
            <div class="tile">
                <span class="index"><?= $key ?></span>
                <span class="institute"><?= $value[$const['post_institute']] ?></span>
                <span class="number"><?= $value[$const['post_number']] ?></span>
                <span class="subject"><?= $value[$const['post_subject']] ?></span>
                <span class="date"><?= $value[$const['post_date']] ?></span>
                <a href="#" class="attachment"><?= $value[$const['file_upload']] ?></a>
            </div>
            <?php endforeach ?>
        </div>
        <div class="control">
            <a href="../dashboard/index.php?user=<?= $username ?>" class="back">
                <i class="md-icons">
                    chevron_left
                </i>
                <span class="text">kembali</span>
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