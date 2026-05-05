  </main>
</div>

<script>
// Auto-dismiss alerts after 4 seconds
document.querySelectorAll('.alert').forEach(el => {
  setTimeout(() => el.style.display = 'none', 4000);
});
// Confirm on delete buttons (Event Delegation)
document.addEventListener('click', e => {
  const btn = e.target.closest('[data-confirm]');
  if (btn && !confirm(btn.getAttribute('data-confirm'))) {
    e.preventDefault();
  }
});
</script>
</body>
</html>
