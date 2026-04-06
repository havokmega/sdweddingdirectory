(function($) {

    "use strict";

    var SDWeddingDirectory_Wishlist = {

        /**
         *  Get the wishlist nonce
         *  ----------------------
         */
        get_security: function(){

            if( typeof SDWEDDINGDIRECTORY_AJAX_OBJ !== 'undefined' && SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_wishlist_security ){

                return SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_wishlist_security;
            }

            return '';
        },

        /**
         *  Sync all save buttons on the page to a given state
         *  --------------------------------------------------
         */
        sync_save_buttons: function( post_id, saved ){

            var icon  = saved ? 'fa fa-heart'       : 'fa fa-heart-o';
            var label = saved ? 'Saved'              : 'Save';

            $( 'a.wishlist-icon[data-post-id="' + post_id + '"], a.sidebar-save-btn[data-post-id="' + post_id + '"]' ).each( function(){

                var btn = $( this );

                btn.toggleClass( 'active', saved ).removeClass( 'disabled' );
                btn.find( 'i' ).attr( 'class', icon );

                if( btn.find( 'span' ).length ){
                    btn.find( 'span' ).text( label );
                } else {
                    btn.contents().filter( function(){ return this.nodeType === 3; } ).first().replaceWith( ' ' + label );
                }
            } );
        },

        /**
         *  Sync all hire buttons on the page to a given state
         *  --------------------------------------------------
         */
        sync_hire_buttons: function( post_id, hired ){

            var icon  = hired ? 'fa fa-check-circle' : 'fa fa-handshake-o';
            var label = hired ? 'Hired'              : 'Hired ?';

            $( 'a.hire-icon[data-post-id="' + post_id + '"], a.sidebar-hire-btn[data-post-id="' + post_id + '"]' ).each( function(){

                var btn = $( this );

                btn.toggleClass( 'active', hired ).removeClass( 'disabled' );
                btn.find( 'i' ).attr( 'class', icon );

                if( btn.find( 'span' ).length ){
                    btn.find( 'span' ).text( label );
                } else {
                    btn.contents().filter( function(){ return this.nodeType === 3; } ).first().replaceWith( ' ' + label );
                }
            } );
        },

        /**
         *  Generic AJAX save/hire handler
         *  ------------------------------
         */
        do_ajax: function( btn, action, post_id, callback ){

            var self = this;

            $.ajax({

                type        :   'POST',
                dataType    :   'json',
                url         :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                data        :   {
                                    'action'    :  action,
                                    'venue_id'  :  post_id,
                                    'security'  :  self.get_security()
                                },

                beforeSend  :   function(){
                                    btn.addClass( 'disabled' ).find( 'i' ).attr( 'class', 'fa fa-spinner fa-spin' );
                                },

                success     :   function( PHP_RESPONSE ){
                                    sdweddingdirectory_alert( PHP_RESPONSE );
                                    callback();
                                },

                error       :   function( xhr, ajaxOptions, thrownError ){
                                    console.log( action + ' error:', xhr.status, thrownError, xhr.responseText );
                                    btn.removeClass( 'disabled' );
                                }
            } );
        },

        /**
         *  1. Save Buttons — nav bar (.wishlist-icon) + sidebar (.sidebar-save-btn)
         *  -------------------------------------------------------------------------
         */
        wishlist_icon_action: function(){

            var self = this;

            $( document ).on( 'click', 'a.wishlist-icon, a.sidebar-save-btn', function( e ){

                e.preventDefault();

                var btn     = $( this ),
                    active  = btn.hasClass( 'active' ),
                    post_id = btn.attr( 'data-post-id' );

                if( btn.hasClass( 'disabled' ) ) return false;

                var action = active ? 'sdweddingdirectory_remove_wishlist' : 'sdweddingdirectory_add_wishlist';

                self.do_ajax( btn, action, post_id, function(){

                    self.sync_save_buttons( post_id, ! active );
                } );

            } );
        },

        /**
         *  2. Hire Buttons — nav bar (.hire-icon) + sidebar (.sidebar-hire-btn)
         *  --------------------------------------------------------------------
         */
        hire_button_action: function(){

            var self = this;

            $( document ).on( 'click', 'a.hire-icon, a.sidebar-hire-btn', function( e ){

                e.preventDefault();

                var btn     = $( this ),
                    active  = btn.hasClass( 'active' ),
                    post_id = btn.attr( 'data-post-id' );

                if( btn.hasClass( 'disabled' ) ) return false;

                var action = active ? 'sdweddingdirectory_remove_hired' : 'sdweddingdirectory_add_hired';

                self.do_ajax( btn, action, post_id, function(){

                    self.sync_hire_buttons( post_id, ! active );
                } );

            } );
        },

        /**
         *  Init
         *  ----
         */
        init: function() {

            this.wishlist_icon_action();

            this.hire_button_action();
        },
    };

    window.SDWeddingDirectory_Wishlist  =   SDWeddingDirectory_Wishlist;

    $(document).ready( function() { SDWeddingDirectory_Wishlist.init(); } );

})(jQuery);
