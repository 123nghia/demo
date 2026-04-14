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
const previewImageCards = [...document.querySelectorAll("[data-image-preview]")];
const detailGalleryImages = [...document.querySelectorAll(".detail-gallery__item img")];

let slideIndex = 0;
let sliderTimer;
let overlayHideTimer;
let menuOpeningTimer;

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

  hoverRedirectCards.forEach((card) => {
    card.style.cursor = "pointer";
    card.setAttribute("role", "link");
    card.setAttribute("tabindex", "0");

    const redirectToTarget = () => {
      const target = card.getAttribute("data-hover-redirect");
      if (!target) return;
      window.location.href = target;
    };

    card.addEventListener("click", (event) => {
      const interactiveTarget = event.target.closest("a, button, input, textarea, select, label");
      if (interactiveTarget) return;
      redirectToTarget();
    });

    card.addEventListener("keydown", (event) => {
      if (event.key !== "Enter" && event.key !== " ") return;
      event.preventDefault();
      redirectToTarget();
    });
  });
}

function initHomeImagePreviewCards() {
  if (!previewImageCards.length) return;

  const lightbox = document.createElement("div");
  lightbox.className = "home-image-lightbox";
  lightbox.setAttribute("aria-hidden", "true");
  lightbox.innerHTML = `
    <button class="home-image-lightbox__close" type="button" aria-label="Đóng xem ảnh" data-home-image-close>×</button>
    <div class="home-image-lightbox__inner" data-home-image-backdrop>
      <img class="home-image-lightbox__img" src="" alt="">
      <p class="home-image-lightbox__title" data-home-image-title></p>
    </div>
  `;
  document.body.append(lightbox);

  const lightboxImage = lightbox.querySelector(".home-image-lightbox__img");
  const lightboxTitle = lightbox.querySelector("[data-home-image-title]");
  const closeButton = lightbox.querySelector("[data-home-image-close]");
  const backdrop = lightbox.querySelector("[data-home-image-backdrop]");

  const closeLightbox = () => {
    if (!root.classList.contains("home-image-open")) return;
    root.classList.remove("home-image-open");
    lightbox.classList.remove("is-open");
    lightbox.setAttribute("aria-hidden", "true");
  };

  const openLightbox = (src, title) => {
    if (!src) return;

    closeMenu();
    closeSearch();

    lightboxImage.src = src;
    lightboxImage.alt = title || "Ảnh dự án";
    lightboxTitle.textContent = title || "";

    root.classList.add("home-image-open");
    lightbox.setAttribute("aria-hidden", "false");

    window.requestAnimationFrame(() => {
      lightbox.classList.add("is-open");
    });
  };

  previewImageCards.forEach((card) => {
    const openFromCard = () => {
      const src = card.getAttribute("data-image-preview") || "";
      const title = card.getAttribute("data-image-preview-title") || "";
      openLightbox(src, title);
    };

    card.style.cursor = "zoom-in";
    card.setAttribute("role", "button");
    card.setAttribute("tabindex", "0");

    card.addEventListener("click", (event) => {
      const interactiveTarget = event.target.closest("a, button, input, textarea, select, label");
      if (interactiveTarget) return;
      openFromCard();
    });

    card.addEventListener("keydown", (event) => {
      if (event.key !== "Enter" && event.key !== " ") return;
      event.preventDefault();
      openFromCard();
    });
  });

  closeButton?.addEventListener("click", closeLightbox);

  backdrop?.addEventListener("click", (event) => {
    if (event.target === backdrop) {
      closeLightbox();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (!root.classList.contains("home-image-open")) return;

    if (event.key === "Escape") {
      event.preventDefault();
      closeLightbox();
    }
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

function initContactFormEnhancements() {
  const contactForms = [...document.querySelectorAll('form.contact-form[action*="contact-submit"]')];
  if (!contactForms.length) return;

  const resolveFirstError = (errorsPayload) => {
    if (!errorsPayload || typeof errorsPayload !== "object") return "Thông tin gửi chưa hợp lệ. Vui lòng kiểm tra lại.";

    const firstField = Object.keys(errorsPayload)[0];
    if (!firstField) return "Thông tin gửi chưa hợp lệ. Vui lòng kiểm tra lại.";

    const firstError = errorsPayload[firstField];
    if (Array.isArray(firstError) && firstError.length) {
      return String(firstError[0]);
    }

    if (typeof firstError === "string" && firstError.trim() !== "") {
      return firstError;
    }

    return "Thông tin gửi chưa hợp lệ. Vui lòng kiểm tra lại.";
  };

  contactForms.forEach((form) => {
    if (form.dataset.enhancedSubmit === "1") return;
    form.dataset.enhancedSubmit = "1";

    const submitButton = form.querySelector(".contact-submit") || form.querySelector('button[type="submit"]');
    if (!submitButton) return;

    const defaultButtonLabel = (submitButton.textContent || "Gửi").trim();
    submitButton.dataset.defaultLabel = defaultButtonLabel;

    let feedbackNode = form.querySelector(".contact-form__feedback");
    if (!feedbackNode) {
      feedbackNode = document.createElement("p");
      feedbackNode.className = "contact-form__feedback";
      feedbackNode.setAttribute("role", "status");
      feedbackNode.setAttribute("aria-live", "polite");

      const noteNode = form.querySelector(".contact-form__note");
      if (noteNode) {
        noteNode.insertAdjacentElement("beforebegin", feedbackNode);
      } else {
        form.appendChild(feedbackNode);
      }
    }

    const showFeedback = (message, tone = "success") => {
      feedbackNode.textContent = message || "";
      feedbackNode.classList.remove("is-success", "is-error", "is-visible");

      if (message) {
        feedbackNode.classList.add(tone === "error" ? "is-error" : "is-success", "is-visible");
      }
    };

    const setSubmittingState = (isSubmitting) => {
      form.classList.toggle("is-submitting", isSubmitting);

      const controls = [...form.querySelectorAll("input, textarea, select, button")];
      controls.forEach((control) => {
        if (!isSubmitting && control === submitButton) {
          control.disabled = false;
          return;
        }

        if (control === submitButton) {
          return;
        }

        control.disabled = isSubmitting;
      });

      submitButton.disabled = isSubmitting;
      submitButton.classList.toggle("is-loading", isSubmitting);

      if (isSubmitting) {
        submitButton.innerHTML = '<span class="contact-submit__spinner" aria-hidden="true"></span><span>ĐANG GỬI...</span>';
        return;
      }

      submitButton.textContent = submitButton.dataset.defaultLabel || "Gửi";
    };

    form.addEventListener("submit", async (event) => {
      event.preventDefault();

      showFeedback("");
      form.classList.remove("is-success");
      setSubmittingState(true);

      try {
        const response = await fetch(form.action, {
          method: "POST",
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          body: new FormData(form),
        });

        const payload = await response.json().catch(() => ({}));

        if (!response.ok) {
          if (response.status === 422) {
            throw new Error(resolveFirstError(payload.errors));
          }

          throw new Error(payload.message || "Không thể gửi thông tin lúc này. Vui lòng thử lại sau.");
        }

        showFeedback(payload.message || "Gửi thành công. HOVI sẽ liên hệ bạn sớm nhất.", "success");
        form.classList.add("is-success");
        form.reset();

        window.setTimeout(() => {
          form.classList.remove("is-success");
        }, 1400);
      } catch (error) {
        showFeedback(error.message || "Không thể gửi thông tin lúc này. Vui lòng thử lại sau.", "error");
      } finally {
        setSubmittingState(false);
      }
    });
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
initHomeImagePreviewCards();
initDetailGalleryLightbox();
initContactFormEnhancements();
goToSlide(0);
startSlider();
syncOverlay();
