# Byvex WooCommerce Starter Theme

> Starter WooCommerce WordPress Theme with Bootstrap 5

A well crafted starter WordPress theme built on top of Bootstrap 5. We created this theme to fulfill just one goal, "A lightweight theme with less code to remove and ready to use with Bootstrap and WooCommerce".

## Code Snippets

### Remove Block Library
```php
add_action('wp_enqueue_scripts', function(){
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
});
```

### Add Class to Excerpt
```php
add_filter('the_excerpt', function($excerpt){
	return str_replace('<p', '<p class="mb-2"', $excerpt);
});
```

### Change Excerpt Read More
```php
add_filter('excerpt_more', function($more){
	if (!is_admin()) {
		return '...';
	}
});
```

## FAQ
If you have any questions or confusion, then submit an issue and we will respond immediately.
