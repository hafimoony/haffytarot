<?php
session_start();
include 'includes/header.php';

$category = $_POST['category'] ?? $_SESSION['category'] ?? 'general';
$_SESSION['category'] = $category;

$category_labels = [
    'love' => 'Percintaan 💖',
    'career' => 'Karir & Keuangan 💼',
    'life' => 'Kehidupan 🌱',
    'general' => 'General 🔮'
];
?>

<div class="text-center my-6">
    <span class="bg-pastel-bunny text-pastel-dark font-bold text-xs px-4 py-1.5 rounded-full inline-block mb-2 border border-pastel-lilac">
        Langkah 2 dari 4 🌸
    </span>
    <h1 class="text-2xl md:text-3xl font-bold font-heading">Pilih Metode Tebaran ✨</h1>
    <p class="text-xs md:text-sm font-semibold opacity-80 mt-1">
        Kategori Terpilih: <span class="bg-pastel-lemon text-pastel-dark px-3 py-0.5 rounded-full font-bold"><?= $category_labels[$category] ?? 'General'; ?></span>
    </p>
</div>

<form action="ask_question.php" method="POST" class="max-w-5xl mx-auto">
    <input type="hidden" name="category" value="<?= htmlspecialchars($category); ?>">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 my-6">
        <!-- 1 Kartu -->
        <div class="bg-white/90 rounded-3xl p-6 cute-card-border flex flex-col justify-between hover:-translate-y-1 transition duration-300 shadow-sm theme-card">
            <div>
                <span class="text-[10px] font-bold text-pastel-dark bg-pastel-lemon px-2.5 py-1 rounded-full inline-block mb-3 font-heading">1 Kartu Simple</span>
                <h3 class="text-lg font-bold mb-2 font-heading">Spill Energi ✨</h3>
                <p class="text-xs leading-relaxed mb-4 font-semibold opacity-80">
                    Jawaban cepat dan ringkas langsung pada inti pertanyaan kamu hari ini.
                </p>
            </div>
            <button type="submit" name="spread" value="daily" class="w-full text-center bg-pastel-mint hover:bg-pastel-lilac text-pastel-dark font-bold text-xs py-3 px-3 rounded-2xl transition shadow-sm border border-pastel-lilac font-heading">
                Pilih 1 Kartu 🌸
            </button>
        </div>

        <!-- 3 Kartu Alur -->
        <div class="bg-white/90 rounded-3xl p-6 cute-card-border flex flex-col justify-between hover:-translate-y-1 transition duration-300 shadow-sm theme-card">
            <div>
                <span class="text-[10px] font-bold text-pastel-dark bg-pastel-bunny px-2.5 py-1 rounded-full inline-block mb-3 font-heading">3 Kartu Alur</span>
                <h3 class="text-lg font-bold mb-2 font-heading">Past, Present, Future ⏳</h3>
                <p class="text-xs leading-relaxed mb-4 font-semibold opacity-80">
                    Melihat alur garis waktu: penyebab lalu, kondisi kini, & potensi hasil nanti.
                </p>
            </div>
            <button type="submit" name="spread" value="ppf" class="w-full text-center bg-pastel-pink hover:bg-pastel-lilac text-pastel-dark font-bold text-xs py-3 px-3 rounded-2xl transition shadow-sm border border-pastel-lilac font-heading">
                Pilih 3 Kartu 🌸
            </button>
        </div>

        <!-- 3 Kartu Yes/No -->
        <div class="bg-white/90 rounded-3xl p-6 cute-card-border flex flex-col justify-between hover:-translate-y-1 transition duration-300 shadow-sm theme-card">
            <div>
                <span class="text-[10px] font-bold text-pastel-dark bg-pastel-lemon px-2.5 py-1 rounded-full inline-block mb-3 font-heading">3 Kartu Jawaban</span>
                <h3 class="text-lg font-bold mb-2 font-heading">Yes or No Oracle 💖</h3>
                <p class="text-xs leading-relaxed mb-4 font-semibold opacity-80">
                    Khusus jika pertanyaan kamu membutuhkan kepastian Ya atau Tidak.
                </p>
            </div>
            <button type="submit" name="spread" value="yesno" class="w-full text-center bg-pastel-mint hover:bg-pastel-lilac text-pastel-dark font-bold text-xs py-3 px-3 rounded-2xl transition shadow-sm border border-pastel-lilac font-heading">
                Pilih Yes/No 🌸
            </button>
        </div>

        <!-- 7 Kartu Horseshoe -->
        <div class="bg-white/90 rounded-3xl p-6 cute-card-border flex flex-col justify-between hover:-translate-y-1 transition duration-300 shadow-sm theme-card">
            <div>
                <span class="text-[10px] font-bold text-pastel-dark bg-pastel-mint px-2.5 py-1 rounded-full inline-block mb-3 font-heading">7 Kartu Lengkung</span>
                <h3 class="text-lg font-bold mb-2 font-heading">Horseshoe Spread 🧲</h3>
                <p class="text-xs leading-relaxed mb-4 font-semibold opacity-80">
                    Tebaran melengkung melacak alur situasi, rintangan, saran, hingga jawaban akhir.
                </p>
            </div>
            <button type="submit" name="spread" value="horseshoe" class="w-full text-center bg-pastel-lemon hover:bg-pastel-lilac text-pastel-dark font-bold text-xs py-3 px-3 rounded-2xl transition shadow-sm border border-pastel-lilac font-heading">
                Pilih Horseshoe 🌸
            </button>
        </div>

        <!-- 10 Kartu Celtic Cross -->
        <div class="bg-white/90 rounded-3xl p-6 cute-card-border flex flex-col justify-between hover:-translate-y-1 transition duration-300 shadow-sm md:col-span-2 lg:col-span-2 theme-card">
            <div>
                <span class="text-[10px] font-bold text-pastel-dark bg-pastel-bunny px-2.5 py-1 rounded-full inline-block mb-3 font-heading">10 Kartu Komplit</span>
                <h3 class="text-lg font-bold mb-2 font-heading">Celtic Cross 🔮</h3>
                <p class="text-xs leading-relaxed mb-4 font-semibold opacity-80">
                    Analisis paling lengkap dan mendalam untuk membedah akar masalah secara menyeluruh.
                </p>
            </div>
            <button type="submit" name="spread" value="celtic" class="w-full text-center bg-pastel-pink hover:bg-pastel-lilac text-pastel-dark font-bold text-xs py-3 px-3 rounded-2xl transition shadow-sm border border-pastel-lilac font-heading">
                Pilih 10 Kartu 🌸
            </button>
        </div>
    </div>
</form>

<div class="text-center mt-6">
    <a href="index.php" class="text-xs font-bold opacity-75 hover:underline">
        ← Ganti Kategori Topik
    </a>
</div>

<?php include 'includes/footer.php'; ?>