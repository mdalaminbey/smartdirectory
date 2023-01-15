<?php

defined( 'ABSPATH' ) || exit;

$user_id = get_current_user_id();

?>

<div class="smart-directory">
	<div class="mt-4 pb-10 mr-5 border ring-1 ring-slate-900/10 absolute left-2/4 -translate-x-1/2 bg-white p-5 w-[800px]">
		<h2 class="text-xl pb-2"><?php esc_html_e( 'Add New Directory', 'smartdirectory' ); ?></h2>
		<hr class="mb-4">
		<div class="mt-4">
			<form data-api="smart-directory/v1/admin/directories" data-method="POST" class="create-directory-form">
				<div class="notice-success mb-5 hidden">
					<div class="w-full text-base bg-success/20 text-success px-2 py-2 border border-success rounded-md">
						<?php esc_html_e( 'Directory created successfully!', 'smartdirectory' ); ?>
					</div>
				</div>
				<div class="notice-error mb-5 bg-danger/5 text-danger rounded p-4 hidden"></div>
				<div class="mb-5">
					<label class="block">
						<span class="text-base"><?php esc_html_e( 'Title', 'smartdirectory' ); ?></span>
						<span class="text-danger">*</span>
						<input type="text" name="title" placeholder="Enter title" required class="form-textarea bg-[#EDF1F7] mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-1.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary"/>
					</label>
				</div>
				<div class="mb-5">
					<label class="block">
						<span class="text-base"><?php esc_html_e( 'Content', 'smartdirectory' ); ?></span>
						<span class="text-danger">*</span>
						<textarea name="content" placeholder="Enter content" required class="form-input bg-[#EDF1F7] mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-1.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary"></textarea>
					</label>
				</div>
				<div class="mb-5">
					<label class="block">
						<span class="text-base"><?php esc_html_e( 'Google Map Link', 'smartdirectory' ); ?></span>
						<span class="text-danger">*</span>
						<input name="map_link" type="url" placeholder="Enter google map link" required class="form-input bg-[#EDF1F7] mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-1.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary" />
					</label>
				</div>
				<div class="mb-5">
					<label class="block">
						<span class="text-base"><?php esc_html_e( 'Author', 'smartdirectory' ); ?></span>
						<span class="text-danger">*</span>
						<br />
						<select name="author" required class="author bg-[#EDF1F7] mt-1.5 max-w-full !w-full rounded-lg">
							<option value=""><?php esc_html_e( '-- Select a User --', 'smartdirectory' ); ?></option>
							<?php foreach ( get_users() as $key => $value ) : ?>
								<option <?php selected( $value->data->ID, $user_id ); ?> value="<?php echo esc_attr( $value->data->ID ); ?>"><?php echo esc_attr( $value->data->display_name ); ?> (<?php echo esc_html( $value->data->user_nicename ); ?>)</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
				<div class="mb-5">
					<label class="block" for="preview_image">
						<span class="text-base">
							<?php esc_html_e( 'Preview image', 'smartdirectory' ); ?> 
							<span class="text-danger">*</span> --
							<span><?php esc_html_e( 'Dimensions' ); ?>: (800x525)</span>
						</span>
						<br>
						<input 
						oninvalid="this.setCustomValidity('Please upload preview image')" 
						oninput="this.setCustomValidity('')"
						type="number" class="opacity-0 w-0 h-0 min-w-0 min-h-0 border-0 p-0" id="preview_image" name="preview_image" required>
						<button type="button" class="preview_image py-2 mt-2 px-8 bg-primary text-white font-semibold rounded-md">Upload Preview Image</button>
						<span class="preview_image_title"></span>
					</label>
				</div>
				<button class="submit-button btn mt-2 inline-flex cursor-pointer rounded-md bg-success py-2 px-8 font-semibold text-white hover:bg-success-hover focus:bg-success-hover active:bg-success-hover/90">
					<svg class="animate-spin -ml-1 mr-3 h-5 w-4 text-white mt-1 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
						<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
						<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
					</svg>
					<?php esc_html_e( 'Submit', 'smartdirectory' ); ?>
				</button>
			</form>
		</div>
	</div>
</div>
