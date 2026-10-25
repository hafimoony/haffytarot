<?php
session_start();
require_once 'config/db.php';
include 'includes/header.php';

$spread = $_POST['spread'] ?? $_SESSION['spread'] ?? 'daily';
$category = $_POST['category'] ?? $_SESSION['category'] ?? 'general';
$user_question = trim($_POST['user_question'] ?? $_SESSION['user_question'] ?? 'Apa pesan untukku saat ini?');

$_SESSION['spread'] = $spread;
$_SESSION['category'] = $category;
$_SESSION['user_question'] = $user_question;

$spread_configs = [
    'daily' => ['name' => 'Spill Energi (Single Card)', 'count' => 1],
    'ppf' => ['name' => 'Past, Present, Future Spread', 'count' => 3],
    'yesno' => ['name' => 'Yes or No Oracle', 'count' => 3],
    'horseshoe' => ['name' => 'Horseshoe Spread', 'count' => 7],
    'celtic' => ['name' => 'The Celtic Cross Spread', 'count' => 10]
];

$config = $spread_configs[$spread] ?? $spread_configs['daily'];
$required_count = $config['count'];

// Query PostgreSQL menggunakan RANDOM() dan PDO fetchAll()
$stmt = $conn->query("SELECT * FROM cards ORDER BY RANDOM()");
$raw_cards = $stmt->fetchAll();

$all_cards = [];
foreach ($raw_cards as $row) {
    $row['is_reversed'] = (rand(0, 1) === 1);
    $all_cards[] = $row;
}
?>

<style>
@keyframes shuffleLeft {
    0%, 100% { transform: translateX(0) rotate(0deg); }
    50% { transform: translateX(-60px) rotate(-15deg); }
}
@keyframes shuffleRight {
    0%, 100% { transform: translateX(0) rotate(0deg); }
    50% { transform: translateX(60px) rotate(15deg); }
}

.animate-shuffle-1 { animation: shuffleLeft 0.8s ease-in-out infinite; }
.animate-shuffle-2 { animation: shuffleRight 0.8s ease-in-out infinite 0.2s; }
</style>

<div id="shuffle-overlay" class="fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex flex-col items-center justify-center">
    <div class="relative w-32 h-48 mb-6">
        <div class="absolute inset-0 bg-white rounded-2xl cute-card-border flex items-center justify-center animate-shuffle-1 shadow-lg">
            <span class="text-3xl">✨</span>
        </div>
        <div class="absolute inset-0 bg-white rounded-2xl cute-card-border flex items-center justify-center animate-shuffle-2 shadow-lg">
            <span class="text-3xl">🔮</span>
        </div>
    </div>
    <h2 class="text-xl font-bold text-white font-heading animate-pulse">Mengocok 78 Kartu Tarot... ✨</h2>
    <p class="text-xs text-white/70 font-semibold mt-2">Salurkan energi dan fokuskan pikiran kamu pada pertanyaan</p>
</div>

<div class="text-center my-6">
    <span class="bg-pastel-bunny text-pastel-dark font-bold text-xs px-4 py-1.5 rounded-full inline-block mb-2 border border-pastel-lilac">
        Langkah 4 dari 4 🔮
    </span>
    <h1 class="text-2xl md:text-3xl font-bold text-pastel-dark font-heading">Pilih <?= $required_count; ?> Kartu dari Meja ✨</h1>
    <p class="text-xs md:text-sm font-semibold text-pastel-dark/80 mt-1">
        Klik <?= $required_count; ?> kartu yang paling memanggil intuisi kamu. 
        <span id="counter-badge" class="bg-pastel-pink text-pastel-dark font-bold px-3 py-0.5 rounded-full ml-1">Terpilih: 0 / <?= $required_count; ?></span>
    </p>
</div>

<form id="tarot-form" action="process_draw.php" method="POST" class="max-w-6xl mx-auto my-6">
    <input type="hidden" name="selected_card_ids" id="selected_card_ids" value="">

    <div class="bg-white/60 backdrop-blur-md p-6 rounded-3xl cute-card-border shadow-md min-h-[380px] theme-card">
        <div class="flex flex-wrap justify-center gap-2 md:gap-3 max-h-[480px] overflow-y-auto p-2">
            <?php foreach ($all_cards as $c): ?>
                <div 
                    class="tarot-deck-card w-14 h-24 md:w-20 md:h-32 bg-white rounded-xl cute-card-border cursor-pointer transition-all duration-300 transform hover:-translate-y-3 hover:scale-105 flex flex-col items-center justify-center p-1 relative shadow-sm"
                    data-card-id="<?= $c['id']; ?>"
                    onclick="selectCard(this, <?= $required_count; ?>)"
                >
                    <div class="w-8 h-8 md:w-12 md:h-12 rounded-full bg-pastel-bunny border border-pastel-lilac flex items-center justify-center">
                        <span class="text-xs md:text-lg">✨</span>
                    </div>
                    <span class="text-[9px] md:text-[10px] font-bold text-pastel-dark mt-1 font-heading">TAROT</span>
                    
                    <div class="card-check-overlay hidden absolute inset-0 bg-pastel-pink/90 rounded-xl flex items-center justify-center border-2 border-pastel-dark">
                        <span class="text-pastel-dark font-bold text-base md:text-xl font-heading">✓</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="text-center mt-8">
        <button 
            type="submit" 
            id="btn-submit" 
            disabled 
            class="bg-pastel-pink text-pastel-dark opacity-50 cursor-not-allowed font-bold text-sm py-3.5 px-10 rounded-full shadow-md transition transform border-2 border-pastel-lilac font-heading"
        >
            Buka Pembacaan Kartu ➔
        </button>
    </div>
</form>

<script>
setTimeout(() => {
    const overlay = document.getElementById('shuffle-overlay');
    if (overlay) {
        overlay.style.transition = 'opacity 0.5s ease';
        overlay.style.opacity = '0';
        setTimeout(() => overlay.remove(), 500);
    }
}, 2200);

let selectedCards = [];

function selectCard(cardElem, maxSelect) {
    const cardId = cardElem.getAttribute('data-card-id');
    const overlay = cardElem.querySelector('.card-check-overlay');

    if (selectedCards.includes(cardId)) {
        selectedCards = selectedCards.filter(id => id !== cardId);
        overlay.classList.add('hidden');
        cardElem.classList.remove('-translate-y-4');
    } else {
        if (selectedCards.length < maxSelect) {
            selectedCards.push(cardId);
            overlay.classList.remove('hidden');
            cardElem.classList.add('-translate-y-4');
        }
    }

    const badge = document.getElementById('counter-badge');
    const btn = document.getElementById('btn-submit');
    const hiddenInput = document.getElementById('selected_card_ids');

    badge.innerText = `Terpilih: ${selectedCards.length} / ${maxSelect}`;
    hiddenInput.value = selectedCards.join(',');

    if (selectedCards.length === maxSelect) {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
        btn.classList.add('hover:bg-pastel-lilac', 'active:scale-95');
    } else {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.classList.remove('hover:bg-pastel-lilac', 'active:scale-95');
    }
}
</script>

<?php include 'includes/footer.php'; ?>