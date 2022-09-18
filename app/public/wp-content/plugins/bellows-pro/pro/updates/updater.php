<?php

//require_once( 'backup.php' );

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'BELLOWS_UPDATES_URL', 'http://sevenspark.com' ); 

// the name of your product. This should match the download name in EDD exactly
define( 'BELLOWS_UPDATES_NAME', 'Bellows Pro' ); 

if( !class_exists( 'BELLOWS_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

function bellows_plugin_updater() {

	// retrieve our license key from the DB
	$license_key = trim( bellows_op( 'license_code' , 'updates' ) );

	// setup the updater
	$edd_updater = new BELLOWS_SL_Plugin_Updater( BELLOWS_UPDATES_URL, BELLOWS_FILE, array( 
			'version' 	=> BELLOWS_VERSION, 				// current version number
			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
			'item_name' => BELLOWS_UPDATES_NAME, 	// name of this plugin
			'author' 	=> 'Chris Mavricos, SevenSpark',  // author of this plugin
			'url'		=> home_url(),
		)
	);

}
add_action( 'admin_init', 'bellows_plugin_updater', 0 );





//UPDATES SETTINGS TAB

add_filter( 'bellows_settings_panel_sections' , 'bellows_updater_settings_panel' );
function bellows_updater_settings_panel( $sections = array() ){
	$sections[] = array(
		'id'	=> BELLOWS_PREFIX.'updates',
		'title'	=> __( 'Updates' , 'bellows' ),
		'sub_sections' => array(
			'license'	=> array(
				'title' => __( 'License' , 'ubermenu' ),
			),
		),
	);

	return $sections;

}

if( is_admin() ) add_filter( 'bellows_settings_panel_fields' , 'bellows_updater_settings_panel_fields' );	//only run in admin so that we're not running extra checks and backups on the front end
function bellows_updater_settings_panel_fields( $fields = array() ){

	$updates = BELLOWS_PREFIX.'updates';

	$desc = __( 'Enter your license code to receive updates', 'bellows' );

	$license_data = get_option( 'bellows_license_data' );
	if( $license_data ){
		$license_status = $license_data->license;

		switch( $license_status ){
			case 'invalid':
				$desc = '<span class="bellows-license-invalid">'.__( 'License Invalid' , 'bellows' ).'</span>';
				$desc.= '<span class="bellows-license-error">'.$license_data->error;
				if( $license_data->error == 'expired' ){
					$desc.= ' '.$license_data->expires;
				}
				$desc.= '</span>';
				break;
			case 'valid': 
				$desc = __( 'License is valid' , 'bellows' );
				break;
		};
	}


	$fields[$updates][] = array(
			'name'	=> 'license_info',
			'label' => __( 'License Info' , 'bellows' ), //__( 'Automatic Updates' , 'bellows' ),
			'desc'	=> __( 'Enter your Bellows License info to receive updates', 'bellows' ),
			'type'	=> 'header',
			'group'	=> 'license',
		);


	$fields[$updates][] = array(
			'name'	=> 'license_code',
			'label'	=> __( 'License Code' , 'bellows' ),
			'desc'	=> $desc,
			'type'	=> 'text',
			'group'	=> 'license',
	);


	return $fields;
}





/************************************
* this illustrates how to activate 
* a license key
*************************************/

// add_action( 'bellows_settings_panel' , 'bellows_update_license_activation' );
// function bellows_update_license_activation(){

// 	shiftp( $_POST );

// 	if( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true ){
// 		if( 'valid' != get_option( 'bellows_license_status' , false ) ){
// 			$license = bellows_op( 'license_key' , 'updates' );
// 			if( $license ){
// 				//bellows_activate_license( $license );
// 			}
// 		}
// 	}
// }

//Only runs when license value changes
function bellows_activate_license( $old_value  ) {

	// retrieve the license from the database
	$license = trim( bellows_op( 'license_code' , 'updates' ) );

	//$license = $value['license_code'];

	if( $license == '' ){
		update_option( 'bellows_license_status' , '' );
		update_option( 'bellows_license_data' , '' );
		return;
	}

	// data to send in our API request
	$api_params = array( 
		'edd_action'=> 'activate_license', 
		'license' 	=> $license, 
		'item_name' => urlencode( BELLOWS_UPDATES_NAME ), // the name of our product in EDD
		'url'       => home_url()
	);

	
	// Call the custom API.
	$response = wp_remote_get( add_query_arg( $api_params, BELLOWS_UPDATES_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

	// make sure the response came back okay
	if ( is_wp_error( $response ) )
		return false;

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	update_option( 'bellows_license_data', $license_data );
		//->license (valid or invalid)
		//->error (expired)
		//->expires (2015-03-23 22:36:04)
}
add_action( 'update_option_'.BELLOWS_PREFIX.'updates' , 'bellows_activate_license' , 10 , 2 );
//add_action( 'admin_init', 'bellows_activate_license' , 10 , 1 );

