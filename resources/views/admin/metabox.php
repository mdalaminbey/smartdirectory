<?php

defined( 'ABSPATH' ) || exit;

$preview_image_id = get_post_meta( $post_id, 'preview_image', true );

?>

<div class="smart-directory">
<table class="table">
	<tbody class="text-left">
		<tr>
			<th class="text-sm w-40"><?php esc_html_e( 'Google map link', 'smartdirectory' )?><span class="text-danger">*</span></th>
			<td>
				<input name="map_link" value="<?php echo esc_attr(get_post_meta($post_id, 'map_link', true))?>" type="url" required placeholder="Enter google map link" class="form-input bg-[#EDF1F7] mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-1.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary">
			</td>
		</tr>
		<tr>
			<th></th>
			<td><?php echo wp_get_attachment_image( $preview_image_id, ); ?></td>
		</tr>
		<tr>
			<th class="text-sm w-40"><?php esc_html_e( 'Choose preview image' )?></th>
			<td>
				<input name="preview_image" type="file" class="form-input bg-[#EDF1F7] mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-1.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary">
			</td>
		</tr>
	</tbody>
</table>
</div>
 <!-- WordPress forms do not have an enctype. So we are adding it by js. -->
<script>
	let form = document.getElementById('post');
	form.setAttribute('enctype', 'multipart/form-data');
</script>