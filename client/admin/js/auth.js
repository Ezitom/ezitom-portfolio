/**
 * auth.js — Admin Authentication Helpers
 */

function isAuthenticated() {
  return localStorage.getItem('admin_logged_in') === 'true';
}

function logout() {
  localStorage.removeItem('admin_logged_in');
  localStorage.removeItem('admin_remember');
  window.location.href = 'login.html';
}

function requireAuth() {
  if (!isAuthenticated()) {
    window.location.href = 'login.html';
    return false;
  }
  return true;
}
