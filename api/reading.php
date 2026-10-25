<?php
session_start();

if (!isset($_SESSION['current_reading'])) {
    header('Location: index.php');
    exit;
}

include 'includes/header.php';

$reading = $_SESSION['current_reading'];
$cards = $reading['cards'];
$spread_key = $reading['spread_key'];
$category = $reading['category'] ?? 'general';
$user_question = $reading['user_question'];

function renderCuteSymbol($suit, $arcana) {
    if ($arcana === 'major') {
        return '<svg class="w-14 h-14" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M50 10L62 38L90 50L62 62L50 90L38 62L10 50L38 38Z" fill="#fffabf" stroke="#bfc4ff" stroke-width="6" stroke-linejoin="round"/>
            <circle cx="50" cy="50" r="12" fill="#f4adcf"/>
        </svg>';
    }

    switch ($suit) {
        case 'cups':
            return '<svg class="w-14 h-14" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M30 35C30 35 30 65 50 65C70 65 70 35 70 35H30Z" fill="#f4adcf" stroke="#2e2538" stroke-width="5"/>
                <path d="M45 65V80H55V65" stroke="#2e2538" stroke-width="5"/>
                <path d="M35 80H65" stroke="#2e2538" stroke-width="5" stroke-linecap="round"/>
            </svg>';
        case 'swords':
            return '<svg class="w-14 h-14" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M50 15L57 65H43L50 15Z" fill="#b2f9e7" stroke="#2e2538" stroke-width="5" stroke-linejoin="round"/>
                <path d="M32 65H68" stroke="#2e2538" stroke-width="6" stroke-linecap="round"/>
            </svg>';
        case 'wands':
            return '<svg class="w-14 h-14" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M50 15 C68 35 72 50 65 68 C58 82 42 82 35 68 C28 50 32 35 50 15 Z" fill="#fffabf" stroke="#2e2538" stroke-width="5"/>
            </svg>';
        case 'pentacles':
            return '<svg class="w-14 h-14" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="34" fill="#fffabf" stroke="#2e2538" stroke-width="5"/>
            </svg>';
        default:
            return '<svg class="w-14 h-14" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="25" fill="#ffe7ff" stroke="#bfc4ff" stroke-width="5"/>
            </svg>';
    }
}

function getCardMeaningByCategory($card_data, $category, $is_reversed) {
    $suffix = $is_reversed ? '_reversed' : '_upright';
    $column_name = $category . $suffix;
    return !empty($card_data[$column_name]) ? $card_data[$column_name] : ($card_data['general' . $suffix] ?? '');
}

function generateSmartInterpretation($question, $category, $cards, $spread_key) {
    $total = count($cards);
    $uprights = 0;
    $yes_count = 0;
    $no_count = 0;

    $clean_question = trim(str_replace(['**', '*', '#', '`', '"', "'"], '', $question));
    
    $processed = [];
    foreach ($cards as $c) {
        if (!$c['is_reversed']) $uprights++;

        $card_yesno = $c['card_data']['is_yes_no'] ?? 'yes';
        if (!$c['is_reversed'] && $card_yesno === 'yes') {
            $yes_count++;
        } else {
            $no_count++;
        }

        $raw_name = $c['card_data']['name'];
        $clean_name = str_replace(['**', '*'], '', preg_replace('/^[0-9IVXLCDM]+\s*-\s*/', '', $raw_name));
        $pos = str_replace(['**', '*'], '', $c['position_label']);
        $orient = $c['is_reversed'] ? 'posisi terbalik' : 'posisi tegak';
        $meaning = str_replace(['**', '*'], '', getCardMeaningByCategory($c['card_data'], $category, $c['is_reversed']));

        $processed[] = [
            'name' => $clean_name,
            'pos' => $pos,
            'orient' => $orient,
            'meaning' => $meaning,
            'is_reversed' => $c['is_reversed']
        ];
    }

    // 1 Kartu (Daily)
    if ($spread_key === 'daily' || $total === 1) {
        $card = $processed[0];
        $p1 = "Halo! Mengenai pertanyaan kamu tentang '" . $clean_question . "', kartu tarot yang terbuka memberikan gambaran energi utama kamu hari ini.";
        $p2 = "Energi hari ini dipengaruhi oleh kartu " . $card['name'] . " (" . $card['orient'] . "). Hal ini mengindikasikan bahwa hari ini " . lcfirst($card['meaning']);
        $p3 = !$card['is_reversed'] 
            ? "Pesan utama buat kamu hari ini: manfaatkan energi positif ini sebaik mungkin, tetap optimis, dan jalani hari dengan keyakinan penuh." 
            : "Pesan utama buat kamu hari ini: luangkan waktu untuk lebih tenang, hindari sikap terburu-buru, dan jadikan petunjuk ini sebagai bentuk kehati-hatian dalam melangkah.";

        return str_replace(['**', '*', '#', '`'], '', $p1 . "\n\n" . $p2 . "\n\n" . $p3);
    }

    // Horseshoe Spread (7 Kartu)
    if ($spread_key === 'horseshoe') {
        $p1 = "Mengenai pertanyaan kamu tentang '" . $clean_question . "', tebaran melengkung Horseshoe Spread mengurai 7 aspek perjalanan kamu secara berurutan.";
        $c = $processed;
        $p2 = "Perjalanan diawali dari " . $c[0]['pos'] . " di mana " . $c[0]['name'] . " (" . $c[0]['orient'] . ") menunjukkan bahwa " . lcfirst($c[0]['meaning']) . 
              " Pada " . $c[1]['pos'] . ", kartu " . $c[1]['name'] . " (" . $c[1]['orient'] . ") memperlihatkan bahwa " . lcfirst($c[1]['meaning']) . 
              " Ke depannya pada " . $c[2]['pos'] . ", " . $c[2]['name'] . " (" . $c[2]['orient'] . ") mengisyaratkan bahwa " . lcfirst($c[2]['meaning']) . 
              " Di puncak lengkungan, " . $c[3]['pos'] . " diwakili oleh " . $c[3]['name'] . " (" . $c[3]['orient'] . ") yang mengingatkan bahwa " . lcfirst($c[3]['meaning']) . 
              " Pengaruh lingkungan pada " . $c[4]['pos'] . " diperlihatkan oleh " . $c[4]['name'] . " (" . $c[4]['orient'] . ") yaitu " . lcfirst($c[4]['meaning']) . 
              " Saran tindakan pada " . $c[5]['pos'] . " disarankan oleh " . $c[5]['name'] . " (" . $c[5]['orient'] . ") agar " . lcfirst($c[5]['meaning']) . 
              " Puncaknya pada " . $c[6]['pos'] . ", " . $c[6]['name'] . " (" . $c[6]['orient'] . ") menyimpulkan bahwa " . lcfirst($c[6]['meaning']);

        $p3 = ($uprights >= 4)
            ? "Kesimpulannya, alur Horseshoe menunjukkan hasil akhir yang menguntungkan. Kamu disarankan buat mengikuti petunjuk tindakan di atas agar tujuan kamu tercapai lancar."
            : "Kesimpulannya, ada tantangan yang perlu kamu lewati dengan sabar. Perhatikan saran tindakan pada kartu ke-6 buat meminimalkan hambatan di hasil akhir.";

        return str_replace(['**', '*', '#', '`'], '', $p1 . "\n\n" . $p2 . "\n\n" . $p3);
    }

    // Past, Present, Future (3 Kartu)
    if ($spread_key === 'ppf') {
        $past = $processed[0];
        $present = $processed[1];
        $future = $processed[2];

        $p1 = "Mengenai pertanyaan kamu tentang '" . $clean_question . "', berikut adalah alur perjalanan energi kamu yang terbagi dalam tiga fase waktu.";
        $p2 = "Untuk alur Masa Lalu, hadir kartu " . $past['name'] . " (" . $past['orient'] . ") yang memperlihatkan bahwa " . lcfirst($past['meaning']) . 
              " Kemudian pada Kondisi Saat Ini, kartu " . $present['name'] . " (" . $present['orient'] . ") menggambarkan situasi kamu di mana " . lcfirst($present['meaning']) . 
              " Ke depannya untuk Prospek Masa Depan, kartu " . $future['name'] . " (" . $future['orient'] . ") memberi petunjuk bahwa " . lcfirst($future['meaning']);

        $p3 = ($uprights >= 2)
            ? "Kesimpulannya, alur perkembangan dari masa lalu hingga masa depan mengarah pada perbaikan yang positif. Kamu sudah berada di jalur yang benar untuk mencapai hasil yang diinginkan."
            : "Kesimpulannya, ada beberapa pelajaran dari masa lalu dan kondisi saat ini yang perlu kamu evaluasi. Benahi hal yang mengganjal agar masa depan kamu menjadi lebih stabil.";

        return str_replace(['**', '*', '#', '`'], '', $p1 . "\n\n" . $p2 . "\n\n" . $p3);
    }

    // Yes or No Oracle (3 Kartu)
    if ($spread_key === 'yesno') {
        $p1 = "Mengenai pertanyaan kamu tentang '" . $clean_question . "', tebaran Yes or No Oracle memberikan gambaran pertimbangan sebagai berikut.";
        $details = [];
        foreach ($processed as $c) {
            $details[] = "Pada " . $c['pos'] . ", kartu " . $c['name'] . " (" . $c['orient'] . ") menunjukkan bahwa " . lcfirst($c['meaning']);
        }
        $p2 = implode(' ', $details);

        if ($yes_count > $no_count) {
            $p3 = "Berdasarkan akumulasi indikasi kartu di atas, jawaban untuk pertanyaan kamu mengarah pada YA. Energinya mendukung penuh rencana atau keinginan kamu saat ini.";
        } elseif ($no_count > $yes_count) {
            $p3 = "Berdasarkan akumulasi indikasi kartu di atas, jawaban untuk pertanyaan kamu mengarah pada TIDAK atau BELUM SAATNYA. Kartu menyarankan agar kamu lebih waspada dan tidak memaksakan kehendak.";
        } else {
            $p3 = "Berdasarkan akumulasi indikasi kartu di atas, jawaban untuk pertanyaan kamu saat ini masih NETRAL. Hasil akhirnya sangat tergantung pada keputusan dan tindakan yang akan kamu ambil.";
        }

        return str_replace(['**', '*', '#', '`'], '', $p1 . "\n\n" . $p2 . "\n\n" . $p3);
    }

    // Celtic Cross (10 Kartu)
    if ($spread_key === 'celtic' || $total >= 10) {
        $p1 = "Mengenai pertanyaan kamu tentang '" . $clean_question . "', tebaran komplit Celtic Cross membedah dinamika masalah kamu secara mendalam.";
        $c = $processed;
        $inti = "Inti situasi diwakili oleh " . $c[0]['name'] . " (" . $c[0]['orient'] . ") yang menandakan " . lcfirst($c[0]['meaning']) . 
                " Hal ini berhadapan dengan hambatan dari " . $c[1]['name'] . " (" . $c[1]['orient'] . ") di mana " . lcfirst($c[1]['meaning']);

        $batin = "Tujuan sadar kamu dipengaruhi oleh " . $c[2]['name'] . " (" . $c[2]['orient'] . ") yaitu " . lcfirst($c[2]['meaning']) . 
                 " Sementara di bawah sadar, kartu " . $c[3]['name'] . " (" . $c[3]['orient'] . ") menunjukkan bahwa " . lcfirst($c[3]['meaning']);

        $waktu = "Melihat alur waktunya, masa lalu diwakili oleh " . $c[4]['name'] . " (" . $c[4]['orient'] . ") yang memperlihatkan " . lcfirst($c[4]['meaning']) . 
                 " Lalu untuk masa depan dekat, kartu " . $c[5]['name'] . " (" . $c[5]['orient'] . ") memberi sinyal bahwa " . lcfirst($c[5]['meaning']);

        $eksternal = "Perasaan diri kamu digambarkan oleh " . $c[6]['name'] . " (" . $c[6]['orient'] . ") yaitu " . lcfirst($c[6]['meaning']) . 
                     " Pengaruh lingkungan sekitar diwakili oleh " . $c[7]['name'] . " (" . $c[7]['orient'] . ") yang menandakan " . lcfirst($c[7]['meaning']);

        $akhir = "Harapan atau ketakutan utama terlihat pada " . $c[8]['name'] . " (" . $c[8]['orient'] . ") di mana " . lcfirst($c[8]['meaning']) . 
                 " Sebagai puncak hasil akhir, " . $c[9]['name'] . " (" . $c[9]['orient'] . ") menyimpulkan bahwa " . lcfirst($c[9]['meaning']);

        $p2 = $inti . "\n\n" . $batin . "\n\n" . $waktu . "\n\n" . $eksternal . "\n\n" . $akhir;
        $p3 = ($uprights >= 5) 
            ? "Rangkuman Kesimpulan: Secara keseluruhan tebaran Celtic Cross memperlihatkan peluang keberhasilan yang besar. Kamu punya kapasitas buat mengatasi masalah ini."
            : "Rangkuman Kesimpulan: Secara keseluruhan tebaran Celtic Cross mengingatkan kamu buat ekstra hati-hati. Ada dinamika internal yang perlu diselesaikan dulu.";

        return str_replace(['**', '*', '#', '`'], '', $p1 . "\n\n" . $p2 . "\n\n" . $p3);
    }

    return "Gunakan pesan kartu sebagai panduan positif untuk melangkah.";
}

$total_cards = count($cards);
$category_labels = [
    'love' => 'Percintaan 💖',
    'career' => 'Karir & Keuangan 💼',
    'life' => 'Kehidupan 🌱',
    'general' => 'General 🔮'
];
?>

<style>
/* Animasi Terbang Kartu ke Meja Lengkung */
@keyframes flyInHorseshoe {
    0% {
        opacity: 0;
        transform: translateY(-150px) scale(0.3) rotate(-25deg);
    }
    60% {
        opacity: 1;
        transform: translateY(10px) scale(1.05) rotate(5deg);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1) rotate(0deg);
    }
}

.horseshoe-card-anim {
    animation: flyInHorseshoe 0.8s cubic-bezier(0.25, 1.25, 0.5, 1) forwards;
    animation-delay: calc(var(--i) * 0.18s);
    opacity: 0;
}

/* Offset Ketinggian Pola Lengkung Horseshoe 7 Kartu */
.hs-pos-0 { margin-top: 80px; }
.hs-pos-1 { margin-top: 40px; }
.hs-pos-2 { margin-top: 10px; }
.hs-pos-3 { margin-top: 0px; } /* Puncak */
.hs-pos-4 { margin-top: 10px; }
.hs-pos-5 { margin-top: 40px; }
.hs-pos-6 { margin-top: 80px; }
</style>

<!-- Header Informasi -->
<div class="text-center my-6 max-w-2xl mx-auto">
    <div class="bg-white/90 p-5 rounded-3xl cute-card-border shadow-sm mb-4">
        <span class="text-xs font-bold text-pastel-dark bg-pastel-lemon px-3 py-1 rounded-full font-heading mb-2 inline-block">
            Topik: <?= $category_labels[$category] ?? 'General'; ?>
        </span>
        <h2 class="text-lg md:text-xl font-bold text-pastel-dark italic">
            "<?= htmlspecialchars(str_replace(['**', '*'], '', $user_question)); ?>"
        </h2>
    </div>
    <span class="text-xs font-bold text-pastel-dark/70">
        Metode: <?= htmlspecialchars($reading['spread_name']); ?>
    </span>
</div>

<!-- Layout Dynamic Grid (Horseshoe Spread Melengkung / Grid Biasa / Single Center) -->
<?php 
$grid_class = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8';
if ($total_cards === 1) {
    $grid_class = 'flex justify-center';
} elseif ($spread_key === 'horseshoe') {
    $grid_class = 'grid grid-cols-2 md:grid-cols-7 gap-2 items-start justify-center min-h-[420px]';
}
?>

<div class="my-10 max-w-6xl mx-auto <?= $grid_class; ?>">
    <?php foreach ($cards as $index => $item): 
        $card = $item['card_data'];
        $is_rev = $item['is_reversed'];
        $clean_card_name = str_replace(['**', '*'], '', $card['name']);
        $meaning_spok = getCardMeaningByCategory($card, $category, $is_rev);
        $clean_meaning = str_replace(['**', '*'], '', $meaning_spok);
        
        $horseshoe_class = ($spread_key === 'horseshoe') ? "hs-pos-{$index}" : "";
    ?>
        <div class="flex flex-col items-center horseshoe-card-anim <?= $horseshoe_class; ?> <?= $total_cards === 1 ? 'w-full max-w-md' : 'w-full'; ?>" style="--i: <?= $index; ?>;">
            
            <span class="text-[10px] md:text-xs font-bold text-pastel-dark mb-2 text-center bg-white/90 px-3 py-1 rounded-full border border-pastel-lilac shadow-sm font-heading truncate max-w-full">
                <?= str_replace(['**', '*'], '', $item['position_label']); ?>
            </span>
            
            <div class="<?= $spread_key === 'horseshoe' ? 'w-28 md:w-36 h-[16rem] md:h-[18rem]' : 'w-60 h-[24rem]'; ?> perspective cursor-pointer" onclick="flipCardAndMeaning(this)">
                <div class="card-inner w-full h-full relative transform-style-3d transition-transform duration-700 rounded-3xl">
                    
                    <!-- Belakang Kartu -->
                    <div class="absolute w-full h-full bg-white cute-card-border rounded-3xl flex flex-col items-center justify-center p-3 backface-hidden shadow-sm">
                        <div class="<?= $spread_key === 'horseshoe' ? 'w-10 h-10' : 'w-20 h-20 mb-3'; ?> rounded-full bg-pastel-bunny border border-pastel-lilac flex items-center justify-center">
                            <span class="<?= $spread_key === 'horseshoe' ? 'text-lg' : 'text-3xl'; ?>">✨</span>
                        </div>
                        <span class="text-[11px] font-bold text-pastel-dark tracking-wide font-heading mt-1">MAGIC TAROT</span>
                        <span class="text-[9px] font-semibold text-pastel-dark/70 mt-2 bg-pastel-lemon px-2 py-0.5 rounded-full">Buka Kartu 💖</span>
                    </div>

                    <!-- Depan Kartu -->
                    <div class="absolute w-full h-full bg-white cute-card-border rounded-3xl p-3 flex flex-col items-center justify-between backface-hidden rotate-y-180">
                        <div class="text-center w-full">
                            <h3 class="text-[11px] font-bold text-pastel-dark tracking-wide truncate font-heading">
                                <?= $clean_card_name; ?>
                            </h3>
                            <span class="text-[8px] font-bold tracking-wider uppercase px-2 py-0.5 rounded-full inline-block mt-0.5 <?= $is_rev ? 'bg-pastel-pink text-pastel-dark' : 'bg-pastel-mint text-pastel-dark'; ?>">
                                <?= $is_rev ? 'Terbalik' : 'Tegak'; ?>
                            </span>
                        </div>

                        <div class="w-full <?= $spread_key === 'horseshoe' ? 'h-24' : 'h-44'; ?> my-1 rounded-2xl bg-pastel-bunny/40 border border-pastel-lilac/40 flex flex-col items-center justify-center p-1 relative">
                            <div class="transition-transform duration-300 <?= $is_rev ? 'rotate-180' : ''; ?>">
                                <?= renderCuteSymbol($card['suit'], $card['arcana']); ?>
                            </div>
                        </div>

                        <div class="text-[9px] font-semibold text-pastel-dark/80 text-center w-full border-t border-pastel-lilac/40 pt-1">
                            <?= $card['element']; ?>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Box Makna Tersembunyi (Hidden) -->
            <div class="card-meaning-box hidden mt-3 w-full bg-white/95 p-3 rounded-2xl border-2 border-pastel-lilac/70 shadow-sm text-left transition-all duration-300">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[10px] font-bold text-pastel-dark font-heading">📖 Makna Kartu:</span>
                    <span class="text-[8px] font-bold px-1.5 py-0.5 rounded-full <?= $is_rev ? 'bg-pastel-pink' : 'bg-pastel-mint'; ?>">
                        <?= $is_rev ? 'Terbalik' : 'Tegak'; ?>
                    </span>
                </div>
                <p class="text-[10px] text-pastel-dark/90 leading-snug font-semibold">
                    <?= htmlspecialchars($clean_meaning); ?>
                </p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Box Paragraf Interpretasi Jawaban -->
<section class="bg-white/95 p-6 md:p-8 rounded-3xl border-3 border-pastel-lilac shadow-md mb-10 max-w-4xl mx-auto">
    <h2 class="text-xl font-bold text-pastel-dark mb-4 flex items-center gap-2 font-heading">
        <span>🔮</span> Jawaban & Interpretasi Buat Kamu
    </h2>

    <div class="bg-pastel-bunny/30 p-5 rounded-2xl border border-pastel-lilac/50">
        <p class="text-xs md:text-sm text-pastel-dark leading-relaxed font-semibold text-justify whitespace-pre-line">
            <?= generateSmartInterpretation($user_question, $category, $cards, $spread_key); ?>
        </p>
    </div>

    <div class="mt-8 text-center">
        <a href="index.php" class="inline-block bg-pastel-pink hover:bg-pastel-lilac text-pastel-dark font-bold text-sm py-3 px-8 rounded-full shadow-md transition transform active:scale-95 border-2 border-pastel-lilac font-heading">
            Tanya Pertanyaan Lain 🌸
        </a>
    </div>
</section>

<script>
function flipCardAndMeaning(cardWrapper) {
    const inner = cardWrapper.querySelector('.card-inner');
    inner.classList.toggle('rotate-y-180');
    
    const meaningBox = cardWrapper.nextElementSibling;
    if (meaningBox && meaningBox.classList.contains('card-meaning-box')) {
        meaningBox.classList.toggle('hidden');
    }
}
</script>

<?php include 'includes/footer.php'; ?>
