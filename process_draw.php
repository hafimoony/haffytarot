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

$spread_configs = [
    'daily' => [
        'name' => 'Spill Energi (Single Card)',
        'positions' => ['Jawaban Utama Kartu']
    ],
    'ppf' => [
        'name' => 'Past, Present, Future Spread',
        'positions' => ['Penyebab Masa Lalu', 'Kondisi Saat Ini', 'Potensi Masa Depan']
    ],
    'yesno' => [
        'name' => 'Yes or No Oracle',
        'positions' => ['Pertimbangan Pertama', 'Pertimbangan Kedua', 'Pertimbangan Ketiga']
    ],
    'horseshoe' => [
        'name' => 'Horseshoe Spread',
        'positions' => [
            '1. Masa Lalu',
            '2. Masa Kini',
            '3. Masa Depan',
            '4. Sikap / Tantangan',
            '5. Pengaruh Lingkungan',
            '6. Saran Tindakan',
            '7. Hasil Akhir'
        ]
    ],
    'celtic' => [
        'name' => 'The Celtic Cross Spread',
        'positions' => [
            'Situasi Utama',
            'Hambatan Langsung',
            'Tujuan Sadar',
            'Akar Bawah Sadar',
            'Pengaruh Lalu',
            'Yang Akan Datang',
            'Perasaan Diri',
            'Pengaruh Lingkungan',
            'Harapan dan Ketakutan',
            'Hasil Akhir'
        ]
    ]
];

$config = $spread_configs[$spread] ?? $spread_configs['daily'];
$selected_ids = explode(',', $selected_ids_str);
$ids_clean = array_map('intval', $selected_ids);
$ids_imploded = implode(',', $ids_clean);

$stmt = $conn->query("SELECT * FROM cards WHERE id IN ($ids_imploded)");
$fetched_raw = $stmt->fetchAll();

$fetched_cards = [];
foreach ($fetched_raw as $row) {
    $fetched_cards[$row['id']] = $row;
}

$drawn_cards = [];
foreach ($ids_clean as $index => $id) {
    if (isset($fetched_cards[$id])) {
        $drawn_cards[] = [
            'position_label' => $config['positions'][$index] ?? "Posisi " . ($index + 1),
            'card_data' => $fetched_cards[$id],
            'is_reversed' => (rand(0, 1) === 1)
        ];
    }
}

$_SESSION['current_reading'] = [
    'spread_key' => $spread,
    'spread_name' => $config['name'],
    'category' => $category,
    'user_question' => $user_question,
    'cards' => $drawn_cards
];

header('Location: reading.php');
exit;
?>