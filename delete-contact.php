<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['contacts'])) {
    $_SESSION['contacts'] = [];
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isset($_SESSION['contacts'][$id])) {
    unset($_SESSION['contacts'][$id]);
    $_SESSION['flash_success'] = "Kontak berhasil dihapus.";
} else {
    $_SESSION['flash_error'] = "Kontak tidak ditemukan.";
}

header("Location: contacts.php");
exit();
