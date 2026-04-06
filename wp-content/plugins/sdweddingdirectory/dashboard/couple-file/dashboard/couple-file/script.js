/**
 *  Couple Profile Update
 */

(function($) {

  'use strict';

    var SDWeddingDirectory_Couple_Dashboard = {

        /**
         *  1. Date Countdown
         *  -----------------
         */
        date_counter: function(){

			  		if( $( '#wedding-countdown' ).length ){

			  				var wedding_date = $( '#wedding-countdown' ).attr( 'data-wedding-date' );

			  				SDWeddingDirectory_Couple_Dashboard.countdown( wedding_date, 'wedding-countdown' );
			  		}
        },

        countdown: function(dt, id){

				    var end = new Date(dt);
				    
				    var _second = 1000;
				    var _minute = _second * 60;
				    var _hour = _minute * 60;
				    var _day = _hour * 24;
				    var timer;

			        var wedding_days 	= $( '#wedding-countdown' ).attr( 'data-wedding-days' 	),
			        	wedding_hours 	= $( '#wedding-countdown' ).attr( 'data-wedding-hours' 	),
			        	wedding_min 	= $( '#wedding-countdown' ).attr( 'data-wedding-min' 	),
			        	wedding_sec 	= $( '#wedding-countdown' ).attr( 'data-wedding-sec' 	),
			        	wedding_msg 	= $( '#wedding-countdown' ).attr( 'data-wedding-msg' 	);
				    
				    function showRemaining() {
				        var now = new Date();
				        var distance = end - now;
				        if (distance < 0) {
				            
				            clearInterval(timer);
				            document.getElementById(id).innerHTML = '<h4 class="pt-3">'+wedding_msg+'</h4>';
				            
				            return;
				        }
				        var days = Math.floor(distance / _day);
				        var hours = Math.floor((distance % _day) / _hour);
				        var minutes = Math.floor((distance % _hour) / _minute);
				        var seconds = Math.floor((distance % _minute) / _second);
				        
				        
				        if (String(hours).length < 2){
				            hours = 0 + String(hours);
				        }
				        if (String(minutes).length < 2){
				            minutes = 0 + String(minutes);
				        }
				        if (String(seconds).length < 2){
				            seconds = 0 + String(seconds);
				        }

						var datestr =

					    '<li class="list-inline-item">'
					        +'<span class="days">'+days+'</span>'
					        +'<div class="days_text">'+ wedding_days +'</div>'
					    +'</li>'
					    +'<li class="list-inline-item">'
					        +'<span class="hours">'+hours+'</span>'
					        +'<div class="hours_text">'+ wedding_hours +'</div>'
					    +'</li>'
					    +'<li class="list-inline-item">'
					        +'<span class="minutes">'+minutes+'</span>'
					        +'<div class="minutes_text">'+ wedding_min +'</div>'
					    +'</li>'
					    +'<li class="list-inline-item">'
					        +'<span class="seconds">'+seconds+'</span>'
					        +'<div class="seconds_text">'+ wedding_sec +'</div>'
					    +'</li>';

				        document.getElementById(id).innerHTML = datestr;
				    }

				    timer = setInterval(showRemaining, 1000);
        },

        /**
         *  2. Wedding Information
         *  -------------------
         */
        init : function(){

            /**
             *  1. Date Countdown
             *  -----------------
             */
            this.date_counter();
        },
    }

    $( document ).ready( function(){ SDWeddingDirectory_Couple_Dashboard.init(); } )

})(jQuery);