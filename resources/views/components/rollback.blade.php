<button class="rollbackToggle" id="rollbackToggle" type="button" aria-label="Kembali ke atas"
    onclick="window.scrollTo({ top: 0, behavior: 'smooth' });">
    <img src="{{ asset('assets/icon/Rollback.svg') }}" alt="Scroll to top">
</button>

<script src="{{ asset('js/components/rollback.js') }}"></script>