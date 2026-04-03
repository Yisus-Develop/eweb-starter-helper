<?php
/**
 * EWEB GitHub Updater.
 *
 * Professional class to handle automatic updates from GitHub.
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'EWEB_GitHub_Updater' ) ) {

	/**
	 * Class EWEB_GitHub_Updater
	 */
	class EWEB_GitHub_Updater {

		/**
		 * Config data.
		 *
		 * @var array
		 */
		private $config;

		/**
		 * GitHub response cache.
		 *
		 * @var object|null
		 */
		private $github_response;

		/**
		 * Constructor.
		 *
		 * @param array $config Configuration array.
		 */
		public function __construct( $config ) {
			$this->config = wp_parse_args(
				$config,
				array(
					'slug'               => '',
					'proper_folder_name' => '',
					'api_url'            => '',
					'raw_url'            => '',
					'github_url'         => '',
					'zip_url'            => '',
					'sslverify'          => true,
					'requires'           => '6.0',
					'tested'             => '6.4',
					'readme'             => 'readme.txt',
					'access_token'       => '',
				)
			);

			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
			add_filter( 'plugins_api', array( $this, 'get_info' ), 10, 3 );
			add_filter( 'upgrader_post_install', array( $this, 'post_install' ), 10, 3 );
		}

		/**
		 * Connect to GitHub API.
		 */
		private function get_repository_info() {
			if ( null !== $this->github_response ) {
				return;
			}

			$args = array(
				'timeout'    => 15,
				'sslverify'  => $this->config['sslverify'],
			);

			if ( ! empty( $this->config['access_token'] ) ) {
				$args['headers'] = array(
					'Authorization' => 'token ' . $this->config['access_token'],
				);
			}

			$request = wp_remote_get( $this->config['api_url'] . '/releases/latest', $args );

			if ( ! is_wp_error( $request ) ) {
				$res = json_decode( wp_remote_retrieve_body( $request ) );
				if ( is_object( $res ) ) {
					$this->github_response = $res;
				}
			}
		}

		/**
		 * Check for updates in the transient.
		 *
		 * @param object $transient Transient object.
		 * @return object
		 */
		public function check_update( $transient ) {
			if ( empty( $transient->checked ) ) {
				return $transient;
			}

			$this->get_repository_info();

			if ( $this->github_response && version_compare( $this->github_response->tag_name, $transient->checked[ $this->config['slug'] ], '>' ) ) {
				$obj              = new stdClass();
				$obj->slug        = $this->config['slug'];
				$obj->new_version = $this->github_response->tag_name;
				$obj->url         = $this->config['github_url'];
				$obj->package     = $this->config['zip_url'];

				$transient->response[ $this->config['slug'] ] = $obj;
			}

			return $transient;
		}

		/**
		 * Get plugin info for the popup.
		 *
		 * @param bool|object $result Result.
		 * @param string      $action Action.
		 * @param object      $args   Args.
		 * @return object|bool
		 */
		public function get_info( $result, $action, $args ) {
			if ( 'query_plugins' !== $action && 'plugin_information' !== $action ) {
				return false;
			}

			if ( $args->slug !== $this->config['slug'] ) {
				return $result;
			}

			$this->get_repository_info();

			if ( $this->github_response ) {
				$res                = new stdClass();
				$res->name          = $this->config['proper_folder_name'];
				$res->slug          = $this->config['slug'];
				$res->version       = $this->github_response->tag_name;
				$res->author        = 'Yisus Develop';
				$res->homepage      = $this->config['github_url'];
				$res->download_link = $this->config['zip_url'];

				// Add Icons and Banners Support.
				$res->icons = array(
					'1x' => $this->config['raw_url'] . '/assets/icon-128x128.png',
					'2x' => $this->config['raw_url'] . '/assets/icon-256x256.png',
				);
				$res->banners = array(
					'low'  => $this->config['raw_url'] . '/assets/banner-772x250.png',
					'high' => $this->config['raw_url'] . '/assets/banner-1544x500.png',
				);

				return $res;
			}

			return $result;
		}

		/**
		 * Cleanup after installation.
		 *
		 * @param bool  $true       True.
		 * @param array $hook_extra Hook extra.
		 * @param array $result     Result.
		 * @return array
		 */
		public function post_install( $true, $hook_extra, $result ) {
			global $wp_filesystem;
			$proper_destination = WP_PLUGIN_DIR . '/' . $this->config['proper_folder_name'];
			$wp_filesystem->move( $result['destination'], $proper_destination );
			$result['destination'] = $proper_destination;

			return $result;
		}
	}
}
