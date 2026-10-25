<?php
$host = 'aws-0-ap-southeast-1.pooler.supabase.com';
$port = '6543';
$db   = 'postgres';
$user = 'postgres.foeddzgzazjmqardsqzq';
$pass = 'namgildaero27'; // Ganti dengan password database Supabase kamu

$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";

try {
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Koneksi Supabase gagal: " . $e->getMessage());
}
?>
