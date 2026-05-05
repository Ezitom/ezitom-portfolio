const EMAILJS_PUBLIC_KEY = 'YOUR_PUBLIC_KEY';
const EMAILJS_SERVICE_ID = 'YOUR_SERVICE_ID';
const EMAILJS_TEMPLATE_ID = 'YOUR_TEMPLATE_ID';

// ── FOOTER LOADER ─────────────────────────────────────────
function loadFooter() {
  const placeholder = document.getElementById('footer-placeholder');
  if (!placeholder) return;
  fetch('footer.html')
    .then(res => res.text())
    .then(html => {
      placeholder.innerHTML = html;
      const yearEl = document.getElementById('current-year');
      if (yearEl) {
        yearEl.textContent = new Date().getFullYear();
      }
    })
    .catch(err => console.warn('Footer could not be loaded:', err));
}
loadFooter();

// Theme Toggle
const themeToggles = document.querySelectorAll('.theme-toggle');
const htmlEl = document.documentElement;

themeToggles.forEach(toggle => {
  toggle.addEventListener('click', () => {
    const current = htmlEl.getAttribute('data-theme');
    const next = current === 'light' ? 'dark' : 'light';
    htmlEl.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    if (typeof initParticles === 'function') initParticles();
  });
});

// Hamburger Menu
const hamburger = document.getElementById('hamburger');
const mobileMenu = document.getElementById('mobile-menu');
if (hamburger && mobileMenu) {
  hamburger.addEventListener('click', () => {
    mobileMenu.classList.toggle('open');
  });
}

// IntersectionObserver for .reveal elements
const observerOpts = { root: null, rootMargin: '0px', threshold: 0.15 };
window.observer = new IntersectionObserver((entries, obs) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('active');
      obs.unobserve(entry.target);
    }
  });
}, observerOpts);

document.querySelectorAll('.reveal').forEach(el => window.observer.observe(el));

// IntersectionObserver for .skill-bar-fill
window.skillObserver = new IntersectionObserver((entries, obs) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.width = entry.target.getAttribute('data-width');
      obs.unobserve(entry.target);
    }
  });
}, observerOpts);

document.querySelectorAll('.skill-bar-fill').forEach(el => window.skillObserver.observe(el));

// Project filter logic
const filterBtns = document.querySelectorAll('.filter-btn');
const projectCards = document.querySelectorAll('.project-card');
if (filterBtns.length > 0) {
  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const cat = btn.getAttribute('data-cat');

      projectCards.forEach(card => {
        if (cat === 'all' || card.getAttribute('data-cat') === cat) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });
}

// Contact form logic
const form = document.getElementById('contact-form');
if (form) {
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    let isValid = true;
    const inputs = form.querySelectorAll('input, select, textarea');

    inputs.forEach(input => {
      input.classList.remove('error');
      if (input.hasAttribute('required') && !input.value.trim()) {
        input.classList.add('error');
        isValid = false;
      }
    });

    if (!isValid) return;

    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const spinner = document.getElementById('btnSpinner');
    const btnIcon = document.getElementById('btnIcon');

    submitBtn.disabled = true;
    if (btnText) btnText.style.display = 'none';
    if (btnIcon) btnIcon.style.display = 'none';
    if (spinner) spinner.style.display = 'block';

    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toastMsg');

    const showToast = (msg, isError = false) => {
      toastMsg.textContent = msg;
      if (isError) {
        toast.classList.add('error-toast');
      } else {
        toast.classList.remove('error-toast');
      }
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 4000);
    };

    const resetBtn = () => {
      submitBtn.disabled = false;
      if (btnText) btnText.style.display = 'block';
      if (btnIcon) btnIcon.style.display = 'block';
      if (spinner) spinner.style.display = 'none';
    };

    if (EMAILJS_PUBLIC_KEY === 'YOUR_PUBLIC_KEY' || typeof emailjs === 'undefined') {
      // Demo Mode
      setTimeout(() => {
        resetBtn();
        form.reset();
        showToast('Message sent! (Demo Mode)');
      }, 1400);
    } else {
      const data = {
        first_name: document.getElementById('firstName').value,
        last_name: document.getElementById('lastName').value,
        email: document.getElementById('email').value,
        subject: document.getElementById('subject').value,
        budget: document.getElementById('budget').value || 'Not specified',
        message: document.getElementById('message').value
      };

      emailjs.init(EMAILJS_PUBLIC_KEY);
      emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID, data)
        .then(() => {
          resetBtn();
          form.reset();
          showToast('Message sent successfully!');
        })
        .catch(err => {
          console.error(err);
          resetBtn();
          showToast('Failed to send message.', true);
        });
    }
  });

  form.querySelectorAll('input, select, textarea').forEach(input => {
    input.addEventListener('input', () => input.classList.remove('error'));
  });
}

// Home enquiry form logic
const homeEnquiryForm = document.getElementById('homeEnquiryForm');
if (homeEnquiryForm) {
  homeEnquiryForm.addEventListener('submit', (e) => {
    e.preventDefault();
    let isValid = true;
    const inputs = homeEnquiryForm.querySelectorAll('input, select, textarea');

    inputs.forEach(input => {
      input.classList.remove('error');
      if (input.hasAttribute('required') && !input.value.trim()) {
        input.classList.add('error');
        isValid = false;
      }
    });

    if (!isValid) return;

    const submitBtn = document.getElementById('enqSubmitBtn');
    const btnText = document.getElementById('enqBtnText');
    const spinner = document.getElementById('enqSpinner');

    submitBtn.disabled = true;
    if (btnText) btnText.style.display = 'none';
    if (spinner) spinner.style.display = 'block';

    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toastMsg');

    const showToast = (msg, isError = false) => {
      if (toastMsg) toastMsg.textContent = msg;
      if (toast) {
        if (isError) {
          toast.classList.add('error-toast');
        } else {
          toast.classList.remove('error-toast');
        }
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 4000);
      } else {
        alert(msg);
      }
    };

    const resetBtn = () => {
      submitBtn.disabled = false;
      if (btnText) btnText.style.display = 'block';
      if (spinner) spinner.style.display = 'none';
    };

    if (EMAILJS_PUBLIC_KEY === 'YOUR_PUBLIC_KEY' || typeof emailjs === 'undefined') {
      // Demo Mode
      setTimeout(() => {
        resetBtn();
        homeEnquiryForm.reset();
        showToast('Message sent! I\'ll be in touch soon.');
      }, 1400);
    } else {
      const data = {
        name: document.getElementById('enqName').value,
        email: document.getElementById('enqEmail').value,
        service: document.getElementById('enqService').value,
        message: document.getElementById('enqMessage').value
      };

      emailjs.init(EMAILJS_PUBLIC_KEY);
      emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID, data)
        .then(() => {
          resetBtn();
          homeEnquiryForm.reset();
          showToast('Message sent! I\'ll be in touch soon.');
        })
        .catch(err => {
          console.error(err);
          resetBtn();
          showToast('Failed to send message.', true);
        });
    }
  });

  homeEnquiryForm.querySelectorAll('input, select, textarea').forEach(input => {
    input.addEventListener('input', () => input.classList.remove('error'));
  });
}

// Canvas particle animation
const canvas = document.getElementById('hero-canvas');
let particles = [];

function initParticles() {
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  canvas.width = window.innerWidth;
  canvas.height = canvas.parentElement.offsetHeight;
  particles = [];

  const isLight = document.documentElement.getAttribute('data-theme') === 'light';
  const color = isLight ? 'rgba(0, 119, 204,' : 'rgba(0, 212, 200,';

  for (let i = 0; i < 110; i++) {
    particles.push({
      x: Math.random() * canvas.width,
      y: Math.random() * canvas.height,
      size: Math.random() * 2 + 0.5,
      sx: (Math.random() - 0.5) * 0.5,
      sy: (Math.random() - 0.5) * 0.5,
      color: color
    });
  }
}

function animateParticles() {
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  const isLight = document.documentElement.getAttribute('data-theme') === 'light';
  const rgb = isLight ? '0, 119, 204' : '0, 212, 200';

  for (let i = 0; i < particles.length; i++) {
    let p = particles[i];
    p.x += p.sx; p.y += p.sy;
    if (p.x < 0 || p.x > canvas.width) p.sx *= -1;
    if (p.y < 0 || p.y > canvas.height) p.sy *= -1;

    ctx.fillStyle = p.color + ' 0.5)';
    ctx.beginPath();
    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
    ctx.fill();

    for (let j = i; j < particles.length; j++) {
      let p2 = particles[j];
      const dx = p.x - p2.x;
      const dy = p.y - p2.y;
      const dist = Math.sqrt(dx * dx + dy * dy);
      if (dist < 100) {
        ctx.beginPath();
        ctx.strokeStyle = `rgba(${rgb}, ${1 - dist / 100})`;
        ctx.lineWidth = 0.5;
        ctx.moveTo(p.x, p.y);
        ctx.lineTo(p2.x, p2.y);
        ctx.stroke();
      }
    }
  }
  requestAnimationFrame(animateParticles);
}

if (canvas) {
  window.addEventListener('resize', initParticles);
  initParticles();
  animateParticles();
}

// Typewriter
const typeEl = document.getElementById('typewriter');
if (typeEl) {
  const roles = ["am a Full-Stack Web Developer", "am an API Architect", "turn ideas into real products"];
  let rIdx = 0, cIdx = 0, isDel = false;

  function type() {
    const role = roles[rIdx];
    if (isDel) {
      typeEl.textContent = role.substring(0, cIdx - 1);
      cIdx--;
    } else {
      typeEl.textContent = role.substring(0, cIdx + 1);
      cIdx++;
    }

    let speed = isDel ? 30 : 80;
    if (!isDel && cIdx === role.length) {
      speed = 2000;
      isDel = true;
    } else if (isDel && cIdx === 0) {
      isDel = false;
      rIdx = (rIdx + 1) % roles.length;
      speed = 500;
    }
    setTimeout(type, speed);
  }
  setTimeout(type, 1000);
}

// ── ANALYTICS EVENT TRACKING ─────────────────────────────
// Fires custom GA4 events for key user interactions.
// All calls are guarded so the site works fine if GA is blocked.

// Track: CTA button clicks on the home page
document.querySelectorAll('.btn-p, .btn-s').forEach(btn => {
  btn.addEventListener('click', () => {
    if (typeof gtag !== 'undefined') {
      gtag('event', 'cta_click', {
        event_category: 'engagement',
        event_label: btn.textContent.trim()
      });
    }
  });
});

// Track: Project card "Live Site" link clicks
document.querySelectorAll('.project-link.live').forEach(link => {
  link.addEventListener('click', () => {
    if (typeof gtag !== 'undefined') {
      gtag('event', 'project_click', {
        event_category: 'projects',
        event_label: link.closest('.project-card')
          ?.querySelector('.project-title')?.textContent.trim()
      });
    }
  });
});

// Track: Contact form submission attempt
const contactForm = document.getElementById('contactForm');
if (contactForm) {
  contactForm.addEventListener('submit', () => {
    if (typeof gtag !== 'undefined') {
      gtag('event', 'form_submit', {
        event_category: 'contact',
        event_label: 'contact_page_form'
      });
    }
  });
}

// Track: Home enquiry form submission attempt
const homeForm = document.getElementById('homeEnquiryForm');
if (homeForm) {
  homeForm.addEventListener('submit', () => {
    if (typeof gtag !== 'undefined') {
      gtag('event', 'form_submit', {
        event_category: 'contact',
        event_label: 'home_enquiry_form'
      });
    }
  });
}

// Track: Theme toggle usage
const themeToggle = document.getElementById('themeToggle');
if (themeToggle) {
  themeToggle.addEventListener('click', () => {
    if (typeof gtag !== 'undefined') {
      gtag('event', 'theme_toggle', {
        event_category: 'ui',
        event_label: document.documentElement.getAttribute('data-theme')
      });
    }
  });
}


// Initialize Lucide Icons
if (typeof lucide !== 'undefined') {
  lucide.createIcons();
}
