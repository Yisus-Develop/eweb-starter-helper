<?php
/**
 * GitHub Update System for EWEB Starter Helper
 * Allows the plugin to receive updates directly from a GitHub repository.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EWEB_SH_Updater {

	private $file;
	private $plugin_slug;
	private $github_user;
	private $github_repo;
	private $github_response;

	public function __construct( $file, $github_user, $github_repo ) {
		$this->file = $file;
		$this->plugin_slug = plugin_basename( $file );
		$this->github_user = $github_user;
		$this->github_repo = $github_repo;

		add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'check_update' ] );
		add_filter( 'plugins_api', [ $this, 'plugin_popup' ], 10, 3 );
		add_filter( 'upgrader_post_install', [ $this, 'after_install' ], 10, 3 );
	}

	/**
	 * Check for updates in GitHub
	 */
	public function check_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$remote = $this->get_github_data();

		if ( $remote && version_compare( EWEB_SH_VERSION, $remote->tag_name, '<' ) ) {
			$obj = new stdClass();
			$obj->slug = 'eweb-starter-helper';
			$obj->new_version = $remote->tag_name;
			$obj->url = 'https://github.com/' . $this->github_user . '/' . $this->github_repo;
			$obj->package = $remote->zipball_url;

			$transient->response[ $this->plugin_slug ] = $obj;
		}

		return $transient;
	}

	/**
	 * Detailed information in the WordPress update popup
	 */
	public function plugin_popup( $result, $action, $args ) {
		if ( $action !== 'plugin_information' || ! isset( $args->slug ) || $args->slug !== 'eweb-starter-helper' ) {
			return $result;
		}

		$remote = $this->get_github_data();
		if ( ! $remote ) {
			return $result;
		}

		$res = new stdClass();
		$res->name = 'EWEB - Starter Helper';
		$res->slug = 'eweb-starter-helper';
		$res->version = $remote->tag_name;
		$res->author = 'Yisus Develop';
		$res->homepage = 'https://enlaweb.co/';
		$res->download_link = $remote->zipball_url;
		$res->sections = [
			'description' => 'Essential initial setup for WordPress projects: Safe SVGs, Elementor cleanup, and performance optimizations.',
			'changelog'   => $remote->body,
		];

		return $res;
	}

	/**
	 * Post-install cleanup: Ensure the folder name is correct
	 */
	public function after_install( $response, $hook_extra, $result ) {
		global $wp_filesystem;
		$install_directory = plugin_dir_path( $this->file );
		$wp_filesystem->move( $result['destination'], $install_directory );
		$result['destination'] = $install_directory;
		return $result;
	}

	/**
	 * Fetch data from GitHub Releases API
	 */
	private function get_github_data() {
		if ( ! empty( $this->github_response ) ) {
			return $this->github_response;
		}

		$url = "https://api.github.com/repos/{$this->github_user}/{$this->github_repo}/releases/latest";
		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$this->github_response = json_decode( wp_remote_retrieve_body( $response ) );
		return $this->github_response;
	}
}
