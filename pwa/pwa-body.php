    <!-- PWA Install Banner -->
    <div id="pwaInstallBanner">
        <div class="pwa-banner-content">
            <div class="pwa-banner-icon">
                <i class="bi bi-download"></i>
            </div>
            <div class="pwa-banner-text">
                <h6>Install Healing Bible Verses App</h6>
                <p>Get quick access and easy listening</p>
            </div>
        </div>
        <div class="pwa-banner-actions">
            <button class="pwa-install-btn" id="pwaInstallBtn">
                <i class="bi bi-plus-circle me-1"></i> Install
            </button>
            <a href="#" class="pwa-banner-link" id="pwaDontShowBtn">Don't show again</a>
            <button class="pwa-close-btn" id="pwaCloseBtn" aria-label="Close">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>

    <script src="pwa/pwa.js?v=<?php echo $version; ?>"></script>