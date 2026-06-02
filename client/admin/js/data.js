/**
 * data.js — LocalStorage Data Layer
 * ====================================
 * Single source of truth for projects, skills, and messages.
 * Seeds from DEFAULT_PROJECTS / DEFAULT_SKILLS on first run.
 */

// ── Seed on first load ──────────────────────────────────────
function seedDataIfEmpty() {
  if (!localStorage.getItem('portfolio_projects')) {
    localStorage.setItem('portfolio_projects', JSON.stringify(DEFAULT_PROJECTS));
  }
  if (!localStorage.getItem('portfolio_skills')) {
    localStorage.setItem('portfolio_skills', JSON.stringify(DEFAULT_SKILLS));
  }
  if (!localStorage.getItem('portfolio_messages')) {
    localStorage.setItem('portfolio_messages', JSON.stringify([]));
  }
}

// ── Projects ───────────────────────────────────────────────
function getProjects() {
  seedDataIfEmpty();
  return JSON.parse(localStorage.getItem('portfolio_projects') || '[]');
}

function saveProjects(projects) {
  localStorage.setItem('portfolio_projects', JSON.stringify(projects));
}

function addProject(project) {
  const projects = getProjects();
  project.id = Date.now();
  projects.push(project);
  saveProjects(projects);
  return project;
}

function updateProject(id, updates) {
  const projects = getProjects();
  const idx = projects.findIndex(p => p.id === id);
  if (idx === -1) return null;
  projects[idx] = { ...projects[idx], ...updates };
  saveProjects(projects);
  return projects[idx];
}

function deleteProject(id) {
  const projects = getProjects().filter(p => p.id !== id);
  saveProjects(projects);
}

// ── Skills ─────────────────────────────────────────────────
function getSkills() {
  seedDataIfEmpty();
  return JSON.parse(localStorage.getItem('portfolio_skills') || '[]');
}

function saveSkills(skills) {
  localStorage.setItem('portfolio_skills', JSON.stringify(skills));
}

function addSkill(skill) {
  const skills = getSkills();
  skill.id = Date.now();
  skills.push(skill);
  saveSkills(skills);
  return skill;
}

function updateSkill(id, updates) {
  const skills = getSkills();
  const idx = skills.findIndex(s => s.id === id);
  if (idx === -1) return null;
  skills[idx] = { ...skills[idx], ...updates };
  saveSkills(skills);
  return skills[idx];
}

function deleteSkill(id) {
  const skills = getSkills().filter(s => s.id !== id);
  saveSkills(skills);
}

// ── Messages ───────────────────────────────────────────────
function getMessages() {
  return JSON.parse(localStorage.getItem('portfolio_messages') || '[]');
}

function saveMessages(messages) {
  localStorage.setItem('portfolio_messages', JSON.stringify(messages));
}

function markMessageRead(id) {
  const messages = getMessages();
  const msg = messages.find(m => m.id === id);
  if (msg) { msg.read = true; saveMessages(messages); }
}

function deleteMessage(id) {
  const messages = getMessages().filter(m => m.id !== id);
  saveMessages(messages);
}

function getUnreadCount() {
  return getMessages().filter(m => !m.read).length;
}
