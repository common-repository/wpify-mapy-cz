<?php
/** @var array $args */

$address = $args['marker']['address'] ?? null;

if ( empty( $address ) ) {
	return;
}
?>
	<address><?= $address ?></address>
<?php
