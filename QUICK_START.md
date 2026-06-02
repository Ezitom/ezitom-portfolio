# 🚀 Quick Start Guide - Admin & Images

## 📊 Admin Dashboard Login

```
URL: http://localhost/ezitom/backend/admin/login.php

Username: admin
Password: Admin@2025!
```

### What You Can Do:
- ✏️ **Add/Edit/Delete Projects** with images
- 📚 **Manage Skills** with proficiency levels
- 💬 **View Contact Messages** from visitors
- 🖼️ **Manage Project Images** by setting image URLs

---

## 🖼️ Project Images Setup

### Step 1: Ensure Database is Initialized
Visit: `http://localhost/ezitom/backend/setup.html`
- Click **"Initialize Database"** button
- Should see success message with 6 sample projects

### Step 2: View Project Images (Frontend)
Visit: `http://localhost/ezitom/projects.html`
- Should display all 6 projects with images
- Images come from `/images/projects/` folder

### Step 3: Test Images Display
Visit: `http://localhost/ezitom/test-images.html`
- Live preview of all projects with images
- Shows image loading status
- Direct links to admin dashboard

### Step 4: Add More Projects (Optional)
1. Login to admin: `backend/admin/login.php`
2. Go to **Projects** tab
3. Click **"Add New Project"**
4. Fill in details:
   - **Image URL**: `images/projects/filename.png`
   - Choose from available images list below

---

## 📸 Available Project Images

All these images are ready in `/images/projects/`:

```
✅ Business website.png
✅ tolu-dami-wedding.svg
✅ greenroots-store.svg
✅ devnotes-blog.svg
✅ harbour-events.svg
✅ opentrack-cli.svg
✅ Autovibe.png
```

When adding projects, use image URLs like:
```
images/projects/Business website.png
images/projects/tolu-dami-wedding.svg
```

---

## 🔄 Complete Workflow

### First Time Setup:
```
1. Start XAMPP (Apache + MySQL)
   ↓
2. Initialize Database
   http://localhost/ezitom/backend/setup.html
   ↓
3. View Projects Page (images load automatically)
   http://localhost/ezitom/projects.html
   ↓
4. Login to Admin (optional - to edit projects)
   http://localhost/ezitom/backend/admin/login.php
```

### Adding New Projects:
```
1. Login to Admin Dashboard
   Username: admin
   Password: Admin@2025!
   ↓
2. Click "Projects" tab
   ↓
3. Click "Add New Project"
   ↓
4. Fill in form:
   - Title (required)
   - Description (required)
   - Image URL: images/projects/filename.png
   - Live URL (optional)
   - Category: General, Business, or Events & Wedding
   - Tech Stack: comma-separated
   ↓
5. Click "Add Project"
   ↓
6. View on frontend - appears automatically
```

---

## 🔗 Important URLs

| Purpose | URL |
|---------|-----|
| **Frontend - Home** | `http://localhost/ezitom/index.html` |
| **Frontend - Projects** | `http://localhost/ezitom/projects.html` |
| **Frontend - Contact** | `http://localhost/ezitom/contact.html` |
| **Test Images** | `http://localhost/ezitom/test-images.html` |
| **Admin Login** | `http://localhost/ezitom/backend/admin/login.php` |
| **Admin Dashboard** | `http://localhost/ezitom/backend/admin/index.php` |
| **Database Setup** | `http://localhost/ezitom/backend/setup.html` |

---

## ❓ Troubleshooting

### Images Not Showing?
- ✅ Check if database is initialized (see status on test-images.html)
- ✅ Verify images exist: Check `/images/projects/` folder
- ✅ Hard refresh browser: `Ctrl+F5` or `Cmd+Shift+R`
- ✅ Check browser console for errors: Press `F12`

### Can't Login to Admin?
- ✅ Verify XAMPP MySQL is running
- ✅ Ensure database is initialized
- ✅ Double-check credentials: username: **admin**, password: **Admin@2025!**
- ✅ Check XAMPP MySQL console: `http://localhost/phpmyadmin`

### Forms Not Submitting?
- ✅ Ensure Apache is running
- ✅ Check browser console (F12) for errors
- ✅ Verify database initialized
- ✅ Test submission: Fill form on `/contact.html` and submit

---

## 💾 Database Info

| Detail | Value |
|--------|-------|
| **Host** | localhost |
| **Database** | ezitom_db |
| **User** | root |
| **Password** | (empty) |
| **Admin Username** | admin |
| **Admin Password** | Admin@2025! |

---

## 📁 File Structure

```
/backend/
  /admin/
    index.php      ← Admin Dashboard
    login.php      ← Admin Login
  /api/
    projects.php   ← Project data API
    contact.php    ← Contact form handler
    setup.php      ← Database setup API
  /config/
    db.php         ← Database connection

/images/projects/  ← Project image files

test-images.html   ← Test page for images
ADMIN_LOGIN.md     ← Admin login info
SETUP_GUIDE.md     ← Setup instructions
```

---

## ✅ Success Checklist

- [ ] XAMPP started (Apache + MySQL)
- [ ] Database initialized
- [ ] Can view projects with images on `/projects.html`
- [ ] Can login to admin: `admin` / `Admin@2025!`
- [ ] Can add/edit projects in admin dashboard
- [ ] Contact form submits successfully
- [ ] Images display correctly on frontend

---

**All set! Your portfolio with project images is ready to use.** 🎉
