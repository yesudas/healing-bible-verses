// Register Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('pwa/sw.js')
                .then(() => console.log("Service Worker Registered"))
                .catch(err => console.log("Service Worker Failed:", err));
        }

        // PWA Installation Banner Logic
        let deferredPrompt;
        const pwaInstallBanner = document.getElementById('pwaInstallBanner');
        const pwaInstallBtn = document.getElementById('pwaInstallBtn');
        const pwaCloseBtn = document.getElementById('pwaCloseBtn');
        const pwaDontShowBtn = document.getElementById('pwaDontShowBtn');

        // Check if user has dismissed the banner permanently
        const dontShowAgain = localStorage.getItem('pwa-dont-show');
        
        // Listen for beforeinstallprompt event
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('beforeinstallprompt event fired');
            e.preventDefault();
            deferredPrompt = e;
            
            // Check if already installed or user chose "Don't show again"
            if (!dontShowAgain && !window.matchMedia('(display-mode: standalone)').matches) {
                // Show banner after 2 seconds
                setTimeout(() => {
                    pwaInstallBanner.classList.add('show');
                }, 2000);
            }
        });

        // Install button click handler
        if (pwaInstallBtn) {
            pwaInstallBtn.addEventListener('click', async () => {
                if (!deferredPrompt) {
                    console.log('No deferred prompt available');
                    return;
                }
                
                // Show the install prompt
                deferredPrompt.prompt();
                
                // Wait for the user's response
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`User response to install prompt: ${outcome}`);
                
                // Hide the banner regardless of outcome
                pwaInstallBanner.classList.remove('show');
                
                if (outcome === 'accepted') {
                    console.log('User accepted the install prompt');
                }
                
                // Clear the deferred prompt
                deferredPrompt = null;
            });
        }

        // Close button handler
        if (pwaCloseBtn) {
            pwaCloseBtn.addEventListener('click', () => {
                pwaInstallBanner.classList.remove('show');
            });
        }

        // "Don't show again" handler
        if (pwaDontShowBtn) {
            pwaDontShowBtn.addEventListener('click', (e) => {
                e.preventDefault();
                localStorage.setItem('pwa-dont-show', 'true');
                pwaInstallBanner.classList.remove('show');
                console.log('User chose not to see install banner again');
            });
        }

        // Check if app is already installed
        window.addEventListener('appinstalled', () => {
            console.log('PWA was installed');
            pwaInstallBanner.classList.remove('show');
        });

        // Hide banner if already running as installed app
        if (window.matchMedia('(display-mode: standalone)').matches) {
            console.log('App is running in standalone mode');
            pwaInstallBanner.style.display = 'none';
        }