/**
 * SDWD Dashboard — Frontend profile editing.
 */
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('sdwd-dashboard-form');
  if (!form) return;

  const status = document.getElementById('sdwd-dashboard-status');

  form.addEventListener('submit', e => {
    e.preventDefault();

    const data = new FormData(form);
    data.append('action', 'sdwd_save_dashboard');
    data.append('nonce', sdwd_dash.nonce);

    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Saving…';

    fetch(sdwd_dash.url, { method: 'POST', body: data })
      .then(r => r.json())
      .then(res => {
        if (status) {
          status.textContent = res.data.message || 'Saved.';
          status.className = 'dash-status ' + (res.success ? 'dash-status--ok' : 'dash-status--err');
        }
        btn.disabled = false;
        btn.textContent = 'Save Changes';
      })
      .catch(() => {
        if (status) {
          status.textContent = 'Something went wrong.';
          status.className = 'dash-status dash-status--err';
        }
        btn.disabled = false;
        btn.textContent = 'Save Changes';
      });
  });

  /* --- Add/remove social rows --- */
  document.addEventListener('click', e => {
    const addBtn = e.target.closest('.dash-social__add');
    if (addBtn) {
      const container = addBtn.previousElementSibling;
      const rows = container.querySelectorAll('.dash-social__row');
      const last = rows[rows.length - 1];
      const clone = last.cloneNode(true);
      clone.querySelectorAll('input').forEach(input => { input.value = ''; });
      container.appendChild(clone);
      reindexSocial(container);
      return;
    }

    const removeBtn = e.target.closest('.dash-social__remove');
    if (removeBtn) {
      const row = removeBtn.closest('.dash-social__row');
      const container = row.parentElement;
      if (container.querySelectorAll('.dash-social__row').length > 1) {
        row.remove();
        reindexSocial(container);
      } else {
        row.querySelectorAll('input').forEach(input => { input.value = ''; });
      }
      return;
    }
  });

  function reindexSocial(container) {
    container.querySelectorAll('.dash-social__row').forEach((row, i) => {
      row.querySelectorAll('input').forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
          input.setAttribute('name', name.replace(/\[\d+\]/, '[' + i + ']'));
        }
      });
    });
  }
});
