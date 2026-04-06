(function ($) {

    "use strict";

    var SDWeddingDirectory_Find_Post_Dropdown    =   {

        /**
         *  Object Variable
         *  ---------------
         */
        _loader                     :   '<span class="input-group-text"><i class="fa fa-spinner fa-spin"></i></span>',

        _close                      :   '<span class="input-group-text"><i class="fa fa-close"></i></span>',

        /**
         *  1. Open Category Box
         *  --------------------
         */
        open_databox: function(){

            /**
             *  Open Databox Script
             *  -------------------
             */
            if( $( '.sdweddingdirectory-dropdown-parent .sdweddingdirectory-dropdown-handler' ).length ){

                $( '.sdweddingdirectory-dropdown-parent .sdweddingdirectory-dropdown-handler' ).map( function(){

                    /**
                     *  Variable
                     *  --------
                     */
                    var     _input_id               =       '#'     +   $( this ).attr( 'data-input-id' ),

                            _dropdown_id            =       '#'     +   $( this ).attr( 'data-dropdown-id' );
                    
                    /**
                     *  When click on input
                     *  -------------------
                     */
                    $( _input_id ).on( 'focus', function(){

                        $( _dropdown_id ).addClass( 'open' );

                    } );

                    /**
                     *  Keydown
                     *  -------
                     */
                    $( document ).on( 'keydown', function(event) {

                        if( event.key == "Escape" ){

                            /**
                             *  Dropdown Hide
                             *  ------------
                             */
                            $( _dropdown_id ).removeClass( 'open' );
                        }

                    } );

                    /**
                     *  If Event Check
                     *  --------------
                     */
                    $( document ).click( event, function(){

                        if ( ! $(event.target).closest( _input_id +','+ _dropdown_id ).length ){

                            /**
                             *  Dropdown Hide
                             *  ------------
                             */
                            $( _dropdown_id ).removeClass( 'open' );
                        }

                    } );

                } );
            }
        },

        /**
         *  Character valid ?
         *  -----------------
         *  @link - https://www.aspsnippets.com/Articles/2687/Perform-AlphaNumeric-validation-Alphabets-and-Numbers-using-OnKeyPress-in-jQuery/
         *  -----------------------------------------------------------------------------------------------------------------------------------
         */
        valid_character: function( e ){

            var keyCode = e.keyCode || e.which;

            var regex = /^[A-Za-z0-9]+$/;

            /**
             *  Valid ?
             *  -------
             */
            if ( regex.test( String.fromCharCode( keyCode ) ) ){

                return      true;
            }

            else{

                return      false;
            }
        },

        /**
         *  2. If Write some text
         *  ---------------------
         */
        search_data: function(){

            /**
             *  Object Variable
             *  ---------------
             */
            var     _loader              =  this._loader,

                    _close               =  this._close;

            /**
             *  Open Databox Script
             *  -------------------
             */
            if( $( '.sdweddingdirectory-dropdown-parent .sdweddingdirectory-dropdown-handler' ).length ){

                $( '.sdweddingdirectory-dropdown-parent .sdweddingdirectory-dropdown-handler' ).map( function(){

                    /**
                     *  Variable
                     *  --------
                     */
                    var     _target_id              =   '#'     +   $( this ).attr( 'id' ),

                            _input_id               =   '#'     +   $( this ).attr( 'data-input-id' ),

                            _dropdown_id            =   '#'     +   $( this ).attr( 'data-dropdown-id' ),

                            _hidden_store_id        =   '#'     +   $( this ).attr( 'data-hidden-fields' ),

                            another_dropdown_id     =   '#'     +   $( this ).attr( 'data-dropdown-another-id' );

                    /**
                     *  Get Input Data
                     *  --------------
                     */
                    if( $( _input_id ).length ){

                        /**
                         *  When write somthing in input
                         *  ----------------------------
                         */
                        $( _input_id ).on( 'keyup', _wait( function( e ){

                            /**
                             *  Wait for event
                             *  --------------
                             */
                            e.preventDefault();

                            /**
                             *  Have AJAX ?
                             *  -----------
                             */
                            var     _this               =       '#'     +   $(this).attr( 'id' ),

                                    enable_ajax         =       $( this ).attr( 'data-ajax' ) == 1

                                                                ?   true

                                                                :   false,

                                    ajax_action         =       $(this).attr( 'data-ajax-write-keyword' ),

                                    hide_empty          =       $(this).attr( 'data-enable-empty-term' ),

                                    display_tab         =       $(this).attr( 'data-display-tab' ),

                                    input_value         =       $.trim( $(this).val() ),

                                    post_type           =       $(this).attr( 'data-post-type' ),

                                    load_data_id        =       '#'     +   $(this).attr( 'data-display-id' ),

                                    /**
                                     *  This is another input id to get value 
                                     *  -------------------------------------
                                     */
                                    another_input_id    =       '#' + $(this).attr( 'data-term-id' ),

                                    term_value          =       $( another_input_id ).length && _is_empty( $( another_input_id ).attr( 'data-value-id' ) )

                                                                ?   parseInt( $( another_input_id ).attr( 'data-value-id' ) )

                                                                :   '',

                                    depth_level         =       $( this ).attr( 'data-depth-level' );

                            /**
                             *  Ajax Enable ?
                             *  -------------
                             */
                            if( enable_ajax ){

                                /**
                                 *  Get Collection
                                 *  --------------
                                 */
                                var     ajax_query_args          =   {};

                                /**
                                 *  Collection
                                 *  ----------
                                 */
                                $( _hidden_store_id ).find( 'input[type=hidden].get_query' ).map( function( index, input ){

                                    /**
                                     *  Var
                                     *  ---
                                     */
                                    var     have_value  =   $( input ).val();

                                    /**
                                     *  Have Value
                                     *  ----------
                                     */
                                    if( true ){

                                        ajax_query_args[ $( input ).attr( 'name' ) ]   =   $( input ).val();
                                    }

                                } );

                                /**
                                 *  Args
                                 *  ----
                                 */
                                ajax_query_args[ 'action' ]         =   ajax_action;

                                ajax_query_args[ 'term_id' ]        =   term_value;

                                ajax_query_args[ 'post_type' ]      =   post_type;

                                ajax_query_args[ 'input_data' ]     =   input_value;

                                ajax_query_args[ 'hide_empty' ]     =   hide_empty;

                                ajax_query_args[ 'depth_level' ]    =   depth_level;

                                ajax_query_args[ 'display_tab' ]    =   display_tab;


                                /**
                                 *  Sending Data AJAX
                                 *  -----------------
                                 */
                                $.ajax( {

                                    type            :   'POST',

                                    dataType        :   'json',

                                    url             :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                                    data            :   ajax_query_args,

                                    beforeSend      :   function(){

                                                            $( _target_id ).find( '.input-group-text' ).remove();

                                                            $( _this ).parent().append( _loader );
                                                        },

                                    success         :   function( PHP_RESPONSE ){

                                                            /**
                                                             *  Category Dropdown Data Added
                                                             *  ----------------------------
                                                             */
                                                            $( load_data_id ).html( PHP_RESPONSE.data );

                                                            /**
                                                             *  Update Value in Input
                                                             *  ---------------------
                                                             */
                                                            SDWeddingDirectory_Find_Post_Dropdown.update_value_in_input();

                                                            /**
                                                             *  Find Keyword
                                                             *  ------------
                                                             */
                                                            SDWeddingDirectory_Find_Post_Dropdown.find_keyword();
                                                        },

                                    complete        :   function(){

                                                            /**
                                                             *  Loader Removed on Category Input Box
                                                             *  ------------------------------------
                                                             */
                                                            $( _target_id ).find( '.input-group-text' ).remove();

                                                            /**
                                                             *  Both Dropdown Hide
                                                             *  ------------------
                                                             */
                                                            $( another_dropdown_id + ',' + _dropdown_id ).removeClass( 'open' );

                                                            /**
                                                             *  Loader Removed on Category Input Box
                                                             *  ------------------------------------
                                                             */
                                                            $( _dropdown_id ).addClass( 'open' );
                                                        },
                                } );
                            }

                            /**
                             *  No AJAX - ok open the dropdown while not click on item
                             *  ------------------------------------------------------
                             */
                            else{

                                /**
                                 *  Dropdown Open while writing somthing
                                 *  ------------------------------------
                                 */
                                $( _dropdown_id ).addClass( 'open' );

                                /**
                                 *  Update Value in Input
                                 *  ---------------------
                                 */
                                SDWeddingDirectory_Find_Post_Dropdown.update_value_in_input();
                            }


                        }, 500 ) );
                    }

                } );
            }
        },

        /**
         *  3. When click on data to update in input
         *  ----------------------------------------
         */
        update_value_in_input: function(){

            /**
             *  Object Variable
             *  ---------------
             */
            var     _loader              =  this._loader,

                    _close               =  this._close;

            /**
             *  Open Databox Script
             *  -------------------
             */
            if( $( '.sdweddingdirectory-dropdown-parent .sdweddingdirectory-dropdown-handler' ).length ){

                $( '.sdweddingdirectory-dropdown-parent .sdweddingdirectory-dropdown-handler' ).map( function(){

                    /**
                     *  Variable
                     *  --------
                     */
                    var     _target_id              =       '#'     +   $( this ).attr( 'id' ),

                            _input_id               =       '#'     +   $( this ).attr( 'data-input-id' ),

                            _dropdown_id            =       '#'     +   $( this ).attr( 'data-dropdown-id' ),

                            _hidden_store_id        =       '#'     +   $( this ).attr( 'data-hidden-fields' ),

                            _ajax_find_term         =       $( _input_id ).attr( 'data-ajax-find-term' ),

                            _post_type              =       $( _input_id ).attr( 'data-post-type' ),


                            /**
                             *  This is another input id to get value 
                             *  -------------------------------------
                             */
                            another_dropdown_id     =       '#'     +   $( this ).attr( 'data-dropdown-another-id' ),

                            another_input_id        =       '#'     +   $( this ).attr( 'data-term-id' ),

                            another_display_id      =       '#'     +   $( another_input_id ).attr( 'data-display-id' ),

                            another_input_exists    =       $( another_input_id ).length  

                                                            ?   true

                                                            :   false,

                            another_input_ajax      =       another_input_exists &&

                                                            $( another_input_id ).attr( 'data-ajax' ) == 1 || 

                                                            $( another_input_id ).attr( 'data-ajax' ) == true

                                                            ?       true 

                                                            :       false,

                            another_input_find      =       another_input_exists && _is_empty( $( another_input_id ).attr( 'data-value-id' ) )

                                                            ?   false

                                                            :   true,

                            depth_level             =       $( another_input_id ).attr( 'data-depth-level' );

                    /**
                     *  When Dropdown [ Target Item ] Click Event Fire
                     *  ----------------------------------------------
                     */
                    if( $( _dropdown_id + ' a.search-item' ).length ){

                        $( _dropdown_id + ' a.search-item' ).off().one( 'click', function( e ){

                            /**
                             *  Wait for event
                             *  --------------
                             */
                            e.preventDefault();

                            /**
                             *  Variable
                             *  --------
                             */
                            var     placeholder         =       $(this).attr( 'data-placeholder-name' ),

                                    collection          =       $(this).attr( 'data-collection' ),

                                    _href               =       $(this).attr( 'href' );

                            /**
                             *  Have Data ?
                             *  -----------
                             */
                            if( collection != '' ){

                                /**
                                 *  JSON
                                 *  ----
                                 */
                                var     data_json       =       $.parseJSON( collection );

                                /**
                                 *  Update Input Hidden Fields
                                 *  --------------------------
                                 */
                                $.map( data_json, function( input_value, input_id ){

                                    if( $( 'input[name=' + input_id + ']' ).length ){

                                        $( 'input[name=' + input_id + ']' ).val( input_value );
                                    }

                                } );

                                /**
                                 *  Term ID
                                 *  -------
                                 */
                                $( _input_id ).attr( 'data-value-id', data_json.term_id  );

                                /**
                                 *  Term Name
                                 *  ---------
                                 */
                                $( _input_id ).val( data_json.term_name );

                                /**
                                 *  Update Prev Select 
                                 *  ------------------
                                 */
                                $( _input_id ).attr( 'data-last-value', data_json.term_id );

                                /**
                                 *  Update Prev Data
                                 *  ----------------
                                 */
                                $( _input_id ).attr( 'data-last-data', collection );

                                /**
                                 *  Dropdown Hide
                                 *  -------------
                                 */
                                $( _dropdown_id ).removeClass( 'open' );

                                /**
                                 *  Remove Selection Function Call
                                 *  ------------------------------
                                 */
                                SDWeddingDirectory_Find_Post_Dropdown.removed_selection();

                                /**
                                 *  Have venue category ?
                                 *  -----------------------
                                 */
                                if( another_input_exists && another_input_find ){

                                    /**
                                     *  Have AJAX
                                     *  ---------
                                     */
                                    if( another_input_ajax ){

                                        /**
                                         *  Get Collection
                                         *  --------------
                                         */
                                        var     ajax_query_args          =   {};

                                        /**
                                         *  Collection
                                         *  ----------
                                         */
                                        $( _hidden_store_id ).find( 'input[type=hidden].get_query' ).map( function( index, input ){

                                            /**
                                             *  Var
                                             *  ---
                                             */
                                            var     have_value  =   $( input ).val();

                                            /**
                                             *  Have Value
                                             *  ----------
                                             */
                                            if( true ){

                                                ajax_query_args[ $( input ).attr( 'name' ) ]   =   $( input ).val();
                                            }

                                        } );

                                        ajax_query_args[ 'action' ]         =       _ajax_find_term;

                                        ajax_query_args[ 'term_id' ]        =       data_json.term_id;

                                        ajax_query_args[ 'post_type' ]      =       _post_type;

                                        ajax_query_args[ 'depth_level' ]    =       depth_level;

                                        /**
                                         *  Sending Data AJAX
                                         *  -----------------
                                         */
                                        $.ajax( {

                                            type            :   'POST',

                                            dataType        :   'json',

                                            url             :   SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                                            data            :   ajax_query_args,

                                            beforeSend      :   function(){

                                                                    $( another_input_id ).parent().find( '.input-group-text' ).remove();

                                                                    $( another_input_id ).parent().append( _loader );
                                                                },

                                            success         :   function( PHP_RESPONSE ){

                                                                    /**
                                                                     *  Location Show with Content
                                                                     *  --------------------------
                                                                     */
                                                                    $( another_display_id ).html( PHP_RESPONSE.data );

                                                                    /**
                                                                     *  Update Value in Input
                                                                     *  ---------------------
                                                                     */
                                                                    SDWeddingDirectory_Find_Post_Dropdown.update_value_in_input();

                                                                    /**
                                                                     *  Search Inside Location
                                                                     *  ----------------------
                                                                     */
                                                                    SDWeddingDirectory_Find_Post_Dropdown.find_keyword();
                                                                },

                                            complete        :   function(){

                                                                    /**
                                                                     *  Find Input Loader Removed
                                                                     *  -------------------------
                                                                     */
                                                                    $( another_input_id ).parent().find( '.input-group-text' ).remove();

                                                                    /**
                                                                     *  Both Dropdown Hide
                                                                     *  ------------------
                                                                     */
                                                                    $( _dropdown_id + ',' + another_dropdown_id ).removeClass( 'open' );

                                                                    /**
                                                                     *  Category Dropdown Open
                                                                     *  ----------------------
                                                                     */
                                                                    $( another_dropdown_id ).addClass( 'open' );
                                                                },
                                        } );
                                    }

                                    /**
                                     *  Open Simple Dropdown
                                     *  --------------------
                                     */
                                    else{

                                        /**
                                         *   Open Another Dropdown
                                         *   ---------------------
                                         */
                                        $( another_dropdown_id ).addClass( 'open' );
                                    }
                                }
                            }

                        } );
                    }

                } );
            }
        },

        /**
         *  4. Have Data ?
         *  --------------
         */
        removed_selection: function(){

            /**
             *  Object Variable
             *  ---------------
             */
            var     _loader              =  this._loader,

                    _close               =  this._close;

            /**
             *  Open Databox Script
             *  -------------------
             */
            if( $( '.sdweddingdirectory-dropdown-parent .sdweddingdirectory-dropdown-handler' ).length ){

                $( '.sdweddingdirectory-dropdown-parent .sdweddingdirectory-dropdown-handler' ).map( function(){

                    /**
                     *  Variable
                     *  --------
                     */
                    var     _target_id              =       '#'     +   $( this ).attr( 'id' ),

                            _input_id               =       '#'     +   $( this ).attr( 'data-input-id' ),

                            _input_value            =       _is_empty( $.trim( $( _input_id ).val() ) )

                                                            ?       true

                                                            :       false,

                            _dropdown_id            =       '#'     +   $( this ).attr( 'data-dropdown-id' ),

                            _ajax_find_term         =       $( _input_id ).attr( 'data-ajax-find-term' ),

                            _post_type              =       $( _input_id ).attr( 'data-post-type' ),

                            _last_save_data         =       $( _input_id ).attr( 'data-last-data' ),


                            /**
                             *  This is another input id to get value 
                             *  -------------------------------------
                             */
                            another_dropdown_id     =       '#'     +   $( this ).attr( 'data-dropdown-another-id' ),

                            another_input_id        =       '#'     +   $( this ).attr( 'data-term-id' ),

                            another_display_id      =       $( another_input_id ).attr( 'data-display-id' ),

                            another_input_exists    =       $( another_input_id ).length  

                                                            ?   true

                                                            :   false,

                            another_input_ajax      =       another_input_exists &&

                                                            $( another_input_ajax ).attr( 'data-ajax' ) == 1 || 

                                                            $( another_input_ajax ).attr( 'data-ajax' ) == true

                                                            ?       true 

                                                            :       false,

                            another_input_find      =       another_input_exists && _is_empty( $( another_input_id ).attr( 'data-value-id' ) )

                                                            ?   false

                                                            :   true;

                    /**
                     *  Have Data ?
                     *  -----------
                     */
                    if( $( _input_id ).length && _input_value ){

                        /**
                         *  Removed Icon Section
                         *  --------------------
                         */
                        $( _input_id ).parent().find( '.input-group-text' ).remove();

                        /**
                         *  Close Icon Added
                         *  ----------------
                         */
                        $( _input_id ).parent().append( _close );

                        /**
                         *  Click on Removed
                         *  ----------------
                         */
                        $( _input_id ).parent().find( '.fa-close' ).one( 'click', function( e ){

                            /**
                             *  Event Start
                             *  -----------
                             */
                            e.preventDefault();

                            /**
                             *  Both Dropdown Hide
                             *  ------------------
                             */
                            $( _dropdown_id + ',' + another_dropdown_id ).removeClass( 'open' );

                            /**
                             *  Removed Term ID + Value Text in Input
                             *  -------------------------------------
                             */
                            $( _input_id ).attr( 'data-value-id', '' ).val( '' );

                            /**
                             *  Removed Last Store Data in Input + Hidden Fields
                             *  ------------------------------------------------
                             */
                            if( _is_empty( _last_save_data ) ){

                                /**
                                 *  JSON
                                 *  ----
                                 */
                                var     data_json       =       $.parseJSON( _last_save_data );

                                /**
                                 *  Update Input Hidden Fields
                                 *  --------------------------
                                 */
                                $.map( data_json, function( input_value, input_id ){

                                    if( $( 'input[name=' + input_id + ']' ).length ){

                                        $( 'input[name=' + input_id + ']' ).val( '' );
                                    }

                                } );
                            }

                            /**
                             *  Click on Input Category
                             *  -----------------------
                             */
                            $( _input_id ).keyup();

                            /**
                             *  Make sure location not empty!
                             *  -----------------------------
                             */
                            if( another_input_find ){

                                /**
                                 *  Load Location Data with Category ID wise
                                 *  ----------------------------------------
                                 */
                                $( another_input_id ).keyup();
                            }

                        } );
                    }

                } );
            }
        },

        /**
         *  Find KeyWord to get data
         *  ------------------------
         */
        find_keyword: function(){

            /**
             *  Make sure class exists
             *  ----------------------
             */
            if( $( '.search-inside-location' ).length ){

                $( '.search-inside-location' ).on( 'keyup', function(){

                    var value = $(this).val().toLowerCase();

                    $( '#' + $(this).attr( 'data-target-box' ) + ' li' ).filter( function() {

                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)

                    });

                } );
            }
        },

        /**
         *  5. Form Submit
         *  --------------
         */
        form_submit: function(){

            /**
             *  Form Submit ?
             *  -------------
             */
            if( $( 'form.sdweddingdirectory-result-page' ).length ){

                $( 'form.sdweddingdirectory-result-page' ).on( 'submit', function( e ){

                    /**
                     *  Wait
                     *  ----
                     */
                    e.preventDefault();

                    /**
                     *  Get Collection
                     *  --------------
                     */
                    var     _get_query          =   {};

                    /**
                     *  Collection
                     *  ----------
                     */
                    $( this ).find( 'input[type=hidden].get_query' ).map( function( index, input ){

                        /**
                         *  Var
                         *  ---
                         */
                        var     have_value  =   $( input ).val();

                        /**
                         *  Have Value
                         *  ----------
                         */
                        if( _is_empty( have_value ) ){

                            _get_query[ $( input ).attr( 'name' ) ]   =   $( input ).val();
                        }

                    } );

                    /**
                     *  Is Empty!
                     *  ---------
                     */
                    if( ! $.isEmptyObject( _get_query ) ){

                        if( _is_empty( _get_query.location ) ){

                            delete _get_query.state_id;
                            delete _get_query.state_name;
                            delete _get_query.region_id;
                            delete _get_query.region_name;
                            delete _get_query.city_id;
                            delete _get_query.city_name;
                        }

                        /**
                         *  Redirection with Args
                         *  ---------------------
                         */
                        window.location.href    =   $(this).attr( 'action' ) + '?' + $.param( _get_query );
                    }

                    else{

                        /**
                         *  Redirection with Args
                         *  ---------------------
                         */
                        window.location.href    =   $(this).attr( 'action' );
                    }

                } );
            }
        },

        /**
         *  7. Location Dropdown Load with JS
         *  ---------------------------------
         */
        dropdown_location_js: function(){

            /**
             *  Make sure input location have enable dropdown JS
             *  ------------------------------------------------
             */
            if( $( 'input.location-input[data-dropdown-js="1"]' ).length ){

                $( 'input.location-input[data-dropdown-js="1"]' ).map( function(){

                    var         _this               =      $( this ),

                                category_id         =      $( _this ).attr( 'data-category-id' ),

                                hide_empty          =      $( _this ).attr( 'data-enable-empty-term' ),

                                post_type           =      $( _this ).attr( 'data-post-type' ),

                                taxonomy            =      $( _this ).attr( 'data-taxonomy' ),

                                load_data_id        =       '#'     +   $( _this ).attr( 'data-display-id' ),

                                depth_level         =       $( _this ).attr( 'data-depth-level' ),

                                display_tab         =       $( _this ).attr( 'data-display-tab' );

                    /**
                     *  Sending Data AJAX
                     *  -----------------
                     */
                    $.ajax( {

                        type            :       'POST',

                        dataType        :       'json',

                        url             :       SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                        data            :       {

                                                    'action'            :       'sdweddingdirectory_location_dropdown_load_with_js',

                                                    'term_id'           :       category_id,

                                                    'hide_empty'        :       hide_empty,

                                                    'post_type'         :       post_type,

                                                    'taxonomy'          :       taxonomy,

                                                    'depth_level'       :       depth_level,

                                                    'display_tab'       :       display_tab,
                                                },

                        beforeSend          :   function(){

                                                },

                        success             :   function( PHP_RESPONSE ){

                                                    /**
                                                     *  Category Dropdown Data Added
                                                     *  ----------------------------
                                                     */
                                                    $( load_data_id ).html( PHP_RESPONSE.data );

                                                    /**
                                                     *  Update Value in Input
                                                     *  ---------------------
                                                     */
                                                    SDWeddingDirectory_Find_Post_Dropdown.update_value_in_input();

                                                    /**
                                                     *  Find Keyword
                                                     *  ------------
                                                     */
                                                    SDWeddingDirectory_Find_Post_Dropdown.find_keyword();
                                                },

                        complete            :   function(){

                                                },

                    } );

                } );
            }
        },

        /**
         *  Load Script
         *  -----------
         */
        init: function(){

            /**
             *  1. When click on input
             *  ----------------------
             */
            this.open_databox();

            /**
             *  2. If Write some text
             *  ---------------------
             */
            this.search_data();

            /**
             *  3. When click on data to update in input
             *  ----------------------------------------
             */
            this.update_value_in_input();

            /**
             *  4. Removed Data
             *  ---------------
             */
            this.removed_selection();

            /**
             *  5. Form Submit
             *  --------------
             */
            this.form_submit();

            /**
             *  6. Find Keyword
             *  ---------------
             */
            this.find_keyword();

            /**
             *  7. Location Dropdown Load with JS
             *  ---------------------------------
             */
            this.dropdown_location_js();

            /**
             *  8. Wedding Venues link → focus Location input
             *  ---------------------------------------------
             */
            this.venues_link_focus_location();
        },

        /**
         *  8. When "Wedding Venues" mega menu item is clicked,
         *     tab cursor to the Location input
         *  ---------------------------------------------------
         */
        venues_link_focus_location: function(){

            $( document ).on( 'click', '.sd-mega-venues-link a.search-item', function(){

                setTimeout( function(){

                    var _location_input = $( '.sdweddingdirectory-dropdown-handler' ).find( 'input[name$="_location_input"]' );

                    if( ! _location_input.length ){
                        _location_input = $( 'input[placeholder="Location"]' );
                    }

                    if( _location_input.length ){
                        _location_input.first().trigger( 'focus' );
                    }

                }, 150 );
            } );
        }
    }

    /**
     *  Global Declare Object
     *  ---------------------
     */
    window.SDWeddingDirectory_Find_Post_Dropdown =   SDWeddingDirectory_Find_Post_Dropdown;

    /**
     *  Document Ready
     *  --------------
     */
    $( document ).ready( function(){  SDWeddingDirectory_Find_Post_Dropdown.init();  } );

})(jQuery);
