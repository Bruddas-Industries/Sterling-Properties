<?php
/**
 * riverbend/shuttle-schedule — the weekday shuttle timetable in the shuttle band.
 *
 * Times are from Sterling's "Shuttle Schedule — Weekday AM/PM Route" sheet
 * (Nina, 2026-10-22). Riverbend is Stop #3, in front of the Gate House; the
 * shuttle's first two stops are at another community and are not listed. The
 * evening runs leave Chatham station, and the sheet gives no Riverbend arrival
 * times for them.
 *
 * Each column's times are one per line in the sidebar. Text after a "|" is the
 * small second line under the time (e.g. "5:22 AM | arrives 5:28").
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$riverbend_cols = [
	[ 'morningTitle', 'morningSub', 'morningNote', 'morningTimes' ],
	[ 'eveningTitle', 'eveningSub', 'eveningNote', 'eveningTimes' ],
];
?>
<div class="shuttle-schedule"<?php echo riverbend_anim(); ?>>
  <?php foreach ( $riverbend_cols as [ $t, $s, $n, $times ] ) :
    $lines = array_filter( array_map( 'trim', preg_split( '/\R/', (string) ( $attributes[ $times ] ?? '' ) ) ) ); ?>
    <div class="shuttle-schedule__col">
      <h3 class="shuttle-schedule__title"><?php echo esc_html( $attributes[ $t ] ?? '' ); ?><?php if ( ! empty( $attributes[ $s ] ) ) : ?> <span><?php echo esc_html( $attributes[ $s ] ); ?></span><?php endif; ?></h3>
      <?php if ( ! empty( $attributes[ $n ] ) ) : ?>
        <p class="shuttle-schedule__note"><?php echo esc_html( $attributes[ $n ] ); ?></p>
      <?php endif; ?>
      <ul class="shuttle-times">
        <?php foreach ( $lines as $line ) :
          $parts = array_map( 'trim', explode( '|', $line, 2 ) ); ?>
          <li><span class="shuttle-times__time"><?php echo esc_html( $parts[0] ); ?></span><?php if ( ! empty( $parts[1] ) ) : ?><span class="shuttle-times__sub"><?php echo esc_html( $parts[1] ); ?></span><?php endif; ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endforeach; ?>
</div>
