<?php
/**
 * Plugin Name: Share Post as Activity
 * Description: Share page/post as activity.
 * Author: BuddyDev
 */

// No direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Share_Post_As_Activity
 */
class Share_Post_As_Activity {

	/**
	 * Class instance
	 *
	 * @var Share_Post_As_Activity
	 */
	private static $instance = null;

	/**
	 * The constructor.
	 */
	private function __construct() {
		$this->setup();
	}

	/**
	 * Class instance
	 *
	 * @return Share_Post_As_Activity
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Setup callback needed
	 */
	private function setup() {
		add_filter( 'the_content', array( $this, 'modify_content' ) );

		add_action( 'wp_ajax_page_activity_share', array( $this, 'process' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
	}

	/**
	 * Modify content
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function modify_content( $content ) {

		if ( ! function_exists( 'buddypress' ) || ! bp_is_active( 'activity' ) ) {
			return $content;
		}

		if ( is_singular() ) {
			$content = sprintf( '<button data-item-id="%d" data-share-url="%s" class="share-post-as-activity">%s</button>', get_the_ID(), get_permalink(), __( 'Share as activity', 'share-post-as-activity' ) ) . $content;
		}

		return $content;
	}

	/**
	 * Process page activity share request
	 */
	public function process() {

		check_ajax_referer( 'share-post-as-activity' );

		if ( ! is_user_logged_in() || empty( $_POST['share_url'] ) || empty( $_POST['item_id'] ) ) {
			wp_send_json_error( __( 'Invalid request', 'share-post-as-activity' ) );
		}

		$post = wp_unslash( $_POST );

		$add = bp_activity_add(
			array(
				'action'    => __( 'Activity shared', 'share-post-as-activity' ),
				'content'   => esc_url( wp_unslash( $post['share_url'] ) ),
				'component' => 'members',
				'type'      => 'new_post_activity_share',
				'item_id'   => absint( $post['item_id'] ),
			)
		);

		if ( is_wp_error( $add ) ) {
			wp_send_json_error( $add->get_error_message() );
		}

		do_action( 'post_shared_as_activity', $post['item_id'] );

		wp_send_json_success( __( 'Shared successfully', 'share-post-as-activity' ) );
	}

	/**
	 * Load assets
	 */
	public function load_assets() {
		wp_enqueue_script(
			'share_post_as_activity_js',
			plugin_dir_url( __FILE__ ) . 'assets/js/share-post-as-activity.js',
			array( 'jquery' ),
			'1.0.1',
			true
		);

		wp_localize_script(
			'share_post_as_activity_js',
			'SHARE_POST_AS_ACTIVITY',
			array(
				'_nonce' => wp_create_nonce( 'share-post-as-activity' ),
			)
		);
	}
}

Share_Post_As_Activity::get_instance();