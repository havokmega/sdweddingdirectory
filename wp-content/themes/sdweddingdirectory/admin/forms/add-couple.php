<?php
/**
 * SDWeddingDirectory - Add Couple Admin Form
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="sdweddingdirectory_content_style">
    <form id="sdweddingdirectory_couple_registration_form" method="post" autocomplete="off">
        <table class="form-table">
            <tbody>
                <?php
                $_input_fields = [
                    'sdweddingdirectory_couple_register_username' => [
                        'type' => esc_attr( 'text' ),
                        'name' => esc_attr__( 'Username', 'sdweddingdirectory' ),
                    ],
                    'sdweddingdirectory_couple_register_first_name' => [
                        'type' => esc_attr( 'text' ),
                        'name' => esc_attr__( 'First Name', 'sdweddingdirectory' ),
                    ],
                    'sdweddingdirectory_couple_register_last_name' => [
                        'type' => esc_attr( 'text' ),
                        'name' => esc_attr__( 'Last Name', 'sdweddingdirectory' ),
                    ],
                    'sdweddingdirectory_couple_register_password' => [
                        'type' => esc_attr( 'text' ),
                        'name' => esc_attr__( 'Password', 'sdweddingdirectory' ),
                    ],
                    'sdweddingdirectory_couple_register_email' => [
                        'type' => esc_attr( 'email' ),
                        'name' => esc_attr__( 'Email ID', 'sdweddingdirectory' ),
                    ],
                    'sdweddingdirectory_couple_register_wedding_date' => [
                        'type' => esc_attr( 'date' ),
                        'name' => esc_attr__( 'Wedding Date', 'sdweddingdirectory' ),
                    ],
                ];
                if ( is_array( $_input_fields ) ) {
                    foreach ( $_input_fields as $key => $value ) {
                        extract( $value );
                        printf(
                            '<tr class="form-field form-required">
                                <th scope="row"><label for="%1$s">%2$s</label></th>
                                <td><input autocomplete="off" name="%1$s" class="form-control" type="%3$s" id="%1$s" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60"></td>
                            </tr>',
                            sanitize_key( $key ),
                            esc_attr( $name ),
                            esc_attr( $type )
                        );
                    }
                }
                $_select_fields = [
                    'sdweddingdirectory_register_couple_person' => [
                        'name' => esc_attr__( 'I am', 'sdweddingdirectory' ),
                        'options' => SDWeddingDirectory_Taxonomy::create_select_option(
                            [
                                'i_am_couple' => esc_attr__( 'Planning my wedding', 'sdweddingdirectory' ),
                                'i_am_guest' => esc_attr__( 'A wedding guest', 'sdweddingdirectory' ),
                            ],
                            [],
                            '',
                            false
                        ),
                    ],
                    'sending_email' => [
                        'name' => esc_attr__( 'Sending Email ?', 'sdweddingdirectory' ),
                        'options' => SDWeddingDirectory_Taxonomy::create_select_option(
                            [
                                '0' => esc_attr__( 'No', 'sdweddingdirectory' ),
                                '1' => esc_attr__( 'Yes', 'sdweddingdirectory' ),
                            ],
                            [],
                            '',
                            false
                        ),
                    ],
                ];
                if ( is_array( $_select_fields ) ) {
                    foreach ( $_select_fields as $key => $value ) {
                        extract( $value );
                        printf(
                            '<tr class="form-field form-required">
                                <th scope="row"><label for="%1$s">%2$s</label></th>
                                <td><select id="%1$s" name="%1$s">%3$s</select></td>
                            </tr>',
                            sanitize_key( $key ),
                            esc_attr( $name ),
                            $options
                        );
                    }
                }
                ?>
            </tbody>
        </table>
        <hr />
        <p class="status"></p>
        <p class="submit">
            <?php
            printf(
                '<button type="submit" class="button button-large button-primary" id="%1$s" name="%1$s">
                    %2$s %3$s
                </button>',
                esc_attr( 'sdweddingdirectory_register_couple_registration_form_button' ),
                esc_attr__( 'Add New Couple', 'sdweddingdirectory' ),
                wp_nonce_field( 'sdweddingdirectory_couple_registration_security', 'sdweddingdirectory_couple_registration_security', true, false )
            );
            ?>
        </p>
    </form>
</div>
