(function($) {

    "use strict";

    var SDWeddingDirectory_Todo = {

        hide_button : function(elm){

            $('#'+elm).attr( 'type', 'button' ).css("cursor", "default").addClass( 'disabled' ).attr( 'aria-disabled', 'true"' );
        },

        show_add_edit_panel : function(){

            /**
             * Add New Todo
             */
            if ($('#sdweddingdirectory_add_todo_form').length) {

                $('#sdweddingdirectory_add_todo_form').slideReveal({

                    trigger: $("#sdweddingdirectory_add_todo_button"),
                    position: "right",
                    push: false,
                    overlay: true,
                    width: 375,
                    speed: 450
                });
            }

           /**
            *  @link http://jsfiddle.net/Nr2zU/
            */
            if( $('#sdweddingdirectory_edit_todo_form').length ){

                $('#sdweddingdirectory_edit_todo_form').slideReveal({

                    trigger: $( this.get_todo_ids() ),
                    position: "right",
                    push: false,
                    overlay: true,
                    width: 375,
                    speed: 450
                });
            }
        },

        get_todo_ids: function(){

            return $(".todo_edit").map(function() {return '#'+this.id;}).get().join(',');
        },

        edit_todo: function(){

            // Edit Todo
            if( $('.todo_edit').length ){

                $( document ).on( 'click', '.todo_edit', function( e ){

                    var unique_id       =   $( this ).attr( 'data-unique-id' ),

                        todo_title      =   $( '#todo_title_'   + unique_id ).text(),

                        todo_overview   =   $( '#todo_overview_'+ unique_id ).text(),

                        todo_date       =   $( '#todo_date_'    + unique_id ).attr( 'data-date' );


                    $( '#edit_todo_title' ).val( todo_title );

                    $( '#edit_todo_unique_id' ).val( unique_id );

                    $( '#edit_todo_date' ).val( todo_date );

                    $( '#edit_todo_overview' ).val( todo_overview );

                    $( 'form#couple_edit_todo_list' ).on( 'submit', function( e ){

                        SDWeddingDirectory_Todo.hide_button( 'edit_todo_list_btn' );

                        $.ajax({
                            type        : 'POST',
                            url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                            dataType    : 'json',
                            data:       { 

                                'action'            :  'sdweddingdirectory_couple_edit_todo_id',
                                'todo_title'        :  $( '#edit_todo_title' ).val(),
                                'todo_date'         :  $( '#edit_todo_date' ).val(),
                                'todo_overview'     :  $( '#edit_todo_overview' ).val(),
                                'todo_unique_id'    :  $( '#edit_todo_unique_id' ).val(),
                            },
                            success: function (data) {
                            
                                location.reload();
                            },
                            beforeSend: function(){

                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory_Error_Todo' );
                                console.log(xhr.status);
                                console.log(thrownError);
                                console.log(xhr.responseText);
                            },
                            complete: function( event, request, settings ){

                            }
                        });
                        e.preventDefault();

                    });
                });
            }
        },

        /**
         *  1. Add New Todo
         *  ---------------
         */
        add_todo: function(){

            // Add Todo
            if( $('#sdweddingdirectory_add_todo_form').length ){

                $( document ).on( 'submit', '#sdweddingdirectory_add_todo_form', function( e ){

                    e.preventDefault();

                    SDWeddingDirectory_Todo.hide_button( 'add_todo_list_btn' );
                        
                        var form_id = '#'+$(this).attr('id');

                        $.ajax({
                            type        : 'POST',
                            url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                            dataType    : 'json',
                            data:       {

                                'action'                :   'sdweddingdirectory_couple_add_todo_list',
                                'security'              : $( '#add_todo_list').val(),
                                'todo_title'            : $( form_id + ' #todo_title').val(),
                                'todo_date'             : $( form_id + ' #todo_date').val(),
                                'todo_overview'         : $( form_id + ' #todo_overview').val(),
                            },
                            success: function (data) {

                                sdweddingdirectory_alert( data );

                                setTimeout(function(){ location.reload(); }, 3000 );
                            },
                            beforeSend: function(){

                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory_Error_Todo' );
                                console.log(xhr.status);
                                console.log(thrownError);
                                console.log(xhr.responseText);
                            },
                            complete: function( event, request, settings ){

                            }
                        });
                });
            }
        },

        delete_todo: function(){

            // Delete Todo
            if( $('.todo_delete').length ){

                $( document ).on( 'click', '.todo_delete', function(e){

                    if( ! confirm( $(this).attr( 'data-todo-removed-alert' ) ) ){
                        return false;
                    }

                    var unique_id = $(this).attr( 'data-unique-id' );

                    $.ajax({
                        type        : 'POST',
                        url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                        dataType    : 'json',
                        data:       { 

                            'action'             :   'sdweddingdirectory_couple_remove_todo_id',
                            'todo_unique_id'     :   unique_id,
                        },
                        success: function (PHP_RESPONSE) {

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            $( '#TODO_DONE_COUNTER, #TODO_DONE_COUNTER' ).html( PHP_RESPONSE.done_task );

                            $( '#number_of_pending_todo, #TODO_PENDING_COUNTER' ).html( PHP_RESPONSE.pending_task );

                            $( '#todo_progressbar' ).attr( 'style', 'width:'+ PHP_RESPONSE.todo_progressbar );

                            $( '#' + unique_id ).remove();
                        },
                        beforeSend: function(){

                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Todo' );
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

        complete_task: function(){

            // Complete Todo Task
            if( $('.todo_task_complete').length ){

                $('.todo_task_complete').map( function(){

                    $( this ).off( 'click' ).one( 'click', function(){

                        var id              =   '#' + $(this).attr( 'id' ),
                            is_completed    =   $( id ).hasClass( 'done' ) ? true : false,
                            parent          =   '#' + $( this ).attr( 'data-id' ),
                            text            =   $( this ).attr( 'data-text' ),
                            alert           =   $(this).attr( 'data-alert' ),
                            alert_div       =   '<div class="row align-items-center alert_show"><div class="col-12 text-center text-white"><p>'+alert+'</p></div></div>';

                        if( is_completed == true ){
                            
                            $( parent ).find( '.todo-title' ).html( text );

                        }else{
                            
                            $( parent ).find( '.hide-column' ).addClass( 'd-none' );
                            $( parent ).addClass( 'alert-bg task-done' );
                            $( parent ).find( '.todo-title' ).html( alert );
                            $( parent ).find( '.todo_show' ).addClass( 'd-none' );
                            $( alert_div ).appendTo( parent );
                        }

                        $.ajax({
                            type        : 'POST',
                            url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                            dataType    : 'json',
                            data:       {

                                'action'      :   'sdweddingdirectory_couple_complete_todo_task',
                                'todo_id'     :   $(this).attr( 'data-id' ),
                            },
                            success: function (RESPONSE) {

                                if( RESPONSE.task_status == '1' ){

                                    $( parent ).find('.to-do-status').empty().html( '<span class="badge badge-success">Complete</span>' );
                                    $( parent ).find( '.hide-column' ).removeClass( 'd-none' );
                                    $( parent ).removeClass( 'alert-bg' );
                                    $( parent ).find( '.todo-title' ).html( '<del>' + text + '</del>' );
                                    $( id ).addClass( 'done' );


                                    $( parent ).find( '.todo_show' ).removeClass( 'd-none' );
                                    $( parent ).find( '.alert_show' ).addClass( 'd-none' ).remove();

                                    $('#number_of_complete_todo_task').html( RESPONSE.complete );
                                    $('#number_of_due_todo_task').html( RESPONSE.due );
                                    $('#number_of_due_pending_task').html( RESPONSE.pending );
                                    $('#number_of_todo_task').html( RESPONSE.counter );
                                }

                                if( RESPONSE.task_status == '0' ){

                                    $( parent ).find('.to-do-status').empty().html( '<span class="badge badge-warning">Pending</span>' );
                                    $( parent ).find( '.todo-title' ).html( text );
                                    $( parent ).removeClass( 'alert-bg task-done' );
                                    $( id ).removeClass( 'done' );

                                    $('#number_of_complete_todo_task').html( RESPONSE.complete );
                                    $('#number_of_due_todo_task').html( RESPONSE.due );
                                    $('#number_of_due_pending_task').html( RESPONSE.pending );
                                    $('#number_of_todo_task').html( RESPONSE.counter );
                                }
                            },
                            beforeSend: function(){

                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                console.log( 'SDWeddingDirectory_Error_Todo' );
                                console.log(xhr.status);
                                console.log(thrownError);
                                console.log(xhr.responseText);
                            },
                            complete: function( event, request, settings ){

                                SDWeddingDirectory_Todo.complete_task();
                            }

                        });

                    } );


                } );
            }
        },

        todo_task_done: function(){

            if( $('.upcoming-task input[type=checkbox]').length ){

                $('.upcoming-task input[type=checkbox]').change(function (e) {
                
                    /**
                     *  When checkbox checked
                     *  ---------------------
                     */
                    if (this.checked) {

                        $(this).attr("checked", "checked").next().addClass("checked-label-text");

                        /**
                         *  Todo Task DONE
                         *  --------------
                         */
                        SDWeddingDirectory_Todo.todo_status_update( $( this ).closest( 'li' ).attr( 'id' ), 1 );

                    } else {

                        $(this).removeAttr("checked").next().removeClass("checked-label-text");

                        /**
                         *  Todo Task Pending
                         *  -----------------
                         */

                        SDWeddingDirectory_Todo.todo_status_update( $( this ).closest( 'li' ).attr( 'id' ), 0 );
                    }

                    e.preventDefault();

                });
            }
        },

        todo_status_update: function( todo_unique_id = '', todo_status = '' ){

            $.ajax({
                type        : 'POST',
                url         : SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,
                dataType    : 'json',
                data:       { 

                    'action'             :   'sdweddingdirectory_couple_complete_todo_task',
                    'todo_unique_id'     :   todo_unique_id,
                    'todo_status'        :   todo_status,
                },
                success: function ( PHP_RESPONSE ) {

                    sdweddingdirectory_alert( PHP_RESPONSE );

                    /**
                     *  If Todo Task is "Pending"
                     *  -------------------------
                     */
                    if( todo_status == 0 ){

                        var button_text = $( '#' + todo_unique_id ).find( '.todo_status_badge' ).attr( 'data-todo-pending-string' );

                        $( '#' + todo_unique_id ).find( '.todo_status_badge' ).removeClass( 'badge-success' ).addClass( 'badge-pending' ).html( button_text );
                    }

                    if( todo_status == 1 ){

                        var button_text = $( '#' + todo_unique_id ).find( '.todo_status_badge' ).attr( 'data-todo-done-string' );

                        $( '#' + todo_unique_id ).find( '.todo_status_badge' ).removeClass( 'badge-pending' ).addClass( 'badge-success' ).html( button_text );
                    }

                    /**
                     *  Todo Complete Counter Update
                     *  ----------------------------
                     */
                    if( $( '.TODO_DONE_COUNTER' ).length ){

                        $( '.TODO_DONE_COUNTER' ).html( PHP_RESPONSE.done_task );  
                    }

                    /**
                     *  Todo Pending Counter Update
                     *  ---------------------------
                     */
                    if( $( '.TODO_PENDING_COUNTER' ).length ){

                        $( '.TODO_PENDING_COUNTER' ).html( PHP_RESPONSE.pending_task );    
                    }

                    /**
                     *  Todo page progressbar update
                     *  ----------------------------
                     */
                    if( $( '#todo_progressbar' ).length ){

                        $( '#todo_progressbar' ).attr( 'style', 'width:'+ PHP_RESPONSE.todo_progressbar );
                    }
                },
                beforeSend: function(){

                },
                error: function (xhr, ajaxOptions, thrownError) {

                    console.log( 'SDWeddingDirectory_Error_Todo' );
                    console.log(xhr.status);
                    console.log(thrownError);
                    console.log(xhr.responseText);
                },
                complete: function( event, request, settings ){

                }
            });
        },

        /**
         *  7. Reset Todo
         *  -------------
         */
        reset_todo: function(){

            if( $( '.reset_todo' ).length ){

                $( '.reset_todo' ).on( 'click', function( e ){

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

                            'action'             :   'sdweddingdirectory_couple_reset_todo',

                            'security'           :   $( '#sdweddingdirectory_couple_todo_security' ).val()
                        },
                        success: function ( PHP_RESPONSE ) {

                            sdweddingdirectory_alert( PHP_RESPONSE );

                            setTimeout( function(){ window.location.reload(); }, 2000 );
                        },
                        beforeSend: function(){

                            $( '.reset_todo' ).append( '<i class="ms-2 fa fa-spinner fa-spin"></i>' );
                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            console.log( 'SDWeddingDirectory_Error_Todo' );
                            console.log(xhr.status);
                            console.log(thrownError);
                            console.log(xhr.responseText);
                        },
                        complete: function( event, request, settings ){

                            $( '.reset_todo' ).find( 'i' ).remove();
                        }
                    });

                } );   
            }
        },

        /**
         *  Load Object Member
         *  ------------------
         */
        init: function() {

            /**
             *  1. Add New Todo
             *  ---------------
             */
            this.add_todo();

            /**
             *  2. Edit Todo
             *  ------------
             */
            this.edit_todo();

            /**
             *  3. Show Panel
             *  -------------
             */
            this.show_add_edit_panel();

            /**
             *  4. Delete Todo
             *  --------------
             */
            this.delete_todo();

            /**
             *  5. Complete Todo
             *  ----------------
             */
            this.complete_task();

            /**
             *  6. Done Todo
             *  ------------
             */
            this.todo_task_done();

            /**
             *  7. Reset Todo
             *  -------------
             */
            this.reset_todo();
        },
    };

    $(document).ready( function() {  SDWeddingDirectory_Todo.init(); });

})(jQuery);