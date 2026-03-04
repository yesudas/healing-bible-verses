(function () {
    const audio        = document.getElementById('audio');
    const nowPlaying   = document.getElementById('nowPlaying');
    const npName       = document.getElementById('npName');
    const progressBar  = document.getElementById('progressBar');
    const progressFill = document.getElementById('progressFill');
    const npCurrent    = document.getElementById('npCurrent');
    const npDuration   = document.getElementById('npDuration');
    const btnPlayPause = document.getElementById('btnPlayPause');
    const iconPlay     = document.getElementById('iconPlay');
    const iconPause    = document.getElementById('iconPause');
    const btnPrev      = document.getElementById('btnPrev');
    const btnNext      = document.getElementById('btnNext');
    const overlay      = document.getElementById('loadingOverlay');

    const trackItems = Array.from(document.querySelectorAll('.track-item[data-src]'));
    let currentIndex = -1;
    let isLoading    = false;

    // ── Sticky language bar position ──
    const header  = document.getElementById('siteHeader');
    const langBar = document.getElementById('langBar');
    function setLangBarTop() {
        langBar.style.top = header.offsetHeight + 'px';
    }
    setLangBarTop();
    window.addEventListener('resize', setLangBarTop);

    // ── Scroll active language pill into view ──
    const activePill = langBar.querySelector('.lang-btn.active');
    if (activePill) {
        setTimeout(() => {
            activePill.scrollIntoView({ inline: 'center', block: 'nearest', behavior: 'smooth' });
        }, 100);
    }

    // ── Format seconds to m:ss ──
    function fmt(s) {
        if (!s || !isFinite(s)) return '0:00';
        const m = Math.floor(s / 60);
        const sec = Math.floor(s % 60);
        return m + ':' + (sec < 10 ? '0' : '') + sec;
    }

    // ── Show / hide loading ──
    function showLoading(idx) {
        isLoading = true;
        overlay.classList.add('show');
        trackItems.forEach(t => t.classList.remove('loading'));
        if (idx >= 0 && idx < trackItems.length) {
            trackItems[idx].classList.add('loading');
        }
    }
    function hideLoading() {
        isLoading = false;
        overlay.classList.remove('show');
        trackItems.forEach(t => t.classList.remove('loading'));
    }

    // ── Play a track by index ──
    function playTrack(idx) {
        if (idx < 0 || idx >= trackItems.length) return;
        currentIndex = idx;

        // Highlight active
        trackItems.forEach(t => t.classList.remove('active'));
        trackItems[idx].classList.add('active');

        // Scroll into view
        trackItems[idx].scrollIntoView({ block: 'nearest', behavior: 'smooth' });

        // Set source & play
        const src      = trackItems[idx].dataset.src;
        const filename = trackItems[idx].dataset.filename;
        const display  = trackItems[idx].querySelector('.track-name').textContent;

        audio.src = src;
        npName.textContent = display;
        nowPlaying.classList.add('visible');
        document.body.classList.add('player-active');
        progressFill.style.width = '0%';
        npCurrent.textContent = '0:00';
        npDuration.textContent = '0:00';

        showLoading(idx);
        audio.load();
        audio.play().catch(() => {});

        updatePlayPauseIcon();
    }

    // ── Play / Pause icon ──
    function updatePlayPauseIcon() {
        if (audio.paused) {
            iconPlay.style.display  = '';
            iconPause.style.display = 'none';
        } else {
            iconPlay.style.display  = 'none';
            iconPause.style.display = '';
        }
    }

    // ── Track click ──
    trackItems.forEach((item, i) => {
        item.addEventListener('click', () => playTrack(i));
    });

    // ── Controls ──
    btnPlayPause.addEventListener('click', () => {
        if (!audio.src) { playTrack(0); return; }
        if (audio.paused) audio.play().catch(() => {});
        else audio.pause();
        updatePlayPauseIcon();
    });

    btnPrev.addEventListener('click', () => {
        if (currentIndex > 0) playTrack(currentIndex - 1);
    });

    btnNext.addEventListener('click', () => {
        if (currentIndex < trackItems.length - 1) playTrack(currentIndex + 1);
    });

    // ── Audio events ──
    audio.addEventListener('canplay', () => {
        hideLoading();
    });

    audio.addEventListener('playing', () => {
        hideLoading();
        updatePlayPauseIcon();
    });

    audio.addEventListener('waiting', () => {
        showLoading(currentIndex);
    });

    audio.addEventListener('pause', updatePlayPauseIcon);

    audio.addEventListener('timeupdate', () => {
        if (!audio.duration) return;
        const pct = (audio.currentTime / audio.duration) * 100;
        progressFill.style.width = pct + '%';
        npCurrent.textContent = fmt(audio.currentTime);
        npDuration.textContent = fmt(audio.duration);
    });

    audio.addEventListener('ended', () => {
        // Auto-play next
        if (currentIndex < trackItems.length - 1) {
            playTrack(currentIndex + 1);
        } else {
            updatePlayPauseIcon();
        }
    });

    audio.addEventListener('error', () => {
        hideLoading();
        alert('Unable to load this track. Please try again.');
    });

    // ── Progress bar seek (click + touch) ──
    function seekFromEvent(e) {
        if (!audio.duration) return;
        const rect = progressBar.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        let pct = (clientX - rect.left) / rect.width;
        pct = Math.max(0, Math.min(1, pct));
        audio.currentTime = pct * audio.duration;
        progressFill.style.width = (pct * 100) + '%';
    }

    progressBar.addEventListener('click', seekFromEvent);

    let isSeeking = false;
    progressBar.addEventListener('touchstart', (e) => {
        isSeeking = true;
        seekFromEvent(e);
    }, { passive: true });
    progressBar.addEventListener('touchmove', (e) => {
        if (isSeeking) seekFromEvent(e);
    }, { passive: true });
    progressBar.addEventListener('touchend', () => { isSeeking = false; });

})();
