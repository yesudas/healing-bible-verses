<?php
/**
 * Sitemap Generator for Healing Bible Verses
 * 
 * Generates an XML sitemap with:
 * - Homepage
 * - All language album pages
 * - All track download links
 */

// Base URL - change this to your production domain
$baseUrl = 'https://wordofgod.in/healing-bible-verses';

// You can also auto-detect from server
//if (isset($_SERVER['HTTP_HOST'])) {
  //  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    //$baseUrl = $protocol . '://' . $_SERVER['HTTP_HOST'];
//}

$langDir = __DIR__ . '/languages';

// Collect available languages
$languages = [];
foreach (new DirectoryIterator($langDir) as $item) {
    if ($item->isDot() || !$item->isDir()) continue;
    $languages[] = $item->getFilename();
}
sort($languages);

// Start XML
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Homepage
$xml .= "  <url>\n";
$xml .= "    <loc>" . htmlspecialchars($baseUrl . '/', ENT_XML1) . "</loc>\n";
$xml .= "    <changefreq>yearly</changefreq>\n";
$xml .= "    <priority>1.0</priority>\n";
$xml .= "  </url>\n";

// Language pages and tracks
foreach ($languages as $lang) {
    $langPath = realpath($langDir . '/' . $lang);
    if (!$langPath || !is_dir($langPath)) continue;
    
    // Language album page
    $xml .= "  <url>\n";
    $xml .= "    <loc>" . htmlspecialchars($baseUrl . '/?lang=' . urlencode($lang), ENT_XML1) . "</loc>\n";
    $xml .= "    <changefreq>yearly</changefreq>\n";
    $xml .= "    <priority>0.8</priority>\n";
    $xml .= "  </url>\n";
    
    // Get all tracks for this language
    $tracks = [];
    foreach (new DirectoryIterator($langPath) as $file) {
        if ($file->isDot() || $file->isDir()) continue;
        $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
        if ($ext === 'mp3') {
            $tracks[] = $file->getFilename();
        }
    }
    sort($tracks);
    
    // Add download link for each track
    foreach ($tracks as $track) {
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars($baseUrl . '/download.php?lang=' . urlencode($lang) . '&file=' . urlencode($track), ENT_XML1) . "</loc>\n";
        $xml .= "    <changefreq>yearly</changefreq>\n";
        $xml .= "    <priority>0.6</priority>\n";
        $xml .= "  </url>\n";
    }
}

$xml .= "</urlset>\n";

// Write to sitemap.xml
file_put_contents(__DIR__ . '/sitemap.xml', $xml);

// Output success message
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitemap Generated - Healing Bible Verses</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f6ff;
            color: #2d2649;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info {
            background: #fff;
            border: 1px solid #e6dff7;
            padding: 20px;
            border-radius: 8px;
        }
        h1 { color: #7c6fd6; margin-top: 0; }
        h2 { color: #6456c4; font-size: 1.2rem; }
        code {
            background: #f3efff;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        a {
            color: #7c6fd6;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        ul {
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <div class="success">
        <h1>✓ Sitemap Generated Successfully!</h1>
        <p><strong>File:</strong> <code>sitemap.xml</code></p>
        <p><strong>Location:</strong> <a href="sitemap.xml" target="_blank">View Sitemap</a></p>
    </div>
    
    <div class="info">
        <h2>Sitemap Statistics</h2>
        <ul>
            <li><strong>Languages:</strong> <?= count($languages) ?></li>
            <li><strong>Total URLs:</strong> <?= substr_count($xml, '<url>') ?></li>
            <li><strong>Base URL:</strong> <code><?= htmlspecialchars($baseUrl) ?></code></li>
        </ul>
        
        <h2>Included URLs</h2>
        <ul>
            <li>Homepage: <code><?= htmlspecialchars($baseUrl . '/') ?></code></li>
            <li><?= count($languages) ?> language album pages</li>
            <li>All MP3 download links from <?= count($languages) ?> languages</li>
        </ul>
        
        <h2>Next Steps</h2>
        <ol>
            <li><strong>Update Base URL:</strong> Edit <code>sitemap.php</code> line 11 to set your production domain</li>
            <li><strong>Submit to Search Engines:</strong>
                <ul>
                    <li><a href="https://search.google.com/search-console" target="_blank">Google Search Console</a></li>
                    <li><a href="https://www.bing.com/webmasters" target="_blank">Bing Webmaster Tools</a></li>
                </ul>
            </li>
            <li><strong>Add to robots.txt:</strong> Add this line:
                <br><code>Sitemap: <?= htmlspecialchars($baseUrl) ?>/sitemap.xml</code>
            </li>
        </ol>
        
        <p style="margin-top: 30px;">
            <a href="index.php">← Back to Home</a> | 
            <a href="sitemap.xml" target="_blank">View sitemap.xml</a>
        </p>
    </div>
</body>
</html>
