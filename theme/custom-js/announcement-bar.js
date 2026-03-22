(function() {
    var slides = document.querySelectorAll('.ofo-announcement-slide');
    if (!slides.length) return;
    var current = 0;
    setInterval(function() {
        slides[current].classList.remove('active');
        current = (current + 1) % slides.length;
        slides[current].classList.add('active');
    }, 4000);
})();
