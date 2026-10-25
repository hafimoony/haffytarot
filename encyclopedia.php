<?php
session_start();
require_once 'config/db.php';
include 'includes/header.php';

$arcana_filter = $_GET['arcana'] ?? 'all';

$sql = "SELECT * FROM cards";
if ($arcana_filter === 'major') {
    $sql .= " WHERE arcana = 'major'";
} elseif ($arcana_filter === 'minor') {
    $sql .= " WHERE arcana = 'minor'";
}
$sql .= " ORDER BY id ASC";

$stmt = $conn->query($sql);
$cards = $stmt->fetchAll();

function getCardNumerology($code) {
    preg_match('/\d+/', $code, $matches);
    $num = isset($matches[0]) ? intval($matches[0]) : 0;
    
    $meanings = [
        0 => 'Potensi tanpa batas, kepolosan, awal baru.',
        1 => 'Fokus, manifestasi, kehendak diri.',
        2 => 'Keseimbangan, intuisi, dualitas.',
        3 => 'Pertumbuhan, kreativitas, kelimpahan.',
        4 => 'Struktur, stabilitas, kontrol.',
        5 => 'Tradisi, pembelajaran, spiritualitas.',
        6 => 'Pilihan, harmoni, hubungan.',
        7 => 'Arah tujuan, tekad, kemenangan.',
        8 => 'Kekuatan batin, kesabaran, keberanian.',
        9 => 'Pencarian diri, introspeksi, bimbingan.',
        10 => 'Siklus hidup, keberuntungan, takdir.'
    ];
    
    return $meanings[$num % 11] ?? 'Transformasi, integrasi energi, dan perjalanan jiwa.';
}
?>

<div class="text-center my-6">
    <span class="bg-pastel-bunny text-pastel-dark font-bold text-xs px-4 py-1.5 rounded-full inline-block mb-2 border border-pastel-lilac">
        Perpustakaan Tarot 📖
    </span>
    <h1 class="text-2xl md:text-3xl font-bold font-heading">Ensiklopedia 78 Kartu Tarot ✨</h1>
    <p class="text-xs md:text-sm font-semibold opacity-80 mt-1">Pelajari elemen, numerologi, dan interpretasi makna setiap kartu secara eksplisit</p>
</div>

<div class="flex justify-center gap-2 mb-8">
    <a href="encyclopedia.php?arcana=all" class="px-4 py-1.5 rounded-full text-xs font-bold border transition font-heading <?= $arcana_filter === 'all' ? 'bg-pastel-pink text-pastel-dark border-pastel-lilac' : 'bg-white/80 border-gray-300'; ?>">
        Semua (78)
    </a>
    <a href="encyclopedia.php?arcana=major" class="px-4 py-1.5 rounded-full text-xs font-bold border transition font-heading <?= $arcana_filter === 'major' ? 'bg-pastel-pink text-pastel-dark border-pastel-lilac' : 'bg-white/80 border-gray-300'; ?>">
        Major Arcana (22)
    </a>
    <a href="encyclopedia.php?arcana=minor" class="px-4 py-1.5 rounded-full text-xs font-bold border transition font-heading <?= $arcana_filter === 'minor' ? 'bg-pastel-pink text-pastel-dark border-pastel-lilac' : 'bg-white/80 border-gray-300'; ?>">
        Minor Arcana (56)
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($cards as $c): 
        $clean_name = str_replace(['**', '*'], '', preg_replace('/^[0-9IVXLCDM]+\s*-\s*/', '', $c['name']));
        $numerology = getCardNumerology($c['code']);
    ?>
        <div class="bg-white/90 rounded-3xl p-5 cute-card-border shadow-sm flex flex-col justify-between theme-card">
            <div>
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase bg-pastel-lemon text-pastel-dark border border-pastel-lilac">
                            <?= $c['arcana']; ?> • <?= $c['suit'] !== 'none' ? $c['suit'] : 'Major'; ?>
                        </span>
                        <h3 class="text-lg font-bold font-heading mt-1">
                            <?= htmlspecialchars($clean_name); ?>
                        </h3>
                    </div>
                    <span class="text-xs font-bold px-2 py-1 rounded-lg bg-pastel-bunny border border-pastel-lilac">
                        <?= htmlspecialchars($c['element']); ?>
                    </span>
                </div>

                <div class="bg-pastel-bunny/30 p-3 rounded-2xl mb-3 border border-pastel-lilac/40 text-xs">
                    <span class="font-bold block mb-0.5 font-heading">🔢 Numerologi & Simbolisme:</span>
                    <p class="opacity-90 leading-relaxed font-semibold"><?= htmlspecialchars($numerology); ?></p>
                </div>

                <div class="space-y-2 text-xs font-semibold">
                    <div class="p-2.5 rounded-xl bg-pastel-mint/30 border border-pastel-mint">
                        <span class="font-bold text-emerald-800 block mb-0.5">☀️ Interpretasi Tegak (Upright):</span>
                        <p class="opacity-90"><?= htmlspecialchars(str_replace(['**', '*'], '', $c['general_upright'])); ?></p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-pastel-pink/30 border border-pastel-pink">
                        <span class="font-bold text-rose-800 block mb-0.5">🌙 Interpretasi Terbalik (Reversed):</span>
                        <p class="opacity-90"><?= htmlspecialchars(str_replace(['**', '*'], '', $c['general_reversed'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>