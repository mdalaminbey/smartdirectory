<?php

defined( 'ABSPATH' ) || exit;

//phpcs:ignore WordPress.Security.NonceVerification.Recommended
$current_page = isset( $_REQUEST['directory-page'] ) ? intval( $_REQUEST['directory-page'] ) : 1;
$offset       = ( $current_page - 1 ) * 12;
$directories  = get_posts( [
	'post_type'      => smart_directory_post_type(),
	'posts_per_page' => 12,
	'offset'         => $offset
] );

if(empty($directories)) {
	echo "No directory found";
	return;
}
foreach($directories as $directory):
	$preview_image_id = get_post_meta( $directory->ID, 'preview_image', true );
?>
<div class="border border-[#CBD5E1] rounded">
	<figure>
		<?php echo wp_get_attachment_image( $preview_image_id, 'full'); ?>
	</figure>
	<div class="p-4">
		<h4 class="capitalize text-lg font-bold hover:text-[#F51957]">
			<?php echo esc_html($directory->post_title); ?>
		</h4>
		<p class="text-gray-500 pb-3">
			<?php echo wp_kses($directory->post_content, smart_directory_kses_allowed()); ?>
		</p>
		<a class="bg-[#F51957] p-2 rounded-lg text-white !no-underline" target="_blank" href="<?php echo esc_url( get_post_meta( $directory->ID, 'map_link', true ) ) ?>">
			<svg class="w-5 inline-block fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 122.88">
				<path d="M61.44,0A61.46,61.46,0,1,1,18,18,61.21,61.21,0,0,1,61.44,0ZM87.06,35.79,66.39,96.94l-9.46-31-31-9.46L87.06,35.79ZM99.15,23.73a53.33,53.33,0,1,0,15.62,37.71A53.16,53.16,0,0,0,99.15,23.73Z"/>
			</svg>
			<span class="text-base pl-1">
				<?php esc_html_e('Navigate', 'smart-directory')?>
			</span>
		</a>
	</div>
</div>
<?php endforeach; ?>