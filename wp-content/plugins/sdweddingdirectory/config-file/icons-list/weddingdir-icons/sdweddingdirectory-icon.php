<?php
/**
 *  --------------------------
 *  SDWeddingDirectory - Icon - Object
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Font_Family_Icon' ) && class_exists( 'SDWeddingDirectory_Icon_Manager' ) ){

	/**
	 *  --------------------------
	 *  SDWeddingDirectory - Icon - Object
	 *  --------------------------
	 */
    class SDWeddingDirectory_Font_Family_Icon extends SDWeddingDirectory_Icon_Manager{

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Returns an instance of this class.
         *  ----------------------------------
         */
        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Icon Filter
             *  -----------
             */
            add_filter( 'sdweddingdirectory/font-family/sdweddingdirectory-icon', function( $args = [] ){

                return  array_merge( $args, self:: icon_collection() );

            }, absint( '10' ) );

            /**
             *  Font
             *  ----
             */
            add_filter( 'sdweddingdirectory_icons_set', function(  $args = [] ){

                return  array_merge(

                            /**
                             *  1. Have Any Another Data ?
                             *  --------------------------
                             */
                            $args,

                            /**
                             *  Collecton of Icons
                             *  ---------------------------
                             */
                            apply_filters( 'sdweddingdirectory/font-family/sdweddingdirectory-icon', [] )
                        );

            }, absint( '10' ), absint( '1' ) );
        }

        /**
         *  Icon Collection
         *  ---------------
         */
        public static function icon_collection(){

            return  	array(

				'sdweddingdirectory-beach-see'  =>  'sdweddingdirectory-beach-see',
				'sdweddingdirectory-beach-sun'  =>  'sdweddingdirectory-beach-sun',
				'sdweddingdirectory-beach-villa'  =>  'sdweddingdirectory-beach-villa',
				'sdweddingdirectory-bohemian-1'   =>  'sdweddingdirectory-bohemian-1',
				'sdweddingdirectory-bohemian-2'   =>  'sdweddingdirectory-bohemian-2',
				'sdweddingdirectory-bohemian-3'   =>  'sdweddingdirectory-bohemian-3',
				'sdweddingdirectory-bohemian-4'   =>  'sdweddingdirectory-bohemian-4',
				'sdweddingdirectory-bohemian-5'   =>  'sdweddingdirectory-bohemian-5',
				'sdweddingdirectory-bow-tie-1'  =>  'sdweddingdirectory-bow-tie-1',
				'sdweddingdirectory-bow-tie-2'  =>  'sdweddingdirectory-bow-tie-2',
				'sdweddingdirectory-bow-tie-3'  =>  'sdweddingdirectory-bow-tie-3',
				'sdweddingdirectory-bow-tie-4'  =>  'sdweddingdirectory-bow-tie-4',
				'sdweddingdirectory-breach-2'   =>  'sdweddingdirectory-breach-2',
				'sdweddingdirectory-casual-1'   =>  'sdweddingdirectory-casual-1',
				'sdweddingdirectory-casual-2'   =>  'sdweddingdirectory-casual-2',
				'sdweddingdirectory-casual-3'   =>  'sdweddingdirectory-casual-3',
				'sdweddingdirectory-casual-4'   =>  'sdweddingdirectory-casual-4',
				'sdweddingdirectory-casual-5'   =>  'sdweddingdirectory-casual-5',
				'sdweddingdirectory-casual-6'   =>  'sdweddingdirectory-casual-6',
				'sdweddingdirectory-elegant-1'  =>  'sdweddingdirectory-elegant-1',
				'sdweddingdirectory-elegant-2'  =>  'sdweddingdirectory-elegant-2',
				'sdweddingdirectory-elegant-3'  =>  'sdweddingdirectory-elegant-3',
				'sdweddingdirectory-elegant-4'  =>  'sdweddingdirectory-elegant-4',
				'sdweddingdirectory-fall-2'   =>  'sdweddingdirectory-fall-2',
				'sdweddingdirectory-fall-3'   =>  'sdweddingdirectory-fall-3',
				'sdweddingdirectory-fall'   =>  'sdweddingdirectory-fall',
				'sdweddingdirectory-glam-1'   =>  'sdweddingdirectory-glam-1',
				'sdweddingdirectory-glam-2'   =>  'sdweddingdirectory-glam-2',
				'sdweddingdirectory-glam-3'   =>  'sdweddingdirectory-glam-3',
				'sdweddingdirectory-glam-4'   =>  'sdweddingdirectory-glam-4',
				'sdweddingdirectory-glam-5'   =>  'sdweddingdirectory-glam-5',
				'sdweddingdirectory-industrial-1'   =>  'sdweddingdirectory-industrial-1',
				'sdweddingdirectory-industrial-2'   =>  'sdweddingdirectory-industrial-2',
				'sdweddingdirectory-industrial-3'   =>  'sdweddingdirectory-industrial-3',
				'sdweddingdirectory-industrial-4'   =>  'sdweddingdirectory-industrial-4',
				'sdweddingdirectory-love-bell'  =>  'sdweddingdirectory-love-bell',
				'sdweddingdirectory-modern-1'   =>  'sdweddingdirectory-modern-1',
				'sdweddingdirectory-modern-2'   =>  'sdweddingdirectory-modern-2',
				'sdweddingdirectory-modern-3'   =>  'sdweddingdirectory-modern-3',
				'sdweddingdirectory-modern-4'   =>  'sdweddingdirectory-modern-4',
				'sdweddingdirectory-romantic-1'   =>  'sdweddingdirectory-romantic-1',
				'sdweddingdirectory-romantic-2'   =>  'sdweddingdirectory-romantic-2',
				'sdweddingdirectory-romantic-3'   =>  'sdweddingdirectory-romantic-3',
				'sdweddingdirectory-romantic-4'   =>  'sdweddingdirectory-romantic-4',
				'sdweddingdirectory-romantic-5'   =>  'sdweddingdirectory-romantic-5',
				'sdweddingdirectory-rustic-1'   =>  'sdweddingdirectory-rustic-1',
				'sdweddingdirectory-rustic-2'   =>  'sdweddingdirectory-rustic-2',
				'sdweddingdirectory-rustic-3'   =>  'sdweddingdirectory-rustic-3',
				'sdweddingdirectory-snow'   =>  'sdweddingdirectory-snow',
				'sdweddingdirectory-spring'   =>  'sdweddingdirectory-spring',
				'sdweddingdirectory-sun-2'  =>  'sdweddingdirectory-sun-2',
				'sdweddingdirectory-sun-3'  =>  'sdweddingdirectory-sun-3',
				'sdweddingdirectory-sun-4'  =>  'sdweddingdirectory-sun-4',
				'sdweddingdirectory-sun'  =>  'sdweddingdirectory-sun',
				'sdweddingdirectory-vintage-1'  =>  'sdweddingdirectory-vintage-1',
				'sdweddingdirectory-vintage-2'  =>  'sdweddingdirectory-vintage-2',
				'sdweddingdirectory-vintage-3'  =>  'sdweddingdirectory-vintage-3',
				'sdweddingdirectory-vintage-4'  =>  'sdweddingdirectory-vintage-4',
				'sdweddingdirectory-winter'   =>  'sdweddingdirectory-winter',
				'sdweddingdirectory-support'  =>  'sdweddingdirectory-support',
				'sdweddingdirectory-ballon-heart'   =>  'sdweddingdirectory-ballon-heart',
				'sdweddingdirectory-bell'   =>  'sdweddingdirectory-bell',
				'sdweddingdirectory-birde'  =>  'sdweddingdirectory-birde',
				'sdweddingdirectory-bridal-wear'  =>  'sdweddingdirectory-bridal-wear',
				'sdweddingdirectory-budget'   =>  'sdweddingdirectory-budget',
				'sdweddingdirectory-bus'  =>  'sdweddingdirectory-bus',
				'sdweddingdirectory-cake'   =>  'sdweddingdirectory-cake',
				'sdweddingdirectory-cake-floor'   =>  'sdweddingdirectory-cake-floor',
				'sdweddingdirectory-cake-stand'   =>  'sdweddingdirectory-cake-stand',
				'sdweddingdirectory-calendar-heart'   =>  'sdweddingdirectory-calendar-heart',
				'sdweddingdirectory-camera'   =>  'sdweddingdirectory-camera',
				'sdweddingdirectory-camera-alt'   =>  'sdweddingdirectory-camera-alt',
				'sdweddingdirectory-chat'   =>  'sdweddingdirectory-chat',
				'sdweddingdirectory-checklist'  =>  'sdweddingdirectory-checklist',
				'sdweddingdirectory-church'   =>  'sdweddingdirectory-church',
				'sdweddingdirectory-cup-cakes'  =>  'sdweddingdirectory-cup-cakes',
				'sdweddingdirectory-dashboard'  =>  'sdweddingdirectory-dashboard',
				'sdweddingdirectory-dove'   =>  'sdweddingdirectory-dove',
				'sdweddingdirectory-fashion'  =>  'sdweddingdirectory-fashion',
				'sdweddingdirectory-florist'  =>  'sdweddingdirectory-florist',
				'sdweddingdirectory-flowers'  =>  'sdweddingdirectory-flowers',
				'sdweddingdirectory-gender'   =>  'sdweddingdirectory-gender',
				'sdweddingdirectory-guest-member'   =>  'sdweddingdirectory-guest-member',
				'sdweddingdirectory-guestlist'  =>  'sdweddingdirectory-guestlist',
				'sdweddingdirectory-guitar'   =>  'sdweddingdirectory-guitar',
				'sdweddingdirectory-hanging-heart'  =>  'sdweddingdirectory-hanging-heart',
				'sdweddingdirectory-heart-double'   =>  'sdweddingdirectory-heart-double',
				'sdweddingdirectory-heart-double-alt'   =>  'sdweddingdirectory-heart-double-alt',
				'sdweddingdirectory-heart-double-face'  =>  'sdweddingdirectory-heart-double-face',
				'sdweddingdirectory-heart-envelope'   =>  'sdweddingdirectory-heart-envelope',
				'sdweddingdirectory-heart-hand'   =>  'sdweddingdirectory-heart-hand',
				'sdweddingdirectory-heart-ring'   =>  'sdweddingdirectory-heart-ring',
				'sdweddingdirectory-invoice'  =>  'sdweddingdirectory-invoice',
				'sdweddingdirectory-location'   =>  'sdweddingdirectory-location',
				'sdweddingdirectory-location-heart'   =>  'sdweddingdirectory-location-heart',
				'sdweddingdirectory-logout'   =>  'sdweddingdirectory-logout',
				'sdweddingdirectory-love-gift'  =>  'sdweddingdirectory-love-gift',
				'sdweddingdirectory-mail'   =>  'sdweddingdirectory-mail',
				'sdweddingdirectory-money-stack'  =>  'sdweddingdirectory-money-stack',
				'sdweddingdirectory-music'  =>  'sdweddingdirectory-music',
				'sdweddingdirectory-my-venue'   =>  'sdweddingdirectory-my-venue',
				'sdweddingdirectory-my-profile'   =>  'sdweddingdirectory-my-profile',
				'sdweddingdirectory-pheras'   =>  'sdweddingdirectory-pheras',
				'sdweddingdirectory-pricing-plans'  =>  'sdweddingdirectory-pricing-plans',
				'sdweddingdirectory-request-quote'  =>  'sdweddingdirectory-request-quote',
				'sdweddingdirectory-reviews'  =>  'sdweddingdirectory-reviews',
				'sdweddingdirectory-ring-double'  =>  'sdweddingdirectory-ring-double',
				'sdweddingdirectory-saving-money'   =>  'sdweddingdirectory-saving-money',
				'sdweddingdirectory-seating-chart'  =>  'sdweddingdirectory-seating-chart',
				'sdweddingdirectory-shopping-bag-heart'   =>  'sdweddingdirectory-shopping-bag-heart',
				'sdweddingdirectory-tent'   =>  'sdweddingdirectory-tent',
				'sdweddingdirectory-vendor-manager'   =>  'sdweddingdirectory-vendor-manager',
				'sdweddingdirectory-vendor-truck'   =>  'sdweddingdirectory-vendor-truck',
				'sdweddingdirectory-venue'  =>  'sdweddingdirectory-venue',
				'sdweddingdirectory-videographer'   =>  'sdweddingdirectory-videographer',
				'sdweddingdirectory-website'  =>  'sdweddingdirectory-website',
				'sdweddingdirectory-wine'   =>  'sdweddingdirectory-wine',
			);
        }
    }

    /**
     *  --------------------------
     *  SDWeddingDirectory - Icon - Object
     *  --------------------------
     */
    SDWeddingDirectory_Font_Family_Icon::get_instance();
}