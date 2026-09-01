/**
 * Rivergate Bordentown — main.js
 * Shared JavaScript for all pages.
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
      var open = item.classList.toggle('is-open');
      head.setAttribute('aria-expanded', open ? 'true' : 'false');
      panel.style.maxHeight = open ? panel.scrollHeight + 'px' : '';
    });
  });

  /* ------------------------------------------------------------------
     6. FLOOR PLAN SELECTOR (homepage)
        PLANS data is injected as an inline <script> by the rivergate/floor-plans
        block (blocks/floor-plans/render.php) via wp_add_inline_script() so it
        reflects the Floor Plan CPT.
     ------------------------------------------------------------------ */
  // Safety guard — PLANS should always be set by the PHP template on the
  // homepage, but default to an empty object on other pages.
  var PLANS     = window.PLANS || {};
  var APPLY_URL = 'https://sterlingproperties.appfolio.com/listings?filters%5Bproperty_list%5D=RIVERGATE+BORDENTOWN';
  var AVAIL_URL = document.querySelector('meta[name="rg-avail-url"]')
    ? document.querySelector('meta[name="rg-avail-url"]').getAttribute('content')
    : '/availability/';

  var fpOptions = document.querySelectorAll('.fp-option');
  var fpDetail  = document.getElementById('fp-detail');

  if (fpOptions.length && fpDetail) {
    fpOptions.forEach(function (opt) {
      var p = PLANS[opt.getAttribute('data-plan')];
      if (!p) return;
      opt.innerHTML =
        '<span class="fp-option__specs">' + p.bed + ' Bed / ' + p.bath + ' Bath / Balcony</span>' +
        '<span class="fp-option__name">' + p.name + '</span>' +
        '<span class="fp-option__size">' + p.sqft + ' sq ft</span>' +
        (p.tag ? '<span class="fp-option__tag">' + p.tag + '</span>' : '');
    });

    function renderDetail(key) {
      var p = PLANS[key];
      if (!p) return;
      var img = p.image
        ? '<img class="fp-plan-img" src="' + p.image + '" alt="' + p.name + ' floor plan — ' + p.bed + ' bed, ' + p.bath + ' bath, ' + p.sqft + ' sq ft" loading="lazy">'
        : '';
      var tour = p.mp
        ? '<button type="button" class="btn btn--secondary" data-mp="' + p.mp + '" data-name="' + p.name + '">Take a Virtual Tour</button>'
        : '<span class="btn btn--secondary fp-vtour-disabled" aria-disabled="true" title="Virtual tour coming soon">Virtual Tour Coming Soon</span>';
      var last = p.pdf
        ? '<a class="btn btn--secondary" href="' + p.pdf + '" target="_blank" rel="noopener noreferrer">Download PDF</a>'
        : '<a class="btn btn--secondary" href="' + APPLY_URL + '" target="_blank" rel="noopener noreferrer">Apply Now</a>';
      fpDetail.innerHTML =
        '<p class="fp-detail__eyebrow">' + p.code + (p.tag ? ' · ' + p.tag : '') + '</p>' +
        '<h3 class="fp-detail__name">' + p.name + '</h3>' +
        '<div class="fp-detail__specs">' +
          '<div class="fp-spec"><span>Bedrooms</span><span>' + p.bed + '</span></div>' +
          '<div class="fp-spec"><span>Bathrooms</span><span>' + p.bath + '</span></div>' +
          '<div class="fp-spec"><span>Square Feet</span><span>' + p.sqft + '</span></div>' +
          (p.rent ? '<div class="fp-spec"><span>Starting Rent</span><span>' + p.rent + '</span></div>' : '') +
          '<div class="fp-spec"><span>Balcony</span><span>Yes</span></div>' +
        '</div>' +
        img +
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

    /* Desktop opens on The Wright so the panel is never blank. Mobile starts
       collapsed so the list stays short and the detail opens under whichever plan
       the visitor taps. */
    function applyFpBreakpoint() {
      if (fpCurrent) { placeDetail(fpCurrent); syncFpAria(); return; }
      if (fpMq.matches) {
        closePlan();
      } else {
        var defaultOpt = document.querySelector('.fp-option[data-plan="wright"]') || fpOptions[0];
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
     13. FLOOR-PLAN LIGHTBOX — click a plan image to view it full screen
         (Andrew, 8/26). Delegated, so it covers the homepage selector's
         JS-rendered image and the floor-plans page cards alike.
     ------------------------------------------------------------------ */
  (function () {
    var box = document.getElementById('fp-lightbox');
    var img = document.getElementById('fp-lightbox-img');
    if (!box || !img) return;
    var lastFocused = null;

    function open(src, alt) {
      lastFocused = document.activeElement;
      img.setAttribute('src', src);
      img.setAttribute('alt', alt || '');
      box.classList.add('is-open');
      box.setAttribute('aria-hidden', 'false');
      document.body.classList.add('mp-open');      /* reuse the scroll lock */
      var btn = box.querySelector('.fp-lightbox__close');
      if (btn) btn.focus();
    }

    function close() {
      box.classList.remove('is-open');
      box.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('mp-open');
      img.setAttribute('src', '');
      if (lastFocused && lastFocused.focus) lastFocused.focus();
    }

    document.addEventListener('click', function (e) {
      if (!e.target.closest) return;
      if (e.target.closest('[data-fpl-close]')) { close(); return; }
      var plan = e.target.closest('.fp-plan-img, .plan-card__img');
      if (plan) { open(plan.getAttribute('src'), plan.getAttribute('alt')); }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && box.classList.contains('is-open')) close();
    });
  }());

  /* ------------------------------------------------------------------
     12. AMENITY PHOTO — follows the selected amenity (Andrew, 8/26)
         Each .amenity carries data-amenity-photo; opening one swaps the
         right-hand photo. Amenities without their own photo leave the
         default in place rather than showing something unrelated.
     ------------------------------------------------------------------ */
  (function () {
    var photo = document.querySelector('.amenities-photo img');
    var items = document.querySelectorAll('.amenity[data-amenity-photo]');
    if (!photo || !items.length) return;

    var defaultSrc = photo.getAttribute('src');
    var defaultAlt = photo.getAttribute('alt');

    items.forEach(function (item) {
      var head = item.querySelector('.amenity__head');
      if (!head) return;
      head.addEventListener('click', function () {
        /* section 5 toggles .is-open; only follow an amenity being opened */
        if (!item.classList.contains('is-open')) {
          photo.setAttribute('src', defaultSrc);
          photo.setAttribute('alt', defaultAlt);
          return;
        }
        var src = item.getAttribute('data-amenity-photo');
        if (!src) return;                       /* no photo yet — keep the default */
        photo.setAttribute('src', src);
        photo.setAttribute('alt', item.getAttribute('data-amenity-name') || defaultAlt);
      });
    });
  }());

  /* ------------------------------------------------------------------
     11. NEIGHBORHOOD MAP — numbered POI markers, list/tab wiring
         Data comes from window.RG_MAP, injected by the
         rivergate/neighborhood-explorer block (blocks/neighborhood-explorer/
         render.php) so the markers always match the rendered list.
         The Maps API calls initRivergateMap when it finishes loading; it is
         enqueued with a dependency on this file so this runs first.
         Section 9 above owns the tab/row UI state — this only adds the map
         behaviour on top, so the list still works if the API fails to load.
     ------------------------------------------------------------------ */
  window.initRivergateMap = function () {
    var cfg   = window.RG_MAP;
    var mapEl = document.getElementById('poi-map');
    if (!cfg || !mapEl || !window.google || !google.maps) return;

    var mapStyles = [
      { elementType: 'geometry', stylers: [{ color: '#f0f0f0' }] },
      { elementType: 'labels.icon', stylers: [{ visibility: 'off' }] },
      { elementType: 'labels.text.fill', stylers: [{ color: '#6b6b6b' }] },
      { elementType: 'labels.text.stroke', stylers: [{ color: '#f5f5f5' }] },
      { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#0472BB' }] },
      { featureType: 'water', elementType: 'labels.text.fill', stylers: [{ color: '#a8c5d8' }] },
      { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#ffffff' }] },
      { featureType: 'road.arterial', elementType: 'geometry', stylers: [{ color: '#e8e8e8' }] },
      { featureType: 'road.highway', elementType: 'geometry', stylers: [{ color: '#d8d8d8' }] },
      { featureType: 'poi.park', elementType: 'geometry', stylers: [{ color: '#e8ede8' }] },
      { featureType: 'transit', elementType: 'geometry', stylers: [{ color: '#e8e8e8' }] },
      { featureType: 'administrative', elementType: 'geometry.stroke', stylers: [{ color: '#c0c0c0' }] },
      { featureType: 'landscape', elementType: 'geometry', stylers: [{ color: '#f0f0f0' }] }
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
        + '<circle cx="14" cy="14" r="13" fill="' + color + '" stroke="#ffffff" stroke-width="2.5"/>'
        + '<text x="14" y="19" text-anchor="middle" font-family="Inter,Arial,sans-serif" font-size="11" font-weight="700" fill="' + (ink || '#ffffff') + '">' + num + '</text>'
        + '</svg>';
      return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
    }

    var homeSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">'
      + '<circle cx="18" cy="18" r="17" fill="#0472BB" stroke="#ffffff" stroke-width="3"/>'
      + '<path d="M18 10 L26 17 L24 17 L24 26 L20 26 L20 21 L16 21 L16 26 L12 26 L12 17 L10 17 Z" fill="#ffffff"/>'
      + '</svg>';

    var activeInfo    = null;
    var byName        = {};
    var byCategory    = {};

    var propertyMarker = new google.maps.Marker({
      position: { lat: cfg.property.lat, lng: cfg.property.lng },
      map: map,
      title: cfg.property.title,
      zIndex: 200,
      icon: {
        url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(homeSvg),
        scaledSize: new google.maps.Size(36, 36),
        anchor: new google.maps.Point(18, 18)
      }
    });
    var propertyInfo = new google.maps.InfoWindow({
      content: '<div style="font-family:Inter,sans-serif;padding:10px 14px;line-height:1.3">'
        + '<strong style="font-size:12px;color:#1C2B3A;display:block;letter-spacing:0.02em">' + cfg.property.title + '</strong>'
        + '<span style="font-size:11px;color:#888;margin-top:2px;display:block">' + cfg.property.address + '</span></div>'
    });
    propertyMarker.addListener('mouseover', function () { propertyInfo.open(map, propertyMarker); });
    propertyMarker.addListener('mouseout', function () { if (activeInfo !== propertyInfo) propertyInfo.close(); });
    propertyMarker.addListener('click', function () {
      if (activeInfo) activeInfo.close();
      propertyInfo.open(map, propertyMarker);
      activeInfo = propertyInfo;
    });

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
      var info = new google.maps.InfoWindow({
        content: '<div style="font-family:Inter,sans-serif;padding:8px 12px;line-height:1;display:flex;align-items:center;gap:8px">'
          + '<span style="flex-shrink:0;width:20px;height:20px;border-radius:50%;background:' + poi.color + ';display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:' + poi.ink + '">' + poi.num + '</span>'
          + '<span style="font-size:12px;font-weight:600;color:#1C2B3A;letter-spacing:0.02em;white-space:nowrap">' + poi.name + '</span></div>'
      });
      marker.addListener('mouseover', function () { info.open(map, marker); });
      marker.addListener('mouseout', function () { if (activeInfo !== info) info.close(); });
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

    /* Get Directions routes FROM the property TO whichever place is selected,
       rather than just dropping a pin on the property's own address. */
    var ORIGIN = '500 Bluff View Circle, Bordentown, NJ 08505';
    var dirBtn = document.getElementById('explorer-directions');

    function setDirections(name, lat, lng) {
      if (!dirBtn) return;
      var dest = (lat && lng) ? (lat + ',' + lng) : name;
      dirBtn.href = 'https://www.google.com/maps/dir/?api=1'
        + '&origin=' + encodeURIComponent(ORIGIN)
        + '&destination=' + encodeURIComponent(dest)
        + '&travelmode=driving';
      dirBtn.textContent = 'Directions to ' + name;
    }

    function highlightRow(name) {
      var row = document.querySelector('.explorer-poi-item[data-poi="' + name + '"]');
      if (!row) return;
      document.querySelectorAll('.explorer-poi-item.is-active').forEach(function (el) { el.classList.remove('is-active'); });
      row.classList.add('is-active');
      row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    /* Clicking a list row pans the map to its marker */
    document.querySelectorAll('.explorer-poi-item[data-poi]').forEach(function (item) {
      var entry = byName[item.getAttribute('data-poi')];
      if (!entry) return;
      item.addEventListener('click', function () {
        map.panTo(entry.marker.getPosition());
        map.setZoom(14);
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
      if (title) title.textContent = name ? name + ' — Virtual Tour' : 'Virtual Tour';
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

    var SEEN_KEY   = 'rg-inquiry-popup-seen';
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

}());
