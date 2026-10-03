<?php
/**
 * Healing Bible Verses – Mobile-friendly audio player
 *
 * Default language: தமிழ் (Tamil)
 * Languages are auto-discovered from the /languages folder.
 */

include 'counter.php';

$version = "2026.02";

$langDir = __DIR__ . '/languages';

// Collect available languages (folder names)
$languages = [];
foreach (new DirectoryIterator($langDir) as $item) {
    if ($item->isDot() || !$item->isDir()) continue;
    $languages[] = $item->getFilename();
}
sort($languages);

// Selected language (default: தமிழ்)
$selected = isset($_GET['lang']) ? $_GET['lang'] : 'தமிழ்';

// Validate: the language folder must exist
$selectedPath = realpath($langDir . '/' . $selected);
if (!$selectedPath || strpos($selectedPath, realpath($langDir)) !== 0 || !is_dir($selectedPath)) {
    $selected = 'தமிழ்';
    $selectedPath = realpath($langDir . '/' . $selected);
}

// Gather mp3 tracks sorted by filename
$tracks = [];
foreach (new DirectoryIterator($selectedPath) as $file) {
    if ($file->isDot() || $file->isDir()) continue;
    $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
    if ($ext === 'mp3') {
        $tracks[] = $file->getFilename();
    }
}
sort($tracks);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Healing Bible Verses</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=<?php echo $version; ?>">

    <!-- PWA functionality -->
    <?php include 'pwa/pwa-head.php'; ?>
    
    <!-- Google Analytics -->
    <?php include 'gtag.php'; ?>
</head>
<body>

<!-- Header -->
<div class="header" id="siteHeader">
    <h1><span class="cross-icon">✝</span> Healing Bible Verses</h1>
    <div class="subtitle">Listen · Meditate · Be Healed</div>
</div>

<!-- Language selector -->
<div class="lang-bar" id="langBar">
    <?php foreach ($languages as $lang): ?>
        <a href="?lang=<?= urlencode($lang) ?>"
           class="lang-btn <?= ($lang === $selected) ? 'active' : '' ?>"
           data-lang="<?= htmlspecialchars($lang, ENT_QUOTES) ?>">
            <?= htmlspecialchars($lang, ENT_QUOTES) ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Loading overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-box">
        <div class="spinner"></div>
        <p>Loading track…</p>
        <div class="sub">Please wait, the file is being prepared.</div>
    </div>
</div>

<!-- Playlist -->
<div class="playlist" id="playlist">
    <?php if (empty($tracks)): ?>
        <div class="empty-state">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 19V6l12-3v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="15" r="3"/></svg>
            <p>No tracks found for this language.</p>
        </div>
    <?php else: ?>
        <div class="track-count"><?= count($tracks) ?> track<?= count($tracks) > 1 ? 's' : '' ?> · <?= htmlspecialchars($selected, ENT_QUOTES) ?></div>
        <?php foreach ($tracks as $i => $track):
            // Build a friendly display name from the filename
            $display = pathinfo($track, PATHINFO_FILENAME);
            // Remove common prefixes like "Healing-Bible-Tablet-Tamil-"
            $display = preg_replace('/^Healing[- ]Bible[- ](?:Tablet|Verse)[- ]?(?:Tamil|English|Hindi|Kannada|Malayalam|Marathi|Telugu|Badaga)?[- ]?/i', '', $display);
            $display = str_replace(['-', '_'], ' ', $display);
            $display = trim($display);
            if (empty($display)) $display = pathinfo($track, PATHINFO_FILENAME);
        ?>
        <div class="track-item"
             data-index="<?= $i ?>"
             data-src="stream.php?lang=<?= urlencode($selected) ?>&file=<?= urlencode($track) ?>"
             data-filename="<?= htmlspecialchars($track, ENT_QUOTES) ?>">
            <div class="track-num"><?= $i + 1 ?></div>
            <div class="track-info">
                <div class="track-name"><?= htmlspecialchars($display, ENT_QUOTES) ?></div>
                <div class="track-loading-text">⏳ Loading…</div>
            </div>
            <a class="track-download"
               href="download.php?lang=<?= urlencode($selected) ?>&file=<?= urlencode($track) ?>"
               title="Download"
               onclick="event.stopPropagation();">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            </a>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <div class="copyright">
            No Copyright, Freely Copy and Distribute (as per Matthew 10:8)
        </div>
        <div>
                <a href="https://www.wordofgodteam.com/" target="_blank" rel="noopener">
                     About Us
                </a>
                <span class="footer-separator">|</span>
                <a href="https://wordofgod.in/good-news-collections/" target="_blank">
                     Good News Collections
                </a>
                <span class="footer-separator">|</span>
                <a href="https://wordofgod.in/bibledictionary/" target="_blank">
                     Bible Dictionaries
                </a>
                <span class="footer-separator">|</span>
                <a href="https://wordofgod.in/bible-concordance/" target="_blank">
                     Bible Concordances
                </a>
                <span class="footer-separator">|</span>
                <a href="https://wordofgod.in/bible-wallpapers/" target="_blank">
                     Bible Wallpapers
                </a>
                <span class="footer-separator">|</span>
                <a href="https://wordofgod.in/bible-app-modules/" target="_blank">
                     Bible App Modules
                </a>
                <span class="footer-separator">|</span>
                <a href="https://wordofgod.in/" target="_blank">
                     Free Christian Resources
                </a>
        </div>
        <div class="footer-section footer-visitors">
            <p class="mb-0">
                <i class="bi bi-emoji-heart-eyes me-1"></i> Visitors: <?= $visitors2 ?>
            </p>
        </div>
        <div style="position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden; opacity: 0; pointer-events: none;" aria-hidden="true">
            <a href="bot.php" tabindex="-1">.</a>
        </div>

        <div class="copyright">
            Note: The albums from the languages Badaga, Hindi, Kannada, Malayalam, Marathi and Telugu are published by www.HealingBibleVerse.com. We are including them here at one place and streaming free of cost.
        </div>
    </div>
</footer>

<!-- Now Playing bar -->
<div class="now-playing" id="nowPlaying">
    <div class="np-track-name" id="npName">—</div>
    <div class="progress-bar" id="progressBar">
        <div class="progress-track">
            <div class="progress-fill" id="progressFill"></div>
        </div>
    </div>
    <div class="np-times">
        <span id="npCurrent">0:00</span>
        <span id="npDuration">0:00</span>
    </div>
    <div class="np-controls">
        <button class="np-btn" id="btnPrev" title="Previous">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M6 6h2v12H6zm3.5 6 8.5 6V6z"/></svg>
        </button>
        <button class="np-btn play-pause" id="btnPlayPause" title="Play / Pause">
            <svg id="iconPlay" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            <svg id="iconPause" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" style="display:none"><path d="M6 19h4V5H6zm8-14v14h4V5z"/></svg>
        </button>
        <button class="np-btn" id="btnNext" title="Next">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M6 18l8.5-6L6 6v12zm10-12v12h2V6z"/></svg>
        </button>
    </div>
</div>

<!-- Audio element -->
<audio id="audio" preload="none"></audio>


    <?php include 'pwa/pwa-body.php'; ?>
    <script src="script.js?v=<?php echo $version; ?>"></script>

</body>
</html>
