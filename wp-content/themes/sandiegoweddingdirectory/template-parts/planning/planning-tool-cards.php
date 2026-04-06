<?php
/**
 * Planning: Tool Cards
 *
 * 3-column icon card row for Budget Calculator, Seating Chart, Guest List.
 */

$theme_uri = get_template_directory_uri();
?>
<section class="planning-tool-cards section">
    <div class="container">
        <?php
        get_template_part( 'template-parts/components/tool-card-row', null, [
            'columns' => 3,
            'cards'   => [
                [
                    'icon_url' => $theme_uri . '/assets/images/icons/planning/budget.png',
                    'title'    => __( 'Budget', 'sdweddingdirectory' ),
                    'desc'     => __( 'Keep your finances organized and stay in control of every wedding expense.', 'sdweddingdirectory' ),
                    'cta_label' => __( 'VIEW YOUR WEDDING BUDGET', 'sdweddingdirectory' ),
                    'cta_url'   => home_url( '/wedding-planning/wedding-budget/' ),
                ],
                [
                    'icon_url' => $theme_uri . '/assets/images/icons/planning/seating-chart.png',
                    'title'    => __( 'Seating Chart', 'sdweddingdirectory' ),
                    'desc'     => __( 'Arrange your tables with a simple drag-and-drop layout to assign guests with ease.', 'sdweddingdirectory' ),
                    'cta_label' => __( 'MANAGE SEATING PLAN', 'sdweddingdirectory' ),
                    'cta_url'   => home_url( '/wedding-planning/wedding-seating-chart/' ),
                ],
                [
                    'icon_url' => $theme_uri . '/assets/images/icons/planning/guest-list.png',
                    'title'    => __( 'Guest List', 'sdweddingdirectory' ),
                    'desc'     => __( 'Track invitations, RSVPs, and event details all in one organized place.', 'sdweddingdirectory' ),
                    'cta_label' => __( 'EDIT GUEST LIST', 'sdweddingdirectory' ),
                    'cta_url'   => home_url( '/wedding-planning/wedding-guest-list/' ),
                ],
            ],
        ] );
        ?>
    </div>
</section>
