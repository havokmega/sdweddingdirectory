document.addEventListener('DOMContentLoaded', () => {
  /* --- Primary navigation --- */
  const navToggle = document.querySelector('[data-nav-toggle]');
  const navPanel = document.querySelector('[data-nav-panel]');
  const desktopQuery = window.matchMedia('(min-width: 1000px)');
  const navItems = navPanel
    ? Array.from(navPanel.querySelectorAll('.header__menu > .nav__item.has-children'))
    : [];

  const getDirectChild = (item, selector) => {
    for (const child of item.children) {
      if (child.matches(selector)) {
        return child;
      }
    }

    return null;
  };

  const getNavTrigger = item => getDirectChild(item, '.nav__link');
  const getNavMenu = item => getDirectChild(item, '.mega-menu') || getDirectChild(item, '.header-nav__submenu');

  const setMenuState = (item, isOpen) => {
    const trigger = getNavTrigger(item);
    const menu = getNavMenu(item);

    item.classList.toggle('is-open', isOpen);

    if (trigger) {
      trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }

    if (!menu) {
      return;
    }

    menu.hidden = !isOpen;
    menu.style.visibility = isOpen ? 'visible' : '';
    menu.style.opacity = isOpen ? '1' : '';
  };

  const closeMenus = exception => {
    navItems.forEach(item => {
      if (item !== exception) {
        setMenuState(item, false);
      }
    });
  };

  const closeNavigation = () => {
    closeMenus();

    if (navPanel) {
      navPanel.classList.remove('is-open');
    }

    if (navToggle) {
      navToggle.classList.remove('is-open');
      navToggle.setAttribute('aria-expanded', 'false');
    }
  };

  const syncNavigationMode = () => {
    closeMenus();

    if (desktopQuery.matches && navPanel) {
      navPanel.classList.remove('is-open');
    }
  };

  navItems.forEach(item => {
    const trigger = getNavTrigger(item);
    let leaveTimer = null;

    setMenuState(item, false);

    item.addEventListener('mouseenter', () => {
      if (!desktopQuery.matches) {
        return;
      }

      if (leaveTimer) {
        clearTimeout(leaveTimer);
        leaveTimer = null;
      }

      closeMenus(item);
      setMenuState(item, true);
    });

    item.addEventListener('mouseleave', () => {
      if (!desktopQuery.matches) {
        return;
      }

      leaveTimer = setTimeout(() => {
        setMenuState(item, false);
        leaveTimer = null;
      }, 120);
    });

    if (trigger) {
      trigger.addEventListener('click', event => {
        if (desktopQuery.matches) {
          return;
        }

        event.preventDefault();

        const shouldOpen = !item.classList.contains('is-open');
        closeMenus(item);
        setMenuState(item, shouldOpen);
      });
    }
  });

  if (navToggle && navPanel) {
    navToggle.addEventListener('click', () => {
      const isOpen = navPanel.classList.toggle('is-open');
      navToggle.classList.toggle('is-open', isOpen);
      navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

      if (!isOpen) {
        closeMenus();
      }
    });
  }

  document.addEventListener('click', event => {
    if (!navPanel || !navPanel.closest('.site-header')) {
      return;
    }

    if (event.target.closest('.site-header')) {
      return;
    }

    closeNavigation();
  });

  document.addEventListener('keydown', event => {
    if (event.key === 'Escape') {
      closeNavigation();
    }
  });

  if (desktopQuery.addEventListener) {
    desktopQuery.addEventListener('change', syncNavigationMode);
  } else {
    desktopQuery.addListener(syncNavigationMode);
  }

  syncNavigationMode();

  /* --- Hero search toggle (venue / vendor) --- */
  const heroToggle = document.querySelectorAll('.hero__toggle-option');
  const heroForm = document.querySelector('.hero__form');

  if (heroToggle.length && heroForm) {
    heroToggle.forEach(label => {
      const radio = label.querySelector('input[type="radio"]');
      if (!radio) return;

      radio.addEventListener('change', () => {
        heroToggle.forEach(l => l.classList.remove('hero__toggle-option--active'));
        label.classList.add('hero__toggle-option--active');

        const venueFields = heroForm.querySelector('.hero__venue-fields');
        const vendorFields = heroForm.querySelector('.hero__vendor-fields');

        if (radio.value === 'vendors') {
          heroForm.action = heroForm.dataset.vendorAction || '/vendors/';
          if (venueFields) venueFields.hidden = true;
          if (vendorFields) vendorFields.hidden = false;
        } else {
          heroForm.action = heroForm.dataset.venueAction || '/venues/';
          if (venueFields) venueFields.hidden = false;
          if (vendorFields) vendorFields.hidden = true;
        }
      });
    });
  }

  /* --- Hero search dropdown panels --- */
  document.querySelectorAll('.hero__dropdown-trigger').forEach(trigger => {
    const field = trigger.closest('.hero__field');
    if (!field) return;

    const panel = field.querySelector('.hero__dropdown-panel');
    const hiddenInput = field.querySelector('input[type="hidden"]');
    const textEl = trigger.querySelector('.hero__dropdown-text');

    if (!panel) return;

    trigger.addEventListener('click', () => {
      const isOpen = trigger.getAttribute('aria-expanded') === 'true';

      // Close all other open panels first
      document.querySelectorAll('.hero__dropdown-trigger[aria-expanded="true"]').forEach(other => {
        if (other !== trigger) {
          other.setAttribute('aria-expanded', 'false');
          const otherPanel = other.closest('.hero__field').querySelector('.hero__dropdown-panel');
          if (otherPanel) otherPanel.hidden = true;
        }
      });

      trigger.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
      panel.hidden = isOpen;
    });

    panel.querySelectorAll('.hero__dropdown-item').forEach(item => {
      item.addEventListener('click', () => {
        // Remove active state from siblings
        panel.querySelectorAll('.hero__dropdown-item').forEach(i => i.classList.remove('hero__dropdown-item--active'));
        item.classList.add('hero__dropdown-item--active');

        // Set hidden input value
        if (hiddenInput) hiddenInput.value = item.dataset.value || '';

        // Update trigger text
        if (textEl) {
          textEl.textContent = item.textContent.trim();
          textEl.classList.add('hero__dropdown-text--selected');
        }

        // Close panel
        trigger.setAttribute('aria-expanded', 'false');
        panel.hidden = true;
      });
    });
  });

  // Close dropdown panels on outside click
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.hero__field--dropdown')) {
      document.querySelectorAll('.hero__dropdown-trigger[aria-expanded="true"]').forEach(trigger => {
        trigger.setAttribute('aria-expanded', 'false');
        const panel = trigger.closest('.hero__field').querySelector('.hero__dropdown-panel');
        if (panel) panel.hidden = true;
      });
    }
  });

  /* --- FAQ Accordion --- */
  document.querySelectorAll('.faq-accordion__question').forEach(btn => {
    btn.addEventListener('click', () => {
      const answer = btn.nextElementSibling;
      const accordion = btn.closest('.faq-accordion');
      const allowMultiple = accordion && accordion.dataset.allowMultiple === 'true';
      const isOpen = btn.getAttribute('aria-expanded') === 'true';

      if (!allowMultiple) {
        accordion.querySelectorAll('.faq-accordion__question').forEach(other => {
          if (other !== btn) {
            other.setAttribute('aria-expanded', 'false');
            const otherAnswer = other.nextElementSibling;
            if (otherAnswer) otherAnswer.setAttribute('data-visible', 'false');
          }
        });
      }

      btn.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
      if (answer) answer.setAttribute('data-visible', isOpen ? 'false' : 'true');
    });
  });

  /* --- FAQ Tabs (page-faqs) --- */
  document.querySelectorAll('.faq-tabs__tab').forEach(tab => {
    tab.addEventListener('click', () => {
      const target = tab.dataset.tab;
      const wrapper = tab.closest('.faq-tabs');
      if (!wrapper) return;

      wrapper.querySelectorAll('.faq-tabs__tab').forEach(t => t.classList.remove('faq-tabs__tab--active'));
      tab.classList.add('faq-tabs__tab--active');

      wrapper.querySelectorAll('.faq-tabs__panel').forEach(panel => {
        panel.hidden = panel.dataset.panel !== target;
      });
    });
  });

  /* --- Blog content splitter (single.php) --- */
  const introImage = document.querySelector('.blog-intro__image img');
  const blogContent = document.querySelector('.blog-content');
  if (introImage && blogContent) {
    const imgHeight = introImage.naturalHeight || introImage.offsetHeight;
    if (imgHeight > 0) {
      blogContent.style.setProperty('--intro-image-height', imgHeight + 'px');
    }
  }


  /* --- Vendor profile: sticky topbar + sidebar state swap --- */
  const vendorProfile = document.querySelector('.vendor-profile');
  const collage = document.getElementById('photo-collage');
  if (vendorProfile && collage && 'IntersectionObserver' in window) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        vendorProfile.classList.toggle('is-scrolled', !entry.isIntersecting);
      });
    }, { threshold: 0, rootMargin: '-60px 0px 0px 0px' });
    io.observe(collage);
  }

  /* --- Vendor profile: quote form submit --- */
  const quoteForm = document.getElementById('sdwd-quote-form');
  if (quoteForm) {
    const ajaxEndpoint = (typeof SDWEDDINGDIRECTORY_AJAX_OBJ !== 'undefined') ? SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl : '/wp-admin/admin-ajax.php';
    const status = quoteForm.querySelector('[data-quote-status]');
    quoteForm.addEventListener('submit', e => {
      e.preventDefault();
      const btn = quoteForm.querySelector('[type="submit"]');
      const origText = btn ? btn.textContent : '';
      if (btn) { btn.disabled = true; btn.textContent = 'Sending...'; }
      if (status) { status.textContent = ''; status.removeAttribute('data-state'); }

      fetch(ajaxEndpoint, { method: 'POST', body: new FormData(quoteForm), credentials: 'same-origin' })
        .then(r => r.json())
        .then(res => {
          if (btn) { btn.disabled = false; btn.textContent = origText; }
          if (status) {
            status.textContent = res.data && res.data.message ? res.data.message : (res.success ? 'Message sent.' : 'Something went wrong.');
            status.setAttribute('data-state', res.success ? 'success' : 'error');
          }
          if (res.success) quoteForm.reset();
        })
        .catch(() => {
          if (btn) { btn.disabled = false; btn.textContent = origText; }
          if (status) { status.textContent = 'Network error. Try again.'; status.setAttribute('data-state', 'error'); }
        });
    });
  }

  /* --- Vendor profile: short-card CTA scrolls to form --- */
  document.querySelectorAll('[data-quote-open]').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = document.getElementById('quote');
      if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  /* --- Sticky profile nav highlight --- */
  const profileNav = document.querySelector('.profile-nav');
  if (profileNav) {
    const sections = document.querySelectorAll('[data-profile-section]');
    const links = profileNav.querySelectorAll('a[href^="#"]');

    window.addEventListener('scroll', () => {
      let current = '';
      sections.forEach(section => {
        const rect = section.getBoundingClientRect();
        if (rect.top <= 120) {
          current = section.dataset.profileSection;
        }
      });
      links.forEach(link => {
        link.classList.toggle('profile-nav__link--active', link.getAttribute('href') === '#' + current);
      });
    });
  }

  /* --- Modal system --- */
  const openModal = id => {
    const overlay = document.getElementById('modal-' + id);
    if (!overlay) return;
    document.querySelectorAll('.modal-overlay.is-open').forEach(o => o.classList.remove('is-open'));
    overlay.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    const firstInput = overlay.querySelector('input:not([type="hidden"]), select, textarea');
    if (firstInput) setTimeout(() => firstInput.focus(), 260);
  };

  const closeModal = overlay => {
    if (!overlay) return;
    overlay.classList.remove('is-open');
    document.body.style.overflow = '';
    const alert = overlay.querySelector('.sdwd-modal__alert');
    if (alert) { alert.classList.remove('is-visible'); alert.textContent = ''; }
  };

  const closeAllModals = () => {
    document.querySelectorAll('.modal-overlay.is-open').forEach(o => closeModal(o));
  };

  const showModalAlert = (overlay, message, type) => {
    const alert = overlay.querySelector('.sdwd-modal__alert');
    if (!alert) return;
    alert.className = 'modal__alert modal__alert--' + type + ' is-visible';
    alert.textContent = message;
  };

  document.addEventListener('click', e => {
    const trigger = e.target.closest('[data-modal-open]');
    if (trigger) {
      e.preventDefault();
      openModal(trigger.dataset.modalOpen);
      return;
    }
    const switchTrigger = e.target.closest('[data-modal-switch]');
    if (switchTrigger) {
      e.preventDefault();
      closeAllModals();
      openModal(switchTrigger.dataset.modalSwitch);
      return;
    }
    if (e.target.closest('[data-modal-close]')) {
      e.preventDefault();
      closeModal(e.target.closest('.modal-overlay'));
      return;
    }
    if (e.target.classList.contains('modal-overlay') && e.target.classList.contains('is-open')) {
      closeModal(e.target);
    }
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeAllModals();
  });

  /* --- Modal AJAX form submission --- */
  const ajaxUrl = (typeof SDWEDDINGDIRECTORY_AJAX_OBJ !== 'undefined') ? SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl : '/wp-admin/admin-ajax.php';

  const submitModalForm = (form, overlay) => {
    const submitBtn = form.querySelector('[type="submit"]');
    const origText = submitBtn ? submitBtn.textContent : '';
    if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Please wait...'; }

    const data = new FormData(form);
    data.append('action', form.dataset.ajaxAction);

    fetch(ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
      .then(r => r.json())
      .then(res => {
        if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = origText; }
        if (res.notice && parseInt(res.notice, 10) === 1) {
          showModalAlert(overlay, res.message || 'Success!', 'success');
          if (res.redirect && res.redirect_link) {
            setTimeout(() => { window.location.href = res.redirect_link; }, 1500);
          } else if (res.modal_close) {
            setTimeout(() => closeModal(overlay), 1500);
          }
        } else {
          showModalAlert(overlay, res.message || 'Something went wrong.', 'error');
        }
      })
      .catch(() => {
        if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = origText; }
        showModalAlert(overlay, 'Network error. Please try again.', 'error');
      });
  };

  document.querySelectorAll('.modal-overlay form[data-ajax-action]').forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();
      submitModalForm(form, form.closest('.modal-overlay'));
    });
  });

  /* --- Vendor/venue account type toggle in registration modal --- */
  const accountTypeRadios = document.querySelectorAll('input[name="account_type"]');
  const categoryField = document.getElementById('vendor-category-field');
  accountTypeRadios.forEach(radio => {
    radio.addEventListener('change', () => {
      if (categoryField) categoryField.style.display = radio.value === 'vendor' ? '' : 'none';
    });
  });

  /* --- Scroll carousels (arrow-based horizontal scroll) --- */
  document.querySelectorAll('[data-scroll-carousel]').forEach(wrapper => {
    const track = wrapper.querySelector('[data-scroll-track]');
    const prevBtn = wrapper.querySelector('[data-scroll-prev]');
    const nextBtn = wrapper.querySelector('[data-scroll-next]');

    if (!track || !prevBtn || !nextBtn) {
      return;
    }

    const getScrollAmount = () => {
      const firstChild = track.firstElementChild;
      if (!firstChild) return 300;
      const style = getComputedStyle(track);
      const gap = parseFloat(style.gap) || 0;
      return firstChild.offsetWidth + gap;
    };

    const updateArrows = () => {
      prevBtn.classList.toggle('hidden', track.scrollLeft <= 1);
      nextBtn.classList.toggle('hidden', track.scrollLeft + track.offsetWidth >= track.scrollWidth - 1);
    };

    prevBtn.addEventListener('click', () => {
      track.scrollBy({ left: -getScrollAmount() * 2, behavior: 'smooth' });
    });

    nextBtn.addEventListener('click', () => {
      track.scrollBy({ left: getScrollAmount() * 2, behavior: 'smooth' });
    });

    track.addEventListener('scroll', updateArrows, { passive: true });
    updateArrows();
  });

  /* --- Infinite looping carousel (single-step) --- */
  document.querySelectorAll('[data-carousel]').forEach(carousel => {
    const track = carousel.querySelector('.home-locations__track');
    if (!track) return;

    const origSlides = Array.from(track.querySelectorAll('.home-locations__slide'));
    if (origSlides.length < 2) return;

    var total = origSlides.length;
    var moving = false;

    // Clone all slides and append before + after originals for seamless loop.
    origSlides.forEach(s => track.appendChild(s.cloneNode(true)));
    origSlides.forEach(s => track.insertBefore(s.cloneNode(true), track.firstChild));

    // Now track has: [clones] [originals] [clones]
    // Start offset at the first original (index = total).
    var pos = total;

    function getStep() {
      var gap = parseFloat(getComputedStyle(track).gap) || 0;
      return track.children[0].offsetWidth + gap;
    }

    function setPos(animated) {
      if (!animated) {
        track.style.transition = 'none';
      }
      track.style.transform = 'translateX(-' + (pos * getStep()) + 'px)';
      if (!animated) {
        track.offsetHeight; // force reflow
        track.style.transition = '';
      }
    }

    function move(dir) {
      if (moving) return;
      moving = true;
      pos += dir;
      setPos(true);
    }

    // After transition ends, silently jump if we've entered clone territory.
    track.addEventListener('transitionend', () => {
      if (pos >= total * 2) {
        pos -= total;
        setPos(false);
      } else if (pos < total) {
        pos += total;
        setPos(false);
      }
      moving = false;
    });

    // Set initial position (no animation).
    setPos(false);

    carousel.querySelectorAll('[data-carousel-prev]').forEach(btn => {
      btn.addEventListener('click', () => move(-1));
    });
    carousel.querySelectorAll('[data-carousel-next]').forEach(btn => {
      btn.addEventListener('click', () => move(1));
    });

    // Intercept anchor-link arrows (home page pattern)
    carousel.querySelectorAll('.home-locations__arrow[href^="#"]').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        move(link.classList.contains('carousel-arrow--prev') ? -1 : 1);
      });
    });

    window.addEventListener('resize', () => setPos(false));
  });
});
