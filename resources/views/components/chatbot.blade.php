<button class="chatbotToggle" id="chatbotToggle" onclick="toggleChatbot()">
    <img src="{{ asset('assets/icon/cipa/chatbot.svg') }}" alt="Chat Cipa">
</button>

<div class="chatbotBox shadow" id="chatbotBox">
    <div class="chatbotHeader">
        <button class="btnCloseChat" onclick="toggleChatbot()">✕</button>
        <h3>Cipa</h3>
    </div>

    <div class="chatbotBody" id="chatbotBody">
        <div class="chatDate">Hari Ini</div>

        <div class="chatRow bot">
            <img src="{{ asset('assets/icon/cipa/chatbotChat.svg') }}" alt="Cipa" class="botAvatar">
            <div class="chatBubble bot caption charcoalGrey">
                Halow balow, selamat datang. Saya Cipa, asisten otomatis Cireng A'paweh yang siap membantu kamu. Apa
                yang bisa saya bantu?
                <span class="chatTime" id="botIntroTime">00:00</span>
            </div>
        </div>

        <div class="chatbotOptions" id="initialOptions">
            <button class="caption charcoalGrey" onclick="triggerBotResponse('menu')">Mau liat Menu Cireng yang paling
                laris!</button>
            <button class="caption charcoalGrey" onclick="triggerBotResponse('stok')">Cek stok cireng hari ini</button>
            <button class="caption charcoalGrey" onclick="triggerBotResponse('lokasi')">Lokasi Cabang A'paweh</button>
            <button class="caption charcoalGrey" onclick="triggerBotResponse('mitra')">Cara jadi Mitra A'paweh</button>
            <button class="caption highlight" onclick="initiateLiveChat()">
                <img class="imgCs" src="{{ asset('assets/icon/cipa/cs.png') }}" alt="CS"> Chat dengan Admin
            </button>
        </div>
    </div>

    <div class="chatConfirmPanel" id="chatConfirmPanel" style="display: none;">
        <p class="confirmQuestion caption charcoalGrey">Apakah ingin melanjutkan obrolan?</p>
        <div class="confirmBtns">
            <button class="caption charcoalGrey" onclick="handleConfirm('ya')">Ya</button>
            <button class="caption charcoalGrey" onclick="handleConfirm('tidak')">Tidak</button>
        </div>
    </div>

    <div class="chatbotFooter" id="chatbotFooter" style="display: none;">
        <input type="text" id="chatInput" placeholder="Ketik Pesan..." onkeypress="handleEnterKey(event)">
        <button class="btnSendChat" id="btnSendChat" onclick="sendUserLiveMessage()">
            <img id="btnBot" src="{{ asset('assets/icon/btnBot.svg') }}" alt="Send">
        </button>
    </div>

    <div class="confirmDisclaimer fdnGreyDark" id="botDisclaimer">
        <img src="{{ asset('assets/icon/info.svg') }}" alt="Informasi">
        <p class="caption charcoalGrey">Cipa dapat melakukan kesalahan, jadi PERLU periksa kembali.</p>
    </div>
</div>

<script src="{{ asset('js/components/chatbot.js') }}"></script>
