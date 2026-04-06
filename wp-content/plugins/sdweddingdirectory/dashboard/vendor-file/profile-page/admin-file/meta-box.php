<?php
/**
 *  SDWeddingDirectory Vendor Profile Metabox
 *  ------------------------------------------
 *  Native WordPress metabox (no OptionTree dependency).
 *  Mirrors the "Create Vendor" field layout so admins can
 *  edit all vendor fields directly from the post editor.
 */
if ( ! class_exists( 'SDWeddingDirectory_Vendor_Profile_Meta' ) ) {

    class SDWeddingDirectory_Vendor_Profile_Meta {

        private static $instance;

        public static function get_instance() {
            if ( ! isset( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function __construct() {
            add_action( 'add_meta_boxes', [ $this, 'register_metabox' ] );
            add_action( 'save_post_vendor', [ $this, 'save_metabox' ], 10, 2 );
        }

        /**
         *  Register the metabox on the vendor edit screen.
         */
        public function register_metabox() {
            add_meta_box(
                'sdwd_vendor_profile_info',
                __( 'Vendor Information', 'sdweddingdirectory' ),
                [ $this, 'render_metabox' ],
                'vendor',
                'normal',
                'high'
            );
        }

        /**
         *  Render the metabox HTML.
         */
        public function render_metabox( $post ) {

            wp_nonce_field( 'sdwd_vendor_profile_save', 'sdwd_vendor_profile_nonce' );

            $post_id = absint( $post->ID );

            // Post meta fields.
            $first_name      = sanitize_text_field( get_post_meta( $post_id, 'first_name', true ) );
            $last_name       = sanitize_text_field( get_post_meta( $post_id, 'last_name', true ) );
            $company_name    = sanitize_text_field( get_post_meta( $post_id, 'company_name', true ) );
            $company_website = esc_url( get_post_meta( $post_id, 'company_website', true ) );
            $company_contact = sanitize_text_field( get_post_meta( $post_id, 'company_contact', true ) );
            $company_email   = sanitize_text_field( get_post_meta( $post_id, 'company_email', true ) );

            // Linked WordPress user.
            $linked_user_id  = absint( get_post_meta( $post_id, 'user_id', true ) );
            $user_login      = '';
            $user_email      = '';

            if ( $linked_user_id ) {
                $user = get_userdata( $linked_user_id );
                if ( $user ) {
                    $user_login = $user->user_login;
                    $user_email = $user->user_email;
                }
            }

            // Vendor category.
            $assigned_cats = wp_get_post_terms( $post_id, 'vendor-category', [ 'fields' => 'ids' ] );
            $selected_cat  = ( ! is_wp_error( $assigned_cats ) && ! empty( $assigned_cats ) ) ? absint( $assigned_cats[0] ) : 0;
            $all_cats      = get_terms([
                'taxonomy'   => 'vendor-category',
                'parent'     => 0,
                'hide_empty' => false,
            ]);
            ?>

            <table class="form-table">
                <tbody>
                    <tr class="form-field">
                        <th scope="row"><label for="sdwd_vendor_username"><?php esc_html_e( 'Username', 'sdweddingdirectory' ); ?></label></th>
                        <td>
                            <input type="text" id="sdwd_vendor_username" class="regular-text" value="<?php echo esc_attr( $user_login ); ?>" disabled />
                            <p class="description"><?php esc_html_e( 'Username cannot be changed.', 'sdweddingdirectory' ); ?></p>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="sdwd_vendor_first_name"><?php esc_html_e( 'First Name', 'sdweddingdirectory' ); ?></label></th>
                        <td><input type="text" name="sdwd_vendor_first_name" id="sdwd_vendor_first_name" class="regular-text" value="<?php echo esc_attr( $first_name ); ?>" /></td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="sdwd_vendor_last_name"><?php esc_html_e( 'Last Name', 'sdweddingdirectory' ); ?></label></th>
                        <td><input type="text" name="sdwd_vendor_last_name" id="sdwd_vendor_last_name" class="regular-text" value="<?php echo esc_attr( $last_name ); ?>" /></td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="sdwd_vendor_password"><?php esc_html_e( 'Password', 'sdweddingdirectory' ); ?></label></th>
                        <td>
                            <input type="text" name="sdwd_vendor_password" id="sdwd_vendor_password" class="regular-text" value="" autocomplete="off" />
                            <p class="description"><?php esc_html_e( 'Leave blank to keep the current password.', 'sdweddingdirectory' ); ?></p>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="sdwd_vendor_email"><?php esc_html_e( 'Email ID', 'sdweddingdirectory' ); ?></label></th>
                        <td><input type="email" name="sdwd_vendor_email" id="sdwd_vendor_email" class="regular-text" value="<?php echo esc_attr( $user_email ); ?>" /></td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="sdwd_vendor_company_name"><?php esc_html_e( 'Company Name', 'sdweddingdirectory' ); ?></label></th>
                        <td><input type="text" name="sdwd_vendor_company_name" id="sdwd_vendor_company_name" class="regular-text" value="<?php echo esc_attr( $company_name ); ?>" /></td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="sdwd_vendor_company_website"><?php esc_html_e( 'Company Website', 'sdweddingdirectory' ); ?></label></th>
                        <td><input type="url" name="sdwd_vendor_company_website" id="sdwd_vendor_company_website" class="regular-text" value="<?php echo esc_attr( $company_website ); ?>" /></td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="sdwd_vendor_contact_number"><?php esc_html_e( 'Contact Number', 'sdweddingdirectory' ); ?></label></th>
                        <td><input type="text" name="sdwd_vendor_contact_number" id="sdwd_vendor_contact_number" class="regular-text" value="<?php echo esc_attr( $company_contact ); ?>" /></td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="sdwd_vendor_category"><?php esc_html_e( 'Category', 'sdweddingdirectory' ); ?></label></th>
                        <td>
                            <select name="sdwd_vendor_category" id="sdwd_vendor_category">
                                <option value="0"><?php esc_html_e( 'Choose Category', 'sdweddingdirectory' ); ?></option>
                                <?php
                                if ( ! is_wp_error( $all_cats ) && ! empty( $all_cats ) ) {
                                    foreach ( $all_cats as $cat ) {
                                        printf(
                                            '<option value="%1$s" %2$s>%3$s</option>',
                                            absint( $cat->term_id ),
                                            selected( $selected_cat, $cat->term_id, false ),
                                            esc_html( $cat->name )
                                        );
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="sdwd_vendor_company_email"><?php esc_html_e( 'Company Email', 'sdweddingdirectory' ); ?></label></th>
                        <td><input type="email" name="sdwd_vendor_company_email" id="sdwd_vendor_company_email" class="regular-text" value="<?php echo esc_attr( $company_email ); ?>" /></td>
                    </tr>
                </tbody>
            </table>

            <?php if ( $linked_user_id ) : ?>
                <p class="description" style="margin-top:12px;">
                    <?php printf(
                        esc_html__( 'Linked WordPress user: #%1$s — %2$s', 'sdweddingdirectory' ),
                        absint( $linked_user_id ),
                        sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( get_edit_user_link( $linked_user_id ) ), esc_html__( 'View user profile', 'sdweddingdirectory' ) )
                    ); ?>
                </p>
            <?php endif; ?>

            <?php
        }

        /**
         *  Save metabox data.
         */
        public function save_metabox( $post_id, $post ) {

            // Verify nonce.
            if ( ! isset( $_POST['sdwd_vendor_profile_nonce'] ) || ! wp_verify_nonce( $_POST['sdwd_vendor_profile_nonce'], 'sdwd_vendor_profile_save' ) ) {
                return;
            }

            // Check autosave.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            // Check permissions.
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }

            // Save post meta fields.
            $meta_fields = [
                'sdwd_vendor_first_name'      => 'first_name',
                'sdwd_vendor_last_name'       => 'last_name',
                'sdwd_vendor_company_name'    => 'company_name',
                'sdwd_vendor_company_website'  => 'company_website',
                'sdwd_vendor_contact_number'  => 'company_contact',
                'sdwd_vendor_company_email'   => 'company_email',
            ];

            foreach ( $meta_fields as $form_key => $meta_key ) {
                if ( isset( $_POST[ $form_key ] ) ) {
                    $value = $meta_key === 'company_website'
                        ? esc_url_raw( wp_unslash( $_POST[ $form_key ] ) )
                        : sanitize_text_field( wp_unslash( $_POST[ $form_key ] ) );
                    update_post_meta( $post_id, $meta_key, $value );
                }
            }

            // Update vendor category taxonomy.
            if ( isset( $_POST['sdwd_vendor_category'] ) ) {
                $cat_id = absint( $_POST['sdwd_vendor_category'] );
                if ( $cat_id > 0 ) {
                    wp_set_post_terms( $post_id, [ $cat_id ], 'vendor-category' );
                } else {
                    wp_set_post_terms( $post_id, [], 'vendor-category' );
                }
            }

            // Update linked WordPress user (email + password).
            $linked_user_id = absint( get_post_meta( $post_id, 'user_id', true ) );

            if ( $linked_user_id ) {
                $user_updates = [];

                // Email.
                if ( isset( $_POST['sdwd_vendor_email'] ) ) {
                    $new_email = sanitize_email( wp_unslash( $_POST['sdwd_vendor_email'] ) );
                    if ( is_email( $new_email ) ) {
                        $user_updates['user_email'] = $new_email;
                        // Also sync to post meta.
                        update_post_meta( $post_id, 'user_email', $new_email );
                    }
                }

                // Password (only if provided).
                if ( ! empty( $_POST['sdwd_vendor_password'] ) ) {
                    $user_updates['user_pass'] = wp_unslash( $_POST['sdwd_vendor_password'] );
                }

                if ( ! empty( $user_updates ) ) {
                    $user_updates['ID'] = $linked_user_id;
                    wp_update_user( $user_updates );
                }
            }
        }
    }

    SDWeddingDirectory_Vendor_Profile_Meta::get_instance();
}
