/**
 *  Vendor Set Pricing
 *  ------------------
 */
(function($) {

    "use strict";

    var SDWeddingDirectory_Vendor_Set_Pricing = {

        sync_tier_visibility: function(){

            if( ! $('#sdwd_vendor_set_pricing_form').length ){
                return;
            }

            var tier_count = parseInt( $('#sdwd_tier_count').val(), 10 );

            if( isNaN( tier_count ) || tier_count < 1 ){
                tier_count = 1;
            }

            if( tier_count > 3 ){
                tier_count = 3;
            }

            $('.sdwd-pricing-tier-column').each(function(){

                var tier = parseInt( $(this).attr('data-tier'), 10 );

                if( tier <= tier_count ){
                    $(this).removeClass('sdwd-tier-hidden');
                }else{
                    $(this).addClass('sdwd-tier-hidden');
                }
            });
        },

        sync_feature_icons: function( $row ){

            if( !$row || !$row.length ){
                return;
            }

            var is_included = $row.find('input[type="checkbox"]').is(':checked');

            var $icon = $row.find('.sdwd-feature-icon-preview');

            $icon.removeClass('is-included is-excluded');

            if( is_included ){
                $icon.addClass('is-included');
                $icon.find('i').removeClass('fa-times').addClass('fa-check');
            }else{
                $icon.addClass('is-excluded');
                $icon.find('i').removeClass('fa-check').addClass('fa-times');
            }
        },

        register_events: function(){

            if( ! $('#sdwd_vendor_set_pricing_form').length ){
                return;
            }

            var self = this;

            $(document).on('change', '#sdwd_tier_count', function(){
                self.sync_tier_visibility();
            });

            $(document).on('change', '.sdwd-feature-edit-row input[type="checkbox"]', function(){
                self.sync_feature_icons( $(this).closest('.sdwd-feature-edit-row') );
            });

            $(document).on('submit', '#sdwd_vendor_set_pricing_form', function(e){

                e.preventDefault();

                $('html, body').animate({ scrollTop: 0 }, 'slow');

                var form_data = $(this).serializeArray();

                form_data.push({
                    name: 'action',
                    value: 'sdwd_vendor_pricing_save_action'
                });

                form_data.push({
                    name: 'security',
                    value: $('#sdwd_vendor_set_pricing_nonce').val()
                });

                $.ajax({
                    type: 'POST',
                    url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                    dataType: 'json',
                    data: form_data,
                    success: function(response){
                        sdweddingdirectory_alert(response);
                    },
                    error: function(){
                        sdweddingdirectory_alert({
                            notice: 2,
                            message: 'Unable to save pricing options right now.'
                        });
                    }
                });
            });

            $('.sdwd-feature-edit-row').each(function(){
                self.sync_feature_icons( $(this) );
            });

            self.sync_tier_visibility();
        },

        init: function(){
            this.register_events();
        }
    };

    $(document).ready(function(){
        SDWeddingDirectory_Vendor_Set_Pricing.init();
    });

})(jQuery);
