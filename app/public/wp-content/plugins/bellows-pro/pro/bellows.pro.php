<?php

require_once( BELLOWS_DIR.'pro/admin/admin.pro.php' );
require_once( BELLOWS_DIR.'pro/skins.pro.php' );
require_once( BELLOWS_DIR.'pro/icons.php' );
require_once( BELLOWS_DIR.'pro/widgets.php' );
require_once( BELLOWS_DIR.'pro/autopopulator.php' );
require_once( BELLOWS_DIR.'pro/widget.autopopulation.php' );
require_once( BELLOWS_DIR.'pro/updates/updater.php' );

function bellows_pro_init(){
	add_action( 'bellows_register_icons' , 'bellows_register_default_icons' );
	do_action( 'bellows_register_icons' );

	//require_once( BELLOWS_DIR . 'pro/search.php' );
}
add_action( 'init' , 'bellows_pro_init' ); 


function bellows_pro_load_assets(){

	$assets = BELLOWS_URL . 'pro/assets/';
	

	//if( bellows_op( 'load_bellows_css' , 'general' ) != 'off' ){
		wp_deregister_style( 'bellows' );
		//wp_dequeue_style( 'bellows' );
		if( SCRIPT_DEBUG ){
			wp_enqueue_style( 'bellows' , $assets.'css/bellows.css' , false , BELLOWS_VERSION );
		}
		else{
			wp_enqueue_style( 'bellows' , $assets.'css/bellows.min.css' , false , BELLOWS_VERSION );
		}

	//}
}

add_action( 'wp_enqueue_scripts' , 'bellows_pro_load_assets' , 22 );