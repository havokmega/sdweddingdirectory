(function ($) {

  "use strict";

  var SDWeddingDirectory_Dashboard = {

      /**
       *  1. Dashboard Left Side Menu 
       *  ---------------------------
       */
      dashboard_left_side_menu : function(){

          if( $('[data-dashboard-tools-toggle="offcanvas"]').length ){

            $('[data-dashboard-tools-toggle="offcanvas"]').on('click', function () {

                $('body').toggleClass('open');

            } );

            $('[data-dashboard-tools-toggle="offcanvas-mobile"]').on('click', function () {

                $('.offcanvas-collapse-mobile').toggleClass('show');

            } );

          }
      },

      /**
       *  2. Slider Close
       *  ---------------
       */
      close_slider: function(){

          if( $( '.sliding-panel' ).length ){

              $( '.sliding-panel' ).each( function(){

                  var id  =   '#'  +  $(this).attr( 'id' );

                  $( id + ' .dashboard-head' ).append( '<a href="javascript:"><i class="fa fa-close"></i></a>' );

                  $( id ).find( '.dashboard-head i' ).click( function( e ){

                      $( id ).slideReveal( "hide" );

                  } );

              } ) ;
          }
      },

      init: function() {

          /**
           *  1. Dashboard Left Side Menu
           *  ---------------------------
           */
          this.dashboard_left_side_menu();

          /**
           *  2. Close Slider
           *  ---------------
           */
          this.close_slider();
      }
  }

  /**
   *  Document Load
   *  -------------
   */
  $(function () { $( document ).ready( function(){ SDWeddingDirectory_Dashboard.init(); }  ) } );

})(jQuery);