(function($) {

    "use strict";

    var SDWeddingDirectory_Budget = {

        /**
         *  1. Open Budget Form
         *  --------------------
         */
        open_budget_form : function(){

            if ($('#sdweddingdirectory_add_budget_category_sidepanel').length) {

                $('#sdweddingdirectory_add_budget_category_sidepanel').slideReveal({

                    trigger: $("#sdweddingdirectory_budget_category"),
                    
                    position: "right",
                    push: false,
                    overlay: true,
                    width: 375,
                    speed: 450
                });
            }
        },

        /**
         *  2. Edit Budget Form
         *  --------------------
         */
        edit_budget_form : function(){

            if ($('#sdweddingdirectory_edit_budget_category_sidepanel').length) {

                $('#sdweddingdirectory_edit_budget_category_sidepanel').slideReveal({

                    trigger: $(".edit_budget_category"),
                    position: "right",
                    push: false,
                    overlay: true,
                    width: 375,
                    speed: 450
                });
            }
        },

        /**
         *  3. Edit Icon to update form
         *  ---------------------------
         */
        edit_form_fields: function(){

            if( $( '.edit_budget_category' ).length ){

                $( '.edit_budget_category' ).map( function(){

                    $( this ).on( 'click', function(){

                        var category_name       = $(this).attr( 'data-category-name' ),
                            category_overview   = $(this).attr( 'data-category-overview' ),
                            category_id         = $(this).attr( 'data-budget-unique-id' ),
                            category_icon       = $(this).attr( 'data-category-icon' );

                        if( $( '#edit_budget_category_name' ).length ){
                            $( '#edit_budget_category_name' ).val( category_name );
                        }

                        if( $( '#edit_budget_category_overview' ).length ){
                            $( '#edit_budget_category_overview' ).val( category_overview );
                        }

                        if( $( '#edit_budget_unique_id' ).length ){
                            $( '#edit_budget_unique_id' ).val( category_id );
                        }

                        if( $( '#edit_budget_category_icon' ).length ){
                            $( '#edit_budget_category_icon' ).val( category_icon ).trigger('change');
                        }

                    } );

                } );
            }
        },

        /**
         *  4. Add Budget Category in Database
         *  ----------------------------------
         */
        add_new_budget_category: function(){

            if( $( '#sdweddingdirectory_add_budget_category_form' ).length ){

                $(document).on( 'submit', '#sdweddingdirectory_add_budget_category_form', function(e) {

                    var form_id =  '#' + $(this).attr( 'id' );

                    $.ajax({
                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data:       {

                            /**
                             *  PHP Function
                             *  ------------
                             */
                            'action'                        : 'sdweddingdirectory_budget_category_add',

                            /**
                             *  Budget Fields
                             *  -------------
                             */
                            'budget_category_name'          : $( form_id + ' #budget_category_name').val(),
                            'budget_category_overview'      : $( form_id + ' #budget_category_overview').val(),
                            'budget_category_icon'          : $( form_id + ' #budget_category_icon' ).val(),

                            /**
                             *  Security
                             *  --------
                             */
                            'security'                      : $( form_id + ' #add_budget_categor_security' ).val(),
                        },
                        success: function ( PHP_RESPONSE ) {

                            console.log(  PHP_RESPONSE  );

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            location.reload();

                        },
                        beforeSend: function(){

                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Budget' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },
                        complete: function( event, request, settings ){

                        }
                    });

                    e.preventDefault();

                } );
            }
        },

        /** 
         *  5. Edit Budget Category
         *  -----------------------
         */
        edit_new_budget_category: function(){

            if( $( '#sdweddingdirectory_edit_budget_category_form' ).length ){

                $(document).on( 'submit', '#sdweddingdirectory_edit_budget_category_form', function(e) {

                    var form_id =  '#' + $(this).attr( 'id' );

                    $.ajax({
                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data:       {

                            /**
                             *  PHP Function
                             *  ------------
                             */
                            'action'                        : 'sdweddingdirectory_budget_category_edit',

                            /**
                             *  Budget Fields
                             *  -------------
                             */
                            'budget_category_name'          : $( form_id + ' #edit_budget_category_name').val(),
                            'budget_category_overview'      : $( form_id + ' #edit_budget_category_overview').val(),
                            'budget_category_icon'          : $( form_id + ' #edit_budget_category_icon' ).val(),
                            'budget_unique_id'              : $( form_id + ' #edit_budget_unique_id').val(),

                            /**
                             *  Security
                             *  --------
                             */
                            'security'                      : $( form_id + ' #edit_budget_categor_security' ).val(),
                        },
                        success: function ( PHP_RESPONSE ) {

                            console.log(  PHP_RESPONSE  );

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            location.reload();

                        },
                        beforeSend: function(){

                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Budget' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },
                        complete: function( event, request, settings ){

                        }
                    });

                    e.preventDefault();

                } );
            }
        },

        /**
         *  6. Removed Budget Category
         *  --------------------------
         */
        remove_budget_category: function(){

            if( $( '.removed_budget_category' ).length ){

                $( document ).on( 'click', '.removed_budget_category', function(){

                    /**
                     *  Confirmation for Removed This Category
                     *  --------------------------------------
                     */
                    if( ! confirm( $(this).attr( 'data-alert-removed-budget-category' ) ) ){
                        return false;
                    }

                    var budget_unique_id = $(this).attr( 'data-budget-unique-id' );

                    /**
                     *  Removed Process Start
                     *  ---------------------
                     */
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        data: {

                            'action'                    : 'sdweddingdirectory_removed_budget_category',
                            'budget_unique_id'          : budget_unique_id
                        },
                        success: function( PHP_RESPONSE ){

                            /**
                             *  1. Show Alert
                             *  -------------
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  After - 1 Sec page refresh
                             *  --------------------------
                             */
                            setTimeout( function(){ location.reload(); }, 1000 );

                        },
                        beforeSend: function(){

                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Budget' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },
                        complete: function( event, request, settings ){

                        }
                    });


                } );
            }
        },

        /**
         *  7. Add New Row Field in Tab Content
         *  -----------------------------------
         */
        add_new_row_fields_budget: function(){

            if( $( '.add_new_budget_row_fields' ).length ){

                $( document ).on( 'click', '.add_new_budget_row_fields', function(){

                    var budget_unique_id    = $(this).attr( 'data-budget-unique-id' ),

                        empty_input         = true;


                    if( $( '#' + budget_unique_id+' input' ).length ){

                        $( '#' + budget_unique_id+' input' ).map( function( i ){

                            $(this).attr( 'value', $(this).val() );

                            if( $(this).hasClass( 'expense_name' ) ){

                                if( $(this).attr( 'value' ) == '' ){

                                    $( this ).focus();

                                    empty_input = false

                                    return false;
                                }
                            }

                        } );
                    }

                    if( empty_input == true ){

                        /**
                         *  Add Row 
                         *  -------
                         */
                        $( '#' + budget_unique_id ).append( SDWeddingDirectory_Budget.budget_field() );

                        /**
                         *  Save Budget Function call
                         *  -------------------------
                         */
                        SDWeddingDirectory_Budget.save_budget_data();

                        /**
                         *  Removed Row Fields Call
                         *  -----------------------
                         */
                        SDWeddingDirectory_Budget.remove_row_fields_budget();

                    }

                } );
            }
        },

        /**
         *  8. Removed Row Field in Tab Content
         *  -----------------------------------
         */
        remove_row_fields_budget: function(){

            if( $( '.budget_field_delete' ).length ){

                $( document ).on( 'click', '.budget_field_delete', function(e){

                    /**
                     *  Make sure event Call once
                     *  -------------------------
                     */
                    if( $( this ).attr( 'data-event-call' ) == 1 ){

                        return;

                    }else{

                        $( this ).attr( 'data-event-call', '1' );
                    }

                    var budget_json_data = new Array(),
                        budget_unique_id = $(this).closest( 'tbody' ).attr( 'id' );

                    /**
                     *  Removed Field
                     *  -------------
                     */
                    $(this).closest( 'tr' ).remove();

                    /**
                     *  After get data
                     *  --------------
                     */
                    $( '#'+budget_unique_id+' tr' ).map( function(){

                        budget_json_data.push({
                            'expense_name'      : $(this).find( 'input.expense_name' ).val(),
                            'estimate_amount'   : $(this).find( 'input.estimate_amount' ).val(),
                            'actual_amount'     : $(this).find( 'input.actual_amount' ).val(),
                            'paid_amount'       : $(this).find( 'input.paid_amount' ).val(),
                        });

                    } );

                    /**
                     *  Save data in database
                     *  ---------------------
                     */
                    SDWeddingDirectory_Budget.save_fields_in_db( budget_unique_id, budget_json_data );

                    e.preventDefault();

                } );
            }
        },

        /**
         *  9. Save Budget Data in Database
         *  -------------------------------
         */
        save_budget_data: function(){

            if( $( 'tbody' ).length ){

                $( 'tbody input' ).on( 'change', function(e){

                    $(this).attr( 'value', $(this).val() );

                    var budget_json_data = new Array(),

                        budget_unique_id = $(this).closest( 'tbody' ).attr( 'id' );

                        $( '#'+budget_unique_id+' tr' ).map( function(){

                            budget_json_data.push({

                                'expense_name'      : $(this).find( 'input.expense_name' ).val(),

                                'estimate_amount'   : $(this).find( 'input.estimate_amount' ).val(),

                                'actual_amount'     : $(this).find( 'input.actual_amount' ).val(),

                                'paid_amount'       : $(this).find( 'input.paid_amount' ).val(),

                            } );

                        } );

                        /**
                         *  Save The Data in Database
                         *  -------------------------
                         */
                        SDWeddingDirectory_Budget.save_fields_in_db( budget_unique_id, budget_json_data );

                    e.preventDefault();

                } );
            }
        },

        /**
         *  Budget Field Add
         *  ----------------
         */
        budget_field: function() {

            return '<tr>'
                        +  '<td><input type="text"   name="expense"  placeholder=""   class="form-control form-control-sm expense_name"/></td>'
                        +  '<td><input type="number" name="estimate" placeholder="0"  class="form-control form-control-sm estimate_amount" /></td>'
                        +  '<td><input type="number" name="actual"   placeholder="0"  class="form-control form-control-sm actual_amount" /></td>' 
                        +  '<td><input type="number" name="paid"     placeholder="0"  class="form-control form-control-sm paid_amount" /></td>'
                        +  '<td><a href="javascript:" class="action-links budget_field_delete"><i class="fa fa-trash"></i></a></td>'
                    +  '</tr>';
        },

        /**
         *  Random Save AJAX Data
         *  ---------------------
         */
        save_fields_in_db: function( budget_unique_id, budget_json_data ){

            if( $( '#'+budget_unique_id+' input' ).length ){

                var estimate_amount=0,
                    actual_amount=0,
                    paid_amount=0;

                $( '#'+budget_unique_id+ ' input' ).map( function(){

                    if( $( this ).hasClass( 'estimate_amount' ) ){

                        if( $(this).val() !== '' && $(this).val() !== null && $(this).val() !== undefined ){

                            estimate_amount = parseInt( $(this).val() ) + parseInt( estimate_amount );
                        }
                    }

                    if( $( this ).hasClass( 'actual_amount' ) ){

                        if( $(this).val() !== '' && $(this).val() !== null && $(this).val() !== undefined ){

                            actual_amount = parseInt( $(this).val() ) + parseInt( actual_amount );
                        }
                    }

                    if( $( this ).hasClass( 'paid_amount' ) ){

                        if( $(this).val() !== '' && $(this).val() !== null && $(this).val() !== undefined ){

                            paid_amount = parseInt( $(this).val() ) + parseInt( paid_amount );
                        }
                    }

                } );

                /**
                 *  Budget Data Collection Print
                 *  ----------------------------
                 */
                if( $( '#'+budget_unique_id ).closest( '.card-shadow' ).find( '.BUDGET_ESTIMATE_COST' ).length ){

                    var i = SDWeddingDirectory_Elements.price_with_currency( estimate_amount );

                    $( '#'+budget_unique_id ).closest( '.card-shadow' ).find( '.BUDGET_ESTIMATE_COST' ).text( i );
                }

                if( $( '#'+budget_unique_id ).closest( '.card-shadow' ).find( '.BUDGET_FINAL_COST' ).length ){

                    var i = SDWeddingDirectory_Elements.price_with_currency( actual_amount );

                    $( '#'+budget_unique_id ).closest( '.card-shadow' ).find( '.BUDGET_FINAL_COST' ).text( i );
                }

                if( $( '#'+budget_unique_id ).closest( '.card-shadow' ).find( '.BUDGET_PAID_COST' ).length ){

                    var i = SDWeddingDirectory_Elements.price_with_currency( paid_amount );

                    $( '#'+budget_unique_id ).closest( '.card-shadow' ).find( '.BUDGET_PAID_COST' ).text( i );
                }

                /**
                 *  Update Budget Data
                 *  ------------------
                 */
                $.ajax({
                    type        : 'POST',
                    url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                    dataType    : 'json',
                    data:       {

                        /**
                         *  PHP Function
                         *  ------------
                         */
                        'action'                        : 'sdweddingdirectory_budget_data_save',

                        /**
                         *  Budget Fields
                         *  -------------
                         */
                        'budget_json_data'              : JSON.stringify( budget_json_data ),

                        'budget_unique_id'              : budget_unique_id,
                    },
                    success: function ( PHP_RESPONSE ) {

                        /**
                         *  Have length
                         *  -----------
                         */
                        if( $( "#sdweddingdirectory_budget_chart" ).length ){

                            $( "#sdweddingdirectory_budget_chart" ).html( '' );
                        }

                        /**
                         *  Create Budgert Chart Data
                         *  -------------------------
                         */
                        SDWeddingDirectory_Budget.budget_category_chart(

                            /**
                             *  1. Category Amount
                             *  ------------------
                             */
                            PHP_RESPONSE.estimate_amount,

                            /** 
                             * 2. Category Names
                             * -----------------
                             */
                            PHP_RESPONSE.budget_category
                        );

                        /**
                         *  Update Master Budget Summary
                         *  ----------------------------
                         */
                        if( $( '#master_paid_amount' ).length ){

                            $( '#master_paid_amount' ).text( PHP_RESPONSE.master_paid_amount );
                        }
                        if( $( '#master_actual_amount' ).length ){
                            
                            $( '#master_actual_amount' ).text( PHP_RESPONSE.master_actual_amount );
                        }
                        if( $( '#master_pending_amount' ).length ){

                            $( '#master_pending_amount' ).text( PHP_RESPONSE.master_pending_amount );
                        }
                    },
                    beforeSend: function(){

                    },
                    error: function (xhr, ajaxOptions, thrownError) {

                        console.log( 'SDWeddingDirectory_Error_Budget' );
                        console.log(xhr.status);
                        console.log(thrownError);
                        console.log(xhr.responseText);
                    },
                    complete: function( event, request, settings ){

                    }
                });

            }else{

                if( $( '.BUDGET_ESTIMATE_COST' ).length ){ $( '.BUDGET_ESTIMATE_COST' ).text( 0 ); }

                if( $( '.BUDGET_FINAL_COST' ).length ){ $( '.BUDGET_FINAL_COST' ).text( 0 ); }

                if( $( '.BUDGET_PAID_COST' ).length ){ $( '.BUDGET_PAID_COST' ).text( 0 ); }
            }
        },

        /**
         *  10. Load the chart
         *  ------------------
         */
        budget_category_chart: function( a, b ){

            var condition_1 = a !== '' && a !== null && a !== undefined;
            var condition_2 = b !== '' && b !== null && b !== undefined;

            if( $( '#sdweddingdirectory_budget_chart' ).length && a && b ){

                $( '#sdweddingdirectory_budget_chart' ).html( '' );

                var optionDonut = {

                    series: a,

                    labels: b,

                    chart: {
                        type: 'donut',
                        width: '100%',
                        height: 300
                    },

                    dataLabels: {
                        enabled: false,
                    },

                    plotOptions: {
                        pie: {
                            customScale: 0.8,
                            donut: {
                                size: '75%',
                            },
                            offsetY: 0,
                        },
                        stroke: {
                            colors: undefined
                        }
                    },

                    title: {
                        style: {
                            fontSize: '18px'
                        }
                    },

                    legend: {
                        position: 'bottom',
                        offsetY: 0
                    }
                }

                var donut = new ApexCharts(

                  document.querySelector("#sdweddingdirectory_budget_chart"),

                  optionDonut
                );

                donut.render();
            }
        },

        /**
         *  11. Open Budget Estimate Amount Form
         *  ------------------------------------
         */
        estimate_user_budget_form : function(){

            if ($('#sdweddingdirectory_budget_amount_sidepanel').length) {

                $('#sdweddingdirectory_budget_amount_sidepanel').slideReveal({

                    trigger: $("#sdweddingdirectory_budget_amount_button"),
                    
                    position: "right",
                    push: false,
                    overlay: true,
                    width: 375,
                    speed: 450
                });

                $( '#sdweddingdirectory_budget_amount_button' ).on( 'click', function(){

                    if( $( '#sdweddingdirectory_budget_amount' ).length ){

                        $( '#sdweddingdirectory_budget_amount' ).val( $(this).attr( 'data-estimate-budget-amount' ) );
                    }

                } );
            }
        },

        /**
         *  12. Estimate Amount Save Process
         *  --------------------------------
         */
        estimate_budget_submit : function(){

            if ($('#sdweddingdirectory_budget_amount_form').length) {

                $('#sdweddingdirectory_budget_amount_form').on( 'submit', function(e){

                    e.preventDefault();

                    var form_id =  '#' + $(this).attr( 'id' );

                    /**
                     *  Estimate Budget Update Process Start
                     *  ------------------------------------
                     */
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        data: {

                            'action'      : 'sdweddingdirectory_estimate_budget_amount',
                            'security'    : $( form_id+ ' #budget_amount_security' ).val(),

                            /**
                             *  Form data
                             *  ---------
                             */
                            'sdweddingdirectory_budget_amount' : $( form_id+ ' #sdweddingdirectory_budget_amount' ).val()
                        },
                        success: function( PHP_RESPONSE ){

                            /**
                             *  1. Show Alert
                             *  -------------
                             */
                            sdweddingdirectory_alert( PHP_RESPONSE );

                            /**
                             *  After - 1 Sec page refresh
                             *  --------------------------
                             */
                            if( PHP_RESPONSE.reload == true ){

                                setTimeout( function(){ location.reload(); }, 2000 );

                            }else{

                                $( '#sdweddingdirectory_budget_amount' ).focus();
                            }
                        },
                        beforeSend: function(){

                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Budget' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },
                        complete: function( event, request, settings ){

                        }
                    });

                } );
            }
        },

        /**
         *  10. Load Chart Data
         *  -------------------
         */
        load_chart_data: function(){

            /**
             *  Have Chart Data ?
             *  -----------------
             */
            $.ajax({
                type        : 'POST',
                url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                dataType    : 'json',
                data:       {

                    /**
                     *  PHP Function
                     *  ------------
                     */
                    'action'                        : 'budget_calculator_chart_data',
                },
                success: function ( PHP_RESPONSE ) {

                    /**
                     *  10. Load the chart
                     *  ------------------
                     */
                    SDWeddingDirectory_Budget.budget_category_chart(

                        /**
                         *  1. Category Amount
                         *  ------------------
                         */
                        PHP_RESPONSE.estimate_amount,

                        /** 
                         * 2. Category Names
                         * -----------------
                         */
                        PHP_RESPONSE.budget_category
                    );

                },
                beforeSend: function(){

                },
                error: function (xhr, ajaxOptions, thrownError) {

                    console.log( 'SDWeddingDirectory_Error_Budget' );
                    console.log(xhr.status);
                    console.log(thrownError);
                    console.log(xhr.responseText);
                },
                complete: function( event, request, settings ){

                }
            });
        },

        /**
         *  13. Reset Budget Calculator
         *  ---------------------------
         */
        reset_budget: function(){

            if( $( '.reset_budget' ).length ){

                $( '.reset_budget' ).on( 'click', function( e ){

                    /**
                     *  Confirm Message
                     *  ---------------
                     */
                    if( ! confirm( $(this).attr( 'data-alert' ) ) ){

                        return false;
                    }

                    /**
                     *  Event
                     *  -----
                     */
                    e.preventDefault();

                    /**
                     *  Reset Todo
                     *  ----------
                     */
                    $.ajax({
                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data:       { 

                            'action'             :   'sdweddingdirectory_couple_reset_budget',

                            'security'           :   $( '#sdweddingdirectory_couple_budget_security' ).val()
                        },
                        success: function ( PHP_RESPONSE ) {

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            setTimeout( function(){ window.location.reload(); }, 2000 );
                        },
                        beforeSend: function(){

                            $( '.reset_budget' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );
                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Budget' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },
                        complete: function( event, request, settings ){

                            $( '.reset_budget' ).find( 'i' ).remove();
                        }
                    });

                } );   
            }
        },

        /** 
         *  Number of Function used in Budget Category
         *  ------------------------------------------
         */
        init: function() {

            /**
             *  1. Open Budget Form
             *  --------------------
             */
            this.open_budget_form();

            /**
             *  2. Edit Budget Form
             *  --------------------
             */
            this.edit_budget_form();

            /**
             *  3. Edit Icon to update form
             *  ---------------------------
             */
            this.edit_form_fields();

            /**
             *  4. Add Budget Category in Database
             *  ----------------------------------
             */
            this.add_new_budget_category();

            /** 
             *  5. Edit Budget Category
             *  -----------------------
             */
            this.edit_new_budget_category();

            /**
             *  6. Removed Budget Category
             *  --------------------------
             */
            this.remove_budget_category();

            /**
             *  7. Add New Row Field in Tab Content
             *  -----------------------------------
             */
            this.add_new_row_fields_budget();

            /**
             *  8. Removed Row Field in Tab Content
             *  -----------------------------------
             */
            this.remove_row_fields_budget();

            /**
             *  9. Save Budget Data in Database
             *  -------------------------------
             */
            this.save_budget_data();

            /**
             *  10. Load Chart
             *  --------------
             */
            this.load_chart_data();

            /**
             *  11. Estimate Budget Amount
             *  --------------------------
             */
            this.estimate_user_budget_form();

            /**
             *  12. Estimate Amount Save Process
             *  --------------------------------
             */
            this.estimate_budget_submit();

            /**
             *  13. Reset Budget Calculator
             *  ---------------------------
             */
            this.reset_budget();
        },
    };

    $(document).ready( function() {  SDWeddingDirectory_Budget.init(); });

})(jQuery);
