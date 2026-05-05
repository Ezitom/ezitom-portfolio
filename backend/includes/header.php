<?php
/**
 * header.php — Admin panel shared header
 * Expects $pageTitle to be set before including.
 */
$pageTitle = $pageTitle ?? 'Admin Panel';
$username  = htmlspecialchars($_SESSION['admin_username'] ?? 'Admin', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> — dev.folio Admin</title>
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../../assets/favicon/favicon.ico">
  <link rel="icon" type="image/png" sizes="32x32" href="../../assets/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../../assets/favicon/favicon-16x16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-touch-icon.png">
  <link rel="manifest" href="../../assets/favicon/site.webmanifest">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:        #0f1117;
      --surface:   #1a1d27;
      --surface2:  #222636;
      --border:    #2e3247;
      --accent:    #00d4c8;
      --accent2:   #7c6bff;
      --text:      #e8eaf0;
      --text2:     #8b91a8;
      --danger:    #ff4f6a;
      --success:   #00c97a;
      --warning:   #ffb347;
      --radius:    8px;
      --sidebar-w: 240px;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── Top Bar ── */
    .topbar {
      position: fixed; top: 0; left: 0; right: 0; z-index: 100;
      height: 56px;
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 1.5rem;
    }
    .topbar-brand {
      font-size: 1rem; font-weight: 700; letter-spacing: .05em;
      color: var(--accent); text-decoration: none;
    }
    .topbar-brand span { color: var(--text2); font-weight: 400; }
    .topbar-user {
      display: flex; align-items: center; gap: .75rem; font-size: .85rem;
      color: var(--text2);
    }
    .topbar-user strong { color: var(--text); }
    .logout-btn {
      background: transparent; border: 1px solid var(--border);
      color: var(--danger); padding: .3rem .8rem; border-radius: var(--radius);
      cursor: pointer; font-size: .8rem; text-decoration: none;
      transition: background .2s;
    }
    .logout-btn:hover { background: rgba(255,79,106,.1); }

    /* ── Layout ── */
    .layout {
      display: flex;
      margin-top: 56px;
      min-height: calc(100vh - 56px);
    }

    /* ── Sidebar ── */
    .sidebar {
      width: var(--sidebar-w);
      background: var(--surface);
      border-right: 1px solid var(--border);
      padding: 1.5rem 0;
      position: fixed; top: 56px; left: 0;
      height: calc(100vh - 56px);
      overflow-y: auto;
    }
    .sidebar-nav { list-style: none; }
    .sidebar-nav a {
      display: flex; align-items: center; gap: .6rem;
      padding: .65rem 1.5rem;
      color: var(--text2); text-decoration: none;
      font-size: .9rem; border-left: 3px solid transparent;
      transition: all .15s;
    }
    .sidebar-nav a:hover,
    .sidebar-nav a.active {
      color: var(--text);
      background: var(--surface2);
      border-left-color: var(--accent);
    }
    .sidebar-nav .nav-icon { font-size: 1rem; width: 20px; text-align: center; }

    /* ── Main content ── */
    .main-content {
      margin-left: var(--sidebar-w);
      flex: 1;
      padding: 2rem;
    }

    /* ── Page title ── */
    .page-header {
      margin-bottom: 1.5rem;
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 1rem;
    }
    .page-header h1 { font-size: 1.4rem; font-weight: 600; }
    .page-header p   { color: var(--text2); font-size: .9rem; margin-top: .2rem; }

    /* ── Cards ── */
    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
    .card-title {
      font-size: 1rem; font-weight: 600; margin-bottom: 1rem;
      padding-bottom: .75rem; border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
    }

    /* ── Buttons ── */
    .btn {
      display: inline-flex; align-items: center; gap: .4rem;
      padding: .5rem 1.1rem; border-radius: var(--radius);
      font-size: .85rem; font-weight: 500; cursor: pointer;
      border: none; text-decoration: none; transition: opacity .2s, transform .1s;
    }
    .btn:hover { opacity: .85; transform: translateY(-1px); }
    .btn:active { transform: translateY(0); }
    .btn-primary   { background: var(--accent);  color: #0f1117; }
    .btn-secondary { background: var(--surface2); color: var(--text); border: 1px solid var(--border); }
    .btn-danger    { background: var(--danger);   color: #fff; }
    .btn-sm { padding: .3rem .7rem; font-size: .78rem; }

    /* ── Forms ── */
    .form-group { margin-bottom: 1rem; }
    .form-label { display: block; font-size: .83rem; color: var(--text2); margin-bottom: .4rem; }
    .form-control {
      width: 100%; background: var(--surface2); border: 1px solid var(--border);
      color: var(--text); border-radius: var(--radius);
      padding: .55rem .85rem; font-size: .9rem; outline: none;
      transition: border-color .2s;
    }
    .form-control:focus { border-color: var(--accent); }
    select.form-control { cursor: pointer; }
    textarea.form-control { min-height: 100px; resize: vertical; }

    /* ── Tables ── */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: .88rem; }
    th {
      text-align: left; padding: .6rem .9rem;
      background: var(--surface2); color: var(--text2);
      font-size: .75rem; text-transform: uppercase; letter-spacing: .06em;
      border-bottom: 1px solid var(--border);
    }
    td {
      padding: .7rem .9rem; border-bottom: 1px solid var(--border);
      color: var(--text); vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: var(--surface2); }

    /* ── Badges / pills ── */
    .badge {
      display: inline-block; padding: .2rem .55rem;
      border-radius: 30px; font-size: .72rem; font-weight: 600;
      letter-spacing: .04em;
    }
    .badge-green  { background: rgba(0,201,122,.15);  color: var(--success); }
    .badge-blue   { background: rgba(0,212,200,.15);  color: var(--accent); }
    .badge-purple { background: rgba(124,107,255,.15);color: var(--accent2); }

    /* ── Alert ── */
    .alert {
      padding: .75rem 1rem; border-radius: var(--radius);
      margin-bottom: 1rem; font-size: .88rem;
    }
    .alert-success { background: rgba(0,201,122,.12); border: 1px solid rgba(0,201,122,.3); color: var(--success); }
    .alert-error   { background: rgba(255,79,106,.12); border: 1px solid rgba(255,79,106,.3); color: var(--danger); }

    /* ── Responsive ── */
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .main-content { margin-left: 0; padding: 1rem; }
    }
  </style>
</head>
<body>

<header class="topbar">
  <a href="index.php" class="topbar-brand">dev.folio <span>Admin</span></a>
  <div class="topbar-user">
    Logged in as <strong><?= $username ?></strong>
    <a href="index.php?action=logout" class="logout-btn">Log Out</a>
  </div>
</header>

<div class="layout">
  <aside class="sidebar">
    <ul class="sidebar-nav">
      <li>
        <a href="index.php" class="<?= (!isset($_GET['tab']) || $_GET['tab'] === 'projects') ? 'active' : '' ?>">
          <span class="nav-icon">🗂</span> Projects
        </a>
      </li>
      <li>
        <a href="index.php?tab=skills" class="<?= ($_GET['tab'] ?? '') === 'skills' ? 'active' : '' ?>">
          <span class="nav-icon">⚡</span> Skills
        </a>
      </li>
      <li>
        <a href="index.php?tab=contacts" class="<?= ($_GET['tab'] ?? '') === 'contacts' ? 'active' : '' ?>">
          <span class="nav-icon">✉️</span> Messages
        </a>
      </li>
    </ul>
  </aside>

  <main class="main-content">
