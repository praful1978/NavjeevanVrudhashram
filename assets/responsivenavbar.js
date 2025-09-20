  const navToggle = document.querySelector('.nav-toggle');
  const nav = document.getElementById('siteNav');

  navToggle.addEventListener('click', () => {
    nav.classList.toggle('active');
    const expanded = navToggle.getAttribute('aria-expanded') === 'true';
    navToggle.setAttribute('aria-expanded', !expanded);
  });