<?php

$role = 'user';

function render() {
    global $role; ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="index.css">
        <title>Input Surat</title>
    </head>
    <body>
        <main>
            <form action="input.php?role=<?= $role ?>" method="POST" enctype="multipart/form-data">
              <header>
                <h1>identitas surat</h1>
              </header>
              <main>
                <div class="input-fill">
                  <label for="institusi">institusi</label>
                  <input type="text" name="institusi" id="institusi" required>
                </div>
                <div class="input-fill">
                  <label for="nomor">nomor</label>
                  <input type="number" name="nomor" id="nomor" required>
                </div>
                <div class="input-fill">
                  <label for="perihal">perihal</label>
                  <input type="text" name="perihal" id="perihal" required>
                </div>
                <div class="input-fill">
                  <label for="tanggal_kegiatan">tanggal kegiatan</label>
                  <input type="datetime-local" name="tanggal_kegiatan" id="tanggal_kegiatan" required>
                </div>
                <div class="input-fill">
                  <label for="dokumen">dokumen</label>
                  <input type="file" name="dokumen" id="dokumen" accept=".doc,.docx,.pdf" multiple="false" required>
                </div>
                <div class="button-list">
                  <button type="submit">submit</button>
                  <button type="button">
                        <?php if ($role === 'admin'): ?>
                            <a href="../surat-keluar/index.php">kembali</a>
                        <?php else: ?>
                            batal
                        <?php endif ?>                    
                  </button>
                </div>
              </main>
            </form>
        </main>
    </body>
    </html>
<?php } ?>