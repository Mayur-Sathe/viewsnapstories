(function() {
    // Theme toggle
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        const saved = localStorage.getItem('theme');
        if (saved === 'dark') document.documentElement.classList.add('dark-theme');
        themeToggle.addEventListener('click', function() {
            const isDark = document.documentElement.classList.toggle('dark-theme');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            document.cookie = 'theme=' + (isDark ? 'dark' : 'light') + ';path=/;max-age=31536000';
        });
    }

    // App download banner
    const banner = document.getElementById('app-download-banner');
    const closeBtn = document.getElementById('banner-close-btn');
    if (banner && closeBtn) {
        if (!localStorage.getItem('banner_closed')) {
            const isAndroid = /android/i.test(navigator.userAgent);
            const isIOS = /iphone|ipad|ipod/i.test(navigator.userAgent);
            if (isAndroid || isIOS) {
                banner.style.display = 'block';
                if (isAndroid) {
                    document.getElementById('android-download-link').style.display = 'inline';
                }
                if (isIOS) {
                    document.getElementById('ios-pwa-text').style.display = 'inline';
                }
            }
        }
        closeBtn.addEventListener('click', function() {
            banner.style.display = 'none';
            localStorage.setItem('banner_closed', 'true');
        });
    }

    // Paste functionality
    window.pasteUsername = function() {
        navigator.clipboard.readText().then(function(text) {
            const input = document.getElementById('usernameInput');
            if (input) input.value = text;
        }).catch(function() {
            // Fallback
            const input = document.getElementById('usernameInput');
            if (input) { input.focus(); input.select(); }
        });
    };

    // Toast notification system
    window.showToast = function(message, type) {
        const el = document.getElementById('toastNotification');
        if (!el) return;
        const body = el.querySelector('.toast-body');
        const header = el.querySelector('.toast-header strong');
        body.textContent = message || 'Error';
        el.classList.remove('bg-danger', 'text-white');
        if (type === 'error') {
            el.classList.add('bg-danger', 'text-white');
            header.textContent = 'Error';
        } else {
            header.textContent = 'Success';
        }
        const toast = new bootstrap.Toast(el, { autohide: true, delay: 5000 });
        toast.show();
        el.addEventListener('hidden.bs.toast', function() {
            el.classList.remove('bg-danger', 'text-white');
        }, { once: true });
    };

    // Normalize username from any input (URL, @username, raw)
    window.goProfile = function(val) {
        var v = (val || '').trim();
        if (!v) return;
        var m = v.match(/snapchat\.com\/(?:add\/|@|stories\/|t\/)?([a-z0-9._-]+)/i);
        if (m) v = m[1];
        v = v.replace(/^@/, '');
        if (/^[a-z0-9._-]{2,30}$/i.test(v)) {
            if (window.snapchatPersistRecentSearch) window.snapchatPersistRecentSearch(v);
            window.location.href = '/profile/' + v;
        }
    };

    // Recent searches persistence
    window.snapchatPersistRecentSearch = function(raw) {
        if (!raw || typeof raw !== 'string') return;
        var u = raw.trim();
        if (!u) return;
        try {
            var list = JSON.parse(localStorage.getItem('snapchat_recent_searches') || '[]');
            if (!Array.isArray(list)) list = [];
            list = [u].concat(list.filter(function(x) { return x !== u; })).slice(0, 8);
            localStorage.setItem('snapchat_recent_searches', JSON.stringify(list));
        } catch(e) {}
        window.dispatchEvent(new CustomEvent('snapchat-recent-search-updated'));
    };
})();
