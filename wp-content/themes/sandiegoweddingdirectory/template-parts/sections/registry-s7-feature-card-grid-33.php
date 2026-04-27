<?php
/**
 * Registry: Tool card grid — 3 + 3 layout, links to all 6 planning tools.
 *
 * Visually mirrors the bottom-of-page block on /wedding-planning/{slug}/
 * (planning-secondary-intro + planning-tool-cards) — same CSS hooks so
 * the look stays in sync without duplicating styles.
 */

$theme_uri = get_template_directory_uri();

$tools = [
    [
        'icon'  => 'checklist.png',
        'title' => __( 'Checklist',       'sandiegoweddingdirectory' ),
        'desc'  => __( 'A complete planning timeline that keeps every task on track.', 'sandiegoweddingdirectory' ),
        'cta'   => __( 'VIEW CHECKLIST',  'sandiegoweddingdirectory' ),
        'url'   => home_url( '/wedding-planning/wedding-checklist/' ),
    ],
    [
        'icon'  => 'seating-chart.png',
        'title' => __( 'Seating Chart',   'sandiegoweddingdirectory' ),
        'desc'  => __( 'Arrange tables and assign guests with drag-and-drop ease.', 'sandiegoweddingdirectory' ),
        'cta'   => __( 'MANAGE SEATING',  'sandiegoweddingdirectory' ),
        'url'   => home_url( '/wedding-planning/wedding-seating-chart/' ),
    ],
    [
        'icon'  => 'vendor-manager.png',
        'title' => __( 'Vendor Manager',  'sandiegoweddingdirectory' ),
        'desc'  => __( 'Search, organize, and communicate with vendors in one place.', 'sandiegoweddingdirectory' ),
        'cta'   => __( 'MANAGE VENDORS',  'sandiegoweddingdirectory' ),
        'url'   => home_url( '/wedding-planning/vendor-manager/' ),
    ],
    [
        'icon'  => 'guest-list.png',
        'title' => __( 'Guest List',      'sandiegoweddingdirectory' ),
        'desc'  => __( 'Track invitations, RSVPs, and event details in one organized place.', 'sandiegoweddingdirectory' ),
        'cta'   => __( 'EDIT GUEST LIST', 'sandiegoweddingdirectory' ),
        'url'   => home_url( '/wedding-planning/wedding-guest-list/' ),
    ],
    [
        'icon'  => 'budget.png',
        'title' => __( 'Budget',          'sandiegoweddingdirectory' ),
        'desc'  => __( 'Keep finances organized and stay in control of every wedding expense.', 'sandiegoweddingdirectory' ),
        'cta'   => __( 'VIEW BUDGET',     'sandiegoweddingdirectory' ),
        'url'   => home_url( '/wedding-planning/wedding-budget/' ),
    ],
    [
        'icon'  => 'wedding-website.png',
        'title' => __( 'Wedding Website', 'sandiegoweddingdirectory' ),
        'desc'  => __( 'Build a personalized website to share details and collect RSVPs.', 'sandiegoweddingdirectory' ),
        'cta'   => __( 'BUILD WEBSITE',   'sandiegoweddingdirectory' ),
        'url'   => home_url( '/wedding-planning/wedding-website/' ),
    ],
];

$cards = [];
foreach ( $tools as $tool ) {
    $cards[] = [
        'icon_url'  => $theme_uri . '/assets/images/icons/planning/' . $tool['icon'],
        'title'     => $tool['title'],
        'desc'      => $tool['desc'],
        'cta_label' => $tool['cta'],
        'cta_url'   => $tool['url'],
    ];
}
?>
<section class="planning-secondary-intro section">
    <div class="container">
        <?php
        get_template_part( 'template-parts/components/section-title', null, [
            'heading' => __( 'Connect every part of your planning, free.', 'sandiegoweddingdirectory' ),
            'desc'    => __( 'Your registry pairs perfectly with the rest of the SD Wedding Directory toolkit. Sign up free and keep checklist, budget, guest list, and more in one place.', 'sandiegoweddingdirectory' ),
            'align'   => 'center',
        ] );
        ?>
    </div>
</section>

<section class="planning-tool-cards section">
    <div class="container">
        <?php
        get_template_part( 'template-parts/components/tool-card-row', null, [
            'columns' => 3,
            'cards'   => $cards,
        ] );
        ?>
    </div>
</section>
