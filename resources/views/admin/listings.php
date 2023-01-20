<?php

use SmartDirectory\App\ListTable;

/**
 * @var ListTable $list_table
 */
?>
<div class="wrap smart-directory-wrap">
	<h1 style="display: inline-flex;"><?php esc_html_e( 'Directories', 'smartdirectory' ); ?></h1>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=smart-directory-add-new' ) ); ?>" class="page-title-action">
		<?php esc_html_e( 'Add New', 'smart-directory' )?>
	</a>
	<?php if ( ! empty( $list_table->search ) ) : ?>
		<span style="margin-left: 10px;"><?php esc_html_e( 'Search results for: ', 'smart-directory' ); ?><?php echo esc_html( $list_table->search ); ?></span>
	<?php endif; ?>
	<hr class="wp-header-end">
	<form method="get">
		<input type="hidden" name="page" value="smart-directory-listings">
		<?php
			$list_table->prepare_items();
			$list_table->views();
			$list_table->search_box( 'search', 'search_id' );
			$list_table->display();
		?>
	</form>
</div>
