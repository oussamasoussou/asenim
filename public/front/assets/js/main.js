/* =========================================================
   ASENIM × Laravel — utilitaires partagés
   window.ASENIM est injecté par resources/views/front/layout.blade.php
   ========================================================= */
window.asenimAsset = function (path) {
  return ((window.ASENIM && window.ASENIM.assetBase) || 'assets/') + path;
};
window.asenimRoute = function (name, fallback) {
  if (!window.ASENIM) return fallback;
  if (name === 'home') return window.ASENIM.homeUrl;
  return (window.ASENIM.routes && window.ASENIM.routes[name]) || fallback;
};
/* POST JSON vers Laravel (jeton CSRF + messages d'erreur de validation) */
window.asenimPost = function (url, formData) {
  const token = document.querySelector('meta[name="csrf-token"]');
  return fetch(url, {
    method: 'POST',
    body: formData,
    credentials: 'same-origin',
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': token ? token.content : ''
    }
  }).then(function (response) {
    return response.json().catch(function () { return {}; }).then(function (data) {
      if (response.ok) return data;
      if (response.status === 419) throw new Error("Votre session a expiré. Actualisez la page puis réessayez.");
      const errors = data.errors ? Object.values(data.errors).flat() : [];
      throw new Error(errors[0] || data.message || "Une erreur est survenue. Réessayez dans un instant.");
    });
  }, function () {
    throw new Error("Connexion impossible. Vérifiez votre accès à Internet puis réessayez.");
  });
};
/* ASENIM public */
if (!document.body.classList.contains('member-page')) {
(function() {
  "use strict";

  // =============================================
  // VARIABLES GLOBALES ET CONFIGURATION
  // =============================================
  const config = {
    scrollThreshold: 100,
    debounceDelay: 10,
    preloaderDuration: 1200,
    autoplayIntervals: {
      newsSection: 5000,
      events: 4000,
      team: 4000,
      simpleCarousel: 7000
    }
  };

  // Détection de la page d'accueil
  const isIndexPage = () => {
    const path = window.location.pathname;
    const hash = window.location.hash;
    const search = window.location.search;
    
    const cleanPath = path.replace(/[?#].*$/, '');
    if (window.ASENIM && typeof window.ASENIM.isHome === 'boolean') return window.ASENIM.isHome;
    
    return cleanPath.endsWith('index.html') || 
           cleanPath.endsWith('/') || 
           cleanPath === '' ||
           cleanPath.split('/').filter(Boolean).length === 0;
  };

  const pageIsIndex = isIndexPage();

  // Éléments DOM fréquemment utilisés
  const domElements = {
    logo: document.getElementById('logo-img'),
    navLinks: document.querySelectorAll('.navmenu li a'),
    header: document.querySelector('#header'),
    mobileToggle: document.querySelector('.mobile-nav-toggle'),
    topbar: document.getElementById('topbar'),
    scrollTop: document.querySelector('.scroll-top'),
    body: document.querySelector('body')
  };

  let isScrolled = false;
  let isMobileNavActive = false;
  let lastScrollTop = 0;

  // =============================================
  // SEUIL DYNAMIQUE : hauteur réelle de l'image .hero-slide
  // =============================================
  let heroCarouselHeight = 0;

  function getHeroCarouselHeight() {
    const heroSlide = document.querySelector('.hero .hero-slide');
    if (heroSlide && heroSlide.offsetHeight > 0) {
      return heroSlide.offsetHeight;
    }

    const activeItem = document.querySelector('.hero .carousel-item.active');
    if (activeItem && activeItem.offsetHeight > 0) {
      return activeItem.offsetHeight;
    }

    const heroSection = document.querySelector('.hero');
    if (heroSection && heroSection.offsetHeight > 0) {
      return heroSection.offsetHeight;
    }

    return window.innerHeight;
  }

  function updateHeroCarouselHeight() {
    heroCarouselHeight = getHeroCarouselHeight();
  }

  // =============================================
  // FONCTIONS UTILITAIRES
  // =============================================
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  function throttle(func, limit) {
    let inThrottle;
    return function() {
      const args = arguments;
      const context = this;
      if (!inThrottle) {
        func.apply(context, args);
        inThrottle = true;
        setTimeout(() => inThrottle = false, limit);
      }
    };
  }

  // =============================================
  // GESTION DU HEADER
  // =============================================
  function initializeHeader() {
    if (!pageIsIndex) {
      domElements.header.classList.add('scrolled');
      domElements.navLinks.forEach(link => link.classList.add('scrolled-link'));
      
      if (domElements.logo) {
        domElements.logo.src = asenimAsset('img/LOGO esim petit2.png');
      }
      
      isScrolled = true;
      
      if (domElements.topbar) {
        domElements.topbar.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
        domElements.topbar.style.transform = 'translateY(0)';
        domElements.topbar.style.opacity = '1';
      }
      
      domElements.header.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
      domElements.header.style.transform = 'translateY(0)';
      domElements.header.style.opacity = '1';
    } else {
      if (domElements.topbar) {
        domElements.topbar.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
      }
      domElements.header.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
    }
  }

  function handleScroll() {
    if (!pageIsIndex || isMobileNavActive) return;

    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    const threshold = heroCarouselHeight || getHeroCarouselHeight();
    
    if (scrollTop > threshold && !isScrolled) {
      isScrolled = true;
      changeLogo(asenimAsset('img/LOGO esim petit2.png'));
      domElements.header.classList.add('scrolled');
      domElements.navLinks.forEach(link => link.classList.add('scrolled-link'));
    } else if (scrollTop <= threshold && isScrolled) {
      isScrolled = false;
      changeLogo(asenimAsset('img/LOGO esim petit.png'));
      domElements.header.classList.remove('scrolled');
      domElements.navLinks.forEach(link => link.classList.remove('scrolled-link'));
    }

    if (scrollTop > lastScrollTop && scrollTop > config.scrollThreshold) {
      if (domElements.topbar) {
        domElements.topbar.style.transform = 'translateY(-100%)';
        domElements.topbar.style.opacity = '0';
      }
      domElements.header.style.transform = 'translateY(-100%)';
      domElements.header.style.opacity = '0';
    } else {
      if (domElements.topbar) {
        domElements.topbar.style.transform = 'translateY(0)';
        domElements.topbar.style.opacity = '1';
      }
      domElements.header.style.transform = 'translateY(0)';
      domElements.header.style.opacity = '1';
    }
    
    lastScrollTop = scrollTop;
  }

  function changeLogo(newSrc) {
    if (!domElements.logo) return;
    
    domElements.logo.style.opacity = '0';
    setTimeout(() => {
      domElements.logo.src = newSrc;
      domElements.logo.onload = () => {
        domElements.logo.style.opacity = '1';
      };
      if (domElements.logo.complete) {
        domElements.logo.style.opacity = '1';
      }
    }, 100);
  }

  // =============================================
  // NAVIGATION MOBILE
  // =============================================
  function mobileNavToggle() {
    const isActive = domElements.body.classList.toggle('mobile-nav-active');
    isMobileNavActive = isActive;
    
    if (domElements.mobileToggle) {
      domElements.mobileToggle.classList.toggle('bi-list');
      domElements.mobileToggle.classList.toggle('bi-x');
    }

    if (isActive) {
      domElements.header.style.transform = 'translateY(0)';
      domElements.header.style.opacity = '1';
      if (domElements.topbar) {
        domElements.topbar.style.transform = 'translateY(0)';
        domElements.topbar.style.opacity = '1';
      }
    } else if (pageIsIndex) {
      handleScroll();
    }
  }

  // =============================================
  // GESTION DU SCROLL
  // =============================================
  function toggleScrolled() {
    if (!domElements.header) return;
    
    const headerClasses = domElements.header.classList;
    if (!headerClasses.contains('scroll-up-sticky') && 
        !headerClasses.contains('sticky-top') && 
        !headerClasses.contains('fixed-top')) return;
    
    if (!pageIsIndex) {
      domElements.body.classList.add('scrolled');
      return;
    }

    const threshold = heroCarouselHeight || getHeroCarouselHeight();
    
    window.scrollY > threshold
      ? domElements.body.classList.add('scrolled')
      : domElements.body.classList.remove('scrolled');
  }

  function toggleScrollTop() {
    if (domElements.scrollTop) {
      window.scrollY > window.innerHeight
        ? domElements.scrollTop.classList.add('active')
        : domElements.scrollTop.classList.remove('active');
    }
  }

  // =============================================
  // INITIALISATION DES ÉVÉNEMENTS
  // =============================================
  function initEventListeners() {
    updateHeroCarouselHeight();
    window.addEventListener('resize', debounce(updateHeroCarouselHeight, 150));
    window.addEventListener('load', updateHeroCarouselHeight);

    if (pageIsIndex) {
      window.addEventListener('scroll', debounce(handleScroll, config.debounceDelay));
    }
    document.addEventListener('scroll', toggleScrolled);
    window.addEventListener('load', toggleScrolled);
    
    if (domElements.scrollTop) {
      domElements.scrollTop.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
      window.addEventListener('load', toggleScrollTop);
      document.addEventListener('scroll', toggleScrollTop);
    }
    
    if (domElements.mobileToggle) {
      domElements.mobileToggle.addEventListener('click', mobileNavToggle);
    }
    
    document.querySelectorAll('#navmenu a').forEach(navmenu => {
      navmenu.addEventListener('click', () => {
        if (document.querySelector('.mobile-nav-active')) {
          mobileNavToggle();
        }
      });
    });
    
    document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
      navmenu.addEventListener('click', function(e) {
        e.preventDefault();
        this.parentNode.classList.toggle('active');
        this.parentNode.nextElementSibling?.classList.toggle('dropdown-active');
        e.stopImmediatePropagation();
      });
    });
    
    document.querySelectorAll('.faq-item h3, .faq-item .faq-toggle').forEach(faqItem => {
      faqItem.addEventListener('click', () => {
        faqItem.parentNode.classList.toggle('faq-active');
      });
    });
    
    function navmenuScrollspy() {
      document.querySelectorAll('.navmenu a').forEach(navmenulink => {
        if (!navmenulink.hash) return;
        let section = document.querySelector(navmenulink.hash);
        if (!section) return;
        let position = window.scrollY + 200;
        if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
          document.querySelectorAll('.navmenu a.active').forEach(link => link.classList.remove('active'));
          navmenulink.classList.add('active');
        } else {
          navmenulink.classList.remove('active');
        }
      });
    }
    window.addEventListener('load', navmenuScrollspy);
    document.addEventListener('scroll', navmenuScrollspy);
  }

  // =============================================
  // INITIALISATION PRINCIPALE
  // =============================================
  function init() {
    updateHeroCarouselHeight();

    initializeHeader();
    initEventListeners();
    
    if (typeof AOS !== 'undefined') {
      AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true,
        mirror: false
      });
    }
    
    if (typeof GLightbox !== 'undefined') {
      const glightbox = GLightbox({
        selector: '.glightbox'
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  window.mobileNavToggle = mobileNavToggle;
})();

// =============================================
// PRELOADER
// =============================================
document.addEventListener("DOMContentLoaded", () => {
  const preloaderHTML = `
    <div id="preloader">
      <img src="${asenimAsset('img/logo6.png')}" alt="ASENIM" class="preloader-logo" />
    </div>`;
  
  document.body.insertAdjacentHTML("afterbegin", preloaderHTML);

  window.addEventListener("load", () => {
    const preloader = document.getElementById("preloader");
    if (!preloader) return;

    setTimeout(() => document.body.classList.add("loaded"), 400);

    setTimeout(() => {
      preloader.style.display = "none";
    }, 1200);
  });
});

// =============================================
// GESTION DES CARROUSELS
// =============================================
document.addEventListener('DOMContentLoaded', function() {
  
  function initNewsSectionCarousel() {
    const newsSectionSwiper = document.querySelector('.news-section-swiper');
    if (!newsSectionSwiper) return;
    
    if (typeof Swiper === 'undefined') {
      console.warn('Swiper n\'est pas chargé');
      return;
    }

    const newsSwiper = new Swiper('.news-section-swiper', {
      loop: true,
      speed: 600,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      slidesPerView: 1,
      spaceBetween: 20,
      pagination: {
        el: '.news-section-pagination',
        type: 'bullets',
        clickable: true,
      },
      navigation: {
        nextEl: '#next-news-section',
        prevEl: '#prev-news-section',
      },
      breakpoints: {
        1200: { slidesPerView: 3, spaceBetween: 20 },
        768:  { slidesPerView: 2, spaceBetween: 15 },
        500:  { slidesPerView: 2, spaceBetween: 10 },
        320:  { slidesPerView: 1, spaceBetween: 10 }
      }
    });

    newsSectionSwiper.addEventListener('mouseenter', () => newsSwiper.autoplay.stop());
    newsSectionSwiper.addEventListener('mouseleave', () => newsSwiper.autoplay.start());
  }

  function initEventsCarousel() {
    const eventsCarousel = document.querySelector('.events-carousel');
    if (!eventsCarousel) return;
    
    if (typeof Swiper === 'undefined') {
      console.warn('Swiper n\'est pas chargé');
      return;
    }

    const eventsSwiper = new Swiper('.events-carousel', {
      direction: 'vertical',
      slidesPerView: 2,
      spaceBetween: 4,
      loop: false,
      speed: 600,
      grabCursor: true,
      mousewheel: true,
      navigation: { nextEl: '#next-btn', prevEl: '#prev-btn' },
      autoplay: { delay: 4000, disableOnInteraction: false },
      breakpoints: {
        768: { slidesPerView: 2, spaceBetween: 3 },
        0:   { slidesPerView: 1, spaceBetween: 2 }
      }
    });
    
    const currentPositionEl = document.getElementById('current-position');
    const currentRangeEl = document.getElementById('current-range');
    const totalSlidesEl = document.getElementById('total-slides');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    
    function updateEventsPosition() {
      if (!currentPositionEl || !currentRangeEl) return;
      const currentSlide = eventsSwiper.activeIndex + 1;
      currentPositionEl.textContent = currentSlide;
      const visibleSlides = eventsSwiper.params.slidesPerView;
      if (eventsSwiper.slides) {
        currentRangeEl.textContent = Math.min(currentSlide + visibleSlides - 1, eventsSwiper.slides.length);
      }
      if (prevBtn) prevBtn.disabled = eventsSwiper.isBeginning;
      if (nextBtn) nextBtn.disabled = eventsSwiper.activeIndex >= eventsSwiper.slides.length - visibleSlides;
    }
    
    if (totalSlidesEl && eventsSwiper.slides) {
      totalSlidesEl.textContent = eventsSwiper.slides.length;
    }
    
    eventsSwiper.on('slideChange', updateEventsPosition);
    if (eventsSwiper.slides) updateEventsPosition();
    
    if (prevBtn) prevBtn.addEventListener('click', () => eventsSwiper.slidePrev());
    if (nextBtn) nextBtn.addEventListener('click', () => eventsSwiper.slideNext());
    
    eventsCarousel.addEventListener('mouseenter', () => eventsSwiper.autoplay.stop());
    eventsCarousel.addEventListener('mouseleave', () => eventsSwiper.autoplay.start());
  }

  function initHorizontalCarousels() {
    const newsGrid = document.getElementById('news-grid');
    if (newsGrid) {
      const newsPrev = document.getElementById('prev-news');
      const newsNext = document.getElementById('next-news');
      if (newsPrev && newsNext) {
        const scrollAmount = newsGrid.offsetWidth * 0.8;
        newsPrev.addEventListener("click", () => newsGrid.scrollBy({ left: -scrollAmount, behavior: "smooth" }));
        newsNext.addEventListener("click", () => newsGrid.scrollBy({ left:  scrollAmount, behavior: "smooth" }));
      }
    }

    const eventsGrid = document.getElementById('events-grid');
    if (eventsGrid) {
      const eventsPrev = document.getElementById('prev-events');
      const eventsNext = document.getElementById('next-events');
      if (eventsPrev && eventsNext) {
        const scrollAmount = eventsGrid.offsetWidth * 0.8;
        eventsPrev.addEventListener("click", () => eventsGrid.scrollBy({ left: -scrollAmount, behavior: "smooth" }));
        eventsNext.addEventListener("click", () => eventsGrid.scrollBy({ left:  scrollAmount, behavior: "smooth" }));
      }
    }
    
    const newsSectionGrid = document.getElementById('news-section-grid');
    if (newsSectionGrid) {
      const prevBtn = document.getElementById('prev-news-section');
      const nextBtn = document.getElementById('next-news-section');
      if (prevBtn && nextBtn) {
        const scrollAmount = newsSectionGrid.offsetWidth * 0.8;
        prevBtn.addEventListener("click", () => newsSectionGrid.scrollBy({ left: -scrollAmount, behavior: "smooth" }));
        nextBtn.addEventListener("click", () => newsSectionGrid.scrollBy({ left:  scrollAmount, behavior: "smooth" }));
      }
    }
  }

  function initClientsLogosCarousel() {
    const track = document.getElementById("clientsLogos");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");

    if (!track || !prevBtn || !nextBtn) return;

    let logos = [...track.children];
    if (logos.length === 0) return;
    
    for (let i = 0; i < 2; i++) {
      logos.forEach(l => track.appendChild(l.cloneNode(true)));
    }

    let logoWidth = 0;
    let gap = 40;
    let position = 0;
    let autoScrollSpeed = window.innerWidth < 768 ? 0.25 : 0.45;
    let autoScrollInterval;
    let dragging = false;
    let startX = 0;
    let startPos = 0;

    setTimeout(() => {
      const firstLogo = track.querySelector('.client-logo');
      if (firstLogo) {
        const style = window.getComputedStyle(firstLogo);
        logoWidth = firstLogo.offsetWidth + parseInt(style.marginLeft || 0) + parseInt(style.marginRight || 0) + gap;
      }
    }, 100);

    function autoScroll() {
        if (!dragging && logoWidth > 0) {
            position -= autoScrollSpeed;
            track.style.transition = "none";
            track.style.transform = `translateX(${position}px)`;
            const totalWidth = logoWidth * logos.length;
            if (Math.abs(position) >= totalWidth) {
                position = 0;
                track.style.transform = `translateX(0px)`;
            }
        }
    }
    function startScroll() { if (!autoScrollInterval) autoScrollInterval = setInterval(autoScroll, 20); }
    function stopScroll() { clearInterval(autoScrollInterval); autoScrollInterval = null; }
    function slide(dir) {
        stopScroll();
        if (logoWidth === 0) return;
        position += dir * logoWidth;
        track.style.transition = "transform 0.4s ease";
        track.style.transform = `translateX(${position}px)`;
        setTimeout(startScroll, 700);
    }

    prevBtn.addEventListener("click", () => slide(1));
    nextBtn.addEventListener("click", () => slide(-1));

    function startDrag(e) {
        dragging = true;
        stopScroll();
        startX = e.touches ? e.touches[0].clientX : e.clientX;
        startPos = position;
        track.classList.add("dragging");
    }
    function drag(e) {
        if (!dragging) return;
        let x = e.touches ? e.touches[0].clientX : e.clientX;
        position = startPos + (x - startX);
        track.style.transition = "none";
        track.style.transform = `translateX(${position}px)`;
    }
    function endDrag() {
        dragging = false;
        track.classList.remove("dragging");
        startScroll();
    }

    track.addEventListener("mousedown", startDrag);
    track.addEventListener("mousemove", drag);
    track.addEventListener("mouseup", endDrag);
    track.addEventListener("mouseleave", endDrag);
    track.addEventListener("touchstart", startDrag, { passive: true });
    track.addEventListener("touchmove", drag, { passive: true });
    track.addEventListener("touchend", endDrag);

    document.querySelectorAll(".client-logo img").forEach(logo => {
        logo.addEventListener("mouseenter", stopScroll);
        logo.addEventListener("mouseleave", () => !dragging && startScroll());
    });

    setTimeout(startScroll, 500);
  }

  function initTeamCarousel() {
    const teamSection = document.querySelector('#team-carousel');
    const teamWrapper = teamSection?.querySelector('.carousel-wrapper');
    const teamShell = teamSection?.querySelector('.team-carousel-shell');

    if (!teamSection || !teamWrapper) return;
    if (typeof Swiper === 'undefined') return;

    const TEAM_AUTOPLAY_DELAY = 4000;
    teamSection.style.setProperty('--team-autoplay-delay', `${TEAM_AUTOPLAY_DELAY}ms`);

    let advancing = false;

    const teamSwiper = new Swiper(teamWrapper, {
      loop: true,
      speed: 700,
      grabCursor: true,
      slidesPerView: 1,
      spaceBetween: 25,
      autoplay: false,
      pagination: { el: '#team-carousel .team-section-pagination', clickable: true },
      navigation: {
        nextEl: '#team-carousel .carousel-btn.next',
        prevEl: '#team-carousel .carousel-btn.prev',
      },
      breakpoints: {
        0:   { slidesPerView: 1, spaceBetween: 20 },
        601: { slidesPerView: 2, spaceBetween: 22 },
        901: { slidesPerView: 3, spaceBetween: 25 }
      },
      on: {
        init(swiper) { bindProgressEnd(swiper); },
        slideChangeTransitionStart(swiper) { bindProgressEnd(swiper); },
        slideChangeTransitionEnd() { advancing = false; }
      }
    });

    function bindProgressEnd(swiper) {
      const activeBullet = teamSection.querySelector(
        '.team-section-pagination .swiper-pagination-bullet-active'
      );
      if (!activeBullet) return;
      if (activeBullet.dataset.progressBound === '1') return;
      activeBullet.dataset.progressBound = '1';

      activeBullet.addEventListener('animationend', function handler(event) {
        if (event.animationName !== 'teamCarouselProgress') return;
        activeBullet.removeEventListener('animationend', handler);
        if (advancing) return;
        advancing = true;
        swiper.slideNext();
      }, { once: true });
    }

    if (teamShell) {
      teamShell.addEventListener('mouseenter', () => teamShell.classList.add('is-autoplay-paused'));
      teamShell.addEventListener('mouseleave', () => teamShell.classList.remove('is-autoplay-paused'));
    }
  }

  function initActuCarousel() {
    const actuSection = document.querySelector('#actu');
    const actuSwiperElement = actuSection?.querySelector('.actu-swiper');
    const actuShell = actuSection?.querySelector('.actu-carousel-shell');

    if (!actuSection || !actuSwiperElement || !actuShell) return;
    if (typeof Swiper === 'undefined') return;

    const ACTU_AUTOPLAY_DELAY = 4000;
    actuSection.style.setProperty('--actu-autoplay-delay', `${ACTU_AUTOPLAY_DELAY}ms`);

    const actuSwiper = new Swiper(actuSwiperElement, {
      loop: true,
      speed: 700,
      grabCursor: true,
      slidesPerView: 1,
      slidesPerGroup: 1,
      spaceBetween: 13,
      watchSlidesProgress: true,
      autoplay: { delay: ACTU_AUTOPLAY_DELAY, disableOnInteraction: false, pauseOnMouseEnter: false },
      pagination: {
        el: actuSection.querySelector('.actu-pagination'),
        type: 'bullets',
        clickable: true,
        dynamicBullets: false,
        renderBullet(index, className) {
          return `<span class="${className}" role="button" aria-label="Aller à l'actualité ${index + 1}"></span>`;
        }
      },
      navigation: {
        nextEl: actuSection.querySelector('.actu-carousel-btn.next'),
        prevEl: actuSection.querySelector('.actu-carousel-btn.prev')
      },
      breakpoints: {
        0:   { slidesPerView: 1, spaceBetween: 15 },
        601: { slidesPerView: 2, spaceBetween: 15 },
        951: { slidesPerView: 3, spaceBetween: 13 }
      },
      on: {
        init() { actuShell.classList.remove('is-autoplay-paused'); },
        autoplayPause() { actuShell.classList.add('is-autoplay-paused'); },
        autoplayResume() { actuShell.classList.remove('is-autoplay-paused'); },
        autoplayStart() { actuShell.classList.remove('is-autoplay-paused'); }
      }
    });

    actuShell.addEventListener('mouseenter', () => {
      actuShell.classList.add('is-autoplay-paused');
      if (!actuSwiper.autoplay) return;
      if (typeof actuSwiper.autoplay.pause === 'function') actuSwiper.autoplay.pause();
      else if (typeof actuSwiper.autoplay.stop === 'function') actuSwiper.autoplay.stop();
    });

    actuShell.addEventListener('mouseleave', () => {
      actuShell.classList.remove('is-autoplay-paused');
      if (!actuSwiper.autoplay) return;
      if (typeof actuSwiper.autoplay.resume === 'function') actuSwiper.autoplay.resume();
      else if (typeof actuSwiper.autoplay.start === 'function') actuSwiper.autoplay.start();
    });
  }

  function initModalAndSearch() {
    const backdrop = document.getElementById('modal-backdrop');
    if (!backdrop) return;

    const mtitle = document.getElementById('modal-title');
    const msub = document.getElementById('modal-sub');
    const mbody = document.getElementById('modal-body');
    const modalClose = document.getElementById('modal-close');
    
    function openModal(title, date, desc) {
      if (mtitle) mtitle.textContent = title;
      if (msub) msub.textContent = date;
      if (mbody) mbody.textContent = desc;
      backdrop.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
    
    function openModalFromCard(btn) {
      const card = btn.closest('.card') || btn.closest('.slide');
      if (!card) return;
      const title = card.querySelector('h4') ? card.querySelector('h4').innerText : 
                   card.querySelector('h3') ? card.querySelector('h3').innerText : 'Title';
      const date = card.querySelector('.pill') ? card.querySelector('.pill').innerText : 'Date not set';
      const desc = card.querySelector('p') ? card.querySelector('p').innerText : 'Description not available';
      openModal(title, date, desc);
    }
    
    function closeModal() {
      backdrop.style.display = 'none';
      document.body.style.overflow = '';
    }

    if (modalClose) modalClose.addEventListener('click', closeModal);
    backdrop.addEventListener('click', (e) => { if (e.target === backdrop) closeModal(); });

    window.openModal = openModal;
    window.openModalFromCard = openModalFromCard;
    window.closeModal = closeModal;

    const searchInput = document.getElementById('search');
    if (searchInput) {
      searchInput.addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase().trim();
        document.querySelectorAll('.grid .card').forEach(card => {
          const title = card.querySelector('h4');
          const paragraph = card.querySelector('p');
          if (title && paragraph) {
            const t = title.innerText.toLowerCase();
            const p = paragraph.innerText.toLowerCase();
            card.style.display = (t.includes(q) || p.includes(q)) ? 'block' : 'none';
          }
        });
      });
    }

    window.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
  }

  function initTabs() {
    document.querySelectorAll('.tab').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        const target = btn.getAttribute('data-target');
        if (!target) return;

        document.querySelectorAll('.panel').forEach(panel => {
          if (panel.id !== target && panel.style.display === "block") {
            panel.classList.remove('animate__animated', 'animate__fadeIn');
            panel.classList.add('animate__animated', 'animate__fadeOut');
            setTimeout(() => {
              panel.style.display = 'none';
              panel.classList.remove('animate__fadeOut');
            }, 300);
          }
        });

        const activePanel = document.getElementById(target);
        if (activePanel) {
          activePanel.style.display = 'block';
          activePanel.classList.remove('animate__fadeOut');
          activePanel.classList.add('animate__animated', 'animate__fadeIn');
          setTimeout(() => { if (typeof AOS !== 'undefined') AOS.refresh(); }, 200);
        }
      });
    });
  }

  function initSimpleCarousel() {
    const carousel = document.getElementById('carousel');
    if (!carousel) return;

    const nextBtn = document.getElementById('next');
    const prevBtn = document.getElementById('prev');
    
    if (nextBtn) nextBtn.addEventListener('click', () => carousel.scrollBy({ left: carousel.clientWidth * 0.7, behavior: 'smooth' }));
    if (prevBtn) prevBtn.addEventListener('click', () => carousel.scrollBy({ left: -carousel.clientWidth * 0.7, behavior: 'smooth' }));

    let auto = setInterval(() => {
      carousel.scrollBy({ left: carousel.clientWidth * 0.7, behavior: 'smooth' });
    }, 7000);
    
    carousel.addEventListener('mouseenter', () => clearInterval(auto));
    carousel.addEventListener('mouseleave', () => {
      auto = setInterval(() => {
        carousel.scrollBy({ left: carousel.clientWidth * 0.7, behavior: 'smooth' });
      }, 7000);
    });

    window.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowRight' && nextBtn) nextBtn.click();
      if (e.key === 'ArrowLeft' && prevBtn) prevBtn.click();
    });
  }

  function initTopbarResponsive() {
    const topbar = document.querySelector('.topbar');
    if (!topbar) return;

    function handleResize() {
      if (window.innerWidth < 992) topbar.classList.add('hide-topbar');
      else topbar.classList.remove('hide-topbar');
    }

    window.addEventListener('resize', debounce(handleResize, 250));
    handleResize();
  }

  function initAllCarousels() {
    initNewsSectionCarousel();
    initEventsCarousel();
    initHorizontalCarousels();
    initClientsLogosCarousel();
    initTeamCarousel();
    initActuCarousel();
    initSimpleCarousel();
  }

  function initComplete() {
    initAllCarousels();
    initModalAndSearch();
    initTabs();
    initTopbarResponsive();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initComplete);
  } else {
    initComplete();
  }
});

// =============================================
// GESTION DU MENU OVERLAY - AVEC SCROLL
// =============================================
document.addEventListener('DOMContentLoaded', function() {
  const menuOverlay   = document.getElementById('menu-overlay');
  const hamburgerIcon = document.querySelector('.white-hamburger');
  const closeMenuBtn  = document.getElementById('close-menu');

  if (!menuOverlay || !hamburgerIcon) return;

  let isMenuOpen = false;
  let animationInProgress = false;

  function disableBodyScroll() {
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
  }

  function enableBodyScroll() {
    document.body.style.overflow = '';
    document.documentElement.style.overflow = '';
  }

  function openMenu() {
    if (animationInProgress || isMenuOpen) return;
    animationInProgress = true;
    isMenuOpen = true;
    disableBodyScroll();
    menuOverlay.scrollTop = 0;
    transformToX();
    menuOverlay.classList.add('active');
    document.body.classList.add('mobile-menu-open');
    setTimeout(() => { animationInProgress = false; }, 500);
  }

  function closeMenu() {
    if (animationInProgress || !isMenuOpen) return;
    animationInProgress = true;
    transformToHamburger();
    menuOverlay.classList.add('closing');
    menuOverlay.classList.remove('active');
    setTimeout(() => {
      menuOverlay.classList.remove('closing');
      enableBodyScroll();
      document.body.classList.remove('mobile-menu-open');
      isMenuOpen = false;
      animationInProgress = false;
    }, 500);
  }

  function toggleMenu() {
    if (isMenuOpen) closeMenu();
    else openMenu();
  }

  function transformToX() {
    hamburgerIcon.classList.add('hamburger-to-x');
    const spans = hamburgerIcon.querySelectorAll('span');
    if (spans.length) {
      spans.forEach(function (span) {
        span.style.transform = '';
        span.style.opacity = '';
      });
    }
    hamburgerIcon.setAttribute('aria-expanded', 'true');
    hamburgerIcon.setAttribute('aria-label', 'Fermer le menu');
  }

  function transformToHamburger() {
    hamburgerIcon.classList.remove('hamburger-to-x');
    const spans = hamburgerIcon.querySelectorAll('span');
    if (spans.length) {
      spans.forEach(function (span) {
        span.style.transform = '';
        span.style.opacity = '';
      });
    }
    hamburgerIcon.setAttribute('aria-expanded', 'false');
    hamburgerIcon.setAttribute('aria-label', 'Ouvrir le menu');
  }

  hamburgerIcon.addEventListener('click', function(e) {
    e.stopPropagation();
    e.preventDefault();
    toggleMenu();
  });

  if (closeMenuBtn) {
    closeMenuBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      e.preventDefault();
      closeMenu();
    });
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && isMenuOpen) closeMenu();
  });

  menuOverlay.querySelectorAll('a').forEach(function(link) {
    link.addEventListener('click', function() { closeMenu(); });
  });

  menuOverlay.addEventListener('touchmove', function(e) {
    if (!isMenuOpen) return;
    const isScrollingMenu = e.target.closest('.menu-overlay');
    if (isScrollingMenu) return;
    e.preventDefault();
  }, { passive: false });

  let scrollTimeout;
  menuOverlay.addEventListener('scroll', function() {
    if (!isMenuOpen) return;
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(() => {
      if (menuOverlay.scrollTop < 0) menuOverlay.scrollTop = 0;
    }, 100);
  });

  document.addEventListener('wheel', function(e) {
    if (isMenuOpen && !e.target.closest('.menu-overlay')) e.preventDefault();
  }, { passive: false });

  /* =========================================================
     EXPOSITION GLOBALE DE closeMenu
     ---------------------------------------------------------
     Permet à d'autres scripts (ex. modal auth) de fermer
     proprement le menu overlay depuis l'extérieur, en
     respectant l'état interne (isMenuOpen, animationInProgress,
     body scroll, classes, etc.).
     ========================================================= */
  window.closeAsenimMenuOverlay = closeMenu;
  window.isAsenimMenuOpen = function () { return isMenuOpen; };
});

/* =============================================
   FORMULAIRE – AJOUT OPTION
============================================= */
document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('addOptionBtn');
  const field = document.getElementById('extraOptionGroup');
  
  if (btn && field) {
    field.style.display = 'none';
    
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      
      if (field.style.display === 'none') {
        field.style.display = 'block';
        setTimeout(() => { field.style.opacity = '1'; }, 10);
        const input = field.querySelector('input');
        if (input) input.focus();
      } else {
        field.style.opacity = '0';
        setTimeout(() => {
          field.style.display = 'none';
          const input = field.querySelector('input');
          if (input) input.value = '';
        }, 300);
      }
    });
  }
});

document.addEventListener('DOMContentLoaded', function() {
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  const eyeIcon = togglePassword?.querySelector('svg');

  if (!togglePassword || !passwordInput || !eyeIcon) return;
  
  const eyeOpenSVG = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
  const eyeClosedSVG = '<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>';
  
  togglePassword.addEventListener('click', function() {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    if (type === 'text') {
      eyeIcon.innerHTML = eyeOpenSVG;
      togglePassword.setAttribute('aria-label', 'Masquer le mot de passe');
    } else {
      eyeIcon.innerHTML = eyeClosedSVG;
      togglePassword.setAttribute('aria-label', 'Afficher le mot de passe');
    }
    
    passwordInput.focus();
  });
});

// =============================================
// ANIMATIONS DU MENU OVERLAY
// =============================================
function initMenuAnimations() {
  const menuOverlay = document.getElementById('menu-overlay');
  if (!menuOverlay) return;
  
  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      if (mutation.attributeName === 'class') {
        if (menuOverlay.classList.contains('active')) {
          animateMenuItems();
        }
      }
    });
  });
  
  observer.observe(menuOverlay, { attributes: true });
  
  function animateMenuItems() {
    const menuItems = document.querySelectorAll('.menu-column a');
    menuItems.forEach((item, index) => {
      item.style.opacity = '0';
      item.style.transform = 'translateX(-20px)';
      setTimeout(() => {
        item.style.transition = 'opacity 0.9s ease, transform 0.9s ease';
        item.style.opacity = '1';
        item.style.transform = 'translateX(0)';
      }, 100 + (index * 100));
    });
  }
}

const userDropdown = document.querySelector('.user-dropdown');

if (userDropdown) {
  userDropdown.classList.add('asenim-auth-ready');
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initMenuAnimations);
} else {
  initMenuAnimations();
}

document.addEventListener('DOMContentLoaded', function() {
  const scrollArrow = document.getElementById('scrollDownArrow');
  if (scrollArrow) {
    scrollArrow.addEventListener('click', function(e) {
      e.preventDefault();
      const heroSection = document.getElementById('hero');
      if (heroSection) {
        const heroHeight = heroSection.offsetHeight;
        window.scrollBy({ top: heroHeight, behavior: 'smooth' });
      }
    });
  }
});

document.addEventListener('DOMContentLoaded', function () {
  function ensureUsmMobileNav() {
    let nav = document.getElementById('usm-mobile-nav');
    if (nav) return nav;

    nav = document.createElement('nav');
    nav.className = 'usm-mobile-nav';
    nav.id = 'usm-mobile-nav';
    nav.setAttribute('aria-label', 'Navigation mobile');

    nav.innerHTML = `
      <div class="usm-mobile-nav__items">
        <a class="usm-mobile-nav__item" href="${asenimRoute('home', 'index.html')}" data-usm-nav="home" aria-label="Accueil">
          <span class="usm-mobile-nav__icon"><i class="bi bi-house"></i></span>
          <span class="usm-mobile-nav__label">Accueil</span>
        </a>
		
        <a class="usm-mobile-nav__item" href="${asenimRoute('about', 'Qui sommes-nous.html')}" data-usm-nav="about" aria-label="Accueil">
          <span class="usm-mobile-nav__icon"><img src="${asenimAsset('img/asenimlogo4.png')}" class="ase-icon" alt=""></span>
          <span class="usm-mobile-nav__label">Qui sommes-nous</span>
        </a>
		
        <a class="usm-mobile-nav__item" href="${asenimRoute('bureau', 'Bureau exécutif.html')}" data-usm-nav="matches" aria-label="Bureau exécutif">
          <span class="usm-mobile-nav__icon"><i class="bi bi-people"></i></span>
          <span class="usm-mobile-nav__label">Bureau exécutif</span>
        </a>

        <a class="usm-mobile-nav__item" href="${asenimRoute('news', 'Actualités.html')}" data-usm-nav="news" aria-label="Actualités">
          <span class="usm-mobile-nav__icon"><i class="bi bi-newspaper"></i></span>
          <span class="usm-mobile-nav__label">Actualités</span>
        </a>

        <button type="button" class="usm-mobile-nav__item usm-mobile-nav__more" id="usm-mobile-more"
                data-usm-nav="more" aria-label="Plus" aria-controls="menu-overlay" aria-expanded="false">
          <span class="usm-mobile-nav__icon usm-mobile-nav__more-icon" aria-hidden="true">
            <span></span><span></span><span></span>
          </span>
          <span class="usm-mobile-nav__label">Plus</span>
        </button>
      </div>`;

    const scrollTop = document.getElementById('scroll-top');
    if (scrollTop && scrollTop.parentNode) {
      scrollTop.parentNode.insertBefore(nav, scrollTop);
    } else {
      document.body.appendChild(nav);
    }

    return nav;
  }

  const mobileNav = ensureUsmMobileNav();
  const moreButton = document.getElementById('usm-mobile-more');
  const overlay = document.getElementById('menu-overlay');
  const closeButton = document.getElementById('close-menu');

  const current = window.location.pathname.replace(/\/+$/, '') || '/';

  mobileNav.querySelectorAll('[data-usm-nav]').forEach(function (item) {
    item.classList.remove('is-active');
    if (!item.getAttribute('href')) return;
    const target = (new URL(item.href, window.location.href).pathname).replace(/\/+$/, '') || '/';
    if (target === current) {
      item.classList.add('is-active');
    }
  });

  const newsPath = window.ASENIM ? (new URL(window.ASENIM.routes.news, window.location.href).pathname) : '/actualites';
  if (!mobileNav.querySelector('.is-active') && current.indexOf(newsPath) === 0) {
    const newsItem = mobileNav.querySelector('[data-usm-nav="news"]');
    if (newsItem) newsItem.classList.add('is-active');
  }

  function openMore() {
    if (!overlay) return;
    overlay.classList.remove('closing');
    overlay.classList.add('active');
    document.body.classList.add('mobile-menu-open');
    if (moreButton) moreButton.setAttribute('aria-expanded', 'true');
  }

  function closeMore() {
    if (!overlay) return;
    overlay.classList.add('closing');
    overlay.classList.remove('active');
    window.setTimeout(function () { overlay.classList.remove('closing'); }, 500);
    document.body.classList.remove('mobile-menu-open');
    if (moreButton) moreButton.setAttribute('aria-expanded', 'false');
  }

  if (moreButton) moreButton.addEventListener('click', function (event) {
    event.preventDefault();
    if (overlay && overlay.classList.contains('active')) closeMore();
    else openMore();
  });

  if (closeButton) closeButton.addEventListener('click', closeMore);

  if (overlay) {
    overlay.addEventListener('click', function (event) {
      if (event.target === overlay) closeMore();
    });
  }

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && overlay && overlay.classList.contains('active')) closeMore();
  });
});

/* =========================================================
   ASENIM — COMPTEUR ANIMÉ UNIVERSEL
   ========================================================= */
document.addEventListener('DOMContentLoaded', function () {
  const counters = document.querySelectorAll('.counter[data-counter-target]');
  if (!counters.length) return;

  function formatNumber(value, isRaw) {
    if (isRaw) return String(value);
    return String(value).replace(/\B(?=(\d{3})+(?!\d))/g, '\u202F');
  }

  function easeOutExpo(t) {
    return t >= 1 ? 1 : 1 - Math.pow(2, -10 * t);
  }

  function animateCounter(el) {
    const target   = parseFloat(el.dataset.counterTarget || '0');
    const prefix   = el.dataset.counterPrefix || '';
    const suffix   = el.dataset.counterSuffix || '';
    const duration = parseFloat(el.dataset.counterDuration || '2000');
    const isRaw    = el.dataset.counterRaw === 'true';

    el.textContent = prefix + formatNumber(0, isRaw);
    el.classList.remove('is-done');
    el.classList.add('is-counting');

    const startTime = performance.now();

    function frame(now) {
      const elapsed  = now - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const eased    = easeOutExpo(progress);
      const current  = Math.round(eased * target);

      el.textContent = prefix + formatNumber(current, isRaw);

      if (progress < 1) {
        requestAnimationFrame(frame);
      } else {
        el.textContent = prefix + formatNumber(target, isRaw);
        el.classList.remove('is-counting');
        el.classList.add('is-done');

        setTimeout(function () {
          el.classList.remove('is-done');
        }, 900);
      }
    }

    requestAnimationFrame(frame);
  }

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        observer.unobserve(entry.target);
        animateCounter(entry.target);
      }
    });
  }, {
    threshold: 0.4,
    rootMargin: '0px 0px -8% 0px'
  });

  counters.forEach(function (counter) {
    const prefix = counter.dataset.counterPrefix || '';
    const isRaw  = counter.dataset.counterRaw === 'true';
    counter.textContent = prefix + formatNumber(0, isRaw);
    observer.observe(counter);
  });
});

(function () {
  var toggles = document.querySelectorAll('.who-tile__toggle');
  var section = document.querySelector('.who-mosaic-section');

  function syncHasOpenPanel() {
    if (!section) return;
    var hasOpen = document.querySelector('.who-tile__expand.is-open') !== null;
    section.classList.toggle('has-open-panel', hasOpen);
  }

  function closePanel(tile, link, panel) {
    if (!tile || !panel || !link) return;
    tile.classList.remove('is-expanded');
    panel.classList.remove('is-open');
    panel.setAttribute('aria-hidden', 'true');
    link.setAttribute('aria-expanded', 'false');
    link.textContent = 'Lire la suite';
    syncHasOpenPanel();
  }

  function openPanel(tile, link, panel) {
    if (!tile || !panel || !link) return;
    tile.classList.add('is-expanded');
    panel.classList.add('is-open');
    panel.setAttribute('aria-hidden', 'false');
    link.setAttribute('aria-expanded', 'true');
    link.textContent = 'Réduire';
    syncHasOpenPanel();
  }

  function closeAllPanels() {
    document
      .querySelectorAll('.who-tile__expand.is-open')
      .forEach(function (panel) {
        var tile = panel.closest('.who-tile');
        var link = tile ? tile.querySelector('.who-tile__toggle') : null;
        closePanel(tile, link, panel);
      });
  }

  toggles.forEach(function (link) {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      var tile  = link.closest('.who-tile');
      var panel = tile ? tile.querySelector('.who-tile__expand') : null;
      if (!tile || !panel) return;

      var isCurrentlyOpen = panel.classList.contains('is-open');

      document
        .querySelectorAll('.who-tile__expand.is-open')
        .forEach(function (openPanel) {
          if (openPanel === panel) return;
          var otherTile = openPanel.closest('.who-tile');
          var otherLink = otherTile ? otherTile.querySelector('.who-tile__toggle') : null;
          closePanel(otherTile, otherLink, openPanel);
        });

      if (isCurrentlyOpen) {
        closePanel(tile, link, panel);
      } else {
        openPanel(tile, link, panel);
      }
    });
  });

  document.addEventListener('click', function (e) {
    var openPanel = document.querySelector('.who-tile__expand.is-open');
    if (!openPanel) return;

    var tile   = openPanel.closest('.who-tile');
    var toggle = tile ? tile.querySelector('.who-tile__toggle') : null;

    if (openPanel.contains(e.target)) return;
    if (toggle && toggle.contains(e.target)) return;

    closeAllPanels();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape' && e.key !== 'Esc') return;
    closeAllPanels();
  });

  function openFromHash() {
    var hash = window.location.hash;
    if (!hash) return;

    var panel = document.querySelector(hash);
    if (!panel || !panel.classList.contains('who-tile__expand')) return;

    var tile = panel.closest('.who-tile');
    var link = tile ? tile.querySelector('.who-tile__toggle') : null;
    if (!tile || !link) return;

    closeAllPanels();
    openPanel(tile, link, panel);

    setTimeout(function () {
      panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 120);
  }

  window.addEventListener('load', function () {
    openFromHash();
    syncHasOpenPanel();
  });

  window.addEventListener('hashchange', openFromHash);

})();

/* =========================================================
   ASENIM — MODAL AUTHENTIFICATION / INSCRIPTION
   ---------------------------------------------------------
   Deux vues séparées avec animation de bascule fluide :
   - "Se connecter"      (panneau bleu)
   - "Créer un compte"   (panneau vert)

   À CHAQUE OUVERTURE du modal :
   → le menu overlay est automatiquement fermé
     (via window.closeAsenimMenuOverlay exposé par le menu)
   → body scroll déverrouillé proprement
   → le modal repart toujours sur la vue "Se connecter"
   ========================================================= */
(function initAsenimAuthModal(){
  "use strict";

  function build(){
    if(document.getElementById("asenimAuthModal")) return;
    const modal=document.createElement("div");
    modal.id="asenimAuthModal";
    modal.setAttribute("aria-hidden","true");
    modal.setAttribute("role","dialog");
    modal.setAttribute("aria-modal","true");
    modal.setAttribute("aria-labelledby","asenimAuthTitle");
    modal.innerHTML=`
      <div class="asenim-auth-dialog" role="document">
        <div class="asenim-auth-head">
          <div class="asenim-auth-brand">
            <img src="${asenimAsset('img/logo6.png')}" alt="Logo ASENIM">
            <span>Espace membre ASENIM</span>
          </div>
          <button type="button" class="asenim-auth-close" id="asenimAuthClose" aria-label="Fermer">
            <i class="fas fa-xmark" aria-hidden="true"></i>
          </button>
        </div>
        <div class="asenim-auth-body">
          <section class="asenim-auth-panel asenim-auth-panel--login" aria-labelledby="asenimAuthTitle">
            <div class="asenim-auth-accent" aria-hidden="true"></div>
            <h2 id="asenimAuthTitle">Se connecter</h2>
            <p class="asenim-auth-intro">Accédez à votre espace membre et retrouvez votre communauté ASENIM.</p>
            <form class="asenim-auth-form" id="asenimLoginForm" novalidate>
              <div class="asenim-auth-field">
                <label for="asenimLoginEmail">Adresse e-mail</label>
                <input id="asenimLoginEmail" name="email" type="email" autocomplete="email" placeholder="exemple@email.com" required>
              </div>
              <div class="asenim-auth-field asenim-auth-password">
                <label for="asenimLoginPassword">Mot de passe</label>
                <input id="asenimLoginPassword" name="password" type="password" autocomplete="current-password" placeholder="Votre mot de passe" required>
                <button type="button" class="asenim-auth-password-toggle" data-password-target="asenimLoginPassword" aria-label="Afficher le mot de passe">
                  <i class="fas fa-eye" aria-hidden="true"></i>
                </button>
              </div>
              <div class="asenim-auth-options">
                <label class="asenim-auth-check"><input type="checkbox" name="remember"><span>Se souvenir de moi</span></label>
                <button type="button" class="asenim-auth-link" data-auth-focus="asenimLoginEmail">Mot de passe oublié ?</button>
              </div>
              <div class="asenim-auth-status" id="asenimLoginStatus" role="status"></div>
              <button class="asenim-auth-submit" type="submit">SE CONNECTER</button>
              <button type="button" class="asenim-auth-link" data-auth-focus="asenimCreateAccount">Créer un compte</button>
            </form>
          </section>
          <section class="asenim-auth-panel asenim-auth-panel--register" aria-labelledby="asenimRegisterTitle">
            <div class="asenim-auth-accent" aria-hidden="true"></div>
            <h2 id="asenimRegisterTitle">Créer un compte</h2>
            <p class="asenim-auth-intro">Rejoignez le réseau des diplômés et la communauté ASENIM.</p>
            <form class="asenim-auth-form" id="asenimRegisterForm" novalidate>
              <div class="asenim-auth-field">
                <label for="asenimRegisterName">Nom et prénom</label>
                <input id="asenimRegisterName" name="name" type="text" autocomplete="name" placeholder="Votre nom et prénom" required>
              </div>
              <div class="asenim-auth-field">
                <label for="asenimRegisterEmail">Adresse e-mail</label>
                <input id="asenimRegisterEmail" name="email" type="email" autocomplete="email" placeholder="exemple@email.com" required>
              </div>
              <div class="asenim-auth-field asenim-auth-password">
                <label for="asenimRegisterPassword">Mot de passe</label>
                <input id="asenimRegisterPassword" name="password" type="password" autocomplete="new-password" placeholder="8 caractères minimum" minlength="8" required>
                <button type="button" class="asenim-auth-password-toggle" data-password-target="asenimRegisterPassword" aria-label="Afficher le mot de passe">
                  <i class="fas fa-eye" aria-hidden="true"></i>
                </button>
              </div>
              <div class="asenim-auth-field asenim-auth-password">
                <label for="asenimRegisterPasswordConfirm">Confirmer le mot de passe</label>
                <input id="asenimRegisterPasswordConfirm" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Confirmez le mot de passe" minlength="8" required>
                <button type="button" class="asenim-auth-password-toggle" data-password-target="asenimRegisterPasswordConfirm" aria-label="Afficher le mot de passe">
                  <i class="fas fa-eye" aria-hidden="true"></i>
                </button>
              </div>
              <div class="asenim-auth-status" id="asenimRegisterStatus" role="status"></div>
              <button class="asenim-auth-submit" type="submit">CRÉER MON COMPTE</button>
              <button type="button" class="asenim-auth-link" data-auth-focus="asenimConnect">Se connecter</button>
            </form>
          </section>
        </div>
      </div>`;
    document.body.appendChild(modal);
  }

  /* =========================================================
     FERMETURE SYSTÉMATIQUE DU MENU OVERLAY
     ---------------------------------------------------------
     Appelée à CHAQUE ouverture du modal auth.
     Utilise window.closeAsenimMenuOverlay exposé par le script
     du menu overlay pour respecter son état interne
     (isMenuOpen, animationInProgress, body scroll, classes…).
     Fallback : fermeture manuelle si la fonction n'est pas dispo.
     ========================================================= */
  function closeMenuOverlaySystematically() {
    // Cas idéal : la fonction officielle est exposée
    if (typeof window.closeAsenimMenuOverlay === "function") {
      try {
        window.closeAsenimMenuOverlay();
      } catch (err) {
        // En cas de pépin, on continue vers le fallback ci-dessous
      }
    }

    // Fallback manuel (au cas où la fonction officielle ne serait pas dispo)
    const overlay = document.getElementById("menu-overlay");
    if (overlay && overlay.classList.contains("active")) {
      overlay.classList.add("closing");
      overlay.classList.remove("active");
      window.setTimeout(function () {
        overlay.classList.remove("closing");
      }, 500);
    }

    // Nettoyage du state global body / html (scroll déverrouillé)
    document.body.classList.remove("mobile-menu-open");
    document.documentElement.style.overflow = "";
    document.body.style.overflow = "";

    // Restaure l'état du hamburger (retire la croix)
    const hamburger = document.querySelector(".white-hamburger");
    if (hamburger) {
      hamburger.classList.remove("hamburger-to-x");
      hamburger.setAttribute("aria-expanded", "false");
      hamburger.setAttribute("aria-label", "Ouvrir le menu");
    }
  }

  /* -------- Ouverture : toujours sur "Se connecter" -------- */
  function openModal(){
    build();
    const modal = document.getElementById("asenimAuthModal");
    if (!modal) return;

    /* 1) Fermer le menu overlay avant tout */
    closeMenuOverlaySystematically();

    /* 2) Repartir systématiquement sur la vue "Se connecter" */
    modal.classList.remove("is-register-mode");

    /* 3) Ouvrir le modal */
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden","false");
    document.body.classList.add("asenim-auth-modal-open");
    document.body.style.overflow = "hidden";

    /* Toujours ouvrir le formulaire depuis son début, même sur écran peu haut */
    const authBody = modal.querySelector(".asenim-auth-body");
    if (authBody) authBody.scrollTop = 0;
    modal.scrollTop = 0;

    /* 4) Focus sur le premier champ après l'animation d'ouverture */
    setTimeout(()=>document.getElementById("asenimLoginEmail")?.focus(),280);
  }

  function closeModal(){
    const modal=document.getElementById("asenimAuthModal");
    if(!modal) return;
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden","true");
    document.body.classList.remove("asenim-auth-modal-open");
    document.body.style.overflow="";
  }

  function status(id,msg){
    const el=document.getElementById(id);
    if(el){el.textContent=msg;el.classList.remove("is-error","is-success");el.classList.add("is-visible");}
  }

  /* Envoi AJAX vers Laravel (connexion / inscription) */
  function asenimSubmit(form, url, statusId, onSuccess){
    const button = form.querySelector('button[type="submit"]');
    status(statusId, "Envoi en cours…");
    if (button) button.disabled = true;
    asenimPost(url, new FormData(form))
      .then(onSuccess)
      .catch(function (err) {
        status(statusId, err.message);
        document.getElementById(statusId)?.classList.add("is-error");
      })
      .finally(function () { if (button) button.disabled = false; });
  }

  function clearStatus(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.textContent = "";
    el.classList.remove("is-visible");
  }

  /* =========================================================
     BASCULE ANIMÉE ENTRE LES DEUX VUES
     ========================================================= */
  function switchAuthView(targetMode) {
    const modal = document.getElementById("asenimAuthModal");
    if (!modal) return;

    const isCurrentlyRegister = modal.classList.contains("is-register-mode");
    const targetIsRegister = targetMode === "register";

    if (isCurrentlyRegister === targetIsRegister) return;
    if (modal.dataset.switching === "1") return;
    modal.dataset.switching = "1";

    clearStatus("asenimLoginStatus");
    clearStatus("asenimRegisterStatus");

    const body = modal.querySelector(".asenim-auth-body");
    const currentPanel = modal.querySelector(
      isCurrentlyRegister ? ".asenim-auth-panel--register" : ".asenim-auth-panel--login"
    );
    const nextPanel = modal.querySelector(
      targetIsRegister ? ".asenim-auth-panel--register" : ".asenim-auth-panel--login"
    );

    if (!body || !currentPanel || !nextPanel) {
      modal.classList.toggle("is-register-mode", targetIsRegister);
      modal.dataset.switching = "0";
      return;
    }

    const startHeight = body.getBoundingClientRect().height;

    currentPanel.classList.add("is-leaving");

    setTimeout(() => {
      currentPanel.classList.remove("is-leaving");

      modal.classList.toggle("is-register-mode", targetIsRegister);

      const endHeight = body.getBoundingClientRect().height;

      body.style.height = startHeight + "px";
      void body.offsetHeight;
      body.style.height = endHeight + "px";

      nextPanel.classList.add("is-entering");

      setTimeout(() => {
        body.style.height = "";
        nextPanel.classList.remove("is-entering");

        /* La nouvelle vue repart en haut du formulaire */
        body.scrollTop = 0;

        const focusId = targetIsRegister ? "asenimRegisterName" : "asenimLoginEmail";
        document.getElementById(focusId)?.focus();

        modal.dataset.switching = "0";
      }, 440);
    }, 240);
  }

  function switchToRegister() { switchAuthView("register"); }
  function switchToLogin()    { switchAuthView("login");    }

  function init(){
    build();

    document.addEventListener("click",function(e){
      /* -------- Ouverture via l'icône utilisateur du header -------- */
      const userButton=e.target.closest(".user-btn");
      if(userButton && window.ASENIM && window.ASENIM.authenticated){ return; }
      if(userButton){
        e.preventDefault();
        e.stopPropagation();
        userButton.closest(".user-dropdown")?.classList.add("asenim-auth-ready");
        openModal();
        return;
      }

      /* =========================================================
         OUVERTURE DU MODAL AUTH
         ---------------------------------------------------------
         • [data-auth-open]                 → bouton recommandé (index, contact…)
         • .menu-overlay .join-btn          → fallback universel
           → couvre <a> ET <button> du menu overlay sur TOUTES
             les pages (Actualités, Bureau exécutif, Galerie,
             inscription, Qui sommes-nous…)
         ========================================================= */
      const authOpenBtn = e.target.closest(
        "[data-auth-open], " +
        ".menu-overlay .join-btn, " +
        ".menu-overlay a.join-btn, " +
        ".menu-overlay button.join-btn"
      );

      if (authOpenBtn) {
        e.preventDefault();
        e.stopPropagation();
        if (window.ASENIM && window.ASENIM.authenticated) { window.location.href = window.ASENIM.dashboardUrl; return; }
        openModal();
        return;
      }

      /* -------- Fermeture du modal -------- */
      if(e.target.closest("#asenimAuthClose")){e.preventDefault();closeModal();return;}

      /* -------- Toggle visibilité mot de passe -------- */
      const toggle=e.target.closest(".asenim-auth-password-toggle");
      if(toggle){
        const input=document.getElementById(toggle.dataset.passwordTarget);
        if(!input)return;
        const show=input.type==="password";
        input.type=show?"text":"password";
        const icon=toggle.querySelector("i");
        icon?.classList.toggle("fa-eye",!show);
        icon?.classList.toggle("fa-eye-slash",show);
        toggle.setAttribute("aria-label",show?"Masquer le mot de passe":"Afficher le mot de passe");
        return;
      }

      /* -------- Bascule entre les deux vues + "mot de passe oublié" -------- */
      const authAction = e.target.closest("[data-auth-focus]");
      if (authAction) {
        e.preventDefault();
        const target = authAction.dataset.authFocus;

        if (target === "asenimCreateAccount") { switchToRegister(); return; }
        if (target === "asenimConnect")       { switchToLogin();    return; }

        if (target === "asenimLoginEmail" && window.ASENIM) { window.location.href = window.ASENIM.forgotUrl; return; }
        document.getElementById(target)?.focus();
        return;
      }

      /* -------- Clic sur le backdrop → fermeture -------- */
      const modal=document.getElementById("asenimAuthModal");
      if(modal && e.target===modal) closeModal();
    });

    document.addEventListener("keydown",e=>{if(e.key==="Escape")closeModal();});

    document.getElementById("asenimLoginForm")?.addEventListener("submit",function(e){
      e.preventDefault();
      if(!this.checkValidity()){this.reportValidity();return;}
      asenimSubmit(this, window.ASENIM.loginUrl, "asenimLoginStatus", function (data) {
        status("asenimLoginStatus", "Connexion réussie, redirection…");
        window.location.href = data.redirect || window.ASENIM.dashboardUrl;
      });
    });

    document.getElementById("asenimRegisterForm")?.addEventListener("submit",function(e){
      e.preventDefault();
      if(!this.checkValidity()){this.reportValidity();return;}
      const p=document.getElementById("asenimRegisterPassword");
      const c=document.getElementById("asenimRegisterPasswordConfirm");
      if(p.value!==c.value){
        c.setCustomValidity("Les mots de passe ne correspondent pas.");
        c.reportValidity();
        c.setCustomValidity("");
        return;
      }
      const form = this;
      asenimSubmit(form, window.ASENIM.registerUrl, "asenimRegisterStatus", function (data) {
        form.reset();
        status("asenimRegisterStatus", data.message);
        document.getElementById("asenimRegisterStatus")?.classList.add("is-success");
      });
    });
  }

  if(document.readyState==="loading") document.addEventListener("DOMContentLoaded",init);
  else init();

  window.openAsenimAuthModal=openModal;
  window.closeAsenimAuthModal=closeModal;
})();

/* =========================================================
   ASENIM — SCRIPTS MIGRÉS DEPUIS LES PAGES HTML
   ---------------------------------------------------------
   Chaque section est auto-conditionnelle : elle ne s'active
   QUE si les éléments concernés existent dans la page courante.
   Aucun conflit avec les scripts déjà présents dans main.js.
   ========================================================= */


/* =========================================================
   1. ACTUALITÉS — Cartes entièrement cliquables
   (anciennement en bas de Actualités.html)
   ========================================================= */
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.actualite-card').forEach(function (card) {
    const link = card.querySelector('.actualite-link');
    if (!link) return;

    card.style.cursor = 'pointer';
    card.setAttribute('tabindex', '0');
    card.setAttribute('role', 'link');

    const openActualite = function () {
      const href = link.getAttribute('href');
      if (href) window.location.href = href;
    };

    card.addEventListener('click', function (event) {
      if (event.target.closest('a, button, input, select, textarea')) return;
      openActualite();
    });

    card.addEventListener('keydown', function (event) {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        openActualite();
      }
    });
  });
});


/* =========================================================
   2. BUREAU EXÉCUTIF — Bouton "Voir plus"
   (anciennement en bas de Bureau exécutif.html)
   ========================================================= */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var grid = document.querySelector('.executif-section .team-grid');
    var btn  = document.getElementById('showMoreBtn');
    if (!grid || !btn) return;

    var allCards = Array.prototype.slice.call(
      grid.querySelectorAll('.team-card')
    );

    function getColumns() {
      var w = window.innerWidth;
      if (w >= 1200) return 3;
      if (w >= 768)  return 2;
      return 1;
    }

    var visibleCount = 0;

    function render() {
      allCards.forEach(function (card, i) {
        card.classList.toggle('card-hidden', i >= visibleCount);
      });

      if (visibleCount >= allCards.length) {
        btn.classList.add('btn-hidden');
        setTimeout(function () { btn.style.display = 'none'; }, 450);
      } else {
        btn.style.display = '';
        btn.classList.remove('btn-hidden');
      }
    }

    function init() {
      var cols = getColumns();
      visibleCount = Math.min(cols * 2, allCards.length);
      render();
    }

    btn.addEventListener('click', function () {
      var cols = getColumns();
      var startIdx = visibleCount;
      var endIdx   = Math.min(visibleCount + (cols * 2), allCards.length);

      for (var i = startIdx; i < endIdx; i++) {
        var card = allCards[i];
        card.classList.add('card-reveal');
        card.addEventListener('animationend', function onEnd() {
          this.classList.remove('card-reveal');
          this.removeEventListener('animationend', onEnd);
        });
      }

      visibleCount = endIdx;
      render();
    });

    var resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        var cols = getColumns();
        if (visibleCount < cols) visibleCount = cols;
        render();
      }, 150);
    });

    init();
  });
})();


/* =========================================================
   3. CONTACT — Modal + labels flottants + validation
   (anciennement en bas de contact.html)
   ========================================================= */
document.addEventListener('DOMContentLoaded', function () {
  const openForm      = document.getElementById("openForm");
  const contactModal  = document.getElementById("contactModal");
  const closeModalBtn = document.getElementById("closeModal");
  const contactForm   = document.getElementById("contactForm");
  const successMessage = document.getElementById("successMessage");

  if (!contactModal || !openForm || !contactForm) return;

  /* --- Labels flottants --- */
  function initFloatingLabels() {
    const fields = document.querySelectorAll('#contactModal .field');

    fields.forEach(field => {
      const input = field.querySelector('input, textarea');
      if (!input) return;

      if (input.value.trim() !== '') {
        field.classList.add('focused');
      }

      input.addEventListener('focus', () => {
        field.classList.add('focused');
      });

      input.addEventListener('blur', () => {
        if (input.value.trim() === '') {
          field.classList.remove('focused');
        }
      });

      if (input.tagName === 'TEXTAREA') {
        input.addEventListener('input', () => {
          if (input.value.trim() !== '') {
            field.classList.add('focused');
          } else {
            field.classList.remove('focused');
          }
        });
      }
    });
  }

  /* --- Validation d'un champ --- */
  function validateField(fieldId, value, type = 'text') {
    const field = document.getElementById(fieldId);
    const fieldContainer = document.getElementById(`${fieldId}Field`);

    if (!field || !fieldContainer) return true;

    let isValid = true;

    if (type === 'email') {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      isValid = value.trim() !== '' && emailRegex.test(value);
    } else if (type === 'text') {
      isValid = value.trim() !== '';
    }

    if (!isValid) {
      fieldContainer.classList.add('error');
    } else {
      fieldContainer.classList.remove('error');
    }

    return isValid;
  }

  /* --- Validation globale --- */
  function validateForm() {
    const name    = document.getElementById('name').value;
    const email   = document.getElementById('email').value;
    const message = document.getElementById('message').value;

    const isNameValid    = validateField('name', name, 'text');
    const isEmailValid   = validateField('email', email, 'email');
    const isMessageValid = validateField('message', message, 'text');

    return isNameValid && isEmailValid && isMessageValid;
  }

  /* --- Réinitialisation --- */
  function resetForm() {
    document.getElementById('name').value    = '';
    document.getElementById('email').value   = '';
    document.getElementById('message').value = '';

    document.querySelectorAll('#contactModal .field').forEach(field => {
      field.classList.remove('focused', 'error');
    });

    setTimeout(() => {
      if (successMessage) successMessage.classList.remove('show');
    }, 3000);
  }

  /* --- Soumission --- */
  contactForm.addEventListener('submit', (e) => {
    e.preventDefault();

    if (validateForm()) {
      const name    = document.getElementById('name').value;
      const email   = document.getElementById('email').value;
      const message = document.getElementById('message').value;

      console.log('Message envoyé:', { name, email, message });

      if (successMessage) successMessage.classList.add('show');

      resetForm();

      setTimeout(() => {
        if (contactModal.style.display === 'block') {
          contactModal.style.display = "none";
          openForm.style.display = "flex";
          document.body.classList.remove('modal-open');
        }
      }, 2000);
    }
  });

  /* --- Validation en temps réel --- */
  document.getElementById('name')?.addEventListener('input', (e) => {
    validateField('name', e.target.value, 'text');
  });

  document.getElementById('email')?.addEventListener('input', (e) => {
    validateField('email', e.target.value, 'email');
  });

  document.getElementById('message')?.addEventListener('input', (e) => {
    validateField('message', e.target.value, 'text');
  });

  /* --- État initial --- */
  contactModal.style.display = "none";
  openForm.style.display = "flex";
  initFloatingLabels();

  /* --- Ouverture --- */
  openForm.addEventListener('click', () => {
    contactModal.style.display = "block";
    openForm.style.display = "none";
    if (window.innerWidth >= 993) {
      document.body.classList.add('modal-open');
    }
    initFloatingLabels();
  });

  /* --- Fermeture bouton --- */
  if (closeModalBtn) {
    closeModalBtn.addEventListener('click', () => {
      contactModal.style.display = "none";
      openForm.style.display = "flex";
      document.body.classList.remove('modal-open');
      if (successMessage) successMessage.classList.remove('show');
    });
  }

  /* --- Fermeture clic extérieur --- */
  window.addEventListener('click', (e) => {
    if (e.target === contactModal) {
      contactModal.style.display = "none";
      openForm.style.display = "flex";
      document.body.classList.remove('modal-open');
      if (successMessage) successMessage.classList.remove('show');
    }
  });
});


/* =========================================================
   4. INDEX — Moving Letters "Nos partenaires" + apparition logos
   (anciennement en bas de index.html)
   Nécessite anime.js (chargé uniquement sur index.html).
   ========================================================= */
document.addEventListener('DOMContentLoaded', function () {
  const clientsSection   = document.querySelector('#clients');
  const clientsTitle     = document.querySelector('#clients .clients-text.ml11');
  const clientsCarousel  = document.querySelector('#clients .clients-carousel');
  const clientsLogos     = document.querySelector('#clients .clients-logos');

  if (!clientsSection || !clientsTitle || !clientsCarousel || !clientsLogos) return;
  if (typeof anime === 'undefined') return;   /* anime.js non chargé → on sort */

  const letters = clientsTitle.querySelector('.letters');
  const line    = clientsTitle.querySelector('.line');
  const mediaQuery = window.matchMedia('(min-width: 768px)');
  let prepared  = false;
  let hasPlayed = false;
  let observer  = null;

  function prepareLetters() {
    if (prepared) return;
    letters.innerHTML = letters.textContent.replace(/([^\x00-\x80]|\w)/g, '<span class="letter">$&</span>');
    prepared = true;
  }

  function revealLogos() {
    const logoItems = clientsLogos.querySelectorAll('.client-logo');
    const entryDistance = Math.max(window.innerWidth * 0.65, 520);

    clientsCarousel.style.visibility = 'visible';

    anime.remove(logoItems);
    anime.set(logoItems, { translateX: entryDistance });

    anime({
      targets: logoItems,
      translateX: [entryDistance, 0],
      duration: 1050,
      delay: anime.stagger(135),
      easing: 'easeOutCubic'
    });
  }

  function playOnce() {
    if (hasPlayed) return;
    hasPlayed = true;
    prepareLetters();

    const textWidth = letters.getBoundingClientRect().width;

    anime.timeline({ loop: false })
      .add({
        targets: line,
        scaleY: [0, 1],
        opacity: [0.5, 1],
        easing: 'easeOutExpo',
        duration: 700
      })
      .add({
        targets: line,
        translateX: [0, textWidth + 10],
        easing: 'easeOutExpo',
        duration: 700,
        delay: 100
      })
      .add({
        targets: '#clients .clients-text.ml11 .letter',
        opacity: [0, 1],
        easing: 'easeOutExpo',
        duration: 600,
        delay: function (el, i) { return 34 * (i + 1); }
      }, '-=775')
      .add({
        targets: line,
        opacity: 0,
        duration: 250,
        easing: 'linear',
        complete: function () {
          revealLogos();
        }
      });
  }

  function setup() {
    prepareLetters();

    if (!mediaQuery.matches) {
      if (hasPlayed) {
        clientsCarousel.style.visibility = 'visible';
        clientsLogos.querySelectorAll('.client-logo').forEach(function (logo) {
          logo.style.transform = 'translateX(0)';
        });
        clientsTitle.querySelectorAll('.letter').forEach(function (letter) {
          letter.style.opacity = '1';
          letter.style.transform = 'none';
        });
        if (line) line.style.opacity = '0';
        return;
      }

      clientsCarousel.style.visibility = 'hidden';

      if (observer) observer.disconnect();
      observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            observer.disconnect();
            playOnce();
          }
        });
      }, { threshold: 0.25 });

      observer.observe(clientsSection);
      return;
    }

    if (hasPlayed) {
      clientsCarousel.style.visibility = 'visible';
      clientsLogos.querySelectorAll('.client-logo').forEach(function (logo) {
        logo.style.transform = 'translateX(0)';
      });
      return;
    }

    clientsCarousel.style.visibility = 'hidden';

    if (observer) observer.disconnect();
    observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          observer.disconnect();
          playOnce();
        }
      });
    }, { threshold: 0.35 });

    observer.observe(clientsSection);
  }

  setup();

  if (mediaQuery.addEventListener) {
    mediaQuery.addEventListener('change', setup);
  } else {
    mediaQuery.addListener(setup);
  }
});


/* =========================================================
   5. INDEX — Timer du hero carousel (pause au survol)
   (anciennement en bas de index.html)
   ========================================================= */
document.addEventListener('DOMContentLoaded', function () {
  const hero = document.getElementById('heroCarousel');
  if (!hero || !window.bootstrap || !bootstrap.Carousel) return;

  const hold = Number(hero.dataset.heroInterval) || 6500;
  const carousel = bootstrap.Carousel.getOrCreateInstance(hero, {
    interval: false,
    ride: false,
    pause: false,
    wrap: true,
    touch: true,
    keyboard: true
  });

  let timerId    = null;
  let startedAt  = 0;
  let remaining  = hold;
  let paused     = false;
  let sliding    = false;

  function clearTimer() {
    if (timerId !== null) {
      window.clearTimeout(timerId);
      timerId = null;
    }
  }

  function armTimer(delay) {
    clearTimer();
    remaining = Math.max(0, delay);
    startedAt = performance.now();
    timerId = window.setTimeout(function () {
      timerId = null;
      remaining = hold;
      if (!paused && !sliding) carousel.next();
    }, remaining);
  }

  function pauseHero() {
    if (paused) return;
    paused = true;
    hero.classList.add('is-paused');
    if (timerId !== null) {
      remaining = Math.max(0, remaining - (performance.now() - startedAt));
      clearTimer();
    }
  }

  function resumeHero() {
    if (!paused) return;
    paused = false;
    hero.classList.remove('is-paused');
    if (!sliding) armTimer(remaining > 0 ? remaining : hold);
  }

  hero.addEventListener('mouseenter', pauseHero);
  hero.addEventListener('mouseleave', resumeHero);
  hero.addEventListener('focusin', pauseHero);
  hero.addEventListener('focusout', function (event) {
    if (!hero.contains(event.relatedTarget)) resumeHero();
  });

  hero.addEventListener('slide.bs.carousel', function () {
    sliding = true;
    clearTimer();
  });

  hero.addEventListener('slid.bs.carousel', function () {
    sliding = false;
    remaining = hold;
    if (!paused) armTimer(hold);
  });

  armTimer(hold);
});


/* =========================================================
   6. INDEX — Swipe mobile + tap tablette sur le hero
   (anciennement en bas de index.html)
   ========================================================= */
document.addEventListener('DOMContentLoaded', function () {
  const hero = document.getElementById('heroCarousel');
  if (!hero) return;

  const SWIPE_THRESHOLD = 45;
  const SWIPE_TIMEOUT   = 700;

  let touchStartX   = 0;
  let touchStartY   = 0;
  let touchStartTime = 0;
  let isSwiping     = false;
  let instance      = null;

  function getCarousel() {
    if (!instance && window.bootstrap && bootstrap.Carousel) {
      instance = bootstrap.Carousel.getOrCreateInstance(hero);
    }
    return instance;
  }

  function isMobile() {
    return window.innerWidth < 768;
  }

  hero.addEventListener('touchstart', function (event) {
    if (event.touches.length !== 1) return;
    const touch = event.touches[0];
    touchStartX = touch.clientX;
    touchStartY = touch.clientY;
    touchStartTime = Date.now();
    isSwiping = false;
  }, { passive: true });

  hero.addEventListener('touchmove', function (event) {
    if (event.touches.length !== 1) return;
    const touch = event.touches[0];
    const deltaX = touch.clientX - touchStartX;
    const deltaY = touch.clientY - touchStartY;

    if (!isSwiping && Math.abs(deltaX) > 12 && Math.abs(deltaX) > Math.abs(deltaY)) {
      isSwiping = true;
    }
  }, { passive: true });

  hero.addEventListener('touchend', function (event) {
    const touch = event.changedTouches && event.changedTouches[0];
    if (!touch) return;

    const deltaX = touch.clientX - touchStartX;
    const deltaY = touch.clientY - touchStartY;
    const elapsed = Date.now() - touchStartTime;

    const horizontal =
      Math.abs(deltaX) >= SWIPE_THRESHOLD &&
      Math.abs(deltaX) > Math.abs(deltaY);
    const quickEnough = elapsed <= SWIPE_TIMEOUT;
    const carousel = getCarousel();

    if (horizontal && quickEnough && carousel) {
      if (deltaX < 0) carousel.next();
      else            carousel.prev();
    }

    isSwiping = false;
  }, { passive: true });

  hero.addEventListener('click', function (event) {
    if (isMobile()) return;
    if (event.target.closest('.usm-hero-arrow')) return;
    if (event.target.closest('.carousel-indicators')) return;
    if (event.target.closest('a, button')) return;

    hero.classList.add('is-touched');
    clearTimeout(hero._arrowTimer);
    hero._arrowTimer = setTimeout(function () {
      hero.classList.remove('is-touched');
    }, 3500);
  });

  document.addEventListener('touchstart', function (event) {
    if (!hero.contains(event.target)) {
      hero.classList.remove('is-touched');
    }
  }, { passive: true });

  window.addEventListener('resize', function () {
    if (window.innerWidth >= 992) {
      hero.classList.remove('is-touched');
    }
  });
});


/* =========================================================
   7. INSCRIPTION — Toggle mot de passe + validation
   (anciennement en bas de inscription.html)
   ========================================================= */
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById("registrationForm");
  if (!form) return;

  const password     = document.getElementById("registrationPassword");
  const confirmation = document.getElementById("registrationPasswordConfirm");
  const status       = document.getElementById("registrationStatus");

  document.querySelectorAll(".registration-password-toggle").forEach(function (button) {
    button.addEventListener("click", function () {
      const input = document.getElementById(button.dataset.target);
      const icon  = button.querySelector("i");
      if (!input) return;

      const show = input.type === "password";
      input.type = show ? "text" : "password";

      icon.classList.toggle("fa-eye", !show);
      icon.classList.toggle("fa-eye-slash", show);

      button.setAttribute(
        "aria-label",
        show ? "Masquer le mot de passe" : "Afficher le mot de passe"
      );
    });
  });

  function checkPasswords() {
    if (confirmation.value && password.value !== confirmation.value) {
      confirmation.setCustomValidity("Les mots de passe ne correspondent pas.");
    } else {
      confirmation.setCustomValidity("");
    }
  }

  password.addEventListener("input", checkPasswords);
  confirmation.addEventListener("input", checkPasswords);

  form.addEventListener("submit", function (event) {
    checkPasswords();

    if (!form.checkValidity()) {
      event.preventDefault();
      form.reportValidity();
      return;
    }

    event.preventDefault();

    const button = form.querySelector('button[type="submit"]');
    if (button) button.disabled = true;
    if (status) {
      status.textContent = "Envoi en cours…";
      status.className = "registration-status is-visible";
    }
    asenimPost(form.action, new FormData(form))
      .then(function (data) {
        form.reset();
        if (status) {
          status.textContent = data.message;
          status.className = "registration-status is-visible success";
        }
      })
      .catch(function (err) {
        if (status) {
          status.textContent = err.message;
          status.className = "registration-status is-visible error";
        }
      })
      .finally(function () { if (button) button.disabled = false; });
  });
});
}
/* Espace membre */
if (document.body.classList.contains('member-page')) {
/* ASENIM — interactions du tableau de bord (démo front-end) */
document.addEventListener('DOMContentLoaded', () => {
  const body = document.body;
  const sidebar = document.querySelector('#memberSidebar');
  const toggle = document.querySelector('.member-menu-toggle');
  const backdrop = document.querySelector('.member-sidebar-backdrop');
  const links = document.querySelectorAll('.member-nav-link');
  const closeMenu = () => { body.classList.remove('menu-open'); toggle?.setAttribute('aria-expanded', 'false'); };
  toggle?.addEventListener('click', () => {
    const opened = body.classList.toggle('menu-open');
    toggle.setAttribute('aria-expanded', String(opened));
  });
  backdrop?.addEventListener('click', closeMenu);
  document.addEventListener('keydown', event => { if(event.key === 'Escape') closeMenu(); });
  links.forEach(link => link.addEventListener('click', () => {
    links.forEach(other => other.classList.remove('is-active'));
    link.classList.add('is-active');
    closeMenu();
  }));
  // Le contenu des rubriques non présentées dans la capture sera branché ultérieurement.
  document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', event => {
      const destination = document.querySelector(link.getAttribute('href'));
      if (!destination) return;
      if (destination.closest('.member-anchor-targets')) {
        event.preventDefault();
        closeMenu();
      }
    });
  });
});

}


/* =========================================================
   NEWSLETTER — pas encore branchée côté administration
   ========================================================= */
document.addEventListener('submit', function (event) {
  const form = event.target.closest('[data-newsletter-form]');
  if (!form) return;
  event.preventDefault();
  let note = form.parentElement.querySelector('.asenim-newsletter-note');
  if (!note) {
    note = document.createElement('p');
    note.className = 'asenim-newsletter-note';
    note.setAttribute('role', 'status');
    form.insertAdjacentElement('afterend', note);
  }
  note.textContent = "L'inscription à la newsletter sera bientôt disponible. Merci de votre intérêt !";
});