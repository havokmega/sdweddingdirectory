<?php
/**
 * Couple Checklist — Shell
 *
 * Spec s12: progress bar + grouped tasks + task rows.
 * Tasks are grouped by phase (Early Planning / 6 Months Out / Final Month).
 *
 * $args:
 *   - checklist (array of [ id, text, completed, due_date, group ])
 */

$checklist = $args['checklist'] ?? [];
if ( ! is_array( $checklist ) ) { $checklist = []; }

$total     = count( $checklist );
$completed = count( array_filter( $checklist, fn( $t ) => ! empty( $t['completed'] ) ) );
$percent   = $total > 0 ? (int) round( ( $completed / $total ) * 100 ) : 0;

// Group tasks. Default groups split the list evenly when no group key set.
$groups = [
    'early' => [ 'label' => __( 'Early Planning',  'sandiegoweddingdirectory' ), 'tasks' => [] ],
    'mid'   => [ 'label' => __( '6 Months Out',    'sandiegoweddingdirectory' ), 'tasks' => [] ],
    'late'  => [ 'label' => __( 'Final Month',     'sandiegoweddingdirectory' ), 'tasks' => [] ],
];
$third = max( 1, (int) ceil( $total / 3 ) );
foreach ( array_values( $checklist ) as $i => $task ) {
    $g = $task['group'] ?? '';
    if ( ! isset( $groups[ $g ] ) ) {
        $g = $i < $third ? 'early' : ( $i < $third * 2 ? 'mid' : 'late' );
    }
    $groups[ $g ]['tasks'][] = $task;
}
?>

<div class="cd-card cd-checklist">
    <div class="cd-card__head">
        <h1 class="cd-card__title"><?php esc_html_e( 'Wedding Checklist', 'sandiegoweddingdirectory' ); ?></h1>
        <span class="cd-checklist__count">
            <?php
            /* translators: 1: completed, 2: total */
            printf( esc_html__( '%1$d of %2$d completed', 'sandiegoweddingdirectory' ), $completed, $total );
            ?>
        </span>
    </div>

    <div class="cd-checklist__progress">
        <div class="cd-checklist__bar" role="progressbar" aria-valuenow="<?php echo (int) $percent; ?>" aria-valuemin="0" aria-valuemax="100">
            <div class="cd-checklist__fill" style="width: <?php echo (int) $percent; ?>%;"></div>
        </div>
        <span class="cd-checklist__percent"><?php echo (int) $percent; ?>%</span>
    </div>

    <div class="cd-checklist__groups">
        <?php foreach ( $groups as $slug => $group ) :
            if ( empty( $group['tasks'] ) ) { continue; }
            $g_total     = count( $group['tasks'] );
            $g_completed = count( array_filter( $group['tasks'], fn( $t ) => ! empty( $t['completed'] ) ) );
        ?>
            <section class="cd-checklist__group">
                <header class="cd-checklist__group-head">
                    <h2 class="cd-checklist__group-title"><?php echo esc_html( $group['label'] ); ?></h2>
                    <span class="cd-checklist__group-count"><?php echo (int) $g_completed; ?> / <?php echo (int) $g_total; ?></span>
                </header>
                <ul class="cd-checklist__list">
                    <?php foreach ( $group['tasks'] as $task ) :
                        $id   = $task['id']        ?? '';
                        $done = ! empty( $task['completed'] );
                        $text = $task['text']      ?? '';
                    ?>
                        <li class="cd-checklist__item<?php echo $done ? ' cd-checklist__item--done' : ''; ?>">
                            <label class="cd-checklist__row">
                                <input type="checkbox" data-cd-task="<?php echo esc_attr( $id ); ?>" <?php checked( $done ); ?>>
                                <span class="cd-checklist__text"><?php echo esc_html( $text ); ?></span>
                                <?php if ( ! empty( $task['due_date'] ) ) : ?>
                                    <span class="cd-checklist__due"><?php echo esc_html( $task['due_date'] ); ?></span>
                                <?php endif; ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endforeach; ?>
    </div>

    <div class="cd-form__actions">
        <button type="button" class="btn btn--ghost" data-cd-add-task>+ <?php esc_html_e( 'Add task', 'sandiegoweddingdirectory' ); ?></button>
    </div>
</div>
