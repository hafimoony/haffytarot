<?php
session_start();
require_once 'config/db.php';

$selected_ids_str = $_POST['selected_card_ids'] ?? '';
$spread = $_SESSION['spread'] ?? 'daily';
$category = $_SESSION['category'] ?? 'general';
$user_question = $_SESSION['user_question'] ?? 'Apa pesan untukku saat ini?';

if (empty($selected_ids_str)) {
    header('Location: draw.php');
    exit;
}

// Tentukan posisi tegak/terbalik secara konsisten untuk setiap kartu yang dipilih
$selected_ids = explode(',', $selected_ids_str);
$rev_flags = [];
foreach ($selected_ids as $id) {
    $rev_flags[] = (rand(0, 1) === 1) ? 1 : 0;
}
$rev_str = implode(',', $rev_flags);

// Kirim data melalui parameter URL GET untuk menghindari masalah sesi hilang di Vercel Serverless
$params = http_build_query([
    'spread' => $spread,
    'category' => $category,
    'q' => $user_question,
    'cards' => $selected_ids_str,
    'rev' => $rev_str
]);

header("Location: reading.php?$params");
exit;
?>
