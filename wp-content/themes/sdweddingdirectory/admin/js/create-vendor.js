/**
 *  SDWeddingDirectory - Page Setting
 *  -------------------------
 */
(function($) {

    'use strict';

    var SDWeddingDirectory_Setting_Page_Create_Vendor = {

        /**
         *  Admin Created Vendor
         *  --------------------
         */
        admin_create_vendor: function() {

            /**
             *  Create Vendor
             *  -------------
             */
            if ($('form#sdweddingdirectory_vendor_registration_form').length) {

                $('form#sdweddingdirectory_vendor_registration_form').on('submit', function(e) {

                    var form_id = '#' + $(this).attr('id');

                    $.ajax({

                        type: 'POST',

                        dataType: 'json',

                        url: SDWEDDINGDIRECTORY_AJAX_OBJECT.ajaxurl,

                        data: {

                            /**
                             *  Action + Security
                             *  -----------------
                             */
                            'action'                            :   'sdweddingdirectory_vendor_register_form_action',

                            'security'                          :   $(form_id + ' #sdweddingdirectory_vendor_registration_form_security').val(),

                            'admin_created_user'                :   1,

                            'sending_email'                     :   $(form_id + ' #sending_email').val(),

                            /**
                             *  Registration Fields here
                             *  ------------------------
                             */
                            'first_name'                        :   $(form_id + ' #sdweddingdirectory_vendor_register_first_name').val(),

                            'last_name'                         :   $(form_id + ' #sdweddingdirectory_vendor_register_last_name').val(),

                            'username'                          :   $(form_id + ' #sdweddingdirectory_vendor_register_username').val(),

                            'email'                             :   $(form_id + ' #sdweddingdirectory_vendor_register_email').val(),

                            'password'                          :   $(form_id + ' #sdweddingdirectory_vendor_register_password').val(),

                            'company_name'                      :   $(form_id + ' #sdweddingdirectory_vendor_register_company_name').val(),

                            'company_website'                   :   $(form_id + ' #sdweddingdirectory_vendor_register_company_website').val(),

                            'contact_number'                    :   $(form_id + ' #sdweddingdirectory_vendor_register_contact_number').val(),

                            'vendor_category'                   :   $(form_id + ' #sdweddingdirectory_vendor_register_category').val(),
                        },

                        success: function(PHP_RESPONSE) {

                            $(form_id + ' .status').text(PHP_RESPONSE.message);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                            console.log(jqXHR);

                            console.log(textStatus);

                            console.log(errorThrown);
                        },
                    });

                    e.preventDefault();
                });
            }
        },

        /**
         *  Load The Script
         *  ---------------
         */
        init: function() {

            /**
             *  Admin Created Vendor
             *  --------------------
             */
            this.admin_create_vendor();
        }
    }

    $(document).ready(function() { SDWeddingDirectory_Setting_Page_Create_Vendor.init(); });

})(jQuery);