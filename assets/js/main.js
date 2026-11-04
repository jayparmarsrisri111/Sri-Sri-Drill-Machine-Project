/**
 * Main Frontend JavaScript
 * Shree Varsha Engineering Works
 */

document.addEventListener('DOMContentLoaded', function () {
  // 1. Sticky Navigation Scroll Effect
  const navbar = document.querySelector('.navbar');
  window.addEventListener('scroll', function () {
    if (window.scrollY > 40) {
      navbar?.classList.add('scrolled');
    } else {
      navbar?.classList.remove('scrolled');
    }
  });

  // 2. Mobile Menu Toggle with Auto-Close & Overlay
  const mobileToggle = document.querySelector('.mobile-toggle');
  const navMenu = document.querySelector('.nav-menu');
  if (mobileToggle && navMenu) {
    mobileToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      const isActive = navMenu.classList.toggle('active');
      mobileToggle.innerHTML = isActive 
        ? '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>' 
        : '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>';
    });

    // Close when clicking any nav link
    navMenu.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        navMenu.classList.remove('active');
        mobileToggle.innerHTML = '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>';
      });
    });

    // Close when clicking outside
    document.addEventListener('click', function(e) {
      if (navMenu.classList.contains('active') && !navMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
        navMenu.classList.remove('active');
        mobileToggle.innerHTML = '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>';
      }
    });
  }

  // 3. Machine Catalog Filtering
  const filterBtns = document.querySelectorAll('.filter-btn');
  const machineCards = document.querySelectorAll('.machine-card');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      filterBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');

      const filterValue = this.getAttribute('data-filter');

      machineCards.forEach(card => {
        const category = card.getAttribute('data-category');
        if (filterValue === 'all' || category === filterValue) {
          card.style.display = 'flex';
          card.style.opacity = '1';
        } else {
          card.style.display = 'none';
          card.style.opacity = '0';
        }
      });
    });
  });

  // 4. Quote / Inquiry Modal
  const modal = document.getElementById('quoteModal');
  const modalClose = document.querySelector('.modal-close');
  const quoteBtns = document.querySelectorAll('.open-quote-modal');
  const modalMachineInput = document.getElementById('modalMachineName');
  const modalMachineIdInput = document.getElementById('modalMachineId');

  function openModal(machineName = '', machineId = '') {
    if (!modal) return;
    if (modalMachineInput) modalMachineInput.value = machineName;
    if (modalMachineIdInput) modalMachineIdInput.value = machineId;
    modal.classList.add('active');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
  window.openQuoteModal = openModal;

  function closeModal() {
    if (!modal) return;
    modal.classList.remove('active');
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }
  window.closeQuoteModal = closeModal;

  quoteBtns.forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const mName = this.getAttribute('data-machine-name') || '';
      const mId = this.getAttribute('data-machine-id') || '';
      openModal(mName, mId);
    });
  });

  // Attach close listener to all modal close triggers
  document.querySelectorAll('.modal-close').forEach(closeBtn => {
    closeBtn.addEventListener('click', function() {
      const parentOverlay = this.closest('.modal-overlay');
      if (parentOverlay) {
        parentOverlay.classList.remove('active');
        parentOverlay.style.display = 'none';
        document.body.style.overflow = '';
      } else {
        closeModal();
      }
    });
  });

  if (modal) {
    modal.addEventListener('click', function (e) {
      if (e.target === modal) {
        closeModal();
      }
    });
  }

  // Close modal on Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal?.classList.contains('active')) {
      closeModal();
    }
  });

  // 5. Ajax Inquiry Form Handler
  const inquiryForms = document.querySelectorAll('.inquiry-form');
  inquiryForms.forEach(form => {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn ? submitBtn.innerHTML : 'Submit';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '⚡ Submitting Inquiry...';
      }

      const formData = new FormData(this);

      fetch(this.getAttribute('action') || 'get-quote.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          // Show success message
          alert(data.message || 'Thank you! Your inquiry has been sent successfully. Our team will contact you shortly.');
          form.reset();
          closeModal();

          // If user checked WhatsApp direct or wants instant chat
          if (data.whatsapp_url) {
            window.open(data.whatsapp_url, '_blank');
          }
        } else {
          alert(data.message || 'Something went wrong. Please call us directly at +91 9824913555.');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Thank you! Inquiry submitted. For immediate reply, please call +91 9824913555.');
        form.reset();
        closeModal();
      })
      .finally(() => {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;
        }
      });
    });
  });
});
