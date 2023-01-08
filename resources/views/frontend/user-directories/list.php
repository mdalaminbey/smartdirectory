<?php 

defined( 'ABSPATH' ) || exit;

//phpcs:ignore WordPress.Security.NonceVerification.Recommended
$current_page   = isset( $_REQUEST['directory-page'] ) ? intval( $_REQUEST['directory-page'] ) : 1;
$posts_per_page = 12;
$offset         = ( $current_page - 1 ) * $posts_per_page;
$args           = [
	'post_type'      => smart_directory_post_type(),
	'posts_per_page' => $posts_per_page,
	'offset'         => $offset,
	'author'         => get_current_user_id(),
	'post_status'    => ['publish', 'pending', 'draft']
];

$wp_query    = new \WP_Query($args);
$directories = $wp_query->posts;

if( empty( $directories ) ) {
	echo "No directory found";
	return;
}

$sl = ($posts_per_page * $current_page) - $posts_per_page;

foreach($directories as $key => $directory):
$preview_image_id = get_post_meta( $directory->ID, 'preview_image', true );
	?>
	<tr class="<?php echo (($key % 2) === 0 ) ? 'bg-[#EDF1F7]/40' : 'bg-white' ?>">
		<td class="border-b border-slate-100 p-4 text-slate-500">
			<?php echo ($key + $sl + 1) //phpcs:ignore ?>
		</td>
		<td class="border-b border-slate-100 p-4 text-slate-500">
		<?php echo esc_html($directory->post_title); ?>
		</td>
		<td class="border-b border-slate-100 p-4 text-slate-500">
			<?php echo esc_html($directory->post_content); ?>
		</td>
		<td class="border-b border-slate-100 p-4 text-slate-500">
			<?php echo esc_html($directory->post_status); ?>
		</td>
		<td class="border-b border-slate-100 p-4 text-slate-500">
			<?php echo wp_get_attachment_image($preview_image_id) ?>
		</td>
	</tr>
<?php endforeach; ?>