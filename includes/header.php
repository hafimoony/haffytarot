<!DOCTYPE html>
<html lang="id" data-theme="pastel">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haffy Tarot Reading ✨</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        heading: ['Fredoka', 'sans-serif'],
                        body: ['Quicksand', 'sans-serif'],
                    },
                    colors: {
                        pastel: {
                            lemon: '#fffabf',
                            bunny: '#ffe7ff',
                            mint: '#b2f9e7',
                            pink: '#f4adcf',
                            lilac: '#bfc4ff',
                            dark: '#2e2538',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Definisi Skema Tema */
        html[data-theme="pastel"] body {
            background: linear-gradient(135deg, #e8dff5 0%, #fce1e4 40%, #e8f8f5 100%);
            color: #2e2538;
        }
        html[data-theme="gothic"] body {
            background: linear-gradient(135deg, #0f0c20 0%, #1a103c 50%, #0d1b2a 100%);
            color: #e0d6ff;
        }
        html[data-theme="vintage"] body {
            background: linear-gradient(135deg, #f4eccf 0%, #e6d3a3 50%, #d4b886 100%);
            color: #4a3b2c;
        }

        /* Warna Komponen Mengikuti Tema */
        html[data-theme="gothic"] .theme-card { background-color: rgba(26, 20, 50, 0.95) !important; color: #e0d6ff !important; border-color: #5c469c !important; }
        html[data-theme="vintage"] .theme-card { background-color: rgba(250, 246, 233, 0.95) !important; color: #4a3b2c !important; border-color: #8c6d46 !important; }

        h1, h2, h3, h4, .font-heading { font-family: 'Fredoka', sans-serif; }
        .cute-card-border { border: 3.5px solid #bfc4ff; box-shadow: 0 8px 20px rgba(191, 196, 255, 0.4); }
        .perspective { perspective: 1000px; }
        .transform-style-3d { transform-style: preserve-3d; }
        .backface-hidden { backface-visibility: hidden; }
        .rotate-y-180 { transform: rotateY(180deg); }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <header class="bg-white/70 backdrop-blur-md border-b-2 border-pastel-lilac sticky top-0 z-50 theme-card">
        <div class="max-w-6xl mx-auto px-4 py-3 flex flex-wrap justify-between items-center gap-3">
            <a href="index.php" class="flex items-center space-x-2">
                <span class="text-xl md:text-2xl font-bold tracking-wide flex items-center gap-2 font-heading">
                    ✨ Haffy Tarot Reading 🔮
                </span>
            </a>
            
            <nav class="flex items-center space-x-3 text-xs md:text-sm font-semibold">
                <a href="encyclopedia.php" class="hover:underline">📖 Ensiklopedia</a>
                <a href="birthcard.php" class="hover:underline">🌟 Birth Card</a>
                
                <!-- Selector Tema -->
                <select id="theme-select" onchange="changeTheme(this.value)" class="bg-pastel-bunny text-pastel-dark border border-pastel-lilac px-2 py-1 rounded-full text-xs font-bold font-heading focus:outline-none cursor-pointer">
                    <option value="pastel">🌸 Pastel Mode</option>
                    <option value="gothic">🌙 Gothic Night</option>
                    <option value="vintage">📜 Vintage Parchment</option>
                </select>
            </nav>
        </div>
    </header>
    
    <script>
        function changeTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('haffy_tarot_theme', theme);
        }
        
        // Memuat tema tersimpan di local storage
        const savedTheme = localStorage.getItem('haffy_tarot_theme') || 'pastel';
        document.documentElement.setAttribute('data-theme', savedTheme);
        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('theme-select');
            if(select) select.value = savedTheme;
        });
    </script>
    <main class="flex-grow container mx-auto px-4 py-8 max-w-6xl">