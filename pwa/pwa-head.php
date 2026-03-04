    <!-- PWA Manifest -->
    <link rel="manifest" href="pwa/manifest.json?v=<?php echo $version; ?>">
    <meta name="theme-color" content="#667eea">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Bible Devotions">

    <style>
    /* PWA Install Banner Styles */
        #pwaInstallBanner {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            transform: translateY(-100%);
            transition: transform 0.3s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        #pwaInstallBanner.show {
            transform: translateY(0);
        }
        
        .pwa-banner-content {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
            min-width: 200px;
        }
        
        .pwa-banner-icon {
            font-size: 2rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px;
            border-radius: 10px;
        }
        
        .pwa-banner-text {
            flex: 1;
        }
        
        .pwa-banner-text h6 {
            margin: 0 0 5px 0;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .pwa-banner-text p {
            margin: 0;
            font-size: 0.85rem;
            opacity: 0.9;
        }
        
        .pwa-banner-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .pwa-install-btn {
            background: rgba(255, 255, 255, 0.95);
            color: #667eea;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .pwa-install-btn:hover {
            background: white;
            transform: scale(1.05);
        }
        
        .pwa-banner-link {
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 8px 15px;
            opacity: 0.9;
            transition: opacity 0.3s ease;
            white-space: nowrap;
        }
        
        .pwa-banner-link:hover {
            opacity: 1;
            text-decoration: underline;
        }
        
        .pwa-close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px 10px;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }
        
        .pwa-close-btn:hover {
            opacity: 1;
        }
        
        @media (max-width: 768px) {
            #pwaInstallBanner {
                flex-direction: column;
                align-items: stretch;
                padding: 12px 15px;
            }
            
            .pwa-banner-content {
                margin-bottom: 10px;
            }
            
            .pwa-banner-actions {
                justify-content: space-between;
                width: 100%;
            }
            
            .pwa-install-btn {
                flex: 1;
            }
        }
        </style>