/* ==========================================================================
   BELLCLAIR AT MONTCLAIR — STATIC PREVIEW BEHAVIOUR
   ==========================================================================
   Shared by every page in preview/. Each block guards on its own markup, so a
   page only runs what it contains. Mirrors the Rivergate preview behaviour
   (and the theme's main.js), plus Bellclair's film modal and amenity photos.
   No build step, no dependencies.
   ========================================================================== */

(function () {
  'use strict';

  var ASSETS = '../theme/bellclair-montclair/assets/';
  var APPLY_URL = 'https://sterlingproperties.appfolio.com/listings?filters%5Bproperty_list%5D=BELLCLAIRE%2C+LLC';
  var MATTERPORT = 'https://my.matterport.com/show/?m=';

  /* Floor plans — from Nina's "Updated Floorplans" sheets (SharePoint,
     Marketing Collateral/Floorplans). Ordered smallest -> largest. Tours are
     Matterport models whose titles name the plan. The Duplex sheet has specs
     but no drawing, so it renders the placeholder panel. */
  var PLANS = {
    calloway:   { name: 'Calloway', code: '7 Bell St · Floor 1 & 8 Bell St · Floor 3', bed: 1, bath: 1, sqft: '745',
                  where: '7 & 8 Bell Street', img: 'calloway.jpg', mp: null },
    armstrong:  { name: 'Armstrong', code: '7 Bell St · Floors 1–4', bed: 1, bath: 1, sqft: '775',
                  where: '7 Bell Street', img: 'armstrong.jpg', mp: MATTERPORT + 'PVaodWhQSWT' },
    holloway:   { name: 'Holloway', code: '691 Bloomfield Ave · Floors 1 & 2 · Loft', bed: 1, bath: 1, sqft: '825',
                  where: '691 Bloomfield Avenue', img: 'holloway.jpg', mp: null },
    basie:      { name: 'Basie', code: '7 Bell St · Floor 1', bed: 1, bath: 1, sqft: '850',
                  where: '7 Bell Street', img: 'basie.jpg', mp: null },
    dorsey:     { name: 'Dorsey', code: '7 Bell St · Floors 1–4', bed: 2, bath: 1, sqft: '1,075',
                  where: '7 Bell Street', img: 'dorsey.jpg', mp: MATTERPORT + 'hrsGWLg4gYT' },
    ellington:  { name: 'Ellington', code: '7 Bell St · Floors 1–4 & 8 Bell St · Floors 1–2', bed: 2, bath: 2, sqft: '1,125',
                  where: '7 & 8 Bell Street', img: 'ellington.jpg', mp: MATTERPORT + 'ah1D28hoCbj' },
    duplex:     { name: 'Duplex', code: '8 Bell St · Floors 1 & 2', bed: 2, bath: 2, sqft: '1,300',
                  where: '8 Bell Street', img: null, mp: null },
    fitzgerald: { name: 'Fitzgerald', code: '8 Bell St · Floor 3 · Two levels', bed: 2, bath: 1.5, sqft: '1,383',
                  where: '8 Bell Street', img: 'fitzgerald.jpg', mp: null }
  };
  var DEFAULT_PLAN = 'dorsey';

  function $(sel, root) { return (root || document).querySelector(sel); }
  function $$(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }

  /* ---------------------------------------------------- 1. header + nav */
  var header = $('#site-header');
  if (header) {
    var onScroll = function () { header.classList.toggle('is-scrolled', window.scrollY > 10); };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  var toggle = $('#nav-toggle');
  var overlay = $('#nav-overlay');
  if (toggle && overlay) {
    var closeNav = function () {
      overlay.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('nav-is-open');
    };
    toggle.addEventListener('click', function () {
      var open = !overlay.classList.contains('is-open');
      overlay.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.classList.toggle('nav-is-open', open);
    });
    $$('a', overlay).forEach(function (a) { a.addEventListener('click', closeNav); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeNav(); });
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
  $$('.amenity-grid, .residence-grid, .communities-teaser, .gallery-grid, .how-it-works, .portal-grid').forEach(function (grid) {
    $$('[data-animate]', grid).forEach(function (el, i) { el.style.transitionDelay = (i % 6 * 70) + 'ms'; });
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

  /* ---------------------------- 3. amenities: expand + swap the photo */
  var amenityPhoto = $('.amenities-photo img');
  var amenityCaption = $('.amenities-photo__caption');
  var defaultPhoto = amenityPhoto ? amenityPhoto.getAttribute('src') : '';
  var defaultCaption = amenityCaption ? amenityCaption.textContent : '';

  function showAmenityPhoto(src, caption) {
    if (!amenityPhoto || !src || amenityPhoto.getAttribute('src') === src) {
      if (amenityCaption && caption) amenityCaption.textContent = caption;
      return;
    }
    amenityPhoto.classList.add('is-swapping');
    window.setTimeout(function () {
      amenityPhoto.onload = function () { amenityPhoto.classList.remove('is-swapping'); };
      amenityPhoto.setAttribute('src', src);
      if (amenityCaption) amenityCaption.textContent = caption;
    }, 220);
  }

  $$('.amenity').forEach(function (item) {
    var head = $('.amenity__head', item);
    var panel = $('.amenity__panel', item);
    if (!head || !panel) return;
    head.addEventListener('click', function () {
      var open = item.classList.toggle('is-open');
      head.setAttribute('aria-expanded', open ? 'true' : 'false');
      panel.style.maxHeight = open ? panel.scrollHeight + 'px' : '';
      if (open) {
        showAmenityPhoto(item.getAttribute('data-amenity-photo') || defaultPhoto,
                         item.getAttribute('data-amenity-name') || defaultCaption);
      } else if (!$('.amenity.is-open')) {
        showAmenityPhoto(defaultPhoto, defaultCaption);
      }
    });
  });

  /* ------------------------------------------ 4. floor-plan selector */
  (function () {
    var list = $('#fp-list');
    var detail = $('#fp-detail');
    var selector = $('.fp-selector');
    if (!list || !detail || !selector) return;
    var options = $$('.fp-option', list);

    options.forEach(function (opt) {
      var p = PLANS[opt.getAttribute('data-plan')];
      if (!p) return;
      opt.innerHTML =
        '<span class="fp-option__specs">' + p.bed + ' Bed' + (p.bath !== '—' ? ' / ' + p.bath + ' Bath' : '') +
          (p.where ? ' · ' + p.where : '') + '</span>' +
        '<span class="fp-option__name">' + p.name + '</span>' +
        '<span class="fp-option__size">' + p.sqft + ' sq ft</span>' +
        (p.mp ? '<span class="fp-option__tag">3D Tour</span>' : '');
    });

    function render(key) {
      var p = PLANS[key];
      var plan = p.img
        ? '<img class="fp-plan-img" src="' + ASSETS + 'images/floorplans/' + p.img + '" alt="' + p.name +
          ' floor plan — ' + p.bed + ' bed, ' + p.bath + ' bath, ' + p.sqft + ' sq ft" loading="lazy">'
        : '<div class="fp-diagram"><span class="fp-diagram__watermark">' + p.name + '</span>' +
          '<span class="fp-diagram__note">Floor plan drawing coming soon</span></div>';
      var tour = p.mp
        ? '<button type="button" class="btn btn--secondary" data-mp="' + p.mp + '" data-name="' + p.name + '">Take a 3D Tour</button>'
        : '';
      detail.innerHTML =
        '<p class="fp-detail__eyebrow">' + p.code + '</p>' +
        '<h3 class="fp-detail__name">' + p.name + '</h3>' +
        '<div class="fp-detail__specs">' +
          '<div class="fp-spec"><span>Bedrooms</span><span>' + p.bed + '</span></div>' +
          '<div class="fp-spec"><span>Bathrooms</span><span>' + p.bath + '</span></div>' +
          '<div class="fp-spec"><span>Square Feet</span><span>' + p.sqft + '</span></div>' +
          '<div class="fp-spec"><span>Laundry</span><span>' + (p.img ? 'In-Unit' : '—') + '</span></div>' +
        '</div>' + plan +
        '<div class="fp-detail__actions">' + tour +
          '<a class="btn btn--primary" href="availability.html">Check Availability</a>' +
          '<a class="btn btn--secondary" href="' + APPLY_URL + '" target="_blank" rel="noopener noreferrer">Apply Now</a>' +
        '</div>';
    }

    /* Below 900px the panel opens inline under the tapped plan (accordion);
       above it sits in the second column (tabs). Same as Rivergate. */
    var mq = window.matchMedia('(max-width: 900px)');
    var current = null;

    function place(opt) {
      if (mq.matches) {
        detail.classList.add('fp-detail--inline');
        if (detail.previousElementSibling !== opt) opt.insertAdjacentElement('afterend', detail);
      } else {
        detail.classList.remove('fp-detail--inline');
        if (detail.parentNode !== selector) selector.appendChild(detail);
      }
    }
    function syncAria() {
      var mobile = mq.matches;
      list.setAttribute('role', mobile ? 'group' : 'tablist');
      detail.setAttribute('role', mobile ? 'region' : 'tabpanel');
      options.forEach(function (o) {
        var on = o.classList.contains('is-active');
        if (mobile) {
          o.removeAttribute('role'); o.removeAttribute('aria-selected');
          o.setAttribute('aria-controls', 'fp-detail'); o.setAttribute('aria-expanded', on ? 'true' : 'false');
        } else {
          o.setAttribute('role', 'tab'); o.removeAttribute('aria-expanded'); o.removeAttribute('aria-controls');
          o.setAttribute('aria-selected', on ? 'true' : 'false');
        }
      });
    }
    function close() {
      options.forEach(function (o) { o.classList.remove('is-active'); });
      current = null; detail.hidden = true; detail.innerHTML = ''; syncAria();
    }
    function select(opt) {
      options.forEach(function (o) { o.classList.remove('is-active'); });
      opt.classList.add('is-active'); current = opt; detail.hidden = false;
      render(opt.getAttribute('data-plan')); place(opt); syncAria();
    }
    options.forEach(function (opt) {
      opt.addEventListener('click', function () {
        if (mq.matches && current === opt) { close(); return; }
        select(opt);
        if (mq.matches) opt.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      });
    });
    function applyBreakpoint() {
      if (current) { place(current); syncAria(); return; }
      if (mq.matches) close();
      else select($('.fp-option[data-plan="' + DEFAULT_PLAN + '"]') || options[0]);
    }
    applyBreakpoint();
    if (mq.addEventListener) mq.addEventListener('change', applyBreakpoint);
    else if (mq.addListener) mq.addListener(applyBreakpoint);
  }());

  /* ------------------------------------------- 5. Matterport lightbox */
  (function () {
    var modal = $('#mp-modal');
    var frame = $('#mp-modal-iframe');
    var title = $('#mp-modal-title');
    if (!modal || !frame) return;
    function open(url, name) {
      frame.src = url + (url.indexOf('?') > -1 ? '&' : '?') + 'play=1';
      title.textContent = name ? name + ' — 3D Tour' : '3D Tour';
      modal.classList.add('is-open'); modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('mp-open');
    }
    function close() {
      if (!modal.classList.contains('is-open')) return;
      modal.classList.remove('is-open'); modal.setAttribute('aria-hidden', 'true');
      frame.src = ''; document.body.classList.remove('mp-open');
    }
    document.addEventListener('click', function (e) {
      var t = e.target.closest('[data-mp]');
      if (t) { e.preventDefault(); open(t.getAttribute('data-mp'), t.getAttribute('data-name')); return; }
      if (e.target.closest('[data-mp-close]')) close();
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
  }());

  /* ---------------------------------------------------- 6. film modal
     Plays the full VTC film with sound
     (theme/bellclair-montclair/assets/video/bellclair-film.mp4). If the file
     is ever missing the modal shows a "coming soon" card instead of a broken
     player. */
  (function () {
    var modal = $('#film-modal');
    if (!modal) return;
    var video = $('video', modal);
    var lastFocus = null;

    function fallback() { modal.classList.add('is-fallback'); }
    video.addEventListener('error', fallback, true);
    $$('source', video).forEach(function (s) { s.addEventListener('error', fallback); });
    /* preload="none" means a missing file is only discovered on play, so ask
       up front — the card is then ready the moment the modal opens. */
    var src = $('source', video) && $('source', video).getAttribute('src');
    if (src && window.fetch) {
      fetch(src, { method: 'HEAD' }).then(function (r) { if (!r.ok) fallback(); }).catch(function () {});
    }

    function open() {
      lastFocus = document.activeElement;
      modal.classList.add('is-open'); modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('mp-open');
      if (video.readyState === 0 && video.networkState === 3) modal.classList.add('is-fallback');
      if (!modal.classList.contains('is-fallback')) {
        var p = video.play();
        if (p && p.catch) p.catch(function () { if (video.error || video.networkState === 3) fallback(); });
      }
      var btn = $('.mp-modal__close', modal);
      if (btn) btn.focus();
    }
    function close() {
      if (!modal.classList.contains('is-open')) return;
      video.pause();
      modal.classList.remove('is-open'); modal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('mp-open');
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }
    document.addEventListener('click', function (e) {
      if (e.target.closest('[data-film]')) { e.preventDefault(); open(); return; }
      if (e.target.closest('[data-film-close]')) close();
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
  }());

  /* --------------------------- 7. photo + floor-plan full-screen viewer
     Same component as the theme's main.js section 13 (.fp-lightbox): floor
     plans open alone; gallery tiles open in gallery mode with prev/next. */
  (function () {
    var tiles = $$('.gallery-item');
    var plansOnPage = $('.fp-selector');
    if (!tiles.length && !plansOnPage) return;

    var box = document.createElement('div');
    box.className = 'fp-lightbox';
    box.id = 'fp-lightbox';
    box.setAttribute('aria-hidden', 'true');
    box.innerHTML =
      '<div class="fp-lightbox__backdrop" data-lb-close></div>' +
      '<div class="fp-lightbox__dialog" role="dialog" aria-modal="true" aria-label="Photo">' +
        '<button class="fp-lightbox__close" type="button" aria-label="Close" data-lb-close>&times;</button>' +
        '<button class="fp-lightbox__nav fp-lightbox__nav--prev" type="button" aria-label="Previous photo">&#8249;</button>' +
        '<img id="fp-lightbox-img" alt="">' +
        '<button class="fp-lightbox__nav fp-lightbox__nav--next" type="button" aria-label="Next photo">&#8250;</button>' +
        '<p class="fp-lightbox__caption" id="fp-lightbox-caption"></p>' +
      '</div>';
    document.body.appendChild(box);

    var img = $('#fp-lightbox-img', box);
    var caption = $('#fp-lightbox-caption', box);
    var photos = [];
    var index = 0;
    var lastFocus = null;

    function show(i) {
      index = (i + photos.length) % photos.length;
      var tile = photos[index];
      var tImg = $('img', tile);
      img.src = tImg.currentSrc || tImg.src;
      img.alt = tImg.alt;
      var cap = $('.gallery-item__caption', tile);
      caption.textContent = cap ? cap.textContent : '';
    }
    function open(gallery) {
      lastFocus = document.activeElement;
      box.classList.add('is-open');
      box.classList.toggle('is-gallery', gallery);
      box.setAttribute('aria-hidden', 'false');
      document.body.classList.add('mp-open');
      $('.fp-lightbox__close', box).focus();
    }
    function close() {
      if (!box.classList.contains('is-open')) return;
      box.classList.remove('is-open', 'is-gallery');
      box.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('mp-open');
      img.removeAttribute('src');
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }

    tiles.forEach(function (t) {
      t.setAttribute('tabindex', '0');
      t.setAttribute('role', 'button');
      var tImg = $('img', t);
      if (tImg && tImg.alt) t.setAttribute('aria-label', 'View larger: ' + tImg.alt);
    });

    document.addEventListener('click', function (e) {
      if (e.target.closest('[data-lb-close]')) { close(); return; }
      if (e.target.closest('.fp-lightbox__nav--prev')) { show(index - 1); return; }
      if (e.target.closest('.fp-lightbox__nav--next')) { show(index + 1); return; }
      var plan = e.target.closest('.fp-plan-img');
      if (plan) {
        img.src = plan.currentSrc || plan.src; img.alt = plan.alt; caption.textContent = '';
        open(false); return;
      }
      var tile = e.target.closest('.gallery-item');
      if (tile) {
        photos = tiles.filter(function (el) { return el.offsetParent !== null; });
        open(true); show(photos.indexOf(tile));
      }
    });
    document.addEventListener('keydown', function (e) {
      if (box.classList.contains('is-open')) {
        if (e.key === 'Escape') close();
        else if (box.classList.contains('is-gallery') && e.key === 'ArrowLeft') show(index - 1);
        else if (box.classList.contains('is-gallery') && e.key === 'ArrowRight') show(index + 1);
        return;
      }
      if ((e.key === 'Enter' || e.key === ' ') && e.target.classList && e.target.classList.contains('gallery-item')) {
        e.preventDefault(); e.target.click();
      }
    });
  }());

  /* ---------------------------------- 8. neighborhood category tabs */
  (function () {
    var explorer = $('#neighborhood-explorer');
    if (!explorer) return;
    var tabs = $$('.tab-btn', explorer);
    tabs.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var tab = btn.getAttribute('data-tab');
        tabs.forEach(function (b) {
          b.classList.toggle('is-active', b === btn);
          b.setAttribute('aria-selected', b === btn ? 'true' : 'false');
        });
        explorer.setAttribute('data-active-tab', tab);
        $$('.explorer-poi-item.is-active', explorer).forEach(function (el) { el.classList.remove('is-active'); });
        var ev;
        try { ev = new CustomEvent('bellclair:tab', { detail: tab }); }
        catch (err) { ev = document.createEvent('CustomEvent'); ev.initCustomEvent('bellclair:tab', false, false, tab); }
        document.dispatchEvent(ev);
      });
    });
  }());
}());


/* ==========================================================================
   NEIGHBORHOOD MAP — Google Maps callback (index.html only)
   ==========================================================================
   Markers are read from the list markup (.explorer-poi-item data-lat/lng), so
   each place is defined once. If Google rejects the API key on this domain,
   gm_authFailure swaps in an OpenStreetMap embed so the section never shows a
   grey error box to the client.
   ========================================================================== */
var BELLCLAIR_HOME = { lat: 40.817948, lng: -74.222495 };   /* 7 Bell St (US Census geocoder) */

function bellclairMapFallback() {
  var el = document.getElementById('poi-map');
  if (!el || el.getAttribute('data-fallback')) return;
  el.setAttribute('data-fallback', '1');
  el.innerHTML = '<iframe title="Map of Bellclair and nearby Montclair" ' +
    'src="https://www.openstreetmap.org/export/embed.html?bbox=-74.2470%2C40.7990%2C-74.1880%2C40.8420&amp;layer=mapnik&amp;marker=' +
    BELLCLAIR_HOME.lat + '%2C' + BELLCLAIR_HOME.lng + '"></iframe>';
}
window.gm_authFailure = bellclairMapFallback;
window.setTimeout(function () {
  if (document.getElementById('poi-map') && !(window.google && window.google.maps)) bellclairMapFallback();
}, 8000);

function initMap() {
  var mapEl = document.getElementById('poi-map');
  if (!mapEl) return;

  var styles = [
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
  var center = { lat: 40.8240, lng: -74.2140 };
  var zoom = 13;
  var map = new google.maps.Map(mapEl, {
    center: center, zoom: zoom, styles: styles,
    mapTypeControl: false, streetViewControl: false, fullscreenControl: true, zoomControl: true
  });

  /* Keep in sync with .tab-dot--* in global.css */
  var colors = { transit: '#A16D29', dining: '#0A090C', shopping: '#B6984E', rec: '#6B6458', education: '#CFC3A6' };
  var ink = { shopping: '#0A090C', education: '#0A090C' };

  function pin(num, color, text) {
    var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">' +
      '<circle cx="14" cy="14" r="13" fill="' + color + '" stroke="#F8F6F1" stroke-width="2.5"/>' +
      '<text x="14" y="19" text-anchor="middle" font-family="Questrial,Arial,sans-serif" font-size="11" font-weight="700" fill="' + text + '">' + num + '</text></svg>';
    return { url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg),
             scaledSize: new google.maps.Size(28, 28), anchor: new google.maps.Point(14, 14) };
  }
  var homeSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">' +
    '<rect x="6" y="6" width="28" height="28" transform="rotate(45 20 20)" fill="#0A090C" stroke="#B6984E" stroke-width="2"/>' +
    '<text x="20" y="25" text-anchor="middle" font-family="Poiret One,Georgia,serif" font-size="15" fill="#E3D5B4">B</text></svg>';
  var home = new google.maps.Marker({
    position: BELLCLAIR_HOME, map: map, title: 'Bellclair at Montclair', zIndex: 200,
    icon: { url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(homeSvg),
            scaledSize: new google.maps.Size(40, 40), anchor: new google.maps.Point(20, 20) }
  });

  function card(html) {
    return new google.maps.InfoWindow({ content: '<div style="font-family:Questrial,Arial,sans-serif;padding:9px 13px;line-height:1.3">' + html + '</div>' });
  }
  var homeInfo = card('<strong style="font-size:12px;color:#0A090C;display:block;letter-spacing:0.04em">BELLCLAIR AT MONTCLAIR</strong>' +
    '<span style="font-size:11px;color:#5C554B;display:block;margin-top:2px">7 &amp; 8 Bell Street, Montclair, NJ</span>');
  var active = null;
  function openInfo(info, marker) { if (active && active !== info) active.close(); info.open(map, marker); active = info; }
  home.addListener('click', function () { openInfo(homeInfo, home); });

  var dirBtn = document.getElementById('explorer-directions');
  var placeUrl = dirBtn ? dirBtn.getAttribute('href') : '';
  var ORIGIN = '7 Bell St, Montclair, NJ 07042';
  function directionsTo(name, lat, lng) {
    if (!dirBtn) return;
    dirBtn.href = 'https://www.google.com/maps/dir/?api=1&origin=' + encodeURIComponent(ORIGIN) +
      '&destination=' + encodeURIComponent(lat + ',' + lng) + '&travelmode=walking';
    dirBtn.textContent = 'Directions to ' + name;
  }
  function resetDirections() { if (dirBtn) { dirBtn.href = placeUrl; dirBtn.textContent = 'Get Directions'; } }

  var byName = {};
  var byCat = {};
  Array.prototype.forEach.call(document.querySelectorAll('.explorer-poi-item[data-poi]'), function (item) {
    var cat = item.closest('[data-category]').getAttribute('data-category');
    var name = item.getAttribute('data-poi');
    var lat = parseFloat(item.getAttribute('data-lat'));
    var lng = parseFloat(item.getAttribute('data-lng'));
    var num = item.querySelector('.poi-num-badge').textContent;
    var color = colors[cat];
    var marker = new google.maps.Marker({ position: { lat: lat, lng: lng }, map: map, title: name, zIndex: 100,
                                          icon: pin(num, color, ink[cat] || '#F8F6F1') });
    var info = card('<span style="font-size:12px;font-weight:600;color:#0A090C;letter-spacing:0.02em;white-space:nowrap">' + name + '</span>');
    marker.addListener('click', function () { openInfo(info, marker); highlight(item); directionsTo(name, lat, lng); });
    item.addEventListener('click', function () {
      map.panTo(marker.getPosition()); map.setZoom(15);
      openInfo(info, marker); highlight(item); directionsTo(name, lat, lng);
    });
    byName[name] = { marker: marker, info: info, cat: cat };
    (byCat[cat] = byCat[cat] || []).push(marker);
  });

  function highlight(item) {
    Array.prototype.forEach.call(document.querySelectorAll('.explorer-poi-item.is-active'), function (el) { el.classList.remove('is-active'); });
    item.classList.add('is-active');
    item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  map.addListener('click', function () { if (active) { active.close(); active = null; } });

  /* The list filter itself runs in the main block (section 8) so it works even
     on the fallback map; here the map just follows the active tab. */
  document.addEventListener('bellclair:tab', function (e) {
    var tab = e.detail;
    resetDirections();
    Object.keys(byName).forEach(function (n) {
      var entry = byName[n];
      var show = tab === 'all' || entry.cat === tab;
      entry.marker.setVisible(show);
      if (!show && active === entry.info) { entry.info.close(); active = null; }
    });
    if (tab === 'all') { map.setCenter(center); map.setZoom(zoom); }
    else if (byCat[tab]) {
      var b = new google.maps.LatLngBounds();
      byCat[tab].forEach(function (m) { b.extend(m.getPosition()); });
      b.extend(home.getPosition());
      map.fitBounds(b, { top: 60, right: 40, bottom: 60, left: 40 });
    }
  });
}
