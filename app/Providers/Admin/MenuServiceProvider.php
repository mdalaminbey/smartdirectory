<?php
/**
 * The menu service provider class handles admin related all menus
 *
 * @package  SmartDirectory
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since    1.0.0
 */

namespace SmartDirectory\App\Providers\Admin;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\App\ListTable;
use SmartDirectory\Bootstrap\System\Contracts\ServiceProvider;
use SmartDirectory\Bootstrap\System\View\View;

class MenuServiceProvider extends ServiceProvider {

	public $list_table;

	public $is_edit_page = false;

	public $edit_post_id;

	public $directory;

	/**
	 * The boot method is called immediately after the service provider calls the constructor
	 *
	 * @return void
	 */
	public function boot() {
		add_action( 'admin_menu', array( $this, 'action_admin_menu' ) );
		add_filter( 'set_screen_option_directory_per_page', array( $this, 'set_per_page' ), 10, 3 );
	}

	/**
	 * Filters a screen option value before it is set.
	 *
	 * The dynamic portion of the hook name, `$option`, refers to the option name.
	 *
	 * Returning false from the filter will skip saving the current option.
	 *
	 * @since 5.4.2
	 *
	 * @see set_screen_options()
	 *
	 * @param mixed  $screen_option The value to save instead of the option value.
	 *                              Default false (to skip saving the current option).
	 * @param string $option        The option name.
	 * @param int    $value         The option value.
	 */
	public function set_per_page( $screen_option, $option, $value ) {
		return $value;
	}

	/**
	 * Fires before the administration menu loads in the admin.
	 */
	public function action_admin_menu(): void {
		add_menu_page( esc_html__( 'Smart Directory', 'smartdirectory' ), esc_html__( 'Smart Directory', 'smartdirectory' ), 'manage_options', 'smartdirectory-menu', function () {}, 'dashicons-list-view', 5 );

		$listing_page_hook = add_submenu_page( 'smartdirectory-menu', esc_html__( 'All listings', 'superdocs' ), esc_html__( 'All listings', 'superdocs' ), 'manage_options', 'smart-directory-listings', array( $this, 'listings' ) );
		add_submenu_page( 'smartdirectory-menu', esc_html__( 'Add New', 'superdocs' ), esc_html__( 'Add New', 'superdocs' ), 'manage_options', 'smart-directory-add-new', array( $this, 'add_new' ) );

		remove_submenu_page( 'smartdirectory-menu', 'smartdirectory-menu' );

		add_action( "load-{$listing_page_hook}", array( $this, 'screen' ) );
	}

	/**
	 * Render add new directory page content
	 */
	public function add_new() {
		View::render( 'admin/add-new' );
	}

	/**
	 * Render directories or edit page content
	 */
	public function listings() {
		if ( $this->is_edit_page ) {
			View::render( 'admin/edit', array( 'directory' => $this->directory ) );
		} else {
			View::render( 'admin/listings', array( 'list_table' => $this->list_table ) );
		}
	}

	/**
	 * Load directories page top options or process directory row action
	 *
	 * @return void
	 */
	public function screen() {
		if ( isset( $_REQUEST['action'] ) && isset( $_REQUEST['element'] ) && isset( $_REQUEST['_row_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_row_nonce'] ) ), 'smart-directory-row' ) ) {

			$action = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) );
			switch ( $action ) {
				case 'edit':
					global $wpdb;
					$post_id = sanitize_text_field( wp_unslash( $_REQUEST['element'] ) );
					//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}smart_directories WHERE ID = %d", $post_id ) );

					if ( isset( $results[0] ) ) {
						$this->directory    = $results[0];
						$this->is_edit_page = true;
					} else {
						$this->screen_options();
					}
					break;

				default:
					$list_table = new ListTable();
					/**
					 * Sending request arguments to avoid nonce reverification
					 */
					$list_table->process_row_action( $_REQUEST );
					break;
			}
		} else {
			$this->screen_options();
		}
	}

	/**
	 * Load directories page top options
	 *
	 * @return void
	 */
	public function screen_options() {
		$this->list_table = new ListTable();

		$args = array(
			'label'   => esc_html__( 'Number of items per page:', 'smartdirectory' ),
			'default' => $this->list_table->get_items_per_page( 'directory_per_page' ),
			'option'  => 'directory_per_page',
		);

		add_screen_option( 'per_page', $args );
	}
}
