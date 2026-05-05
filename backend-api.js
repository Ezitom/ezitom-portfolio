/**
 * backend-api.js — Modern AJAX handler for EziTom Portfolio
 * Handles form submissions via PHP backend instead of EmailJS.
 */

// ── SELF-CORRECTING API PATH ──────────────────────────────
// Use a relative path that works from any root-level HTML file
const API_BASE = 'backend/api';

console.log('[dev.folio DEBUG] backend-api.js loaded.');
console.log('[dev.folio DEBUG] API_BASE set to:', API_BASE);

// ── Utility: safe HTML escape ──────────────────────────────
function escHtml(str) {
  const div = document.createElement('div');
  div.textContent = str;
  return div.innerHTML;
}

// ── CONTACT FORM — PHP backend ─────────────────────────────
const contactFormPHP = document.getElementById('contact-form');

if (contactFormPHP && window.location.protocol !== 'file:') {
  contactFormPHP.addEventListener('submit', async function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    let isValid = true;
    contactFormPHP.querySelectorAll('input, select, textarea').forEach(input => {
      input.classList.remove('error');
      if (input.hasAttribute('required') && !input.value.trim()) {
        input.classList.add('error');
        isValid = false;
      }
    });
    if (!isValid) return;

    const submitBtn = document.getElementById('submitBtn');
    const btnText   = document.getElementById('btnText');
    const spinner   = document.getElementById('btnSpinner');
    const btnIcon   = document.getElementById('btnIcon');

    submitBtn.disabled = true;
    if (btnText) btnText.style.display = 'none';
    if (btnIcon) btnIcon.style.display = 'none';
    if (spinner) spinner.style.display = 'block';

    const showToast = (msg, isError = false) => {
      const toast    = document.getElementById('toast');
      const toastMsg = document.getElementById('toastMsg');
      if (!toast) { alert(msg); return; }
      toastMsg.textContent = msg;
      toast.classList.toggle('error-toast', isError);
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 5000);
    };

    const resetBtn = () => {
      submitBtn.disabled = false;
      if (btnText) btnText.style.display = 'block';
      if (btnIcon) btnIcon.style.display = 'block';
      if (spinner) spinner.style.display = 'none';
    };

    const formData = {
        name: [
          document.getElementById('firstName')?.value.trim(),
          document.getElementById('lastName')?.value.trim()
        ].filter(Boolean).join(' '),
        email: document.getElementById('email')?.value.trim() || '',
        subject: document.getElementById('subject')?.value.trim() || '',
        message: document.getElementById('message')?.value.trim() || ''
    };

    console.log('[dev.folio DEBUG] Submitting contact form...', formData);

    try {
        const response = await fetch(`${API_BASE}/contact.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        const text = await response.text();
        console.log('[dev.folio DEBUG] Raw server response:', text);

        let result;
        try {
            result = JSON.parse(text);
        } catch (parseErr) {
            console.error('[dev.folio DEBUG] Invalid JSON:', text);
            throw new Error('Server returned an invalid response. Check the console for details.');
        }

        if (result.status === 'success') {
            showToast(result.message, false);
            contactFormPHP.reset();
            if (typeof gtag !== 'undefined') {
              gtag('event', 'form_submit', { event_category: 'contact', event_label: 'contact_page_form' });
            }
        } else {
            showToast(result.message || 'The server rejected the message.', true);
        }
    } catch (error) {
        console.error('[dev.folio DEBUG] Submission error:', error);
        showToast(error.message || 'Something went wrong. Please try again.', true);
    } finally {
        resetBtn();
    }
  }, true); // capture phase — fires before main.js EmailJS listener
}

// ── HOME ENQUIRY FORM — PHP backend ───────────────────────
const homeEnquiryFormPHP = document.getElementById('homeEnquiryForm');

if (homeEnquiryFormPHP && window.location.protocol !== 'file:') {
  homeEnquiryFormPHP.addEventListener('submit', async function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    let isValid = true;
    homeEnquiryFormPHP.querySelectorAll('input, select, textarea').forEach(input => {
      input.classList.remove('error');
      if (input.hasAttribute('required') && !input.value.trim()) {
        input.classList.add('error');
        isValid = false;
      }
    });
    if (!isValid) return;

    const submitBtn = document.getElementById('enqSubmitBtn');
    const btnText   = document.getElementById('enqBtnText');
    const spinner   = document.getElementById('enqSpinner');

    submitBtn.disabled = true;
    if (btnText) btnText.style.display = 'none';
    if (spinner) spinner.style.display = 'block';

    const showToast = (msg, isError = false) => {
      const toast    = document.getElementById('toast');
      const toastMsg = document.getElementById('toastMsg');
      if (!toast) { alert(msg); return; }
      toastMsg.textContent = msg;
      toast.classList.toggle('error-toast', isError);
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 5000);
    };

    const resetBtn = () => {
      submitBtn.disabled = false;
      if (btnText) btnText.style.display = 'block';
      if (spinner) spinner.style.display = 'none';
    };

    const formData = {
        name: document.getElementById('enqName')?.value.trim() || '',
        email: document.getElementById('enqEmail')?.value.trim() || '',
        subject: document.getElementById('enqService')?.value.trim() || '',
        message: document.getElementById('enqMessage')?.value.trim() || ''
    };

    console.log('[dev.folio DEBUG] Submitting home enquiry form...', formData);

    try {
        const response = await fetch(`${API_BASE}/contact.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        const text = await response.text();
        console.log('[dev.folio DEBUG] Raw server response:', text);

        let result;
        try {
            result = JSON.parse(text);
        } catch (parseErr) {
            console.error('[dev.folio DEBUG] Invalid JSON:', text);
            throw new Error('Server returned an invalid response. Check the console for details.');
        }

        if (result.status === 'success') {
            showToast(result.message, false);
            homeEnquiryFormPHP.reset();
            if (typeof gtag !== 'undefined') {
              gtag('event', 'form_submit', { event_category: 'contact', event_label: 'home_enquiry_form' });
            }
        } else {
            showToast(result.message || 'The server rejected the message.', true);
        }
    } catch (error) {
        console.error('[dev.folio DEBUG] Submission error:', error);
        showToast(error.message || 'Something went wrong. Please try again.', true);
    } finally {
        resetBtn();
    }
  }, true);
}

// ── DYNAMIC LOADERS ───────────────────────────────────────

/**
 * Fetches and renders projects from the API
 */
async function loadProjects() {
    const grid = document.getElementById('projects-grid');
    if (!grid) return;

    try {
        const response = await fetch(`${API_BASE}/projects.php`);
        const result = await response.json();

        if (result.success && result.data) {
            grid.innerHTML = result.data.map(p => `
                <div class="project-card reveal" data-cat="${escHtml(p.category)}">
                    <div class="project-thumb">
                        <img src="${escHtml(p.image_url)}" alt="${escHtml(p.title)}" class="project-thumb-img">
                        <span class="project-cat-pill">${escHtml(p.category)}</span>
                    </div>
                    <div class="project-content">
                        <div class="project-tags">
                            ${(p.tech_stack || []).map(t => `<span>${escHtml(t)}</span>`).join('')}
                        </div>
                        <h3>${escHtml(p.title)}</h3>
                        <p class="project-copy">${escHtml(p.description)}</p>
                        <div class="project-links">
                            <a href="${escHtml(p.live_url)}" target="_blank" class="btn btn-filled project-link live">Live Site &rarr;</a>
                        </div>
                    </div>
                </div>
            `).join('');
            
            // Re-trigger reveal animation for new items
            if (window.observer) {
                grid.querySelectorAll('.reveal').forEach(el => window.observer.observe(el));
            }
        }
    } catch (err) {
        console.error('[dev.folio] Failed to load projects:', err);
        grid.innerHTML = '<p class="text-muted">Unable to load projects at this time.</p>';
    }
}

/**
 * Fetches and renders skills from the API
 */
async function loadSkills() {
    const container = document.getElementById('skills-container');
    if (!container) return;

    try {
        const response = await fetch(`${API_BASE}/skills.php`);
        const result = await response.json();

        if (result.success && result.data) {
            let html = '<div class="skills-grid">';
            
            for (const [category, skills] of Object.entries(result.data)) {
                html += `
                    <div class="skill-card reveal">
                        <h3 class="mono">${escHtml(category)}</h3>
                        <div class="skill-list">
                            ${skills.map(s => `
                                <div class="skill-bar-row">
                                    <div class="skill-bar-info">
                                        <span>${escHtml(s.skill_name)}</span>
                                        <span>${s.proficiency}%</span>
                                    </div>
                                    <div class="skill-bar-bg">
                                        <div class="skill-bar-fill" data-width="${s.proficiency}%"></div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }
            html += '</div>';
            container.innerHTML = html;

            // Re-trigger reveal and skill bar animations
            if (window.observer) {
                container.querySelectorAll('.reveal').forEach(el => window.observer.observe(el));
            }
            if (window.skillObserver) {
                container.querySelectorAll('.skill-bar-fill').forEach(el => window.skillObserver.observe(el));
            }
        }
    } catch (err) {
        console.error('[dev.folio] Failed to load skills:', err);
        container.innerHTML = '<p class="text-muted">Unable to load skills at this time.</p>';
    }
}

// ── INITIALIZATION ────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    loadProjects();
    loadSkills();
});
