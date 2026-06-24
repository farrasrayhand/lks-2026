<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Asisten Virtual - Kaltim Smart Platform</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; min-height: 100vh; display: flex; flex-direction: column; }
        .header { background: #0f172a; color: #fff; padding: 16px 24px; display: flex; align-items: center; gap: 12px; }
        .header a { color: #94a3b8; text-decoration: none; font-size: 0.85rem; }
        .header a:hover { color: #fff; }
        .header h1 { font-size: 1.1rem; font-weight: 600; }
        .chat-container { flex: 1; max-width: 700px; width: 100%; margin: 0 auto; display: flex; flex-direction: column; padding: 20px; }
        .messages { flex: 1; overflow-y: auto; padding: 10px 0; display: flex; flex-direction: column; gap: 12px; }
        .msg { max-width: 80%; padding: 12px 16px; border-radius: 12px; font-size: 0.9rem; line-height: 1.5; white-space: pre-line; }
        .msg.bot { align-self: flex-start; background: #fff; border: 1px solid #e2e8f0; color: #1e293b; border-bottom-left-radius: 4px; }
        .msg.user { align-self: flex-end; background: #2563eb; color: #fff; border-bottom-right-radius: 4px; }
        .msg.typing { align-self: flex-start; background: #f1f5f9; color: #94a3b8; font-style: italic; }
        .input-area { display: flex; gap: 8px; padding: 16px 0; border-top: 1px solid #e2e8f0; margin-top: 12px; }
        .input-area input { flex: 1; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 24px; font-size: 0.9rem; outline: none; }
        .input-area input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .input-area button { padding: 12px 24px; background: #2563eb; color: #fff; border: none; border-radius: 24px; font-weight: 600; cursor: pointer; font-size: 0.9rem; }
        .input-area button:hover { background: #1d4ed8; }
        .suggestions { display: flex; gap: 6px; flex-wrap: wrap; padding: 10px 0; }
        .suggestion { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; padding: 6px 14px; border-radius: 16px; font-size: 0.78rem; cursor: pointer; }
        .suggestion:hover { background: #dbeafe; }
        .powered { text-align: center; padding: 12px; color: #94a3b8; font-size: 0.72rem; }
        @media (max-width: 600px) {
            .msg { max-width: 90%; }
            .header { padding: 12px 16px; }
            .chat-container { padding: 12px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="/">← Kembali</a>
        <h1>🤖 Asisten Virtual Kaltim</h1>
    </div>

    <div class="chat-container">
        <div class="messages" id="messages">
            <div class="msg bot">
Halo! Saya asisten virtual Kaltim Smart Platform 👋

Saya bisa bantu jawab pertanyaan seputar layanan publik:
• Pembuatan KTP, KK, Akta Kelahiran
• Izin Usaha & Surat Pindah
• Cek status & lapor masalah
• Cara daftar akun

Silakan ketik pertanyaan Anda ⬇️
            </div>
        </div>
        <div class="suggestions" id="suggestions">
            <span class="suggestion" onclick="sendSuggestion(this)">Cara buat KTP</span>
            <span class="suggestion" onclick="sendSuggestion(this)">Syarat KK</span>
            <span class="suggestion" onclick="sendSuggestion(this)">Cara lapor jalan rusak</span>
            <span class="suggestion" onclick="sendSuggestion(this)">Cek status pengajuan</span>
            <span class="suggestion" onclick="sendSuggestion(this)">Daftar akun</span>
            <span class="suggestion" onclick="sendSuggestion(this)">Jam operasional</span>
        </div>
        <div class="input-area">
            <input type="text" id="userInput" placeholder="Ketik pertanyaan Anda..." onkeypress="if(event.key==='Enter')send()">
            <button onclick="send()">Kirim</button>
        </div>
        <div class="powered">Powered by Amazon Lex (AWS) — LKS Cloud Computing 2026</div>
    </div>

    <script>
        const messages = document.getElementById('messages');
        const input = document.getElementById('userInput');
        let waiting = false;

        function addMessage(text, type) {
            const div = document.createElement('div');
            div.className = 'msg ' + type;
            div.textContent = text;
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
            return div;
        }

        function sendSuggestion(el) {
            input.value = el.textContent;
            send();
        }

        async function send() {
            const msg = input.value.trim();
            if (!msg || waiting) return;
            waiting = true;
            input.value = '';
            addMessage(msg, 'user');
            const typing = addMessage('Mengetik...', 'typing');

            try {
                const res = await fetch('/api/chatbot', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ message: msg })
                });
                const data = await res.json();
                typing.remove();
                addMessage(data.reply, 'bot');
            } catch (e) {
                typing.remove();
                addMessage('Maaf, terjadi gangguan. Silakan coba lagi.', 'bot');
            }
            waiting = false;
        }

        input.focus();
    </script>
</body>
</html>
