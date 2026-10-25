<?php include 'includes/header.php'; ?>

<div class="max-w-3xl mx-auto my-8 text-center">
    <span class="bg-pastel-bunny text-pastel-dark font-bold text-xs px-4 py-1.5 rounded-full inline-block mb-3 border border-pastel-lilac">
        Langkah 1 dari 4 ✨
    </span>
    <h1 class="text-3xl font-bold text-pastel-dark mb-2 font-heading">Mau Bahas Topik Apa Hari Ini? 🌸</h1>
    <p class="text-pastel-dark/80 text-sm font-semibold mb-8">
        Pilih kategori yang paling sesuai dengan hal yang ingin kamu tanyakan~
    </p>

    <form action="select_spread.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5 text-left">
        <button type="submit" name="category" value="love" class="bg-white/90 p-6 rounded-3xl cute-card-border hover:-translate-y-1 transition duration-300 shadow-sm flex flex-col justify-between group theme-card">
            <div>
                <span class="text-3xl mb-3 block">💖</span>
                <h3 class="text-lg font-bold font-heading group-hover:text-pastel-pink transition">Percintaan & Hubungan</h3>
                <p class="text-xs opacity-75 font-semibold mt-1">Soal doi, kejelasan hubungan, mantan, atau jodoh masa depan.</p>
            </div>
            <span class="mt-4 text-xs font-bold text-pastel-dark bg-pastel-bunny px-3 py-1.5 rounded-full inline-block text-center border border-pastel-lilac font-heading">Pilih Topik Ini ✨</span>
        </button>

        <button type="submit" name="category" value="career" class="bg-white/90 p-6 rounded-3xl cute-card-border hover:-translate-y-1 transition duration-300 shadow-sm flex flex-col justify-between group theme-card">
            <div>
                <span class="text-3xl mb-3 block">💼</span>
                <h3 class="text-lg font-bold font-heading group-hover:text-pastel-mint transition">Karir & Keuangan</h3>
                <p class="text-xs opacity-75 font-semibold mt-1">Pekerjaan, bisnis, keuangan, proyek baru, atau rencana masa depan.</p>
            </div>
            <span class="mt-4 text-xs font-bold text-pastel-dark bg-pastel-mint px-3 py-1.5 rounded-full inline-block text-center border border-pastel-lilac font-heading">Pilih Topik Ini ✨</span>
        </button>

        <button type="submit" name="category" value="life" class="bg-white/90 p-6 rounded-3xl cute-card-border hover:-translate-y-1 transition duration-300 shadow-sm flex flex-col justify-between group theme-card">
            <div>
                <span class="text-3xl mb-3 block">🌱</span>
                <h3 class="text-lg font-bold font-heading group-hover:text-pastel-lemon transition">Kehidupan & Pengembangan Diri</h3>
                <p class="text-xs opacity-75 font-semibold mt-1">Arah hidup, keputusan besar, kesehatan batin, dan pencarian jati diri.</p>
            </div>
            <span class="mt-4 text-xs font-bold text-pastel-dark bg-pastel-lemon px-3 py-1.5 rounded-full inline-block text-center border border-pastel-lilac font-heading">Pilih Topik Ini ✨</span>
        </button>

        <button type="submit" name="category" value="general" class="bg-white/90 p-6 rounded-3xl cute-card-border hover:-translate-y-1 transition duration-300 shadow-sm flex flex-col justify-between group theme-card">
            <div>
                <span class="text-3xl mb-3 block">🔮</span>
                <h3 class="text-lg font-bold font-heading group-hover:text-pastel-lilac transition">General / Pertanyaan Lain</h3>
                <p class="text-xs opacity-75 font-semibold mt-1">Pertanyaan umum atau situasi yang butuh panduan cepat.</p>
            </div>
            <span class="mt-4 text-xs font-bold text-pastel-dark bg-pastel-bunny px-3 py-1.5 rounded-full inline-block text-center border border-pastel-lilac font-heading">Pilih Topik Ini ✨</span>
        </button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>