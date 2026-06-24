<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kaltim Smart Platform - Layanan Publik Digital</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; color: #1e293b; background: #fff; }
        .top-bar { background: #0f172a; color: #94a3b8; text-align: center; padding: 6px; font-size: 0.78rem; }
        nav { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; height: 64px; position: sticky; top: 0; z-index: 100; }
        nav .brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        nav .brand .logo { width: 36px; height: 36px; background: #2563eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 1rem; }
        nav .brand .text { font-weight: 700; font-size: 1rem; color: #0f172a; }
        nav .brand .text small { display: block; font-weight: 400; font-size: 0.68rem; color: #64748b; }
        nav .links { display: flex; gap: 24px; align-items: center; }
        nav .links a { color: #475569; text-decoration: none; font-size: 0.88rem; font-weight: 500; }
        nav .links a:hover { color: #2563eb; }
        .btn { display: inline-block; padding: 10px 22px; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 0.88rem; transition: 0.2s; cursor: pointer; border: none; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-primary:hover { background: #1d4ed8; box-shadow: 0 4px 12px rgba(37,99,235,0.25); }
        .btn-outline { border: 2px solid #2563eb; color: #2563eb; }
        .btn-outline:hover { background: #2563eb; color: #fff; }
        .btn-white { background: #fff; color: #0f172a; }
        .btn-white:hover { background: #f1f5f9; }
        .hero { background: linear-gradient(160deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%); padding: 90px 20px 80px; text-align: center; position: relative; }
        .hero::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 60px; background: linear-gradient(to top right, #fff 49%, transparent 51%); }
        .hero-content { max-width: 680px; margin: 0 auto; position: relative; z-index: 1; }
        .hero .label { display: inline-block; background: rgba(37,99,235,0.2); color: #60a5fa; padding: 4px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; margin-bottom: 20px; }
        .hero h1 { font-size: 2.6rem; color: #fff; margin-bottom: 14px; line-height: 1.2; }
        .hero h1 span { color: #38bdf8; }
        .hero p { color: #94a3b8; font-size: 1.05rem; line-height: 1.7; margin-bottom: 32px; }
        .hero .cta { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); max-width: 850px; margin: 40px auto 0; gap: 1px; border-radius: 10px; overflow: hidden; }
        .stats-row .stat { background: rgba(255,255,255,0.06); padding: 20px; text-align: center; }
        .stats-row .stat .num { font-size: 1.4rem; font-weight: 700; color: #fff; }
        .stats-row .stat .label { color: #94a3b8; font-size: 0.72rem; margin-top: 2px; background: none; padding: 0; display: block; }
        section { padding: 70px 20px; }
        .container { max-width: 1050px; margin: 0 auto; }
        .section-title { text-align: center; margin-bottom: 48px; }
        .section-title h2 { font-size: 1.8rem; color: #0f172a; margin-bottom: 8px; }
        .section-title p { color: #64748b; font-size: 0.95rem; }
        .services-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .service-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 28px 20px; text-align: center; transition: 0.2s; }
        .service-card:hover { border-color: #2563eb; box-shadow: 0 4px 20px rgba(37,99,235,0.08); transform: translateY(-2px); }
        .service-card .icon { width: 56px; height: 56px; background: #eff6ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 14px; }
        .service-card h3 { font-size: 0.95rem; color: #1e293b; margin-bottom: 4px; }
        .service-card p { font-size: 0.82rem; color: #64748b; }
        .service-card .badge { display: inline-block; margin-top: 8px; background: #f1f5f9; color: #475569; padding: 3px 10px; border-radius: 4px; font-size: 0.7rem; font-weight: 500; }
        .how-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; }
        .how-step { text-align: center; }
        .how-step .num { width: 48px; height: 48px; background: #2563eb; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 700; margin: 0 auto 14px; }
        .how-step h3 { font-size: 1rem; margin-bottom: 6px; }
        .how-step p { font-size: 0.85rem; color: #64748b; line-height: 1.5; }
        .cta-section { background: #0f172a; text-align: center; padding: 70px 20px; }
        .cta-section h2 { color: #fff; font-size: 1.8rem; margin-bottom: 10px; }
        .cta-section p { color: #94a3b8; margin-bottom: 28px; font-size: 0.95rem; }
        .cta-section .cta-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .bg-gray { background: #f8fafc; }
        footer { background: #0f172a; color: #94a3b8; padding: 50px 20px 30px; }
        footer .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 32px; max-width: 1050px; margin: 0 auto 36px; }
        footer h4 { color: #e2e8f0; font-size: 0.9rem; margin-bottom: 12px; }
        footer p, footer a { font-size: 0.82rem; color: #64748b; text-decoration: none; display: block; margin-bottom: 6px; }
        footer a:hover { color: #38bdf8; }
        footer .bottom { border-top: 1px solid #1e293b; padding-top: 20px; text-align: center; font-size: 0.78rem; max-width: 1050px; margin: 0 auto; }
        @media (max-width: 768px) {
            .hero h1 { font-size: 1.8rem; }
            .stats-row { grid-template-columns: repeat(2,1fr); }
            .how-grid { grid-template-columns: 1fr; }
            footer .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="top-bar">🟢 Sistem beroperasi normal — Melayani warga Kalimantan Timur 24 jam</div>

    <nav>
        <a href="/" class="brand">
            <div class="logo">KT</div>
            <div class="text">Kaltim Smart Platform<small>Provinsi Kalimantan Timur</small></div>
        </a>
        <div class="links">
            <a href="#layanan">Layanan</a>
            <a href="#cara">Cara Kerja</a>
            <a href="/api-info">API</a>
            <a href="/health">Status</a>
            @auth
                <a href="{{ auth()->user()->isAdmin() ? '/admin/dashboard' : '/citizen/dashboard' }}" class="btn btn-primary" style="padding:8px 18px;font-size:0.82rem;">Dashboard</a>
            @else
                <a href="/login" class="btn btn-outline" style="padding:8px 18px;font-size:0.82rem;">Login</a>
            @endif
        </div>
    </nav>

    <div class="hero">
        <div class="hero-content">
            <div class="label">🆕 Layanan Publik Digital</div>
            <h1>Layanan Warga <span>Kalimantan Timur</span> Kini Lebih Mudah</h1>
            <p>Ajukan dokumen, laporkan masalah, dan pantau status permintaan Anda secara online — kapan saja, di mana saja.</p>
            <div class="cta">
                @auth
                    <a href="/citizen/dashboard" class="btn btn-white">Dashboard Saya</a>
                @else
                    <a href="/register" class="btn btn-white">Daftar Sekarang — Gratis</a>
                    <a href="#layanan" class="btn btn-outline" style="border-color:#fff;color:#fff;">Lihat Layanan</a>
                @endauth
            </div>
            <div class="stats-row">
                <div class="stat"><div class="num">5+</div><div class="label">Jenis Layanan</div></div>
                <div class="stat"><div class="num">24/7</div><div class="label">Akses Layanan</div></div>
                <div class="stat"><div class="num">Real-Time</div><div class="label">Notifikasi Status</div></div>
                <div class="stat"><div class="num">Online</div><div class="label">Tanpa Perlu ke Kantor</div></div>
            </div>
        </div>
    </div>

    <section id="layanan">
        <div class="container">
            <div class="section-title">
                <h2>Layanan Publik yang Tersedia</h2>
                <p>Pilih layanan yang Anda butuhkan dan ajukan secara online</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="icon">🪪</div>
                    <h3>Pembuatan KTP</h3>
                    <p>Kartu Tanda Penduduk elektronik baru atau pengganti yang hilang</p>
                    <span class="badge">⏱ 14 hari kerja</span>
                </div>
                <div class="service-card">
                    <div class="icon">📋</div>
                    <h3>Kartu Keluarga</h3>
                    <p>Pembuatan dan pembaruan Kartu Keluarga untuk seluruh anggota</p>
                    <span class="badge">⏱ 7 hari kerja</span>
                </div>
                <div class="service-card">
                    <div class="icon">👶</div>
                    <h3>Akta Kelahiran</h3>
                    <p>Pencatatan kelahiran dan penerbitan akta secara resmi</p>
                    <span class="badge">⏱ 7 hari kerja</span>
                </div>
                <div class="service-card">
                    <div class="icon">🏪</div>
                    <h3>Izin Usaha Mikro</h3>
                    <p>Perizinan untuk usaha mikro dan kecil di wilayah Kaltim</p>
                    <span class="badge">⏱ 21 hari kerja</span>
                </div>
                <div class="service-card">
                    <div class="icon">📝</div>
                    <h3>Surat Pindah</h3>
                    <p>Surat keterangan pindah domisili antar wilayah</p>
                    <span class="badge">⏱ 5 hari kerja</span>
                </div>
                <div class="service-card" style="border:2px dashed #cbd5e1;background:#f8fafc;">
                    <div class="icon" style="background:#fff;">📢</div>
                    <h3>Laporan Warga</h3>
                    <p>Laporkan masalah infrastruktur, lingkungan, atau sosial di sekitar Anda</p>
                    <span class="badge">Respon cepat</span>
                </div>
            </div>
        </div>
    </section>

    <section id="cara" class="bg-gray">
        <div class="container">
            <div class="section-title">
                <h2>Cara Menggunakan Layanan</h2>
                <p>Tiga langkah mudah untuk mendapatkan layanan publik</p>
            </div>
            <div class="how-grid">
                <div class="how-step">
                    <div class="num">1</div>
                    <h3>Daftar Akun</h3>
                    <p>Buat akun warga dengan email dan data diri Anda. Proses registrasi kurang dari 1 menit.</p>
                </div>
                <div class="how-step">
                    <div class="num">2</div>
                    <h3>Ajukan Permintaan</h3>
                    <p>Pilih jenis layanan, isi formulir, dan unggah dokumen pendukung jika diperlukan.</p>
                </div>
                <div class="how-step">
                    <div class="num">3</div>
                    <h3>Pantau Status</h3>
                    <p>Dapatkan notifikasi real-time setiap kali status permintaan Anda berubah.</p>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="section-title">
                <h2>Mengapa Kaltim Smart Platform?</h2>
                <p>Keunggulan layanan publik digital kami</p>
            </div>
            <div class="services-grid" style="grid-template-columns:repeat(auto-fit,minmax(230px,1fr));">
                <div class="service-card" style="text-align:left;">
                    <div class="icon" style="margin:0 0 14px 0;">⚡</div>
                    <h3>Cepat & Efisien</h3>
                    <p>Tidak perlu antre di kantor. Ajukan dari rumah dan pantau secara online.</p>
                </div>
                <div class="service-card" style="text-align:left;">
                    <div class="icon" style="margin:0 0 14px 0;">🔒</div>
                    <h3>Aman & Terpercaya</h3>
                    <p>Data Anda dilindungi dengan enkripsi dan standar keamanan ketat.</p>
                </div>
                <div class="service-card" style="text-align:left;">
                    <div class="icon" style="margin:0 0 14px 0;">🔔</div>
                    <h3>Notifikasi Real-Time</h3>
                    <p>Selalu tahu status terbaru permintaan layanan Anda tanpa harus cek manual.</p>
                </div>
                <div class="service-card" style="text-align:left;">
                    <div class="icon" style="margin:0 0 14px 0;">📱</div>
                    <h3>Akses 24/7</h3>
                    <p>Layanan tersedia kapan saja, di mana saja. Cukup dengan koneksi internet.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="cta-section">
        <div class="container">
            <h2>Siap Mengajukan Layanan?</h2>
            <p>Daftar sekarang dan nikmati kemudahan layanan publik digital</p>
            <div class="cta-btns">
                @auth
                    <a href="/citizen/dashboard" class="btn btn-white">Dashboard Saya</a>
                @else
                    <a href="/register" class="btn btn-white">Daftar Sekarang — Gratis</a>
                    <a href="/login" class="btn btn-outline" style="border-color:#fff;color:#fff;">Sudah Punya Akun? Login</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Chat Widget -->
    <div id="chatWidget" style="position:fixed;bottom:24px;right:24px;z-index:1000;">
        <div id="chatBox" style="display:none;width:340px;height:440px;background:#fff;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,0.18);overflow:hidden;margin-bottom:12px;flex-direction:column;">
            <div style="background:#2563eb;color:#fff;padding:14px 16px;display:flex;justify-content:space-between;align-items:center;">
                <strong style="font-size:0.9rem;">🤖 Asisten Kaltim</strong>
                <button onclick="toggleChat()" style="background:none;border:none;color:#fff;cursor:pointer;font-size:1.2rem;">✕</button>
            </div>
            <div id="chatMessages" style="flex:1;overflow-y:auto;padding:12px;display:flex;flex-direction:column;gap:8px;background:#f8fafc;">
                <div style="background:#fff;padding:10px 14px;border-radius:10px;font-size:0.82rem;max-width:90%;align-self:flex-start;border:1px solid #e2e8f0;line-height:1.4;">Halo! Saya asisten virtual 👋<br>Tanya apa saja seputar layanan publik.</div>
            </div>
            <div id="chatSuggestions" style="padding:6px 12px;display:flex;gap:4px;flex-wrap:wrap;background:#fff;border-top:1px solid #e2e8f0;">
                <span onclick="chatSend(this.textContent)" style="cursor:pointer;background:#eff6ff;color:#2563eb;padding:4px 10px;border-radius:12px;font-size:0.7rem;">Cara buat KTP</span>
                <span onclick="chatSend(this.textContent)" style="cursor:pointer;background:#eff6ff;color:#2563eb;padding:4px 10px;border-radius:12px;font-size:0.7rem;">Syarat KK</span>
                <span onclick="chatSend(this.textContent)" style="cursor:pointer;background:#eff6ff;color:#2563eb;padding:4px 10px;border-radius:12px;font-size:0.7rem;">Lapor jalan rusak</span>
                <span onclick="chatSend(this.textContent)" style="cursor:pointer;background:#eff6ff;color:#2563eb;padding:4px 10px;border-radius:12px;font-size:0.7rem;">Cek status</span>
            </div>
            <div style="display:flex;gap:8px;padding:10px;background:#fff;border-top:1px solid #e2e8f0;">
                <input id="chatInput" type="text" placeholder="Ketik pertanyaan..." style="flex:1;padding:8px 12px;border:1px solid #e2e8f0;border-radius:20px;font-size:0.82rem;outline:none;" onkeypress="if(event.key==='Enter')chatSend()">
                <button onclick="chatSend()" style="background:#2563eb;color:#fff;border:none;padding:8px 16px;border-radius:20px;cursor:pointer;font-size:0.82rem;">Kirim</button>
            </div>
        </div>
        <button id="chatBubble" onclick="toggleChat()" style="width:56px;height:56px;background:#2563eb;color:#fff;border:none;border-radius:50%;font-size:1.5rem;cursor:pointer;box-shadow:0 4px 16px rgba(37,99,235,0.4);display:flex;align-items:center;justify-content:center;">💬</button>
    </div>
    <script>
        let chatOpen = false;
        function toggleChat() {
            chatOpen = !chatOpen;
            document.getElementById('chatBox').style.display = chatOpen ? 'flex' : 'none';
            document.getElementById('chatBubble').textContent = chatOpen ? '✕' : '💬';
            if (chatOpen) document.getElementById('chatInput').focus();
        }
        async function chatSend(msg) {
            if (!msg) { msg = document.getElementById('chatInput').value.trim(); if (!msg) return; document.getElementById('chatInput').value = ''; }
            const msgs = document.getElementById('chatMessages');
            msgs.insertAdjacentHTML('beforeend', '<div style="background:#2563eb;color:#fff;padding:8px 14px;border-radius:10px;font-size:0.82rem;max-width:85%;align-self:flex-end;">'+msg+'</div>');
            msgs.insertAdjacentHTML('beforeend', '<div id="typing" style="color:#94a3b8;font-size:0.78rem;padding:4px 14px;">Mengetik...</div>');
            msgs.scrollTop = msgs.scrollHeight;
            try {
                const res = await fetch('/api/chatbot', {method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({message:msg})});
                const data = await res.json();
                document.getElementById('typing')?.remove();
                msgs.insertAdjacentHTML('beforeend', '<div style="background:#fff;padding:10px 14px;border-radius:10px;font-size:0.82rem;max-width:90%;align-self:flex-start;border:1px solid #e2e8f0;line-height:1.4;">'+data.reply.replace(/\n/g,'<br>')+'</div>');
            } catch(e) {
                document.getElementById('typing')?.remove();
                msgs.insertAdjacentHTML('beforeend', '<div style="color:#dc2626;font-size:0.78rem;">Gagal terhubung, coba lagi.</div>');
            }
            msgs.scrollTop = msgs.scrollHeight;
        }
    </script>

    <footer>
        <div class="footer-grid">
            <div>
                <h4>Kaltim Smart Platform</h4>
                <p>Platform layanan publik digital resmi Pemerintah Provinsi Kalimantan Timur. Dibangun untuk memudahkan warga mengakses layanan administrasi kependudukan dan perizinan.</p>
            </div>
            <div>
                <h4>Menu</h4>
                <a href="/">Beranda</a>
                <a href="#layanan">Layanan Publik</a>
                <a href="#cara">Cara Kerja</a>
                <a href="/login">Login Warga</a>
                <a href="/register">Daftar Akun</a>
            </div>
            <div>
                <h4>Lainnya</h4>
                <a href="/api-info">Dokumentasi API</a>
                <a href="/health">Status Sistem</a>
                <a href="/admin/dashboard">Dashboard Admin</a>
            </div>
        </div>
        <div class="bottom">&copy; 2026 Kaltim Smart Platform — Pemerintah Provinsi Kalimantan Timur — LKS Cloud Computing</div>
    </footer>
</body>
</html>
