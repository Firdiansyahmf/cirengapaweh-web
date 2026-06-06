@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/chat.css') }}">
@endpush

@section('content')
    <div class="content-header" style="margin-bottom: 20px;">
        <h2 class="displayH2 charcoalGrey">Manajemen Chat</h2>
    </div>

    <div class="chatContainer">
        <div class="chatSidebar">
            <div class="chatSidebarHeader">
                <h3 class="subH3">Session Chat</h3>
                <div class="chatTabs">
                    <button class="chatTab active" onclick="filterSessions('all', this)">Semua</button>
                    <button class="chatTab" onclick="filterSessions('open', this)">Dibuka</button>
                    <button class="chatTab" onclick="filterSessions('closed', this)">Ditutup</button>
                </div>
            </div>
            <div class="sessionList" id="sessionListAdmin">
                <p>Memuat data chat...</p>
            </div>
        </div>

        <div class="chatRoom" id="chatRoomArea">
            <div class="roomHeader">
                <div>
                    <div class="roomTitle" id="activeRoomTitle">Session #...</div>
                </div>
                <button id="btnCloseSession" class="btnSoft" onclick="forceCloseSession()">
                    Tutup Sesi Ini
                </button>
            </div>

            <div class="roomMessages" id="roomMessagesAdmin">
            </div>

            <div class="roomFooter" id="roomFooterAdmin">

                <input type="text" id="adminChatInput" placeholder="Ketik pesan balasan..."
                    onkeypress="handleAdminEnter(event)">

                <button class="btnSendAdmin" onclick="sendAdminMessage()">
                    <img id="btnBot" src="{{ asset('assets/icon/btnBot.svg') }}" alt="Send">
                </button>

            </div>
            <div class="closedNotice" id="closedNoticeAdmin" style="display: none;">
                Sesi obrolan ini sudah ditutup. Tidak dapat membalas pesan.
            </div>
        </div>

        <div class="chatRoom" id="chatEmptyState" style="justify-content: center; align-items: center;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5"
                style="margin-bottom: 16px;">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <p class="bodyLg charcoalGrey" style="opacity: 0.5;">Pilih obrolan di samping untuk mulai membalas.</p>
        </div>
    </div>

    <script src="{{ asset('js/admin/chat.js') }}"></script>
@endsection
