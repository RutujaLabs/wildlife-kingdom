document.addEventListener('DOMContentLoaded', function () {

  // Mobile nav toggle
  const toggle = document.getElementById('navToggle');
  const nav = document.getElementById('mainNav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      nav.classList.toggle('open');
    });
  }

  // Reveal sections gently as the user scrolls to them
  const revealEls = document.querySelectorAll('[data-reveal]');
  if ('IntersectionObserver' in window && revealEls.length) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });

    revealEls.forEach(el => observer.observe(el));
  } else {
    // Fallback: just show everything
    revealEls.forEach(el => el.classList.add('is-visible'));
  }

});