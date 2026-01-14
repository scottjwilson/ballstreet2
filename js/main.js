// Import all CSS files - Vite will watch and HMR these automatically
// Always import base CSS files for HMR to work properly
import "../css/variables.css";
import "../css/base.css";
import "../css/layout.css";
import "../css/header.css";
import "../css/footer.css";
// Import all page-specific CSS files
// Vite will tree-shake unused CSS in production, but we import all for HMR
import "../css/front-page.css";

// Main JavaScript code
console.log("Vite is working with HMR!");

// Mobile menu toggle
document.addEventListener("DOMContentLoaded", function () {
  const mobileMenuBtn = document.querySelector(".mobile-menu-btn");
  const mobileNav = document.querySelector(".nav-mobile");

  if (mobileMenuBtn && mobileNav) {
    mobileMenuBtn.addEventListener("click", function () {
      const isOpen = mobileNav.classList.toggle("is-open");
      mobileMenuBtn.classList.toggle("is-active");
      mobileMenuBtn.setAttribute("aria-expanded", isOpen);
    });
  }
});
