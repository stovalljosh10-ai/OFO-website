/**
 * OFO Merica's Birthday Bash Countdown
 * Vanilla JS — no jQuery required
 */
(function () {
  'use strict';

  var section = document.getElementById('ofo-countdown-section');
  if (!section) return;

  var target = section.getAttribute('data-target');
  if (!target) return;

  var targetDate = new Date(target).getTime();

  var els = {
    days:  document.getElementById('ofo-cd-days'),
    hours: document.getElementById('ofo-cd-hours'),
    mins:  document.getElementById('ofo-cd-mins'),
    secs:  document.getElementById('ofo-cd-secs'),
    timer: document.getElementById('ofo-cd-timer'),
    live:  document.getElementById('ofo-cd-live'),
    cta:   document.getElementById('ofo-cd-cta')
  };

  var prev = { days: '', hours: '', mins: '', secs: '' };

  function pad(n) {
    return n < 10 ? '0' + n : String(n);
  }

  function flipUnit(el, value, key) {
    if (!el) return;
    var numEl = el.querySelector('.ofo-cd-num');
    if (!numEl) return;

    var str = pad(value);
    if (str === prev[key]) return;

    prev[key] = str;
    el.classList.remove('flip');
    // Force reflow to restart animation
    void el.offsetWidth;
    el.classList.add('flip');
    numEl.textContent = str;
  }

  function tick() {
    var now = Date.now();
    var diff = targetDate - now;

    if (diff <= 0) {
      // Sale is live
      if (els.timer) els.timer.style.display = 'none';
      if (els.live) els.live.style.display = 'block';
      if (els.cta) {
        els.cta.textContent = '🔥 Shop the Sale NOW';
        els.cta.classList.add('ofo-cd-cta--live');
      }
      return;
    }

    var d = Math.floor(diff / (1000 * 60 * 60 * 24));
    var h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    var s = Math.floor((diff % (1000 * 60)) / 1000);

    flipUnit(els.days, d, 'days');
    flipUnit(els.hours, h, 'hours');
    flipUnit(els.mins, m, 'mins');
    flipUnit(els.secs, s, 'secs');

    requestAnimationFrame(function () {
      setTimeout(tick, 1000 - (Date.now() % 1000));
    });
  }

  // Start on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', tick);
  } else {
    tick();
  }
})();
