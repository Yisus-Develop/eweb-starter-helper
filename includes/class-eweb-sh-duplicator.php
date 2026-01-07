<?php
/**
 * Duplicator Class
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EWEB_SH_Duplicator {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_filter( 'post_row_actions', [ $this, 'add_duplicate_link' ], 10, 2 );
		add_filter( 'page_row_actions', [ $this, 'add_duplicate_link' ], 10, 2 );
		add_action( 'admin_action_eweb_sh_duplicate_post', [ $this, 'handle_duplicate_action' ] );
	}

	/**
	 * Add "Duplicate" link to post row actions
	 */
	public function add_duplicate_link( $actions, $post ) {
		if ( current_user_can( 'edit_posts' ) ) {
			$actions['duplicate'] = sprintf(
				'<a href="%s" title="%s" rel="permalink">%s</a>',
				wp_nonce_url( admin_url( 'admin.php?action=eweb_sh_duplicate_post&post=' . $post->ID ), 'eweb_sh_duplicate_post_' . $post->ID ),
				__( 'Duplicate this item', 'eweb-starter-helper' ),
				__( 'Duplicate', 'eweb-starter-helper' )
			);
		}
		return $actions;
	}

	/**
	 * Handle the duplication action
	 */
	public function handle_duplicate_action() {
		if ( ! isset( $_GET['post'] ) || ! isset( $_GET['_wpnonce'] ) ) {
			wp_die( __( 'Insufficient parameters.', 'eweb-starter-helper' ) );
		}

		$post_id = absint( $_GET['post'] );
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'eweb_sh_duplicate_post_' . $post_id ) ) {
			wp_die( __( 'Security check failed.', 'eweb-starter-helper' ) );
		}

		$post = get_post( $post_id );
		if ( ! $post ) {
			wp_die( __( 'Post does not exist.', 'eweb-starter-helper' ) );
		}

		$new_post_id = $this->duplicate_post( $post );

		if ( is_wp_error( $new_post_id ) ) {
			wp_die( $new_post_id->get_error_message() );
		}

		wp_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );
		exit;
	}

	/**
	 * Duplicate post logic
	 */
	private function duplicate_post( $post ) {
		$current_user = wp_get_current_user();
		$new_post_args = [
			'post_author'    => $current_user->ID,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => sprintf( __( '%s (Copy)', 'eweb-starter-helper' ), $post->post_title ),
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order,
		];

		$new_post_id = wp_insert_post( $new_post_args );

		if ( $new_post_id && ! is_wp_error( $new_post_id ) ) {
			// Duplicate Taxonomies
			$taxonomies = get_object_taxonomies( $post->post_type );
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post->ID, $taxonomy, [ 'fields' => 'slugs' ] );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}

			// Duplicate Meta Data
			$post_meta_data = get_post_meta( $post->ID );
			foreach ( $post_meta_data as $key => $values ) {
				foreach ( $values as $value ) {
					add_post_meta( $new_post_id, $key, maybe_unserialize( $value ) );
				}
			}
		}

		return $new_post_id;
	}
}
