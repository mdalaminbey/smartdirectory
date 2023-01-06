<?php

defined( 'ABSPATH' ) || exit;

?>

<div class="smart-directory">
	<?php if ( is_user_logged_in() ) {?>
	<div class="p-7 border border-[#CBD5E1] rounded-sm">
		<h2 class="line-clamp-1 dark:text-navy-100 text-xl font-bold tracking-wide text-slate-700 lg:text-base">
			<?php esc_html_e( 'Create Directory', 'smartdirectory' )?>
		</h2>
		<hr class="mt-2" />
		<div class="mt-4">
			<form action="" method="post" class="create-directory-form">
				<div class="notice-success mb-5 hidden">
					<div class="w-full text-base bg-success/20 text-success px-2 py-2 border border-success rounded-md">
						<?php esc_html_e( 'Directory created successfully!', 'smartdirectory' )?>
					</div>
				</div>
				<div class="notice-error mb-5 bg-danger/5 text-danger rounded p-4 hidden"></div>
				<div class="mb-5">
					<label class="block">
						<span class="text-base"><?php esc_html_e( 'Title', 'smartdirectory' )?></span>
						<span class="text-danger">*</span>
						<input type="text" name="title" placeholder="Enter title" required class="form-textarea bg-[#EDF1F7] mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-1.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary"/>
					</label>
				</div>
				<div class="mb-5">
					<label class="block">
						<span class="text-base"><?php esc_html_e( 'Content', 'smartdirectory' )?></span>
						<span class="text-danger">*</span>
						<textarea name="content" placeholder="Enter content" required class="form-input bg-[#EDF1F7] mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-1.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary"></textarea>
					</label>
				</div>
				<div class="mb-5">
					<label class="block">
						<span class="text-base"><?php esc_html_e( 'Google Map Link', 'smartdirectory' )?></span>
						<span class="text-danger">*</span>
						<input name="map_link" type="url" placeholder="Enter google map link" required class="form-input bg-[#EDF1F7] mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-1.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary" />
					</label>
				</div>
				<div class="mb-5">
					<label class="block">
						<span class="text-base"><?php esc_html_e( 'Preview image', 'smartdirectory' )?></span>
						<span class="text-danger">*</span>
						<input type="file" id="preview_image" required name="preview_image" id="preview_image" class="hidden" accept="image/png, image/jpeg" />
						<div class="preview_body px-7 py-7 mt-1 min-h-[70px] text-gray-500 bg-[#EDF1F7] border-dashed border-2 border-[#CBD5E1] rounded-md">
 							<span class="underline cursor-pointer text-black"><?php esc_html_e( 'Browse Preview Image', 'smartdirectory' )?></span>
							<div class="preview-content hidden h-60 bg-slate-700/25 mt-3 overflow-hidden relative rounded">
								<div class="absolute top-1.5 left-1.5 z-30">
									<button type="button" class="preview_image_remove cursor-pointer bg-white rounded-full">
										<svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
											<path d="M11.586 13l-2.293 2.293a1 1 0 0 0 1.414 1.414L13 14.414l2.293 2.293a1 1 0 0 0 1.414-1.414L14.414 13l2.293-2.293a1 1 0 0 0-1.414-1.414L13 11.586l-2.293-2.293a1 1 0 0 0-1.414 1.414L11.586 13z" fill="currentColor" fill-rule="nonzero">
											</path>
										</svg>
									</button>
									<span class="text-white text-sm font-medium preview-title"></span>
								</div>
								<img class="absolute left-2/4 top-2/4 -translate-x-2/4 -translate-y-2/4">
							</div>
						</div>
					</label>
				</div>
				<button class="submit-button btn mt-2 inline-flex cursor-pointer rounded-md bg-success py-2 px-8 font-semibold text-white hover:bg-success-hover focus:bg-success-hover active:bg-success-hover/90">
					<svg class="animate-spin -ml-1 mr-3 h-5 w-4 text-white mt-1 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
						<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
						<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
					</svg>
					<?php esc_html_e( 'Submit', 'smartdirectory' )?>
				</button>
			</form>
		</div>
	</div>
	<?php } else {?>
		<div class="p-7 border border-danger/90 rounded-sm bg-danger/10">
			<span class="text-danger/90">
				<?php esc_html_e('You must login to create the directory.', 'smartdirectory')?>
				<a class="underline" href="<?php echo esc_url(wp_login_url()) ?>">
					<?php esc_html_e('Click here to login', 'smartdirectory')?>
				</a>
			</span>
		</div>
	<?php }?>
</div>
