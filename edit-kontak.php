<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['contacts'])) {
    $_SESSION['contacts'] = [];
}

$contacts = $_SESSION['contacts'];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!isset($contacts[$id])) {
    $_SESSION['flash_error'] = "Kontak dengan ID tersebut tidak ditemukan.";
    header("Location: contacts.php");
    exit();
}

$errors = [];
$data = $contacts[$id];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data['nama']   = trim($_POST['nama'] ?? '');
    $data['telepon'] = trim($_POST['telepon'] ?? '');
    $data['email']  = trim($_POST['email'] ?? '');
    $data['alamat'] = trim($_POST['alamat'] ?? '');

    if (empty($data['nama'])) {
        $errors[] = "Nama harus diisi.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $data['nama'])) {
        $errors[] = "Nama hanya boleh mengandung huruf dan spasi.";
    }

    if (empty($data['telepon'])) {
        $errors[] = "Nomor telepon harus diisi.";
    } elseif (!preg_match("/^[0-9+\-\s]+$/", $data['telepon'])) {
        $errors[] = "Nomor telepon hanya boleh berisi angka, spasi, + dan -.";
    }

    if (empty($data['email'])) {
        $errors[] = "Email harus diisi.";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    if (empty($errors)) {
        $_SESSION['contacts'][$id] = $data;
        $_SESSION['flash_success'] = "Kontak berhasil diperbarui.";
        header("Location: contacts.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kontak</title>
    <style>
        .error { color:red; }
        .form-group { margin-bottom:10px; }
    </style>
</head>
<body>
    <h2>Edit Kontak</h2>
    <p>
        <a href="contacts.php">&laquo; Kembali ke Daftar Kontak</a> |
        <a href="logout.php">Logout</a>
    </p>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <h3>Terjadi Error:</h3>
            <ul>
            <?php foreach ($errors as $e): ?>
                <li><?php echo htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>">
        <div class="form-group">
            <label for="nama">Nama Lengkap:</label><br>
            <input type="text" id="nama" name="nama"
                   value="<?php echo htmlspecialchars($data['nama']); ?>">
        </div>

        <div class="form-group">
            <label for="telepon">Nomor Telepon:</label><br>
            <input type="text" id="telepon" name="telepon"
                   value="<?php echo htmlspecialchars($data['telepon']); ?>">
        </div>

        <div class="form-group">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email"
                   value="<?php echo htmlspecialchars($data['email']); ?>">
        </div>

        <div class="form-group">
            <label for="alamat">Alamat:</label><br>
            <textarea id="alamat" name="alamat" rows="3" cols="40"><?php
                echo htmlspecialchars($data['alamat']);
            ?></textarea>
        </div>

        <input type="submit" value="Simpan Perubahan">
    </form>
</body>
</html>
