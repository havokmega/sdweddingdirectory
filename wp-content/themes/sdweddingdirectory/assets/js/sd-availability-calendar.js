(function() {
  'use strict';

  function pad(number) {
    return number < 10 ? '0' + number : String(number);
  }

  function formatDate(year, month, day) {
    return year + '-' + pad(month + 1) + '-' + pad(day);
  }

  function monthName(year, month) {
    var date = new Date(year, month, 1);
    return date.toLocaleDateString(undefined, { month: 'long', year: 'numeric' });
  }

  function createMonthTable(year, month, bookedDates, availableDates) {
    var firstDay = new Date(year, month, 1);
    var firstWeekday = firstDay.getDay();
    var totalDays = new Date(year, month + 1, 0).getDate();
    var today = new Date();

    var html = '';
    html += '<div class="sd-cal-month-title">' + monthName(year, month) + '</div>';
    html += '<table class="sd-cal-month-table"><thead><tr>';
    html += '<th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th>';
    html += '</tr></thead><tbody>';

    var day = 1;

    for (var row = 0; row < 6; row++) {
      html += '<tr>';

      for (var col = 0; col < 7; col++) {
        if ((row === 0 && col < firstWeekday) || day > totalDays) {
          html += '<td class="sd-cal-day sd-cal-day-empty"></td>';
        } else {
          var key = formatDate(year, month, day);
          var classes = ['sd-cal-day'];
          var dot = '';

          if (
            today.getFullYear() === year &&
            today.getMonth() === month &&
            today.getDate() === day
          ) {
            classes.push('sd-cal-today');
          }

          if (bookedDates.indexOf(key) !== -1) {
            classes.push('sd-cal-booked');
            dot = '<span class="sd-cal-dot sd-cal-dot-booked"></span>';
          } else if (availableDates.indexOf(key) !== -1) {
            classes.push('sd-cal-available');
            dot = '<span class="sd-cal-dot sd-cal-dot-available"></span>';
          }

          html += '<td class="' + classes.join(' ') + '"><div>' + day + '</div>' + dot + '</td>';
          day++;
        }
      }

      html += '</tr>';

      if (day > totalDays) {
        break;
      }
    }

    html += '</tbody></table>';

    return html;
  }

  function fetchAvailability(vendorId) {
    var ajaxUrl = (window.sdAvailability && sdAvailability.ajaxUrl) || '';

    if (!ajaxUrl || !vendorId) {
      return Promise.resolve({ booked_dates: [], available_dates: [] });
    }

    var url = ajaxUrl + '?action=sd_get_vendor_availability&vendor_id=' + encodeURIComponent(vendorId);

    return fetch(url, { credentials: 'same-origin' })
      .then(function(response) { return response.json(); })
      .then(function(data) {
        if (!data || !data.success || !data.data) {
          return { booked_dates: [], available_dates: [] };
        }

        return {
          booked_dates: Array.isArray(data.data.booked_dates) ? data.data.booked_dates : [],
          available_dates: Array.isArray(data.data.available_dates) ? data.data.available_dates : []
        };
      })
      .catch(function() {
        return { booked_dates: [], available_dates: [] };
      });
  }

  /**
   *  Get the month pair for a given offset from state
   */
  function getMonthPair(state, offset) {
    var m = state.month + offset;
    var y = state.year;

    while (m > 11) { m -= 12; y++; }
    while (m < 0)  { m += 12; y--; }

    var m2 = m + 1;
    var y2 = y;

    if (m2 > 11) { m2 = 0; y2++; }

    return { leftMonth: m, leftYear: y, rightMonth: m2, rightYear: y2 };
  }

  /**
   *  Fill a slide element with two months of content
   */
  function fillSlide(slide, pair, data) {
    var left  = slide.querySelector('.sd-cal-month:first-child');
    var right = slide.querySelector('.sd-cal-month:last-child');

    if (left)  left.innerHTML  = createMonthTable(pair.leftYear, pair.leftMonth, data.booked_dates, data.available_dates);
    if (right) right.innerHTML = createMonthTable(pair.rightYear, pair.rightMonth, data.booked_dates, data.available_dates);
  }

  /**
   *  Build the year labels (prev, current, next)
   */
  function renderYearLabels(calendar, state) {
    var container = calendar.querySelector('.sd-cal-year-labels');

    if (!container) return;

    var html = '';
    for (var offset = -1; offset <= 1; offset++) {
      var yr = state.year + offset;
      var cls = 'sd-cal-year-label';

      if (offset === 0) cls += ' sd-cal-year-active';

      html += '<span class="' + cls + '" data-year="' + yr + '">' + yr + '</span>';
    }

    container.innerHTML = html;
  }

  /**
   *  Render the calendar (no animation)
   */
  function renderCalendar(calendar, state, data) {
    var activeSlide = calendar.querySelector('.sd-cal-slide-active');
    var prevSlide   = calendar.querySelector('.sd-cal-slide-prev');
    var nextSlide   = calendar.querySelector('.sd-cal-slide-next');

    if (!activeSlide) return;

    var activePair = getMonthPair(state, 0);
    fillSlide(activeSlide, activePair, data);

    if (prevSlide) {
      fillSlide(prevSlide, getMonthPair(state, -1), data);
    }

    if (nextSlide) {
      fillSlide(nextSlide, getMonthPair(state, 1), data);
    }

    renderYearLabels(calendar, state);

    // Reset slider position
    var slider = calendar.querySelector('.sd-cal-slider');
    if (slider) {
      slider.classList.remove('sd-cal-slide-left', 'sd-cal-slide-right');
      slider.style.transition = 'none';
      slider.style.transform = 'translateX(0)';
    }
  }

  /**
   *  Animate the slide transition
   *  direction: 'left' (next) or 'right' (prev)
   */
  function animateSlide(calendar, state, data, direction, callback) {
    var slider = calendar.querySelector('.sd-cal-slider');

    if (!slider) {
      callback();
      return;
    }

    var prevSlide = calendar.querySelector('.sd-cal-slide-prev');
    var nextSlide = calendar.querySelector('.sd-cal-slide-next');

    // Pre-fill the incoming slide with the NEW months (after state change)
    if (direction === 'left' && nextSlide) {
      fillSlide(nextSlide, getMonthPair(state, 1), data);
    } else if (direction === 'right' && prevSlide) {
      fillSlide(prevSlide, getMonthPair(state, -1), data);
    }

    // Force reflow so the non-transitioned position is painted
    slider.style.transition = 'none';
    slider.style.transform = 'translateX(0)';
    slider.offsetHeight; // force reflow

    // Start the transition
    slider.style.transition = 'transform 0.45s ease-in-out';
    slider.style.transform = direction === 'left' ? 'translateX(-33.333%)' : 'translateX(33.333%)';

    var done = false;

    function finish() {
      if (done) return;
      done = true;

      slider.style.transition = 'none';
      slider.style.transform = 'translateX(0)';

      // Now update all three slides with the correct content for the new state
      renderCalendar(calendar, state, data);

      if (callback) callback();
    }

    slider.addEventListener('transitionend', function handler() {
      slider.removeEventListener('transitionend', handler);
      finish();
    });

    // Fallback in case transitionend doesn't fire
    setTimeout(finish, 500);
  }

  function initCalendar(calendar) {
    var vendorId = parseInt(calendar.getAttribute('data-vendor-id'), 10) || 0;
    var today = new Date();
    var animating = false;

    var state = {
      month: today.getMonth(),
      year: today.getFullYear()
    };

    fetchAvailability(vendorId).then(function(data) {
      renderCalendar(calendar, state, data);

      var prev = calendar.querySelector('.sd-cal-prev');
      var next = calendar.querySelector('.sd-cal-next');

      if (prev) {
        prev.addEventListener('click', function() {
          if (animating) return;
          animating = true;

          // Pre-fill the prev slide with what will become the active months
          var prevSlide = calendar.querySelector('.sd-cal-slide-prev');
          var newMonth = state.month - 1;
          var newYear = state.year;

          if (newMonth < 0) { newMonth = 11; newYear--; }

          var previewState = { month: newMonth, year: newYear };

          if (prevSlide) {
            fillSlide(prevSlide, getMonthPair(previewState, 0), data);
          }

          // Force reflow
          var slider = calendar.querySelector('.sd-cal-slider');
          slider.style.transition = 'none';
          slider.style.transform = 'translateX(0)';
          slider.offsetHeight;

          // Animate
          slider.style.transition = 'transform 0.45s ease-in-out';
          slider.style.transform = 'translateX(33.333%)';

          var done = false;

          function finish() {
            if (done) return;
            done = true;

            state.month = newMonth;
            state.year = newYear;

            renderCalendar(calendar, state, data);
            animating = false;
          }

          slider.addEventListener('transitionend', function handler() {
            slider.removeEventListener('transitionend', handler);
            finish();
          });

          setTimeout(finish, 500);
        });
      }

      if (next) {
        next.addEventListener('click', function() {
          if (animating) return;
          animating = true;

          // Pre-fill the next slide with what will become the active months
          var nextSlide = calendar.querySelector('.sd-cal-slide-next');
          var newMonth = state.month + 1;
          var newYear = state.year;

          if (newMonth > 11) { newMonth = 0; newYear++; }

          var previewState = { month: newMonth, year: newYear };

          if (nextSlide) {
            fillSlide(nextSlide, getMonthPair(previewState, 0), data);
          }

          // Force reflow
          var slider = calendar.querySelector('.sd-cal-slider');
          slider.style.transition = 'none';
          slider.style.transform = 'translateX(0)';
          slider.offsetHeight;

          // Animate
          slider.style.transition = 'transform 0.45s ease-in-out';
          slider.style.transform = 'translateX(-33.333%)';

          var done = false;

          function finish() {
            if (done) return;
            done = true;

            state.month = newMonth;
            state.year = newYear;

            renderCalendar(calendar, state, data);
            animating = false;
          }

          slider.addEventListener('transitionend', function handler() {
            slider.removeEventListener('transitionend', handler);
            finish();
          });

          setTimeout(finish, 500);
        });
      }

      // Year label clicks
      calendar.addEventListener('click', function(e) {
        var label = e.target.closest('.sd-cal-year-label');

        if (!label || animating) return;

        var clickedYear = parseInt(label.getAttribute('data-year'), 10);

        if (clickedYear === state.year) return;

        state.year = clickedYear;
        renderCalendar(calendar, state, data);
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    var calendars = document.querySelectorAll('.sd-availability-calendar[data-vendor-id]');

    calendars.forEach(function(calendar) {
      initCalendar(calendar);
    });
  });
})();
