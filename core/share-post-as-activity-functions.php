<?php
/**
 * Plugin functions
 *
 * @package share-post-as-activity
 */

// No direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render share activity button
 *
 * @param array $args Array.
 */
function share_post_as_activity_button( $args ) {
	echo share_post_as_activity_get_button( $args );
}

/**
 * Get activity share button
 *
 * @param array $args Array.
 *
 * @return string
 */
function share_post_as_activity_get_button( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'item_id'   => 0,
		'share_url' => '',
		'class'     => '',
		'label'     => __( 'Share as activity', 'share-post-as-activity' ),
	) );

	return sprintf( '<button data-item-id="%d" data-share-url="%s" class="share-post-as-activity %s">%s</button>', $args['item_id'], $args['share_url'], $args['class'], $args['label'] );
}