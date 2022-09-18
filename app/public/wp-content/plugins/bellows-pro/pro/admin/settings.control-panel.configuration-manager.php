<?php

/**
 * Add the Configuration Tabs
 */
add_filter( 'bellows_settings_panel_sections' , 'bellows_settings_panel_sections_pro' );
function bellows_settings_panel_sections_pro( $sections = array() ){

	array_unshift( $sections , array(
		'id'	=> BELLOWS_PREFIX.'generator',
		'title' => __( 'Code Generator' , 'bellows' ),	//Wizard?
		'custom'=> 'bellows_generator_ui',
	));

	$configs = bellows_get_menu_configurations();

	//Add a Tab for each additional Configuration
	foreach( $configs as $config ){

		$sections[] = array(
			'id'	=> BELLOWS_PREFIX.$config,
			'title' => '+'.$config,
			'sub_sections'	=> bellows_get_configuration_subsections( $config ),
		);
	}

	return $sections;
}

/**
 * Add Pro Settings Fields for each Configuration
 */
add_filter( 'bellows_settings_panel_fields' , 'bellows_settings_panel_fields_configurations' , 50 );
function bellows_settings_panel_fields_configurations( $fields = array() ){

	//Add options for each additional Instance
	$configs = bellows_get_menu_configurations();	
	foreach( $configs as $config ){
		$fields[BELLOWS_PREFIX.$config] = bellows_get_configuration_fields( $config );		
	}

	return $fields;
}


/**
 * CREATE INSTANCE MANAGER
 */

add_action( 'bellows_settings_before' , 'bellows_configuration_manager');

function bellows_configuration_manager(){
	
	?>

	<div class="ssmenu_configuration_manager">

		<a class="ssmenu_configuration_toggle ssmenu_configuration_button">+ Add Bellows Configuration</a>

		<div class="ssmenu_configuration_wrap ssmenu_configuration_container_wrap">

			<div class="ssmenu_configuration_container">

				<h3>Add Bellows Configuration</h3>

				<form class="ssmenu_configuration_form">
					<input class="ssmenu_configuration_input" type="text" name="ssmenu_configuration_id" placeholder="configuration_id" />
					<?php wp_nonce_field( 'bellows-add-configuration' ); ?>
					<a class="ssmenu_configuration_button ssmenu_configuration_create_button">Create Configuration</a>
				</form>

				<p class="ssmenu_configuration_form_desc">Enter an ID for your new menu configuration.  This ID will be used when printing the menu, 
					and must contain only letters, hyphens, and underscores.  <a class="ssmenu_configuration_notice_close" href="#">close</a></p>

				<span class="ssmenu_configuration_close">&times;</span>

			</div>

		</div>


		<div class="ssmenu_configuration_wrap ssmenu_configuration_notice_wrap ssmenu_configuration_notice_success">
			<div class="ssmenu_configuration_notice">
				New menu created. <a href="<?php echo admin_url('themes.php?page=bellows-settings'); ?>" class="ssmenu_configuration_button">Refresh Page</a> 
				<p>Note: Any setting changes you've made have not been saved yet.  <a class="ssmenu_configuration_notice_close" href="#">close</a></p>
			</div>
		</div>

		<div class="ssmenu_configuration_wrap ssmenu_configuration_notice_wrap ssmenu_configuration_notice_error">
			<div class="ssmenu_configuration_notice">
				New menu configuration creation failed.  <span class="bellows-error-message">You may have a PHP error on your site which prevents AJAX requests from completing.</span>  <a class="ssmenu_configuration_notice_close" href="#">close</a>
			</div>
		</div>

		<div class="ssmenu_configuration_wrap ssmenu_configuration_notice_wrap ssmenu_configuration_delete_notice_success">
			<div class="ssmenu_configuration_notice">
				Configuration Deleted.  <a class="ssmenu_configuration_notice_close" href="#">close</a></p>
			</div>
		</div>

		<div class="ssmenu_configuration_wrap ssmenu_configuration_notice_wrap ssmenu_configuration_delete_notice_error">
			<div class="ssmenu_configuration_notice">
				Menu Configuration deletion failed.  <span class="bellows-delete-error-message">You may have a PHP error on your site which prevents AJAX requests from completing.</span>  <a class="ssmenu_configuration_notice_close" href="#">close</a>
			</div>
		</div>

		
	</div>

	<?php
}



function bellows_add_configuration_callback(){

	check_ajax_referer( 'bellows-add-configuration' , 'bellows_nonce' );

	//Check user permissions
	if( !current_user_can( BELLOWS_ADMIN_CAP ) ){
		status_header( '403' ); 
		die( 'User does not have permission' );
	}

	$response = array();

	$serialized_settings = $_POST['bellows_data'];

	$dirty_settings = array();
	parse_str( $serialized_settings, $dirty_settings );
	
	//ONLY ALLOW SETTINGS WE'VE DEFINED	
	$data = wp_parse_args( $dirty_settings, array( 'ssmenu_configuration_id' ) );

	$new_id = $data['ssmenu_configuration_id'];

	if( $new_id == '' ){
		$response['error'] = 'Please enter an ID. ';
	}
	else{
		//$new_id = sanitize_title( $new_id );
		$new_id = sanitize_key( $new_id );

		//update 
		$configs = get_option( BELLOWS_MENU_CONFIGURATIONS , array() );

		if( in_array( $new_id , $configs ) ){
			$response['error'] = 'That ID is already taken. ';
		}
		else{
			$configs[] = $new_id;
			update_option( BELLOWS_MENU_CONFIGURATIONS , $configs );
		}

		$response['id'] = $new_id;
	}

	$response['data'] = $data;

	echo json_encode( $response );

	die();
}
add_action( 'wp_ajax_bellows_add_configuration', 'bellows_add_configuration_callback' );


function bellows_delete_configuration_callback(){

	check_ajax_referer( 'bellows-delete-configuration' , 'bellows_nonce' );

	//Check user permissions
	if( !current_user_can( BELLOWS_ADMIN_CAP ) ){
		status_header( '403' ); 
		die( 'User does not have permission' );
	}

	$response = array();
//echo json_encode( $_POST['bellows_data'] );
//die();
	//$serialized_settings = $_POST['bellows_data'];
	//$dirty_settings = array();
	//parse_str( $serialized_settings, $dirty_settings );
	
	$dirty_settings = $_POST['bellows_data'];

	//ONLY ALLOW SETTINGS WE'VE DEFINED	
	$data = wp_parse_args( $dirty_settings, array( 'ssmenu_configuration_id' ) );

	$id = $data['ssmenu_configuration_id'];

	if( $id == '' ){
		$response['error'] = 'Missing ID';
	}
	else{
		
		$configs = get_option( BELLOWS_MENU_CONFIGURATIONS , array() );

		if( !in_array( $id , $configs ) ){
			$response['error'] = 'ID not in $configs ['.$id.']';
		}
		else{
			
			//Remove Menu from menus list in DB
			$i = array_search( $id , $configs );
			if( $i !== false ) unset( $configs[$i] );
			update_option( BELLOWS_MENU_CONFIGURATIONS , $configs );

			//Remove menu's custom styles
			bellows_delete_menu_styles( $id );

			$response['menus'] = $configs;
		}

		$response['id'] = $id;
	}

	$response['data'] = $data;

	echo json_encode( $response );

	die();
}
add_action( 'wp_ajax_bellows_delete_configuration', 'bellows_delete_configuration_callback' );




/**
 * DELETE SETTINGS
 */

add_filter( 'bellows_settings_subsections' , 'bellows_settings_subsection_delete' , 1000 , 2 );
add_filter( 'bellows_settings_panel_fields' , 'bellows_settings_panel_fields_delete' , 1000 );

function bellows_settings_subsection_delete( $subsections , $config_id ){
	if( $config_id != 'main' ){
		$subsections['delete'] = array(
			'title'	=> __( 'Delete' ),
		);
	}
	return $subsections;
}

function bellows_settings_panel_fields_delete( $fields = array() ){

	$delete_header = array(
		'name'	=> 'header_delete',
		'label'	=> __( 'Delete', 'bellows' ),
		'type'	=> 'header',
		'group'	=> 'delete',
	);

	$configs = bellows_get_menu_configurations( false );

	foreach( $configs as $config_id ){

		//Requres $menu var
		$delete_configuration = array(
			'name'	=> 'delete',
			'label'	=> __( 'Delete Configuration' , 'shiftnav' ),
			'desc'	=> '<a class="ssmenu_configuration_button ssmenu_configuration_button_delete" href="#" data-ssmenu-configuration-id="'.$config_id.'" data-ssmenu-nonce="'.wp_create_nonce( 'bellows-delete-configuration' ).'">'.__( 'Permanently Delete Configuration' , 'bellows' ).'</a>',
			'type'	=> 'html',
			'group'	=> 'delete',
		);

		$fields[BELLOWS_PREFIX.$config_id][3000] = $delete_header;
		$fields[BELLOWS_PREFIX.$config_id][3010] = $delete_configuration;
	}

	return $fields;
}