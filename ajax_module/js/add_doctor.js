// Vanilla JS to submit Add Doctor form via AJAX using fetch and FormData
(function(){
  // Helper: find the add doctor form
  const form = document.getElementById('add-doctor-form');
  if (!form) return; // nothing to do if form not present

  // Create and insert a small status container for errors/loading
  const statusEl = document.createElement('div');
  statusEl.className = 'ajax-status';
  form.prepend(statusEl);

  // Helper: clear previous ajax errors
  function clearErrors() {
    form.querySelectorAll('.form-error-ajax').forEach(e => e.remove());
    form.querySelectorAll('.error').forEach(i => i.classList.remove('error'));
    statusEl.textContent = '';
  }

  // Helper: show field errors
  function showErrors(errors) {
    for (const [k,v] of Object.entries(errors)) {
      const field = form.querySelector('[name="' + k + '"]');
      if (field) {
        field.classList.add('error');
        const err = document.createElement('div');
        err.className = 'form-error form-error-ajax';
        err.textContent = '⚠ ' + v;
        // place after field
        field.insertAdjacentElement('afterend', err);
      }
    }
  }

  // Helper: build table row HTML for the new doctor
  function buildRow(d) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${d.photo_url ? '<img src="'+d.photo_url+'" class="photo-preview">' : '<div class="photo-placeholder">👤</div>'}</td>
      <td><strong>${escapeHtml(d.name)}</strong><br><small class="text-muted">${escapeHtml(d.email)}</small></td>
      <td>${escapeHtml(d.specialization_name)}</td>
      <td>$${escapeHtml(d.consultation_fee)}</td>
      <td><small>${escapeHtml(d.available_days)}</small></td>
      <td><span class="badge badge-active">Active</span></td>
      <td><strong>0</strong></td>
      <td>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <a href="${location.protocol}//${location.host}${location.pathname}?page=admin/doctor-edit&id=${d.id}" class="btn btn-ghost btn-sm">Edit</a>
          <form method="POST" action="${location.protocol}//${location.host}${location.pathname}?page=admin/doctor-deactivate" style="display:inline;">
            <input type="hidden" name="id" value="${d.id}">
            <button type="submit" class="btn btn-sm btn-warning">Deactivate</button>
          </form>
        </div>
      </td>
    `;
    return tr;
  }

  // Basic HTML escaping
  function escapeHtml(s){ return String(s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c]; }); }

  // Submission handler
  form.addEventListener('submit', function(e){
    // Allow fallback when JS disabled — prevent default only when we handle AJAX
    e.preventDefault();
    clearErrors();

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn ? submitBtn.textContent : null;
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.textContent = 'Adding...';
    }

    const fd = new FormData(form);

    fetch('/ajax_module/handlers/add_doctor.php', {
      method: 'POST',
      body: fd,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    }).then(async res => {
      const data = await res.json().catch(()=>({}));
      if (!res.ok) {
        if (data && data.errors) {
          showErrors(data.errors);
        } else {
          statusEl.textContent = 'An unexpected error occurred.';
        }
      } else {
        if (data.success) {
          // append new row to table if present
          const tbody = document.querySelector('.table-wrap table tbody');
          if (tbody) {
            const row = buildRow(data.doctor);
            tbody.prepend(row);
          }
          // close modal
          const modal = document.getElementById('add-doctor-modal');
          if (modal) modal.classList.remove('open');
        } else if (data.errors) {
          showErrors(data.errors);
        } else {
          statusEl.textContent = 'Unexpected response from server.';
        }
      }
    }).catch(err => {
      statusEl.textContent = 'Network error. Please try again.';
    }).finally(() => {
      if (submitBtn) {
        submitBtn.disabled = false;
        if (originalBtnText) submitBtn.textContent = originalBtnText;
      }
    });
  });
})();
