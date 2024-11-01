<?php
/** @var array $args */

if ( empty( $args ) ) {
	return;
}
?>
<div class="mapy-cz__wrapper">
	<?php do_action( 'wpify_mapy_cz_before_map', $args ); ?>
	<script>if(!window.wpify_mapy_cz){window.wpify_mapy_cz = {}};window.wpify_mapy_cz[<?= $args['id'] ?>]=<?= wp_json_encode( $args ) ?></script>
	<div class="<?php echo join( ' ', array_filter( array( 'mapy-cz__map', $args['class'] ?? null ) ) ); ?>" data-mapycz="<?= $args['id'] ?>"
	></div>
	<?php do_action( 'wpify_mapy_cz_after_map', $args ); ?>
</div>
