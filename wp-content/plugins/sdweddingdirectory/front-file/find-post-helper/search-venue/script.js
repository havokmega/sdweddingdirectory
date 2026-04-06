(function($) {

    'use strict';

    var SDWeddingDirectory_Search_Venue = {

        /**
         *  Show filter widget
         *  ------------------
         */
        view_more_filter: function(){

            if( $( '.view-more-filter' ).length ){

                $( '.view-more-filter' ).on( 'click', function(){

                    $(this).closest( '.row' ).find( '.d-none' ).removeClass( 'd-none' );

                    $(this).parent().addClass( 'd-none' ).remove();

                } );
            }
        },

        /**
         *  Clear Filter
         *  ------------
         */
        clear_filter : function(){

            /**
             *  Make sure have at least one filter
             *  ----------------------------------
             */
            if( $( '.clear_active_filters' ).length ){

                /**
                 *  When click event pass
                 *  ---------------------
                 */
                $( '.clear_active_filters' ).off().one( 'click', function( e ){

                    /**
                     *  Event Start
                     *  -----------
                     */
                    e.preventDefault();

                    /**
                     *  Removed All Filters
                     *  -------------------
                     */
                    if( $( '.active-filters-widget a' ).length ){

                        $( '.active-filters-widget a' ).map( function(){

                            /**
                             *  Var
                             *  ---
                             */
                            var     filter_name     =   $(this).attr( 'data-handler' ),

                                    filter_id       =   '#' + filter_name,

                                    filter_value    =   $(this).attr( 'data-value' ),

                                    filter_type     =   $(this).attr( 'data-type' );

                                /**
                                 *  Make sure it's range type
                                 *  -------------------------
                                 */
                                if( filter_type == 'range' ){

                                    /**
                                     *  Have Range Type checkbox is checked ?
                                     *  -------------------------------------
                                     */
                                    if( $( filter_id + ' input:checkbox:checked' ).length ){

                                        /**
                                         *  Removed All Input as Checked
                                         *  ----------------------------
                                         */
                                        $( filter_id + ' input:checkbox' ).removeAttr('checked');
                                    }

                                    /**
                                     *  Min + Max + Query String will Empty
                                     *  -----------------------------------
                                     */
                                    $( filter_id + '-input-min' ).val( '' );

                                    $( filter_id + '-input-max' ).val( '' );

                                    /**
                                     *  Update Query
                                     *  ------------
                                     */
                                    SDWeddingDirectory_Search_Venue.update_query( filter_name, '' );
                                }

                                /**
                                 *  Make sure it's string type
                                 *  --------------------------
                                 */
                                else if( filter_type == 'string' ){

                                    /**
                                     *  Have Range Type checkbox is checked ?
                                     *  -------------------------------------
                                     */
                                    if( $( filter_id + ' input:checkbox:checked' ).length ){

                                        /**
                                         *  Removed All Input as Checked
                                         *  ----------------------------
                                         */
                                        $( filter_id + ' input:checkbox' ).removeAttr('checked');
                                    }

                                    /**
                                     *  Input Data Collection + Query String will Empty
                                     *  -----------------------------------------------
                                     */
                                    $( filter_id + '-input-data' ).val( '' );

                                    /**
                                     *  Update Query
                                     *  ------------
                                     */
                                    SDWeddingDirectory_Search_Venue.update_query(

                                        /**
                                         *  1. Data Query ID
                                         *  ----------------
                                         */
                                        filter_name,

                                        /** 
                                         *  2. Query Replace
                                         *  ----------------
                                         */
                                        SDWeddingDirectory_Search_Venue.query_replace( filter_name, '' )
                                    );
                                }

                                /**
                                 *  Make sure it's single type
                                 *  --------------------------
                                 */
                                else if( filter_type == 'single' ){

                                    /**
                                     *  Have Single Type checkbox is checked ?
                                     *  --------------------------------------
                                     */
                                    if( $( filter_id + ' input:checkbox:checked' ).length ){

                                        /**
                                         *  Removed All Input as Checked
                                         *  ----------------------------
                                         */
                                        $( filter_id + ' input:checkbox' ).removeAttr('checked');
                                    }

                                    /**
                                     *  Input Data Collection + Query String will Empty
                                     *  -----------------------------------------------
                                     */
                                    $( filter_id + '-input-data' ).val( '' );

                                    /**
                                     *  Update Query
                                     *  ------------
                                     */
                                    SDWeddingDirectory_Search_Venue.update_query( filter_name, '' );
                                }

                                /**
                                 *  Make sure it's string type
                                 *  --------------------------
                                 */
                                else if( filter_type == 'range-slider' ){

                                    /**
                                     *  Have Range Type checkbox is checked ?
                                     *  -------------------------------------
                                     */
                                    if( $( filter_id + '-input-data' ).length ){

                                        /**
                                         *  Removed All Input as Checked
                                         *  ----------------------------
                                         */
                                        $( filter_id + '-input-data' ).val( '0' );

                                        /**
                                         *  Removed All Input as Checked
                                         *  ----------------------------
                                         */
                                        $( filter_id + '-radius' ).attr( 'data-value', parseInt( '0' ) );

                                        /**
                                         *  Removed All Input as Checked
                                         *  ----------------------------
                                         */
                                        $( filter_id + '-counter' ).html( parseInt( '0' ) );

                                        /**
                                         *  Load Slider
                                         *  -----------
                                         */
                                        SDWeddingDirectory_Search_Venue.range_slider();
                                    }

                                    /**
                                     *  Update Query
                                     *  ------------
                                     */
                                    SDWeddingDirectory_Search_Venue.update_query(

                                        /**
                                         *  1. Data Query ID
                                         *  ----------------
                                         */
                                        filter_name,

                                        /** 
                                         *  2. Query Replace
                                         *  ----------------
                                         */
                                        SDWeddingDirectory_Search_Venue.query_replace( filter_name, '' )
                                    );
                                }

                                /**
                                 *  Make sure it's string type
                                 *  --------------------------
                                 */
                                else if( filter_type == 'select-item' ){

                                    /**
                                     *  Have Range Type checkbox is checked ?
                                     *  -------------------------------------
                                     */
                                    if( $( filter_id + '-input' ).length ){

                                        /**
                                         *  Removed All Input as Checked
                                         *  ----------------------------
                                         */
                                        $( filter_id + '-input' ).val( '' );

                                        /**
                                         *  Removed All Input as Checked
                                         *  ----------------------------
                                         */
                                        $( filter_id + '-input-data' ).val( '' );

                                        /**
                                         *  Removed All Input as Checked
                                         *  ----------------------------
                                         */
                                        $( filter_id + '-target-element a.active' ).removeClass( 'active' );
                                    }

                                    /**
                                     *  Update Query
                                     *  ------------
                                     */
                                    SDWeddingDirectory_Search_Venue.update_query(

                                        /**
                                         *  1. Data Query ID
                                         *  ----------------
                                         */
                                        filter_name,

                                        /** 
                                         *  2. Query Replace
                                         *  ----------------
                                         */
                                        SDWeddingDirectory_Search_Venue.query_replace( filter_name, '' )
                                    );
                                }

                                /**
                                 *  Make sure it's select type
                                 *  --------------------------
                                 */
                                else if( filter_type == 'select' ){

                                    /**
                                     *  Removed Checked
                                     *  ---------------
                                     */
                                    if( $( filter_id + ' select option:selected' ).length ){

                                        /**
                                         *  Remove Checked If Found
                                         *  -----------------------
                                         */
                                        $( filter_id + ' select option' ).removeAttr( 'selected' );

                                        /**
                                         *  Update in input field
                                         *  ---------------------
                                         */
                                        $( filter_id + '-input-data' ).val( '' );
                                    }

                                    /**
                                     *  Update Query
                                     *  ------------
                                     */
                                    SDWeddingDirectory_Search_Venue.update_query(

                                        /**
                                         *  1. Data Query ID
                                         *  ----------------
                                         */
                                        filter_name,

                                        /** 
                                         *  2. Query Replace
                                         *  ----------------
                                         */
                                        SDWeddingDirectory_Search_Venue.query_replace( filter_name, '' )
                                    );
                                }

                                /**
                                 *  Make sure it's Calender type
                                 *  ----------------------------
                                 */
                                else if( filter_type == 'calender' ){

                                    /**
                                     *  Empty Calender
                                     *  --------------
                                     */
                                    $( filter_id + '-input-data' ).val( '' );

                                    $( filter_id + '-calender' ).attr( 'data-date', '' );

                                    /**
                                     *  Create DatePicker
                                     *  -----------------
                                     */
                                    $( filter_id + '-calender' ) .datepicker( 'destroy' );

                                    /**
                                     *  New Calender Call
                                     *  -----------------
                                     */
                                    SDWeddingDirectory_Search_Venue.calendar_availability();

                                    /**
                                     *  Update Query
                                     *  ------------
                                     */
                                    SDWeddingDirectory_Search_Venue.update_query(

                                        /**
                                         *  1. Data Query ID
                                         *  ----------------
                                         */
                                        filter_name,

                                        /** 
                                         *  2. Query Replace
                                         *  ----------------
                                         */
                                        SDWeddingDirectory_Search_Venue.query_replace( filter_name, '' )
                                    );
                                }

                                /**
                                 *  Else 
                                 *  ----
                                 */
                                else{

                                    console.log( 'Can\'t Find Out This Type : ' + filter_type );
                                }

                                /** 
                                 *  Removed Filter
                                 *  --------------
                                 */
                                $( this ).remove();

                        } );
                    }

                    /**
                     *  Hide Filter Widet
                     *  -----------------
                     */
                    if( $( '#active-filters-section' ).length ){

                        $( '#active-filters-section' ).addClass( 'd-none' );
                    }

                    /**
                     *  Active Filters
                     *  --------------
                     */
                    SDWeddingDirectory_Search_Venue.active_filter();

                    /**
                     *  Find Venue
                     *  ------------
                     */
                    SDWeddingDirectory_Search_Venue.find_venue();

                } );
            }
        },

        /**
         *  Filter Added
         *  ------------
         */
        apply_filter: function( setting ){

            /**
             *  Merge Default Args
             *  ------------------
             */
            var filter  =   $.extend( true, {

                                id      :   null,

                                name    :   null,

                                action  :   null,

                                type    :   null,

                                remove  :   '<span><i class="fa fa-close ms-1"></i></span>',

                                class   :   'btn btn-outline-primary btn-sm btn-rounded me-1 mb-2'

                            }, setting );


            /**
             *  Add Filter
             *  ----------
             */
            if( filter.type == 'range-slider' && 'add' == filter.action ){

                /**
                 *  ===========
                 *  Add Process
                 *  ===========
                 */
                var     value       =   $( filter.id + '-input-data' ).val(),

                        string      =   $( filter.id +'-translation' ).length

                                    ?   ' ' + $( '#'+ filter.name +'-translation' ).val() + ' : '

                                    :   '',

                        _label      =   $('input[name=radius-in]:radio:checked').val(),

                        _value      =   '[data-value="'+ value +'"]',

                        _handler    =   '[data-handler="'+ filter.name +'"]';

                /**
                 *  Have Filter ?
                 *  -------------
                 */
                if( $( '.active-filters-widget a'+ _handler ).length ){

                    $( '.active-filters-widget a'+ _handler ).remove();
                }

                /**
                 *  Have already this query in active filter to verify not exitst to add current filter
                 *  ===================================================================================
                 */
                if( ! $( '.active-filters-widget a' + _value + _handler  ).length ){

                    $( '.active-filters-widget' ).append(' <a href="javascript:" id="'+ _rand() +'" class="'+ filter.class +'" data-handler="'+ filter.name +'" data-type="'+filter.type+'" data-value="'+ value +'">' + string + value + ' ' + _label + ' '+ filter.remove +'</a> ');
                }
            }

            /**
             *  Add Filter
             *  ----------
             */
            if( filter.type == 'calender' && 'add' == filter.action ){

                /**
                 *  ===========
                 *  Add Process
                 *  ===========
                 */
                var     collection      =   $( filter.id + '-input-data' ).val().split(','),

                        _handler        =   '[data-handler="'+ filter.name +'"]';

                /**
                 *  Have Filter ?
                 *  -------------
                 */
                if( $( '.active-filters-widget a'+ _handler ).length ){

                    $( '.active-filters-widget a'+ _handler ).remove();
                }

                /**
                 *  Make sure have data
                 *  -------------------
                 */
                if( $( collection ).length ){

                    /**
                     *  Update Filters
                     *  --------------
                     */
                    $( collection ).map( function( index, value ){

                        /**
                         *  Make sure value not empty!
                         *  --------------------------
                         */
                        if( value !== '' ){

                            /**
                             *  Var
                             *  ---
                             */
                            var     _value      =   '[data-value="'+ value +'"]';

                            /**
                             *  Have already this query in active filter to verify not exitst to add current filter
                             *  ===================================================================================
                             */
                            if( ! $( '.active-filters-widget a' + _value + _handler  ).length ){

                                $( '.active-filters-widget' ).append(' <a href="javascript:" id="'+ _rand() +'" class="'+ filter.class +'" data-handler="'+ filter.name +'" data-type="'+filter.type+'" data-value="'+ value +'">' + value + ' '+ filter.remove +'</a> ');
                            }
                        }

                    } );
                }
            }

            /**
             *  Add Filter
             *  ----------
             */
            if( filter.type == 'select' && 'add' == filter.action ){

                /**
                 *  ===========
                 *  Add Process
                 *  ===========
                 */
                var     value       =   $( filter.id + ' select option:selected' ).val(),

                        string      =   $( '#'+ filter.name +'-translation' ).length

                                    ?   ' ' + $( '#'+ filter.name +'-translation' ).val() + ' : '

                                    :   '',

                        _label      =   $( filter.id + ' select option:selected' ).attr( 'data-string' ),

                        _value      =   '[data-value="'+ value +'"]',

                        _handler    =   '[data-handler="'+ filter.name +'"]';

                /**
                 *  Have Filter ?
                 *  -------------
                 */
                if( $( '.active-filters-widget a'+ _handler ).length ){

                    $( '.active-filters-widget a'+ _handler ).remove();
                }

                /**
                 *  Have already this query in active filter to verify not exitst to add current filter
                 *  ===================================================================================
                 */
                if( ! $( '.active-filters-widget a' + _value + _handler  ).length ){

                    $( '.active-filters-widget' ).append(' <a href="javascript:" id="'+ _rand() +'" class="'+ filter.class +'" data-handler="'+ filter.name +'" data-type="'+filter.type+'" data-value="'+ value +'">' + string + ' ' + _label +' '+ filter.remove +'</a> ');
                }
            }

            /**
             *  Add Filter
             *  ----------
             */
            if( filter.type == 'select-item' && 'add' == filter.action ){

                /**
                 *  ===========
                 *  Add Process
                 *  ===========
                 */
                var     string      =   $( filter.id + '-input' ).val(),

                        value       =   $( filter.id + '-input-data' ).val(),

                        _value      =   '[data-value="'+ value +'"]',

                        _handler    =   '[data-handler="'+ filter.name +'"]';

                /**
                 *  Have Filter ?
                 *  -------------
                 */
                if( $( '.active-filters-widget a'+ _handler ).length ){

                    $( '.active-filters-widget a'+ _handler ).remove();
                }

                /**
                 *  Have already this query in active filter to verify not exitst to add current filter
                 *  ===================================================================================
                 */
                if( ! $( '.active-filters-widget a' + _value + _handler  ).length ){

                    $( '.active-filters-widget' ).append(' <a href="javascript:" id="'+ _rand() +'" class="'+ filter.class +'" data-handler="'+ filter.name +'" data-type="'+filter.type+'" data-value="'+ value +'">' + string +' '+ filter.remove +'</a> ');
                }
            }

            /**
             *  Add Filter
             *  ----------
             */
            if( filter.type == 'string' && 'add' == filter.action ){

                /**
                 *  Unchecked Checkbox Check
                 *  ------------------------
                 */
                $( filter.id + ' input:checkbox:not(:checked)' ).map(function(){

                    var _value      =   '[data-value="'+ $(this).val() +'"]',

                        _handler    =   '[data-handler="'+ filter.name +'"]';

                    /**
                     *  Have Filter ?
                     *  -------------
                     */
                    if( $( '.active-filters-widget a'+ _value + _handler ).length ){

                        $( '.active-filters-widget a'+ _value + _handler ).remove();
                    }
                });

                /**
                 *  Have Filter Section ?
                 *  ---------------------
                 */
                $( filter.id + ' input:checkbox:checked' ).map(function(){

                    /**
                     *  Create Object
                     *  -------------
                     */
                            var     string_filter   =   $.extend( true, {

                                                    rand_id     :  _rand(),

                                                    value       :   $(this).val(),

                                                    string      :   $(this).closest( 'div.col' ).find( 'label' ).html(),

                                                }, filter ),

                            _value          =   '[data-value="'+ string_filter.value +'"]',

                            _handler        =   '[data-handler="'+ filter.name +'"]';

                    /**
                     *  Have already this query in active filter to verify not exitst to add current filter
                     *  ===================================================================================
                     */
                    if( ! $( '.active-filters-widget a' + _value + _handler  ).length ){

                        $( '.active-filters-widget' ).append(' <a href="javascript:" id="'+ string_filter.rand_id +'" class="'+ filter.class +'" data-handler="'+ filter.name +'" data-type="'+filter.type+'" data-value="'+ string_filter.value +'">' + string_filter.string +' '+ filter.remove +'</a> ');
                    }

                });
            }

            /**
             *  Add Filter
             *  ----------
             */
            if( filter.type == 'single' && 'add' == filter.action ){

                var     _handler    =   '[data-handler="'+ filter.name +'"]',

                        checked     =   $( filter.id + ' input:checkbox:checked' ).first(),

                        value       =   checked.length ? checked.val() : '',

                        string      =   checked.length ? checked.closest( 'div.col' ).find( 'label' ).html() : '',

                        _value      =   '[data-value="'+ value +'"]';

                if( $( '.active-filters-widget a'+ _handler ).length ){

                    $( '.active-filters-widget a'+ _handler ).remove();
                }

                if( value !== '' && ! $( '.active-filters-widget a' + _value + _handler  ).length ){

                    $( '.active-filters-widget' ).append(' <a href="javascript:" id="'+ _rand() +'" class="'+ filter.class +'" data-handler="'+ filter.name +'" data-type="'+filter.type+'" data-value="'+ value +'">' + string +' '+ filter.remove +'</a> ');
                }
            }

            /**
             *  Add Filter
             *  ----------
             */
            if( filter.type == 'range'  && 'add' == filter.action ){

                /**
                 *  Unchecked Checkbox Check
                 *  ------------------------
                 */
                $( filter.id + ' input:checkbox:not(:checked)' ).map(function(){

                    var _value      =   '[data-value="'+ $(this).val() +'"]',

                        _handler    =   '[data-handler="'+ filter.name +'"]';

                    /**
                     *  Have Filter ?
                     *  -------------
                     */
                    if( $( '.active-filters-widget a'+ _value + _handler ).length ){

                        $( '.active-filters-widget a'+ _value + _handler ).remove();
                    }
                });

                /**
                 *  Have Filter Section ?
                 *  ---------------------
                 */
                $( filter.id + ' input:checkbox:checked' ).map(function(){

                    /**
                     *  Create Object
                     *  -------------
                     */
                    var     range_filter    =   $.extend( true, {

                                                    rand_id     :  _rand(),

                                                    value       :   $(this).val(),

                                                    string      :   $( '#'+ filter.name +'-translation' ).length

                                                                    ?   ' ' + $( '#'+ filter.name +'-translation' ).val() + ' '

                                                                    :   '',

                                                    before      :   $(this).closest( 'div.col' ).find( 'label' ).html(),

                                                    after       :   '',

                                                }, filter ),

                            _value          =   '[data-value="'+ range_filter.value +'"]',

                            _handler        =   '[data-handler="'+ filter.name +'"]';

                    /**
                     *  Have already this query in active filter to verify not exitst to add current filter
                     *  ===================================================================================
                     */
                    if( ! $( '.active-filters-widget a' + _value + _handler  ).length ){

                        $( '.active-filters-widget' ).append(' <a href="javascript:" id="'+ range_filter.rand +'" class="'+ filter.class +'" data-handler="'+ filter.name +'" data-type="'+filter.type+'" data-value="'+ range_filter.value +'">'+ range_filter.before + range_filter.string + range_filter.after +' '+ filter.remove +'</a> ');
                    }

                });
            }

            /**
             *  Have Filters ?
             *  --------------
             */
            SDWeddingDirectory_Search_Venue.active_filter();
        },

        /**
         *  Each Select Option Checked to find venue
         *  ------------------------------------------
         */
        get_select_item_data: function(){

            /**
             *  Have Class ?
             *  ------------
             */
            if( $( '.select-item-data' ).length ){

                $( '.select-item-data' ).map( function(){

                    var     _id             =   '#' + $(this).attr( 'data-handler' ),

                            _type           =   $(this).attr( 'data-type' ),

                            _filter_name    =   $(this).attr( 'data-handler' ),

                            _filter_enable  =   $( this ).hasClass( 'enable-filter-tag' );

                    /**
                     *  ============================================
                     *  Make sure this checkbox is string collection
                     *  ============================================
                     */
                    if( _type == 'select-item' ){

                        /**
                         *  Event Check
                         *  -----------
                         */
                        $( _id + '-target-element a' ).on( 'click', function(){


                            /**
                             *  JSON
                             *  ----
                             */
                            var     data_json       =       $.parseJSON( $(this).attr( 'data-collection' ) );

                            /**
                             *  Update Input Hidden Fields
                             *  --------------------------
                             */
                            $.map( data_json, function( input_value, input_id ){

                                if( $( 'input[name=' + input_id + ']' ).length ){

                                    $( 'input[name=' + input_id + ']' ).val( input_value );
                                }

                                /**
                                 *  Update Query Args
                                 *  -----------------
                                 */
                                SDWeddingDirectory_Search_Venue.update_query( input_id, input_value );

                            } );

                            /**
                             *  Make sure user selected region
                             *  ------------------------------
                             */
                            if( $(this).hasClass( '_default_region_' ) || $(this).hasClass( '_select_region_' ) ){


                                if( $( '#city_id-input-data' ).length ){

                                    $( '#city_id-input-data' ).val( '' );
                                }

                                /**
                                 *  Input Empty!
                                 *  ------------
                                 */
                                if( $( '#city_id-input' ).length ){

                                    $( '#city_id-input' ).val( '' );
                                }

                                /**
                                 *  City Tab / City Data Active Mode Removed
                                 *  ----------------------------------------
                                 */
                                if( $( '.city-data-tab a.list-group-item.active' ).length ){

                                    $( '.city-data-tab a.list-group-item.active' ).removeClass( 'active' );

                                    $( '.city-data-tab a.list-group-item._default_city_' ).addClass( 'active' );
                                }

                                /**
                                 *  Have Active Filter ?
                                 *  --------------------
                                 */
                                if( $( '.active-filters-widget a' ).attr( 'data-handler', 'city_id' ).length ){

                                    $( '.active-filters-widget a' ).attr( 'data-handler', 'city_id' ).remove();
                                }

                                /**
                                 *  Have Filter ?
                                 *  -------------
                                 */
                                SDWeddingDirectory_Search_Venue.active_filter();
                            }

                            else if( $(this).hasClass( '_default_city_' ) ){

                                /**
                                 *  Update Hidden Fields for City ID
                                 *  --------------------------------
                                 */
                                if( $( 'input#city_id' ).length  ){

                                    $( 'input#city_id' ).val( '' );
                                }

                                /**
                                 *  Have Active Filter ?
                                 *  --------------------
                                 */
                                if( $( '.active-filters-widget a' ).attr( 'data-handler', 'city_id' ).length ){

                                    $( '.active-filters-widget a' ).attr( 'data-handler', 'city_id' ).remove();
                                }

                                /**
                                 *  Have Filter ?
                                 *  -------------
                                 */
                                SDWeddingDirectory_Search_Venue.active_filter();
                            }

                            /**
                             *  Is Update in Active Filter ?
                             *  ----------------------------
                             */
                            if( _filter_enable ){

                                /**
                                 *  Add Filter
                                 *  ----------
                                 */
                                SDWeddingDirectory_Search_Venue.apply_filter( {

                                    id      :   _id,

                                    name    :   _filter_name,

                                    action  :   'add',

                                    type    :   _type

                                } );
                            }

                            /**
                             *  Have Checked Value ?
                             *  --------------------
                             */
                            if( $( _id + '-input-data' ).length ){

                                /**
                                 *  Update Query
                                 *  ------------
                                 */
                                SDWeddingDirectory_Search_Venue.update_query( _filter_name, $( _id + '-input-data' ).val() );

                                /**
                                 *  Update in Hidden Input
                                 *  ----------------------
                                 */
                                if( $( '#' + _filter_name ) ){

                                    $( '#' + _filter_name ).val( $( _id + '-input-data' ).val() );
                                }

                                /**
                                 *  Find Result
                                 *  -----------
                                 */
                                SDWeddingDirectory_Search_Venue.find_venue();
                            }

                            else{

                                /**
                                 *  Update Query
                                 *  ------------
                                 */
                                SDWeddingDirectory_Search_Venue.update_query( _filter_name, '' );

                                /**
                                 *  Update the Data in Input Fields
                                 *  -------------------------------
                                 */
                                $( _id + '-input-data' ).val( '' );

                                /**
                                 *  Update in Hidden Input
                                 *  ----------------------
                                 */
                                if( $( '#' + _filter_name ) ){

                                    $( '#' + _filter_name ).val( '' );
                                }

                                /**
                                 *  Find Result
                                 *  -----------
                                 */
                                SDWeddingDirectory_Search_Venue.find_venue();
                            }

                        });
                    }

                } );
            }
        },

        /**
         *  Range Slider
         *  ------------
         */
        range_slider: function(){

            /**
             *  Have Pricing Div ?
             *  ------------------
             */
            if( $( ".rang-slider" ).length ){

                /**
                 *  Range Slider
                 *  ------------
                 */
                $( ".rang-slider" ).map( function(){

                    /**
                     *  Var
                     *  ---
                     */
                    var     _handler            =   $(this).attr( 'data-handler' ),

                            _type               =   $(this).attr( 'data-type' ),

                            _current_value      =   $(this).attr( 'data-value' ),

                            _id                 =   '#' + _handler,

                            _max_limit          =   parseInt( $( this ).attr( 'data-max-limit' ) ),

                            _max_value_set      =   parseInt( $( _id + '-input-data' ).val() );

                    /**
                     *  Create Slider
                     *  -------------
                     */
                    $( this ).slider( {

                        range           :   "min",

                        max             :   parseInt( _max_limit ),

                        value           :   parseInt( _current_value ),

                        slide: function (event, ui) {

                            /**
                             *  Slider Value
                             *  ------------
                             */
                            $( _id + '-input-data' ).val( ui.value );

                            $( _id + '-counter' ).html( ui.value );
                        },

                        stop(){

                            /**
                             *  Add Filter
                             *  ----------
                             */
                            SDWeddingDirectory_Search_Venue.apply_filter( {

                                id      :   _id,

                                name    :   _handler,

                                action  :   'add',

                                type    :   _type

                            } );

                            /**
                             *  Have Checked Value ?
                             *  --------------------
                             */
                            if( $( _id + '-input-data' ).length ){

                                /**
                                 *  Update Query
                                 *  ------------
                                 */
                                SDWeddingDirectory_Search_Venue.update_query( _handler, $( _id + '-input-data' ).val() );

                                /**
                                 *  Find Result
                                 *  -----------
                                 */
                                SDWeddingDirectory_Search_Venue.find_venue();
                            }

                            else{

                                /**
                                 *  Update Query
                                 *  ------------
                                 */
                                SDWeddingDirectory_Search_Venue.update_query( _handler, '' );

                                /**
                                 *  Update the Data in Input Fields
                                 *  -------------------------------
                                 */
                                $( _id + '-input-data' ).val( '' );

                                /**
                                 *  Find Result
                                 *  -----------
                                 */
                                SDWeddingDirectory_Search_Venue.find_venue();
                            }


                            SDWeddingDirectory_Search_Venue.find_venue();
                        }
                    });

                } );
            }
        },

        /**
         *  Each Select Option Checked to find venue
         *  ------------------------------------------
         */
        get_select_data: function(){

            /**
             *  Have Class ?
             *  ------------
             */
            if( $( '.select-data' ).length ){

                $( '.select-data' ).map( function(){

                    var     _id             =   '#' + $(this).attr( 'data-handler' ),

                            _type           =   $(this).attr( 'data-type' ),

                            _filter_name    =   $(this).attr( 'data-handler' );

                    /**
                     *  ============================================
                     *  Make sure this checkbox is string collection
                     *  ============================================
                     */
                    if( _type == 'select' ){

                        /**
                         *  Event Check
                         *  -----------
                         */
                        $( _id + ' select' ).on( 'change', function(e){

                            /**
                             *  Wait
                             *  ----
                             */
                            e.preventDefault();

                            /**
                             *  Have Checked Value ?
                             *  --------------------
                             */
                            if( $( _id + ' select option:selected' ).length ){

                                /**
                                 *  Collection
                                 *  ==========
                                 */
                                var     collection  =   $( _id + ' select option:selected' ).val();

                                /**
                                 *  Update the Data in Input Fields
                                 *  -------------------------------
                                 */
                                $( _id + '-input-data' ).val( collection );

                                /**
                                 *  Update Query
                                 *  ------------
                                 */
                                SDWeddingDirectory_Search_Venue.update_query( _filter_name, collection );

                                /**
                                 *  Find Result
                                 *  -----------
                                 */
                                SDWeddingDirectory_Search_Venue.find_venue();
                            }

                            else{

                                /**
                                 *  Update Query
                                 *  ------------
                                 */
                                SDWeddingDirectory_Search_Venue.update_query( _filter_name, '' );

                                /**
                                 *  Update the Data in Input Fields
                                 *  -------------------------------
                                 */
                                $( _id + '-input-data' ).val( '' );

                                /**
                                 *  Find Result
                                 *  -----------
                                 */
                                SDWeddingDirectory_Search_Venue.find_venue();
                            }

                            /**
                             *  Add Filter
                             *  ----------
                             */
                            SDWeddingDirectory_Search_Venue.apply_filter( {

                                id      :   _id,

                                name    :   _filter_name,

                                action  :   'add',

                                type    :   _type

                            } );

                        });
                    }

                } );
            }
        },

        /**
         *  Check Checkbox Data on Checked to update data with find result
         *  --------------------------------------------------------------
         *  @link - https://stackoverflow.com/questions/8465821/find-all-unchecked-checkbox-in-jquery#answers-header
         *  --------------------------------------------------------------------------------------------------------
         */
        get_checkbox_data: function(){

            /**
             *  Have Class ?
             *  ------------
             */
            if( $( '.checkbox-data' ).length ){

                $( '.checkbox-data' ).map( function(){

                    var     _id             =   '#' + $(this).attr( 'data-handler' ),

                            _type           =   $(this).attr( 'data-type' ),

                            _filter_name    =   $(this).attr( 'data-handler' );

                    /**
                     *  ============================================
                     *  Make sure this checkbox is string collection
                     *  ============================================
                     */
                    if( _type == 'string' ){

                        /**
                         *  Event Check
                         *  -----------
                         */
                        $( _id + ' input' ).on( 'change', function(e){

                            /**
                             *  Wait
                             *  ----
                             */
                            e.preventDefault();

                            /**
                             *  Have Checked Value ?
                             *  --------------------
                             */
                            if( $( _id + ' input:checkbox:checked' ).length ){

                                /**
                                 *  Collection
                                 *  ==========
                                 */
                                var     collection  =   $( _id + ' input:checkbox:checked' ).map(function(){

                                                            return $( this ).val();

                                                        }).get();

                                /**
                                 *  Update the Data in Input Fields
                                 *  -------------------------------
                                 */
                                $( _id + '-input-data' ).val( collection );

                                /**
                                 *  Update Query
                                 *  ------------
                                 */
                                SDWeddingDirectory_Search_Venue.update_query( _filter_name, collection );

                                /**
                                 *  Find Result
                                 *  -----------
                                 */
                                SDWeddingDirectory_Search_Venue.find_venue();
                            }

                            else{

                                /**
                                 *  Update Query
                                 *  ------------
                                 */
                                SDWeddingDirectory_Search_Venue.update_query( _filter_name, '' );

                                /**
                                 *  Update the Data in Input Fields
                                 *  -------------------------------
                                 */
                                $( _id + '-input-data' ).val( '' );

                                /**
                                 *  Find Result
                                 *  -----------
                                 */
                                SDWeddingDirectory_Search_Venue.find_venue();
                            }

                            /**
                             *  Add Filter
                             *  ----------
                             */
                            SDWeddingDirectory_Search_Venue.apply_filter( {

                                id      :   _id,

                                name    :   _filter_name,

                                action  :   'add',

                                type    :   _type

                            } );

                        });
                    }

                    /**
                     *  ============================================
                     *  Make sure this checkbox is single collection
                     *  ============================================
                     */
                    if( _type == 'single' ){

                        $( _id + ' input' ).on( 'change', function( e ){

                            e.preventDefault();

                            /**
                             *  Single select behavior
                             *  ----------------------
                             */
                            if( $(this).is( ':checked' ) ){

                                $( _id + ' input:checkbox' ).not( this ).removeAttr( 'checked' );
                            }

                            /**
                             *  Update input + query
                             *  --------------------
                             */
                            if( $( _id + ' input:checkbox:checked' ).length ){

                                var single_value = $( _id + ' input:checkbox:checked' ).first().val();

                                $( _id + '-input-data' ).val( single_value );

                                SDWeddingDirectory_Search_Venue.update_query( _filter_name, single_value );

                                SDWeddingDirectory_Search_Venue.find_venue();

                            }else{

                                $( _id + '-input-data' ).val( '' );

                                SDWeddingDirectory_Search_Venue.update_query( _filter_name, '' );

                                SDWeddingDirectory_Search_Venue.find_venue();
                            }

                            /**
                             *  Add Filter
                             *  ----------
                             */
                            SDWeddingDirectory_Search_Venue.apply_filter( {

                                id      :   _id,

                                name    :   _filter_name,

                                action  :   'add',

                                type    :   _type
                            } );

                        });
                    }

                    /**
                     *  ===============================
                     *  Make sure it's range collection
                     *  ===============================
                     */
                    else if( _type == 'range' ){

                        /**
                         *  Event Check
                         *  -----------
                         */
                        $( _id + ' input' ).on( 'change', function(e){

                            /**
                             *  Wait
                             *  ----
                             */
                            e.preventDefault();

                            /**
                             *  Var
                             *  ---
                             */
                            var     query_param     =   [],

                                    collection      =   [];

                            /**
                             *  Get Min and Max
                             *  ---------------
                             */
                            if( $( _id + ' input:checkbox:checked' ).length ){

                                $( _id + ' input:checkbox:checked' ).map(function(){

                                    collection.push( $( this ).attr( 'data-min' ) );

                                    collection.push( $( this ).attr( 'data-max' ) );

                                    query_param.push( $( this ).val() );

                                } );

                                /**
                                 *  Have Seating Capacity ?
                                 *  -----------------------
                                 */
                                if( $.isEmptyObject( collection ) ){

                                    $( _id + '-input-min' ).val( '' );

                                    $( _id + '-input-max' ).val( '' );

                                    /**
                                     *  Update Query
                                     *  ------------
                                     */
                                    SDWeddingDirectory_Search_Venue.update_query( _filter_name, '' );

                                    /**
                                     *  Find Result
                                     *  -----------
                                     */
                                    SDWeddingDirectory_Search_Venue.find_venue();
                                }

                                else{

                                    $( _id + '-input-min' ).val( Math.min.apply(Math,collection) );

                                    $( _id + '-input-max' ).val( Math.max.apply(Math,collection) );

                                    /**
                                     *  Update Query
                                     *  ------------
                                     */
                                    SDWeddingDirectory_Search_Venue.update_query( _filter_name, query_param );

                                    /**
                                     *  Find Result
                                     *  -----------
                                     */
                                    SDWeddingDirectory_Search_Venue.find_venue();
                                }
                            }

                            else{

                                $( _id + '-input-min' ).val( '' );

                                $( _id + '-input-max' ).val( '' );

                                /**
                                 *  Update Query
                                 *  ------------
                                 */
                                SDWeddingDirectory_Search_Venue.update_query( _filter_name, '' );

                                /**
                                 *  Find Result
                                 *  -----------
                                 */
                                SDWeddingDirectory_Search_Venue.find_venue();
                            }

                            /**
                             *  Add Filter
                             *  ----------
                             */
                            SDWeddingDirectory_Search_Venue.apply_filter( {

                                id      :   _id,

                                name    :   _filter_name,

                                action  :   'add',

                                type    :   _type

                            } );

                        });
                    }

                } );
            }
        },

        /**
         *  Have Filters ?
         *  --------------
         */
        active_filter: function(){

            /**
             *  Make sure now, have any filter available ?
             *  ------------------------------------------
             */
            if( $( '.active-filters-widget a' ).length ){

                $( '#active-filters-section' ).removeClass( 'd-none' );

            }else{

                $( '#active-filters-section' ).addClass( 'd-none' );
            }
        },

        /**
         *  Active Filter - Removed
         *  -----------------------
         */
        remove_filter: function(){

            /**
             *  Have Active Filter ?
             *  --------------------
             */
            if( $( '.active-filters-widget a' ).length ){

                /**
                 *  When click event pass
                 *  ---------------------
                 */
                $( '.active-filters-widget a' ).off().one( 'click', function( e ){

                    /**
                     *  Event Start
                     *  -----------
                     */
                    e.preventDefault();

                    /**
                     *  Var
                     *  ---
                     */
                    var     filter_name     =   $(this).attr( 'data-handler' ),

                            filter_id       =   '#' + filter_name,

                            filter_value    =   $(this).attr( 'data-value' ),

                            filter_type     =   $(this).attr( 'data-type' ),

                            string_data     =   '',

                            collection      =   [],

                            query_param     =   [];

                        /**
                         *  Make sure it's range type
                         *  -------------------------
                         */
                        if( filter_type == 'range' ){

                            /**
                             *  Get Min and Max
                             *  ---------------
                             */
                            if( $( filter_id + ' input:checkbox:checked' ).length ){

                                $( filter_id + ' input:checkbox:checked' ).map(function(){

                                    if( $(this).val() ==  filter_value ){

                                        $(this).removeAttr( 'checked' )
                                    }

                                } );

                                $( filter_id + ' input:checkbox:checked' ).map(function(){

                                    collection.push( $( this ).attr( 'data-min' ) );

                                    collection.push( $( this ).attr( 'data-max' ) );

                                    query_param.push( $( this ).val() );

                                } );

                                /**
                                 *  Have Seating Capacity ?
                                 *  -----------------------
                                 */
                                if( $.isEmptyObject( collection ) ){

                                    $( filter_id + '-input-min' ).val( '' );

                                    $( filter_id + '-input-max' ).val( '' );

                                    /**
                                     *  Update Query
                                     *  ------------
                                     */
                                    SDWeddingDirectory_Search_Venue.update_query( filter_name, '' );

                                }else{

                                    $( filter_id + '-input-min' ).val( Math.min.apply(Math,collection) );

                                    $( filter_id + '-input-max' ).val( Math.max.apply(Math,collection) );

                                    /**
                                     *  Update Query
                                     *  ------------
                                     */
                                    SDWeddingDirectory_Search_Venue.update_query( filter_name, query_param );
                                }

                            }else{

                                $( filter_id + '-input-min' ).val( '' );

                                $( filter_id + '-input-max' ).val( '' );

                                /**
                                 *  Update Query
                                 *  ------------
                                 */
                                SDWeddingDirectory_Search_Venue.update_query( filter_name, '' );
                            }
                        }

                        /**
                         *  Make sure it's string type
                         *  --------------------------
                         */
                        else if( filter_type == 'string' ){

                            /**
                             *  Removed Checked
                             *  ---------------
                             */
                            if( $( filter_id + ' input:checkbox:checked' ).length ){

                                /**
                                 *  Remove Checked If Found
                                 *  -----------------------
                                 */
                                $( filter_id + ' input:checkbox:checked' ).map( function(){

                                    if( $(this).val() ==  filter_value ){

                                        $(this).removeAttr( 'checked' )
                                    }

                                } );

                                /**
                                 *  Have Any Checked Data ?
                                 *  -----------------------
                                 */
                                if( $( filter_id + ' input:checkbox:checked' ).length ){

                                    /**
                                     *  Collection IDs
                                     *  --------------
                                     */
                                    var     string_data     =   $( filter_id + ' input:checkbox:checked' ).map( function(){

                                                                    return $(this).val();

                                                                } ).get();

                                    /**
                                     *  Update in input field
                                     *  ---------------------
                                     */
                                    $( filter_id + '-input-data' ).val( string_data );

                                }else{

                                    $( filter_id + '-input-data' ).val( '' );
                                }
                            }

                            /**
                             *  Update Query
                             *  ------------
                             */
                            SDWeddingDirectory_Search_Venue.update_query(

                                /**
                                 *  1. Data Query ID
                                 *  ----------------
                                 */
                                filter_name,

                                /** 
                                 *  2. Query Replace
                                 *  ----------------
                                 */
                                SDWeddingDirectory_Search_Venue.query_replace( filter_name, filter_value )
                            );
                        }

                        /**
                         *  Make sure it's single type
                         *  -------------------------
                         */
                        else if( filter_type == 'single' ){

                            if( $( filter_id + ' input:checkbox:checked' ).length ){

                                $( filter_id + ' input:checkbox' ).removeAttr( 'checked' );

                                $( filter_id + '-input-data' ).val( '' );
                            }

                            SDWeddingDirectory_Search_Venue.update_query( filter_name, '' );
                        }

                        /**
                         *  Make sure it's select type
                         *  --------------------------
                         */
                        else if( filter_type == 'select' ){

                            /**
                             *  Removed Checked
                             *  ---------------
                             */
                            if( $( filter_id + ' select option:selected' ).length ){

                                /**
                                 *  Remove Checked If Found
                                 *  -----------------------
                                 */
                                $( filter_id + ' select option' ).removeAttr( 'selected' );

                                /**
                                 *  Update in input field
                                 *  ---------------------
                                 */
                                $( filter_id + '-input-data' ).val( '' );
                            }

                            /**
                             *  Update Query
                             *  ------------
                             */
                            SDWeddingDirectory_Search_Venue.update_query(

                                /**
                                 *  1. Data Query ID
                                 *  ----------------
                                 */
                                filter_name,

                                /** 
                                 *  2. Query Replace
                                 *  ----------------
                                 */
                                SDWeddingDirectory_Search_Venue.query_replace( filter_name, filter_value )
                            );
                        }

                        /**
                         *  Make sure it's select item type
                         *  -------------------------------
                         */
                        else if( filter_type == 'select-item' ){

                            /**
                             *  Removed Checked
                             *  ---------------
                             */
                            if( $( filter_id + '-input-data' ).length ){

                                /**
                                 *  Input value null
                                 *  ----------------
                                 */
                                $( filter_id + '-input' ).val( '' );

                                $( filter_id + '-input-data' ).val( '' );

                                var  target_a   =   $( filter_id ).find( 'a[data-location-id="'+ filter_value +'"]' ).attr( 'data-collection' );

                                /**
                                 *  JSON
                                 *  ----
                                 */
                                var     data_json       =       $.parseJSON( target_a );

                                /**
                                 *  Update Input Hidden Fields
                                 *  --------------------------
                                 */
                                $.map( data_json, function( input_value, input_id ){

                                    if( $( 'input[name=' + input_id + ']' ).length ){

                                        if( filter_value == input_value ){

                                            $( 'input[name=' + input_id + ']' ).val( '' );
                                        }
                                    }

                                } );

                                /**
                                 *  Update Hidden Input 
                                 *  -------------------
                                 */
                                if( $( filter_id ).length ){

                                    $( filter_id ).val( '' );
                                }

                                $( '#input-collapse-' + filter_name + ' a' ).removeClass( 'active' );
                            }

                            /**
                             *  Update Query
                             *  ------------
                             */
                            SDWeddingDirectory_Search_Venue.update_query(

                                /**
                                 *  1. Data Query ID
                                 *  ----------------
                                 */
                                filter_name,

                                /** 
                                 *  2. Query Replace
                                 *  ----------------
                                 */
                                SDWeddingDirectory_Search_Venue.query_replace( filter_name, filter_value )
                            );
                        }

                        /**
                         *  Make sure it's select item type
                         *  -------------------------------
                         */
                        else if( filter_type == 'range-slider' ){

                            /**
                             *  Have Range Type checkbox is checked ?
                             *  -------------------------------------
                             */
                            if( $( filter_id + '-input-data' ).length ){

                                /**
                                 *  Removed All Input as Checked
                                 *  ----------------------------
                                 */
                                $( filter_id + '-input-data' ).val( '0' );

                                /**
                                 *  Removed All Input as Checked
                                 *  ----------------------------
                                 */
                                $( filter_id + '-radius' ).attr( 'data-value', parseInt( '0' ) );

                                /**
                                 *  Removed All Input as Checked
                                 *  ----------------------------
                                 */
                                $( filter_id + '-counter' ).html( parseInt( '0' ) );
                            }

                            /**
                             *  Load Slider
                             *  -----------
                             */
                            SDWeddingDirectory_Search_Venue.range_slider();

                            /**
                             *  Update Query
                             *  ------------
                             */
                            SDWeddingDirectory_Search_Venue.update_query(

                                /**
                                 *  1. Data Query ID
                                 *  ----------------
                                 */
                                filter_name,

                                /** 
                                 *  2. Query Replace
                                 *  ----------------
                                 */
                                SDWeddingDirectory_Search_Venue.query_replace( filter_name, '' )
                            );
                        }

                        /**
                         *  Make sure it's Calender type
                         *  ----------------------------
                         */
                        else if( filter_type == 'calender' ){

                            var     new_collection      =   '',

                                    handler             =   $( filter_id + '-input-data' ).val().split( ',' );

                            /**
                             *  Get Values
                             *  ----------
                             */
                            if( $( handler ).length ){

                                /**
                                 *  Handler
                                 *  -------
                                 */
                                $.each( handler, function( index, value ){

                                    if ( value === filter_value ){

                                        handler.splice( index, 1 );
                                    }

                                } );

                                var new_collection  =

                                $( handler ).map( function( index, value ){

                                    return      value;

                                } ).get().join( ',' );

                                /**
                                 *  New Collection
                                 *  --------------
                                 */
                                $( filter_id + '-calender' ).attr( 'data-date', new_collection );

                                $( filter_id + '-input-data' ).val( new_collection );

                                /**
                                 *  Create DatePicker
                                 *  -----------------
                                 */
                                $( filter_id + '-calender' ) .datepicker( 'destroy' );
                            }

                            /**
                             *  New Calender Call
                             *  -----------------
                             */
                            SDWeddingDirectory_Search_Venue.calendar_availability();

                            /**
                             *  Update Query
                             *  ------------
                             */
                            SDWeddingDirectory_Search_Venue.update_query(

                                /**
                                 *  1. Data Query ID
                                 *  ----------------
                                 */
                                filter_name,

                                /** 
                                 *  2. Query Replace
                                 *  ----------------
                                 */
                                SDWeddingDirectory_Search_Venue.query_replace( filter_name, '' )
                            );
                        }

                        /**
                         *  Type not found
                         *  --------------
                         */
                        else{

                            console.log( 'Note : Filter Type Not Found..' );
                        }

                        /** 
                         *  Removed Filter
                         *  --------------
                         */
                        $( this ).remove();

                        /**
                         *  Make sure now, have any filter available ?
                         *  ------------------------------------------
                         */
                        if( ! $( '.active-filters-widget a' ).length ){

                            $( '#active-filters-section' ).addClass( 'd-none' );
                        }

                        /**
                         *  Find Venue
                         *  ------------
                         */
                        SDWeddingDirectory_Search_Venue.find_venue();
                } );
            }
        },

        /**
         *  Get Query
         *  ---------
         */
        get_query: function(){

            var url = document.location.href;

            var qs = url.substring(url.indexOf('?') + 1).split('&');

            for(var i = 0, result = {}; i < qs.length; i++){

                qs[i] = qs[i].split('=');

                result[qs[i][0]] = decodeURIComponent(qs[i][1]);
            }

            return result;
        },

        /**
         *  Value Get vai Query Parameter
         *  -----------------------------
         */
        getUrlParameter: function (name){

            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");

            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),

            results = regex.exec(location.search);

            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        },

        /**
         *  String => Array => Unset Value => Again Create String Return
         *  ------------------------------------------------------------
         */
        query_replace: function( data_query, remove ){

            /**
             *  1. Query String ID Value
             *  ------------------------
             */
            var query_name_value = SDWeddingDirectory_Search_Venue.getUrlParameter( data_query )

            var strings_data = query_name_value.split(',');

            strings_data.splice( $.inArray( remove, strings_data), 1 );

            return strings_data;
        },

        /**
         *  Working
         *  -------
         *  https://stackoverflow.com/questions/5999118/how-can-i-add-or-update-a-query-string-parameter#answer-41542008
         *  ------------------------------------------------------------------------------------------------------------
         */
        update_query: function( key, value ) {

            if ('URLSearchParams' in window) {

                var searchParams = new URLSearchParams(window.location.search)

                searchParams.set( key, value );

                var newRelativePathQuery = window.location.pathname + '?' + searchParams.toString();
                
                history.pushState(null, '', newRelativePathQuery);
            }
        },

        /**
         *  Remove : Query Parameter selected name
         *  --------------------------------------
         *  @link - https://gist.github.com/simonw/9445b8c24ddfcbb856ec#gistcomment-3324009
         *  -------------------------------------------------------------------------------
         */
        remove_query: function( query ){

            const url = new URL(location);

            url.searchParams.delete( query );

            history.replaceState(null, null, url);
        },

        /**
         *  Get all query string with array formate
         *  ---------------------------------------
         *  @link - https://fellowtuts.com/jquery/get-query-string-values-url-parameters-javascript/#crayon-6424d2880aa35662910140
         *  ----------------------------------------------------------------------------------------------------------------------
         */
        query_to_obj: function(){

            var url = document.location.href;
            var qs = url.substring(url.indexOf('?') + 1).split('&');
            for(var i = 0, result = {}; i < qs.length; i++){
                qs[i] = qs[i].split('=');
                result[qs[i][0]] = decodeURIComponent(qs[i][1]);
            }
            return result;
        },

        /**
         *  Query parameter value get
         *  -------------------------
         */
        query_key_value: function(key){

            var obj     =   SDWeddingDirectory_Search_Venue.query_to_obj();

            return  obj.key;
        },

        /**
         *  1. Search result page events 
         *  ----------------------------
         */
        search_result_page_event: function(){

            /**
             *  If we get Search Result Form ID to FIRE EVENTS
             *  ----------------------------------------------
             */
            if( $('form.sdweddingdirectory-result-page').length ){

                /**
                 *  3. Search Result Form Submit
                 *  ----------------------------
                 */
                $(document).on('submit', 'form.sdweddingdirectory-result-page', function(e){

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
                });

                /**
                 *  Each Checkbox Checked to find venue
                 *  -------------------------------------
                 */
                SDWeddingDirectory_Search_Venue.get_checkbox_data();

                /**
                 *  Each Select Option Checked to find venue
                 *  ------------------------------------------
                 */
                SDWeddingDirectory_Search_Venue.get_select_data();

                /**
                 *  Select Item
                 *  -----------
                 */
                SDWeddingDirectory_Search_Venue.get_select_item_data();

                /**
                 *  Have active filters ?
                 *  =====================
                 */
                SDWeddingDirectory_Search_Venue.remove_filter();

                /**
                 *  Clear Filter
                 *  ------------
                 */
                SDWeddingDirectory_Search_Venue.clear_filter();

                /**
                 *  View More Filtes
                 *  ----------------
                 */
                SDWeddingDirectory_Search_Venue.view_more_filter();

                /**
                 *  Range Slider
                 *  ------------
                 */
                SDWeddingDirectory_Search_Venue.range_slider();

                /**
                 *  Calender Availibility
                 *  ---------------------
                 */
                SDWeddingDirectory_Search_Venue.calendar_availability();

                /**
                 *  By Default Calling
                 *  ------------------
                 */
                // SDWeddingDirectory_Search_Venue.find_venue(); // if id found to call
            }
        },

        /**
         *  Pagination wise venue load
         *  ----------------------------
         */
        pagination_item_load: function(){

            if( $( '.sdweddingdirectory_venue_pagination_number .pagination_number' ).length ){

                $( '.sdweddingdirectory_venue_pagination_number .pagination_number' ).on( 'click', function( e ){

                    $( 'input[name=paged]' ).val( $( this ).attr( 'data-value' ) );

                    SDWeddingDirectory_Search_Venue.remove_query( 'paged' );

                    SDWeddingDirectory_Search_Venue.find_venue();

                    e.preventDefault();

                } );
            }
        },

        /**
         *  Toggle Loading State
         *  --------------------
         */
        toggle_loading: function( isLoading ){

            var loaderHtml = '<div class="loader-ajax-container-wrap"><div class="loader-ajax-container"><div class="loader-ajax"></div></div></div>';

            if( isLoading ){

                $( '#venue_search_result' ).addClass( 'sdweddingdirectory-results-loading' );

                $( '.venue-filter-section input, .venue-filter-section select, .venue-filter-section button' ).prop( 'disabled', true );

                if( $( '#venue_search_result' ).length && ! $( '#venue_search_result' ).prev( '.loader-ajax-container-wrap' ).length ){

                    $( loaderHtml ).insertBefore( $( '#venue_search_result' ) );
                }

            }else{

                $( '#venue_search_result' ).removeClass( 'sdweddingdirectory-results-loading' );

                $( '.venue-filter-section input, .venue-filter-section select, .venue-filter-section button' ).prop( 'disabled', false );

                $( '.loader-ajax-container-wrap' ).remove();
            }
        },

        /**
         *  Update Filter Count Badge
         *  -------------------------
         */
        update_filter_count: function(){

            if( ! $( '#venue-filter-count' ).length ){

                return;
            }

            var count = $( '.active-filters-widget a' ).length;

            if( count > 0 ){

                $( '#venue-filter-count' ).text( count ).removeClass( 'd-none' );

            }else{

                $( '#venue-filter-count' ).addClass( 'd-none' ).text( '' );
            }
        },

        /**
         *  Restore Collapse State
         *  ----------------------
         */
        restore_collapse_state: function(){

            $( '.venue-filter-section .collapse' ).each( function(){

                var collapseId = $( this ).attr( 'id' );

                if( ! collapseId ){

                    return;
                }

                var state = sessionStorage.getItem( 'sdwd_filter_' + collapseId );

                if( state === 'hide' ){

                    $( this ).removeClass( 'show' );

                }else if( state === 'show' ){

                    $( this ).addClass( 'show' );
                }
            } );

            $( document ).off( 'shown.bs.collapse.sdwd hidden.bs.collapse.sdwd' ).on( 'shown.bs.collapse.sdwd hidden.bs.collapse.sdwd', '.venue-filter-section .collapse', function(){

                var collapseId = $( this ).attr( 'id' );

                if( collapseId ){

                    sessionStorage.setItem( 'sdwd_filter_' + collapseId, $( this ).hasClass( 'show' ) ? 'show' : 'hide' );
                }
            } );
        },

        /**
         *  3. Search Venue Handler
         *  -------------------------
         */
        find_venue: function(){

            /**
             *  1. Empty Result Page
             *  --------------------
             */
            if( $( '#venue_search_result' ).length ){

                /**
                 *  Empty Result
                 *  ------------
                 */
                $( '#venue_search_result' ).empty();

                /**
                 *  Update HTML
                 *  -----------
                 */
                $( '<div class="loader-ajax-container-wrap"><div class="loader-ajax-container"><div class="loader-ajax"></div></div></div>' ).insertBefore( $( '#venue_search_result' ) );
            }

            /**
             *  Loading State
             *  -------------
             */
            SDWeddingDirectory_Search_Venue.toggle_loading( true );

            var activeLayout = parseInt( $( ".switch_layout.active" ).attr( 'data-layout' ) );

            if( isNaN( activeLayout ) && $( 'input[name=layout]' ).length ){

                activeLayout = parseInt( $( 'input[name=layout]' ).val() );
            }

            if( isNaN( activeLayout ) ){

                activeLayout = 0;
            }

            /**
             *  4. AJAX Start
             *  -------------
             */
            $.ajax({

                type: 'POST',

                dataType: 'json',

                url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                
                data: {

                    /**
                     *  Action of Function memeber
                     *  --------------------------
                     */
                    'action'                    :   'sdweddingdirectory_load_venue_data',

                    /**
                     *  Hidden Fields
                     *  -------------
                     */
                    'cat_id'                    :   $( 'input[name=cat_id]' ).length       ?    $( 'input[name=cat_id]' ).val()       :   '',

                    'region_id'                 :   $( 'input[name=region_id]' ).length    ?    $( 'input[name=region_id]' ).val()    :   '',

                    'state_id'                  :   $( 'input[name=state_id]' ).length     ?    $( 'input[name=state_id]' ).val()     :   '',

                    'location'                  :   $( 'input[name=location]' ).length     ?    $( 'input[name=location]' ).val()     :   '',

                    'city_id'                   :   $( 'input[name=city_id]' ).length      ?    $( 'input[name=city_id]' ).val()      :   '',

                    'latitude'                  :   $( 'input[name=latitude]' ).length     ?    $( 'input[name=latitude]' ).val()     :   '',

                    'longitude'                 :   $( 'input[name=longitude]' ).length    ?    $( 'input[name=longitude]' ).val()    :   '',

                    'city_name'                 :   $( 'input[name=city_name]' ).length    ?    $( 'input[name=city_name]' ).val()    :   '',

                    'region_name'               :   $( 'input[name=region_name]' ).length  ?    $( 'input[name=region_name]' ).val()  :   '',

                    'geoloc'                    :   $( 'input[name=geoloc]' ).length       ?    $( 'input[name=geoloc]' ).val()       :   '',

                    'pincode'                   :   $( 'input[name=pincode]' ).length      ?    $( 'input[name=pincode]' ).val()      :   '',

                    /**
                     *  Venue Sub Category Data
                     *  -------------------------
                     */
                    'sub-category'              :   $( '#sub-category' + '-input-data' ).val(),

                    /**
                     *  Venue Min + Max Price Data
                     *  ----------------------------
                     */
                    'min-price'         :       $( '#price-filter'+ '-input-min' ).length && $( '#price-filter'+ '-input-min' ).val() !== 0 

                                                ?   $( '#price-filter'+ '-input-min' ).val()

                                                :   '',

                    'max-price'         :       $( '#price-filter'+ '-input-max' ).length && $( '#price-filter'+ '-input-max' ).val() !== 0

                                                ?   $( '#price-filter'+ '-input-max' ).val()

                                                :   '',
                    /**
                     *  Venue Seat Capacity Data
                     *  --------------------------
                     */
                    'min-seat'                  :   $( '#capacity'+'-input-min' ).length && $( '#capacity'+'-input-min' ).val() !== 0 

                                                ?   $( '#capacity'+'-input-min' ).val()

                                                :   '',

                    'max-seat'                  :   $( '#capacity'+'-input-max' ).length && $( '#capacity'+'-input-max' ).val() !== 0 

                                                ?   $( '#capacity'+'-input-max' ).val()

                                                :   '',

                    /**
                     *  Current page
                     *  ------------
                     */
                    'paged'                     :   $('input[name=paged]').val(),

                    /**
                     *  Venue Layout
                     *  --------------
                     */
                    'layout'                    :   activeLayout,

                    /**
                     *  Sory By
                     *  -------
                     */
                    'sort-by'                   :   $( '#sort-by' + '-input-data' ).val(),

                    /**
                     *  Term Group
                     *  ----------
                     */
                    'term_box_group'            :   SDWeddingDirectory_Search_Venue.term_group_data(),

                    /**
                     *  Availibility Date
                     *  -----------------
                     */
                    'availability'              :   $( '#availability-input-data' ).length

                                                    ?   $( '#availability-input-data' ).val()

                                                    :   ''
                },

                beforeSend: function(){

                    /**
                     *  Before AJAX to find venue in backend removed map + venue data on search result page.
                     *  ----------------------------------------------------------------------------------------
                     */
                    var map_id          =   $( '#map_handler' ).attr( 'data-map-id' ),

                        map_class       =   $( '#map_handler' ).attr( 'data-map-class' );

                    /**
                     *  Venue Search Result Handling Section ID to targe elements
                     *  -----------------------------------------------------------
                     */
                    $( '#map_handler' ).html( '' );

                    $( '#map_handler' ).append( '<div id="'+map_id+'" class="'+map_class+'"></div>' );

                    /**
                     *  Before load the pagination data removed inner data
                     *  --------------------------------------------------
                     */
                    $( '#venue_have_pagination' ).html( '' );

                },
                success: function( PHP_RESPONSE ){

                    SDWeddingDirectory_Search_Venue.toggle_loading( false );

                    /**
                     *  Location Input Update
                     *  ---------------------
                     */
                    if( $( 'input.venue-location' ).length ){

                        /**
                         *  Update ID
                         *  ---------
                         */
                        if( _is_empty( PHP_RESPONSE.location_input_id ) ){

                            $( 'input.venue-location' ).attr( 'data-value-id', PHP_RESPONSE.location_input_id );    
                        }

                        /**
                         *  Update Name
                         *  -----------
                         */
                        if( _is_empty( PHP_RESPONSE.location_input_name ) ){

                            $( 'input.venue-location' ).attr( 'value', PHP_RESPONSE.location_input_name );
                        }
                    }

                    /**
                     *  1. Loader Removed
                     *  -----------------
                     */
                    $('.loader-ajax-container-wrap').remove();

                    /**
                     *  2. Venue Data HTML Update in Document
                     *  ---------------------------------------
                     */
                    $( '#venue_search_result' ).html( PHP_RESPONSE.venue_html_data );

                    /**
                     *  Have Found Result ?
                     *  -------------------
                     */
                    if( _is_empty( PHP_RESPONSE.found_result ) ){

                        $( '#found_venues' ).find( 'span' ).html( PHP_RESPONSE.found_result );

                        $( '#result-counter' ).find( 'span' ).html( '(' +PHP_RESPONSE.found_result + ')' );

                    }else{

                        $( '#found_venues' ).find( 'span' ).html( PHP_RESPONSE.found_result );

                        $( '#result-counter' ).find( 'span' ).html( '(' +PHP_RESPONSE.found_result + ')' );
                    }

                    /**
                     *  3. Load the Pagination Data
                     *  ---------------------------
                     */
                    if( PHP_RESPONSE.have_pagination !== '' ){

                        $( '#venue_have_pagination' ).html( PHP_RESPONSE.pagination );
                    }

                    /**
                     *  Have Pagination ?
                     *  -----------------
                     */
                    SDWeddingDirectory_Search_Venue.default_script();

                    /**
                     *  Update Filter Count
                     *  -------------------
                     */
                    SDWeddingDirectory_Search_Venue.update_filter_count();
                },

                error: function (xhr, ajaxOptions, thrownError) {

                    SDWeddingDirectory_Search_Venue.toggle_loading( false );

                    console.log( 'SDWeddingDirectory Find Venue Error..' );

                    console.log(xhr.status);

                    console.log(thrownError);

                    console.log(xhr.responseText);
                },

            });
        },

        /**
         *  When site have data
         *  -------------------
         */
        default_script: function(){

            /**
             *  Have Pagination ?
             *  -----------------
             */
            SDWeddingDirectory_Search_Venue.pagination_item_load();

            /**
             *  Map Data : Collection
             *  ---------------------
             */
            var SDWeddingDirectory_Map_Data = new Array();

            /**
             *  Load Map ID
             *  -----------
             */
            SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_id' ]     =  $( '#map_handler' ).attr( 'data-map-id' );

            /**
             *  Map Zoom Level
             *  --------------
             */
            SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_zoom_level' ]  =  parseInt( SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_map_zoom_level );

            /**
             *  1. Defult Map : Latitude
             *  ------------------------
             */
            SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_latitude' ]    = SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_map_latitude;

            /**
             *  2. Defult Map : Longitude
             *  -------------------------
             */
            SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_longitude' ]   = SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_map_longitude;

            /**
             *  InfoBox Parent Class
             *  --------------------
             */
            SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_info_window_parent_class' ] = 'sdweddingdirectory-map-venue-overview';  

            /**
             *  Have venue on page ?
             *  ----------------------
             */
            if( $( '#sdweddingdirectory-find-venue-tab-content .active.show .sdweddingdirectory_venue' ).length ){

                /**
                 *  Map Data
                 *  --------
                 */
                SDWeddingDirectory_Map_Data[ 'sdweddingdirectory_map_data' ]   = SDWeddingDirectory_Search_Venue.sdweddingdirectory_venue_map_data();
            }

            /**
             *  1. Google Map
             *  -------------
             */
            if( typeof SDWeddingDirectory_Google_Map === 'object' ){

                SDWeddingDirectory_Google_Map.google_map_load_venues( SDWeddingDirectory_Map_Data );
            }

            /**
             *  2. Leaflet Map
             *  --------------
             */
            if( typeof SDWeddingDirectory_Leaflet_Map === 'object' ){

                SDWeddingDirectory_Leaflet_Map.leaflet_map_load_venues( SDWeddingDirectory_Map_Data );
            }

            /**
             *  Pagination Object Call
             *  ----------------------
             */
            SDWeddingDirectory_Pagination.sdweddingdirectory_pagination_call();

            /**
             *  Have SDWeddingDirectory Core Object ?
             *  -----------------------------
             */
            if ( typeof SDWeddingDirectory_Elements === 'object' ){

                SDWeddingDirectory_Elements.sdweddingdirectory_select_option();
            }

            /**
             *  Removed map location information on front page after document load
             *  ------------------------------------------------------------------
             */
            if( $('.sdweddingdirectory_venue .d-none').length ){

                $('.sdweddingdirectory_venue .d-none').remove();
            }

            /**
             *  Have active filters ?
             *  =====================
             */
            SDWeddingDirectory_Search_Venue.remove_filter();

            /**
             *  Clear Filter
             *  ------------
             */
            SDWeddingDirectory_Search_Venue.clear_filter();

            /**
             *  Have Verify Badge ?
             *  -------------------
             */
            if ( typeof SDWeddingDirectory_Elements === 'object' ){

                SDWeddingDirectory_Elements.tooltip();
            }

            /**
             *  Have Verify Badge ?
             *  -------------------
             */
            if ( typeof SDWeddingDirectory_Rating === 'object' ){

                SDWeddingDirectory_Rating.init();
            }

            /**
             *  Restore Collapse State
             *  ----------------------
             */
            SDWeddingDirectory_Search_Venue.restore_collapse_state();

            /**
             *  Update Filter Count
             *  -------------------
             */
            SDWeddingDirectory_Search_Venue.update_filter_count();
        },

        /**
         *  Term Group Data
         *  ---------------
         */
        term_group_data : function(){

            var     term_group_data     =   {};

            /**
             *  Have Term Group Available ?
             *  ---------------------------
             */
            if( $( 'input.term_group_meta' ).length ){

                /**
                 *  Collection of Data
                 *  ------------------
                 */
                $( 'input.term_group_meta' ).map( function(){

                    var     input_id            =   $(this).attr( 'id' ),

                            term_slug           =   $( '#' + input_id ).attr( 'data-term-slug' ),

                            term_value          =   $( this ).val();


                            if( term_value != '' ){

                                /**
                                 *  Collection of Term Slug
                                 *  -----------------------
                                 */
                                term_group_data[ term_slug ] =  term_value;
                            }
                } );
            }

            /**
             *  Term Collection
             *  ---------------
             */
            return          term_group_data;
        },

        /**
         *  If Have Map to load this function : Venue Map Information
         *  -----------------------------------------------------------
         */
        sdweddingdirectory_venue_map_data : function(){

            var locations = new Array();

            $('#sdweddingdirectory-find-venue-tab-content .active.show .sdweddingdirectory_venue').map(function( index, value ) {

                locations.push({

                    'id'                        : $(this).attr('id'),

                    'lat'                       : parseFloat( $(this).find('.venue_latitude').val() )

                                                ? parseFloat( $(this).find('.venue_latitude').val() )

                                                : SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_map_latitude,

                    'lng'                       : parseFloat( $(this).find('.venue_longitude').val() )

                                                ? parseFloat( $(this).find('.venue_longitude').val() )

                                                : SDWEDDINGDIRECTORY_AJAX_OBJ.sdweddingdirectory_map_longitude,

                    'url'                       : $(this).find('.venue_single_link').val(),

                    'title'                     : $(this).find('.venue_title').val(),

                    'image'                     : $(this).find('.venue_image').val(),

                    'address'                   : $(this).find('.venue_address').val(),

                    'icon'                      : $(this).find('.venue_category_icon').val(),

                    'category_marker'           : $(this).find('.venue_category_marker').val(),

                    'venue_rating'            : $(this).find('.reviews').html(),

                    'get_popup_data'            : $(this).find( '.get_popup_data' ).val(),

                    // 'venue_review_count'      : $(this).find( '.venue_review_count' ).val(),

                    // 'venue_review_average'    : $(this).find( '.venue_review_average' ).val()

                });
            });

            return locations;
        },

        /**
         *  Calender Availibility
         *  ---------------------
         */
        calendar_availability : function(){

            /**
             *  Have Class ?
             *  ------------
             */
            if( $( '.calender-data' ).length ){

                $( '.calender-data' ).map( function(){

                    var     _id             =   '#' + $(this).attr( 'data-handler' ),

                            _value          =   $( _id + '-input-data' ).val().split(','),

                            _type           =   $(this).attr( 'data-type' ),

                            _filter_name    =   $(this).attr( 'data-handler' );

                    /**
                     *  ============================================
                     *  Make sure this checkbox is string collection
                     *  ============================================
                     */
                    if( _type == 'calender' ){

                        /**
                         *  Create DatePicker
                         *  -----------------
                         */
                        $( _id + '-calender' ) .datepicker( {

                            format              :   'dd/mm/yyyy',

                            multidate           :   true,

                            startDate           :   '0d',

                            maxViewMode         :   2,

                        } );

                        /**
                         *  When Update Date
                         *  ----------------
                         */
                        $( _id + '-calender' ) .on( 'changeDate', function( e ){
                            
                            var collection      =   '';

                            /**
                             *  Make sure have date
                             *  -------------------
                             */
                            if( $( e.dates ).length ){

                                var collection     =   

                                $( e.dates ).map( function( i, j ){

                                    return      SDWeddingDirectory_Search_Venue.date_format( j );

                                } ).get().join( ',' );
                            }

                            /**
                             *  Have Checked Value ?
                             *  --------------------
                             */
                            if( collection !== '' ){

                                /**
                                 *  Update the Data in Input Fields
                                 *  -------------------------------
                                 */
                                $( _id + '-input-data' ).val( collection );

                                /**
                                 *  Update Query
                                 *  ------------
                                 */
                                SDWeddingDirectory_Search_Venue.update_query( _filter_name, collection );

                                /**
                                 *  Find Result
                                 *  -----------
                                 */
                                SDWeddingDirectory_Search_Venue.find_venue();
                            }

                            else{

                                /**
                                 *  Update Query
                                 *  ------------
                                 */
                                SDWeddingDirectory_Search_Venue.update_query( _filter_name, '' );

                                /**
                                 *  Update the Data in Input Fields
                                 *  -------------------------------
                                 */
                                $( _id + '-input-data' ).val( '' );

                                /**
                                 *  Find Result
                                 *  -----------
                                 */
                                SDWeddingDirectory_Search_Venue.find_venue();
                            }

                            /**
                             *  Add Filter
                             *  ----------
                             */
                            SDWeddingDirectory_Search_Venue.apply_filter( {

                                id      :   _id,

                                name    :   _filter_name,

                                action  :   'add',

                                type    :   _type

                            } );

                        } );
                    }

                } );
            }
        },

        /**
         *  Date Format
         *  -----------
         */
        date_format: function( get_date ){

            const date                  =   new Date( get_date );

            const day                   =   date.getDate();

            const month                 =   date.getMonth() + 1;

            const year                  =   date.getFullYear();

            const dayOfWeek             =   date.getDay();

            const daysOfWeek            =   [ "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat" ];

            const formattedDate         =   `${day}/${month}/${year}`;

            return                           formattedDate;
        },

        /**
         *  1. Load Search Venue Script
         *  -----------------------------
         */
        init: function() {

            /**
             *  JS Enabled Class
             *  ---------------
             */
            $( 'body' ).addClass( 'sdweddingdirectory-js' );

            /**
             *  1. Load the search result page script + events
             *  ----------------------------------------------
             */
            this.search_result_page_event();    // load search venue actions

            /**
             *  When site have data
             *  -------------------
             */    
            this.default_script();
        }
    }

    /**
     *  Document is ready to load the script
     *  ------------------------------------
     */
    $(document).ready( function() {   SDWeddingDirectory_Search_Venue.init();  } );

})(jQuery);
