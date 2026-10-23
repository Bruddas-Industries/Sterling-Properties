/**
 * Bellclair Montclair — main.js
 * Shared JavaScript for all pages. Forked from rivergate-bordentown; the
 * Bellclair-only parts are the floor-plan panel (6), the amenity photo
 * caption (12), the map styling and fallback (11) and the film modal (14).
 */

(function () {
  'use strict';

  /* ------------------------------------------------------------------
     1. STICKY HEADER — shadow on scroll; transparency on video-hero pages
     ------------------------------------------------------------------ */
  var header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', function () {
      header.classList.toggle('is-scrolled', window.scrollY > 10);
    }, { passive: true });
  }

  /* ------------------------------------------------------------------
     2. FULL-SCREEN OVERLAY NAV
     ------------------------------------------------------------------ */
  var toggle  = document.getElementById('nav-toggle');
  var overlay = document.getElementById('nav-overlay');

  if (toggle && overlay) {
    function closeNav() {
      overlay.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('nav-is-open');
    }
    function openNav() {
      overlay.classList.add('is-open');
      toggle.setAttribute('aria-expanded', 'true');
      document.body.classList.add('nav-is-open');
    }

    toggle.addEventListener('click', function () {
      overlay.classList.contains('is-open') ? closeNav() : openNav();
    });

    overlay.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', closeNav);
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeNav();
    });
  }

  /* ------------------------------------------------------------------
     3. SMOOTH SCROLL — in-page anchor links
        Covers bare "#section" links AND absolute links that resolve to a
        section on the page you are already on. The nav uses
        home_url('/#section'), so on the homepage those must scroll
        smoothly instead of triggering a full reload.
     ------------------------------------------------------------------ */
  function samePath(a, b) {
    return a.replace(/\/+$/, '') === b.replace(/\/+$/, '');
  }

  document.querySelectorAll('a[href]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      var url;
      try {
        url = new URL(this.href, window.location.href);
      } catch (err) {
        return;
      }

      if (!url.hash || url.hash === '#') return;
      if (url.origin !== window.location.origin) return;
      if (!samePath(url.pathname, window.location.pathname)) return;

      var target;
      try {
        target = document.querySelector(url.hash);
      } catch (err) {
        return;                        /* hash isn't a valid selector */
      }

      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  /* ------------------------------------------------------------------
     4. SCROLL-REVEAL ANIMATIONS
     ------------------------------------------------------------------ */
  var animItems = document.querySelectorAll('[data-animate]');
  if (animItems.length) {
    /* Stagger grid children */
    document.querySelectorAll('.card-grid, .amenity-grid, .residence-grid, .communities-teaser, .how-it-works, .portal-grid').forEach(function (grid) {
      grid.querySelectorAll('[data-animate]').forEach(function (el, i) {
        el.style.transitionDelay = (i % 6 * 70) + 'ms';
      });
    });

    if (!window.IntersectionObserver) {
      animItems.forEach(function (el) { el.classList.add('is-visible'); });
    } else {
      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

      animItems.forEach(function (el) { observer.observe(el); });
    }
  }

  /* ------------------------------------------------------------------
     5. AMENITY ACCORDION (homepage + amenities page)
     ------------------------------------------------------------------ */
  document.querySelectorAll('.amenity').forEach(function (item) {
    var head  = item.querySelector('.amenity__head');
    var panel = item.querySelector('.amenity__panel');
    if (!head || !panel) return;

    head.addEventListener('click', function () {
      var open = !item.classList.contains('is-open');

      /* Only one amenity open at a time within a grid, so a neighbouring tile
         never sits expanded alongside the one being read. */
      if (open) {
        var grid = item.closest('.amenity-grid') || document;
        grid.querySelectorAll('.amenity.is-open').forEach(function (other) {
          if (other === item) return;
          other.classList.remove('is-open');
          var oh = other.querySelector('.amenity__head');
          var op = other.querySelector('.amenity__panel');
          if (oh) oh.setAttribute('aria-expanded', 'false');
          if (op) op.style.maxHeight = '';
        });
      }

      item.classList.toggle('is-open', open);
      head.setAttribute('aria-expanded', open ? 'true' : 'false');
      panel.style.maxHeight = open ? panel.scrollHeight + 'px' : '';
    });
  });

  /* ------------------------------------------------------------------
     6. FLOOR PLAN SELECTOR (homepage)
        PLANS data is injected as an inline <script> by the bellclair/floor-plans
        block (blocks/floor-plans/render.php) via wp_add_inline_script() so it
        reflects the Floor Plan CPT.
     ------------------------------------------------------------------ */
  // Safety guard — PLANS should always be set by the PHP template on the
  // homepage, but default to an empty object on other pages.
  var PLANS     = window.PLANS || {};
  var APPLY_URL = 'https://sterlingproperties.appfolio.com/listings?filters%5Bproperty_list%5D=BELLCLAIRE%2C+LLC';
  var AVAIL_URL = document.querySelector('meta[name="bc-avail-url"]')
    ? document.querySelector('meta[name="bc-avail-url"]').getAttribute('content')
    : '/availability/';

  var fpOptions = document.querySelectorAll('.fp-option');
  var fpDetail  = document.getElementById('fp-detail');

  if (fpOptions.length && fpDetail) {
    /* Mirrors bellclair_fp_option_inner() / bellclair_fp_detail_html() in
       blocks/_shared.php, which render the same markup before this runs. */
    function fpTag(p) { return p.tag || (p.mp ? '3D Tour' : ''); }

    fpOptions.forEach(function (opt) {
      var p = PLANS[opt.getAttribute('data-plan')];
      if (!p) return;
      var tag = fpTag(p);
      opt.innerHTML =
        '<span class="fp-option__specs">' + p.bed + ' Bed / ' + p.bath + ' Bath' + (p.where ? ' · ' + p.where : '') + '</span>' +
        '<span class="fp-option__name">' + p.name + '</span>' +
        '<span class="fp-option__size">' + p.sqft + ' sq ft</span>' +
        (tag ? '<span class="fp-option__tag">' + tag + '</span>' : '');
    });

    function renderDetail(key) {
      var p = PLANS[key];
      if (!p) return;
      var plan = p.image
        ? '<img class="fp-plan-img" src="' + p.image + '" alt="' + p.name + ' floor plan — ' + p.bed + ' bed, ' + p.bath + ' bath, ' + p.sqft + ' sq ft" loading="lazy">'
        : '<div class="fp-diagram"><span class="fp-diagram__watermark">' + p.name + '</span>' +
          '<span class="fp-diagram__note">Floor plan drawing coming soon</span></div>';
      var tour = p.mp
        ? '<button type="button" class="btn btn--secondary" data-mp="' + p.mp + '" data-name="' + p.name + '">Take a 3D Tour</button>'
        : '';
      var last = p.pdf
        ? '<a class="btn btn--secondary" href="' + p.pdf + '" target="_blank" rel="noopener noreferrer">Download PDF</a>'
        : '<a class="btn btn--secondary" href="' + APPLY_URL + '" target="_blank" rel="noopener noreferrer">Apply Now</a>';
      fpDetail.innerHTML =
        '<p class="fp-detail__eyebrow">' + p.code + '</p>' +
        '<h3 class="fp-detail__name">' + p.name + '</h3>' +
        '<div class="fp-detail__specs">' +
          '<div class="fp-spec"><span>Bedrooms</span><span>' + p.bed + '</span></div>' +
          '<div class="fp-spec"><span>Bathrooms</span><span>' + p.bath + '</span></div>' +
          '<div class="fp-spec"><span>Square Feet</span><span>' + p.sqft + '</span></div>' +
          (p.rent ? '<div class="fp-spec"><span>Starting Rent</span><span>' + p.rent + '</span></div>' : '') +
          '<div class="fp-spec"><span>Laundry</span><span>' + (p.laundry || '—') + '</span></div>' +
        '</div>' +
        plan +
        '<div class="fp-detail__actions">' +
          tour +
          '<a class="btn btn--primary" href="' + AVAIL_URL + '">Check Availability</a>' +
          last +
        '</div>';
    }

    /* Below 900px .fp-selector stacks, so the detail panel is moved into .fp-list
       directly under the tapped plan (accordion) rather than being left below the
       whole list, which forced a long scroll back up. */
    var fpList     = document.getElementById('fp-list') || fpDetail.parentNode.querySelector('.fp-list');
    var fpSelector = fpDetail.closest('.fp-selector');
    var fpMq       = window.matchMedia('(max-width: 900px)');
    var fpCurrent  = null;

    function placeDetail(opt) {
      if (!fpSelector) return;
      if (fpMq.matches) {
        fpDetail.classList.add('fp-detail--inline');
        if (fpDetail.previousElementSibling !== opt) opt.insertAdjacentElement('afterend', fpDetail);
      } else {
        fpDetail.classList.remove('fp-detail--inline');
        if (fpDetail.parentNode !== fpSelector) fpSelector.appendChild(fpDetail);
      }
    }

    /* Two different widgets: a listbox + side panel on desktop, a disclosure
       accordion on mobile (where the panel sits inside the list, which a listbox
       may not contain). Keep the ARIA in step with whichever is on screen. */
    function syncFpAria() {
      var mobile = fpMq.matches;
      if (fpList) fpList.setAttribute('role', mobile ? 'group' : 'listbox');
      fpDetail.setAttribute('role', 'region');
      fpOptions.forEach(function (o) {
        var on = o.classList.contains('is-active');
        if (mobile) {
          o.removeAttribute('role');            /* native <button> role is correct here */
          o.removeAttribute('aria-selected');
          o.setAttribute('aria-controls', 'fp-detail');
          o.setAttribute('aria-expanded', on ? 'true' : 'false');
        } else {
          o.setAttribute('role', 'option');
          o.removeAttribute('aria-expanded');
          o.removeAttribute('aria-controls');
          o.setAttribute('aria-selected', on ? 'true' : 'false');
        }
      });
    }

    function closePlan() {
      fpOptions.forEach(function (o) { o.classList.remove('is-active'); });
      fpCurrent = null;
      fpDetail.hidden = true;
      fpDetail.innerHTML = '';
      syncFpAria();
    }

    function selectPlan(opt) {
      fpOptions.forEach(function (o) { o.classList.remove('is-active'); });
      opt.classList.add('is-active');
      fpCurrent = opt;
      fpDetail.hidden = false;
      renderDetail(opt.getAttribute('data-plan'));
      placeDetail(opt);
      syncFpAria();
    }

    fpOptions.forEach(function (opt) {
      opt.addEventListener('click', function () {
        /* On mobile the list acts as an accordion — tapping the open plan closes it */
        if (fpMq.matches && fpCurrent === opt) { closePlan(); return; }
        selectPlan(opt);
        if (fpMq.matches) opt.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      });
    });

    /* Desktop opens on the block's default plan (Dorsey) so the panel is never
       blank. Mobile starts collapsed so the list stays short and the detail
       opens under whichever plan the visitor taps. */
    function applyFpBreakpoint() {
      if (fpCurrent) { placeDetail(fpCurrent); syncFpAria(); return; }
      if (fpMq.matches) {
        closePlan();
      } else {
        var defaultKey = fpSelector ? fpSelector.getAttribute('data-default-plan') : '';
        var defaultOpt = (defaultKey && document.querySelector('.fp-option[data-plan="' + defaultKey + '"]')) || fpOptions[0];
        if (defaultOpt) selectPlan(defaultOpt);
      }
    }
    applyFpBreakpoint();
    if (fpMq.addEventListener) fpMq.addEventListener('change', applyFpBreakpoint);
    else if (fpMq.addListener) fpMq.addListener(applyFpBreakpoint);
  }

  /* ------------------------------------------------------------------
     7. FLOOR PLANS PAGE — 1BR / 2BR tab switcher
     ------------------------------------------------------------------ */
  var planTabBtns   = document.querySelectorAll('.plan-tab-btn');
  var planPanels    = document.querySelectorAll('.plan-panel');

  if (planTabBtns.length && planPanels.length) {
    planTabBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var target = btn.getAttribute('data-tab');
        planTabBtns.forEach(function (b) { b.classList.remove('is-active'); });
        planPanels.forEach(function (p) { p.hidden = true; });
        btn.classList.add('is-active');
        var panel = document.getElementById('plan-' + target);
        if (panel) panel.hidden = false;
      });
    });
  }

  /* ------------------------------------------------------------------
     8. GALLERY — category filter tabs
     ------------------------------------------------------------------ */
  var galleryTabs  = document.querySelectorAll('.gallery-tab');
  var galleryItems = document.querySelectorAll('.gallery-item[data-category]');

  if (galleryTabs.length && galleryItems.length) {
    galleryTabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        var cat = tab.getAttribute('data-filter');
        galleryTabs.forEach(function (t) { t.classList.remove('is-active'); });
        tab.classList.add('is-active');

        galleryItems.forEach(function (item) {
          if (cat === 'all' || item.getAttribute('data-category') === cat) {
            item.style.display = '';
          } else {
            item.style.display = 'none';
          }
        });
      });
    });
  }

  /* ------------------------------------------------------------------
     9. NEIGHBORHOOD EXPLORER — tab filter
     ------------------------------------------------------------------ */
  var explorerEl  = document.getElementById('neighborhood-explorer');
  var explorerTabs = document.querySelectorAll('.tab-btn[data-tab]');

  if (explorerEl && explorerTabs.length) {
    explorerTabs.forEach(function (btn) {
      btn.addEventListener('click', function () {
        explorerTabs.forEach(function (b) {
          b.classList.remove('is-active');
          b.setAttribute('aria-selected', 'false');
        });
        btn.classList.add('is-active');
        btn.setAttribute('aria-selected', 'true');
        explorerEl.setAttribute('data-active-tab', btn.getAttribute('data-tab'));
      });
    });

    /* POI item → highlight row (Google Maps integration handled in location template) */
    document.querySelectorAll('.explorer-poi-item').forEach(function (item) {
      item.addEventListener('click', function () {
        document.querySelectorAll('.explorer-poi-item').forEach(function (i) { i.classList.remove('is-active'); });
        item.classList.add('is-active');
      });
    });
  }

  /* ------------------------------------------------------------------
     13. LIGHTBOX — click a floor plan (Andrew, 8/26) or a gallery photo to
         view it full screen. Delegated, so it covers the homepage selector's
         JS-rendered plan image and the page-level cards/tiles alike.
         Gallery photos add a caption, prev/next, arrow keys and swipe,
         stepping through every visible tile on the page in order.
     ------------------------------------------------------------------ */
  (function () {
    var box = document.getElementById('fp-lightbox');
    var img = document.getElementById('fp-lightbox-img');
    if (!box || !img) return;
    var dialog  = box.querySelector('.fp-lightbox__dialog');
    var caption = document.getElementById('fp-lightbox-caption');
    var lastFocused = null;
    var photos = [];   /* gallery tiles in page order, while in gallery mode */
    var index  = -1;

    /* Tiles are plain <figure>s — make them reachable and operable by keyboard. */
    document.querySelectorAll('.gallery-item').forEach(function (tile) {
      var t = tile.querySelector('img');
      if (!t) return;
      tile.setAttribute('tabindex', '0');
      tile.setAttribute('role', 'button');
      tile.setAttribute('aria-label', 'View larger: ' + (t.getAttribute('alt') || 'photo'));
    });

    function show(src, alt, srcset, text, ratio) {
      /* srcset lets the browser fetch a size that fits the screen rather than
         the full original; floor plans have none, so clear any left over.
         sizes = the width the photo will actually display at: the dialog is
         min(96vw, 1400px) wide and 92vh minus the caption tall, so a portrait
         photo is height-limited and needs a much narrower file. */
      if (srcset) {
        var w = Math.min(window.innerWidth * 0.96, 1400, (window.innerHeight * 0.92 - 48) * (ratio || 1.5));
        img.setAttribute('srcset', srcset);
        img.setAttribute('sizes', Math.round(w) + 'px');
      } else {
        img.removeAttribute('srcset');
        img.removeAttribute('sizes');
      }
      img.setAttribute('src', src);
      img.setAttribute('alt', alt || '');
      if (caption) caption.textContent = text || '';
    }

    function showPhoto(i) {
      var n = photos.length;
      index = ((i % n) + n) % n;
      var tile = photos[index];
      var t = tile.querySelector('img');
      var cap = tile.querySelector('figcaption');
      var tw = +t.getAttribute('width') || t.naturalWidth;
      var th = +t.getAttribute('height') || t.naturalHeight;
      show(t.getAttribute('src'), t.getAttribute('alt'), t.getAttribute('srcset'),
           (cap ? cap.textContent.trim() : '') + '  ·  ' + (index + 1) + ' / ' + n,
           tw && th ? tw / th : 0);
    }

    function open(gallery) {
      lastFocused = document.activeElement;
      box.classList.toggle('is-gallery', gallery);
      if (dialog) dialog.setAttribute('aria-label', gallery ? 'Photo gallery' : 'Floor plan');
      box.classList.add('is-open');
      box.setAttribute('aria-hidden', 'false');
      document.body.classList.add('mp-open');      /* reuse the scroll lock */
      var btn = box.querySelector('.fp-lightbox__close');
      if (btn) btn.focus();
    }

    function openTile(tile) {
      /* offsetParent is null for tiles hidden by a gallery filter */
      photos = Array.prototype.filter.call(document.querySelectorAll('.gallery-item'), function (el) {
        return el.querySelector('img') && el.offsetParent !== null;
      });
      showPhoto(photos.indexOf(tile));
      open(true);
    }

    function close() {
      box.classList.remove('is-open', 'is-gallery');
      box.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('mp-open');
      img.removeAttribute('srcset');
      img.setAttribute('src', '');
      photos = [];
      if (lastFocused && lastFocused.focus) lastFocused.focus();
    }

    function step(d) { if (photos.length) showPhoto(index + d); }

    document.addEventListener('click', function (e) {
      if (!e.target.closest) return;
      /* The dialog fills most of the screen, so a click in the empty space
         beside the image lands on it rather than the backdrop — close on both. */
      if (e.target.closest('[data-fpl-close]') || e.target === dialog) { close(); return; }
      if (e.target.closest('[data-fpl-prev]')) { step(-1); return; }
      if (e.target.closest('[data-fpl-next]')) { step(1); return; }
      var plan = e.target.closest('.fp-plan-img, .plan-card__img');
      if (plan) {
        photos = [];
        show(plan.getAttribute('src'), plan.getAttribute('alt'));
        open(false);
        return;
      }
      var tile = e.target.closest('.gallery-item');
      if (tile && tile.querySelector('img') && !box.contains(tile)) openTile(tile);
    });

    document.addEventListener('keydown', function (e) {
      if (box.classList.contains('is-open')) {
        if (e.key === 'Escape') close();
        else if (e.key === 'ArrowLeft') step(-1);
        else if (e.key === 'ArrowRight') step(1);
        return;
      }
      if ((e.key === 'Enter' || e.key === ' ') && e.target.classList &&
          e.target.classList.contains('gallery-item')) {
        e.preventDefault();
        openTile(e.target);
      }
    });

    /* Swipe left/right on touch screens */
    var touchX = null;
    box.addEventListener('touchstart', function (e) { touchX = e.touches[0].clientX; }, { passive: true });
    box.addEventListener('touchend', function (e) {
      if (touchX === null) return;
      var dx = e.changedTouches[0].clientX - touchX;
      touchX = null;
      if (Math.abs(dx) > 50) step(dx < 0 ? 1 : -1);
    });
  }());

  /* ------------------------------------------------------------------
     12. AMENITY PHOTO — follows the selected amenity
         Each .amenity carries data-amenity-photo; opening one cross-fades the
         right-hand photo and updates its caption. Amenities without their own
         photo keep the current photo and just retitle it; closing the last
         open amenity returns to the default.
     ------------------------------------------------------------------ */
  (function () {
    var photo   = document.querySelector('.amenities-photo img');
    var caption = document.querySelector('.amenities-photo__caption');
    var items   = document.querySelectorAll('.amenity[data-amenity-photo]');
    if (!photo || !items.length) return;

    var defaultSrc     = photo.getAttribute('src');
    var defaultAlt     = photo.getAttribute('alt');
    var defaultCaption = caption ? caption.textContent : '';

    function show(src, name) {
      if (caption) caption.textContent = name;
      if (!src || photo.getAttribute('src') === src) return;
      photo.classList.add('is-swapping');               /* global.css 29f */
      window.setTimeout(function () {
        photo.onload = function () { photo.classList.remove('is-swapping'); };
        photo.setAttribute('src', src);
        photo.setAttribute('alt', name);
      }, 220);
    }

    items.forEach(function (item) {
      var head = item.querySelector('.amenity__head');
      if (!head) return;
      head.addEventListener('click', function () {
        /* section 5 toggles .is-open (one at a time) before this runs */
        if (!item.classList.contains('is-open')) {
          show(defaultSrc, defaultCaption || defaultAlt);
          return;
        }
        show(item.getAttribute('data-amenity-photo'),
             item.getAttribute('data-amenity-name') || defaultCaption);
      });
    });
  }());

  /* ------------------------------------------------------------------
     11. NEIGHBORHOOD MAP — numbered POI markers, list/tab wiring
         Data comes from window.BC_MAP, injected by the
         bellclair/neighborhood-explorer block (blocks/neighborhood-explorer/
         render.php) so the markers always match the rendered list.
         The Maps API calls initBellclairMap when it finishes loading; it is
         enqueued with a dependency on this file so this runs first.
         Section 9 above owns the tab/row UI state — this only adds the map
         behaviour on top, so the list still works if the API fails to load.
     ------------------------------------------------------------------ */
  /* If Google refuses the key on this domain (it is referrer-restricted),
     swap in the OpenStreetMap embed so the section never shows a grey error
     box. Also covers the API never loading at all. */
  function mapFallback() {
    var el  = document.getElementById('poi-map');
    var cfg = window.BC_MAP;
    if (!el || !cfg || !cfg.fallback || el.getAttribute('data-fallback')) return;
    el.setAttribute('data-fallback', '1');
    el.innerHTML = '';
    var frame = document.createElement('iframe');
    frame.title = 'Map of ' + cfg.property.title + ' and nearby Montclair';
    frame.src = cfg.fallback;
    el.appendChild(frame);
  }
  window.gm_authFailure = mapFallback;
  if (window.BC_MAP) {
    window.setTimeout(function () {
      if (!(window.google && window.google.maps)) mapFallback();
    }, 8000);
  }

  window.initBellclairMap = function () {
    var cfg   = window.BC_MAP;
    var mapEl = document.getElementById('poi-map');
    if (!cfg || !mapEl || mapEl.getAttribute('data-fallback') || !window.google || !google.maps) return;

    /* Warm ivory map to sit on the Bellclair field */
    var mapStyles = [
      { elementType: 'geometry', stylers: [{ color: '#f3f0ea' }] },
      { elementType: 'labels.icon', stylers: [{ visibility: 'off' }] },
      { elementType: 'labels.text.fill', stylers: [{ color: '#6b6458' }] },
      { elementType: 'labels.text.stroke', stylers: [{ color: '#f8f6f1' }] },
      { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#d9d2c3' }] },
      { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#ffffff' }] },
      { featureType: 'road.arterial', elementType: 'geometry', stylers: [{ color: '#ebe5d9' }] },
      { featureType: 'road.highway', elementType: 'geometry', stylers: [{ color: '#e3d5b4' }] },
      { featureType: 'poi.park', elementType: 'geometry', stylers: [{ color: '#e4e6d6' }] },
      { featureType: 'transit', elementType: 'geometry', stylers: [{ color: '#e8e3d8' }] },
      { featureType: 'administrative', elementType: 'geometry.stroke', stylers: [{ color: '#cfc3a6' }] }
    ];

    var map = new google.maps.Map(mapEl, {
      center: cfg.center,
      zoom: cfg.zoom,
      styles: mapStyles,
      mapTypeControl: false,
      streetViewControl: false,
      fullscreenControl: true,
      zoomControl: true
    });

    function svgMarker(num, color, ink) {
      var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">'
        + '<circle cx="14" cy="14" r="13" fill="' + color + '" stroke="#F8F6F1" stroke-width="2.5"/>'
        + '<text x="14" y="19" text-anchor="middle" font-family="Questrial,Arial,sans-serif" font-size="11" font-weight="700" fill="' + (ink || '#F8F6F1') + '">' + num + '</text>'
        + '</svg>';
      return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
    }

    /* Black deco diamond with a gold "B" for the property itself */
    var homeSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">'
      + '<rect x="6" y="6" width="28" height="28" transform="rotate(45 20 20)" fill="#0A090C" stroke="#B6984E" stroke-width="2"/>'
      + '<text x="20" y="25" text-anchor="middle" font-family="Poiret One,Georgia,serif" font-size="15" fill="#E3D5B4">B</text>'
      + '</svg>';

    function card(html) {
      return new google.maps.InfoWindow({
        content: '<div style="font-family:Questrial,Arial,sans-serif;padding:9px 13px;line-height:1.3">' + html + '</div>'
      });
    }

    var activeInfo = null;
    var byName     = {};
    var byCategory = {};

    var propertyMarker = new google.maps.Marker({
      position: { lat: cfg.property.lat, lng: cfg.property.lng },
      map: map,
      title: cfg.property.title,
      zIndex: 200,
      icon: {
        url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(homeSvg),
        scaledSize: new google.maps.Size(40, 40),
        anchor: new google.maps.Point(20, 20)
      }
    });
    var propertyInfo = card('<strong style="font-size:12px;color:#0A090C;display:block;letter-spacing:0.04em;text-transform:uppercase">' + cfg.property.title + '</strong>'
      + '<span style="font-size:11px;color:#5C554B;display:block;margin-top:2px">' + cfg.property.address + '</span>');
    propertyMarker.addListener('click', function () {
      if (activeInfo && activeInfo !== propertyInfo) activeInfo.close();
      propertyInfo.open(map, propertyMarker);
      activeInfo = propertyInfo;
    });

    /* Get Directions routes FROM the property TO whichever place is selected.
       Most of the list is walkable, so directions open in walking mode. */
    var dirBtn    = document.getElementById('explorer-directions');
    var PLACE_URL = dirBtn ? dirBtn.getAttribute('href') : '';

    function setDirections(name, lat, lng) {
      if (!dirBtn) return;
      var dest = (lat && lng) ? (lat + ',' + lng) : name;
      dirBtn.href = 'https://www.google.com/maps/dir/?api=1'
        + '&origin=' + encodeURIComponent(cfg.property.origin)
        + '&destination=' + encodeURIComponent(dest)
        + '&travelmode=walking';
      dirBtn.textContent = 'Directions to ' + name;
    }

    /* Nothing selected: the button just opens the property's own map entry. */
    function resetDirections() {
      if (!dirBtn || !PLACE_URL) return;
      dirBtn.href = PLACE_URL;
      dirBtn.textContent = 'Get Directions';
    }

    function highlightRow(name) {
      var rows = document.querySelectorAll('.explorer-poi-item[data-poi]');
      var row = null;
      rows.forEach(function (r) { if (r.getAttribute('data-poi') === name) row = r; });
      if (!row) return;
      document.querySelectorAll('.explorer-poi-item.is-active').forEach(function (el) { el.classList.remove('is-active'); });
      row.classList.add('is-active');
      row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    cfg.pois.forEach(function (poi) {
      var marker = new google.maps.Marker({
        position: { lat: poi.lat, lng: poi.lng },
        map: map,
        title: poi.name,
        zIndex: 100,
        icon: {
          url: svgMarker(poi.num, poi.color, poi.ink),
          scaledSize: new google.maps.Size(28, 28),
          anchor: new google.maps.Point(14, 14)
        }
      });
      var info = card('<span style="font-size:12px;font-weight:600;color:#0A090C;letter-spacing:0.02em;white-space:nowrap">' + poi.name + '</span>');
      marker.addListener('click', function () {
        if (activeInfo === info) { info.close(); activeInfo = null; return; }
        if (activeInfo) activeInfo.close();
        info.open(map, marker);
        activeInfo = info;
        highlightRow(poi.name);
        setDirections(poi.name, poi.lat, poi.lng);
      });

      byName[poi.name] = { marker: marker, info: info, category: poi.category };
      if (!byCategory[poi.category]) byCategory[poi.category] = [];
      byCategory[poi.category].push(marker);
    });

    map.addListener('click', function () {
      if (activeInfo) { activeInfo.close(); activeInfo = null; }
    });

    /* Clicking a list row pans the map to its marker */
    document.querySelectorAll('.explorer-poi-item[data-poi]').forEach(function (item) {
      var entry = byName[item.getAttribute('data-poi')];
      if (!entry) return;
      item.addEventListener('click', function () {
        map.panTo(entry.marker.getPosition());
        map.setZoom(15);
        if (activeInfo) activeInfo.close();
        entry.info.open(map, entry.marker);
        activeInfo = entry.info;
        setDirections(item.getAttribute('data-poi'),
                      item.getAttribute('data-lat'), item.getAttribute('data-lng'));
        mapEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      });
    });

    /* Tabs additionally filter markers and refit the viewport */
    document.querySelectorAll('.tab-btn[data-tab]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var tab = btn.getAttribute('data-tab');
        Object.keys(byName).forEach(function (name) {
          var entry = byName[name];
          var show  = (tab === 'all') || (entry.category === tab);
          entry.marker.setVisible(show);
          if (!show && activeInfo === entry.info) { entry.info.close(); activeInfo = null; }
        });
        resetDirections();
        if (tab === 'all') {
          map.setCenter(cfg.center);
          map.setZoom(cfg.zoom);
        } else if (byCategory[tab]) {
          var bounds = new google.maps.LatLngBounds();
          byCategory[tab].forEach(function (m) { bounds.extend(m.getPosition()); });
          bounds.extend(propertyMarker.getPosition());
          map.fitBounds(bounds, { top: 60, right: 40, bottom: 60, left: 40 });
        }
      });
    });
  };

  /* ------------------------------------------------------------------
     10. APPFOLIO IFRAME — hide fallback if iframe loads
     ------------------------------------------------------------------ */
  var afIframe   = document.querySelector('.appfolio-frame iframe');
  var afFallback = document.getElementById('appfolio-fallback');

  if (afIframe && afFallback) {
    afIframe.addEventListener('load', function () {
      afFallback.style.display = 'none';
      afIframe.style.position  = 'static';
      afIframe.style.height    = '650px';
    });
  }

  /* ------------------------------------------------------------------
     11. MATTERPORT VIRTUAL-TOUR LIGHTBOX
        Opens for any [data-mp] trigger (floor-plan tour buttons, amenities
        tour button). Modal markup lives in footer.php so it's on every page.
     ------------------------------------------------------------------ */
  (function () {
    var modal = document.getElementById('mp-modal');
    var frame = document.getElementById('mp-modal-iframe');
    var title = document.getElementById('mp-modal-title');
    if (!modal || !frame) return;

    function openTour(url, name) {
      frame.src = url + (url.indexOf('?') > -1 ? '&' : '?') + 'play=1';
      if (title) title.textContent = name ? name + ' — 3D Tour' : '3D Tour';
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('mp-open');
    }
    function closeTour() {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      frame.src = '';
      document.body.classList.remove('mp-open');
    }

    document.addEventListener('click', function (e) {
      var trigger = e.target.closest ? e.target.closest('[data-mp]') : null;
      if (trigger) {
        e.preventDefault();
        openTour(trigger.getAttribute('data-mp'), trigger.getAttribute('data-name'));
        return;
      }
      if (e.target.closest && e.target.closest('[data-mp-close]')) { closeTour(); }
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeTour(); });
  }());

  /* ------------------------------------------------------------------
     10. GENERAL INQUIRY POP-UP
         Markup is rendered server-side by inc/inquiry-popup.php on wp_footer;
         this only handles when it opens and how it closes. Shown once per
         browser session; ?popup=1 forces it for review. Styles: global.css
         section 26.
     ------------------------------------------------------------------ */
  (function () {
    var popup = document.getElementById('inq-popup');
    if (!popup) return;

    var SEEN_KEY   = 'bc-inquiry-popup-seen';
    var OPEN_DELAY = 1400;
    var dialog     = popup.querySelector('.inq-popup__dialog');
    var forced     = popup.hasAttribute('data-inq-forced') ||
                     /[?&]popup=1(&|$)/.test(window.location.search);
    var lastFocused = null;

    function markSeen() {
      try { window.sessionStorage.setItem(SEEN_KEY, '1'); } catch (e) {}
    }

    function alreadySeen() {
      if (forced) return false;
      try { return window.sessionStorage.getItem(SEEN_KEY) === '1'; }
      catch (e) { return false; }   /* storage disabled — just show it */
    }

    function focusable() {
      return Array.prototype.filter.call(
        dialog.querySelectorAll('a[href], button, input, select, textarea'),
        function (el) { return !el.disabled && el.offsetParent !== null; }
      );
    }

    function open() {
      /* Never stack on top of the film, a 3D tour or the photo viewer */
      if (document.body.classList.contains('mp-open')) { window.setTimeout(open, 4000); return; }
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
      if (e.target.closest && e.target.closest('[data-inq-close]')) close();
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

    /* A submission re-renders the page with the pop-up already open (PHP adds
       .is-open + data-inq-forced), so only the idle case needs the timer. */
    if (popup.classList.contains('is-open')) {
      markSeen();
      document.body.classList.add('inq-open');
      return;
    }
    if (alreadySeen()) return;
    window.setTimeout(open, OPEN_DELAY);
  }());

  /* ------------------------------------------------------------------
     14. FILM MODAL
         Any [data-film] trigger (hero button, film bands, amenities panel)
         plays the full VTC film with sound in #film-modal (footer.php). If the
         file is missing the modal shows a "coming soon" card instead of a
         broken player. Styles: global.css 29e.
     ------------------------------------------------------------------ */
  (function () {
    var modal = document.getElementById('film-modal');
    if (!modal) return;
    var video = modal.querySelector('video');
    if (!video) return;
    var lastFocus = null;

    function fallback() { modal.classList.add('is-fallback'); }
    video.addEventListener('error', fallback, true);
    video.querySelectorAll('source').forEach(function (s) { s.addEventListener('error', fallback); });

    /* preload="none" means a missing file is only discovered on play, so ask
       up front — the card is then ready the moment the modal opens. */
    var src = video.querySelector('source') && video.querySelector('source').getAttribute('src');
    if (src && window.fetch && document.querySelector('[data-film]')) {
      fetch(src, { method: 'HEAD' }).then(function (r) { if (!r.ok) fallback(); }).catch(function () {});
    }

    function open() {
      lastFocus = document.activeElement;
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('mp-open');
      if (video.readyState === 0 && video.networkState === 3) fallback();
      if (!modal.classList.contains('is-fallback')) {
        var p = video.play();
        if (p && p.catch) p.catch(function () { if (video.error || video.networkState === 3) fallback(); });
      }
      var btn = modal.querySelector('.mp-modal__close');
      if (btn) btn.focus();
    }
    function close() {
      if (!modal.classList.contains('is-open')) return;
      video.pause();
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('mp-open');
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }

    document.addEventListener('click', function (e) {
      if (!e.target.closest) return;
      if (e.target.closest('[data-film]')) { e.preventDefault(); open(); return; }
      if (e.target.closest('[data-film-close]')) close();
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
  }());

  /* ------------------------------------------------------------------
     15. FORM TIMING
         Records how long the visitor was on the page before submitting a
         form. inc/leads.php treats anything under 3 seconds as a bot; an
         empty value (no JavaScript) is let through.
     ------------------------------------------------------------------ */
  (function () {
    var loaded = Date.now();
    document.addEventListener('submit', function (e) {
      var field = e.target.querySelector && e.target.querySelector('[data-form-elapsed]');
      if (field) field.value = String(Math.floor((Date.now() - loaded) / 1000));
    }, true);
  }());

}());
