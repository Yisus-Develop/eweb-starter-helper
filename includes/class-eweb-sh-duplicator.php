<?php
/**
 * Post Duplicator Module.
 *
 * Provides functionality to clone posts, pages, and custom post types.
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class EWEB_SH_Duplicator
 */
class EWEB_SH_Duplicator {

	/**
	 * Instance of this class.
	 *
	 * @var EWEB_SH_Duplicator|null
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return EWEB_SH_Duplicator
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_action_eweb_sh_duplicate_post_as_draft', array( $this, 'duplicate_post_as_draft' ) );
		add_filter( 'post_row_actions', array( $this, 'add_duplicate_link' ), 10, 2 );
		add_filter( 'page_row_actions', array( $this, 'add_duplicate_link' ), 10, 2 );
	}

	/**
	 * Add duplicate link to post row actions.
	 *
	 * @param array   $actions Post actions.
	 * @param WP_Post $post    Post object.
	 * @return array
	 */
	public function add_duplicate_link( $actions, $post ) {
		if ( current_user_can( 'edit_posts' ) ) {
			$actions['duplicate'] = sprintf(
				'<a href="%s" title="%s" rel="permalink">%s</a>',
				wp_nonce_url( admin_url( 'admin.php?action=eweb_sh_duplicate_post_as_draft&post=' . $post->ID ), 'eweb_sh_duplicate_post_' . $post->ID ),
				esc_attr__( 'Duplicate this item', 'eweb-starter-helper' ),
				esc_html__( 'Duplicate', 'eweb-starter-helper' )
			);
		}
		return $actions;
	}

	/**
	 * Duplicate a post as a draft and redirect to the edit screen.
	 */
	public function duplicate_post_as_draft() {
		// Security Check.
		$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;

		if ( ! $post_id || ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), 'eweb_sh_duplicate_post_' . $post_id ) ) {
			wp_die( esc_html__( 'Security check failed. Please try again.', 'eweb-starter-helper' ) );
		}

		// Get original post.
		$post = get_post( $post_id );

		if ( null === $post ) {
			wp_die( esc_html__( 'Post not found.', 'eweb-starter-helper' ) );
		}

		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;

		// New post data.
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => sprintf( esc_html__( '%s (Copy)', 'eweb-starter-helper' ), $post->post_title ),
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order,
		);

		// Insert the post.
		$new_post_id = wp_insert_post( $args );

		if ( ! is_wp_error( $new_post_id ) ) {
			// Copy taxonomies.
			$taxonomies = get_object_taxonomies( $post->post_type );
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}

			// Copy meta data.
			$post_meta = get_post_custom( $post_id );
			foreach ( $post_meta as $key => $values ) {
				foreach ( $values as $value ) {
					add_post_meta( $new_post_id, $key, maybe_unserialize( $value ) );
				}
			}

			// Redirect to edit screen.
			wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
			exit;
		} else {
			wp_die( esc_html__( 'Failed to create duplicate.', 'eweb-starter-helper' ) );
		}
	}
}
