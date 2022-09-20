<?php
$unique_id = wp_unique_id('search-');
?>

<form class="" method="get" action="<?php echo esc_url(home_url('/')); ?>">
	<label for="<?php echo esc_attr($unique_id); ?>" class="form-label mb-0">
		<?php echo esc_html('Enter your search term'); ?>
	</label>
	<div class="d-flex">
		<input type="search" name="s" placeholder="<?php echo esc_attr('...'); ?>" id="<?php echo esc_attr($unique_id); ?>" class="form-control mb-2 me-1" />
		<input type="submit" value="<?php echo esc_attr('Search'); ?>" class="btn btn-primary mb-2" />
	</div>
</form>
