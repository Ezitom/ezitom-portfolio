<?php
/**
 * login.php — Admin login page
 */

// If already logged in, redirect to dashboard
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>false,'httponly'=>true,'samesite'=>'Lax']);
    session_start();
}
if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

// Handle form submission (POST to self)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../config/db.php';

    $username = trim($_POST['username'] ?? '');
    // Trimming the password to handle accidental spaces from copy-pasting
    $password = trim($_POST['password'] ?? '');

    if (!$username || !$password) {
        $error = 'Please enter both username and password.';
    } else {
        // Brute-force protection
        if (!isset($_SESSION['login_attempts']))     $_SESSION['login_attempts']    = 0;
        if (!isset($_SESSION['login_attempt_time'])) $_SESSION['login_attempt_time'] = time();

        if (time() - $_SESSION['login_attempt_time'] > 900) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['login_attempt_time'] = time();
        }

        if ($_SESSION['login_attempts'] >= 10) {
            $error = 'Too many failed attempts. Please wait 15 minutes.';
        } else {
            try {
                $pdo  = getPDO();
                $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :u LIMIT 1");
                $stmt->execute([':u' => $username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password_hash'])) {
                    session_regenerate_id(true);
                    $_SESSION['admin_id']       = (int)$user['id'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['login_attempts'] = 0;
                    header('Location: index.php');
                    exit;
                } else {
                    $_SESSION['login_attempts']++;
                    $error = 'Invalid username or password.';
                }
            } catch (PDOException $e) {
                $error = 'Database error. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — dev.folio</title>
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../../assets/favicon/favicon.ico">
  <link rel="icon" type="image/png" sizes="32x32" href="../../assets/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../../assets/favicon/favicon-16x16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-touch-icon.png">
  <link rel="manifest" href="../../assets/favicon/site.webmanifest">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --bg: #0f1117; --surface: #1a1d27; --border: #2e3247;
      --accent: #00d4c8; --text: #e8eaf0; --text2: #8b91a8;
      --danger: #ff4f6a; --radius: 10px;
    }
    body {
      background: var(--bg); color: var(--text);
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      min-height: 100vh; display: flex;
      align-items: center; justify-content: center;
    }
    .login-wrap {
      width: 100%; max-width: 400px; padding: 1.5rem;
    }
    .login-logo {
      text-align: center; margin-bottom: 2rem;
    }
    .login-logo h1 { font-size: 1.6rem; color: var(--accent); }
    .login-logo p  { color: var(--text2); font-size: .85rem; margin-top: .4rem; }
    .card {
      background: var(--surface); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 2rem;
    }
    .form-group { margin-bottom: 1.2rem; }
    .form-label { display: block; font-size: .82rem; color: var(--text2); margin-bottom: .45rem; }
    .form-control {
      width: 100%; background: #0f1117; border: 1px solid var(--border);
      color: var(--text); border-radius: var(--radius);
      padding: .65rem 1rem; font-size: .95rem; outline: none;
      transition: border-color .2s;
    }
    .form-control:focus { border-color: var(--accent); }
    .btn {
      width: 100%; padding: .75rem; background: var(--accent);
      color: #0f1117; border: none; border-radius: var(--radius);
      font-size: 1rem; font-weight: 700; cursor: pointer;
      transition: opacity .2s;
    }
    .btn:hover { opacity: .85; }
    .error-msg {
      background: rgba(255,79,106,.1); border: 1px solid rgba(255,79,106,.3);
      color: var(--danger); padding: .75rem 1rem; border-radius: var(--radius);
      font-size: .87rem; margin-bottom: 1.2rem;
    }
    .back-link {
      display: block; text-align: center; margin-top: 1.5rem;
      color: var(--text2); font-size: .82rem; text-decoration: none;
    }
    .back-link:hover { color: var(--accent); }
  </style>
</head>
<body>
  <div class="login-wrap">
    <div class="login-logo">
      <h1>dev.folio</h1>
      <p>Admin Panel</p>
    </div>
    <div class="card">
      <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <div class="form-group">
          <label class="form-label" for="username">Username</label>
          <input
            type="text" id="username" name="username"
            class="form-control"
            value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            autocomplete="username"
          >
        </div>
        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <input
            type="password" id="password" name="password"
            class="form-control"
            autocomplete="current-password"
          >
        </div>
        <button type="submit" class="btn">Sign In →</button>
      </form>
    </div>
    <a href="../../index.html" class="back-link">← Back to portfolio</a>
  </div>
</body>
</html>
