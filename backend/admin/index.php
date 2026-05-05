<?php
/**
 * index.php — Admin Dashboard
 * Tabs: Projects | Skills | Messages
 */

require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/db.php';

// ── Handle logout ─────────────────────────────────────────────
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}

$pdo       = getPDO();
$tab       = $_POST['tab'] ?? $_GET['tab'] ?? 'projects';
$alert     = '';
$alertType = 'success';

// ══════════════════════════════════════════════════════════════
// PROJECTS ACTIONS
// ══════════════════════════════════════════════════════════════
if ($tab === 'projects' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $action   = $_POST['action'] ?? '';
    $title    = trim($_POST['title']       ?? '');
    $desc     = trim($_POST['description'] ?? '');
    $tech_raw = trim($_POST['tech_stack']  ?? '');
    $img_url  = trim($_POST['image_url']   ?? '');
    $live_url = trim($_POST['live_url']    ?? '');
    $category = trim($_POST['category']    ?? 'General');
    $techArr  = array_filter(array_map('trim', explode(',', $tech_raw)));
    $techJson = json_encode(array_values($techArr));

    if ($action === 'add') {
        if (!$title || !$desc) { $alert = 'Title and description are required.'; $alertType = 'error'; }
        else {
            $stmt = $pdo->prepare("INSERT INTO projects (title,description,tech_stack,image_url,live_url,category) VALUES(:t,:d,:ts,:i,:l,:c)");
            $stmt->execute([':t'=>htmlspecialchars($title,ENT_QUOTES,'UTF-8'),':d'=>htmlspecialchars($desc,ENT_QUOTES,'UTF-8'),':ts'=>$techJson,':i'=>htmlspecialchars($img_url,ENT_QUOTES,'UTF-8'),':l'=>htmlspecialchars($live_url,ENT_QUOTES,'UTF-8'),':c'=>$category]);
            $alert = 'Project added successfully.';
        }
    }

    if ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !$title || !$desc) { $alert = 'ID, title, and description are required.'; $alertType = 'error'; }
        else {
            $stmt = $pdo->prepare("UPDATE projects SET title=:t,description=:d,tech_stack=:ts,image_url=:i,live_url=:l,category=:c WHERE id=:id");
            $stmt->execute([':t'=>htmlspecialchars($title,ENT_QUOTES,'UTF-8'),':d'=>htmlspecialchars($desc,ENT_QUOTES,'UTF-8'),':ts'=>$techJson,':i'=>htmlspecialchars($img_url,ENT_QUOTES,'UTF-8'),':l'=>htmlspecialchars($live_url,ENT_QUOTES,'UTF-8'),':c'=>$category,':id'=>$id]);
            $alert = 'Project updated.';
        }
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM projects WHERE id=:id");
            $stmt->execute([':id'=>$id]);
            if ($stmt->rowCount() > 0) {
                $alert = 'Project deleted successfully.';
                $alertType = 'success';
            } else {
                $alert = 'Project not found or already deleted.';
                $alertType = 'error';
            }
        }
    }
}
// ══════════════════════════════════════════════════════════════
// MESSAGES ACTIONS
// ══════════════════════════════════════════════════════════════
if ($tab === 'contacts' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $pdo->prepare("DELETE FROM contacts WHERE id=:id")->execute([':id'=>$id]);
            $alert = 'Message deleted.';
            $alertType = 'success';
        }
    }
}

// ══════════════════════════════════════════════════════════════
// SKILLS ACTIONS
// ══════════════════════════════════════════════════════════════
if ($tab === 'skills' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $action      = $_POST['action']      ?? '';
    $skill_name  = trim($_POST['skill_name']  ?? '');
    $category    = trim($_POST['category']    ?? '');
    $proficiency = (int)($_POST['proficiency'] ?? 80);

    if ($action === 'add') {
        if (!$skill_name || !$category) { $alert = 'Skill name and category are required.'; $alertType = 'error'; }
        else {
            try {
                $stmt = $pdo->prepare("INSERT INTO skills (skill_name,category,proficiency) VALUES(:n,:c,:p)");
                $stmt->execute([':n'=>htmlspecialchars($skill_name,ENT_QUOTES,'UTF-8'),':c'=>htmlspecialchars($category,ENT_QUOTES,'UTF-8'),':p'=>max(0,min(100,$proficiency))]);
                $alert = 'Skill added.';
            } catch (PDOException $e) {
                $alert = 'This skill already exists in that category.'; $alertType = 'error';
            }
        }
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) { $pdo->prepare("DELETE FROM skills WHERE id=:id")->execute([':id'=>$id]); $alert = 'Skill deleted.'; }
    }
}

// ── Fetch data for current tab ────────────────────────────────
$projects = $skills = $contacts = [];
$editProject = null;

if ($tab === 'projects') {
    $projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
    if (isset($_GET['edit'])) {
        $s = $pdo->prepare("SELECT * FROM projects WHERE id=:id");
        $s->execute([':id'=>(int)$_GET['edit']]);
        $editProject = $s->fetch();
    }
}
if ($tab === 'skills') {
    $skills = $pdo->query("SELECT * FROM skills ORDER BY category, proficiency DESC")->fetchAll();
}
if ($tab === 'contacts') {
    $contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
}

$pageTitle = ucfirst($tab);
require_once __DIR__ . '/../includes/header.php';
?>

<?php if ($alert): ?>
  <div class="alert alert-<?= $alertType === 'error' ? 'error' : 'success' ?>">
    <?= htmlspecialchars($alert, ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php endif; ?>

<!-- ══════════════════ PROJECTS TAB ══════════════════ -->
<?php if ($tab === 'projects'): ?>

<div class="page-header">
  <div>
    <h1>Projects</h1>
    <p>Manage your portfolio projects</p>
  </div>
  <a href="index.php?tab=projects" class="btn btn-secondary btn-sm">+ New Project</a>
</div>

<!-- Add / Edit Form -->
<div class="card">
  <div class="card-title">
    <?= $editProject ? '✏️ Edit Project' : '➕ Add New Project' ?>
  </div>
  <form method="POST" action="index.php?tab=projects">
    <input type="hidden" name="action" value="<?= $editProject ? 'edit' : 'add' ?>">
    <?php if ($editProject): ?>
      <input type="hidden" name="id" value="<?= (int)$editProject['id'] ?>">
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
      <div class="form-group">
        <label class="form-label">Title *</label>
        <input name="title" class="form-control" required
          value="<?= htmlspecialchars($editProject['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Category *</label>
        <select name="category" class="form-control">
          <?php foreach(['General','Business','Events & Wedding'] as $cat): ?>
            <option value="<?= $cat ?>" <?= ($editProject['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Description *</label>
      <textarea name="description" class="form-control" required><?= htmlspecialchars($editProject['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
      <div class="form-group">
        <label class="form-label">Tech Stack (comma-separated)</label>
        <input name="tech_stack" class="form-control"
          value="<?php
            if ($editProject) {
              $ts = json_decode($editProject['tech_stack'], true) ?? [];
              echo htmlspecialchars(implode(', ', $ts), ENT_QUOTES, 'UTF-8');
            }
          ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Live URL</label>
        <input name="live_url" class="form-control" type="url"
          value="<?= htmlspecialchars($editProject['live_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Image path (relative to project root)</label>
      <input name="image_url" class="form-control"
        value="<?= htmlspecialchars($editProject['image_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div style="display:flex;gap:.75rem;">
      <button type="submit" class="btn btn-primary">
        <?= $editProject ? 'Save Changes' : 'Add Project' ?>
      </button>
      <?php if ($editProject): ?>
        <a href="index.php?tab=projects" class="btn btn-secondary">Cancel</a>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- Projects Table -->
<div class="card">
  <div class="card-title">All Projects (<?= count($projects) ?>)</div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th><th>Title</th><th>Category</th><th>Tech Stack</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projects as $p): ?>
          <?php $ts = json_decode($p['tech_stack'], true) ?? []; ?>
          <tr>
            <td><?= (int)$p['id'] ?></td>
            <td>
              <strong><?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?></strong>
              <?php if ($p['live_url'] && $p['live_url'] !== '#'): ?>
                <br><a href="<?= htmlspecialchars($p['live_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank"
                   style="color:var(--accent);font-size:.78rem;">↗ View</a>
              <?php endif; ?>
            </td>
            <td>
              <span class="badge <?= $p['category'] === 'Business' ? 'badge-green' : ($p['category'] === 'General' ? 'badge-blue' : 'badge-purple') ?>">
                <?= htmlspecialchars($p['category'], ENT_QUOTES, 'UTF-8') ?>
              </span>
            </td>
            <td style="color:var(--text2);font-size:.8rem;"><?= htmlspecialchars(implode(', ', $ts), ENT_QUOTES, 'UTF-8') ?></td>
            <td>
              <a href="index.php?tab=projects&edit=<?= (int)$p['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
              <form class="delete-form" data-endpoint="../api/projects.php" data-id="<?= (int)$p['id'] ?>" style="display:inline;">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($projects)): ?>
          <tr><td colspan="5" style="color:var(--text2);text-align:center;padding:2rem;">No projects yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ══════════════════ SKILLS TAB ══════════════════ -->
<?php elseif ($tab === 'skills'): ?>

<div class="page-header">
  <div><h1>Skills</h1><p>Manage your skills and tech stack</p></div>
</div>

<div class="card">
  <div class="card-title">➕ Add Skill</div>
  <form method="POST" action="index.php?tab=skills">
    <input type="hidden" name="action" value="add">
    <div style="display:grid;grid-template-columns:1fr 1fr 120px auto;gap:1rem;align-items:flex-end;">
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label">Skill Name *</label>
        <input name="skill_name" class="form-control" required placeholder="e.g. Vue.js">
      </div>
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label">Category *</label>
        <input name="category" class="form-control" required placeholder="e.g. Frontend">
      </div>
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label">Proficiency %</label>
        <input name="proficiency" class="form-control" type="number" min="0" max="100" value="80">
      </div>
      <button type="submit" class="btn btn-primary" style="height:42px;">Add</button>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-title">All Skills (<?= count($skills) ?>)</div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>#</th><th>Skill</th><th>Category</th><th>Proficiency</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($skills as $s): ?>
          <tr>
            <td><?= (int)$s['id'] ?></td>
            <td><?= htmlspecialchars($s['skill_name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><span class="badge badge-blue"><?= htmlspecialchars($s['category'], ENT_QUOTES, 'UTF-8') ?></span></td>
            <td>
              <div style="display:flex;align-items:center;gap:.6rem;">
                <div style="flex:1;background:var(--border);border-radius:30px;height:6px;min-width:80px;">
                  <div style="width:<?= (int)$s['proficiency'] ?>%;background:var(--accent);height:6px;border-radius:30px;"></div>
                </div>
                <span style="font-size:.8rem;color:var(--text2);"><?= (int)$s['proficiency'] ?>%</span>
              </div>
            </td>
            <td>
              <form class="delete-form" data-endpoint="../api/skills.php" data-id="<?= (int)$s['id'] ?>" style="display:inline;">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($skills)): ?>
          <tr><td colspan="5" style="color:var(--text2);text-align:center;padding:2rem;">No skills yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ══════════════════ CONTACTS TAB ══════════════════ -->
<?php elseif ($tab === 'contacts'): ?>

<div class="page-header">
  <div><h1>Messages</h1><p>Contact form submissions from your portfolio</p></div>
  <span style="color:var(--text2);font-size:.85rem;"><?= count($contacts) ?> total</span>
</div>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>#</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($contacts as $c): ?>
          <tr>
            <td><?= (int)$c['id'] ?></td>
            <td><?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>
              <a href="mailto:<?= htmlspecialchars($c['email'], ENT_QUOTES, 'UTF-8') ?>"
                 style="color:var(--accent);">
                <?= htmlspecialchars($c['email'], ENT_QUOTES, 'UTF-8') ?>
              </a>
            </td>
            <td style="font-size:.82rem;color:var(--text2);"><?= htmlspecialchars($c['subject'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="max-width:280px;font-size:.83rem;color:var(--text2);">
              <?= nl2br(htmlspecialchars(substr($c['message'], 0, 120), ENT_QUOTES, 'UTF-8')) ?>
              <?= strlen($c['message']) > 120 ? '…' : '' ?>
            </td>
            <td style="font-size:.78rem;color:var(--text2);white-space:nowrap;">
              <?= date('d M Y, H:i', strtotime($c['created_at'])) ?>
            </td>
            <td>
              <form class="delete-form" data-endpoint="../api/contact.php" data-id="<?= (int)$c['id'] ?>" style="display:inline;">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($contacts)): ?>
          <tr><td colspan="6" style="color:var(--text2);text-align:center;padding:2rem;">No messages yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const deleteForms = document.querySelectorAll('.delete-form');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const id = form.getAttribute('data-id');
            const endpoint = form.getAttribute('data-endpoint');
            
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                return;
            }
            
            const btn = form.querySelector('button');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Deleting...';
            
            try {
                const response = await fetch(endpoint, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Find the parent row and remove it
                    const row = form.closest('tr');
                    if (row) {
                        row.style.opacity = '0.5';
                        row.style.transition = 'opacity 0.3s ease';
                        setTimeout(() => row.remove(), 300);
                    }
                    // Optional: show a mini toast or alert
                    console.log('Deleted successfully');
                } else {
                    alert('Error: ' + (result.message || 'Unknown error occurred'));
                    btn.disabled = false;
                    btn.textContent = originalText;
                }
            } catch (error) {
                console.error('Delete fetch error:', error);
                alert('Unable to connect to the server. Please try again.');
                btn.disabled = false;
                btn.textContent = originalText;
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
