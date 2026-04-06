(function() {
  'use strict';

  function initSidebarSticky() {
    var sidebar = document.getElementById('sd-profile-sidebar');
    if (!sidebar) {
      return;
    }

    var parent = sidebar.parentElement;
    var isFixed = false;
    var startY = 0;
    var cachedLeft = 0;
    var cachedWidth = 0;

    function getTop() {
      var admin = document.body.classList.contains('admin-bar') ? 32 : 0;
      var navSticky = document.body.classList.contains('sd-profile-nav-is-sticky');
      var navHeight = 0;
      if (navSticky) {
        var nav = document.querySelector('.vendor-nav-sticky.is-fixed');
        if (nav) { navHeight = nav.offsetHeight; }
      }
      var w = window.innerWidth;
      var base = 96;
      if (w <= 1199) { base = 76; }
      else if (w <= 1399) { base = 88; }
      return (navSticky ? navHeight + 16 : base) + admin;
    }

    function unfix() {
      sidebar.style.position = '';
      sidebar.style.top = '';
      sidebar.style.left = '';
      sidebar.style.width = '';
      sidebar.style.zIndex = '';
      isFixed = false;
    }

    function measure() {
      if (isFixed) { unfix(); }
      /* Capture the sidebar's own dimensions while in normal flow —
         this already accounts for the parent column's padding. */
      var sidebarRect = sidebar.getBoundingClientRect();
      cachedLeft = sidebarRect.left + window.pageXOffset;
      cachedWidth = sidebarRect.width;
      startY = sidebarRect.top + window.pageYOffset - getTop();
    }

    function onScroll() {
      if (window.innerWidth <= 991) {
        if (isFixed) { unfix(); }
        return;
      }

      var scrollY = window.pageYOffset || 0;
      var navSticky = document.body.classList.contains('sd-profile-nav-is-sticky');
      var isVenue = document.body.classList.contains('single-venue');

      if (navSticky && !isVenue) {
        if (isFixed) { unfix(); }
        return;
      }

      if (scrollY >= startY) {
        sidebar.style.position = 'fixed';
        sidebar.style.top = getTop() + 'px';
        sidebar.style.left = cachedLeft + 'px';
        sidebar.style.width = cachedWidth + 'px';
        sidebar.style.zIndex = '1007';
        isFixed = true;
      } else if (isFixed) {
        unfix();
      }
    }

    measure();
    onScroll();

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', function() { measure(); onScroll(); });
  }

  function initScrollSpy() {
    var links = Array.prototype.slice.call(document.querySelectorAll('.vendor-nav-link[href^="#section_"]'));

    if (!links.length) {
      return;
    }

    var linkMap = {};
    var sections = [];

    links.forEach(function(link) {
      var selector = link.getAttribute('href');
      var target = selector ? document.querySelector(selector) : null;

      if (target) {
        linkMap[selector] = link;
        sections.push(target);
      }
    });

    if (!sections.length) {
      return;
    }

    var setActive = function(selector) {
      links.forEach(function(link) {
        link.classList.toggle('active', link.getAttribute('href') === selector);
      });
    };

    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          setActive('#' + entry.target.id);
        }
      });
    }, {
      rootMargin: '-35% 0px -55% 0px',
      threshold: 0
    });

    sections.forEach(function(section) {
      observer.observe(section);
    });
  }

  function initSectionNavSticky() {
    var body = document.body;
    var stickyStateClass = 'sd-profile-nav-is-sticky';

    if (!body.classList.contains('single-vendor') && !body.classList.contains('single-venue')) {
      return;
    }

    var nav = document.querySelector('.vendormenu-anim .vendor-nav-sticky');

    if (!nav) {
      return;
    }

    var navParent = nav.parentElement;
    var spacer = document.createElement('div');
    var isFixed = false;
    var startY = 0;

    spacer.className = 'sd-profile-nav-spacer';
    spacer.style.height = '0px';
    navParent.appendChild(spacer);

    function topOffset() {
      return body.classList.contains('admin-bar') ? 32 : 0;
    }

    function resetNav() {
      nav.classList.remove('is-fixed');
      nav.style.left = '';
      nav.style.width = '';
      nav.style.top = '';
      spacer.style.height = '0px';
      isFixed = false;
      body.classList.remove(stickyStateClass);
    }

    function measureStart() {
      var wasFixed = isFixed;

      if (wasFixed) {
        resetNav();
      }

      var rect = nav.getBoundingClientRect();
      startY = rect.top + window.pageYOffset - topOffset();
    }

    function updateFixedGeometry() {
      var parentRect = navParent.getBoundingClientRect();
      nav.style.left = parentRect.left + 'px';
      nav.style.width = parentRect.width + 'px';
      nav.style.top = topOffset() + 'px';
    }

    function onScroll() {
      var currentY = window.pageYOffset || document.documentElement.scrollTop || 0;

      if (currentY >= startY) {
        if (!isFixed) {
          spacer.style.height = nav.offsetHeight + 'px';
          nav.classList.add('is-fixed');
          isFixed = true;
        }
        updateFixedGeometry();
        body.classList.add(stickyStateClass);
      } else if (isFixed) {
        resetNav();
      } else {
        body.classList.remove(stickyStateClass);
      }
    }

    measureStart();
    onScroll();

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', function() {
      measureStart();
      onScroll();
    });
  }

  function initProfileActions() {
    var ajaxUrl = (typeof sdProfileActions !== 'undefined' && sdProfileActions.ajaxUrl)
      ? sdProfileActions.ajaxUrl
      : (typeof SDWEDDINGDIRECTORY_AJAX_OBJ !== 'undefined' ? SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl : '');

    if (!ajaxUrl || typeof window.jQuery === 'undefined') {
      return;
    }

    var $ = window.jQuery;
    var processingClass = 'is-processing';

    $(document).on('click', '.sd-profile-action-btn[data-action-type][data-post-id]', function(e) {
      e.preventDefault();

      var $btn = $(this);
      if ($btn.hasClass(processingClass)) {
        return;
      }

      var actionType = $btn.data('action-type');
      var actionName = actionType === 'hired' ? 'sdwd_profile_toggle_hired' : 'sdwd_profile_toggle_saved';
      var nonce = $btn.data('nonce');
      var postId = parseInt($btn.data('post-id'), 10);

      if (!postId || !nonce) {
        return;
      }

      $btn.addClass(processingClass);

      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajaxUrl,
        data: {
          action: actionName,
          nonce: nonce,
          post_id: postId
        }
      }).done(function(response) {
        if (!response || response.success !== true || !response.data) {
          return;
        }

        var isActive = !!response.data.active;
        var defaultLabel = String($btn.data('label-default') || '');
        var activeLabel = String($btn.data('label-active') || '');
        var nextLabel = isActive ? activeLabel : defaultLabel;

        $btn.toggleClass('is-active', isActive);
        if (actionType === 'saved') {
          var icon = $btn.find('i.fa').first();
          if (icon.length) {
            icon.removeClass('fa-heart fa-heart-o').addClass(isActive ? 'fa-heart' : 'fa-heart-o');
          }
        }

        if (nextLabel !== '') {
          $btn.find('span').first().text(nextLabel);
        }
      }).always(function() {
        $btn.removeClass(processingClass);
      });
    });

    $(document).on('submit', '.sd-profile-message-form', function(e) {
      e.preventDefault();

      var $form = $(this);
      var $submitButton = $form.find('button[type="submit"]');

      if (!$submitButton.length || $submitButton.hasClass(processingClass)) {
        return;
      }

      var nonce = String($form.find('input[name="nonce"]').val() || '');
      var postId = parseInt($form.data('post-id'), 10);
      var postType = String($form.data('post-type') || '');

      if (!nonce || !postId || (postType !== 'vendor' && postType !== 'venue')) {
        return;
      }

      $submitButton.addClass(processingClass).prop('disabled', true);

      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajaxUrl,
        data: {
          action: 'sdwd_profile_send_message',
          nonce: nonce,
          post_id: postId,
          post_type: postType,
          full_name: $form.find('[name="full_name"]').val(),
          email: $form.find('[name="email"]').val(),
          phone: $form.find('[name="phone"]').val(),
          event_date: $form.find('[name="event_date"]').val(),
          message_body: $form.find('[name="message_body"]').val()
        }
      }).done(function(response) {
        if (response && response.success === true) {
          if (typeof window.toastr !== 'undefined') {
            window.toastr.success(response.data && response.data.message ? response.data.message : 'Message sent successfully.');
          }
          $form.find('[name="phone"]').val('');
          $form.find('[name="event_date"]').val('');
        } else if (typeof window.toastr !== 'undefined') {
          window.toastr.error(response && response.data && response.data.message ? response.data.message : 'Unable to send message.');
        }
      }).fail(function() {
        if (typeof window.toastr !== 'undefined') {
          window.toastr.error('Unable to send message right now. Please try again.');
        }
      }).always(function() {
        $submitButton.removeClass(processingClass).prop('disabled', false);
      });
    });
  }

  function initReadMore() {
    var wraps = document.querySelectorAll('.sd-vendor-about-readmore-wrap');
    wraps.forEach(function(wrap) {
      if (wrap.scrollHeight <= 160) {
        wrap.classList.add('is-expanded');
        return;
      }
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'sd-vendor-readmore-btn';
      btn.textContent = 'Read more';
      btn.addEventListener('click', function() {
        var expanded = wrap.classList.toggle('is-expanded');
        btn.textContent = expanded ? 'Read less' : 'Read more';
      });
      wrap.parentNode.insertBefore(btn, wrap.nextSibling);
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    initSidebarSticky();
    initSectionNavSticky();
    initScrollSpy();
    initProfileActions();
    initReadMore();
  });
})();
