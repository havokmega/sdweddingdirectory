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
                    'title'    => __( 'Budget', 'sandiegoweddingdirectory' ),
                    'desc'     => __( 'Keep your finances organized and stay in control of every wedding expense.', 'sandiegoweddingdirectory' ),
                    'cta_label' => __( 'VIEW YOUR WEDDING BUDGET', 'sandiegoweddingdirectory' ),
                    'cta_url'   => home_url( '/wedding-planning/wedding-budget/' ),
                ],
                [
                    'icon_url' => $theme_uri . '/assets/images/icons/planning/seating-chart.png',
                    'title'    => __( 'Seating Chart', 'sandiegoweddingdirectory' ),
                    'desc'     => __( 'Arrange your tables with a simple drag-and-drop layout to assign guests with ease.', 'sandiegoweddingdirectory' ),
                    'cta_label' => __( 'MANAGE SEATING PLAN', 'sandiegoweddingdirectory' ),
                    'cta_url'   => home_url( '/wedding-planning/wedding-seating-chart/' ),
                ],
                [
                    'icon_url' => $theme_uri . '/assets/images/icons/planning/guest-list.png',
                    'title'    => __( 'Guest List', 'sandiegoweddingdirectory' ),
                    'desc'     => __( 'Track invitations, RSVPs, and event details all in one organized place.', 'sandiegoweddingdirectory' ),
                    'cta_label' => __( 'EDIT GUEST LIST', 'sandiegoweddingdirectory' ),
                    'cta_url'   => home_url( '/wedding-planning/wedding-guest-list/' ),
                ],
            ],
        ] );
        ?>
    </div>
</section>
