# 📋 Setup Guide: EziTom Portfolio

## Database Setup

### Option 1: Web-based Setup (Easiest)
1. Start Apache and MySQL via XAMPP Control Panel
2. Navigate to: `http://localhost/ezitom/backend/setup.html`
3. Click **"Initialize Database"** button
4. Database will be populated with sample projects and skills

### Option 2: Command Line
```bash
# From project root:
php backend/setup_database.php
```

### Option 3: Manual MySQL
```bash
# From project root:
mysql -u root < backend/config/setup.sql
```

## What Gets Installed

### Tables Created:
- **projects** - Portfolio projects with images
- **skills** - Technical skills organized by category  
- **contacts** - Contact form submissions
- **admin_users** - Admin login (username: `admin`, password: `Admin@2025!`)

### Sample Data:
- 6 portfolio projects with images
- 15+ skills across Frontend, Backend, DevOps categories
- 1 admin user for testing

## Project Images

Image files are already in place at `/images/projects/`:
- `Business website.png` - See Mary See Beauty Salon
- `tolu-dami-wedding.svg` - Tolu & Dami: Our Story
- `greenroots-store.svg` - GreenRoots Organic Store
- `devnotes-blog.svg` - DevNotes Blog
- `harbour-events.svg` - Harbour Events Co.
- `opentrack-cli.svg` - OpenTrack CLI
- `Autovibe.png` - Additional sample

## Forms Submission

### Contact Form (`/contact.html`)
Submits to: `/backend/api/contact.php`
- Stores submissions in database
- Real-time validation
- Toast notifications

### Home Enquiry Form (`/index.html`)
Submits to: `/backend/api/contact.php`
- Same backend as contact form
- Quick project inquiry form

### Form Fields Required:
- **Name** (required)
- **Email** (required, validated)
- **Message** (required)
- **Subject** (required)
- **Budget** (optional)

## Testing Forms Locally

1. Open DevTools Console (F12)
2. Submit a form
3. Check console for debug messages:
   - `[dev.folio DEBUG] Submitting contact form...`
   - Raw server response with status

## Database Connection Details

- **Host:** localhost
- **Database:** ezitom_db
- **User:** root
- **Password:** (blank)
- **Port:** 3306

## API Endpoints

### Projects API
- `GET /backend/api/projects.php` - Fetch all projects
- `GET /backend/api/projects.php?category=Business` - Filter by category
- Response: `{success: true, data: [...]}`

### Skills API
- `GET /backend/api/skills.php` - Fetch all skills
- Response: `{success: true, data: {...}}`

### Contact API
- `POST /backend/api/contact.php` - Submit contact form
- Payload: `{name, email, subject, message}`
- Response: `{status: 'success'|'error', message: '...'}`

### Setup API
- `GET /backend/api/setup.php` - Check database status
- `POST /backend/api/setup.php` - Initialize database
- Response: `{ready: true/false, projects: N, skills: N}`

## Troubleshooting

### Images Not Showing
- Check browser DevTools > Network tab for 404 errors
- Verify `/images/projects/` folder contains image files
- Database must have `image_url` values populated

### Forms Not Submitting
- Check DevTools Console for errors
- Verify XAMPP services are running (Apache + MySQL)
- Check `/backend/api/debug.log` for PHP errors

### Database Connection Fails
- Ensure MySQL is running in XAMPP Control Panel
- Verify database `ezitom_db` exists
- Check database credentials in `/backend/config/db.php`

## File Structure
```
/backend/
  /api/
    contact.php      ← Form submissions
    projects.php     ← Project data
    skills.php       ← Skills data
    setup.php        ← Database initialization
  /config/
    db.php           ← Database connection
    setup.sql        ← Database schema
  setup_database.php ← CLI setup script
  setup.html         ← Web setup interface

/images/projects/    ← Project thumbnails

/backend-api.js      ← Forms and project loader
/main.js             ← General functionality
/contact.html        ← Contact page
/projects.html       ← Projects showcase
```

## Next Steps

1. Initialize database (see above)
2. Verify projects appear on `/projects.html`
3. Test forms on `/contact.html` and `/index.html`
4. Configure email (optional - currently shows success toast)
5. Update social links in contact section

---
✅ **All set!** Your portfolio is ready to showcase projects and collect inquiries.
