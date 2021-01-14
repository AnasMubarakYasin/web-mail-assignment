<?php 
declare(strict_types=1);

require_once '../../src/config.php';
require_once '../../src/mail-tb.php';

$mailConfig = $_configini['mail'];

$keyInstitute = $mailConfig['post_institute'];
$keyNumber = $mailConfig['post_number'];
$keySubject = $mailConfig['post_subject'];
$keyDate = $mailConfig['post_date'];
$keyFilename = $mailConfig['file_upload'];

$institute = $_POST[$keyInstitute];
$number = $_POST[$keyNumber];
$subject = $_POST[$keySubject];
$date = $_POST[$keyDate];
$file = $_FILES[$keyFilename];

$filefullname = $file['name'];

$fileext = pathinfo($filefullname, PATHINFO_EXTENSION);
$filename = pathinfo($filefullname, PATHINFO_FILENAME);

$destination = $_configini['dir']['private'] . $mailConfig['dir'] . $institute;

$tb = new TB\Mail($_configini);

$data = $tb->get(null, $institute, $number);
$isSuccess = true;

if (!$data) {
    if (file_exists($destination) === false) {
        $isSuccess = mkdir($destination);
    }

    $destination .= DIRECTORY_SEPARATOR . $number;
} else {
    $isSuccess = false;
}


if ($isSuccess && file_exists($destination) === false) {
    $isSuccess = mkdir($destination);
}

if ($isSuccess && in_array($fileext, $mailConfig['accept_exts'])) {
    $destination .= DIRECTORY_SEPARATOR . $filefullname;

    $isSuccess = move_uploaded_file($file['tmp_name'], $destination);
}

if ($isSuccess && !$data) {
    $data = [
        $keyInstitute => $institute,
        $keyNumber => $number,
        $keySubject => $subject,
        $keyDate => $date,
        $keyFilename => $filefullname
    ];

    $isSuccess = $tb->insert($data);

    if ($isSuccess === false) {
        unlink($destination);
        rmdir(dirname($destination));
    }
}

if ($isSuccess) {
    $title = 'Diterima | ' . $filename;
    $icon = 'done';
    $text = 'Sukses';
} else {
    $title = 'Tidak Diterima | ' . $filename;
    $icon = 'clear';
    $text = 'Gagal';
}

if (isset($_GET['role'])) {
    $role = $_GET['role'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="response.css">
    <title>Surat <?= $title ?></title>
</head>

<body>
    <main>
        <h1>
            <i class="md-icons"><?= $icon ?></i>
            <span class="text"><?= $text ?></span>
        </h1>
        <?php if ($role === 'admin'): ?>
            <a href="../surat-keluar/index.php" class="btn-back">
                <i class="md-icons">
                    chevron_left
                </i>
                <span class="text">kembali</span>
            </a>
        <?php else: ?>
        <?php endif ?>  
    </main>
</body>
</html>
