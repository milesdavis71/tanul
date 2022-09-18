<?php

function bellows_generator_terms_fields(){

    $terms_fields = array(
        array(
            'name'		=> 'config_id',
            'title'		=> __( 'Configuration' , 'bellows' ),
            'type'		=> 'radio',
            'options'	=> bellows_generator_get_config_ops(),
            'default'	=> 'main',
            'desc'		=> __( 'The Bellows Settings Configuration to use to display this menu' , 'bellows' ),
        ),
        array(
            'name'		=> 'taxonomy',
            'title'		=> __( 'Taxonomy' , 'bellows' ),
            'type'		=> 'multicheck',
            'options'	=> bellows_generator_get_taxonomy_ops(),
            'default'	=> '',
            'desc'		=> __( 'Select the taxonomies to be displayed.  In most cases, you will select a single taxonomy here.' , 'bellows' ),
            'required'	=> true,
        ),
        array(
            'name'		=> 'number',
            'title'		=> __( 'Number' , 'bellows' ),
            'type'		=> 'text',
            'default'	=> '',
            'desc'		=> __( 'The maximum number of results to display' , 'bellows' ),
        ),
        array(
            'name'		=> 'depth',
            'title'		=> __( 'Depth (Menu Levels)' , 'bellows' ),
            'type'		=> 'text',
            'default'	=> '',
            'desc'		=> __( 'The maximum number of levels to display.' , 'bellows' ),
        ),
        
        array(
            'name'		=> 'offset',
            'title'		=> __( 'Offset' , 'bellows' ),
            'type'		=> 'text',
            'default'	=> '',
            'desc'		=> __( 'The number by which to offset the terms query (leave blank to start with the first result)' , 'bellows' ),
        ),
        array(
            'name'		=> 'child_of',
            'title'		=> __( 'Child Of' , 'bellows' ),
            'type'		=> 'parent_terms',
            'default'	=> '',
            'desc'		=> __( 'Select a Taxonomy to show options, or enter a term ID directly' , 'bellows' ),
        ),
        array(
            'name'		=> 'orderby',
            'title'		=> __( 'Order By' , 'bellows' ),
            'type'		=> 'radio',
            'options'	=> array(
                'name'	=> __( 'Name', 'bellows' ),
                'slug' 	=> __( 'Slug' , 'bellows' ),
                'term_group' => __( 'Term Group' , 'bellows' ),
                'term_id' => __( 'Term ID' , 'bellows' ),
                'id'	=> __( 'ID' , 'bellows' ),
                'description' => __( 'Description', 'bellows' ),
                'count'	=> __( 'Count' , 'bellows' ),
            ),
            'default'	=> 'name',
            'desc'		=> __( 'Sort the results by this parameter' , 'bellows' ),
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
            'name'		=> 'hide_empty',
            'title'		=> __( 'Hide Empty' , 'bellows' ),
            'type'		=> 'checkbox',
            'desc'		=> __( 'Hide terms not assigned to any posts' , 'bellows' ),
            'default'	=> true,
        ),
        array(
            'name'		=> 'hierarchical',
            'title'		=> __( 'Hierarchical' , 'bellows' ),
            'type'		=> 'checkbox',
            'desc'		=> __( 'Enable to include terms that have non-empty descendants' , 'bellows' ),
            'default'	=> true,
        ),
        array(
            'name'		=> 'exclude',
            'title'		=> __( 'Exclude' , 'bellows' ),
            'type'		=> 'text',
            'default'	=> '',
            'desc'		=> __( 'Comma-separated list of term IDs to exclude' , 'bellows' ),
        ),
        array(
            'name'		=> 'counts',
            'title'		=> __( 'Display Post Counts' , 'bellows' ),
            'type'		=> 'checkbox',
            'desc'		=> __( 'Display the number of posts for each term in (parentheses)' , 'bellows' ),
            'default'	=> false,
        ),

    );

    return $terms_fields;
}