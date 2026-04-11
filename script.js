const root = document.body;
const scrollRoot = document.querySelector("#main-scroll");
const sections = [...document.querySelectorAll("[data-section]")];
const dots = [...document.querySelectorAll("[data-dot]")];
const overlay = document.querySelector(".overlay");

const menuToggle = document.querySelector("[data-open-menu]");
const menuClose = document.querySelector("[data-close-menu]");
const searchToggle = document.querySelector("[data-open-search]");
const searchClose = document.querySelector("[data-close-search]");
const mobileLinks = [...document.querySelectorAll("[data-mobile-link]")];
const contactWidget = document.querySelector("[data-contact-widget]");
const contactToggle = document.querySelector("[data-contact-toggle]");

const sliderTrack = document.querySelector("[data-slider-track]");
const sliderPrev = document.querySelector("[data-slider-prev]");
const sliderNext = document.querySelector("[data-slider-next]");
const sliderSlides = sliderTrack ? [...sliderTrack.children] : [];
const hoverRedirectCards = [...document.querySelectorAll("[data-hover-redirect]")];
const detailGalleryImages = [...document.querySelectorAll(".detail-gallery__item img")];

let slideIndex = 0;
let sliderTimer;
let wheelLock = false;
let overlayHideTimer;
let menuOpeningTimer;
let hoverRedirectTimer;

function syncOverlay() {
  if (!overlay) return;

  const opened = root.classList.contains("menu-open") || root.classList.contains("search-open");

  if (opened) {
    window.clearTimeout(overlayHideTimer);
    overlay.hidden = false;
    window.requestAnimationFrame(() => {
      overlay.classList.add("is-visible");
    });
    return;
  }

  overlay.classList.remove("is-visible");
  overlayHideTimer = window.setTimeout(() => {
    const stillOpen = root.classList.contains("menu-open") || root.classList.contains("search-open");
    if (!stillOpen) {
      overlay.hidden = true;
    }
  }, 340);
}

function openMenu() {
  if (root.classList.contains("menu-open")) return;

  root.classList.add("menu-opening");
  window.clearTimeout(menuOpeningTimer);
  menuOpeningTimer = window.setTimeout(() => {
    root.classList.remove("menu-opening");
  }, 460);

  root.classList.add("menu-open");
  root.classList.remove("search-open");
  menuToggle?.classList.remove("is-pressed");
  menuToggle?.setAttribute("aria-expanded", "true");
  document.querySelector("#mobile-menu")?.setAttribute("aria-hidden", "false");
  document.querySelector("#search-modal")?.setAttribute("aria-hidden", "true");
  syncOverlay();
}

function closeMenu() {
  if (!root.classList.contains("menu-open")) return;

  window.clearTimeout(menuOpeningTimer);
  root.classList.remove("menu-opening");
  root.classList.remove("menu-open");
  menuToggle?.classList.remove("is-pressed");
  menuToggle?.setAttribute("aria-expanded", "false");
  document.querySelector("#mobile-menu")?.setAttribute("aria-hidden", "true");
  syncOverlay();
}

function openSearch() {
  root.classList.add("search-open");
  root.classList.remove("menu-open");
  document.querySelector("#search-modal")?.setAttribute("aria-hidden", "false");
  document.querySelector("#mobile-menu")?.setAttribute("aria-hidden", "true");
  syncOverlay();
}

function closeSearch() {
  root.classList.remove("search-open");
  document.querySelector("#search-modal")?.setAttribute("aria-hidden", "true");
  syncOverlay();
}

function updateDots(id) {
  dots.forEach((dot) => {
    dot.classList.toggle("is-active", dot.dataset.dot === id);
  });
  root.classList.toggle("scrolled", id !== "hero");
  root.classList.toggle("in-gallery", id === "projects-1" || id === "projects-2");
  root.classList.toggle("in-footer", id === "footer");
}

function observeSections() {
  const observer = new IntersectionObserver(
    (entries) => {
      const visible = entries
        .filter((entry) => entry.isIntersecting)
        .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

      if (visible) {
        updateDots(visible.target.dataset.section);
      }
    },
    {
      root: scrollRoot,
      threshold: [0.25, 0.55, 0.75],
    },
  );

  sections.forEach((section) => observer.observe(section));
}

function goToSlide(index) {
  if (!sliderTrack || !sliderSlides.length) return;
  slideIndex = (index + sliderSlides.length) % sliderSlides.length;
  sliderTrack.style.transform = `translateX(-${slideIndex * 100}%)`;
}

function startSlider() {
  if (!sliderSlides.length) return;
  clearInterval(sliderTimer);
  sliderTimer = window.setInterval(() => {
    goToSlide(slideIndex + 1);
  }, 3000);
}

function smoothAnchorLinks() {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", (event) => {
      const href = anchor.getAttribute("href");
      if (!href || href === "#") return;

      const target = document.querySelector(href);
      if (!target) return;

      event.preventDefault();
      closeMenu();
      closeSearch();

      target.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    });
  });
}

function bindHoverRedirects() {
  if (!hoverRedirectCards.length) return;
  if (!window.matchMedia("(hover: hover) and (pointer: fine)").matches) return;

  hoverRedirectCards.forEach((card) => {
    card.style.cursor = "pointer";

    card.addEventListener("mouseenter", () => {
      const target = card.getAttribute("data-hover-redirect");
      if (!target) return;

      window.clearTimeout(hoverRedirectTimer);
      hoverRedirectTimer = window.setTimeout(() => {
        window.location.href = target;
      }, 120);
    });

    card.addEventListener("mouseleave", () => {
      window.clearTimeout(hoverRedirectTimer);
    });
  });
}

function initDetailGalleryLightbox() {
  if (!detailGalleryImages.length) return;

  const lightbox = document.createElement("div");
  lightbox.className = "detail-lightbox";
  lightbox.setAttribute("aria-hidden", "true");
  lightbox.innerHTML = `
    <div class="detail-lightbox__top">
      <p class="detail-lightbox__counter" data-lightbox-counter>1 / ${detailGalleryImages.length}</p>
      <div class="detail-lightbox__actions">
        <button class="detail-lightbox__action" type="button" data-lightbox-prev aria-label="Ảnh trước">‹</button>
        <button class="detail-lightbox__action" type="button" data-lightbox-next aria-label="Ảnh tiếp">›</button>
        <button class="detail-lightbox__action detail-lightbox__action--close" type="button" data-lightbox-close aria-label="Đóng ảnh">×</button>
      </div>
    </div>
    <div class="detail-lightbox__viewport">
      <img class="detail-lightbox__image" src="" alt="">
    </div>
  `;
  document.body.append(lightbox);

  const lightboxImage = lightbox.querySelector(".detail-lightbox__image");
  const counter = lightbox.querySelector("[data-lightbox-counter]");
  const prevButton = lightbox.querySelector("[data-lightbox-prev]");
  const nextButton = lightbox.querySelector("[data-lightbox-next]");
  const closeButton = lightbox.querySelector("[data-lightbox-close]");
  const viewport = lightbox.querySelector(".detail-lightbox__viewport");

  let currentIndex = 0;

  const updateImage = (index) => {
    currentIndex = (index + detailGalleryImages.length) % detailGalleryImages.length;
    const sourceImage = detailGalleryImages[currentIndex];
    lightboxImage.src = sourceImage.currentSrc || sourceImage.src;
    lightboxImage.alt = sourceImage.alt || "Ảnh dự án";
    counter.textContent = `${currentIndex + 1} / ${detailGalleryImages.length}`;
  };

  const closeLightbox = () => {
    if (!root.classList.contains("lightbox-open")) return;
    root.classList.remove("lightbox-open");
    lightbox.classList.remove("is-open");
    lightbox.setAttribute("aria-hidden", "true");
  };

  const openLightbox = (index) => {
    closeMenu();
    closeSearch();
    updateImage(index);
    root.classList.add("lightbox-open");
    lightbox.setAttribute("aria-hidden", "false");
    window.requestAnimationFrame(() => {
      lightbox.classList.add("is-open");
    });
  };

  detailGalleryImages.forEach((image, index) => {
    image.style.cursor = "zoom-in";
    image.addEventListener("click", () => {
      openLightbox(index);
    });
  });

  prevButton?.addEventListener("click", () => {
    updateImage(currentIndex - 1);
  });

  nextButton?.addEventListener("click", () => {
    updateImage(currentIndex + 1);
  });

  closeButton?.addEventListener("click", closeLightbox);

  viewport?.addEventListener("click", (event) => {
    if (event.target === viewport) {
      closeLightbox();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (!root.classList.contains("lightbox-open")) return;

    if (event.key === "ArrowLeft") {
      event.preventDefault();
      updateImage(currentIndex - 1);
    }

    if (event.key === "ArrowRight") {
      event.preventDefault();
      updateImage(currentIndex + 1);
    }

    if (event.key === "Escape") {
      event.preventDefault();
      closeLightbox();
    }
  });
}

menuToggle?.addEventListener("click", openMenu);
menuToggle?.addEventListener("pointerdown", () => {
  menuToggle.classList.add("is-pressed");
});
menuToggle?.addEventListener("pointerup", () => {
  menuToggle.classList.remove("is-pressed");
});
menuToggle?.addEventListener("pointercancel", () => {
  menuToggle.classList.remove("is-pressed");
});
menuToggle?.addEventListener("blur", () => {
  menuToggle.classList.remove("is-pressed");
});
menuClose?.addEventListener("click", closeMenu);
searchToggle?.addEventListener("click", openSearch);
searchClose?.addEventListener("click", closeSearch);
overlay?.addEventListener("click", () => {
  closeMenu();
  closeSearch();
});

mobileLinks.forEach((link) => {
  link.addEventListener("click", closeMenu);
});

contactToggle?.addEventListener("click", () => {
  contactWidget?.classList.toggle("is-open");
});

sliderPrev?.addEventListener("click", () => {
  goToSlide(slideIndex - 1);
  startSlider();
});

sliderNext?.addEventListener("click", () => {
  goToSlide(slideIndex + 1);
  startSlider();
});

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    closeMenu();
    closeSearch();
  }
});

observeSections();
smoothAnchorLinks();
bindHoverRedirects();
initDetailGalleryLightbox();
goToSlide(0);
startSlider();
syncOverlay();

if (scrollRoot) {
  scrollRoot.addEventListener(
    "wheel",
    (event) => {
      if (window.innerWidth <= 820) return;
      if (wheelLock) return;

      const delta = event.deltaY;
      if (Math.abs(delta) < 10) return;

      event.preventDefault();
      wheelLock = true;

      const currentTop = scrollRoot.scrollTop;
      const sorted = sections
        .map((section) => ({ section, top: section.offsetTop }))
        .sort((a, b) => a.top - b.top);

      const currentIndex = sorted.findIndex((item, index) => {
        const next = sorted[index + 1];
        return next ? currentTop < next.top - 2 : true;
      });

      const nextIndex = delta > 0 ? Math.min(currentIndex + 1, sorted.length - 1) : Math.max(currentIndex - 1, 0);
      const targetTop = sorted[nextIndex]?.top ?? 0;

      scrollRoot.scrollTo({
        top: targetTop,
        behavior: "smooth",
      });

      window.setTimeout(() => {
        wheelLock = false;
      }, 520);
    },
    { passive: false },
  );
}
