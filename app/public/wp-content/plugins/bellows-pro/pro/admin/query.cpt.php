<?php

// Register Custom Post Type
function bellows_cpt_query() {

	$labels = array(
		'name'                  => _x( 'Bellows Queries', 'Post Type General Name', 'bellows' ),
		'singular_name'         => _x( 'Bellows Query', 'Post Type Singular Name', 'bellows' ),
		'menu_name'             => __( 'Bellows Queries', 'bellows' ),
		'name_admin_bar'        => __( 'Bellows Queries', 'bellows' ),
		'archives'              => __( 'Bellows Query Archives', 'bellows' ),
		'parent_item_colon'     => __( 'Parent Item:', 'bellows' ),
		'all_items'             => __( 'All Items', 'bellows' ),
		'add_new_item'          => __( 'Add New Item', 'bellows' ),
		'add_new'               => __( 'Add New', 'bellows' ),
		'new_item'              => __( 'New Item', 'bellows' ),
		'edit_item'             => __( 'Edit Item', 'bellows' ),
		'update_item'           => __( 'Update Item', 'bellows' ),
		'view_item'             => __( 'View Item', 'bellows' ),
		'search_items'          => __( 'Search Item', 'bellows' ),
		'not_found'             => __( 'Not found', 'bellows' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'bellows' ),
		'featured_image'        => __( 'Featured Image', 'bellows' ),
		'set_featured_image'    => __( 'Set featured image', 'bellows' ),
		'remove_featured_image' => __( 'Remove featured image', 'bellows' ),
		'use_featured_image'    => __( 'Use as featured image', 'bellows' ),
		'insert_into_item'      => __( 'Insert into item', 'bellows' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'bellows' ),
		'items_list'            => __( 'Items list', 'bellows' ),
		'items_list_navigation' => __( 'Items list navigation', 'bellows' ),
		'filter_items_list'     => __( 'Filter items list', 'bellows' ),
	);
	$args = array(
		'label'                 => __( 'Bellows Query', 'bellows' ),
		'description'           => __( 'A private query structure for the Bellows Accordion Plugin', 'bellows' ),
		'labels'                => $labels,
		'supports'              => array( 'title', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => false,
		'show_in_menu'          => false,
		'menu_position'         => 5,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
	);
	register_post_type( 'bellows_query', $args );

}
add_action( 'init', 'bellows_cpt_query', 0 );






//AJAX Handling
add_action( 'wp_ajax_bellows_saved_query_save_over' , 'wp_ajax_bellows_saved_query_save_over_callback' );
function wp_ajax_bellows_saved_query_save_over_callback(){
	//Check that data was sent
	if( empty( $_POST ) ){
		status_header( '400' );
		die( 'No data sent' );
	}

	//Check that the nonce is valid
	$nonce = $_POST['_bellows_query_save_nonce'];
	if( !wp_verify_nonce( $nonce, 'bellows-query-save-nonce') ){
		status_header( '401' );
		die( 'Invalid Nonce' );
	}

	//Check user permissions
	if( !current_user_can( BELLOWS_ADMIN_CAP ) ){
		status_header( '403' ); 
		die( 'User does not have permission' );
	}

	$query_id;
	$query_args;

	//Check that post ID was sent
	if( isset( $_POST['query_id'] ) ){
		$query_id = $_POST['query_id'];
		if( !is_numeric( $query_id ) ){
			wp_send_json( array( 'status' => 2 , 'msg' => 'Not a valid $query_id ' . $query_id ) );
		}

		$is_new = $query_id == -1 ? true : false;
	}
	else{
		wp_send_json( array( 'status' => 2, 'msg' => 'Query ID not set' ) );
	}

	//Check that query args were sent
	if( isset( $_POST['query_args'] ) ){
		$defaults = array( 'config_id' => 'main' , 'post_type' => 'page' );
		$query_args = wp_parse_args( $_POST['query_args'] , $defaults );
		if( !is_array( $query_args ) ){
			wp_send_json( array( 'status' => 2 , 'msg' => 'Query args value not an array' ) );
		}
	}
	else{
		wp_send_json( array( 'status' => 2 , 'msg' => 'Query Args not set' ) ); 
	}



	$post = get_post( $query_id );
	if( $post->post_type != 'bellows_query' ){
		wp_send_json( array( 'status' => 2 , 'msg' => 'Post ID is not that of a Bellows Query post' ) ); 
	}

	$query_type = get_post_meta( $query_id , 'query_type' , true );
	if( !$query_type ){
		wp_send_json( array( 'status' => 2 , 'msg' => 'Query Type for this query is not set' ) ); 
	}



	//Now set the metadata
	update_post_meta( $query_id , 'query_args' , $query_args );

	$status = 0; //0 = success, 1 = warning, 2 = error

	$msg = '<strong>' . $post->post_title . ' ['.$query_id.']</strong> ' . __( 'query overwritten with new settings. ' , 'bellows' );


	$r = array(
		'nonce'	=> wp_create_nonce( 'bellows-query-save-nonce' ),
		'query_id' => $query_id,
		'query_args'	=> $query_args,
		'status'	=> 0,
		//'query_list'	=> bellows_saved_query_list( $query_type , false ),
		'msg'	=> $msg,
	);

	wp_send_json( $r );

}

add_action( 'wp_ajax_bellows_query_save' , 'wp_ajax_bellows_query_save_callback' );
function wp_ajax_bellows_query_save_callback(){

	//Check that data was sent
	if( empty( $_POST ) ){
		status_header( '400' );
		die( 'No data sent' );
	}

	//Check that the nonce is valid
	$nonce = $_POST['_bellows_query_save_nonce'];
	if( !wp_verify_nonce( $nonce, 'bellows-query-save-nonce') ){
		status_header( '401' );
		die( 'Invalid Nonce' );
	}

	//Check user permissions
	if( !current_user_can( BELLOWS_ADMIN_CAP ) ){
		status_header( '403' ); 
		die( 'User does not have permission' );
	}


	$query_id;
	$query_title;
	$query_args;
	$query_type;

	$is_new = false;

	//Check that post ID was sent
	if( isset( $_POST['query_id'] ) ){
		$query_id = $_POST['query_id'];
		if( !is_numeric( $query_id ) ){
			wp_send_json( array( 'status' => 2 , 'msg' => 'Not a valid $query_id ' . $query_id ) );
		}

		$is_new = $query_id == -1 ? true : false;
	}
	else{
		wp_send_json( array( 'status' => 2, 'msg' => 'Query ID not set' ) );
	}

	//Check that Query Title was sent
	if( isset( $_POST['query_title'] ) ){
		$query_title = $_POST['query_title'];
		if( !$query_title ){
			wp_send_json( array( 'status' => 2 , 'msg' => 'Please give your query a name in order to save' ) );
		}

	}
	else{
		wp_send_json( array( 'status' => 2 , 'msg' => 'Query name not set' ) );
	}

	
	//Check that query args were sent
	if( isset( $_POST['query_args'] ) ){
		$defaults = array( 'config_id' => 'main' , 'post_type' => 'page' );
		$query_args = wp_parse_args( $_POST['query_args'] , $defaults );
		if( !is_array( $query_args ) ){
			wp_send_json( array( 'status' => 2 , 'msg' => 'Query args value not an array' ) );
		}
	}
	else{
		wp_send_json( array( 'status' => 2 , 'msg' => 'Query Args not set' ) ); 
	}

	//Check that Query Type was sent
	if( isset( $_POST['query_type'] ) ){
		$query_type = $_POST['query_type'];
	}
	else wp_send_json( array( 'status' => 2 , 'msg' => 'Query Type not set' ) );




	//update/create new Bellows Query post
	$post = array(
		'post_title'	=> wp_strip_all_tags( $query_title ),
		'post_status'	=> 'publish',
		'post_type'		=> 'bellows_query',
	);

	//If no Query ID, create a new Query
	if( $query_id != -1 ){
		$post['ID']		= $query_id;
	}

	$query_id = wp_insert_post( $post );

	if( !$query_id ){
		wp_send_json( array( 'status' => 2 , 'msg' => 'WordPress failed to save custom post' ) );
	}
	else{

		//Convert any true/false strings to booleans
		$bools = array( 'hide_empty' , 'hierarchical' , 'counts' );
		foreach( $query_args as $key => $val ){
			if( in_array( $key , $bools ) ){
		 		if( $val === 'true' ) $val = $query_args[$key] = true;
		 		else if( $val === 'false' ) $query_args[$key] = false;
			}
		}

		//Now set the metadata
		update_post_meta( $query_id , 'query_args' , $query_args );
		update_post_meta( $query_id , 'query_type' , $query_type );

		$status = 0; //0 = success, 1 = warning, 2 = error

		$msg = '';
		if( $is_new ) $msg = __( 'Saved new query with ID ' , 'bellows' ) . $query_id;
		else $msg = __( 'Updated query ' , 'bellows' ) . $query_id;



		$r = array(
			'nonce'	=> wp_create_nonce( 'bellows-query-save-nonce' ),
			'query_id' => $query_id,
			'query_args'	=> $query_args,
			'status'	=> 0,
			'query_list'	=> bellows_saved_query_list( $query_type , false ),
			'msg'	=> $msg,
		);

		wp_send_json( $r );
	}
}



function bellows_saved_query_list( $query_type , $echo = true ){

	$saved_queries = get_posts( array(
			'post_type'	=> 'bellows_query',
			'numberposts' => -1,
		)); 

	if( !$echo ){
		ob_start();
	}

	if( count( $saved_queries ) == 0 ){
		//echo 'saved queries will be displayed here';
	}

	foreach( $saved_queries as $query ){
		$qid = $query->ID;
		$qtitle = $query->post_title;

//TODO: CHECK QUERY TYPE, ONLY SHOW CURRENT QUERY TYPE
		$qtype = get_post_meta( $qid , 'query_type' , true );
		if( $qtype !== $query_type ) continue;

		

		?>
		<div class="bellows-saved-query" data-qid="<?php echo $qid; ?>">
			<div class="bellows-saved-query-actions">
				<a class="bellows-saved-query-btn bellows-saved-query-btn-edit">
					<i class="fa fa-folder-open"></i>
					<span class="bellows-saved-query-btn-title">Open this query for editing</span>
				</a> 
				<a class="bellows-saved-query-btn bellows-saved-query-btn-save">
					<i class="fa fa-save"></i>
					<span class="bellows-saved-query-btn-title">Overwrite this query with the current settings</span>
				</a>
				<a class="bellows-saved-query-btn bellows-saved-query-btn-code">
					<i class="fa fa-code"></i>
					<span class="bellows-saved-query-btn-title">Get the shortcode and PHP code for this menu</span>
				</a>
				<a class="bellows-saved-query-btn bellows-saved-query-btn-delete">
					<i class="fa fa-remove"></i>
					<span class="bellows-saved-query-btn-title">Delete this query permanently</span>
				</a>
			</div>
			<div class="bellows-saved-query-title"><?php echo $qtitle; ?></div> 
			<div class="bellows-saved-query-code">
				<div class="bellows-saved-query-code-shortcode">[bellows_menu qid="<?php echo $qid; ?>"]</div>
				<div class="bellows-saved-query-code-php">&lt;?php bellows_menu( <?php echo $qid; ?> ); ?&gt;</div>
			</div>
		</div>
		<?php
	}

	if( !$echo ){
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
}



add_action( 'wp_ajax_bellows_saved_query_delete' , 'bellows_saved_query_delete_callback' );
function bellows_saved_query_delete_callback(){

	//Check that the nonce is valid
	$nonce = $_POST['_bellows_query_save_nonce'];
	if( !wp_verify_nonce( $nonce, 'bellows-query-save-nonce') ){
		status_header( '401' );
		die( 'Invalid Nonce' );
	}

	//Check user permissions
	if( !current_user_can( BELLOWS_ADMIN_CAP ) ){
		status_header( '403' ); 
		die( 'User does not have permission' );
	}


	//Check that post ID was sent
	if( isset( $_POST['query_id'] ) ){
		$query_id = $_POST['query_id'];
		if( !is_numeric( $query_id ) ){
			wp_send_json( array( 'status' => 2 , 'msg' => 'Not a valid Query ID [' . $query_id .']' ) );
		}

		//$is_new = $query_id == -1 ? true : false;
	}
	else{
		wp_send_json( array( 'status' => 2, 'msg' => 'Query ID not set' ) );
	}


	$post = get_post( $query_id );
	if( $post->post_type != 'bellows_query' ){
		wp_send_json( array( 'status' => 2 , 'msg' => 'Post '.$query_id.' is not a Bellows Query post, so it was not deleted.' ) );
	}
	if( !wp_delete_post( $query_id ) ){
		wp_send_json( array( 'status' => 2 , 'msg' => 'WordPress failed to delete post' ) );
	}


	wp_send_json( array( 
		'status' => 0 , 
		'query_id' => $query_id,
		'msg' => 'Deleted query ' . $query_id . ' ('.$post->post_title.')',
	) );



}



add_action( 'wp_ajax_bellows_saved_query_load' , 'bellows_saved_query_load_callback' );
function bellows_saved_query_load_callback(){

	//Check that the nonce is valid
	$nonce = $_POST['_bellows_query_save_nonce'];
	if( !wp_verify_nonce( $nonce, 'bellows-query-save-nonce') ){
		status_header( '401' );
		die( 'Invalid Nonce' );
	}

	//Check user permissions
	if( !current_user_can( BELLOWS_ADMIN_CAP ) ){
		status_header( '403' ); 
		die( 'User does not have permission' );
	}


	//Check that post ID was sent
	if( isset( $_POST['query_id'] ) ){
		$query_id = $_POST['query_id'];
		if( !is_numeric( $query_id ) ){
			wp_send_json( array( 'status' => 2 , 'msg' => 'Not a valid Query ID [' . $query_id .']' ) );
		}
	}
	else{
		wp_send_json( array( 'status' => 2, 'msg' => 'Query ID not set' ) );
	}


	$post = get_post( $query_id );
	if( $post->post_type != 'bellows_query' ){
		wp_send_json( array( 'status' => 2 , 'msg' => 'Post '.$query_id.' is not a Bellows Query post.  Could not load.' ) );
	}

	$query_args = get_post_meta( $query_id , 'query_args' , true );


	//Get all args
	$all_fields;
	$query_type = get_post_meta( $query_id , 'query_type' , true );
	switch( $query_type ){
		case 'post':
			$all_fields = bellows_generator_posts_fields();
			break;
		case 'term':
			$all_fields = bellows_generator_terms_fields();
			break;
	}

	$r_args = array();
	foreach( $all_fields as $field ){
		$key = $field['name'];
		if( isset( $query_args[ $key ] ) ){
			$r_args[ $key ] = $query_args[$key];
		}
		else{
			$r_args[ $key ] = $field['default'];
		}
	}

	wp_send_json( array( 
		'status' => 0 , 
		'query_id' => $query_id,
		'query_title' => $post->post_title,
		'query_args' => $r_args, //$query_args,
		'msg' => 'Loaded query ' . $query_id . ' ('.$post->post_title.')',
	) );



}

