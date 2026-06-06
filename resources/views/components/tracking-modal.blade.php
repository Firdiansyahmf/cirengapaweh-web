<!-- Tracking Modal - Pattern matches modal-confirmation -->
<div id="trackingModal" class="trackingModalOverlay">
    <div class="trackingModalDialog">
        <div class="trackingModalHeader">
            <h3>Lacak Paket</h3>
            <button class="trackingModalClose" onclick="closeTrackingModal()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div id="trackingContent" class="trackingModalContent">
            <!-- Tracking info populated by JS -->
        </div>
        <div class="trackingModalFooter">
            <button class="btnRefresh" onclick="refreshTrackingStatus(); return false;" title="Perbarui status pengiriman">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 6px; font-size: 18px;">refresh</span>
                Refresh Status
            </button>
            <button class="btnClose" onclick="closeTrackingModal()">Tutup</button>
        </div>
    </div>
</div>

