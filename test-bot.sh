#!/bin/bash

# Bot Honeypot Test Script
# Tests the bot.php honeypot with various user agents

URL="https://yourdomain.com/healing-bible-verses/bot.php"

echo "Testing Bot Honeypot at: $URL"
echo "=========================================="
echo ""

echo "Test 1: Googlebot"
curl -s -A "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)" "$URL" > /dev/null
echo "✓ Googlebot request sent"
echo ""

echo "Test 2: Bingbot"
curl -s -A "Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)" "$URL" > /dev/null
echo "✓ Bingbot request sent"
echo ""

echo "Test 3: Bad Bot"
curl -s -A "BadBot/1.0" "$URL" > /dev/null
echo "✓ BadBot request sent"
echo ""

echo "Test 4: Python Scraper"
curl -s -A "Python-urllib/3.10" "$URL" > /dev/null
echo "✓ Python scraper request sent"
echo ""

echo "Test 5: SEMrush Bot"
curl -s -A "Mozilla/5.0 (compatible; SemrushBot/7~bl; +http://www.semrush.com/bot.html)" "$URL" > /dev/null
echo "✓ SEMrush bot request sent"
echo ""

echo "=========================================="
echo "Tests complete! Check your bot.log file on the server."
echo ""
echo "To view the log, run:"
echo "  cat /path/to/special-mp3s/bot.log"
echo ""
echo "Or download it from your server:"
echo "  scp your-server:/path/to/special-mp3s/bot.log ."
