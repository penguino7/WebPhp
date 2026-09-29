/**
 * Bài 16: Rich Text Box (WYSIWYG CKEditor) - Client-side JavaScript Handler
 */

// 1. Khởi tạo theme ngay khi nạp để tránh nháy giao diện (FOUC)
(function () {
  var theme = localStorage.getItem("pixel_admin_theme") || "dark";
  document.documentElement.setAttribute("data-theme", theme);
})();

/**
 * Cập nhật giao diện nút Theme Admin
 */
function updateThemeUI() {
  var theme = document.documentElement.getAttribute("data-theme") || "dark";
  var btn = document.getElementById("themeToggleBtn");
  if (btn) {
    var icon = btn.querySelector(".theme-icon");
    var text = btn.querySelector(".theme-text");
    if (theme === "light") {
      if (icon) icon.textContent = "☀️";
      if (text) text.textContent = "SÁNG";
    } else {
      if (icon) icon.textContent = "🌙";
      if (text) text.textContent = "TỐI";
    }
  }
}

/**
 * Chuyển đổi theme Sáng / Tối trong Admin
 */
function togglePixelAdminTheme() {
  var current = document.documentElement.getAttribute("data-theme") || "dark";
  var next = current === "dark" ? "light" : "dark";
  document.documentElement.setAttribute("data-theme", next);
  localStorage.setItem("pixel_admin_theme", next);
  updateThemeUI();
}

/**
 * Khởi tạo CKEditor cho trường textarea full_spec
 */
function initCKEditorForFullSpec() {
  var textarea = document.getElementById("full_spec");
  if (textarea && typeof CKEDITOR !== "undefined") {
    CKEDITOR.replace("full_spec", {
      height: 350,
      language: "vi",
      toolbar: [
        { name: "document", items: ["Source", "-", "Preview", "Templates"] },
        {
          name: "clipboard",
          items: [
            "Cut",
            "Copy",
            "Paste",
            "PasteText",
            "PasteFromWord",
            "-",
            "Undo",
            "Redo",
          ],
        },
        { name: "editing", items: ["Find", "Replace", "-", "SelectAll"] },
        "/",
        {
          name: "basicstyles",
          items: [
            "Bold",
            "Italic",
            "Underline",
            "Strike",
            "Subscript",
            "Superscript",
            "-",
            "RemoveFormat",
          ],
        },
        {
          name: "paragraph",
          items: [
            "NumberedList",
            "BulletedList",
            "-",
            "Outdent",
            "Indent",
            "-",
            "Blockquote",
            "CreateDiv",
            "-",
            "JustifyLeft",
            "JustifyCenter",
            "JustifyRight",
            "JustifyBlock",
          ],
        },
        { name: "links", items: ["Link", "Unlink"] },
        {
          name: "insert",
          items: ["Image", "Table", "HorizontalRule", "SpecialChar"],
        },
        "/",
        { name: "styles", items: ["Styles", "Format", "Font", "FontSize"] },
        { name: "colors", items: ["TextColor", "BGColor"] },
        { name: "tools", items: ["Maximize", "ShowBlocks"] },
      ],
      allowedContent: true,
    });

    // Đồng bộ dữ liệu CKEditor vào textarea trước khi submit form
    var form = document.getElementById("richTextProductForm");
    if (form) {
      form.addEventListener("submit", function () {
        for (var instanceName in CKEDITOR.instances) {
          CKEDITOR.instances[instanceName].updateElement();
        }
      });
    }
  }
}

/**
 * Chèn mẫu bảng cấu hình Laptop Gaming vào CKEditor
 */
function insertGamingTemplate() {
  if (typeof CKEDITOR !== "undefined" && CKEDITOR.instances.full_spec) {
    var html = `<h3>🔥 LAPTOP GAMING CHIẾN MỌI TỰA GAME</h3>
<p>Dòng laptop gaming hiệu năng cực đỉnh với công nghệ tản nhiệt 2 quạt thế hệ mới và màn hình tần số quét siêu cao.</p>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; margin-top:10px;">
    <thead>
        <tr style="background-color:#e6005c; color:#ffffff;">
            <th style="width:30%;">Tiêu Chí</th>
            <th>Cấu Hình Chi Tiết</th>
        </tr>
    </thead>
    <tbody>
        <tr><td><strong>Bộ Xử Lý (CPU)</strong></td><td>Intel Core i7-13700H (14 nhân, 20 luồng, Turbo 5.0GHz)</td></tr>
        <tr><td><strong>Card Đồ Họa (GPU)</strong></td><td>NVIDIA GeForce RTX 4070 8GB GDDR6 (TGP 140W)</td></tr>
        <tr><td><strong>Bộ Nhớ RAM</strong></td><td>32GB DDR5 5200MHz Dual Channel</td></tr>
        <tr><td><strong>Lưu Trữ SSD</strong></td><td>1TB SSD M.2 PCIe 4.0 Super Fast</td></tr>
        <tr><td><strong>Màn Hình</strong></td><td>16.0 inch QHD+ (2560 x 1600), 240Hz, 3ms, G-Sync, 100% DCI-P3</td></tr>
        <tr><td><strong>Bàn Phím</strong></td><td>RGB 4-zone có bàn phím số riêng biệt</td></tr>
        <tr><td><strong>Trọng Lượng &amp; Pin</strong></td><td>2.35 kg - Pin 90Whr hỗ trợ sạc nhanh</td></tr>
    </tbody>
</table>`;
    CKEDITOR.instances.full_spec.setData(html);
  }
}

/**
 * Chèn mẫu bảng cấu hình Laptop Văn Phòng - Doanh Nhân vào CKEditor
 */
function insertOfficeTemplate() {
  if (typeof CKEDITOR !== "undefined" && CKEDITOR.instances.full_spec) {
    var html = `<h3>💼 LAPTOP DOANH NHÂN &amp; VĂN PHÒNG SIÊU NHẸ</h3>
<p>Thiết kế vỏ nhôm nguyên khối sang trọng, bảo mật vân tay một chạm cùng thời lượng pin lên đến 18 tiếng liên tục.</p>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; margin-top:10px;">
    <thead>
        <tr style="background-color:#0099ff; color:#ffffff;">
            <th style="width:30%;">Mục Đánh Giá</th>
            <th>Thông Số Thực Tế</th>
        </tr>
    </thead>
    <tbody>
        <tr><td><strong>Bộ Vi Xử Lý</strong></td><td>Apple M3 Chip / Intel Core Ultra 7 155H</td></tr>
        <tr><td><strong>Bộ Nhớ Trong</strong></td><td>16GB LPDDR5X Siêu Tiết Kiệm Điện</td></tr>
        <tr><td><strong>Ổ Cứng</strong></td><td>512GB SSD Siêu Tốc</td></tr>
        <tr><td><strong>Màn Hình</strong></td><td>14.0 inch OLED 3K (2880 x 1800), 120Hz, 100% DCI-P3, HDR 600</td></tr>
        <tr><td><strong>Cổng Kết Nối</strong></td><td>2x Thunderbolt 4 / USB-C, 1x USB-A 3.2, 1x HDMI 2.1, Jack 3.5mm</td></tr>
        <tr><td><strong>Khối Lượng</strong></td><td>1.19 kg - Mỏng chỉ 13.9 mm</td></tr>
    </tbody>
</table>`;
    CKEDITOR.instances.full_spec.setData(html);
  }
}

// 2. Khởi tạo khi DOM sẵn sàng
document.addEventListener("DOMContentLoaded", function () {
  updateThemeUI();
  initCKEditorForFullSpec();
});
