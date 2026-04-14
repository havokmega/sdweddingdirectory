<?php
/**
 * Couple Dashboard — Right sidebar 2: Task List
 *
 * Preview of the first few checklist items from sdwd_checklist user meta.
 * Links to the full checklist page for editing.
 *
 * $args:
 *   - checklist (array of { text, completed, due_date })
 */

$checklist = $args['checklist'] ?? [];
if ( ! is_array( $checklist ) ) { $checklist = []; }

// Show up to 5 incomplete tasks first, then fill with complete if needed.
$incomplete = array_values( array_filter( $checklist, fn( $t ) => empty( $t['completed'] ) ) );
$complete   = array_values( array_filter( $checklist, fn( $t ) => ! empty( $t['completed'] ) ) );
$preview    = array_slice( array_merge( $incomplete, $complete ), 0, 5 );
?>

<aside class="cd-widget cd-widget--tasks">
    <h3 class="cd-widget__title">
        <span class="icon-check" aria-hidden="true"></span>
        <?php esc_html_e( 'Task List', 'sdweddingdirectory' ); ?>
    </h3>

    <?php if ( empty( $preview ) ) : ?>
        <div class="cd-widget__soon">
            <?php esc_html_e( 'No tasks yet. Visit the checklist to get started.', 'sdweddingdirectory' ); ?>
        </div>
    <?php else : ?>
        <ul class="cd-tasks">
            <?php foreach ( $preview as $task ) :
                $done  = ! empty( $task['completed'] );
                $text  = $task['text'] ?? '';
                $class = 'cd-tasks__item' . ( $done ? ' cd-tasks__item--done' : '' );
            ?>
                <li class="<?php echo esc_attr( $class ); ?>">
                    <input type="checkbox" class="cd-tasks__check" disabled <?php checked( $done ); ?>>
                    <span class="cd-tasks__text"><?php echo esc_html( $text ); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a href="<?php echo esc_url( home_url( '/couple-dashboard/checklist/' ) ); ?>" class="cd-widget__link">
        <?php esc_html_e( 'View full checklist', 'sdweddingdirectory' ); ?> &rarr;
    </a>
</aside>
