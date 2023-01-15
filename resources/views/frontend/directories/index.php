<?php

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\System\View\View;

$total_directories = smart_directory_count_total();
$total             = isset( $total_directories['approved'] ) ? $total_directories['approved'] : 0;
?>
<div class="smart-directory directory-list-body" data-api="smart-directory/v1/directories">
	<div class="grid grid-cols-3 gap-8 directory-list" data-total="<?php echo esc_attr( $total ); ?>">
		<?php View::render( 'frontend/directories/list' ); ?>
	</div>
	<div class="pt-2 preloader hidden">
		<svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-[#F51957]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
			<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
			<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
		</svg>
	</div>
	<div class="pagination mt-6"></div>
</div>
