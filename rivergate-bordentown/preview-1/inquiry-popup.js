/* ==========================================================================
   RIVERGATE BORDENTOWN — GENERAL INQUIRY POP-UP (static preview)
   ==========================================================================
   Opens a leasing-inquiry modal shortly after the page loads, once per browser
   session. Styles live in the theme's global.css (section 26) so the preview
   and the WordPress theme share one source of truth.

   Include on a page with:  <script src="inquiry-popup.js" defer></script>

   Reviewing tip: the pop-up is suppressed after it has been seen once. Append
   ?popup=1 to any URL to force it open again without clearing session storage.
   ========================================================================== */

(function () {
  'use strict';

  var SEEN_KEY = 'rg-inquiry-popup-seen';
  var OPEN_DELAY = 1400;
  var IMG = '../theme/rivergate-bordentown/assets/images/exterior/exterior-entrance-sunset.jpg';

  var forced = /[?&]popup=1(&|$)/.test(window.location.search);

  function alreadySeen() {
    if (forced) return false;
    try { return window.sessionStorage.getItem(SEEN_KEY) === '1'; }
    catch (e) { return false; }  /* private mode / storage disabled — just show it */
  }

  function markSeen() {
    try { window.sessionStorage.setItem(SEEN_KEY, '1'); } catch (e) {}
  }

  if (alreadySeen()) return;

  /* ---------------------------------------------------------------- markup */
  var popup = document.createElement('div');
  popup.className = 'inq-popup';
  popup.id = 'inq-popup';
  popup.innerHTML =
    '<div class="inq-popup__backdrop" data-inq-close></div>' +
    '<div class="inq-popup__dialog" role="dialog" aria-modal="true" aria-labelledby="inq-popup-title">' +
      '<button class="inq-popup__close" type="button" aria-label="Close" data-inq-close>&times;</button>' +
      '<div class="inq-popup__media">' +
        '<img src="' + IMG + '" alt="" aria-hidden="true">' +
      '</div>' +
      '<div class="inq-popup__body" id="inq-popup-body">' +
        '<span class="eyebrow">Now Leasing</span>' +
        '<h2 class="inq-popup__title" id="inq-popup-title">Let’s Find Your Rivergate Home</h2>' +
        '<p class="inq-popup__text">Tell us what you’re looking for and our leasing team will follow up with current pricing and availability.</p>' +
        '<form class="inq-popup__form" novalidate>' +
          '<div class="form-group">' +
            '<label for="inq-name">Name</label>' +
            '<input type="text" id="inq-name" name="name" placeholder="Jane Smith" autocomplete="name" required>' +
          '</div>' +
          '<div class="form-group">' +
            '<label for="inq-email">Email Address</label>' +
            '<input type="email" id="inq-email" name="email" placeholder="jane@example.com" autocomplete="email" required>' +
          '</div>' +
          '<div class="form-group">' +
            '<label for="inq-phone">Phone Number</label>' +
            '<input type="tel" id="inq-phone" name="phone" placeholder="(609) 000-0000" autocomplete="tel">' +
          '</div>' +
          '<div class="form-group">' +
            '<label for="inq-interest">Interested In</label>' +
            '<select id="inq-interest" name="interest">' +
              '<option value="">Select a residence type…</option>' +
              '<option value="1br">One Bedroom</option>' +
              '<option value="2br">Two Bedroom</option>' +
              '<option value="unsure">Not Sure Yet</option>' +
            '</select>' +
          '</div>' +
          '<button type="submit" class="btn btn--primary">Send My Inquiry</button>' +
        '</form>' +
        '<p class="inq-popup__fineprint">Or reach us directly — ' +
          '<a href="tel:6092980303">609.298.0303</a> · ' +
          '<a href="mailto:rivergate@spgnj.com">rivergate@spgnj.com</a></p>' +
      '</div>' +
    '</div>';

  document.body.appendChild(popup);

  var dialog = popup.querySelector('.inq-popup__dialog');
  var body   = popup.querySelector('.inq-popup__body');
  var form   = popup.querySelector('.inq-popup__form');
  var lastFocused = null;

  /* ------------------------------------------------------- open / close */
  function focusable() {
    return Array.prototype.filter.call(
      dialog.querySelectorAll('a[href], button, input, select, textarea'),
      function (el) { return !el.disabled && el.offsetParent !== null; }
    );
  }

  function open() {
    lastFocused = document.activeElement;
    popup.classList.add('is-open');
    popup.setAttribute('aria-hidden', 'false');
    document.body.classList.add('inq-open');
    var first = dialog.querySelector('input, select, button');
    if (first) first.focus();
  }

  function close() {
    markSeen();
    popup.classList.remove('is-open');
    popup.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('inq-open');
    if (lastFocused && lastFocused.focus) lastFocused.focus();
  }

  popup.addEventListener('click', function (e) {
    if (e.target.closest('[data-inq-close]')) close();
  });

  document.addEventListener('keydown', function (e) {
    if (!popup.classList.contains('is-open')) return;

    if (e.key === 'Escape') { close(); return; }

    /* Keep tabbing inside the dialog while it's open */
    if (e.key === 'Tab') {
      var items = focusable();
      if (!items.length) return;
      var first = items[0];
      var last  = items[items.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }
  });

  /* ------------------------------------------------------------- submit */
  /* Static preview: no backend, so confirm in place rather than navigating. */
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    var name  = form.querySelector('#inq-name');
    var email = form.querySelector('#inq-email');
    if (!name.value.trim()) { name.focus(); return; }
    if (!email.checkValidity || !email.checkValidity()) { email.focus(); return; }

    markSeen();
    body.innerHTML =
      '<div class="inq-popup__thanks">' +
        '<span class="eyebrow">Thank You</span>' +
        '<h3>We’ll be in touch shortly.</h3>' +
        '<p>Thanks, ' + name.value.trim().split(' ')[0].replace(/[<>&]/g, '') + '. ' +
          'A member of the Rivergate leasing team will reach out with pricing and availability. ' +
          'Need something sooner? Call <a href="tel:6092980303">609.298.0303</a>.</p>' +
        '<a class="btn btn--primary" href="availability.html">See What’s Available</a>' +
      '</div>';
    body.querySelector('.btn').focus();
  });

  /* --------------------------------------------------------------- boot */
  window.setTimeout(open, OPEN_DELAY);
}());
