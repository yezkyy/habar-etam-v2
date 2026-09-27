<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teleprompter Studio On-Air — {{ \App\Models\SystemSetting::get('studio_partner_name', 'PT SCM Studio') }} & {{ \App\Models\SystemSetting::get('site_name', 'Habar Etam') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/logo-habar-etam.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    @php
    $isProduction = app()->environment('production');
    $manifestPath = $isProduction ? '../public_html/build/manifest.json' : public_path('build/manifest.json');
    @endphp

    @if ($isProduction && file_exists($manifestPath))
    @php
    $manifest = json_decode(file_get_contents($manifestPath), true);
    @endphp
    <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
    <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
    @else
    @viteReactRefresh
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @endif

    <style>
        body {
            background-color: #000000 !important;
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            overflow: hidden;
            user-select: none;
        }

        /* Reading Eyeline Sweetspot Guide Overlay */
        .reading-guide-bar {
            position: fixed;
            top: 40%;
            left: 0;
            right: 0;
            height: 140px;
            pointer-events: none;
            border-top: 1px dashed rgba(245, 158, 11, 0.3);
            border-bottom: 1px dashed rgba(245, 158, 11, 0.3);
            background: linear-gradient(180deg, rgba(245, 158, 11, 0.03) 0%, rgba(245, 158, 11, 0.08) 50%, rgba(245, 158, 11, 0.03) 100%);
            z-index: 10;
        }

        /* Mirrored text mode for physical teleprompter glass */
        .is-mirrored {
            transform: scaleX(-1) !important;
        }

        /* Custom scrollbar hidden */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-black text-white selection:bg-amber-500 selection:text-black"
    x-data="teleprompterApp()"
    x-init="initApp()"
    @keydown.window="handleKeydown($event)">

    <!-- Reading Sweetspot Guideline Overlay (Toggleable) -->
    <div x-show="showGuide" x-cloak class="reading-guide-bar">
        <div class="max-w-6xl mx-auto h-full flex items-center justify-between px-6 text-[10px] font-mono font-bold text-amber-500/50 uppercase tracking-widest">
            <span>&bull; AREA BACA KAMERA (EYELINE)</span>
            <span>{{ strtoupper(\App\Models\SystemSetting::get('studio_partner_name', 'PT SCM STUDIO')) }} &bull;</span>
        </div>
    </div>

    <!-- Top Master Broadcast Control Ribbon -->
    <header class="bg-gray-950/95 border-b border-gray-800/80 backdrop-blur-xl px-4 sm:px-6 py-3 shrink-0 z-30 flex flex-wrap items-center justify-between gap-3 shadow-2xl transition-all duration-300"
        :class="{ 'opacity-20 hover:opacity-100': hideControlsOnScroll && isScrolling }">

        <!-- Left: Studio Live Beacon & Clock -->
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-black tracking-wider uppercase shadow-inner">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                <span class="w-2 h-2 rounded-full bg-rose-500 -ml-4"></span>
                <span>ON-AIR STUDIO</span>
            </div>

            <div class="hidden sm:flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-gray-200 border border-white/10 text-xs font-mono font-bold">
                <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-400"></i>
                <span x-text="currentTime">--:--:-- WITA</span>
            </div>

            <!-- Playlist Selector Dropdown -->
            <div class="relative flex items-center gap-1">
                <select x-model="currentIndex"
                    @change="selectSegment(parseInt($event.target.value))"
                    class="bg-gray-900 text-white text-xs font-bold px-3 py-1.5 rounded-xl border border-gray-700 hover:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 max-w-[200px] sm:max-w-xs truncate cursor-pointer">
                    <template x-for="(item, idx) in playlist" :key="idx">
                        <option :value="idx" x-text="'#' + (idx + 1) + ' [' + item.source + '] ' + item.title"></option>
                    </template>
                </select>

                <!-- Prev & Next Buttons -->
                <button type="button"
                    @click="prevSegment()"
                    :disabled="currentIndex <= 0"
                    class="p-1.5 rounded-xl bg-gray-900 hover:bg-gray-800 disabled:opacity-40 disabled:cursor-not-allowed text-gray-300 hover:text-white border border-gray-800 cursor-pointer"
                    title="Segmen Sebelumnya (Panah Kiri)">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
                <button type="button"
                    @click="nextSegment()"
                    :disabled="currentIndex >= playlist.length - 1"
                    class="p-1.5 rounded-xl bg-gray-900 hover:bg-gray-800 disabled:opacity-40 disabled:cursor-not-allowed text-gray-300 hover:text-white border border-gray-800 cursor-pointer"
                    title="Segmen Berikutnya (Panah Kanan)">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Center: Prompter Adjusters (Speed, Font Size, Mirror, Guide) -->
        <div class="flex flex-wrap items-center gap-2">
            <!-- Speed Control -->
            <div class="flex items-center gap-1.5 bg-gray-900 px-2.5 py-1 rounded-xl border border-gray-800">
                <span class="text-[11px] text-gray-400 font-semibold hidden md:inline">Kecepatan:</span>
                <button type="button" @click="changeSpeed(-1)" class="w-6 h-6 rounded-lg bg-gray-800 hover:bg-gray-700 text-white text-xs font-bold cursor-pointer">-</button>
                <span x-text="speed" class="text-xs font-mono font-bold text-amber-400 w-5 text-center">2</span>
                <button type="button" @click="changeSpeed(1)" class="w-6 h-6 rounded-lg bg-gray-800 hover:bg-gray-700 text-white text-xs font-bold cursor-pointer">+</button>
            </div>

            <!-- Font Size Control -->
            <div class="flex items-center gap-1.5 bg-gray-900 px-2.5 py-1 rounded-xl border border-gray-800">
                <span class="text-[11px] text-gray-400 font-semibold hidden md:inline">Ukuran:</span>
                <button type="button" @click="changeFontSize(-4)" class="w-6 h-6 rounded-lg bg-gray-800 hover:bg-gray-700 text-white text-xs font-bold cursor-pointer">A-</button>
                <span x-text="fontSize + 'px'" class="text-xs font-mono font-bold text-amber-400 w-10 text-center">42px</span>
                <button type="button" @click="changeFontSize(4)" class="w-6 h-6 rounded-lg bg-gray-800 hover:bg-gray-700 text-white text-xs font-bold cursor-pointer">A+</button>
            </div>

            <!-- Theme / Color Switcher -->
            <div class="hidden lg:flex items-center gap-1 bg-gray-900 p-1 rounded-xl border border-gray-800">
                <button type="button" @click="textColor = 'text-yellow-400'" :class="textColor === 'text-yellow-400' ? 'ring-2 ring-yellow-400' : ''" class="w-5 h-5 rounded-md bg-yellow-400 cursor-pointer" title="Kuning Emas"></button>
                <button type="button" @click="textColor = 'text-white'" :class="textColor === 'text-white' ? 'ring-2 ring-white' : ''" class="w-5 h-5 rounded-md bg-white cursor-pointer" title="Putih Tajam"></button>
                <button type="button" @click="textColor = 'text-emerald-400'" :class="textColor === 'text-emerald-400' ? 'ring-2 ring-emerald-400' : ''" class="w-5 h-5 rounded-md bg-emerald-400 cursor-pointer" title="Hijau Studio"></button>
                <button type="button" @click="textColor = 'text-cyan-300'" :class="textColor === 'text-cyan-300' ? 'ring-2 ring-cyan-300' : ''" class="w-5 h-5 rounded-md bg-cyan-300 cursor-pointer" title="Biru Cyan"></button>
            </div>

            <!-- Mirror / Glass Mode Toggle -->
            <button type="button"
                @click="isMirrored = !isMirrored"
                class="px-2.5 py-1.5 rounded-xl text-xs font-bold border transition-colors inline-flex items-center gap-1.5 cursor-pointer"
                :class="isMirrored ? 'bg-amber-500 text-gray-950 border-amber-500' : 'bg-gray-900 text-gray-300 border-gray-800 hover:bg-gray-800'">
                <i data-lucide="flip-horizontal" class="w-3.5 h-3.5"></i>
                <span class="hidden sm:inline" x-text="isMirrored ? 'Kaca Cermin: ON' : 'Cermin'"></span>
            </button>

            <!-- Guide Line Toggle -->
            <button type="button"
                @click="showGuide = !showGuide"
                class="p-1.5 rounded-xl border transition-colors text-xs font-bold inline-flex items-center cursor-pointer"
                :class="showGuide ? 'bg-amber-500/20 text-amber-400 border-amber-500/40' : 'bg-gray-900 text-gray-400 border-gray-800 hover:text-white'"
                title="Garis Pandu Tatapan Kamera (G)">
                <i data-lucide="align-center" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Right: Play/Pause, Fullscreen & Exit -->
        <div class="flex items-center gap-2">
            <!-- Play / Pause Master Button -->
            <button type="button"
                @click="toggleScroll()"
                class="h-9 px-4 rounded-xl text-xs font-black transition-all inline-flex items-center gap-2 shadow-lg cursor-pointer transform active:scale-95"
                :class="isScrolling ? 'bg-amber-500 hover:bg-amber-600 text-gray-950 ring-2 ring-amber-400/50' : 'bg-emerald-500 hover:bg-emerald-600 text-white ring-2 ring-emerald-400/50'">
                <i data-lucide="play" x-show="!isScrolling" class="w-4 h-4 fill-current"></i>
                <i data-lucide="pause" x-show="isScrolling" class="w-4 h-4 fill-current"></i>
                <span x-text="isScrolling ? 'JEDA (SPASI)' : 'MULAI (SPASI)'"></span>
            </button>

            <!-- Restart to Top -->
            <button type="button"
                @click="restartScroll()"
                class="p-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-gray-300 hover:text-white border border-gray-800 transition-colors cursor-pointer"
                title="Kembali ke Awal (Home / R)">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
            </button>

            <!-- Native Fullscreen Toggle -->
            <button type="button"
                @click="toggleFullscreen()"
                class="p-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-gray-300 hover:text-white border border-gray-800 transition-colors cursor-pointer"
                title="Fullscreen Layar Penuh (F)">
                <i data-lucide="maximize" class="w-4 h-4"></i>
            </button>

            <!-- Keyboard Hotkeys Help -->
            <button type="button"
                @click="helpModal = true"
                class="p-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-gray-300 hover:text-white border border-gray-800 transition-colors cursor-pointer"
                title="Bantuan Shortcut Keyboard (?)">
                <i data-lucide="help-circle" class="w-4 h-4"></i>
            </button>

            <!-- Close / Return to Admin Tab -->
            <button type="button"
                onclick="window.close()"
                class="p-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 border border-rose-500/20 transition-colors cursor-pointer"
                title="Tutup Tab Teleprompter">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </header>

    <!-- Reading Progress Indicator Line -->
    <div class="w-full bg-gray-900 h-1 relative z-20">
        <div class="bg-gradient-to-r from-amber-500 to-yellow-400 h-full transition-all duration-100"
            :style="'width: ' + readingProgress + '%'"></div>
    </div>

    <!-- Main Teleprompter Scroll Screen Canvas -->
    <main id="prompterViewport"
        class="flex-1 overflow-y-auto no-scrollbar px-6 sm:px-16 md:px-24 py-16 text-center select-text relative"
        :class="{ 'is-mirrored': isMirrored }"
        @scroll="calculateProgress()">

        <div class="max-w-4xl mx-auto space-y-12 transition-all">
            <!-- Header Segment Indicator -->
            <div class="space-y-3 pt-6">
                <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30 text-xs font-mono font-black uppercase tracking-wider">
                    <span x-text="'SEGMEN #' + (currentIndex + 1) + ' DARI ' + playlist.length"></span>
                    <span>&bull;</span>
                    <span x-text="currentItem.source"></span>
                    <template x-if="currentItem.is_featured_live">
                        <span class="px-2 py-0.5 rounded-full bg-rose-500 text-white text-[10px] font-black animate-pulse">LIVE</span>
                    </template>
                </div>

                <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight"
                    x-text="currentItem.title"></h1>

                <p class="text-xs sm:text-sm text-gray-400 font-medium"
                    x-text="currentItem.subtitle"></p>

                <div class="flex items-center justify-center gap-3 text-xs text-gray-500 font-mono">
                    <span x-text="currentItem.word_count + ' KATA'"></span>
                    <span>•</span>
                    <span x-text="'ESTIMASI WAKTU: ~' + currentItem.reading_seconds + ' DETIK'"></span>
                </div>
            </div>

            <div class="h-0.5 w-32 bg-amber-500/40 mx-auto rounded-full"></div>

            <!-- Dynamic Large Script Reading Text -->
            <div class="font-sans font-extrabold leading-relaxed tracking-wide transition-all selection:bg-amber-500 selection:text-black pb-96"
                :class="textColor"
                :style="'font-size: ' + fontSize + 'px; line-height: 1.6; letter-spacing: 0.02em;'"
                x-text="currentItem.editorial_script">
            </div>

            <!-- End of Segment Marker -->
            <div class="pt-12 pb-32 border-t border-gray-900 space-y-4">
                <span class="text-xs font-mono uppercase tracking-widest text-gray-600 block">
                    [ AKHIR NASKAH SEGMEN INI ]
                </span>
                <template x-if="currentIndex < playlist.length - 1">
                    <button type="button"
                        @click="nextSegment()"
                        class="px-6 py-2.5 rounded-2xl bg-amber-500 hover:bg-amber-600 text-gray-950 font-bold text-xs inline-flex items-center gap-2 cursor-pointer shadow-lg">
                        <span>Lanjut ke Segmen Berikutnya</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </template>
            </div>
        </div>
    </main>

    <!-- Bottom Status Bar -->
    <footer class="bg-gray-950 border-t border-gray-900 px-6 py-2 shrink-0 z-20 flex items-center justify-between text-[11px] font-mono text-gray-500">
        <div class="flex items-center gap-4">
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full" :class="isScrolling ? 'bg-emerald-500 animate-pulse' : 'bg-gray-600'"></span>
                <span x-text="isScrolling ? 'SCROLLING AKTIF' : 'STANDBY'"></span>
            </span>
            <span class="hidden sm:inline">|</span>
            <span class="hidden sm:inline" x-text="'PROGRESS: ' + Math.round(readingProgress) + '%'"></span>
        </div>

        <div>
            PT SCM BROADCAST NETWORK • HABAR ETAM V2
        </div>
    </footer>

    <!-- Keyboard Shortcuts Help Modal -->
    <div x-show="helpModal"
        x-cloak
        @click.outside="helpModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4 backdrop-blur-md">
        <div class="bg-gray-950 border border-gray-800 rounded-3xl max-w-lg w-full p-6 sm:p-7 space-y-5 text-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-800 pb-3">
                <div class="flex items-center gap-2">
                    <span class="p-2 rounded-xl bg-amber-500/20 text-amber-400"><i data-lucide="keyboard" class="w-4 h-4"></i></span>
                    <h3 class="text-sm font-bold text-white">Panduan Shortcut Keyboard Prompter</h3>
                </div>
                <button type="button" @click="helpModal = false" class="text-gray-400 hover:text-white p-1">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="space-y-2.5 text-xs">
                <div class="flex items-center justify-between p-2 rounded-xl bg-gray-900 border border-gray-800/80">
                    <span class="text-gray-300">Mulai / Jeda Gulir Teks (Play/Pause)</span>
                    <kbd class="px-2.5 py-1 bg-black rounded-lg border border-gray-700 text-amber-400 font-mono font-bold">SPASI</kbd>
                </div>
                <div class="flex items-center justify-between p-2 rounded-xl bg-gray-900 border border-gray-800/80">
                    <span class="text-gray-300">Segmen Berikutnya / Sebelumnya</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-black rounded-lg border border-gray-700 text-amber-400 font-mono font-bold">&larr;</kbd>
                        <kbd class="px-2 py-1 bg-black rounded-lg border border-gray-700 text-amber-400 font-mono font-bold">&rarr;</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between p-2 rounded-xl bg-gray-900 border border-gray-800/80">
                    <span class="text-gray-300">Tambah / Kurangi Kecepatan</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-black rounded-lg border border-gray-700 text-amber-400 font-mono font-bold">+</kbd>
                        <kbd class="px-2 py-1 bg-black rounded-lg border border-gray-700 text-amber-400 font-mono font-bold">-</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between p-2 rounded-xl bg-gray-900 border border-gray-800/80">
                    <span class="text-gray-300">Mode Layar Penuh (Fullscreen)</span>
                    <kbd class="px-2.5 py-1 bg-black rounded-lg border border-gray-700 text-amber-400 font-mono font-bold">F</kbd>
                </div>
                <div class="flex items-center justify-between p-2 rounded-xl bg-gray-900 border border-gray-800/80">
                    <span class="text-gray-300">Balik Kaca Cermin (Mirror Horizontal)</span>
                    <kbd class="px-2.5 py-1 bg-black rounded-lg border border-gray-700 text-amber-400 font-mono font-bold">M</kbd>
                </div>
                <div class="flex items-center justify-between p-2 rounded-xl bg-gray-900 border border-gray-800/80">
                    <span class="text-gray-300">Garis Pandu Tatapan Kamera (Eyeline Guide)</span>
                    <kbd class="px-2.5 py-1 bg-black rounded-lg border border-gray-700 text-amber-400 font-mono font-bold">G</kbd>
                </div>
                <div class="flex items-center justify-between p-2 rounded-xl bg-gray-900 border border-gray-800/80">
                    <span class="text-gray-300">Kembali ke Awal Naskah (Restart)</span>
                    <kbd class="px-2.5 py-1 bg-black rounded-lg border border-gray-700 text-amber-400 font-mono font-bold">Home / R</kbd>
                </div>
            </div>

            <div class="pt-2 text-center">
                <button type="button" @click="helpModal = false" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-gray-950 font-bold rounded-xl text-xs cursor-pointer">
                    Mengerti, Siap Siaran
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function teleprompterApp() {
            return {
                playlist: @json($playlist),
                currentIndex: 0,
                currentItem: {},

                currentTime: '--:--:-- WITA',
                isScrolling: false,
                isMirrored: false,
                showGuide: true,
                hideControlsOnScroll: true,
                fontSize: 42,
                speed: 2,
                textColor: 'text-yellow-400',
                readingProgress: 0,

                scrollInterval: null,
                helpModal: false,

                initApp() {
                    if (this.playlist && this.playlist.length > 0) {
                        this.currentItem = this.playlist[0];
                    } else {
                        this.currentItem = {
                            title: 'Belum Ada Naskah Siaran',
                            subtitle: 'Pilih topik dari feed redaksi',
                            editorial_script: 'Tidak ada materi siaran yang dipilih untuk dibacakan pada sesi teleprompter kali ini.',
                            source: 'Studio',
                            word_count: 0,
                            reading_seconds: 0
                        };
                    }

                    // Clock loop
                    const updateClock = () => {
                        const now = new Date();
                        const options = {
                            timeZone: 'Asia/Makassar',
                            hour12: false,
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        };
                        this.currentTime = now.toLocaleTimeString('id-ID', options) + ' WITA';
                    };
                    updateClock();
                    setInterval(updateClock, 1000);

                    if (window.initIcons) {
                        window.initIcons();
                    }
                },

                selectSegment(index) {
                    if (index >= 0 && index < this.playlist.length) {
                        this.currentIndex = index;
                        this.currentItem = this.playlist[index];
                        this.restartScroll();
                        this.$nextTick(() => {
                            if (window.initIcons) window.initIcons();
                        });
                    }
                },

                prevSegment() {
                    if (this.currentIndex > 0) {
                        this.selectSegment(this.currentIndex - 1);
                    }
                },

                nextSegment() {
                    if (this.currentIndex < this.playlist.length - 1) {
                        this.selectSegment(this.currentIndex + 1);
                    }
                },

                toggleScroll() {
                    this.isScrolling = !this.isScrolling;
                    if (this.isScrolling) {
                        this.startScrollEngine();
                    } else {
                        if (this.scrollInterval) clearInterval(this.scrollInterval);
                    }
                },

                startScrollEngine() {
                    if (this.scrollInterval) clearInterval(this.scrollInterval);
                    const viewport = document.getElementById('prompterViewport');
                    if (!viewport) return;

                    this.scrollInterval = setInterval(() => {
                        if (this.isScrolling && viewport) {
                            viewport.scrollTop += this.speed;
                            this.calculateProgress();

                            // Detect end of scroll
                            if (viewport.scrollTop + viewport.clientHeight >= viewport.scrollHeight - 5) {
                                this.isScrolling = false;
                                clearInterval(this.scrollInterval);
                            }
                        }
                    }, 30);
                },

                restartScroll() {
                    const viewport = document.getElementById('prompterViewport');
                    if (viewport) {
                        viewport.scrollTop = 0;
                    }
                    this.readingProgress = 0;
                    if (this.isScrolling) {
                        this.startScrollEngine();
                    }
                },

                calculateProgress() {
                    const viewport = document.getElementById('prompterViewport');
                    if (!viewport) return;
                    const maxScroll = viewport.scrollHeight - viewport.clientHeight;
                    if (maxScroll > 0) {
                        this.readingProgress = Math.min(100, Math.max(0, (viewport.scrollTop / maxScroll) * 100));
                    }
                },

                changeSpeed(delta) {
                    this.speed = Math.max(1, Math.min(10, this.speed + delta));
                },

                changeFontSize(delta) {
                    this.fontSize = Math.max(24, Math.min(84, this.fontSize + delta));
                },

                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(err => {
                            console.error('Gagal masuk mode fullscreen: ', err);
                        });
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        }
                    }
                },

                handleKeydown(e) {
                    // If modal is open or target is an input/select, don't trigger hotkeys
                    if (this.helpModal || ['INPUT', 'SELECT', 'TEXTAREA'].includes(e.target.tagName)) {
                        return;
                    }

                    if (e.code === 'Space') {
                        e.preventDefault();
                        this.toggleScroll();
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        this.nextSegment();
                    } else if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        this.prevSegment();
                    } else if (e.key === '+' || e.key === '=') {
                        e.preventDefault();
                        this.changeSpeed(1);
                    } else if (e.key === '-' || e.key === '_') {
                        e.preventDefault();
                        this.changeSpeed(-1);
                    } else if (e.key === 'f' || e.key === 'F') {
                        e.preventDefault();
                        this.toggleFullscreen();
                    } else if (e.key === 'm' || e.key === 'M') {
                        e.preventDefault();
                        this.isMirrored = !this.isMirrored;
                    } else if (e.key === 'g' || e.key === 'G') {
                        e.preventDefault();
                        this.showGuide = !this.showGuide;
                    } else if (e.key === 'r' || e.key === 'R' || e.key === 'Home') {
                        e.preventDefault();
                        this.restartScroll();
                    } else if (e.key === '?') {
                        e.preventDefault();
                        this.helpModal = !this.helpModal;
                    }
                }
            };
        }
    </script>
</body>

</html>