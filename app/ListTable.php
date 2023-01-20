<?php
/**
 * Directory list table
 *
 * @package  SmartDirectory
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since    1.0.0
 */

namespace SmartDirectory\App;

defined( 'ABSPATH' ) || exit;

use WP_List_Table;

class ListTable extends WP_List_Table {

	public $items;
	public $per_page;
	public $current_page_no;
	public $current_status;
	private $table_data;
	private $top_page;
	private $current_page_slug;
	private $counts;
	public $search = '';

	/**
	 * Constructor.
	 *
	 * @param array|string $args Array or string of arguments.
	 */
	public function __construct( $args = array() ) {

		parent::__construct( $args );

		$this->top_page          = 'smart-directory_page';
		$this->current_page_slug = 'smart-directory-listings';

		$nonce_action = "bulk-{$this->top_page}_{$this->current_page_slug}";

		if ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), $nonce_action ) ) {
			/**
			 * Sending request arguments to avoid nonce reverification
			 */
			$this->process_bulk_action( $_REQUEST );
			$this->search = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '';
		}

		$this->prepare_data();
	}

	/**
	 * Preparing the required data for the table
	 *
	 * @return void
	 */
	public function prepare_data() {

		$this->counts          = smart_directory_count_total();
		$this->per_page        = $this->get_items_per_page( 'directory_per_page' );
		$this->current_status  = $this->get_current_status();
		$this->current_page_no = $this->get_pagenum();
		$this->table_data      = $this->get_table_data();
	}

	/**
	 * Gets a list of columns.
	 *
	 * The format is:
	 * - `'internal-name' => 'Title'`
	 *
	 * @return array
	 */
	public function get_columns() {

		$columns = array(
			'cb'              => '<input type="checkbox" />',
			'title'           => esc_html__( 'Title', 'smartdirectory' ),
			'content'         => esc_html__( 'Content', 'smartdirectory' ),
			'status'          => esc_html__( 'Status', 'smartdirectory' ),
			'author_id'       => esc_html__( 'Author', 'smartdirectory' ),
			'submission_date' => esc_html__( 'Submission Date', 'smartdirectory' ),
		);
		return $columns;
	}

	/**
	 * Gets the list of views available on this table.
	 *
	 * The format is an associative array:
	 * - `'id' => 'link'`
	 *
	 * @return array
	 */
	protected function get_views() {

		$counts = smart_directory_count_total();
		$links  = array();
		foreach ( $counts as $status => $count ) {
			$links[ $status ] = '<a href="' . admin_url( 'admin.php?page=smart-directory-listings' ) . '&status=' . $status . '" class="' . ( ( $status === $this->current_status ) ? 'current' : '' ) . '" aria-current="page">' . ucfirst( $status ) . ' <span class="count">' . $count . '</span></a>';
		}
		return $links;
	}

	/**
	 * Prepares the list of items for displaying.
	 */
	public function prepare_items() {

		$hidden_columns_meta_key = "manage{$this->top_page}_{$this->current_page_slug}columnshidden";

		$hidden_columns = get_user_meta( get_current_user_id(), $hidden_columns_meta_key, true );

		if ( ! is_array( $hidden_columns ) ) {
			$hidden_columns = array();
		}

		$columns               = $this->get_columns();
		$sortable              = $this->get_sortable_columns();
		$primary               = 'title';
		$this->_column_headers = array( $columns, $hidden_columns, $sortable, $primary );

		/**
		 * Pagination
		 */
		$total = isset( $this->counts[ $this->current_status ] ) ? $this->counts[ $this->current_status ] : 0;

		$this->set_pagination_args(
			array(
				'total_items' => $total,
				'per_page'    => $this->per_page,
				'total_pages' => ceil( $total / $this->per_page ),
			)
		);

		$this->items = $this->table_data;
	}

	/**
	 * @param object|array $item row item.
	 * @param string       $column_name db column name.
	 */
	protected function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			case 'title':
				return '<strong>' . $item[ $column_name ] . '</strong>';
			case 'content':
			case 'submission_date':
				return $item[ $column_name ];
			case 'status':
				return '<div class="smart-directory" style="margin-top:8px;"><span class="directory-status directory-status-' . $item[ $column_name ] . '">' . $item[ $column_name ] . '</span></span></div>';
			case 'author_id':
				return $item['author_name'];
			default:
				return '';
		}
	}

	/**
	 * @param object|array $item row item.
	 */
	protected function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="element[]" value="%s" />', $item['ID'] );
	}

	/**
	 * Gets a list of sortable columns.
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {

		$sortable_columns = array(
			'title'           => array( 'title', true ),
			'status'          => array( 'status', true ),
			'author_id'       => array( 'author_id', true ),
			'submission_date' => array( 'submission_date', true ),
		);
		return $sortable_columns;
	}

	/**
	 * Generates and display row actions links for the list table.
	 *
	 * @since 4.3.0
	 *
	 * @param object|array $item        The item being acted upon.
	 * @param string       $column_name Current column name.
	 * @param string       $primary     Primary column name.
	 * @return string The row actions HTML, or an empty string
	 *                if the current column is not the primary column.
	 */
	protected function handle_row_actions( $item, $column_name, $primary ) {

		if ( $column_name !== $primary ) {
			return;
		}

		$nonce = wp_create_nonce( 'smart-directory-row' );

		$actions = array(
			'edit'                   => sprintf( '<a href="?page=%s&action=%s&element=%s&status=%s&_row_nonce=%s">' . esc_html__( 'Edit', 'smartdirectory' ) . '</a>', 'smart-directory-listings', 'edit', $item['ID'], $this->current_status, $nonce ),
			'delete'                 => sprintf( '<a href="?page=%s&action=%s&element=%s&status=%s&_row_nonce=%s">' . esc_html__( 'Delete', 'smartdirectory' ) . '</a>', 'smart-directory-listings', 'delete', $item['ID'], $this->current_status, $nonce ),
			'delete-with-attachment' => sprintf( '<a style="color:#b32d2e" href="?page=%s&action=%s&element=%s&status=%s&_row_nonce=%s">' . esc_html__( 'Delete With Attachment', 'smartdirectory' ) . '</a>', 'smart-directory-listings', 'delete-with-attachment', $item['ID'], $this->current_status, $nonce ),
		);

		$actions = apply_filters( 'smart_directory_row_actions', $actions );

		return $this->row_actions( $actions );
	}

	/**
	 * Retrieves the list of bulk actions available for this table.
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {

		$actions = array(
			'delete_all'                 => esc_html__( 'Delete', 'smartdirectory' ),
			'delete_all_with_attachment' => esc_html__( 'Delete With Attachment', 'smartdirectory' ),
			'approve_all'                => esc_html__( 'Approve', 'smartdirectory' ),
			'reject_all'                 => esc_html__( 'Reject', 'smartdirectory' ),
			'pending_all'                => esc_html__( 'Pending', 'smartdirectory' ),
		);

		switch ( $this->current_status ) {
			case 'approved':
				unset( $actions['approve_all'] );
				break;
			case 'pending':
				unset( $actions['pending_all'] );
				break;
			case 'rejected':
				unset( $actions['reject_all'] );
				break;
		}

		return apply_filters( 'smart_directory_bulk_actions', $actions );
	}

	/**
	 * Get table data form DB
	 *
	 * @return array
	 */
	private function get_table_data() {

		global $wpdb;

		$order   = $this->get_request_value( 'order', array( 'asc', 'desc' ), 'desc' );
		$orderby = $this->get_request_value( 'orderby', array( 'ID', 'title', 'status' ), 'ID' );

		$query = "SELECT directory.*, user.display_name as author_name FROM {$wpdb->prefix}smart_directories AS directory LEFT JOIN {$wpdb->prefix}users AS user ON directory.author_id = user.ID";

		if ( 'all' !== $this->current_status ) {
			$status_query = $wpdb->prepare( ' WHERE status=%s', $this->current_status );
		} else {
			$status_query = ' WHERE 1=1';
		}

		$query .= $status_query;

		$offset = ( $this->current_page_no - 1 ) * $this->per_page;
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$pagination_query = $wpdb->prepare( "ORDER BY directory.{$orderby} {$order} LIMIT %d OFFSET %d", $this->per_page, $offset );

		if ( ! empty( $this->search ) ) {

			$search = "%{$this->search}%";

			/**
			 * Count search results
			 */
			$count = $wpdb->get_var(
				$wpdb->prepare(
					//phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $status_query Already prepared
					"SELECT COUNT(*) FROM {$wpdb->prefix}smart_directories AS directory LEFT JOIN {$wpdb->prefix}users AS user ON directory.author_id = user.ID {$status_query} AND (
                    directory.title LIKE %s OR
                    directory.content LIKE %s OR
                    directory.status LIKE %s OR
                    user.display_name LIKE %s)",
					$search,
					$search,
					$search,
					$search
				)
			);

			$this->counts[ $this->current_status ] = $count;

			return $wpdb->get_results(
				$wpdb->prepare(
					//phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $query Already prepared
					"{$query} AND (
                    directory.title LIKE %s OR
                    directory.content LIKE %s OR
                    directory.status LIKE %s OR
                    user.display_name LIKE %s) {$pagination_query}", //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $pagination_query Already prepared
					$search,
					$search,
					$search,
					$search //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $pagination_query Already prepared
				),
				ARRAY_A
			);
		} else {
			// $query and $pagination_query already prepared
            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			return $wpdb->get_results( "{$query} {$pagination_query}", ARRAY_A );
		}
	}

	/**
	 * Get current page directory status
	 *
	 * @return string
	 */
	protected function get_current_status() {
		return $this->get_request_value( 'status', $this->available_status(), 'all' );
	}

	/**
	 * Get the request value if the given $haystack matches
	 *
	 * @param string  $needle request key.
	 * @param array   $haystack available data .
	 * @param boolean $default if the value will not match then the $default will be returned.
	 * @return string|bool
	 */
	public function get_request_value( string $needle, array $haystack, $default = false ) {

		$status = $default;

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Already verified in the constructor
		if ( ! empty( $_REQUEST[ $needle ] ) && in_array( $_REQUEST[ $needle ], $haystack, true ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$status = sanitize_text_field( wp_unslash( $_REQUEST[ $needle ] ) );
		}

		return $status;
	}

	/**
	 * SmartDirectory available directory status
	 *
	 * @return array
	 */
	public function available_status() {
		return apply_filters( 'smart_directory_available_status', array( 'approved', 'rejected', 'pending' ) );
	}

	/**
	 * Process table bulk action
	 *
	 * @param array $request $_REQUEST array.
	 * @return void
	 */
	public function process_bulk_action( array $request ) {

		if ( empty( $request['element'] ) ) {
			return;
		}

		$ids = $request['element'];

		/**
		 * Fires before smartdirectory bulk action are processed
		 */
		do_action( 'smart_directory_before_process_bulk_action', $ids, $this->current_action() );

		switch ( $this->current_action() ) {
			case 'approve_all':
				$this->update_status( $ids );
				break;
			case 'reject_all':
				$this->update_status( $ids, 'rejected' );
				break;
			case 'pending_all':
				$this->update_status( $ids, 'pending' );
				break;
			case 'delete_all':
				$this->delete_items( $ids );
				break;
			case 'delete_all_with_attachment':
				$this->delete_items( $ids, true );
				break;
		}

		wp_cache_delete( 'smart-directories-total' );

		wp_safe_redirect( $this->get_redirect_url() );
		exit;
	}

	/**
	 * Process row action
	 *
	 * @param array $request $_REQUEST array.
	 */
	public function process_row_action( $request ) {
		/**
		 * Fires before smartdirectory row action are processed
		 */
		do_action( 'smart_directory_before_process_action', $request['element'], $request['action'] );

		switch ( $request['action'] ) {
			case 'delete':
				$this->delete_items( array( $request['element'] ) );
				break;

			case 'delete-with-attachment':
				$this->delete_items( array( $request['element'] ), true );
				break;
		}

		wp_safe_redirect( $this->get_redirect_url() );
		exit;
	}

	/**
	 * Permanently delete directories
	 *
	 * @param array   $ids directory ids.
	 * @param boolean $with_attachment If the preview image is to be removed. then set it `true`.
	 * @return void
	 */
	private function delete_items( array $ids, bool $with_attachment = false ) {

		global $wpdb;

		$query = $wpdb->prepare( "FROM {$wpdb->prefix}smart_directories WHERE ID = %d", $ids[0] );

		unset( $ids[0] );

		foreach ( $ids as $id ) {
			$query .= $wpdb->prepare( ' OR ID = %d', $id );
		}

		if ( $with_attachment ) {
			$select_query = 'SELECT * ' . $query;

			// $select_query variable already prepared
			//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$results = $wpdb->get_results( $select_query, ARRAY_A );

			foreach ( $results as $directory ) {

				wp_delete_attachment( $directory['preview_image_id'] );
			}
		}
		// $query variable already prepared
        //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$wpdb->query( 'DELETE ' . $query );
	}

	/**
	 * Update directories status
	 *
	 * @param array  $ids directory ids.
	 * @param string $status directory status.
	 * @return void
	 */
	private function update_status( array $ids, string $status = 'approved' ) {

		global $wpdb;

		$query = $wpdb->prepare( "UPDATE {$wpdb->prefix}smart_directories SET status = %s WHERE ID = %d", $status, $ids[0], );

		unset( $ids[0] );

		foreach ( $ids as $id ) {
			$query .= $wpdb->prepare( ' OR ID = %d', $id );
		}

		// $query variable already prepared
        //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$wpdb->query( $query );
	}

	/**
	 * URL to redirect after process action
	 *
	 * @return string
	 */
	private function get_redirect_url(): string {
		return admin_url( 'admin.php?page=smart-directory-listings' ) . '&paged=' . $this->get_pagenum() . '&status=' . $this->get_current_status();
	}
}
