// File JavaScript tổng xử lý tương tác giao diện
document.addEventListener("DOMContentLoaded", function () {
  const currentPath = window.location.pathname.toLowerCase();

  // 1. Xử lý đổi màu Active cho Menu bên trái
  const leftLinks = document.querySelectorAll(".left-menu a, .sidebar a");
  leftLinks.forEach((link) => {
    const href = link.getAttribute("href");
    if (!href) return;
    const linkPath = href.toLowerCase();

    // Kiểm tra nếu đường dẫn hiện tại khớp với link bài 1 hoặc bài 2
    if (
      (linkPath.includes("bai1") && currentPath.includes("bai1")) ||
      (linkPath.includes("bai2") && currentPath.includes("bai2"))
    ) {
      link.classList.add("active-menu");
    }
  });

  // 2. Xử lý đổi màu Active cho Menu ngang Bài 2 (Register / ResultRegister / Calculate)
  const navLinks = document.querySelectorAll(".menu-nav a");
  let hasActiveNav = false;

  navLinks.forEach((link) => {
    const href = link.getAttribute("href");
    if (!href) return;
    const linkFileName = href.toLowerCase().split("/").pop();

    if (currentPath.endsWith(linkFileName)) {
      link.classList.add("active");
      hasActiveNav = true;
    }
  });

  // Mặc định nếu ở Bai2 mà chưa có tab nào active (ví dụ index.php), active tab Register
  if (!hasActiveNav && currentPath.includes("bai2") && navLinks.length > 0) {
    navLinks[0].classList.add("active");
  }
});
