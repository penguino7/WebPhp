/**
 * Bài 15: Shopping Cart - Client-side JavaScript Handler
 * Quản lý Dark/Light mode, điều khiển số lượng giỏ hàng & tương tác giao diện
 */

// 1. Khởi tạo theme ngay khi nạp để tránh nháy giao diện (FOUC)
(function() {
    var theme = localStorage.getItem('pixel_shop_theme') || 'dark';
    document.documentElement.setAttribute('data-theme', theme);
})();

/**
 * Cập nhật biểu tượng nút Theme
 */
function updateShopThemeUI() {
    var theme = document.documentElement.getAttribute('data-theme') || 'dark';
    var btn = document.getElementById('themeToggleBtn');
    if (btn) {
        var icon = btn.querySelector('.theme-icon');
        if (icon) {
            icon.textContent = (theme === 'light') ? '☀️' : '🌙';
        }
    }
}

/**
 * Chuyển đổi qua lại giữa Dark Mode và Light Mode
 */
function togglePixelShopTheme() {
    var current = document.documentElement.getAttribute('data-theme') || 'dark';
    var next = (current === 'dark') ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('pixel_shop_theme', next);
    updateShopThemeUI();
}

/**
 * Điều chỉnh số lượng tại trang Chi Tiết Sản Phẩm (productDetail.php)
 * @param {number} delta (+1 hoặc -1)
 */
function adjustQty(delta) {
    var input = document.getElementById('detailQuantity');
    if (input) {
        var current = parseInt(input.value) || 1;
        var next = Math.max(1, Math.min(99, current + delta));
        input.value = next;
    }
}

/**
 * Điều chỉnh số lượng trên từng dòng trong Giỏ Hàng (cartView.php) và tự động cập nhật
 * @param {number} productId
 * @param {number} delta (+1 hoặc -1)
 */
function changeRowQty(productId, delta) {
    var input = document.getElementById('qty_' + productId);
    if (input) {
        var current = parseInt(input.value) || 1;
        var next = Math.max(1, Math.min(99, current + delta));
        input.value = next;
        var cartForm = document.getElementById('cartForm');
        if (cartForm) {
            cartForm.submit();
        }
    }
}

// 2. Khởi tạo khi DOM sẵn sàng
document.addEventListener('DOMContentLoaded', function() {
    updateShopThemeUI();
});
