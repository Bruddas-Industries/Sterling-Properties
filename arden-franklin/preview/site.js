/* ==========================================================================
   THE ARDEN — STATIC PREVIEW BEHAVIOUR (coming-soon splash)
   ==========================================================================
   Forked from Riverbend's preview/site.js, cut down to what the one-page
   splash uses: the header's scrolled state, smooth anchor scrolling, scroll
   reveal and the interest-list form. Each block guards on its own markup.
   No build step, no dependencies. When the full site is built in December,
   start again from Riverbend's site.js (floor plans, amenities, map).
   ========================================================================== */

(function () {
  'use strict';

  var ASSETS = '../theme/arden-franklin/assets/';

  function $(sel, root) { return (root || document).querySelector(sel); }
  function $$(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }

  /* ------------------------------------------------------ 1. header */
  var header = $('#site-header');
  if (header) {
    var onScroll = function () { header.classList.toggle('is-scrolled', window.scrollY > 10); };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* Smooth scroll for same-page anchors */
  $$('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var href = a.getAttribute('href');
      if (href.length < 2) return;
      var target = document.querySelector(href);
      if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });
  });

  /* ------------------------------------------------ 2. scroll reveal */
  var reveal = $$('[data-animate]');
  $$('.splash-pillars__grid, .splash-places').forEach(function (grid) {
    $$('[data-animate]', grid).forEach(function (el, i) { el.style.transitionDelay = (i * 90) + 'ms'; });
  });
  if (!('IntersectionObserver' in window)) {
    reveal.forEach(function (el) { el.classList.add('is-visible'); });
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { entry.target.classList.add('is-visible'); io.unobserve(entry.target); }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    reveal.forEach(function (el) { io.observe(el); });
  }

  /* ------------------------------------------ 3. interest-list form
     The preview has no server, so a valid submission shows the thank-you
     state instead of posting. On WordPress this form becomes a dynamic block
     that saves a Lead and emails the leasing team (OPERATIONS → Mail). */
  var form = $('#interest-form');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!form.checkValidity()) { form.reportValidity(); return; }
      var first = ($('#first-name', form).value || '').trim();
      var card = form.closest('.splash-form');
      card.innerHTML =
        '<div class="splash-form__success" role="status" tabindex="-1">' +
          '<img src="' + ASSETS + 'images/logos/arden-mark-espresso.png" alt="" aria-hidden="true">' +
          '<h3>Thank you' + (first ? ', ' + first.replace(/[<>&"]/g, '') : '') + '.</h3>' +
          '<p>You’re on The Arden’s interest list. Our leasing team will be in touch as soon as floor plans, pricing and leasing dates are available.</p>' +
          '<p class="splash-form__fine">Design preview: this form is not connected yet, so nothing was sent.</p>' +
        '</div>';
      var done = $('.splash-form__success', card);
      if (done) done.focus();
    });
  }
})();
