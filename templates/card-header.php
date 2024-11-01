<?php
/** @var array $args */

$title = $args['marker']['title'] ?? null;

if ( empty( $title ) ) {
	return;
}

$permalink = get_permalink( $args['marker']['id'] );
?>
<h4>
	<a href="<?= esc_attr( $permalink ) ?>">
		<?= $title ?>
	</a>
</h4>
