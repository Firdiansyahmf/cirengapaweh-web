@props(['id', 'type', 'title', 'message', 'confirmAction', 'btnColor' => 'btnPrimary', 'btnText' => 'Konfirmasi'])

<div id="{{ $id }}" class="modalOverlay">
    <div class="modalDialog small">
        <div class="modalHeader">
            <h3>{{ $title }}</h3>
            <button class="modalClose" onclick="closeConfirmModal('{{ $type }}')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="modalBody">
            <p>{{ $message }}</p>
        </div>
        <div class="modalFooter">
            <button class="btnCancel" onclick="closeConfirmModal('{{ $type }}')">Batal</button>
            <button class="{{ $btnColor }}" onclick="{{ $confirmAction }}">{{ $btnText }}</button>
        </div>
    </div>
</div>