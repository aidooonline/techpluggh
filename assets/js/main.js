/* TechPlug GH theme scripts */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var menu   = document.getElementById('tpg-mobile-menu');
    var panel  = document.getElementById('tpg-mobile-panel');
    var toggle = document.getElementById('tpg-menu-toggle');

    function openMenu() {
      if (!menu) return;
      menu.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      requestAnimationFrame(function () {
        if (panel) panel.style.transform = 'translateX(0)';
      });
      if (toggle) toggle.setAttribute('aria-expanded', 'true');
    }
    function closeMenu() {
      if (!menu) return;
      if (panel) panel.style.transform = 'translateX(-100%)';
      document.body.style.overflow = '';
      setTimeout(function () { menu.classList.add('hidden'); }, 300);
      if (toggle) toggle.setAttribute('aria-expanded', 'false');
    }

    if (toggle) toggle.addEventListener('click', openMenu);
    if (menu) {
      menu.querySelectorAll('[data-close]').forEach(function (el) {
        el.addEventListener('click', closeMenu);
      });
    }
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeMenu();
    });

    /* Search drawer */
    var sToggle = document.getElementById('tpg-search-toggle');
    var sDrawer = document.getElementById('tpg-search-drawer');
    if (sToggle && sDrawer) {
      sToggle.addEventListener('click', function () {
        sDrawer.classList.toggle('hidden');
        var input = sDrawer.querySelector('input[type="search"]');
        if (input && !sDrawer.classList.contains('hidden')) input.focus();
      });
    }

    /* Subtle header elevation on scroll */
    var header = document.getElementById('site-header');
    if (header) {
      var onScroll = function () {
        if (window.scrollY > 8) header.classList.add('shadow-card');
        else header.classList.remove('shadow-card');
      };
      window.addEventListener('scroll', onScroll, { passive: true });
      onScroll();
    }

    /* Reveal/hide cart bubble after AJAX fragments update */
    var syncCart = function () {
      document.querySelectorAll('.tpg-cart-bubble').forEach(function (bubble) {
        var inner = bubble.querySelector('.tpg-cart-count');
        var n = inner ? parseInt(inner.getAttribute('data-count') || inner.textContent || '0', 10) : 0;
        if (n > 0) bubble.classList.remove('opacity-0');
        else bubble.classList.add('opacity-0');
      });
    };
    document.body.addEventListener('added_to_cart', syncCart);
    document.body.addEventListener('wc_fragments_refreshed', syncCart);
    document.body.addEventListener('wc_fragments_loaded', syncCart);
  });
})();
