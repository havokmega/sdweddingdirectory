(function($) {

    'use strict';

    var SDWeddingDirectory_Search_Vendor = {

        init: function(){

            this.form = $( '.sdweddingdirectory-vendor-filters' );

            if( ! this.form.length ){

                return;
            }

            $( 'body' ).addClass( 'sdweddingdirectory-js' );

            this.results = $( '#vendor_search_result' );

            this.pagination = $( '#vendor_have_pagination' );

            this.countBadge = $( '#vendor-filter-count' );

            this.bind_events();

            this.restore_collapse_state();

            this.update_filter_count();

            this.move_filters_for_offcanvas();
        },

        bind_events: function(){

            var self = this;

            this.form.on( 'change', 'input[type=checkbox]', function(){

                self.find_vendors( 1 );
            } );

            $( document ).on( 'click', '#vendor_have_pagination a', function( e ){

                e.preventDefault();

                var page = self.get_paged_from_url( $(this).attr( 'href' ) );

                self.find_vendors( page );
            } );

            $( document ).on( 'click', '.sdweddingdirectory-clear-filters', function( e ){

                e.preventDefault();

                self.clear_filters();
            } );

            $( document ).on( 'click', '#vendor-filter-apply', function(){

                self.find_vendors( 1 );
            } );

            $( document ).on( 'shown.bs.collapse hidden.bs.collapse', '.venue-filter-section .collapse', function(){

                var collapseId = $( this ).attr( 'id' );

                if( collapseId ){

                    sessionStorage.setItem( 'sdwd_filter_' + collapseId, $( this ).hasClass( 'show' ) ? 'show' : 'hide' );
                }
            } );

            $( window ).on( 'resize', function(){

                self.move_filters_for_offcanvas();
            } );
        },

        get_filter_values: function( name ){

            var values = [];

            this.form.find( 'input[name="' + name + '[]"]:checked' ).each( function(){

                values.push( $( this ).val() );
            } );

            return values;
        },

        get_paged_from_url: function( url ){

            if( ! url ){

                return 1;
            }

            try{

                var parsed = new URL( url, window.location.origin );

                var paged = parsed.searchParams.get( 'paged' );

                return paged ? parseInt( paged, 10 ) : 1;

            }catch( err ){

                return 1;
            }
        },

        update_query: function( params ){

            var url = new URL( window.location.href );

            var keys = [

                'vendor_pricing', 'vendor_pricing[]',

                'vendor_services', 'vendor_services[]',

                'vendor_style', 'vendor_style[]',

                'vendor_specialties', 'vendor_specialties[]',

                'paged'
            ];

            keys.forEach( function( key ){

                url.searchParams.delete( key );
            } );

            if( params.vendor_pricing && params.vendor_pricing.length ){

                params.vendor_pricing.forEach( function( value ){

                    url.searchParams.append( 'vendor_pricing[]', value );
                } );
            }

            if( params.vendor_services && params.vendor_services.length ){

                params.vendor_services.forEach( function( value ){

                    url.searchParams.append( 'vendor_services[]', value );
                } );
            }

            if( params.vendor_style && params.vendor_style.length ){

                params.vendor_style.forEach( function( value ){

                    url.searchParams.append( 'vendor_style[]', value );
                } );
            }

            if( params.vendor_specialties && params.vendor_specialties.length ){

                params.vendor_specialties.forEach( function( value ){

                    url.searchParams.append( 'vendor_specialties[]', value );
                } );
            }

            if( params.paged && params.paged > 1 ){

                url.searchParams.set( 'paged', params.paged );
            }

            window.history.pushState( {}, '', url.toString() );
        },

        toggle_loading: function( isLoading ){

            var loaderHtml = '<div class="loader-ajax-container-wrap"><div class="loader-ajax-container"><div class="loader-ajax"></div></div></div>';

            if( isLoading ){

                this.results.addClass( 'sdweddingdirectory-results-loading' );

                this.form.find( 'input, select, button' ).prop( 'disabled', true );

                if( this.results.length && ! this.results.prev( '.loader-ajax-container-wrap' ).length ){

                    $( loaderHtml ).insertBefore( this.results );
                }

            }else{

                this.results.removeClass( 'sdweddingdirectory-results-loading' );

                this.form.find( 'input, select, button' ).prop( 'disabled', false );

                $( '.loader-ajax-container-wrap' ).remove();
            }
        },

        update_filter_count: function(){

            if( ! this.countBadge.length ){

                return;
            }

            var count = this.form.find( 'input[type=checkbox]:checked' ).length;

            if( count > 0 ){

                this.countBadge.text( count ).removeClass( 'd-none' );

            }else{

                this.countBadge.addClass( 'd-none' ).text( '' );
            }
        },

        render_no_results: function(){

            var title = SDWEDDINGDIRECTORY_AJAX_OBJ.vendor_no_results_title || 'No vendors match your current filters.';

            var text = SDWEDDINGDIRECTORY_AJAX_OBJ.vendor_no_results_text || 'Try removing some filters.';

            var clearLabel = SDWEDDINGDIRECTORY_AJAX_OBJ.vendor_no_results_clear || 'Clear All Filters';

            return '<div class="sdweddingdirectory-no-results text-center p-4">' +
                '<h4 class="mb-2">' + title + '</h4>' +
                '<p class="mb-3">' + text + '</p>' +
                '<button class="btn btn-link sdweddingdirectory-clear-filters" type="button">' + clearLabel + '</button>' +
            '</div>';
        },

        clear_filters: function(){

            this.form.find( 'input[type=checkbox]' ).prop( 'checked', false );

            this.find_vendors( 1 );
        },

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
        },

        move_filters_for_offcanvas: function(){

            var offcanvasBody = $( '#vendor-filter-offcanvas .offcanvas-body' );

            var sidebar = $( '.sdweddingdirectory-vendor-sidebar' );

            if( ! offcanvasBody.length || ! sidebar.length ){

                return;
            }

            if( ! this.placeholder || ! this.placeholder.length ){

                this.placeholder = $( '<div class="sdweddingdirectory-vendor-filter-placeholder"></div>' );

                this.form.before( this.placeholder );
            }

            if( window.innerWidth < 992 ){

                if( ! offcanvasBody.find( this.form ).length ){

                    offcanvasBody.append( this.form );
                }

            }else{

                if( ! this.placeholder.next().is( this.form ) ){

                    this.placeholder.after( this.form );
                }
            }
        },

        find_vendors: function( page ){

            var self = this;

            var paged = page || 1;

            var pricing = this.get_filter_values( 'vendor_pricing' );

            var services = this.get_filter_values( 'vendor_services' );

            var style = this.get_filter_values( 'vendor_style' );

            var specialties = this.get_filter_values( 'vendor_specialties' );

            var termId = parseInt( this.form.data( 'term-id' ), 10 ) || 0;

            var taxonomy = this.form.data( 'taxonomy' ) || 'vendor-category';

            this.update_query({

                vendor_pricing: pricing,

                vendor_services: services,

                vendor_style: style,

                vendor_specialties: specialties,

                paged: paged
            });

            this.toggle_loading( true );

            $.ajax({

                type: 'POST',

                dataType: 'json',

                url: SDWEDDINGDIRECTORY_AJAX_OBJ.ajaxurl,

                data: {

                    action: 'sdweddingdirectory_load_vendor_data',

                    nonce: SDWEDDINGDIRECTORY_AJAX_OBJ.vendor_filter_nonce,

                    vendor_category: termId,

                    taxonomy: taxonomy,

                    paged: paged,

                    vendor_pricing: pricing,

                    vendor_services: services,

                    vendor_style: style,

                    vendor_specialties: specialties
                },

                success: function( response ){

                    self.toggle_loading( false );

                    if( self.results.length ){

                        if( response.found_result && response.vendor_html_data !== '' ){

                            self.results.html( response.vendor_html_data );

                        }else{

                            self.results.html( self.render_no_results() );
                        }
                    }

                    if( self.pagination.length ){

                        self.pagination.html( response.pagination || '' );
                    }

                    if( $( '#vendor_result_count' ).length ){

                        $( '#vendor_result_count' ).text( response.found_result || 0 );
                    }

                    self.update_filter_count();
                },

                error: function(){

                    self.toggle_loading( false );
                }
            });
        }
    };

    $(document).ready( function(){

        SDWeddingDirectory_Search_Vendor.init();
    } );

})(jQuery);
