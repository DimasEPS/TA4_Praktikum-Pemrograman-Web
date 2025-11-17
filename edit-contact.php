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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kontak</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Edit Kontak</h2>
            <div class="mb-4">
                <a href="contacts.php" class="text-blue-600 hover:text-blue-800 mr-4">&laquo; Kembali ke Daftar Kontak</a>
                <a href="logout.php" class="text-red-600 hover:text-red-800">Logout</a>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <h3 class="font-bold mb-2">Terjadi Error:</h3>
                <ul class="list-disc list-inside">
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>">
                <div class="mb-4">
                    <label for="nama" class="block text-gray-700 font-medium mb-2">Nama Lengkap:</label>
                    <input type="text" id="nama" name="nama"
                           value="<?php echo htmlspecialchars($data['nama']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="telepon" class="block text-gray-700 font-medium mb-2">Nomor Telepon:</label>
                    <input type="text" id="telepon" name="telepon"
                           value="<?php echo htmlspecialchars($data['telepon']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email:</label>
                    <input type="email" id="email" name="email"
                           value="<?php echo htmlspecialchars($data['email']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-6">
                    <label for="alamat" class="block text-gray-700 font-medium mb-2">Alamat:</label>
                    <textarea id="alamat" name="alamat" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php
                        echo htmlspecialchars($data['alamat']);
                    ?></textarea>
                </div>

                <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition duration-200">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</body>
</html>
