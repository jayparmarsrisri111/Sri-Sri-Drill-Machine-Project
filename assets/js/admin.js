/**
 * Admin Panel JavaScript
 * Shree Varsha Engineering Works - Varsha Brand
 * Optimized for Desktop, Tablet, and Mobile UX
 */

document.addEventListener('DOMContentLoaded', function () {
  // Mobile & Tablet Sidebar Navigation Drawer
  const sidebar = document.getElementById('adminSidebar');
  const toggleBtn = document.getElementById('adminSidebarToggle');
  const closeBtn = document.getElementById('adminSidebarClose');
  const backdrop = document.getElementById('adminSidebarBackdrop');

  function openAdminSidebar() {
    if (sidebar) sidebar.classList.add('active');
    if (backdrop) backdrop.classList.add('active');
    document.body.classList.add('admin-sidebar-open');
  }

  function closeAdminSidebar() {
    if (sidebar) sidebar.classList.remove('active');
    if (backdrop) backdrop.classList.remove('active');
    document.body.classList.remove('admin-sidebar-open');
  }

  if (toggleBtn) {
    toggleBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      if (sidebar && sidebar.classList.contains('active')) {
        closeAdminSidebar();
      } else {
        openAdminSidebar();
      }
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', function (e) {
      e.preventDefault();
      closeAdminSidebar();
    });
  }

  if (backdrop) {
    backdrop.addEventListener('click', closeAdminSidebar);
  }

  // Close when clicking outside or pressing Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeAdminSidebar();
    }
  });

  // Automatically close sidebar when clicking a link on small viewports
  if (sidebar) {
    sidebar.querySelectorAll('.sidebar-link').forEach(link => {
      link.addEventListener('click', function () {
        if (window.innerWidth <= 1024) {
          closeAdminSidebar();
        }
      });
    });
  }

  // Confirm Delete Dialog
  const deleteLinks = document.querySelectorAll('.confirm-delete');
  deleteLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
        e.preventDefault();
      }
    });
  });

  // Table Search Filter
  const tableSearchInput = document.getElementById('tableSearch');
  if (tableSearchInput) {
    tableSearchInput.addEventListener('input', function () {
      const query = this.value.toLowerCase().trim();
      const rows = document.querySelectorAll('.admin-table tbody tr, .tech-table tbody tr');

      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        if (text.includes(query)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  }
});
