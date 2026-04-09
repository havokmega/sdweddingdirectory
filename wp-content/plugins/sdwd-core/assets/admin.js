/**
 * SDWD Core — Admin JS
 *
 * Handles repeatable fields (social media, pricing features)
 * and pricing tier add/remove in meta boxes.
 */
document.addEventListener('DOMContentLoaded', () => {

  /* ── Repeatable rows (social links, features) ──────────── */

  document.addEventListener('click', e => {

    // Add row.
    const addBtn = e.target.closest('.sdwd-repeater__add');
    if (addBtn) {
      const repeater = addBtn.closest('.sdwd-repeater');
      const items = repeater.querySelector('.sdwd-repeater__items');
      const rows = items.querySelectorAll('.sdwd-repeater__row');
      const max = parseInt(addBtn.dataset.sdwdMax, 10) || 50;

      if (rows.length >= max) return;

      const lastRow = rows[rows.length - 1];
      const newRow = lastRow.cloneNode(true);

      // Clear values and update name indices.
      newRow.querySelectorAll('input').forEach(input => {
        input.value = '';
      });

      items.appendChild(newRow);
      reindexRepeater(items);
      return;
    }

    // Remove row.
    const removeBtn = e.target.closest('.sdwd-repeater__remove');
    if (removeBtn) {
      const row = removeBtn.closest('.sdwd-repeater__row');
      const items = row.parentElement;
      if (items.querySelectorAll('.sdwd-repeater__row').length > 1) {
        row.remove();
        reindexRepeater(items);
      } else {
        // Last row — just clear it.
        row.querySelectorAll('input').forEach(input => { input.value = ''; });
      }
      return;
    }
  });

  /**
   * Re-index name attributes after add/remove so they stay sequential.
   */
  function reindexRepeater(items) {
    items.querySelectorAll('.sdwd-repeater__row').forEach((row, i) => {
      row.querySelectorAll('input').forEach(input => {
        const name = input.getAttribute('name');
        if (!name) return;
        // Replace [N] with new index — handles sdwd_social[0][label] pattern.
        input.setAttribute('name', name.replace(/\[\d+\]/, '[' + i + ']'));
      });
    });
  }

  /* ── Pricing tier add/remove ───────────────────────────── */

  function buildTierHeading(h4, index) {
    // Clear existing content safely.
    while (h4.firstChild) {
      h4.removeChild(h4.firstChild);
    }
    h4.appendChild(document.createTextNode('Tier ' + (index + 1) + ' '));
    if (index > 0) {
      const removeBtn = document.createElement('button');
      removeBtn.type = 'button';
      removeBtn.className = 'button-link sdwd-pricing-tier__remove';
      removeBtn.textContent = 'Remove';
      h4.appendChild(removeBtn);
    }
  }

  document.addEventListener('click', e => {

    // Add tier.
    const addTier = e.target.closest('.sdwd-pricing-tier__add');
    if (addTier) {
      const container = addTier.closest('.sdwd-pricing-tiers');
      const tiers = container.querySelectorAll('.sdwd-pricing-tier');

      if (tiers.length >= 3) return;

      const lastTier = tiers[tiers.length - 1];
      const newTier = lastTier.cloneNode(true);
      const newIndex = tiers.length;

      // Update tier heading.
      const h4 = newTier.querySelector('h4');
      if (h4) {
        buildTierHeading(h4, newIndex);
      }

      // Clear all input values and update name indices.
      newTier.querySelectorAll('input').forEach(input => {
        input.value = '';
        const name = input.getAttribute('name');
        if (name) {
          input.setAttribute('name', name.replace(/sdwd_pricing\[\d+\]/, 'sdwd_pricing[' + newIndex + ']'));
        }
      });

      // Update tier data attribute.
      newTier.dataset.sdwdTier = newIndex;

      // Keep only one empty feature row.
      const featureItems = newTier.querySelector('.sdwd-repeater__items');
      if (featureItems) {
        const featureRows = featureItems.querySelectorAll('.sdwd-repeater__row');
        featureRows.forEach((row, i) => {
          if (i > 0) row.remove();
        });
      }

      // Update add-feature button tier index.
      const featureBtn = newTier.querySelector('[data-sdwd-tier-index]');
      if (featureBtn) featureBtn.dataset.sdwdTierIndex = newIndex;

      container.insertBefore(newTier, addTier);

      // Hide add button if we hit 3.
      if (container.querySelectorAll('.sdwd-pricing-tier').length >= 3) {
        addTier.style.display = 'none';
      }

      return;
    }

    // Remove tier.
    const removeTier = e.target.closest('.sdwd-pricing-tier__remove');
    if (removeTier) {
      const tier = removeTier.closest('.sdwd-pricing-tier');
      const container = tier.closest('.sdwd-pricing-tiers');
      tier.remove();

      // Re-index remaining tiers.
      container.querySelectorAll('.sdwd-pricing-tier').forEach((t, i) => {
        t.dataset.sdwdTier = i;
        const h4 = t.querySelector('h4');
        if (h4) {
          buildTierHeading(h4, i);
        }
        t.querySelectorAll('input').forEach(input => {
          const name = input.getAttribute('name');
          if (name) {
            input.setAttribute('name', name.replace(/sdwd_pricing\[\d+\]/, 'sdwd_pricing[' + i + ']'));
          }
        });
        const featureBtn = t.querySelector('[data-sdwd-tier-index]');
        if (featureBtn) featureBtn.dataset.sdwdTierIndex = i;
      });

      // Show add button again.
      const addBtn = container.querySelector('.sdwd-pricing-tier__add');
      if (addBtn) addBtn.style.display = '';

      return;
    }
  });
});
