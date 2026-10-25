<?php
session_start();
require_once 'config/db.php';
include 'includes/header.php';

$birthdate = $_POST['birthdate'] ?? '';
$calculated_card = null;
$birth_number = null;

if (!empty($birthdate)) {
    // Algoritma Numerologi Birth Card (DD + MM + YYYY)
    $date_parts = explode('-', $birthdate); // Format YYYY-MM-DD
    if (count($date_parts) === 3) {
        $year = intval($date_parts[0]);
        $month = intval($date_parts[1]);
        $day = intval($date_parts[2]);

        $sum = $day + $month + $year;
        
        // Reduksi hingga <= 22 (Range 0-21 Major Arcana)
        while ($sum > 21) {
            $digits = str_split((string)$sum);
            $sum = array_sum(array_map('intval', $digits));
        }
        
        $birth_number = $sum;
        
        // Format pencarian kode Major Arcana (misal: major_01, major_02)
        $code_search = sprintf("major_%02d", $birth_number);
        
        $stmt = $conn->prepare("SELECT * FROM cards WHERE code = ? LIMIT 1");
        $stmt->bind_param("s", $code_search);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $calculated_card = $res->fetch_assoc();
        }
    }
}
?>

<div class="max-w-xl mx-auto my-6 text-center">
    <span class="bg-pastel-bunny text-pastel-dark font-bold text-xs px-4 py-1.5 rounded-full inline-block mb-2 border border-pastel-lilac">
        Kalkulator Numerologi 🌟
    </span>
    <h1 class="text-2xl md:text-3xl font-bold font-heading">Temukan Birth Card Kamu ✨</h1>
    <p class="text-xs md:text-sm font-semibold opacity-80 mt-1">
        Hitung kartu Major Arcana yang menaungi potensi kepribadian dan takdir batin kamu berdasarkan tanggal lahir.
    </p>

    <!-- Form Input Tanggal Lahir -->
    <form action="birthcard.php" method="POST" class="bg-white/90 p-6 rounded-3xl cute-card-border shadow-md mt-6 text-left theme-card">
        <label for="birthdate" class="block text-xs font-bold mb-2 font-heading">
            📅 Pilih Tanggal Lahir Kamu:
        </label>
        <input 
            type="date" 
            name="birthdate" 
            id="birthdate" 
            required 
            value="<?= htmlspecialchars($birthdate); ?>"
            class="w-full p-3 rounded-2xl border-2 border-pastel-lilac focus:outline-none focus:border-pastel-pink text-sm font-bold bg-pastel-bunny/30 mb-4"
        >
        <button type="submit" class="w-full bg-pastel-pink hover:bg-pastel-lilac text-pastel-dark font-bold text-sm py-3 px-6 rounded-2xl transition shadow-md border-2 border-pastel-lilac font-heading">
            Hitung Birth Card Saya 🌸
        </button>
    </form>

    <!-- Hasil Perhitungan Birth Card -->
    <?php if ($calculated_card): 
        $clean_name = str_replace(['**', '*'], '', preg_replace('/^[0-9IVXLCDM]+\s*-\s*/', '', $calculated_card['name']));
    ?>
        <div class="mt-8 bg-white/95 p-6 md:p-8 rounded-3xl cute-card-border shadow-lg text-left theme-card animate-fade-in">
            <div class="text-center mb-6">
                <span class="text-xs font-bold bg-pastel-lemon text-pastel-dark px-3 py-1 rounded-full font-heading">
                    Angka Takdir Tarot: #<?= $birth_number; ?>
                </span>
                <h2 class="text-2xl font-bold font-heading mt-2">
                    <?= htmlspecialchars($clean_name); ?>
                </h2>
                <span class="text-xs font-semibold opacity-80">Elemen: <?= htmlspecialchars($calculated_card['element']); ?></span>
            </div>

            <div class="bg-pastel-bunny/40 p-5 rounded-2xl border border-pastel-lilac mb-4 text-xs md:text-sm leading-relaxed font-semibold">
                <h3 class="font-bold text-pastel-dark mb-2 font-heading">🔮 Potensi & Karakter Utama Kamu:</h3>
                <p class="opacity-90">
                    <?= htmlspecialchars(str_replace(['**', '*'], '', $calculated_card['life_upright'])); ?>
                </p>
            </div>

            <div class="bg-pastel-mint/30 p-4 rounded-2xl border border-pastel-mint text-xs leading-relaxed font-semibold">
                <h3 class="font-bold text-emerald-900 mb-1 font-heading">🌱 Pesan Pertumbuhan Diri:</h3>
                <p class="opacity-90">
                    <?= htmlspecialchars(str_replace(['**', '*'], '', $calculated_card['general_upright'])); ?>
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>