<?php

use SmartDirectory\App\ListTable;

/**
 * @var ListTable $list_table
 */
?>
<div class="wrap">
	<h1 style="display: inline-flex;"><?php esc_html_e( 'Directories', 'smartdirectory' ); ?></h1>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=smart-directory-add-new' ) ); ?>" class="page-title-action">Add New</a>
	<hr class="wp-header-end">
		<form method="post">
		<?php
			$list_table->prepare_items();
			$list_table->views();
			$list_table->search_box( 'search', 'search_id' );
			$list_table->display();
		?>
	</div>
</form>
