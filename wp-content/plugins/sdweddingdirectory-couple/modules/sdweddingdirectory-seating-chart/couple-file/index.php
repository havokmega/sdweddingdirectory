<?php
/**
 *  SDWeddingDirectory - Couple Seating Chart
 *  ----------------------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_Couple_Seating_Chart' ) && class_exists( 'SDWeddingDirectory_Seating_Chart_Database' ) ) {

    class SDWeddingDirectory_Couple_Seating_Chart extends SDWeddingDirectory_Seating_Chart_Database {

        private static $instance;

        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        public function __construct() {

            if ( ! parent::is_couple() ) {
                return;
            }

            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ], absint( '85' ) );
            add_action( 'sdweddingdirectory/couple-dashboard', [ $this, 'dashboard_page' ], absint( '85' ), absint( '1' ) );
        }

        public static function sdweddingdirectory_script() {

            if ( ! parent::dashboard_page_set( 'seating-chart' ) ) {
                return;
            }

            wp_enqueue_style(
                esc_attr( sanitize_title( __CLASS__ ) . '-style' ),
                esc_url( plugin_dir_url( __FILE__ ) . 'style.css' ),
                [],
                esc_attr( parent::_file_version( plugin_dir_path( __FILE__ ) . 'style.css' ) ),
                'all'
            );

            wp_enqueue_script(
                esc_attr( sanitize_title( __CLASS__ ) ),
                esc_url( plugin_dir_url( __FILE__ ) . 'script.js' ),
                [ 'jquery' ],
                esc_attr( parent::_file_version( plugin_dir_path( __FILE__ ) . 'script.js' ) ),
                true
            );

            wp_localize_script(
                esc_attr( sanitize_title( __CLASS__ ) ),
                'sdweddingdirectorySeatingChart',
                [
                    'ajaxUrl'   => esc_url( admin_url( 'admin-ajax.php' ) ),
                    'nonce'     => wp_create_nonce( 'sdweddingdirectory_seating_chart_nonce' ),
                    'chartData' => parent::chart_data(),
                    'guestPool' => parent::guest_pool(),
                    'strings'   => [
                        'saveSuccess'   => esc_attr__( 'Seating chart saved successfully.', 'sdweddingdirectory' ),
                        'saveError'     => esc_attr__( 'Unable to save seating chart right now.', 'sdweddingdirectory' ),
                        'addTableError' => esc_attr__( 'Please provide table details and valid seat settings.', 'sdweddingdirectory' ),
                        'unassigned'    => esc_attr__( 'Unassigned', 'sdweddingdirectory' ),
                    ],
                ]
            );
        }

        public static function dashboard_page( $args = [] ) {

            if ( ! parent::_is_array( $args ) ) {
                return;
            }

            extract( wp_parse_args( $args, [
                'page' => '',
            ] ) );

            if ( empty( $page ) || $page !== esc_attr( 'seating-chart' ) ) {
                return;
            }
            ?>
            <div class="container-fluid sdwc-seating-chart-page">
                <div class="section-title mb-3">
                    <div class="d-md-flex justify-content-between align-items-center gap-2">
                        <h2 class="mb-2 mb-md-0"><?php esc_html_e( 'Seating Chart', 'sdweddingdirectory' ); ?></h2>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-dark" id="sdwc-print-view"><?php esc_html_e( 'Print View', 'sdweddingdirectory' ); ?></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="sdwc-export-json"><?php esc_html_e( 'Export JSON', 'sdweddingdirectory' ); ?></button>
                            <button type="button" class="btn btn-sm btn-primary" id="sdwc-save-chart"><?php esc_html_e( 'Save Seating Chart', 'sdweddingdirectory' ); ?></button>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><?php esc_html_e( 'Add Table', 'sdweddingdirectory' ); ?></h5>
                                <div class="mb-2">
                                    <label for="sdwc-table-name" class="form-label small mb-1"><?php esc_html_e( 'Table Name / Number', 'sdweddingdirectory' ); ?></label>
                                    <input id="sdwc-table-name" type="text" class="form-control form-control-sm" placeholder="<?php echo esc_attr__( 'e.g. Table 1', 'sdweddingdirectory' ); ?>">
                                </div>
                                <div class="mb-2">
                                    <label for="sdwc-table-shape" class="form-label small mb-1"><?php esc_html_e( 'Shape', 'sdweddingdirectory' ); ?></label>
                                    <select id="sdwc-table-shape" class="form-select form-select-sm">
                                        <option value="round"><?php esc_html_e( 'Round', 'sdweddingdirectory' ); ?></option>
                                        <option value="rectangular"><?php esc_html_e( 'Rectangular', 'sdweddingdirectory' ); ?></option>
                                        <option value="square"><?php esc_html_e( 'Square', 'sdweddingdirectory' ); ?></option>
                                    </select>
                                </div>

                                <div class="row g-2 mb-2" id="sdwc-rect-seats-row" style="display:none;">
                                    <div class="col-6">
                                        <label for="sdwc-table-short-side" class="form-label small mb-1"><?php esc_html_e( 'Seats on Short Side', 'sdweddingdirectory' ); ?></label>
                                        <input id="sdwc-table-short-side" type="number" min="1" max="15" value="1" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-6">
                                        <label for="sdwc-table-long-side" class="form-label small mb-1"><?php esc_html_e( 'Seats on Long Side', 'sdweddingdirectory' ); ?></label>
                                        <input id="sdwc-table-long-side" type="number" min="1" max="15" value="3" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="mb-2" id="sdwc-square-seats-row" style="display:none;">
                                    <label for="sdwc-table-square-side" class="form-label small mb-1"><?php esc_html_e( 'Seats Per Side', 'sdweddingdirectory' ); ?></label>
                                    <input id="sdwc-table-square-side" type="number" min="1" max="7" value="2" class="form-control form-control-sm">
                                </div>

                                <div class="mb-3" id="sdwc-seat-count-row">
                                    <label for="sdwc-table-seats" class="form-label small mb-1"><?php esc_html_e( 'Seat Count', 'sdweddingdirectory' ); ?></label>
                                    <input id="sdwc-table-seats" type="number" min="1" max="30" value="8" class="form-control form-control-sm">
                                </div>
                                <button type="button" class="btn btn-sm btn-dark w-100" id="sdwc-add-table"><?php esc_html_e( 'Add Table To Floor Plan', 'sdweddingdirectory' ); ?></button>
                            </div>
                        </div>

                        <div class="card shadow-sm mt-3">
                            <div class="card-body">
                                <h5 class="card-title mb-2"><?php esc_html_e( 'Table Assignments', 'sdweddingdirectory' ); ?></h5>
                                <p class="text-muted small mb-2"><?php esc_html_e( 'Assign guests to seats for each table.', 'sdweddingdirectory' ); ?></p>
                                <div id="sdwc-table-list" class="sdwc-table-list"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0"><?php esc_html_e( 'Floor Plan Canvas', 'sdweddingdirectory' ); ?></h5>
                                    <span class="text-muted small"><?php esc_html_e( 'Drag tables to position them.', 'sdweddingdirectory' ); ?></span>
                                </div>
                                <div class="row g-2 align-items-end mb-3">
                                    <div class="col-sm-4 col-6">
                                        <label for="sdwc-layout-width-feet" class="form-label small mb-1"><?php esc_html_e( 'Layout Width (ft)', 'sdweddingdirectory' ); ?></label>
                                        <input id="sdwc-layout-width-feet" type="number" min="10" max="500" value="80" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-sm-4 col-6">
                                        <label for="sdwc-layout-height-feet" class="form-label small mb-1"><?php esc_html_e( 'Layout Height (ft)', 'sdweddingdirectory' ); ?></label>
                                        <input id="sdwc-layout-height-feet" type="number" min="10" max="500" value="60" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-sm-4 col-12">
                                        <label for="sdwc-layout-scale" class="form-label small mb-1"><?php esc_html_e( 'Scale', 'sdweddingdirectory' ); ?> <span id="sdwc-layout-scale-value">100%</span></label>
                                        <input id="sdwc-layout-scale" type="range" min="1" max="150" value="100" class="form-range">
                                    </div>
                                </div>

                                <div class="sdwc-floor-plan-viewport">
                                    <div id="sdwc-floor-plan" class="sdwc-floor-plan"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-3" id="sdwc-readonly-card">
                    <div class="card-body">
                        <h5 class="card-title mb-2"><?php esc_html_e( 'Read-Only Print / Export Preview', 'sdweddingdirectory' ); ?></h5>
                        <p class="text-muted small mb-3"><?php esc_html_e( 'This section reflects the final chart in a print-friendly format.', 'sdweddingdirectory' ); ?></p>
                        <div id="sdwc-readonly-view"></div>
                    </div>
                </div>

                <div class="card shadow-sm mt-3 sdwc-print-only" id="sdwc-print-layout-card">
                    <div class="card-body">
                        <h5 class="card-title mb-2"><?php esc_html_e( 'Floor Plan Layout', 'sdweddingdirectory' ); ?></h5>
                        <div class="sdwc-floor-plan-print-shell">
                            <div id="sdwc-floor-plan-print" class="sdwc-floor-plan sdwc-floor-plan-print"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    SDWeddingDirectory_Couple_Seating_Chart::get_instance();
}
