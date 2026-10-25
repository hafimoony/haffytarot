<?php
session_start();
include 'includes/header.php';

$category = $_POST['category'] ?? $_SESSION['category'] ?? 'general';
$spread = $_POST['spread'] ?? $_SESSION['spread'] ?? 'daily';

$_SESSION['category'] = $category;
$_SESSION['spread'] = $spread;

$spread_names = [
    'daily' => 'Spill Energi (1 Kartu)',
    'ppf' => 'Past, Present, Future (3 Kartu)',
    'yesno' => 'Yes or No Oracle (3 Kartu)',
    'horseshoe' => 'Horseshoe Spread (7 Kartu)',
    'celtic' => 'Celtic Cross (10 Kartu)'
];

$category_info = [
    'love' => [
        'title' => 'Percintaan & Hubungan 💖',
        'placeholder' => 'Contoh: Bagaimana kelanjutan hubunganku sama doi? / Apakah dia serius sama aku?'
    ],
    'career' => [
        'title' => 'Karir & Keuangan 💼',
        'placeholder' => 'Contoh: Apakah ini waktu yang tepat buat pindah kerja? / Bagaimana prospek keuanganku bulan ini?'
    ],
    'life' => [
        'title' => 'Kehidupan & Pengembangan Diri 🌱',
        'placeholder' => 'Contoh: Langkah apa yang harus aku ambil sekarang? / Bagaimana cara mengatasi rasa cemas ini?'
    ],
    'general' => [
        'title' => 'General / Umum 🔮',
        'placeholder' => 'Tuliskan pertanyaan apa saja yang ingin kamu tanyakan...'
    ]
];

$info = $category_info[$category] ?? $category_info['general'];
?>

<div class="max-w-xl mx-auto my-8 text-center">
    <span class="bg-pastel-bunny text-pastel-dark font-bold text-xs px-4 py-1.5 rounded-full inline-block mb-3 border border-pastel-lilac">
        Langkah 3 dari 4 ✨
    </span>
    <h1 class="text-2xl font-bold mb-2 font-heading">Ketik Pertanyaan Kamu 💕</h1>
    
    <div class="flex justify-center gap-2 mb-6 text-xs font-bold">
        <span class="bg-pastel-lemon text-pastel-dark px-3 py-1 rounded-full"><?= $info['title']; ?></span>
        <span class="bg-pastel-mint text-pastel-dark px-3 py-1 rounded-full"><?= $spread_names[$spread] ?? 'Tebaran Kartu'; ?></span>
    </div>

    <form action="draw.php" method="POST" class="bg-white/90 p-6 rounded-3xl cute-card-border shadow-md text-left theme-card">
        <input type="hidden" name="category" value="<?= htmlspecialchars($category); ?>">
        <input type="hidden" name="spread" value="<?= htmlspecialchars($spread); ?>">
        
        <label for="user_question" class="block text-sm font-bold mb-3 font-heading">
            ❓ Tulis Pertanyaan Spesifik Kamu:
        </label>
        <textarea 
            name="user_question" 
            id="user_question" 
            rows="4" 
            required 
            placeholder="<?= $info['placeholder']; ?>" 
            class="w-full p-4 rounded-2xl border-2 border-pastel-lilac focus:outline-none focus:border-pastel-pink text-sm text-pastel-dark font-semibold placeholder:text-pastel-dark/40 bg-pastel-bunny/30 mb-6"
        ></textarea>

        <div class="flex items-center justify-between gap-4">
            <a href="select_spread.php" class="text-xs font-bold opacity-75 hover:underline">
                ← Ganti Metode Tebaran
            </a>
            <button type="submit" class="bg-pastel-pink hover:bg-pastel-lilac text-pastel-dark font-bold text-sm py-3 px-6 rounded-2xl transition shadow-md border-2 border-pastel-lilac font-heading">
                Lanjut ke Meja Kartu ➔
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>