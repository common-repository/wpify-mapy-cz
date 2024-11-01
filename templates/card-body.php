<?php
/** @var array $args */

$thumbnail_id = $args['marker']['thumbnail_id'] ?? null;
$description  = $args['marker']['description'] ?? null;

if ( ! empty ( $thumbnail_id ) ) {
	echo wp_get_attachment_image( $thumbnail_id );
}

if ( ! empty( $description ) ) {
	?>
	<p><?= $description ?></p>
	<?php
}
