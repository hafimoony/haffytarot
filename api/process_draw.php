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

// Kirim data langsung via URL GET agar aman dari masalah serverless Vercel
$params = http_build_query([
    'spread' => $spread,
    'category' => $category,
    'q' => $user_question,
    'cards' => $selected_ids_str
]);

header("Location: reading.php?$params");
exit;
?>
