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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kontak</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Top Bar -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Sistem Manajemen Kontak</h2>
            <p class="text-gray-600">Login sebagai: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
            <div class="mt-4">
                <a href="contacts.php" class="text-blue-600 hover:text-blue-800 mr-4">Daftar Kontak</a>
                <a href="logout.php" class="text-red-600 hover:text-red-800">Logout</a>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (!empty($flash_success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($flash_success); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($flash_error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($flash_error); ?>
            </div>
        <?php endif; ?>

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

        <!-- Form Tambah Kontak -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Tambah Kontak Baru</h3>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-4">
                    <label for="nama" class="block text-gray-700 font-medium mb-2">Nama Lengkap:</label>
                    <input type="text" id="nama" name="nama"
                           value="<?php echo isset($data['nama']) ? htmlspecialchars($data['nama']) : ''; ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="telepon" class="block text-gray-700 font-medium mb-2">Nomor Telepon:</label>
                    <input type="text" id="telepon" name="telepon"
                           value="<?php echo isset($data['telepon']) ? htmlspecialchars($data['telepon']) : ''; ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email:</label>
                    <input type="email" id="email" name="email"
                           value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="alamat" class="block text-gray-700 font-medium mb-2">Alamat:</label>
                    <textarea id="alamat" name="alamat" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php
                        echo isset($data['alamat']) ? htmlspecialchars($data['alamat']) : '';
                    ?></textarea>
                </div>

                <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition duration-200">
                    Simpan Kontak
                </button>
            </form>
        </div>

        <!-- Daftar Kontak -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Kontak</h3>
            <?php if (!empty($contacts)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($contacts as $c): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $c['id']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($c['nama']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($c['telepon']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($c['email']); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo nl2br(htmlspecialchars($c['alamat'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="edit-contact.php?id=<?php echo $c['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                    <a href="delete-contact.php?id=<?php echo $c['id']; ?>"
                                       onclick="return confirm('Yakin ingin menghapus kontak ini?');"
                                       class="text-red-600 hover:text-red-900">
                                       Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500">Belum ada kontak. Tambahkan kontak melalui form di atas.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
