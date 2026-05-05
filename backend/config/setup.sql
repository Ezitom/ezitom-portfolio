-- ============================================================
-- setup.sql — dev.folio Portfolio Database Setup
-- Run this in your MySQL console:
-- mysql -u root < setup.sql
-- ============================================================

-- Create database
CREATE DATABASE IF NOT EXISTS devfolio_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE devfolio_db;

-- ─────────────────────────────────────────────────────────────
-- TABLE: contacts
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS contacts (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(150)  NOT NULL,
  email      VARCHAR(254)  NOT NULL,
  subject    VARCHAR(200)  NOT NULL,
  message    TEXT          NOT NULL,
  ip_address VARCHAR(45)   DEFAULT NULL,
  created_at TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email      (email),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────────────────
-- TABLE: projects
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS projects (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(200)  NOT NULL,
  description TEXT          NOT NULL,
  tech_stack  JSON          NOT NULL,
  image_url   VARCHAR(500)  DEFAULT NULL,
  live_url    VARCHAR(500)  DEFAULT NULL,
  category    ENUM('General','Business','Events & Wedding') NOT NULL DEFAULT 'General',
  created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_category  (category),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────────────────
-- TABLE: skills
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS skills (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  skill_name  VARCHAR(100) NOT NULL,
  category    VARCHAR(100) NOT NULL,
  proficiency TINYINT UNSIGNED NOT NULL DEFAULT 80 COMMENT '0-100 percent',
  created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_skill (skill_name, category),
  INDEX idx_category (category)
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────────────────────
-- TABLE: admin_users
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS admin_users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(80)  NOT NULL UNIQUE,
  email         VARCHAR(254) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- ============================================================
-- SEED DATA
-- ============================================================

-- Admin user: username=admin, password=Admin@2025!
-- Hash generated with: password_hash('Admin@2025!', PASSWORD_BCRYPT)
INSERT INTO admin_users (username, email, password_hash) VALUES (
  'admin',
  'oniebenezer1@gmail.com',
  '$2y$10$BRU/dgKuTbRSiRL0PqpIruxc58MGFabFguIF5uQSXGSlCFMGRs.Ka'
) ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash);

-- Sample projects
INSERT IGNORE INTO projects (title, description, tech_stack, image_url, live_url, category) VALUES
(
  'See Mary See Beauty Salon',
  'A brand dedicated to helping individuals express their identity through their hair. With over 10 years of industry experience, the brand emphasises attention to detail and a deep understanding of personal style. The website reflects this expertise while providing a modern, user-friendly experience.',
  '["WordPress", "WooCommerce", "Custom CSS"]',
  'images/projects/Business website.png',
  'https://pcc.plz.mybluehost.me/website_f48cd1cd/',
  'Business'
),
(
  'Tolu & Dami: Our Story',
  'A couple in Abuja wanted a personal site to share their story, countdown to the big day, and collect RSVPs. Built a warm, elegant single-page site with a live countdown timer, photo gallery, and a working RSVP form that emails the couple on every submission.',
  '["HTML", "CSS", "Vanilla JS", "EmailJS"]',
  'images/projects/tolu-dami-wedding.svg',
  '#',
  'Events & Wedding'
),
(
  'GreenRoots Organic Store',
  'An organic food brand scaling from farmers markets to online sales. Built a full e-commerce storefront integrated with the Shopify Storefront API, custom cart UI, and a mobile-first checkout flow. First-week sales exceeded the client monthly in-store average.',
  '["Next.js", "Shopify API", "Stripe", "Tailwind CSS"]',
  'images/projects/greenroots-store.svg',
  '#',
  'Business'
),
(
  'DevNotes - Personal Blog',
  'A personal space on the web to write about real discoveries — not tutorials, just notes. Built with Next.js App Router and MDX so code, diagrams, and thoughts live in the same post. Fully statically generated, loads in under 1 second.',
  '["Next.js", "MDX", "Vercel", "TypeScript"]',
  'images/projects/devnotes-blog.svg',
  '#',
  'General'
),
(
  'Harbour Events Co.',
  'A high-end event planning company in Lagos needed a site that felt as premium as their service. Built a visually rich site with animated section transitions, a portfolio gallery, and an enquiry form that sends structured briefing emails directly to their inbox.',
  '["React", "Framer Motion", "Nodemailer", "Tailwind CSS"]',
  'images/projects/harbour-events.svg',
  '#',
  'Events & Wedding'
),
(
  'OpenTrack CLI',
  'A command-line tool built out of frustration — it pulls open GitHub issues across all repos into one prioritised list in the terminal. 200+ GitHub stars, adopted by multiple teams who reported significant time savings.',
  '["Node.js", "GitHub API", "npm", "Commander.js"]',
  'images/projects/opentrack-cli.svg',
  '#',
  'General'
);

-- Sample skills
INSERT IGNORE INTO skills (skill_name, category, proficiency) VALUES
-- Frontend
('HTML',           'Frontend', 95),
('CSS',            'Frontend', 95),
('JavaScript',     'Frontend', 90),
('WordPress',      'Frontend', 85),
-- Backend
('Node.js/Express','Backend',  85),
('Native PHP',     'Backend',  80),
('MySQL',          'Backend',  85),
('Python/Django',  'Backend',  78),
('REST & GraphQL APIs', 'Backend', 90),
('PostgreSQL',     'Backend',  82),
-- DevOps & Tooling
('Git',            'DevOps & Tooling', 90),
('GitHub Actions', 'DevOps & Tooling', 80),
('Vercel',         'DevOps & Tooling', 85),
('Netlify',        'DevOps & Tooling', 82);

-- Sample contact submission
INSERT IGNORE INTO contacts (name, email, subject, message, ip_address) VALUES
(
  'Test User',
  'test@example.com',
  'I want to discuss a project idea',
  'Hi! I came across your portfolio and I would love to discuss a web project I have in mind. Please get in touch.',
  '127.0.0.1'
);
