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

$directories = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}smart_directories WHERE author_id=%d ORDER BY ID DESC LIMIT %d OFFSET %d", get_current_user_id(), $directory_per_page, $offset ) );

if ( ! empty( $directories ) ) {

	$sl = ( $directory_per_page * $current_page ) - $directory_per_page;

	foreach ( $directories as $key => $directory ) :

		$preview_image_id = get_post_meta( $directory->ID, 'preview_image', true );
		?>
		<tr class="<?php echo ( ( $key % 2 ) === 0 ) ? 'bg-[#EDF1F7]/40' : 'bg-white'; ?>">
			<td class="border-b border-slate-100 p-4 text-slate-500">
				<?php echo ($key + $sl + 1) //phpcs:ignore ?>
			</td>
			<td class="border-b border-slate-100 p-4 text-slate-500">
			<?php echo esc_html( $directory->title ); ?>
			</td>
			<td class="border-b border-slate-100 p-4 text-slate-500">
				<?php echo esc_html( $directory->content ); ?>
			</td>
			<td class="border-b border-slate-100 p-4 text-slate-500">
				<span class="directory-status directory-status-<?php echo esc_attr( $directory->status ); ?>">
					<?php echo esc_html( $directory->status ); ?>
				</span>
			</td>
			<td class="border-b border-slate-100 p-4 text-slate-500">
				<?php echo wp_get_attachment_image( $directory->preview_image_id ); ?>
			</td>
			<td class="border-b border-slate-100 p-4 text-slate-500">
				<?php echo esc_html( $directory->submission_date ); ?>
			</td>
		</tr>
		<?php
	endforeach;
} else {
	?>
		<tr class="bg-white">
			<td class="border-b border-slate-100 p-4 text-slate-500">
				<?php esc_html_e( 'No directory found', 'smartdirectory' ); ?>
			</td>
		</tr>
	<?php
}
?>
