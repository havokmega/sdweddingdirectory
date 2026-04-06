/**
 *  SDWeddingDirectory - Page Setting
 *  -------------------------
 */
(function($) {

    'use strict';

    var SDWeddingDirectory_Setting_Page_Create_Couple = {

        /**
         *  Admin Created Couple
         *  --------------------
         */
        admin_create_couple: function() {

            /**
             *  Create Couple 
             *  -------------
             */
            if ($('form#sdweddingdirectory_couple_registration_form').length) {

                $('form#sdweddingdirectory_couple_registration_form').on('submit', function(e) {

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
                            'action'                    :   'sdweddingdirectory_couple_register_form_action',

                            'security'                  :   $(form_id + ' #sdweddingdirectory_couple_registration_security').val(),

                            'admin_created_user'        :   1,

                            'sending_email'             :   $(form_id + ' #sending_email').val(),

                            /**
                             *  Registration Fields here
                             *  ------------------------
                             */
                            'sdweddingdirectory_couple_register_last_name'      : $(form_id + ' #sdweddingdirectory_couple_register_last_name').val(),

                            'sdweddingdirectory_couple_register_first_name'     : $(form_id + ' #sdweddingdirectory_couple_register_first_name').val(),

                            'sdweddingdirectory_couple_register_username'       : $(form_id + ' #sdweddingdirectory_couple_register_username').val(),

                            'sdweddingdirectory_couple_register_email'          : $(form_id + ' #sdweddingdirectory_couple_register_email').val(),

                            'sdweddingdirectory_couple_register_wedding_date'   : $(form_id + ' #sdweddingdirectory_couple_register_wedding_date').val(),

                            /**
                             *  Password information
                             *  --------------------
                             */
                            'sdweddingdirectory_couple_register_password'       : $(form_id + ' #sdweddingdirectory_couple_register_password').val(),

                            /**
                             *  Register Couple Person [ WHO ]
                             *  ------------------------------
                             */
                            'sdweddingdirectory_register_couple_person'         : $(form_id + ' #sdweddingdirectory_register_couple_person').val(),
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
             *  Admin Created Couple
             *  --------------------
             */
            this.admin_create_couple();
        }
    }

    $(document).ready(function() { SDWeddingDirectory_Setting_Page_Create_Couple.init(); });

})(jQuery);