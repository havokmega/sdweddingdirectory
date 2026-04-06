<?php
/**
 *  -------------------------------------
 *  OptionTree ( Theme Option Framework )
 *  -------------------------------------
 *  @author : By - Derek Herman
 *  ---------------------------
 *  @link - https://wordpress.org/plugins/option-tree/
 *  --------------------------------------------------
 *  Fields : https://github.com/valendesigns/option-tree-theme/blob/master/inc/theme-options.php
 *  --------------------------------------------------------------------------------------------
 *  SDWeddingDirectory - Framework - Section - Couple
 *  ------------------------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_FrameWork_Budget_Calculator_Setting' ) && class_exists( 'SDWeddingDirectory_FrameWork_Couple_Section' )  ) {

    /**
     *  SDWeddingDirectory - Framework - Section - Couple
     *  ------------------------------------------
     */
    class SDWeddingDirectory_FrameWork_Budget_Calculator_Setting extends SDWeddingDirectory_FrameWork_Couple_Section {

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance(){

            if( ! isset( self::$instance ) ){

                self::$instance = new self;
            }

            return  self::$instance;
        }


        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            return; // Disabled - migrated to native settings

            /**
             *  Add Setting
             *  -----------
             */
            add_filter( parent:: section_info(), [ $this, 'budget_calculator_setting' ], absint( '40' ), absint( '2' ) );
        }

        /**
         *   Wishlist Setting Tab
         *   --------------------
         */
        public static function budget_calculator_setting( $have_setting = [], $have_section = '' ){

            $add_setting    =   array(

                    array(

                        'id'        =>  esc_attr( 'couple_budget_calculator_setting_tab' ),

                        'label'     =>  esc_attr__( 'Budget Calculator', 'sdweddingdirectory-budget-calculator' ),

                        'type'      =>  esc_attr( 'tab' ),

                        'section'   =>  esc_attr( $have_section )
                    ),

                    array(

                        'id'        =>  esc_attr( 'sdweddingdirectory_default_budget_data_switch' ),

                        'label'     =>  esc_attr__( 'Default Budget Data Insert in Register Couple ?', 'sdweddingdirectory-budget-calculator' ),

                        'std'       =>  esc_attr( 'on' ),

                        'type'      =>  esc_attr( 'on-off' ),

                        'section'   =>  esc_attr( $have_section ),
                    ),

                    array(

                        'id'        =>  sanitize_key( 'sdweddingdirectory_default_budget_amount' ),

                        'label'     =>  esc_attr__( 'Default Estimate Budget Amount', 'sdweddingdirectory-budget-calculator' ),

                        'type'      =>  esc_attr( 'text' ),

                        'std'       =>  '',

                        'section'   =>  esc_attr( $have_section ),
                    ),

                    array(

                        'id'        =>  esc_attr( 'sdweddingdirectory_budget_category_data' ),

                        'label'     =>  esc_attr__( 'Budget Category Data', 'sdweddingdirectory-budget-calculator' ),

                        'type'      =>  esc_attr( 'list-item' ),

                        'section'   =>  esc_attr( $have_section ),

                        'condition' =>  esc_attr( 'sdweddingdirectory_default_budget_data_switch:is(on)' ),

                        'std'       =>  array(

                                            array(

                                                'title'     =>  esc_attr( 'Venue' ),

                                                'icon'      =>  esc_attr( 'sdweddingdirectory-location-heart' ),

                                                'json'      =>  json_encode( array(

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'   =>  esc_attr( 'Ceremony Venue Fee' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Ceremony Venue Decorations' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Reception Venue' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Rehearsal Dinner Venue' ),

                                                                    ) ),

                                                                ) )
                                            ),

                                            array(

                                                'title'     =>  esc_attr( 'Videographer' ),

                                                'icon'      =>  esc_attr( 'sdweddingdirectory-videographer' ),

                                                'json'      =>  json_encode( array(

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Videographer' ),

                                                                    ) ),

                                                                ) )
                                            ),

                                            array(

                                                'title'     =>  esc_attr( 'Invitations' ),

                                                'icon'      =>  esc_attr( 'sdweddingdirectory-heart-envelope' ),

                                                'json'      =>  json_encode( array(

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Invitations and Reply Cards' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Other Stationery' ),

                                                                    ) ),

                                                                ) )
                                            ),

                                            array(

                                                'title'     =>  esc_attr( 'Favors and Gifts' ),

                                                'icon'      =>  esc_attr( 'sdweddingdirectory-love-gift' ),

                                                'json'      =>  json_encode( array(

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Favors and Gifts' ),

                                                                    ) ),

                                                                ) )
                                            ),

                                            array(

                                                'title'     =>  esc_attr( 'Cake' ),

                                                'icon'      =>  esc_attr( 'sdweddingdirectory-cake-floor' ),

                                                'json'      =>  json_encode( array(

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Cake and Cutting Fee' ),

                                                                    ) ),
                                                                ) )
                                            ),

                                            array(

                                                'title'     =>  esc_attr( 'Dress and Attire' ),

                                                'icon'      =>  esc_attr( 'sdweddingdirectory-fashion' ),

                                                'json'      =>  json_encode( array(

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Dress and Alterations' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Headpiece and Veil' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Wedding Accessories' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Tux or Suit' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Additional Accessories' ),

                                                                    ) ),

                                                                ) )
                                            ),

                                            array(

                                                'title'     =>  esc_attr( 'Band' ),

                                                'icon'      =>  esc_attr( 'sdweddingdirectory-guitar' ),

                                                'json'      =>  json_encode( array(

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Band' ),

                                                                    ) ),

                                                                ) )
                                            ),

                                            array(

                                                'title'     =>  esc_attr( 'Jewelry' ),

                                                'icon'      =>  esc_attr( 'sdweddingdirectory-ring_double' ),

                                                'json'      =>  json_encode( array(

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Your Ring' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Partner\'s Ring' ),

                                                                    ) ),

                                                                ) )
                                            ),

                                            array(

                                                'title'     =>  esc_attr( 'Rentals' ),

                                                'icon'      =>  esc_attr( 'sdweddingdirectory-tent' ),

                                                'json'      =>  json_encode( array(

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr__( 'Reception Rentals' ),

                                                                    ) ),

                                                                ) )
                                            ),

                                            array(

                                                'title'     =>  esc_attr( 'Transportation' ),

                                                'icon'      =>  esc_attr( 'sdweddingdirectory-bus' ),

                                                'json'      =>  json_encode( array(

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Ceremony Decorations' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Reception Decorations and Centerpieces' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Wedding Party Bouquets' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Wedding Party Boutonnieres' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Flower Girl Flowers' ),

                                                                    ) ),

                                                                    self:: budget_dummy_amount( array(

                                                                        'expense_name'    =>  esc_attr( 'Additional Boutonnieres and Corsages' ),

                                                                    ) ),

                                                                ) )
                                            ),
                                        ),

                        'settings'    =>    array(

                                                array(

                                                    'id'          =>  sanitize_key( 'icon' ),

                                                    'label'       =>  esc_attr__( 'Icon', 'sdweddingdirectory-budget-calculator' ),

                                                    'type'        =>  esc_attr( 'select' ),

                                                    'choices'     =>  apply_filters( 'sdweddingdirectory_icons_set_for_ot', [] ),
                                                ),

                                                array(

                                                    'id'          =>  sanitize_key( 'json' ),

                                                    'label'       =>  esc_attr__( 'Budget Data', 'sdweddingdirectory-budget-calculator' ),

                                                    'type'        =>  esc_attr( 'textarea-simple' ),
                                                ),
                                            ),
                    ),
            );

            return      array_merge( $have_setting, $add_setting );
        }

        /**
         *  Merge Budget Expence JSON args
         *  ------------------------------
         */
        public static function budget_dummy_amount( $args = [] ){

            /**
             *  Have Data ?
             *  -----------
             */
            if(  parent:: _is_array( $args )  ){

                /**
                 *  Merge Args + Default Args
                 *  -------------------------
                 */
                return      wp_parse_args(

                                /**
                                 *  Have Args
                                 *  ---------
                                 */
                                $args,

                                /**
                                 *  Default Data
                                 *  ------------
                                 */
                                array(

                                    'estimate_amount'     =>  '',

                                    'actual_amount'       =>  '',

                                    'paid_amount'         =>  '',
                                )
                            );
            }
        }
    }

    /**
     *  SDWeddingDirectory - Framework - Section - Couple
     *  ------------------------------------------
     */
    SDWeddingDirectory_FrameWork_Budget_Calculator_Setting::get_instance();
}