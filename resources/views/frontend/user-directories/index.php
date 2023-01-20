<?php

use SmartDirectory\Bootstrap\System\View\View;

defined( 'ABSPATH' ) || exit;

?>
<div class="smart-directory directory-list-body" data-api="smart-directory/v1/directories/user-directories">
	<?php
	if ( is_user_logged_in() ) {

		global $wpdb;

		$results         = $wpdb->get_results( $wpdb->prepare( "SELECT COUNT( * ) AS num_posts FROM {$wpdb->prefix}smart_directories WHERE author_id=%d", get_current_user_id() ) );
		$total_directory = isset( $results[0]->num_posts ) ? $results[0]->num_posts : 0;
		?>
	<div class="overflow-hidden">
		<table class="border border-[#EDF1F7] table-auto w-full text-base">
			<thead class="bg-[#F51957]">
				<tr>
					<th class="border-b font-semibold p-4 pr-8 pb-3 text-white text-left"><?php esc_html_e( 'SL', 'smartdirectory' ); ?></th>
					<th class="border-b font-semibold p-4 pr-8 pb-3 text-white text-left"><?php esc_html_e( 'Title', 'smartdirectory' ); ?></th>
					<th class="border-b font-semibold p-4 pr-8 pb-3 text-white text-left"><?php esc_html_e( 'Content', 'smartdirectory' ); ?></th>
					<th class="border-b font-semibold p-4 pr-8 pb-3 text-white text-left"><?php esc_html_e( 'Status', 'smartdirectory' ); ?></th>
					<th class="border-b font-semibold p-4 pr-8 pb-3 text-white text-left"><?php esc_html_e( 'Preview', 'smartdirectory' ); ?></th>
					<th class="border-b font-semibold p-4 pr-8 pb-3 text-white text-left"><?php esc_html_e( 'Submission Date', 'smartdirectory' ); ?></th>
				</tr>
			</thead>
			<tbody class="directory-list" data-total="<?php echo esc_attr( $total_directory ); ?>">
			<?php View::render( 'frontend/user-directories/list' ); ?>
			</tbody>
		</table>
	</div>
	<div class="pt-2 preloader hidden">
		<svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-[#F51957]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
			<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
			<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
		</svg>
	</div>
	<div class="pagination mt-6"></div>
	<?php } else { ?>
		<div class="p-7 border border-danger/90 rounded-sm bg-danger/10">
			<span class="text-danger/90">
				<?php esc_html_e( 'Please login to see your directories', 'smartdirectory' ); ?>
				<a class="underline" href="<?php echo esc_url( wp_login_url() ); ?>">
					<?php esc_html_e( 'Click here to login', 'smartdirectory' ); ?>
				</a>
			</span>
		</div>
	<?php } ?>
</div>
