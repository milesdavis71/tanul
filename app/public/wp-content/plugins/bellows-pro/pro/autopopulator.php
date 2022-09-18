<?php

function bellows_populate_terms( $sorted_menu_items, $args ){


	if( !is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_POST['action'] ) && $_POST['action'] == 'bellows_generate_preview') ){

		//if( $menu->term_id == 2 ){	//Change to your menu ID

		if( isset( $args->bellows_populate_terms ) ){

			if( !isset( $args->bellows_terms ) ) return $sorted_menu_items;
			// if( !isset( $args->bellows_terms['taxonomies'] ) ) return $sorted_menu_items;

			$sorted_menu_items = array();		//Keep track of new items
			$menu_order = 1;					//Keep track of order

			$taxonomies = isset( $args->bellows_terms['taxonomy'] ) ? $args->bellows_terms['taxonomy'] : $args->bellows_terms['taxonomies'];
			$query_args = $args->bellows_terms;
			$query_args['taxonomy'] = $taxonomies; // this changed in WordPress 4.5 
			unset( $query_args['taxonomies'] ); // in case it was also set

			//Allow filtering at this point
			$query_args = apply_filters( 'bellows_auto_terms_query_args' , $query_args );

			$root = $query_args['child_of'] ? intval( $query_args['child_of'] ) : 0; //make sure child_of is not set to '', which returns nothing

			//$terms = get_terms( $taxonomies , $query_args );
			$terms = get_terms( $query_args );	//changed in WP 4.5 

			//Likely invalid taxonomies passed, returns error object
			if( is_wp_error( $terms ) ){
				//echo '<p><strong>'.$terms->get_error_message().'</strong></p>';
				bellows_admin_notice( $terms->get_error_message() );
			}
			else{

				//Determine depth of each term, if necessary
				$max_depth = ( isset( $query_args['depth'] ) && $query_args['depth'] != 0 && is_numeric( $query_args['depth'] ) ) ? $query_args['depth'] : 5; // Use 5 as a sanity check

				if( $max_depth ){
					$term_index = array();

					//Create a map of index => Term ID so we can look this up efficiently
					foreach( $terms as $k => $term ){
						$term_index[$term->term_id] = $k;
					}

					// Loop through each term in the results
					foreach( $terms as $term ){
						$depth = 0;

						// Assign to a new variable for internal while loop
						$t = $term;

						// Determine its depth by counting its parents
						while( $t->parent !== $root ){
							$depth++;
							$t = $terms[$term_index[$t->parent]];

							//If parent depth is known, use that rather than walking whole tree
							if( $t->depth ){
								$depth+= $t->depth;
								break;
							}
						}
						$term->depth = $depth;
					}
				}


				$child_map = array();	//parent_id => array of terms

				foreach( $terms as $term ){
					//Create array
					if( !isset( $child_map[$term->parent] ) ){
						$child_map[$term->parent] = array();
					}
					$child_map[$term->parent][] = $term;
				}


				if( isset( $child_map[$root] ) ){
					foreach( $child_map[$root] as $term ){
						//$term->name = $term->name . $term->depth;
						bellows_pop_child_terms( $term , $sorted_menu_items , $child_map , $menu_order , $args , $max_depth );

					}
				}
			}
			//Give the current item classes properly
			_wp_menu_item_classes_by_context( $sorted_menu_items );
			

			//Auto term items need to be given current item classes manually
			//because the function above requires the $item->type to be set to
			//"taxonomy" in order to actually process...
			//Note: ancestors current classes currently added via JS

			//Strategy:
			// - Find the current query object (that is, the main subject of the page) - in general, this means posts
			// - Get the $terms associated with the current query object (post)
			// - Go through all auto-term items and assign classes appropriately
			global $wp_query;
			$queried_object_id = (int) $wp_query->queried_object_id;
			$terms = array();	//for use with associated terms on current post

			//Matching posts within a terms menu, if the setting is enabled
			if( bellows_op( 'current_associated_term' , $args->bellows_config ) === 'on' ){
				$queried_object = $wp_query->get_queried_object(); // Grab the current post
				// Only going to apply to singular posts
				if ( $wp_query->is_singular && ! empty( $queried_object->post_type ) && ! is_post_type_hierarchical( $queried_object->post_type ) ) {
					$terms = wp_get_object_terms( $queried_object_id, $taxonomies, array( 'fields' => 'ids' ) );
				}
			}

			foreach( $sorted_menu_items as $key => $item ){
				if( $item->type == 'auto-term' ){

					//Current Menu Item
					if( $item->object_id == $queried_object_id ){
						$item->classes[] = 'current-menu-item';
					}

					//Current Page item
					if( bellows_op( 'current_associated_term' , $args->bellows_config ) === 'on' ){
						if( count( $terms ) && in_array( $item->object_id , $terms ) ){
							$item->classes[] = 'current-menu-item';
						}
					}

				}
			}
		}
	}
	return $sorted_menu_items;

}


function bellows_pop_child_terms( $term , &$items , &$child_map , &$menu_order , $args , $max_depth ){
	// $parent_id = 0;
					// if( $term->parent ) $parent_id = $term->parent;
//echo 'pop '. $term->term_id .' [' . $term->name .'] '. $term->parent. ' '. $menu_order.'<br/>';
		$item = new stdClass;						//Default object
		$item->menu_item_parent = $term->parent;		//Parent is the current static item
		$item->url = get_term_link( $term );		//Link to the Term's URL
		$item->title = apply_filters( 'bellows_auto_term_title' , $term->name , $term , $args );					//Use the Term's name as the menu item title
		//bellp( $args );
		if( isset( $args->bellows_terms['counts'] ) && $args->bellows_terms['counts'] ) $item->title.= apply_filters( 'bellows_term_count_display' , ' ('.$term->count.')' , $term ); // . "[$term->term_id][$term->parent]";
		$item->menu_order = $menu_order;			//Set the menu order


		//$item->ID = 'auto_term-'.$term->term_id;
		$item->ID = $term->term_id;
		$item->type = 'auto-term';
		$item->object = 'term';
		$item->object_id = $term->term_id;
		$item->db_id = $term->term_id;
//echo $item->ID . ' :: ' .$item->menu_item_parent ."<br/>";

		$item->classes = array( 'menu-item' );
		$item->classes[] = 'menu-item-term-'.$term->term_id;

		//Determine if item has chilren - that this term has child terms AND we haven't exceeded any set max depth
		$has_children = isset( $child_map[$term->term_id] ) && ( $term->depth < ( $max_depth - 1 ) );
				//$item->title = $term->name . ' :: ' . ( $has_children ? 'has children ' : 'has no kids' ) . ' :: ' . $term->depth . ' :: ' . $max_depth;

		if( $has_children ) $item->classes[] = 'menu-item-has-children';


		$items[] = $item;						//Store this item to be added later
		$menu_order++;							//Increment menu order

		if( $has_children ){
			foreach( $child_map[$term->term_id] as $child_term ){
				bellows_pop_child_terms( $child_term , $items , $child_map , $menu_order , $args , $max_depth );
			}
		}
}




function bellows_populate_posts( $sorted_menu_items, $args ){

	if( !is_admin() ||
		( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_POST['action'] ) && $_POST['action'] == 'bellows_generate_preview') ){

		if( isset( $args->bellows_populate_posts ) ){

			if( !isset( $args->bellows_posts ) ) return $sorted_menu_items;
			//if( !isset( $args->bellows_posts[''] ) ) return $sorted_menu_items;

			$sorted_menu_items = array();					//Keep track of new items
			$menu_order = 1;					//Keep track of order

			$query_args = $args->bellows_posts;
			//Allow filtering
			$query_args = apply_filters( 'bellows_auto_posts_query_args' , $query_args );

			$root = $query_args['post_parent'] ? $query_args['post_parent'] : 0;
			$depth = isset( $query_args['depth'] ) ? $query_args['depth'] : 1;		//default to 1 level
//bellp( $query_args );

			$posts;
			if( $query_args['post_parent'] === '' ){
				$posts = get_posts( $query_args );
			}
			//only children
			else{

				//get grandchildren
				if( $depth > 1 ){
					$posts = bellows_get_posts_recursive( $query_args , $depth );
				}
				//only direct children
				else{
					$posts = get_posts( $query_args );
				}

				//Include post parent
				if( isset( $args->bellows_posts['include_parent'] ) && $args->bellows_posts['include_parent'] ){
					//TODO: if we support post_parent__in, then adjust for multiple IDs
					if( isset( $query_args['post_parent'] ) && $query_args['post_parent'] ){ //non-zero

						$post_parent = $query_args['post_parent'];

						// If it's a dynamic parent, determine its ID
						if( $post_parent < 0 ){
							$post_parent = bellows_get_dynamic_post_parent( $post_parent );
						}

						//Get the parent as a post object
						$parent = get_post( $post_parent );
						//Add the parent to the set of $posts to be displayed
						$posts[] = $parent;
						//Set the root to this post's parent (if top level, this will be 0)
						$root = $parent->post_parent;
					}
				}

			}

			//No results
			if( !count( $posts ) ){
				return $sorted_menu_items;
			}


			$child_map = array();	//parent_id => array of terms

			foreach( $posts as $post ){
//if( !is_admin() ) echo $post->post_title . ' :: ' . $post->ID . ' :: ' .$post->post_parent . '<br/>';
				//Create array
				if( !isset( $child_map[$post->post_parent] ) ){
					$child_map[$post->post_parent] = array();
				}
				$child_map[$post->post_parent][] = $post;
			}
//if( !is_admin() ) bellp( $child_map );
			if( isset( $child_map[$root] ) ){
				foreach( $child_map[$root] as $post ){

					bellows_pop_child_posts( $post , $sorted_menu_items , $child_map , $menu_order , $depth );

				}
			}
			else{
				//The root (or a top level item) was not returned in the post set, most likely due to the sort order
				$notice = bellows_admin_notice( 'Your query parameters do not return a top level item or child of the root element due to the way the results set is limited.  Try adjusting your sorting or the number of items you are returning.' , false );
				if( !is_admin() ) echo $notice;
				else{
					$item = new stdClass;							//Default object
					$item->menu_item_parent = 0;					//Parent is the current static item
					$item->url = '#';								//Link to the Page's URL
					$item->title = $notice;
					$item->menu_order = 0;			//Set the menu order

					$sorted_menu_items[] = $item;
				}
			}
//if( !is_admin() ) bellp( $query_args );
			//Give the current item classes properly
			_wp_menu_item_classes_by_context( $sorted_menu_items );
		}
	}
//if( !is_admin() ) bellp( $sorted_menu_items );
	return $sorted_menu_items;

}

function bellows_get_posts_recursive( $query_args , $depth ){

	$posts = get_posts( $query_args );
	$child_posts = array();
	$depth--;
	if( $depth > 0 ){
		foreach( $posts as $post ){
			//Only query children if this post type is hierarchical
			if( is_post_type_hierarchical( $post->post_type ) ){
				$query_args['post_parent'] = $post->ID;
				$child_posts = array_merge( $child_posts , bellows_get_posts_recursive( $query_args , $depth ) );
			}
		}
	}
	$posts = array_merge( $posts , $child_posts );
	return $posts;
}

function bellows_pop_child_posts( $post , &$items , &$child_map , &$menu_order , $depth ){
	//bellp( $post->ID . ' :: '. $post->post_parent . ' :: ' . $post->post_title );
	//bellp( $post );

	// $parent_id = 0;
					// if( $term->parent ) $parent_id = $term->parent;
//echo 'pop '. $term->term_id .' [' . $term->name .'] '. $term->parent. ' '. $menu_order.'<br/>';
		$item = new stdClass;							//Default object
		$item->menu_item_parent = $post->post_parent;	//Parent is the current static item
		$item->url = get_permalink( $post );			//Link to the Page's URL
		$item->title = apply_filters( 'bellows_auto_post_title' , $post->post_title , $post );					//Use the Term's name as the menu item title
		$item->menu_order = $menu_order;			//Set the menu order


		//$item->ID = 'auto_term-'.$term->term_id;
		$item->ID = $post->ID;
		$item->type = 'post_type'; // 'auto-post';
		$item->post_parent = $post->post_parent;
		$item->bellows_type = 'auto-post';
		$item->object = 'post';
		$item->object_id = $post->ID;
		$item->db_id = $post->ID;
//echo $item->ID . ' :: ' .$item->menu_item_parent ."<br/>";

		$item->classes = array( 'menu-item' );
		$item->classes[] = 'menu-item-post-'.$post->ID;
		if( isset( $child_map[$post->ID] ) ) $item->classes[] = 'menu-item-has-children';

//bellp( $items );
		$items[] = $item;						//Store this item to be added later
		$menu_order++;									//Increment menu order

		if( --$depth > 0 ){
			if( isset( $child_map[$post->ID] ) ){
				foreach( $child_map[$post->ID] as $child_post ){
					bellows_pop_child_posts( $child_post , $items , $child_map , $menu_order , $depth );
				}
			}
		}
}





//add_filter( 'wp_get_nav_menu_items' , 'bellows_populate_pages' , 10, 3 );

// function bellows_skip_standard_menu_population( $menu_obj , $menu_slug ){
// 	bellp( $menu_obj );
// 	if( isset( $menu_obj->term_id ) ) $menu_obj->term_id = 0;
// 	return $menu_obj;
// }
//add_filter( 'wp_get_nav_menu_object' , 'bellows_skip_standard_menu_population' , 20 , 2 );
