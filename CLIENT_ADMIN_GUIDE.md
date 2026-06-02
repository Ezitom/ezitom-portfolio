# 📊 Client-Side Admin Dashboard Guide

This guide explains how to access, use, and understand the new **client-side Admin Dashboard** built inside the `/client/admin/` directory.

---

## 1. Local Host Access URLs

If you are using **VS Code Live Server (Port 5500)**:

*   **Login Page:** [http://127.0.0.1:5500/client/admin/login.html](http://127.0.0.1:5500/client/admin/login.html)
*   **Dashboard Home:** [http://127.0.0.1:5500/client/admin/dashboard.html](http://127.0.0.1:5500/client/admin/dashboard.html)

---

## 2. Authentication Credentials

The login credentials are set in `client/admin/js/config.js`:

*   **Gmail Address:** `oniebenezer1@gmail.com`
*   **Default Password:** `Admin@2025!`

### Features:
1.  **Remember Me:** Checking the box remembers your login status for 30 days.
2.  **Forgot Password:** If you forgot the password, you can go to `forgot.html` page, verify your admin Gmail, and set a new password. The new password is saved to local storage as `admin_custom_password` and will override the default hardcoded one.

---

## 3. How the Synchronization Works

Since this dashboard runs **purely client-side**, it uses browser `localStorage` as the single source of truth:

*   **Automatic Seeding:** The first time you sign in, the dashboard seeds `localStorage` with default projects and skills so the interface isn't blank.
*   **Dynamic Client Rendering:** The front-end (`backend-api.js`) has been updated to query `localStorage` first. Any additions, updates, or deletions you make in the Admin Dashboard will reflect on the front-end instantly.
*   **Contact Form Submissions:** The contact form (`main.js` and `backend-api.js`) is hooked so that successful submissions are saved to `localStorage` automatically. You can read, mark as read, delete, and reply to these messages from the Admin Messages tab.
*   **Fallback Resilience:** If `localStorage` is cleared, the portfolio pages automatically fall back to loading from your PHP backend API or hardcoded defaults, ensuring the website never breaks.

---

## 4. File Structure

All files for the client-side admin are contained under the `client/admin/` directory:

```
client/admin/
├── css/
│   └── admin.css            # Custom, rich dark theme design styling
├── js/
│   ├── auth.js              # Session checking, login state, and guards
│   ├── config.js            # Admin Gmail, default projects, and skills
│   ├── data.js              # CRUD layer for localStorage
│   ├── sidebar.js           # Responsive sidebar renderer (mobile overlay)
│   └── toast.js             # Action feedback notifications
├── login.html               # Admin Sign In page
├── forgot.html              # Custom Password reset page
├── dashboard.html           # Home stats overview
├── projects.html            # Projects CRUD panel
├── skills.html              # Skills CRUD panel
└── messages.html            # Contact submissions panel
```

---

## 5. Rich Design Aesthetics

The UI matches the modern styling of your main portfolio:
*   **Glassmorphism & Gradients:** Vibrant neon-teal (`#00d4c8`) and deep purple-blue accent lines.
*   **Mobile-First Responsive Layout:** The sidebar collapses into a slide-out drawer on tablet and mobile screens, controlled by a header hamburger button.
*   **Interactive Controls:** Smooth slide-down animations for forms, progress bar indicators for skill proficiency, hover transformations, and toast notification alerts.
