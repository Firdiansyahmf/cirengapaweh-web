@extends('layouts.admin')

@section('title', 'Manajemen Chat - Cireng A\'paweh')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/chat.css') }}" />

    <div class="chatContainer">
        <!-- Chat Sessions List -->
        <aside class="sessionList">
            <div class="sessionHeader">
                <h2>Session Chat</h2>
                <div class="sessionSearch">
                    <span class="material-symbols-outlined">search</span>
                    <input type="text" id="searchSession" placeholder="Cari session...">
                </div>
            </div>

            <div class="sessionTabs">
                <button class="tabBtn active" data-status="all">
                    Semua
                </button>
                <button class="tabBtn" data-status="open">
                    Dibuka
                </button>
                <button class="tabBtn" data-status="closed">
                    Ditutup
                </button>
            </div>

            <div class="sessionItems" id="sessionList">
                @forelse($sessions as $session)
                    <div class="sessionItem" data-session-id="{{ $session->id }}" data-status="{{ $session->status }}" onclick="selectSession({{ $session->id }})">
                        <div class="sessionInfo">
                            <div class="sessionTitle">
                                <span class="userName">User #{{ $session->id }}</span>
                                <span class="sessionTime">{{ $session->updated_at->format('H:i') }}</span>
                            </div>
                            <div class="sessionDetails">
                                <span class="sessionPhone">{{ $session->customer_phone ?? '-' }}</span>
                                <span class="sessionStatus">
                                    @if($session->status === 'open')
                                        <span class="statusBadge statusOpen">Dibuka</span>
                                    @else
                                        <span class="statusBadge statusClosed">Ditutup</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="sessionPreview">
                            {{ $session->messages->last()?->message ?? 'Tidak ada pesan' }}
                        </div>
                        <span class="unreadBadge">{{ $session->messages_count }}</span>
                    </div>
                @empty
                    <div class="emptyState">
                        <span class="material-symbols-outlined">mail</span>
                        <p>Tidak ada session chat</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($sessions->hasPages())
                <div class="sessionPagination">
                    @if (!$sessions->onFirstPage())
                        <a href="{{ $sessions->previousPageUrl() }}" class="paginationArrow">←</a>
                    @endif
                    <span class="paginationInfo">{{ $sessions->currentPage() }} / {{ $sessions->lastPage() }}</span>
                    @if ($sessions->hasMorePages())
                        <a href="{{ $sessions->nextPageUrl() }}" class="paginationArrow">→</a>
                    @endif
                </div>
            @endif
        </aside>

        <!-- Chat Detail Area -->
        <main class="chatArea">
            <div class="chatEmpty" id="chatEmpty">
                <div class="emptyContent">
                    <span class="material-symbols-outlined">chat_bubble_outline</span>
                    <h3>Pilih Session Chat</h3>
                    <p>Pilih session dari daftar di sebelah kiri untuk memulai chat</p>
                </div>
            </div>

            <div class="chatContent" id="chatContent" style="display: none;">
                <!-- Chat Header -->
                <div class="chatHeader">
                    <div class="chatHeaderInfo">
                        <div>
                            <h3 id="chatSessionName">Session #</h3>
                            <p id="chatSessionPhone">-</p>
                        </div>
                    </div>
                    <div class="chatHeaderActions">
                        <span id="sessionStatusBadge" class="statusBadge statusOpen">Dibuka</span>
                        <button class="btnIcon" id="btnToggleSession" onclick="toggleSessionStatus()">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="messagesArea" id="messagesArea">
                    <!-- Messages will be loaded here -->
                </div>

                <!-- Input Area -->
                <div class="inputArea">
                    <form id="messageForm" onsubmit="sendMessage(event)">
                        @csrf
                        <div class="inputGroup">
                            <textarea id="messageInput" placeholder="Ketik pesan..." rows="1"></textarea>
                            <button type="submit" class="btnSend" id="btnSend">
                                <span class="material-symbols-outlined">send</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Session Actions Menu -->
    <div class="actionMenu" id="actionMenu" style="display: none;">
        <button class="actionItem" onclick="toggleSessionStatus()">
            <span class="material-symbols-outlined" id="toggleIcon">lock_open</span>
            <span id="toggleText">Tutup Session</span>
        </button>
    </div>

    <script src="{{ asset('js/admin/chat.js') }}"></script>
@endsection
