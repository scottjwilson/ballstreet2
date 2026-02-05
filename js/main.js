/**
 * Ball Street Sports Journal
 * Main JavaScript Entry Point
 */

// Import all CSS files - Vite will watch and HMR these automatically
import "../css/variables.css";
import "../css/base.css";
import "../css/layout.css";
import "../css/ticker.css";
import "../css/header.css";
import "../css/footer.css";
import "../css/buttons.css";
import "../css/cards.css";
import "../css/hero.css";
import "../css/deals.css";
import "../css/articles.css";
import "../css/newsletter.css";
import "../css/front-page.css";
import "../css/athletes-table.css";
import "../css/single-athlete.css";
import "../css/archive.css";
import "../css/single.css";
import "../css/single-deal.css";

(function () {
  "use strict";

  // ========================================
  // THEME TOGGLE
  // ========================================

  function initThemeToggle() {
    const STORAGE_KEY = "ballstreet-theme";
    const root = document.documentElement;
    const toggleButtons = document.querySelectorAll(".theme-toggle");

    // Get initial theme from localStorage or system preference
    function getPreferredTheme() {
      const stored = localStorage.getItem(STORAGE_KEY);
      if (stored) {
        return stored;
      }
      // Check system preference
      return window.matchMedia("(prefers-color-scheme: light)").matches
        ? "light"
        : "dark";
    }

    // Apply theme to document
    function setTheme(theme) {
      root.setAttribute("data-theme", theme);
      localStorage.setItem(STORAGE_KEY, theme);
      updateToggleButtons(theme);
    }

    // Update all toggle button icons/aria labels
    function updateToggleButtons(theme) {
      const isDark = theme === "dark";

      toggleButtons.forEach(function (btn) {
        btn.setAttribute(
          "aria-label",
          isDark ? "Switch to light mode" : "Switch to dark mode",
        );

        // Update icons
        const sunIcon = btn.querySelector(".icon-sun");
        const moonIcon = btn.querySelector(".icon-moon");

        if (sunIcon && moonIcon) {
          sunIcon.style.display = isDark ? "none" : "block";
          moonIcon.style.display = isDark ? "block" : "none";
        }
      });
    }

    // Toggle between light and dark
    function toggleTheme() {
      const current = root.getAttribute("data-theme") || getPreferredTheme();
      const next = current === "dark" ? "light" : "dark";
      setTheme(next);
    }

    // Initialize
    const initialTheme = getPreferredTheme();
    setTheme(initialTheme);

    // Bind all toggle buttons
    toggleButtons.forEach(function (btn) {
      btn.addEventListener("click", toggleTheme);
    });

    // Listen for system preference changes
    window
      .matchMedia("(prefers-color-scheme: light)")
      .addEventListener("change", function (e) {
        // Only auto-switch if user hasn't manually set a preference
        if (!localStorage.getItem(STORAGE_KEY)) {
          setTheme(e.matches ? "light" : "dark");
        }
      });

    // Expose toggle function globally for external use
    window.ballstreetToggleTheme = toggleTheme;
  }

  // ========================================
  // TICKER
  // ========================================

  function initTicker() {
    const ticker = document.querySelector(".ticker-wrapper");
    if (!ticker) return;

    // Pause animation on hover
    ticker.addEventListener("mouseenter", function () {
      ticker.style.animationPlayState = "paused";
    });

    ticker.addEventListener("mouseleave", function () {
      ticker.style.animationPlayState = "running";
    });
  }

  // ========================================
  // HEADER SCROLL BEHAVIOR
  // ========================================

  function initHeader() {
    const header = document.querySelector(".header");
    if (!header) return;

    let ticking = false;

    function updateHeader() {
      if (window.scrollY > 50) {
        header.classList.add("is-scrolled");
      } else {
        header.classList.remove("is-scrolled");
      }
      ticking = false;
    }

    window.addEventListener("scroll", function () {
      if (!ticking) {
        requestAnimationFrame(updateHeader);
        ticking = true;
      }
    });
  }

  // ========================================
  // FADE IN ANIMATIONS
  // ========================================

  function initFadeAnimations() {
    const fadeElements = document.querySelectorAll(".fade-in");
    if (!fadeElements.length) return;

    const observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
      },
    );

    fadeElements.forEach(function (el) {
      observer.observe(el);
    });
  }

  // ========================================
  // CATEGORY TABS
  // ========================================

  function initCategoryTabs() {
    const tabs = document.querySelectorAll(".category-btn");
    if (!tabs.length) return;

    tabs.forEach(function (tab) {
      tab.addEventListener("click", function () {
        // Remove active class from all tabs
        tabs.forEach(function (t) {
          t.classList.remove("active");
        });

        // Add active class to clicked tab
        tab.classList.add("active");

        // Get category for filtering
        const category = tab.dataset.category;

        // Emit custom event for filtering (can be used by other modules)
        document.dispatchEvent(
          new CustomEvent("categoryChanged", {
            detail: { category: category },
          }),
        );
      });
    });
  }

  // ========================================
  // NEWSLETTER FORM
  // ========================================

  function initNewsletter() {
    const form = document.querySelector(".newsletter-form");
    if (!form) return;

    form.addEventListener("submit", function (e) {
      const emailInput = form.querySelector('input[type="email"]');
      if (!emailInput) return;

      const email = emailInput.value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!email || !emailRegex.test(email)) {
        e.preventDefault();
        emailInput.classList.add("is-error");
        emailInput.focus();
      } else {
        emailInput.classList.remove("is-error");
      }
    });
  }

  // ========================================
  // SMOOTH SCROLL
  // ========================================

  function initSmoothScroll() {
    const anchors = document.querySelectorAll('a[href^="#"]');

    anchors.forEach(function (anchor) {
      anchor.addEventListener("click", function (e) {
        const targetId = this.getAttribute("href");
        if (targetId === "#") return;

        const target = document.querySelector(targetId);
        if (target) {
          e.preventDefault();
          const offset = 100;
          const position =
            target.getBoundingClientRect().top + window.scrollY - offset;

          window.scrollTo({
            top: position,
            behavior: "smooth",
          });
        }
      });
    });
  }

  // ========================================
  // CARD HOVER EFFECTS
  // ========================================

  function initCardEffects() {
    const cards = document.querySelectorAll(
      ".deal-card, .sidebar-card, .article-row",
    );

    cards.forEach(function (card) {
      // Add keyboard accessibility
      if (!card.hasAttribute("tabindex")) {
        card.setAttribute("tabindex", "0");
      }

      // Handle keyboard navigation
      card.addEventListener("keydown", function (e) {
        if (e.key === "Enter" || e.key === " ") {
          const link = card.querySelector("a");
          if (link) {
            link.click();
          }
        }
      });
    });
  }

  // ========================================
  // SHARE BUTTONS
  // ========================================

  function initShareButtons() {
    const copyButtons = document.querySelectorAll(".copy-link");
    if (!copyButtons.length) return;

    copyButtons.forEach(function (btn) {
      btn.addEventListener("click", function () {
        const url = btn.dataset.url;
        if (!url) return;

        navigator.clipboard
          .writeText(url)
          .then(function () {
            const originalText = btn.textContent;
            btn.textContent = "Copied!";
            btn.style.background = "var(--accent-green-dim)";
            btn.style.color = "var(--accent-green)";

            setTimeout(function () {
              btn.textContent = originalText;
              btn.style.background = "";
              btn.style.color = "";
            }, 2000);
          })
          .catch(function () {
            // Fallback for older browsers
            const textarea = document.createElement("textarea");
            textarea.value = url;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand("copy");
            document.body.removeChild(textarea);

            btn.textContent = "Copied!";
            setTimeout(function () {
              btn.textContent = "Copy Link";
            }, 2000);
          });
      });
    });
  }

  // ========================================
  // ATHLETES TABLE
  // ========================================

  function initAthletesTable() {
    const tables = document.querySelectorAll("[data-athletes-table]");
    if (!tables.length) return;

    tables.forEach(function (container) {
      const rows = container.querySelectorAll("[data-athlete-row]");
      const searchInput = container.querySelector("[data-athletes-search]");
      const filterSelects = container.querySelectorAll(
        "[data-athletes-filter]",
      );
      const sortButtons = container.querySelectorAll("[data-athletes-sort]");
      const viewButtons = container.querySelectorAll("[data-view]");
      const noResults = container.querySelector(".athletes-no-results");

      let currentSort = { field: null, direction: "asc" };

      // Debounce utility
      function debounce(func, wait) {
        let timeout;
        return function (...args) {
          clearTimeout(timeout);
          timeout = setTimeout(function () {
            func.apply(this, args);
          }, wait);
        };
      }

      // Apply filters
      function applyFilters() {
        const searchTerm = searchInput
          ? searchInput.value.toLowerCase().trim()
          : "";

        const filters = {};
        filterSelects.forEach(function (select) {
          const filterType = select.dataset.athletesFilter;
          const value = select.value.toLowerCase();
          if (value) {
            filters[filterType] = value;
          }
        });

        let visibleCount = 0;

        rows.forEach(function (row) {
          let visible = true;

          // Search filter
          if (searchTerm) {
            const name = row.dataset.name || "";
            const position = row.dataset.position || "";
            const school = row.dataset.school || "";
            const searchableText = name + " " + position + " " + school;

            if (!searchableText.includes(searchTerm)) {
              visible = false;
            }
          }

          // Dropdown filters
          for (const filterType in filters) {
            const rowValue = row.dataset[filterType] || "";
            if (!rowValue.includes(filters[filterType])) {
              visible = false;
              break;
            }
          }

          row.style.display = visible ? "" : "none";
          if (visible) visibleCount++;
        });

        // Show/hide no results message
        if (noResults) {
          noResults.style.display = visibleCount === 0 ? "" : "none";
        }
      }

      // Sort rows
      function sortRows(field) {
        if (currentSort.field === field) {
          currentSort.direction =
            currentSort.direction === "asc" ? "desc" : "asc";
        } else {
          currentSort.field = field;
          currentSort.direction = "asc";
        }

        const rowsArray = Array.from(rows);
        const direction = currentSort.direction;

        rowsArray.sort(function (a, b) {
          let aVal = a.dataset[field] || "";
          let bVal = b.dataset[field] || "";

          // Numeric comparison for nil and rank
          if (field === "nil" || field === "rank") {
            aVal = parseFloat(aVal) || 0;
            bVal = parseFloat(bVal) || 0;
            return direction === "asc" ? aVal - bVal : bVal - aVal;
          }

          // String comparison
          aVal = aVal.toLowerCase();
          bVal = bVal.toLowerCase();

          if (aVal < bVal) return direction === "asc" ? -1 : 1;
          if (aVal > bVal) return direction === "asc" ? 1 : -1;
          return 0;
        });

        // Re-append rows in sorted order (for each view)
        const tableBody = container.querySelector(".athletes-table tbody");
        const cardsGrid = container.querySelector(".athletes-cards-grid");

        if (tableBody) {
          rowsArray.forEach(function (row) {
            if (row.tagName === "TR") {
              tableBody.appendChild(row);
            }
          });
        }

        if (cardsGrid) {
          rowsArray.forEach(function (row) {
            if (row.tagName === "ARTICLE") {
              cardsGrid.appendChild(row);
            }
          });
        }

        // Update sort button states
        sortButtons.forEach(function (btn) {
          btn.classList.remove("sort-asc", "sort-desc");
          if (btn.dataset.athletesSort === field) {
            btn.classList.add("sort-" + currentSort.direction);
          }
        });
      }

      // Switch view
      function switchView(view) {
        const views = container.querySelectorAll(".athletes-view");
        views.forEach(function (v) {
          v.classList.remove("is-active");
        });

        const targetView = container.querySelector(".athletes-view-" + view);
        if (targetView) {
          targetView.classList.add("is-active");
        }

        viewButtons.forEach(function (btn) {
          btn.classList.toggle("active", btn.dataset.view === view);
        });

        container.dataset.view = view;
      }

      // Bind events
      if (searchInput) {
        searchInput.addEventListener(
          "input",
          debounce(function () {
            applyFilters();
          }, 200),
        );
      }

      filterSelects.forEach(function (select) {
        select.addEventListener("change", applyFilters);
      });

      sortButtons.forEach(function (btn) {
        btn.addEventListener("click", function () {
          sortRows(btn.dataset.athletesSort);
        });
      });

      viewButtons.forEach(function (btn) {
        btn.addEventListener("click", function () {
          switchView(btn.dataset.view);
        });
      });
    });
  }

  // ========================================
  // INIT
  // ========================================

  function init() {
    initThemeToggle();
    initTicker();
    initHeader();
    initFadeAnimations();
    initCategoryTabs();
    initNewsletter();
    initSmoothScroll();
    initCardEffects();
    initAthletesTable();
    initShareButtons();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
