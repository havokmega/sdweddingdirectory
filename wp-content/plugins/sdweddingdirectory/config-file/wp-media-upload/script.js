/**
 *  One of example : https://github.com/10quality/wp-media-uploader
 *  ---------------------------------------------------------------
 *  We are not used above link code it's custom
 *  -------------------------------------------
 */
(function($) {

    "use strict";

    var SDWeddingDirectory_Media_Uploader = {

        /**
         *  1. Upload Single Media
         *  ----------------------
         */
        single_upload_media: function(){

            /**
             *  Single Image Upload
             *  -------------------
             */
            $( document ).on( 'click', '.upload_single_media', function( event ) {

                /**
                 *  Event
                 *  -----
                 */
                event.preventDefault();

                /**
                 *  Variable
                 *  --------
                 */
                var     file_frame,
                        
                        button_id                   =       '#'     +   $(this).attr( 'id' ),

                        img_preview_id              =       $( this ).attr( 'data-preview' ),

                        img_size                    =       $( this ).attr( 'data-media-size' ),

                        media_update_key            =       $( this ).attr( 'data-key' ),

                        is_ajax                     =       $( this ).attr( 'data-ajax-save' ),

                        post_id                     =       $( this ).attr( 'data-post-id' ),

                        img_height                  =       $( this ).attr( 'data-height' ),

                        img_width                   =       $( this ).attr( 'data-width' ),

                        allowed_type                =       $( this ).attr( 'data-allowed-type' ),

                        store_media_ids             =       $( this ).attr( 'data-value-update-text-id' ),

                        frame_title                 =       $( this ).attr( 'data-frame-name' ),

                        frame_button_name           =       $( this ).attr( 'data-frame-button' ),

                        extra_link_update_fiels     =       $( this ).attr( 'data-extra-link-update' );

                /**
                 *  Frame Open ?
                 *  ------------
                 *  if the file_frame has already been created, just reuse it
                 *  ---------------------------------------------------------
                 */
                if ( file_frame ) {

                    file_frame.open();

                    return;
                }

                /**
                 *   Frame
                 *   -----
                 */
                file_frame      =       wp.media.frames.file_frame = wp.media( {

                                            title           :       frame_title,

                                            button          :       {   text: frame_button_name,  },

                                            multiple        :       false,

                                            library         :       { type: allowed_type },

                                        } );

                /**
                 *  Selected Media Will Selected when Open
                 *  --------------------------------------
                 *  @credit -  https://wordpress.stackexchange.com/questions/235406/how-do-i-select-an-image-from-media-library-in-my-plugin#answer-236296
                 *  --------------------------------------------------------------------------------------------------------------------------------------
                 */
                if( false ){

                    file_frame.on( 'open',function() {

                        var     selection       =       file_frame.state().get( 'selection' );

                        /**
                         *  Make sure have value 
                         *  --------------------
                         */
                        if( _is_empty( $( '#' + store_media_ids ).val() ) ){

                            var     ids             =       $( '#' + store_media_ids ).val().split( ',' );

                            ids.forEach( function( id ){
                                
                                var     attachment      =   wp.media.attachment( id );

                                        attachment.fetch();

                                        selection.add( attachment ? [ attachment ] : [] );
                            } );
                        }

                    } );
                }

                /**
                 *  Select
                 *  ------
                 */
                file_frame.on( 'select', function(){

                    var     selection       =       file_frame.state().get('selection'),

                            attachment_ids  =       selection.map( function( attachment ) {

                                                        attachment = attachment.toJSON();

                                                        return attachment.id;

                                                    }).join();

                    
                    /**
                     *  Have Attachments ?
                     *  ------------------
                     */
                    if( attachment_ids.charAt(0) === ',' ) { 

                        attachment_ids      =       attachment_ids.substring(1); 
                    }

                    /**
                     * AJAX is "TRUE" after direct update post ids with thumb ids
                     * ----------------------------------------------------------
                     */
                    if( is_ajax == 'true' || is_ajax == true ){

                        /**
                         *  Update Media in Database
                         *  ------------------------
                         */
                        SDWeddingDirectory_Media_Uploader.upate_media_in_database( 

                            media_update_key, 

                            post_id, 

                            attachment_ids 
                        );
                    }

                    /**
                     *  Get Image links
                     *  ---------------
                     */
                    var attachment_thumbs = selection.map( function( attachment ) {

                        attachment      =       attachment.toJSON();

                        /**
                         *  Only Allowed Type Data Collect
                         *  ------------------------------
                         */
                        if( allowed_type == attachment.type ){

                            /**
                             *  Make sure image crop list found
                             *  -------------------------------
                             */
                            if( img_size in attachment.sizes ){

                                /**
                                 *  Make sure size wise image uploaded
                                 *  ----------------------------------
                                 */
                                if( attachment.sizes[ img_size ].height >= img_height && attachment.sizes[ img_size ].width >=  img_width ){

                                    return      attachment.sizes[img_size].url;
                                }

                                else{

                                    return      attachment.sizes.full.url;
                                }
                            }

                            else{

                                return      attachment.sizes.full.url;
                            }
                        }

                    }).join(' ');


                    /**
                     *  Image Preview Ids showing image
                     *  -------------------------------
                     */
                    if( $( '#' + img_preview_id ).length ){

                        $( '#' + img_preview_id ).attr( 'src', attachment_thumbs );
                    }

                    /**
                     *  Media ids updated in text fields
                     *  --------------------------------
                     */
                    if( $( '#' + store_media_ids ).length ){

                        $( '#' + store_media_ids ).attr( 'value', attachment_ids );
                    }


                    /**
                     *  Make sure have extra replace fiels
                     *  ----------------------------------
                     */
                    if( _is_empty( extra_link_update_fiels ) ){

                        const JSONobject = $.parseJSON( extra_link_update_fiels );

                        for( let i in JSONobject ) { 

                            $( i ).attr( JSONobject[i], attachment_thumbs );
                        };
                    }
                });

                file_frame.open();
            });
        },

        /**
         *  2. Upload Gallery Media
         *  -----------------------
         */
        multi_upload_media: function(){

            /**
             *  Multiple Gallery Upload
             *  -----------------------
             */
            $( document ).on( 'click', '.upload_multi_media', function( event ) {

                /**
                 *  Event Start
                 *  -----------
                 */
                event.preventDefault();

                /**
                 *  Variable
                 *  --------
                 */
                var     file_frame,

                        button_id               =       '#'     +   $(this).attr( 'id' ),

                        img_preview_id          =       $( this ).attr( 'data-preview' ),

                        img_size                =       $( this ).attr( 'data-media-size' ),

                        media_update_key        =       $( this ).attr( 'data-key' ),

                        is_ajax                 =       $( this ).attr( 'data-ajax-save' ),

                        post_id                 =       $( this ).attr( 'data-post-id' ),

                        allowed_type            =       $( this ).attr( 'data-allowed-type' ),

                        store_media_ids         =       $( this ).attr( 'data-value-update-text-id' ),

                        frame_title             =       $( this ).attr( 'data-frame-name' ),

                        frame_button_name       =       $( this ).attr( 'data-frame-button' );

                /**
                 *  Frame Open ?
                 *  ------------
                 *  if the file_frame has already been created, just reuse it
                 *  ---------------------------------------------------------
                 */
                if ( file_frame ) {

                    file_frame.open();

                    return;
                }

                /**
                 *  File Name
                 *  ---------
                 */
                file_frame          =       wp.media.frames.file_frame = wp.media({

                                                title           :       frame_title,

                                                button          :       {   text: frame_button_name,  },

                                                multiple        :       true,

                                                library         :       { type: allowed_type },

                                            } );

                /**
                 *  Selected Media Will Selected when Open
                 *  --------------------------------------
                 *  @credit -  https://wordpress.stackexchange.com/questions/235406/how-do-i-select-an-image-from-media-library-in-my-plugin#answer-236296
                 *  --------------------------------------------------------------------------------------------------------------------------------------
                 */
                if( false ){

                    file_frame.on( 'open',function() {

                        var     selection       =       file_frame.state().get( 'selection' );

                        /**
                         *  Make sure have value 
                         *  --------------------
                         */
                        if( _is_empty( $( '#' + store_media_ids ).val() ) ){

                            var     ids             =       $( '#' + store_media_ids ).val().split( ',' );

                            ids.forEach( function( id ){
                                
                                var     attachment      =   wp.media.attachment( id );

                                        attachment.fetch();

                                        selection.add( attachment ? [ attachment ] : [] );
                            } );
                        }

                    } );
                }


                /**
                 *  Selected 
                 *  --------
                 */
                file_frame.on( 'select', function() {

                    /**
                     *  Handler
                     *  -------
                     */
                    var     selection       =       file_frame.state().get( 'selection' ),
                    
                            /**
                             *  Media Ids
                             *  ---------
                             */
                            attachment_ids  =       selection.map( function( attachment ){

                                                        return      attachment.toJSON().id;

                                                    } ).join();

                            /**
                             *  Make sure have value 
                             *  --------------------
                             */
                            if( _is_empty( $( '#' + store_media_ids ).val() ) ){

                                attachment_ids      =     $( '#' + store_media_ids ).val().split( ',' ) + ',' + attachment_ids;
                            }

                            /**
                             *  Attachment IDs
                             *  --------------
                             */
                            if( attachment_ids.charAt(0) === ',' ) {

                                attachment_ids = attachment_ids.substring(1); 
                            }

                    /**
                     * AJAX is "TRUE" after direct update post ids with thumb ids
                     * ----------------------------------------------------------
                     */
                    if( is_ajax == 'true' || is_ajax == true ){

                        /**
                         *  Update Media in Database
                         *  ------------------------
                         */
                        SDWeddingDirectory_Media_Uploader.upate_media_in_database( 

                            media_update_key, 

                            post_id, 

                            attachment_ids 
                        );
                    }

                    /**
                     *  Get Image ( Thumbnails )
                     *  ------------------------
                     */
                    var attachment_thumbs = selection.map( function( attachment ) {

                        /**
                         *  Attachments
                         *  -----------
                         */
                        attachment      =       attachment.toJSON();

                        /**
                         *  Only Allowed Type Data Collect
                         *  ------------------------------
                         */
                        if( allowed_type == attachment.type ){

                            /**
                             *  Found Size ?
                             *  ------------
                             */
                            if( img_size in attachment.sizes ){

                                return      SDWeddingDirectory_Media_Uploader.image_format(

                                                attachment.sizes[img_size].url,

                                                attachment.id
                                            );
                            }

                            /**
                             *  Not Found Image Size
                             *  --------------------
                             */
                            else {

                                /**
                                 *  Have Thumbnails Size ?
                                 *  ----------------------
                                 */
                                if( attachment.sizes.hasOwnProperty( 'thumbnail' ) ){

                                    return      SDWeddingDirectory_Media_Uploader.image_format(

                                                    attachment.sizes.thumbnail.url,

                                                    attachment.id
                                                );
                                }

                                /**
                                 *  Return Full Image
                                 *  -----------------
                                 */
                                else{

                                    return      SDWeddingDirectory_Media_Uploader.image_format(

                                                    attachment.sizes.full.url,

                                                    attachment.id
                                                );
                                }
                            }
                        }

                    } ).join(' ');

                    /**
                     *  Load HTML in Preview ID
                     *  -----------------------
                     */
                    if( $( '#' + img_preview_id ).length ){

                        $( '#' + img_preview_id ).append( attachment_thumbs );
                    }

                    /**
                     *  Update Media Ids in input fields
                     *  --------------------------------
                     */
                    if( '#' + store_media_ids !== '' ){

                        $( '#' + store_media_ids ).attr( 'value', attachment_ids );
                    }

                } );

                /**
                 *  Frame Open
                 *  ----------
                 */
                file_frame.open();

            } );
        },

        /**
         *  5. Multiple Upload PDF Fields used script
         *  -----------------------------------------
         */
        upload_multiple_pdf_fields: function() {

            /**
             *  Button Event
             *  ------------
             */
            $( document ).on( 'click', '.upload-multiple-pdf-file', function( event ) {

                /**
                 *  Event Start
                 *  -----------
                 */
                event.preventDefault();

                /**
                 *  Variable
                 *  --------
                 */
                var     file_frame,

                        button_id               =       '#'     +   $(this).attr( 'id' ),

                        file_id                 =       $( this ).attr( 'data-file-id' ),

                        file_name               =       $( this ).attr( 'data-file-name' ),

                        frame_title             =       $( this ).attr( 'data-frame-name' ),

                        frame_button_name       =       $( this ).attr( 'data-frame-button' ),

                        allowed_type            =       $( this ).attr( 'data-allowed-type' );

                /**
                 *  if the file_frame has already been created, just reuse it
                 *  ---------------------------------------------------------
                 */
                if ( file_frame ) {

                    file_frame.open();

                    return;
                }

                /**
                 *  File Frame
                 *  ----------
                 */
                file_frame      =   wp.media.frames.file_frame      =   wp.media({

                                        title           :       frame_title,

                                        button          :       { text: frame_button_name, },

                                        multiple        :       false,

                                        library         :       { type: allowed_type },

                                    } );

                /**
                 *  Selected Media Will Selected when Open
                 *  --------------------------------------
                 *  @credit -  https://wordpress.stackexchange.com/questions/235406/how-do-i-select-an-image-from-media-library-in-my-plugin#answer-236296
                 *  --------------------------------------------------------------------------------------------------------------------------------------
                 */
                if( false ){

                    file_frame.on( 'open',function() {

                        var     selection       =       file_frame.state().get( 'selection' );

                        /**
                         *  Make sure have value 
                         *  --------------------
                         */
                        if( _is_empty( $( '#' + store_media_ids ).val() ) ){

                            var     ids             =       $( '#' + store_media_ids ).val().split( ',' );

                            ids.forEach( function( id ){
                                
                                var     attachment      =   wp.media.attachment( id );

                                        attachment.fetch();

                                        selection.add( attachment ? [ attachment ] : [] );
                            } );
                        }

                    } );
                }

                /**
                 *  Select
                 *  ------
                 */
                file_frame.on( 'select', function() {

                    /**
                     *  Handler
                     *  -------
                     */
                    var     selection           =       file_frame.state().get('selection'),

                            attachment_ids      =       selection.map( function( attachment ) {

                                                            attachment = attachment.toJSON();

                                                            /**
                                                             *  Only Allowed Type Data Collect
                                                             *  ------------------------------
                                                             */
                                                            if( allowed_type == attachment.type ){

                                                                return      attachment.id;
                                                            }

                                                        } ).join();

                    if( attachment_ids.charAt(0) === ',' ) {

                        attachment_ids = attachment_ids.substring(1); 
                    }

                    /**
                     *  Get Image links
                     *  ---------------
                     */
                    var     attachment_thumbs   =   selection.map( function( attachment ) {

                                                        attachment      =   attachment.toJSON();

                                                        if( attachment.type == allowed_type ){

                                                            return attachment.filename;
                                                        }

                                                    } ).join(' ');


                    /**
                     *  Make sure file is PDF
                     *  ---------------------
                     */
                    if( attachment_thumbs == '' ){

                        $( '#' + file_name ).html( 'Error...' );

                    }else{

                        /**
                         *  Image Preview Ids showing image
                         *  -------------------------------
                         */
                        if( $( '#' + file_name ).length ){

                            $( '#' + file_name ).html( attachment_thumbs );
                        }

                        /**
                         *  Media ids updated in text fields
                         *  --------------------------------
                         */
                        if( $( '#' + file_id ).length ){

                            $( '#' + file_id ).attr( 'value', attachment_ids );
                        }
                    }

                } );

                /**
                 *  File Open
                 *  ---------
                 */
                file_frame.open();

            } );
        },

        /**
         *  Update Data in Database
         *  -----------------------
         */
        upate_media_in_database: function( media_update_key, post_id, attachment_ids ){

            /**
             * AJAX is "TRUE" after direct update post ids with thumb ids
             * ----------------------------------------------------------
             */
            if( ( media_update_key !== '' && media_update_key !== null ) && post_id !== 0 && post_id !== '0' ){

                $.ajax({

                    type            :   'POST',

                    dataType        :   'json',

                    url             :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                    data            :   {

                        'action'        :   'sdweddingdirectory_update_single_media',

                        'image_key'     :   media_update_key,

                        'image_id'      :   attachment_ids,

                        'post_id'       :   post_id,

                        'security'      :   SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_media_security
                    },

                    success: function( RESPONSE ){

                        sdweddingdirectory_alert( RESPONSE );
                    },

                    beforeSend: function(){

                    },

                    error: function (xhr, ajaxOptions, thrownError) {

                        console.log( 'Media Not Updated in Database' );

                        console.log( xhr.status );

                        console.log( thrownError );

                        console.log( xhr.responseText );
                    },

                    complete: function( event, request, settings ){

                    }

                } );
            }
        },

        /**
         *  Image Format
         *  ------------
         */
        image_format: function( src, id ){

            /**
             *  Return HTML
             *  -----------
             */
            return  '<div class="col sdweddingdirectory_gallery_thumb">'+

                        '<div class="dash-categories">'+

                            '<div class="edit">'+

                                '<a href="javascript:" data-media-id="'+id+'" class="sdweddingdirectory-remove-media"><i class="fa fa-trash"></i></a>'+

                            '</div>'+

                            '<img src="'+src+'" data-media-id="'+id+'" />'+

                        '</div>'+

                    '</div>';
        },

        /**
         *  3. Removed Gallery Thumb
         *  ------------------------
         */
        remove_gallery_image: function (){

            /**
             *  When Removed Image
             *  ------------------
             */
            $( document ).on( 'click', '.sdweddingdirectory-remove-media', function( e ){

                /**
                 *  Variable
                 *  --------
                 */
                var     _this               =   $(this),

                        parent_section      =   '#' + $(_this).closest( '.row' ).attr( 'id' ),

                        have_media_id       =   $(_this).attr( 'data-media-id' ),

                        media_update_key    =   $( parent_section ).attr( 'data-key' ),

                        is_ajax             =   $( parent_section ).attr( 'data-ajax-save' ),

                        post_id             =   $( parent_section ).attr( 'data-post-id' ),

                        store_media_ids     =   $( parent_section ).attr( 'data-value-update-text-id' );


                /**
                 *  @link - https://css-tricks.com/snippets/jquery/make-an-jquery-hasattr/#article-header-id-0
                 *  ------------------------------------------------------------------------------------------
                 */
                if( typeof have_media_id !== typeof undefined && have_media_id !== false ){

                    if ( ! confirm( 'Are you sure ?' )  ){

                        return false;
                    }

                    e.preventDefault();
                    
                    /**
                     *  Removed Gallery Image
                     *  ---------------------
                     */
                    $( _this ).closest( '.sdweddingdirectory_gallery_thumb' ).remove();


                    /**
                     *  New Media IDs
                     *  -------------
                     */
                    var     new_media_ids   =   $( parent_section   + ' img' ).map( function(){

                                                    return      $( this ).attr( 'data-media-id' );

                                                } ).get().join( ',' );

                    /**
                     *  Update Media
                     *  ------------
                     */
                    $( '#' + store_media_ids ).val( new_media_ids );

                    /**
                     * AJAX is "TRUE" after direct update post ids with thumb ids
                     * ----------------------------------------------------------
                     */
                    if( is_ajax == 'true' || is_ajax == true ){

                        /**
                         *  Update Media in Database
                         *  ------------------------
                         */
                        SDWeddingDirectory_Media_Uploader.upate_media_in_database( 

                            media_update_key, 

                            post_id, 

                            new_media_ids 
                        );
                    }
                }

            } );
        },

        /**
         *  Objects Member's
         *  ----------------
         */
        init: function() {

            /**
             *  1. Upload Media
             *  ---------------
             */
            this.single_upload_media();

            /**
             *  2. Multi Media Uploader
             *  -----------------------
             */
            this.multi_upload_media();

            /**
             *  3. Removed Gallery Thumb
             *  ------------------------
             */
            this.remove_gallery_image();

            /**
             *  5. Multiple Upload PDF Fields used script
             *  -----------------------------------------
             */
            this.upload_multiple_pdf_fields();
        },
    }

    /**
     *  Store Object
     *  ------------
     */
    window.SDWeddingDirectory_Media_Uploader    =   SDWeddingDirectory_Media_Uploader;

    /**
     *  Document Ready to Run script on browser
     *  ---------------------------------------
     */
    $(document).ready( function() {  SDWeddingDirectory_Media_Uploader.init(); } );

})(jQuery);
