// Mobile menu toggle
const toggle = document.querySelector('.nav-toggle');
const nav = document.querySelector('#siteNav');
if (toggle && nav) {
  toggle.addEventListener('click', () => {
    const open = nav.classList.toggle('show');
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
  document.addEventListener('click', (e) => {
    if (!nav.contains(e.target) && !toggle.contains(e.target)) {
      nav.classList.remove('show'); toggle.setAttribute('aria-expanded','false');
    }
  });
}

// Smooth scroll for on-page anchors (e.g., #terms)
document.addEventListener('click', (e) => {
  const a = e.target.closest('a[href^="#"]');
  if (!a) return;
  const el = document.querySelector(a.getAttribute('href'));
  if (!el) return;
  e.preventDefault();
  el.scrollIntoView({ behavior: 'smooth', block: 'start' });
});

// Contact form front-end validation
window.validateContactForm = function (event) {
  const form = event.target;
  const name = form.querySelector('input[name="name"]')?.value.trim();
  const phone = form.querySelector('input[name="phone"]')?.value.trim();
  const message = form.querySelector('textarea[name="message"]')?.value.trim();
  if (!name || !phone || !message) {
    alert('कृपया आवश्यक माहिती भरा.');
    event.preventDefault();
    return false;
  }
  return true;
};

// Lightweight image lightbox (no dependency)
(function(){
  const links = document.querySelectorAll('a.glight');
  if (!links.length) return;
  const overlay = document.createElement('div');
  overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.9);display:none;align-items:center;justify-content:center;z-index:9999;padding:20px';
  const img = document.createElement('img');
  img.style.maxWidth = '96%'; img.style.maxHeight = '92%'; img.style.borderRadius = '12px';
  overlay.appendChild(img);
  overlay.addEventListener('click', ()=> overlay.style.display='none');
  document.body.appendChild(overlay);
  links.forEach(a=>{
    a.addEventListener('click', e=>{
      e.preventDefault();
      img.src = a.href;
      overlay.style.display='flex';
    });
  });
})();
