/* ==========================================================================
   RIVERBEND AT FLORHAM PARK — STATIC PREVIEW BEHAVIOUR
   ==========================================================================
   Shared by every page in preview/. Each block guards on its own markup, so a
   page only runs what it contains. Forked from Bellclair's preview (which
   mirrors the theme's main.js), without the film modal. No build step, no
   dependencies.
   ========================================================================== */

(function () {
  'use strict';

  var ASSETS = '../theme/riverbend-florhampark/assets/';
  /* AppFolio's city filter returns exactly Riverbend's listings (checked
     2026-10-20); Sterling has no other Florham Park listings. */
  var APPLY_URL = 'https://sterlingproperties.appfolio.com/listings?filters%5Bcities%5D%5B%5D=Florham+Park';
  var MATTERPORT = 'https://my.matterport.com/show/?m=';

  /* Floor plans — from the "Updated Floorplans" sheets (SharePoint,
     Marketing Collateral/Floorplans). Ordered smallest -> largest. Every plan
     is in Buildings 1-18 and has a balcony, a walk-in closet, laundry and a
     garage. Tours are the Matterport models on the AppFolio listings; each
     model's own title names the plan. Florham has no tour yet. */
  var PLANS = {
    madison:    { name: 'Madison', code: 'Buildings 1–18 · Floor 1', bed: 1, bath: 1, sqft: '725',
                  where: 'First floor', img: 'madison.jpg', mp: MATTERPORT + 'qRBhDU6A7ZQ' },
    livingston: { name: 'Livingston', code: 'Buildings 1–18 · Floor 1 · With study', bed: 1, bath: 1, sqft: '800',
                  where: 'First floor · Study', img: 'livingston.jpg', mp: MATTERPORT + 'yGQSWLhuVAq' },
    chatham:    { name: 'Chatham', code: 'Buildings 1–18 · Floor 2', bed: 2, bath: 2, sqft: '1,050',
                  where: 'Second floor', img: 'chatham.jpg', mp: MATTERPORT + 'bbuj3Fchx1H' },
    florham:    { name: 'Florham', code: 'Buildings 1–18 · Floor 2', bed: 2, bath: 2, sqft: '1,225',
                  where: 'Second floor', img: 'florham.jpg', mp: null }
  };
  var DEFAULT_PLAN = 'chatham';

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

  /* --------------------------- 6. photo + floor-plan full-screen viewer
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

  /* ---------------------------------- 7. neighborhood category tabs */
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
        try { ev = new CustomEvent('riverbend:tab', { detail: tab }); }
        catch (err) { ev = document.createEvent('CustomEvent'); ev.initCustomEvent('riverbend:tab', false, false, tab); }
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
var RIVERBEND_HOME = { lat: 40.7533634, lng: -74.3695411 };   /* clubhouse building by the pool (OpenStreetMap way 1368465163) */

function riverbendMapFallback() {
  var el = document.getElementById('poi-map');
  if (!el || el.getAttribute('data-fallback')) return;
  el.setAttribute('data-fallback', '1');
  el.innerHTML = '<iframe title="Map of Riverbend and nearby Florham Park" ' +
    'src="https://www.openstreetmap.org/export/embed.html?bbox=-74.5000%2C40.6950%2C-74.2600%2C40.8150&amp;layer=mapnik&amp;marker=' +
    RIVERBEND_HOME.lat + '%2C' + RIVERBEND_HOME.lng + '"></iframe>';
}
window.gm_authFailure = riverbendMapFallback;
window.setTimeout(function () {
  if (document.getElementById('poi-map') && !(window.google && window.google.maps)) riverbendMapFallback();
}, 8000);

function initMap() {
  var mapEl = document.getElementById('poi-map');
  if (!mapEl) return;

  /* Cream land, sage parks, sky water — the brand palette, kept pale so the
     pins carry the colour. */
  var styles = [
    { elementType: 'geometry', stylers: [{ color: '#f3efe6' }] },
    { elementType: 'labels.icon', stylers: [{ visibility: 'off' }] },
    { elementType: 'labels.text.fill', stylers: [{ color: '#5d5752' }] },
    { elementType: 'labels.text.stroke', stylers: [{ color: '#f8f5ee' }] },
    { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#cddcee' }] },
    { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#ffffff' }] },
    { featureType: 'road.arterial', elementType: 'geometry', stylers: [{ color: '#ece6da' }] },
    { featureType: 'road.highway', elementType: 'geometry', stylers: [{ color: '#f6d3ce' }] },
    { featureType: 'poi.park', elementType: 'geometry', stylers: [{ color: '#dbe6d8' }] },
    { featureType: 'landscape.natural', elementType: 'geometry', stylers: [{ color: '#e6ebdf' }] },
    { featureType: 'transit', elementType: 'geometry', stylers: [{ color: '#e8e2d6' }] },
    { featureType: 'administrative', elementType: 'geometry.stroke', stylers: [{ color: '#d5cbbb' }] }
  ];
  var center = { lat: 40.7600, lng: -74.3900 };
  var zoom = 12;
  var map = new google.maps.Map(mapEl, {
    center: center, zoom: zoom, styles: styles,
    mapTypeControl: false, streetViewControl: false, fullscreenControl: true, zoomControl: true
  });

  /* Keep in sync with .tab-dot--* in global.css */
  var colors = { transit: '#A63F35', dining: '#232121', shopping: '#3F6894', rec: '#56735A', education: '#A0BEE2' };
  var ink = { education: '#232121' };

  function pin(num, color, text) {
    var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">' +
      '<circle cx="14" cy="14" r="13" fill="' + color + '" stroke="#F5F1E9" stroke-width="2.5"/>' +
      '<text x="14" y="19" text-anchor="middle" font-family="Manrope,Arial,sans-serif" font-size="11" font-weight="700" fill="' + text + '">' + num + '</text></svg>';
    return { url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg),
             scaledSize: new google.maps.Size(28, 28), anchor: new google.maps.Point(14, 14) };
  }
  /* Home marker: the brand's round mark — a coral R on charcoal */
  var homeSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 42 42">' +
    '<circle cx="21" cy="21" r="19" fill="#232121" stroke="#EB6F63" stroke-width="2"/>' +
    '<text x="21" y="27.5" text-anchor="middle" font-family="Jost,Century Gothic,sans-serif" font-size="18" font-weight="300" fill="#EB6F63">R</text></svg>';
  var home = new google.maps.Marker({
    position: RIVERBEND_HOME, map: map, title: 'Riverbend at Florham Park', zIndex: 200,
    icon: { url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(homeSvg),
            scaledSize: new google.maps.Size(42, 42), anchor: new google.maps.Point(21, 21) }
  });

  function card(html) {
    return new google.maps.InfoWindow({ content: '<div style="font-family:Manrope,Arial,sans-serif;padding:9px 13px;line-height:1.3">' + html + '</div>' });
  }
  var homeInfo = card('<strong style="font-size:12px;color:#232121;display:block;letter-spacing:0.04em">RIVERBEND AT FLORHAM PARK</strong>' +
    '<span style="font-size:11px;color:#5D5752;display:block;margin-top:2px">1901 Sterling Drive, Florham Park, NJ</span>');
  var active = null;
  function openInfo(info, marker) { if (active && active !== info) active.close(); info.open(map, marker); active = info; }
  home.addListener('click', function () { openInfo(homeInfo, home); });

  /* Riverbend is a driving neighborhood: directions default to driving, and
     switch to walking only for places whose measured time is a walk. */
  var dirBtn = document.getElementById('explorer-directions');
  var placeUrl = dirBtn ? dirBtn.getAttribute('href') : '';
  var ORIGIN = '1901 Sterling Dr, Florham Park, NJ 07932';
  function directionsTo(name, lat, lng, mode) {
    if (!dirBtn) return;
    dirBtn.href = 'https://www.google.com/maps/dir/?api=1&origin=' + encodeURIComponent(ORIGIN) +
      '&destination=' + encodeURIComponent(lat + ',' + lng) + '&travelmode=' + mode;
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
    var time = item.querySelector('.explorer-poi-drive');
    var mode = time && /walk/i.test(time.textContent) ? 'walking' : 'driving';
    var color = colors[cat];
    var marker = new google.maps.Marker({ position: { lat: lat, lng: lng }, map: map, title: name, zIndex: 100,
                                          icon: pin(num, color, ink[cat] || '#F5F1E9') });
    var info = card('<span style="font-size:12px;font-weight:600;color:#232121;letter-spacing:0.02em;white-space:nowrap">' + name + '</span>');
    marker.addListener('click', function () { openInfo(info, marker); highlight(item); directionsTo(name, lat, lng, mode); });
    item.addEventListener('click', function () {
      map.panTo(marker.getPosition()); map.setZoom(14);
      openInfo(info, marker); highlight(item); directionsTo(name, lat, lng, mode);
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

  /* The list filter itself runs in the main block (section 7) so it works even
     on the fallback map; here the map just follows the active tab. */
  document.addEventListener('riverbend:tab', function (e) {
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
