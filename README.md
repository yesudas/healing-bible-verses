# Healing Bible Verses

A mobile-friendly audio player web application for listening to and downloading healing Bible verse recordings in multiple languages.

## 🌟 Demo URL

https://wordofgod.in/healing-bible-verses/

## 🌟 Features

- **🎵 Multi-language Support**: 8 languages including தமிழ் (Tamil), English, Hindi, Kannada, Malayalam, Marathi, Telugu, and Badaga
- **📱 Mobile-First Design**: Fully responsive with touch-optimized controls
- **🎧 Advanced Audio Player**: 
  - Play/pause, previous/next track controls
  - Progress bar with seek functionality (click or drag)
  - Auto-play next track
  - Loading indicators for buffering
- **⬇️ Secure Downloads**: Download individual tracks with security protections
- **🎨 Peaceful Theme**: Spiritual purple/lavender color scheme with gradients
- **⚡ HTTP Range Support**: Proper audio streaming with seek capability
- **🔍 SEO Ready**: Auto-generated XML sitemap for search engines
- **📲 Progressive Web App (PWA)**: Install on mobile/desktop for offline access
- **🛡️ Bot Honeypot**: Security monitoring to detect and log bot activity

## 📂 Project Structure

```
healing-bible-verses/
├── index.php          # Main audio player page
├── style.css          # All styling
├── script.js          # Player functionality
├── download.php       # Secure file download handler
├── stream.php         # Audio streaming with Range support
├── sitemap.php        # Sitemap generator
├── sitemap.xml        # Auto-generated sitemap (created by sitemap.php)
├── bot.php            # Bot honeypot trap for security monitoring
├── bot.log            # Bot activity log (created automatically)
├── test-bot.sh        # Shell script to test bot honeypot
├── pwa/               # Progressive Web App files
│   ├── manifest.json  # PWA manifest
│   ├── sw.js          # Service worker
│   ├── pwa.js         # PWA registration script
│   ├── pwa-head.php   # PWA meta tags
│   ├── pwa-body.php   # PWA initialization
│   ├── icon-192.png   # App icon (192x192)
│   └── icon-512.png   # App icon (512x512)
├── languages/         # Audio files organized by language
│   ├── தமிழ்/
│   ├── English/
│   ├── Hindi/
│   ├── Kannada/
│   ├── Malayalam/
│   ├── Marathi/
│   ├── Telugu/
│   └── Badaga/
└── README.md
```

## 🚀 Quick Start

### Requirements

- PHP 7.4 or higher
- Web server (Apache, Nginx, or PHP built-in server)

### Installation

1. **Clone or download** this repository to your web server directory

2. **Start the PHP development server** (for testing):
   ```bash
   cd healing-bible-verses
   php -S localhost:8000
   ```

3. **Open your browser** and navigate to:
   ```
   http://localhost:8000
   ```

### Production Deployment

1. Upload all files to your web server
2. Ensure the `languages/` folder and all subdirectories are readable
3. Update the base URL in `sitemap.php` (line 11):
   ```php
   $baseUrl = 'https://yourdomain.com/healing-bible-verses';
   ```
4. Generate the sitemap by visiting:
   ```
   https://yourdomain.com/healing-bible-verses/sitemap.php
   ```

## 🎯 Usage

### For Visitors

1. **Select a language** from the horizontal scrollable language bar
2. **Click any track** to start playing
3. **Use player controls**:
   - Play/Pause button
   - Previous/Next buttons
   - Click or drag the progress bar to seek
4. **Download tracks** using the download icon on each track

### For Administrators

#### Adding New Languages

1. Create a new folder under `languages/` with the language name
2. Add `.mp3` files to the folder
3. The language will automatically appear in the language selector

#### Adding New Tracks

1. Upload `.mp3` files to the appropriate language folder
2. Files are automatically sorted alphabetically by filename
3. Regenerate the sitemap by visiting `sitemap.php`

#### Updating the Sitemap

Visit `https://yourdomain.com/sitemap.php` to regenerate `sitemap.xml` with all current languages and tracks.

## 🔒 Security Features

### Download Protection (`download.php`)

- ✅ Only serves files from `/languages/` directory
- ✅ Blocks directory traversal attempts (`../` patterns)
- ✅ Only allows `.mp3` file extensions
- ✅ Uses `realpath()` validation to prevent symlink attacks
- ✅ Suppresses error output to prevent path disclosure
- ✅ Streams files in chunks to prevent memory issues

### Bot Honeypot (`bot.php`)

A security feature that detects and logs bot activity:

- ✅ Hidden honeypot link (invisible to users, accessible to bots)
- ✅ Logs bot IP address, user agent, referer, and timestamp
- ✅ Creates unique ID to prevent duplicate logging
- ✅ Minimal response to avoid alerting sophisticated bots
- ✅ `noindex, nofollow` meta tags to prevent search engine indexing
- ✅ Automatic logging to `bot.log` file

#### Testing the Honeypot

Use the provided test script to verify the honeypot is working:

```bash
# Edit test-bot.sh to update the URL (line 6)
nano test-bot.sh

# Make it executable
chmod +x test-bot.sh

# Run the test
./test-bot.sh
```

The script will send requests from 5 different bot user agents:
1. Googlebot
2. Bingbot
3. Generic BadBot
4. Python Scraper
5. SEMrush Bot

Check `bot.log` after running to see logged bot activity:

```bash
cat bot.log
```

**Log Format:**
```
[2026-03-04 12:34:56] ID: abc123def456 | IP: 123.45.67.89 | User-Agent: Googlebot/2.1 | Referer: direct
```

### Streaming Protection (`stream.php`)

- Same security measures as `download.php`
- Supports HTTP Range requests for seeking
- Returns `206 Partial Content` for range requests
- Validates byte ranges to prevent invalid requests

## 🎨 Customization

### Changing Colors

Edit the CSS variables in `style.css`:

```css
:root {
    --primary: #7c6fd6;        /* Main purple */
    --primary-dark: #6456c4;   /* Darker purple */
    --bg: #f8f6ff;             /* Background */
    --accent: #d4af37;         /* Gold accent */
    /* ... more variables */
}
```

### Changing Default Language

Edit `index.php` line 22:

```php
$selected = isset($_GET['lang']) ? $_GET['lang'] : 'தமிழ்';
```

Replace `'தமிழ்'` with any other language folder name.

## 📊 SEO & Analytics

### Sitemap

The sitemap includes:
- Homepage (priority: 1.0)
- All language album pages (priority: 0.8)
- All track download URLs (priority: 0.6)
- Change frequency: yearly

Submit `sitemap.xml` to:
- [Google Search Console](https://search.google.com/search-console)
- [Bing Webmaster Tools](https://www.bing.com/webmasters)

### robots.txt (Recommended)

Create a `robots.txt` file in the root:

```
User-agent: *
Allow: /

Sitemap: https://yourdomain.com/healing-bible-verses/sitemap.xml
```

## 🌐 Browser Compatibility

- ✅ Chrome/Edge (latest)
- ✅ Firefox (latest)
- ✅ Safari (iOS & macOS)
- ✅ Mobile browsers (Android Chrome, iOS Safari)

## 📱 Mobile Features

- Touch-optimized controls with larger tap targets
- Swipe-enabled language selector
- Safe area support for iPhone notches
- Loading indicators for slow connections
- Responsive design (adapts to all screen sizes)

## � Progressive Web App (PWA)

This application supports Progressive Web App features, allowing users to install it on their devices for a native app-like experience.

### PWA Features

- **Installable**: Add to home screen on mobile and desktop
- **Offline Support**: Service worker caches assets for offline access
- **App Icons**: Custom icons for different screen sizes (192x192, 512x512)
- **Standalone Mode**: Runs in full-screen without browser UI when installed
- **Fast Loading**: Cached resources load instantly

### Installation

#### On Mobile (Android/iOS):
1. Open the website in your browser
2. Tap the browser menu (⋮ or share icon)
3. Select "Add to Home Screen" or "Install App"
4. Follow the prompts to install

#### On Desktop (Chrome/Edge):
1. Look for the install icon (⊕) in the address bar
2. Click it and confirm installation
3. The app will open in a standalone window

### PWA Files

- **`pwa/manifest.json`**: App manifest with name, icons, colors, and display settings
- **`pwa/sw.js`**: Service worker for offline caching
- **`pwa/pwa.js`**: Service worker registration script
- **`pwa/pwa-head.php`**: Meta tags and manifest link (included in `<head>`)
- **`pwa/pwa-body.php`**: PWA initialization script (included before `</body>`)
- **`pwa/icon-192.png`**: App icon (192x192px)
- **`pwa/icon-512.png`**: App icon (512x512px)

### Customizing PWA

To customize the PWA appearance, edit `pwa/manifest.json`:

```json
{
  "name": "Healing Bible Verses",
  "short_name": "HBV",
  "theme_color": "#7c6fd6",
  "background_color": "#f8f6ff",
  ...
}
```

Update icons by replacing `icon-192.png` and `icon-512.png` with your custom images.

## 🛡️ Bot Detection & Security

### Bot Honeypot

The application includes a bot honeypot (`bot.php`) to monitor and log automated bot activity for security purposes.

#### How It Works

1. **Hidden Link**: A honeypot link is embedded in the page (hidden from users with CSS)
2. **Bot Access**: Bots crawling the site will find and access the hidden link
3. **Logging**: Each bot visit is logged with details (IP, user agent, referer, timestamp)
4. **Deduplication**: Unique ID prevents the same bot from being logged multiple times

#### Monitoring Bot Activity

View the bot log:
```bash
cat bot.log
```

Sample log entry:
```
[2026-03-04 12:34:56] ID: a1b2c3d4 | IP: 192.168.1.100 | User-Agent: Googlebot/2.1 | Referer: https://example.com
```

#### Testing the Honeypot

The `test-bot.sh` script simulates various bot visits:

```bash
# 1. Edit the URL in the script
nano test-bot.sh
# Change line 6: URL="https://yourdomain.com/healing-bible-verses/bot.php"

# 2. Make executable
chmod +x test-bot.sh

# 3. Run tests
./test-bot.sh

# 4. Check results
cat bot.log
```

The test simulates these bots:
- **Googlebot** - Google's web crawler
- **Bingbot** - Microsoft's search engine bot
- **BadBot** - Generic malicious bot
- **Python Scraper** - Common scraping tool
- **SEMrush Bot** - SEO analysis tool

#### Security Best Practices

1. **Regular Monitoring**: Check `bot.log` periodically for suspicious activity
2. **IP Blocking**: Block malicious IPs found in logs at server/firewall level
3. **Log Rotation**: Set up log rotation to prevent `bot.log` from growing too large
4. **Analytics**: Use logged data to understand bot traffic patterns

## 🐛 Troubleshooting

### Seeking doesn't work

Ensure you're using `stream.php` for audio sources (not direct file paths). The code already does this, but if you modify track sources, use:
```php
stream.php?lang=English&file=track.mp3
```

### Files not loading

1. Check file permissions (folders should be readable)
2. Verify `.mp3` files are in the correct language folders
3. Check PHP error logs for details

### Player appears below footer

This should be fixed in the current version. The player uses `position: fixed; z-index: 9999` to stay on top.

## 📄 License

Copyright © 2026 Healing Bible Verses. All rights reserved.

## 🔗 Links

- Website: [WordofGod.in](https://wordofgod.in)
- Issues: Report bugs or request features via your version control system

## 🙏 Credits

Created with ❤️ for spreading healing Bible verses across multiple languages.

---

**For support or questions, visit [WordofGod.in](https://wordofgod.in)**
