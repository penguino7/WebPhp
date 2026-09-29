/**
 * Bài 13: Website Bán Laptop (End User) - Client-side JavaScript Handler
 */

(function () {
  var theme = localStorage.getItem("pixel_theme") || "dark";
  document.documentElement.setAttribute("data-theme", theme);
})();

function updateThemeUI() {
  var currentTheme =
    document.documentElement.getAttribute("data-theme") || "dark";
  var btn = document.getElementById("themeToggleBtn");
  if (btn) {
    var icon = btn.querySelector(".theme-icon");
    var text = btn.querySelector(".theme-text");
    if (currentTheme === "light") {
      if (icon) icon.textContent = "☀️";
      if (text) text.textContent = "SÁNG";
    } else {
      if (icon) icon.textContent = "🌙";
      if (text) text.textContent = "TỐI";
    }
  }
}

function togglePixelTheme() {
  var currentTheme =
    document.documentElement.getAttribute("data-theme") || "dark";
  var newTheme = currentTheme === "dark" ? "light" : "dark";
  document.documentElement.setAttribute("data-theme", newTheme);
  localStorage.setItem("pixel_theme", newTheme);
  updateThemeUI();
}

document.addEventListener("DOMContentLoaded", updateThemeUI);
