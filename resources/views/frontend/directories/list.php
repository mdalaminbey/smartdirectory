<?php

defined( 'ABSPATH' ) || exit;

//phpcs:ignore WordPress.Security.NonceVerification.Recommended
$current_page       = ! empty( $_REQUEST['directory-page'] ) ? intval( $_REQUEST['directory-page'] ) : 1;
$directory_per_page = 12;
if ( $current_page < 1 ) {
	$current_page = 1;
}

$offset = ( $current_page - 1 ) * $directory_per_page;

global $wpdb;

$directories = $wpdb->get_results( $wpdb->prepare( "SELECT ID, title, content, preview_image_id, map_link FROM {$wpdb->prefix}smart_directories WHERE status = 'approved' ORDER BY ID DESC LIMIT %d OFFSET %d", $directory_per_page, $offset ) );

if ( empty( $directories ) ) {
	echo 'No directory found';
	return;
}
foreach ( $directories as $directory ) : ?>
<div class="border border-[#CBD5E1] rounded">
	<figure>
		<?php echo wp_get_attachment_image( $directory->preview_image_id, 'full' ); ?>
	</figure>
	<div class="p-4">
		<h4 class="capitalize text-lg font-bold hover:text-[#F51957]">
			<?php echo esc_html( $directory->title ); ?>
		</h4>
		<p class="text-gray-500 pb-3">
			<?php echo wp_kses( $directory->content, smart_directory_kses_allowed() ); ?>
		</p>
		<a class="bg-[#F51957] p-2 rounded-lg text-white !no-underline" target="_blank" href="<?php echo esc_url( $directory->map_link ); ?>">
			<svg class="w-5 inline-block fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 122.88">
				<path d="M61.44,0A61.46,61.46,0,1,1,18,18,61.21,61.21,0,0,1,61.44,0ZM87.06,35.79,66.39,96.94l-9.46-31-31-9.46L87.06,35.79ZM99.15,23.73a53.33,53.33,0,1,0,15.62,37.71A53.16,53.16,0,0,0,99.15,23.73Z"/>
			</svg>
			<span class="text-base pl-1">
				<?php esc_html_e( 'Navigate', 'smart-directory' ); ?>
			</span>
		</a>
	</div>
</div>
<?php endforeach; ?>
