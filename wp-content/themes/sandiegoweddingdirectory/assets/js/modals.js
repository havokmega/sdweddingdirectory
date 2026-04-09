/**
 * SDWD Modal System
 *
 * Handles open/close/switch, AJAX form submission via sdwd_ajax,
 * and vendor/venue field toggling on the registration modal.
 */
document.addEventListener('DOMContentLoaded', () => {

  /* ── Open / Close / Switch ─────────────────────────────── */

  const openModal = id => {
    const overlay = document.getElementById('sdwd-modal-' + id);
    if (!overlay) return;
    document.querySelectorAll('.sdwd-modal-overlay.is-open').forEach(o => o.classList.remove('is-open'));
    overlay.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    const first = overlay.querySelector('input:not([type="hidden"]):not([type="radio"]), select, textarea');
    if (first) setTimeout(() => first.focus(), 200);
  };

  const closeModal = overlay => {
    if (!overlay) return;
    overlay.classList.remove('is-open');
    document.body.style.overflow = '';
    const alert = overlay.querySelector('.sdwd-modal__alert');
    if (alert) {
      alert.className = 'sdwd-modal__alert';
      alert.textContent = '';
    }
  };

  const closeAll = () => {
    document.querySelectorAll('.sdwd-modal-overlay.is-open').forEach(o => closeModal(o));
  };

  document.addEventListener('click', e => {
    const openTrigger = e.target.closest('[data-sdwd-modal-open]');
    if (openTrigger) {
      e.preventDefault();
      openModal(openTrigger.dataset.sdwdModalOpen);
      return;
    }

    const switchTrigger = e.target.closest('[data-sdwd-modal-switch]');
    if (switchTrigger) {
      e.preventDefault();
      closeAll();
      openModal(switchTrigger.dataset.sdwdModalSwitch);
      return;
    }

    if (e.target.closest('[data-sdwd-modal-close]')) {
      e.preventDefault();
      closeModal(e.target.closest('.sdwd-modal-overlay'));
      return;
    }

    if (e.target.classList.contains('sdwd-modal-overlay') && e.target.classList.contains('is-open')) {
      closeModal(e.target);
    }
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeAll();
  });

  /* ── Alert helper ──────────────────────────────────────── */

  const showAlert = (overlay, message, type) => {
    const alert = overlay.querySelector('.sdwd-modal__alert');
    if (!alert) return;
    alert.className = 'sdwd-modal__alert sdwd-modal__alert--' + type + ' is-visible';
    alert.textContent = message;
  };

  /* ── AJAX form submission ──────────────────────────────── */

  const ajaxConfig = (typeof sdwd_ajax !== 'undefined') ? sdwd_ajax : null;

  document.querySelectorAll('form[data-sdwd-form]').forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();
      if (!ajaxConfig) return;

      const overlay = form.closest('.sdwd-modal-overlay');
      const submitBtn = form.querySelector('[type="submit"]');
      const origText = submitBtn ? submitBtn.textContent : '';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Please wait\u2026';
      }

      const data = new FormData(form);
      data.append('action', 'sdwd_' + form.dataset.sdwdForm);
      data.append('nonce', ajaxConfig.nonce);

      fetch(ajaxConfig.url, { method: 'POST', body: data, credentials: 'same-origin' })
        .then(r => r.json())
        .then(res => {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = origText;
          }

          if (res.success) {
            showAlert(overlay, res.data.message || 'Success!', 'success');
            if (res.data.redirect) {
              setTimeout(() => { window.location.href = res.data.redirect; }, 1200);
            }
          } else {
            showAlert(overlay, (res.data && res.data.message) || 'Something went wrong.', 'error');
          }
        })
        .catch(() => {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = origText;
          }
          showAlert(overlay, 'Network error. Please try again.', 'error');
        });
    });
  });

  /* ── Vendor/Venue registration toggle ──────────────────── */

  const vendorForm = document.getElementById('sdwd-vendor-register-form');
  if (vendorForm) {
    const addressRow = document.getElementById('sdwd-venue-address-row');
    const zipRow = document.getElementById('sdwd-venue-zip-row');
    const categoryField = document.getElementById('sdwd-vendor-category-field');
    const venueTypeField = document.getElementById('sdwd-venue-type-field');

    const toggleVendorFields = type => {
      const isVenue = type === 'venue';

      // Address + zip rows: show for venue, hide for vendor.
      if (addressRow) addressRow.style.display = isVenue ? '' : 'none';
      if (zipRow) zipRow.style.display = isVenue ? '' : 'none';

      // Category dropdown: show for vendor, hide for venue.
      if (categoryField) categoryField.style.display = isVenue ? 'none' : '';

      // Venue type dropdown: show for venue, hide for vendor.
      if (venueTypeField) venueTypeField.style.display = isVenue ? '' : 'none';
    };

    vendorForm.querySelectorAll('input[name="account_type"]').forEach(radio => {
      radio.addEventListener('change', () => toggleVendorFields(radio.value));
    });

    // Set initial state.
    const checked = vendorForm.querySelector('input[name="account_type"]:checked');
    if (checked) toggleVendorFields(checked.value);
  }
});
