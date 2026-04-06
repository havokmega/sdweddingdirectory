(function($) {

    "use strict";

    var SDWeddingDirectory_Guest_List_RSVP = {

        /**
         *  RSVP
         *  ----
         */
        rsvp: function(){

            /**
             *  Load Chart
             *  ----------
             */
            if( $( '.sdweddingdirectory_guest_email_update' ).length ){

            	/**
            	 *  Loop
            	 *  ----
            	 */
                $( '.sdweddingdirectory_guest_email_update' ).map( function(){


                	var 	_form 		=	$(this).attr( 'id' ),

                			_form_id 	=	'#' + _form;

                	/**
                	 *  When Click
                	 *  ----------
                	 */ 
                	$( '#submit_' + _form ).on( 'click', function( e ){

                		/**
                		 *  Event Start
                		 *  -----------
                		 */
                		e.preventDefault();

                        var email_id        =       $( '#email_' + _form ).val();

                        /**
                         *  Make sure email id not empty!
                         *  -----------------------------
                         */
                        if( _is_empty( email_id ) ){

                            /**
                             *  Match with Email ID ?
                             *  ---------------------
                             */
                            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;    

                            /**
                             *  Test Email
                             *  ----------
                             */
                            if( ! regex.test( email_id ) ){

                                /**
                                 *  Translation Ready String
                                 *  ------------------------
                                 */
                                sdweddingdirectory_alert( {

                                    'notice'  :  2,

                                    'message' :  SDWEDDINGDIRECTORY_AJAX_OBJ.guest_list.invalid_email

                                } );
                            }

                            /**
                             *  Email Is Perfect
                             *  ----------------
                             */
                            else{

                                var     _button_content     =   $( _form_id ).find( 'button' ).html();

                                /**
                                 *  AJAX
                                 *  ----
                                 */
                                $.ajax({

                                    type        :   'POST',

                                    url         :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                                    dataType    :   'json',

                                    data:       {

                                        /**
                                         *  1. Action & Security
                                         *  --------------------
                                         */
                                        'action'                    :   'sdweddingdirectory_guest_email_update',

                                        'guest_email'               :   $( '#email_' + _form ).val(),

                                        'guest_unique_id'           :   $( '#submit_' + _form ).attr( 'data-guest-id' ),

                                        'security'                  :   SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_guest_list_security
                                    },

                                    beforeSend: function(){

                                        $( _form_id ).find( 'button' ).html( '' );

                                        $( _form_id ).find( 'button' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );
                                    },

                                    success: function ( PHP_RESPONSE ) {

                                        /**
                                         *  Alert
                                         *  -----
                                         */
                                        sdweddingdirectory_alert( PHP_RESPONSE );

                                        /**
                                         *  Removed Disabled Checkbox
                                         *  -------------------------
                                         */
                                        $( '#section_' + _form ).find( 'input[type=checkbox]' ).removeAttr( 'disabled' ).removeClass( 'disabled' );

                                        $( '#toggle_' + _form ).html( PHP_RESPONSE.guest_email );

                                        $( '#target_' + _form ).collapse( 'toggle' );
                                    },

                                    error: function (xhr, ajaxOptions, thrownError) {

                                        console.log( 'SDWeddingDirectory_Guest_List_RSVP / Update Guest Email ID' );

                                        console.log(xhr.status);

                                        console.log(thrownError);

                                        console.log(xhr.responseText);
                                    },

                                    complete: function( event, request, settings ){

                                        $( _form_id ).find( 'button' ).html( '' );

                                        $( _form_id ).find( 'button' ).append( _button_content );

                                        /**
                                         *  Remove Guest
                                         *  ------------
                                         */
                                        SDWeddingDirectory_Guest_List_RSVP.remove_guest();
                                    }

                                } );
                            }
                        }

                        else{

                            /**
                             *  Translation Ready String
                             *  ------------------------
                             */
                            sdweddingdirectory_alert( {

                                'notice'  :  0,

                                'message' :  SDWEDDINGDIRECTORY_AJAX_OBJ.guest_list.empty_email

                            } );
                        }

                	} );

                } );
            }
        },

        /**
         *  Get Checkbox
         *  ------------
         */
        checkbox_checked: function(){

        	/**
        	 *  Checkbox Is Checked ?
        	 *  ---------------------
        	 */
        	if( $( '.guest_rsvp_list input[type=checkbox]' ).length ){

        		$( '.guest_rsvp_list input[type=checkbox]' ).on( 'change', function(){

	        		/**
	        		 *  Read Guest
	        		 *  ----------
	        		 */
	        		SDWeddingDirectory_Guest_List_RSVP.read_checkbox();

        		} );
        	}
        },

        /**
         *  Read Checkbox
         *  -------------
         */
        read_checkbox: function(){

        	/**
        	 *  Update in To
        	 *  ------------
        	 */
    		$( 'ul.guest-rsvp-list.list-unstyled' ).html( 

    			$( '.guest_rsvp_list input[type=checkbox]:checked' ).map( function(){

    			var name 	=	$(this).attr( 'data-name' ),

    				id 		=	$(this).val();

    			return 		'<li class="tag">' +

                                '<span>'+ name +'</span>' +

                                '<a href="javascript:" class="del-guest text-dark" data-id="'+ id +'">' +

                                    '<i class="fa fa-close"></i>' +

                                '</a>' +

                            '</li>';

    		} ).get().join( ' ' ) );

    		/**
    		 *  Remove Guest
    		 *  ------------
    		 */
    		SDWeddingDirectory_Guest_List_RSVP.remove_guest();
        },

        /**
         *  Remove Guest
         *  ------------
         */
        remove_guest: function(){

        	/**
        	 *  Have Tag ?
        	 *  ----------
        	 */
        	if( $( 'a.del-guest' ).length ){

        		/**
        		 *  Each Tag Target
        		 *  ---------------
        		 */
        		$( 'a.del-guest' ).map( function(){

        			/**
        			 *  When Click
        			 *  ----------
        			 */
	        		$(this).on( 'click', function( e ){

	        			e.preventDefault();

		        		var input_id 	=	'#guest_' + $(this).attr( 'data-id' );

		        		/**
		        		 *  Is Checked ?
		        		 *  ------------
		        		 */
		        		if( $( input_id ).checked ){

		        			$( input_id ).prop( 'checked', true );
		        		}

		        		/**
		        		 *  If Not Checked ?
		        		 *  ----------------
		        		 */
		        		else{

		        			$( input_id ).prop( 'checked', false );
		        		}

		        		/**
		        		 *  Remove Tag
		        		 *  ----------
		        		 */
		        		$(this).closest( 'li' ).remove();

	        		} );

        		} );
        	}
        },

        /**
         *  Select All Guest
         *  ----------------
         */
        select_all_guest: function(){

        	/**
        	 *  Select All
        	 *  ----------
        	 */
        	if( $( '#select-all' ).length ){

        		/**
        		 *  Event Check
        		 *  -----------
        		 */
				$( '#select-all' ).change( function(){

					/**
					 *  Condition
					 *  ---------
					 */
					$('.guest_rsvp_list input:checkbox:not(.disabled)').not(this).prop('checked', this.checked);

	        		/**
	        		 *  Read Guest
	        		 *  ----------
	        		 */
	        		SDWeddingDirectory_Guest_List_RSVP.read_checkbox();

				});
        	}
        },

        /**
         *  Search Guest
         *  ------------
         */
        search_guest: function(){

        	/**
        	 *  Have Guest ?
        	 *  ------------
        	 */
        	if( $( '.search-guest' ).length ){

				$( '.search-guest' ).on( 'keyup', function() {

				    var value 	= 	$(this).val().toLowerCase();

				    $( '.guest_rsvp_list .guest-info' ).filter(function() {

				      	$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)

				    });

				});
        	}
        },

        /**
         *  Start Sending RSVP
         *  ------------------
         */
        sending_email_rsvp: function(){

            /**
             *  Form Exits ?
             *  ------------
             */
            if( $( '#submit_rsvp_to_guest' ).length ){

                /**
                 *  When Submit
                 *  -----------
                 */
                $( '#submit_rsvp_to_guest' ).on( 'submit', function( e ){

                    var     _form               =   $(this).attr( 'id' ),

                            _form_id            =   '#' + _form,

                            _button_class       =   $( '#couple_send_rsvp' ).find( 'i' ).attr( 'class' );

                    /**
                     *  Wait for event
                     *  --------------
                     */
                    e.preventDefault();

                    /**
                     *  Handler
                     *  -------
                     */
                    var guest_ids   =   new Array();

                    /**
                     *  Collection of Guest IDs
                     *  -----------------------
                     */
                    $( 'ul.guest-rsvp-list li a' ).map( function( i ){

                        guest_ids[i] =  $(this).attr( 'data-id' );

                    } );

                    /**
                     *  AJAX
                     *  ----
                     */
                    $.ajax({

                        type        :   'POST',

                        url         :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        dataType    :   'json',

                        data:       {

                            /**
                             *  1. Action & Security
                             *  --------------------
                             */
                            'action'            :  'sdweddingdirectory_couple_sending_guest_rsvp',

                            'security'          :  $( '#sdweddingdirectory_rsvp_email_security' ).val(),

                            'rsvp_content'      :  $( '#rsvp-message' ).val(),

                            'rsvp_image'        :  $( 'img#rsvp_image' ).attr( 'src' ),

                            'enable_website'    :  $( '#enable_website' ).prop('checked') ? 1 : 0,

                            'guest_ids'         :  guest_ids,
                        },

                        beforeSend: function(){

                            $( '#couple_send_rsvp' ).find( 'i' ).attr( 'class', 'ms-2 fa fa-spinner fa-spin' );
                        },

                        success: function ( PHP_RESPONSE ) {

                            /**
                             *  Alert
                             *  -----
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );
                        },

                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Guest_List_RSVP / Update Guest Email ID' );

                            console.log(xhr.status);

                            console.log(thrownError);

                            console.log(xhr.responseText);
                        },

                        complete: function( event, request, settings ){

                            /**
                             *  Done
                             *  ----
                             */
                            $( '#couple_send_rsvp' ).find( 'i' ).attr( 'class', _button_class );

                            /**
                             *  Remove Guest
                             *  ------------
                             */
                            SDWeddingDirectory_Guest_List_RSVP.remove_guest();
                        }

                    } );

                } );
            }
        },

        /**
         *  Load Object
         *  -----------
         */
        init: function(){

            /**
             *  RSVP
             *  ----
             */
            this.rsvp();

            /**
             *  Checkbox Checked
             *  ----------------
             */
            this.checkbox_checked();

            /**
             *  Removed Guest
             *  -------------
             */
            this.remove_guest();

            /**
             *  Select All Guest
             *  ----------------
             */
            this.select_all_guest();

            /**
             *  Search Guest
             *  ------------
             */
            this.search_guest();

            /**
             *  Start Sending RSVP
             *  ------------------
             */
            this.sending_email_rsvp();
        },
    };

    $(document).ready( function() { SDWeddingDirectory_Guest_List_RSVP.init(); });

})(jQuery);
