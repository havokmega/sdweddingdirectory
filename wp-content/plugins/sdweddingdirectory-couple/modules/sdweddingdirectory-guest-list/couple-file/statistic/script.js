(function($) {

    "use strict";

    var SDWeddingDirectory_Guest_List_Graph = {

        /**
         *  Load Graph
         *  ----------
         */
        load_graph: function( _lable, _series, _total_guest ){

            /**
             *  Load Chart
             *  ----------
             */
            if( $( '#sdweddingdirectory_event_chart' ).length ){

                $( '#sdweddingdirectory_event_chart' ).html( '' );

                    var options = {

                        series: _series,

                        labels: _lable,

                        colors: ['#198754', '#ffc107', '#dc3545' ],

                        chart: {

                            height: 350,

                            type: 'radialBar',
                        },

                        plotOptions: {
                            radialBar: {
                                dataLabels: {

                                    name: {
                                        fontSize: '22px',
                                    },
                                    value: {
                                        fontSize: '16px',
                                    },

                                    total: {
                                        show: true,
                                        label: "Guest",
                                        formatter: function (w) { return _total_guest }
                                    }
                                }
                            }
                        },
                    };

                    /**
                     *  Load Chart
                     *  ----------
                     */
                    var chart = new ApexCharts( document.querySelector( "#sdweddingdirectory_event_chart" ), options );

                    chart.render();
            }
        },

        init: function(){

            /**
             *  Load Chart
             *  ----------
             */
            this.load_graph(

                $.parseJSON( $( '#sdweddingdirectory_event_chart' ).attr( 'data-lable' ) ),

                $.parseJSON( $( '#sdweddingdirectory_event_chart' ).attr( 'data-counter' ) ),

                $( '#sdweddingdirectory_event_chart' ).attr( 'data-total-guest' )
            );
        },
    };

    $(document).ready( function() { SDWeddingDirectory_Guest_List_Graph.init(); });

})(jQuery);