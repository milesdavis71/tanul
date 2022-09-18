<?php

function bellows_generator_posts_fields(){

	$posts_fields = array(
			array(
				'name'		=> 'config_id',
				'title'		=> __( 'Configuration' , 'bellows' ),
				'type'		=> 'radio',
				'options'	=> bellows_generator_get_config_ops(),
				'default'	=> 'main',
				'desc'		=> __( 'The Bellows Settings Configuration to use to display this menu' , 'bellows' ),
			),
			array(
				'name'		=> 'post_type',
				'title'		=> __( 'Post Type' , 'bellows' ),
				'type'		=> 'multicheck',
				'options'	=> bellows_generator_get_post_type_ops(),
				'default'	=> 'page',
				'desc'		=> __( 'The type of posts to include.  Defaults to Pages if none are selected.' , 'bellows' ),
			),
			array(
				'name'		=> 'post_parent',	//Need to include kids
				'title'		=> __( 'Post Parent' , 'bellows' ),
				'type'		=> 'post_parent',
				'options'	=> 'bellows_post_parents',
				'default'	=> '',
				'desc'		=> __( 'All returned items will have this parent.  If you want to display multiple levels, set the Depth value as well' , 'bellows' ),
			),
            array(
                'name'		=> 'include_parent',
                'title'		=> __( 'Include Parent' , 'bellows' ),
                'type'		=> 'checkbox',
                'desc'		=> __( 'Include the Post Parent in the menu' , 'bellows' ),
                'default'	=> false,
            ),
			array(
				'name'		=> 'depth',
				'title'		=> __( 'Depth (Menu Levels)' , 'bellows' ),
				'type'		=> 'text',
				'default'	=> 2,
				'desc'		=> __( 'The maximum number of levels to return' , 'bellows' ),
			),
			array(
				'name'		=> 'numberposts',
				'title'		=> __( 'Number' , 'bellows' ),
				'type'		=> 'text',
				'default'	=> '',
				'desc'		=> __( 'The maximum number of results to display' , 'bellows' ),
			),
			array(
				'name'		=> 'orderby',
				'title'		=> __( 'Order By' , 'bellows' ),
				'type'		=> 'radio',
				'options'	=> array(
					'none'	=> __( 'None' , 'bellows' ),
					'ID'	=> __( 'ID' , 'bellows' ),
					'author'=> __( 'Author' , 'bellows' ),
					'title'	=> __( 'Title' , 'bellows' ),
					'name'	=> __( 'Post Slug', 'bellows' ),
					'type' 	=> __( 'Post Type' , 'bellows' ),
					'date' 	=> __( 'Date' , 'bellows' ),
					'modified' => __( 'Modified Date' , 'bellows' ),
					'parent'	=> __( 'Parent' , 'bellows' ),
					'menu_order'	=> __( 'Page Order (menu_order)' , 'bellows' ),
					'rand' => __( 'Random', 'bellows' ),
					'comment_count'	=> __( 'Comment Count' , 'bellows' ),
				),
				'default'	=> 'title',
				'desc'		=> __( 'Sort the results by this parameter.  See ' , 'bellows' ).'<a href="https://codex.wordpress.org/Template_Tags/get_posts#Parameters">get_posts() parameters</a>',
			),
			array(
				'name'		=> 'order',
				'title'		=> __( 'Order' , 'bellows' ),
				'type'		=> 'radio',
				'options'	=> array(
					'ASC'	=> __( 'Ascending (1-10, A-Z)', 'bellows' ),
					'DESC' 	=> __( 'Descending (10-1, Z-A)' , 'bellows' ),
				),
				'default'	=> 'ASC',
				'desc'		=> __( 'Sort in normal or reverse order' , 'bellows' ),
			),
			array(
				'name'		=> 'author',
				'title'		=> __( 'Author' , 'bellows' ),
				'type'		=> 'post_author',
				'default'	=> '',
				'desc'		=> __( 'The user who authored the post' , 'bellows' ),
			),
            array(
                'name'		=> 'exclude',
                'title'		=> __( 'Exclude' , 'bellows' ),
                'type'		=> 'text',
                'default'	=> '',
                'desc'		=> __( 'Comma-separated list of post IDs to exclude' , 'bellows' ),
            ),

		);

	$taxonomies = get_taxonomies( array(
					'public'	=> true,
					//'_builtin'	=> true,
					) , 'objects' );

	foreach( $taxonomies as $tax_id => $tax ){
		$posts_fields[] = array(
		 	'name'	=> 'tax_'.$tax_id,
		 	'title'	=> 'Taxonomy: ' . $tax->label,
		 	'type'	=> 'post_terms',
		 	'tax_id'=> $tax_id,
		 	'default' => '',
		 	'desc'	=> __( 'Display posts that are in any of these ' , 'bellows' ) . $tax->label . '. ' . __( 'Enter a term ID, or click the Load button to load your options' , 'bellows' ),
		);
	}

	return $posts_fields;
}
