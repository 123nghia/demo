(() => {
  const body = document.body;
  if (!body) return;

  const prefix = body.dataset.rootPrefix || "";
  const page = body.dataset.page || "";
  const isHomePage = page === "home";
  const sharedLogo = `${prefix}logoMenuRight1.png`;
  const sharedFooterLogo = `${prefix}logofooter.png`;
  const contact = {
    phoneDisplay: "0988991635",
    phoneHref: "tel:+84988991635",
    email: "hovivietnam99@gmail.com",
    emailHref: "mailto:hovivietnam99@gmail.com",
    websiteLabel: "www.hovi.com.vn",
    websiteHref: "https://www.hovi.com.vn",
    address: "BT6 KĐT Việt Hưng, Long Biên, Hà Nội",
    socialFacebook: "https://www.hovi.com.vn",
    socialTiktok: "https://www.hovi.com.vn",
    socialYoutube: "https://www.hovi.com.vn",
  };

  const homeSection = (sectionId) => (isHomePage ? `#${sectionId}` : `${prefix}index.html#${sectionId}`);
  const pageLink = (relativePath) => `${prefix}${relativePath}`;

  const mobileMenuLinks = [
    { key: "home", label: "Trang chủ", href: homeSection("hero") },
    { key: "about", label: "Giới thiệu", href: pageLink("about-us/") },
    { key: "project", label: "M07-L14, ĐTM Dương Nội", href: pageLink("biet-thu-don-lap-m07-l14-dtm-duong-noi/") },
    { key: "oceanpark", label: "Vinhomes Ocean Park", href: pageLink("thiet-ke-biet-thu-vinhomes-ocean-park/") },
    { key: "contact", label: "Liên hệ", href: pageLink("lien-he/") },
  ];

  const desktopNavLinks = [
    { key: "home", label: "Trang chủ", href: homeSection("hero"), homeIcon: true },
    { key: "project", label: "M07-L14, ĐTM Dương Nội", href: pageLink("biet-thu-don-lap-m07-l14-dtm-duong-noi/") },
    { key: "oceanpark", label: "Vinhomes Ocean Park", href: pageLink("thiet-ke-biet-thu-vinhomes-ocean-park/") },
    { key: "about", label: "Giới thiệu", href: pageLink("about-us/") },
    { key: "contact", label: "Liên hệ", href: pageLink("lien-he/") },
  ];

  const mobileLinksHtml = mobileMenuLinks
    .map(
      (link) =>
        `<a href="${link.href}" class="mobile-menu__link" data-mobile-link data-page-key="${link.key}">${link.label}</a>`,
    )
    .join("");

  const desktopLinksHtml = desktopNavLinks
    .map((link) => {
      if (link.homeIcon) {
        return `
      <a class="header-nav__link header-nav__link--home" href="${link.href}" data-page-key="${link.key}">
        <img src="${prefix}assets/icons/icon-home.webp" alt="">
        <span>${link.label}</span>
      </a>`;
      }
      return `<a class="header-nav__link" href="${link.href}" data-page-key="${link.key}">${link.label}</a>`;
    })
    .join("");

  const headerHtml = `
  <aside class="mobile-menu" id="mobile-menu" aria-hidden="true">
    <div class="mobile-menu__header">
      <img src="${sharedLogo}" alt="HOVI Việt Nam" class="mobile-menu__logo">
      <button class="mobile-menu__close" type="button" data-close-menu aria-label="Đóng menu">×</button>
    </div>
    <nav class="mobile-menu__nav" aria-label="Điều hướng di động">
      ${mobileLinksHtml}
    </nav>
    <p class="mobile-menu__meta">Thiết kế và thi công cảnh quan, sân vườn cao cấp cho biệt thự và penthouse.</p>
  </aside>

  <section class="search-modal" id="search-modal" aria-hidden="true">
    <div class="search-modal__card">
      <button class="search-modal__close" type="button" data-close-search aria-label="Đóng tìm kiếm">×</button>
      <p class="search-modal__eyebrow">Tìm kiếm</p>
      <h2>Tìm kiếm dự án bạn quan tâm</h2>
      <form class="search-modal__form" action="#" method="get">
        <input type="search" placeholder="Nhập tên dự án, khu đô thị hoặc từ khóa">
        <button type="submit">Tìm</button>
      </form>
    </div>
  </section>

  <header class="site-header">
    <div class="header-main">
      <div class="header-actions header-actions--left">
        <a class="icon-button" href="${contact.phoneHref}" aria-label="Gọi HOVI Việt Nam">
          <svg viewBox="0 0 473.806 473.806" aria-hidden="true" class="phone-icon">
            <path d="M374.456,293.506c-9.7-10.1-21.4-15.5-33.8-15.5c-12.3,0-24.1,5.3-34.2,15.4l-31.6,31.5c-2.6-1.4-5.2-2.7-7.7-4c-3.6-1.8-7-3.5-9.9-5.3c-29.6-18.8-56.5-43.3-82.3-75c-12.5-15.8-20.9-29.1-27-42.6c8.2-7.5,15.8-15.3,23.2-22.8c2.8-2.8,5.6-5.7,8.4-8.5c21-21,21-48.2,0-69.2l-27.3-27.3c-3.1-3.1-6.3-6.3-9.3-9.5c-6-6.2-12.3-12.6-18.8-18.6c-9.7-9.6-21.3-14.7-33.5-14.7s-24,5.1-34,14.7c-0.1,0.1-0.1,0.1-0.2,0.2l-34,34.3c-12.8,12.8-20.1,28.4-21.7,46.5c-2.4,29.2,6.2,56.4,12.8,74.2c16.2,43.7,40.4,84.2,76.5,127.6c43.8,52.3,96.5,93.6,156.7,122.7c23,10.9,53.7,23.8,88,26c2.1,0.1,4.3,0.2,6.3,0.2c23.1,0,42.5-8.3,57.7-24.8c0.1-0.2,0.3-0.3,0.4-0.5c5.2-6.3,11.2-12,17.5-18.1c4.3-4.1,8.7-8.4,13-12.9c9.9-10.3,15.1-22.3,15.1-34.6c0-12.4-5.3-24.3-15.4-34.3L374.456,293.506z" />
            <path d="M256.056,112.706c26.2,4.4,50,16.8,69,35.8s31.3,42.8,35.8,69c1.1,6.6,6.8,11.2,13.3,11.2c0.8,0,1.5-0.1,2.3-0.2c7.4-1.2,12.3-8.2,11.1-15.6c-5.4-31.7-20.4-60.6-43.3-83.5s-51.8-37.9-83.5-43.3c-7.4-1.2-14.3,3.7-15.6,11S248.656,111.506,256.056,112.706z" />
            <path d="M473.256,209.006c-8.9-52.2-33.5-99.7-71.3-137.5s-85.3-62.4-137.5-71.3c-7.3-1.3-14.2,3.7-15.5,11c-1.2,7.4,3.7,14.3,11.1,15.6c46.6,7.9,89.1,30,122.9,63.7c33.8,33.8,55.8,76.3,63.7,122.9c1.1,6.6,6.8,11.2,13.3,11.2c0.8,0,1.5-0.1,2.3-0.2C469.556,223.306,474.556,216.306,473.256,209.006z" />
          </svg>
        </a>
        <button class="icon-button" type="button" data-open-search aria-label="Mở tìm kiếm">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M10.5 4a6.5 6.5 0 1 0 4.02 11.6l4.44 4.45 1.06-1.06-4.45-4.44A6.5 6.5 0 0 0 10.5 4Zm0 1.5a5 5 0 1 1 0 10 5 5 0 0 1 0-10Z" fill="currentColor" />
          </svg>
        </button>
      </div>

      <a class="site-logo" href="${homeSection("hero")}" aria-label="HOVI Việt Nam">
        <img src="${sharedLogo}" alt="HOVI Việt Nam">
      </a>

      <div class="header-actions header-actions--right">
        <button class="menu-trigger" type="button" data-open-menu aria-label="Mở menu">
          <svg class="menu-icon" width="50" height="30" viewBox="0 0 50 30" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <defs>
              <linearGradient id="MenuGradientShared">
                <stop offset="0%" stop-color="#D28BE3" />
                <stop offset="20%" stop-color="#F3DBCB" />
                <stop offset="40%" stop-color="#91B2E7" />
                <stop offset="60%" stop-color="#E5DFCB" />
                <stop offset="80%" stop-color="#B9E9D1" />
                <stop offset="100%" stop-color="#D891DF" />
              </linearGradient>
            </defs>
            <path d="M2 6.5h46v3H2z" fill="url(#MenuGradientShared)" />
            <path d="M2 14h46v3H2z" fill="url(#MenuGradientShared)" />
            <path d="M2 21.5h46v3H2z" fill="url(#MenuGradientShared)" />
          </svg>
        </button>
      </div>
    </div>

    <nav class="header-nav" aria-label="Điều hướng chính">${desktopLinksHtml}
    </nav>
  </header>`;

  const footerHtml = `
    <footer class="site-footer">
      <div class="site-footer__brand">
        <div class="site-footer__brand-top">
          <img src="${sharedFooterLogo}" alt="Hovi Việt Nam" class="site-footer__logo">
          <div class="site-footer__brand-text">
            <p class="site-footer__brand-name">HOVI VIỆT NAM</p>
            <p class="site-footer__brand-since">EST 2021</p>
          </div>
        </div>
        <div class="social-badges" aria-label="Mạng xã hội">
          <a href="${contact.socialFacebook}" target="_blank" rel="noreferrer noopener" aria-label="Facebook">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 22v-8h2.7l.4-3.2h-3.1V8.8c0-.9.2-1.5 1.5-1.5H16.7V4.4c-.3 0-.9-.1-1.9-.1-3 0-4.8 1.8-4.8 5.1v1.4H7.3V14H10v8h3.5Z" fill="currentColor" /></svg>
          </a>
          <a href="${contact.socialTiktok}" target="_blank" rel="noreferrer noopener" aria-label="TikTok">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14.8 3c.3 2.1 1.5 3.8 3.6 4.2v2.7c-1.5 0-2.9-.5-4-1.5v5.3c0 3-2.2 5.3-5.4 5.3-2.9 0-5-2.1-5-4.9 0-3.1 2.4-5.2 5.6-4.9v2.8c-1.4-.2-2.8.5-2.8 2 0 1.2 1 2 2.1 2 1.5 0 2.1-1.2 2.1-2.6V3h3.8Z" fill="currentColor" /></svg>
          </a>
          <a href="${contact.socialYoutube}" target="_blank" rel="noreferrer noopener" aria-label="YouTube">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.2 7.4c-.2-1-.9-1.8-1.9-2-1.7-.4-7.3-.4-7.3-.4s-5.6 0-7.3.4c-1 .2-1.7 1-1.9 2C2.4 9.1 2.4 12 2.4 12s0 2.9.4 4.6c.2 1 .9 1.8 1.9 2 1.7.4 7.3.4 7.3.4s5.6 0 7.3-.4c1-.2 1.7-1 1.9-2 .4-1.7.4-4.6.4-4.6s0-2.9-.4-4.6ZM10.2 15.5v-7l6 3.5-6 3.5Z" fill="currentColor" /></svg>
          </a>
        </div>
      </div>

      <div class="footer-divider"></div>
      <p class="site-footer__title">CÔNG TY TNHH HOVI VIỆT NAM</p>
      <p class="site-footer__tax">MST: 2301198445</p>

      <div class="footer-grid">
        <article class="footer-item">
          <img src="${prefix}assets/icons/icon-footer-1.webp" alt="">
          <p>${contact.address}</p>
        </article>
        <article class="footer-item">
          <img src="${prefix}assets/icons/icon-footer-2.webp" alt="">
          <p><a href="${contact.websiteHref}" target="_blank" rel="noreferrer noopener">${contact.websiteLabel}</a></p>
        </article>
        <article class="footer-item">
          <img src="${prefix}assets/icons/icon-footer-3.webp" alt="">
          <p><a href="${contact.emailHref}">${contact.email}</a></p>
        </article>
        <article class="footer-item">
          <img src="${prefix}assets/icons/icon-footer-4.webp" alt="">
          <p><a href="${contact.phoneHref}">${contact.phoneDisplay}</a></p>
        </article>
      </div>
    </footer>`;

  const headerSlot = document.querySelector("[data-shared-header]");
  if (headerSlot) {
    headerSlot.outerHTML = headerHtml;
  }

  const footerSlot = document.querySelector("[data-shared-footer]");
  if (footerSlot) {
    footerSlot.outerHTML = footerHtml;
  }

  if (!page) return;

  document.querySelectorAll(`[data-page-key=\"${page}\"]`).forEach((link) => {
    if (link.classList.contains("header-nav__link")) {
      link.classList.add("header-nav__link--active");
    }
    link.setAttribute("aria-current", "page");
  });
})();
