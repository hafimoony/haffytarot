<?php
$host = 'db.foeddzgzazjmqardsqzq.supabase.co';
$port = '5432';
$db   = 'postgres';
$user = 'postgres';
$pass = 'namgildaero27';

$dsn = "pgsql:host=$host;port=$port;dbname=$db;user=$user;password=$pass";

try {
    $conn = new PDO($dsn);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi Supabase gagal: " . $e->getMessage());
}
?>