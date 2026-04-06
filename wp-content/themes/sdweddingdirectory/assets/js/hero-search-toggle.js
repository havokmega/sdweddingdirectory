(function($) {
    'use strict';

    $(document).ready(function() {
        var $toggle = $('#sd-search-toggle');

        if (!$toggle.length) {
            return;
        }

        var $form = $toggle.closest('form.sdweddingdirectory-result-page').first();

        if (!$form.length) {
            var $container = $toggle.closest('.col-xl-8, .col-lg-12, .col-12').first();
            $form = $container.find('form.sdweddingdirectory-result-page').first();
        }

        if (!$form.length) {
            return;
        }

        var $venueFields = $form.find('.sd-venue-search-fields');
        var $vendorFields = $form.find('.sd-vendor-search-fields');

        var clearQueryInputs = function() {
            $form.find('input.get_query').val('');
        };

        var resetFieldState = function($scope) {
            $scope.find('input[type="text"]').each(function() {
                $(this)
                    .val('')
                    .attr('data-value-id', '')
                    .attr('data-last-value', '')
                    .attr('data-last-data', '');
            });
        };

        var applyMode = function(mode) {
            if (mode === 'vendors') {
                $venueFields.hide();
                $vendorFields.show();

                if ($form.data('vendor-action')) {
                    $form.attr('action', $form.data('vendor-action'));
                }

                clearQueryInputs();
                resetFieldState($venueFields);
            } else {
                $vendorFields.hide();
                $venueFields.show();

                if ($form.data('venue-action')) {
                    $form.attr('action', $form.data('venue-action'));
                }

                resetFieldState($vendorFields);
            }
        };

        // If location is selected, carry it to venue-type archive links.
        $(document).on('click', '.sd-venue-search-fields a.search-item-redirect', function() {
            var $link = $(this);
            var href = $link.attr('href') || '';

            if (href.indexOf('/venue-types/') === -1) {
                return;
            }

            var selectedLocation = $.trim($form.find('input.get_query[name="location"]').val() || '');
            if (!selectedLocation) {
                return;
            }

            try {
                var url = new URL(href, window.location.origin);
                url.searchParams.set('location', selectedLocation);
                $link.attr('href', url.toString());
            } catch (e) {
                // Keep original href if URL parsing fails.
            }
        });

        $toggle.on('change click', 'input[name="sd_search_mode"]', function() {
            applyMode($(this).val());
        });

        var initialMode = $toggle.find('input[name="sd_search_mode"]:checked').val() || 'venues';
        applyMode(initialMode);
    });
})(jQuery);
