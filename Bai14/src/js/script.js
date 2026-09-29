/**
 * Bài 14: Web Bán Laptop (Admin Panel) - Client-side JavaScript Handler
 */

(function() {
    var currentTheme = localStorage.getItem('pixel_admin_theme') || 'dark';
    document.documentElement.setAttribute('data-theme', currentTheme);
})();

function updateThemeUI() {
    var theme = document.documentElement.getAttribute('data-theme') || 'dark';
    var btn = document.getElementById('themeToggleBtn');
    if (btn) {
        var icon = btn.querySelector('.theme-icon');
        var text = btn.querySelector('.theme-text');
        if (theme === 'light') {
            if (icon) icon.textContent = '☀️';
            if (text) text.textContent = 'SÁNG';
        } else {
            if (icon) icon.textContent = '🌙';
            if (text) text.textContent = 'TỐI';
        }
    }
}

function togglePixelAdminTheme() {
    var current = document.documentElement.getAttribute('data-theme') || 'dark';
    var next = (current === 'dark') ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('pixel_admin_theme', next);
    updateThemeUI();
}

document.addEventListener('DOMContentLoaded', updateThemeUI);
