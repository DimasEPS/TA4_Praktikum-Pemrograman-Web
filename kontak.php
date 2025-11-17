<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['contacts'])) {
    $_SESSION['contacts'] = [];
}

if (!isset($_SESSION['next_contact_id'])) {
    $_SESSION['next_contact_id'] = 1;
}

$errors = [];
$data = [];

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
        $id = $_SESSION['next_contact_id']++;

        $_SESSION['contacts'][$id] = [
            'id'      => $id,
            'nama'    => $data['nama'],
            'telepon' => $data['telepon'],
            'email'   => $data['email'],
            'alamat'  => $data['alamat']
        ];

        $data = [];

        $_SESSION['flash_success'] = "Kontak berhasil ditambahkan.";
        header("Location: contacts.php");
        exit();
    }
}


$contacts = $_SESSION['contacts'];

$flash_success = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);

$flash_error = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Kontak</title>
    <style>
        .error { color:red; }
        .success { color:green; }
        .form-group { margin-bottom:10px; }
        table { border-collapse: collapse; width: 100%; margin-top:20px; }
        th, td { border:1px solid #ccc; padding:8px; text-align:left; }
        th { background:#f0f0f0; }
        .top-bar { margin-bottom:15px; }
    </style>
</head>
<body>
    <div class="top-bar">
        <h2>Sistem Manajemen Kontak</h2>
        <p>Login sebagai: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
        <p>
            <a href="contacts.php">Daftar Kontak</a> | 
            <a href="logout.php">Logout</a>
        </p>
    </div>

    <?php if (!empty($flash_success)): ?>
        <div class="success"><?php echo htmlspecialchars($flash_success); ?></div>
    <?php endif; ?>

    <?php if (!empty($flash_error)): ?>
        <div class="error"><?php echo htmlspecialchars($flash_error); ?></div>
    <?php endif; ?>

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

    <h3>Tambah Kontak Baru</h3>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="nama">Nama Lengkap:</label><br>
            <input type="text" id="nama" name="nama"
                   value="<?php echo isset($data['nama']) ? htmlspecialchars($data['nama']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="telepon">Nomor Telepon:</label><br>
            <input type="text" id="telepon" name="telepon"
                   value="<?php echo isset($data['telepon']) ? htmlspecialchars($data['telepon']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email"
                   value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="alamat">Alamat:</label><br>
            <textarea id="alamat" name="alamat" rows="3" cols="40"><?php
                echo isset($data['alamat']) ? htmlspecialchars($data['alamat']) : '';
            ?></textarea>
        </div>

        <input type="submit" value="Simpan Kontak">
    </form>

    <h3>Daftar Kontak</h3>
    <?php if (!empty($contacts)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($contacts as $c): ?>
                <tr>
                    <td><?php echo $c['id']; ?></td>
                    <td><?php echo htmlspecialchars($c['nama']); ?></td>
                    <td><?php echo htmlspecialchars($c['telepon']); ?></td>
                    <td><?php echo htmlspecialchars($c['email']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($c['alamat'])); ?></td>
                    <td>
                        <a href="edit-contact.php?id=<?php echo $c['id']; ?>">Edit</a> |
                        <a href="delete-contact.php?id=<?php echo $c['id']; ?>"
                           onclick="return confirm('Yakin ingin menghapus kontak ini?');">
                           Hapus
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Belum ada kontak. Tambahkan kontak melalui form di atas.</p>
    <?php endif; ?>
</body>
</html>
