<?php

function bellows_item_save_create_auto_widget_area( $item_id , $setting , $val , &$saved_settings ){

	$menu_item_widget_areas = get_option( BELLOWS_MENU_ITEM_WIDGET_AREAS , array() );

	//Widget Area ID
	//$widget_area_id = 'umitem_'.$item_id;
	$widget_area_id = $item_id;

	//If Widget Area Name is set, set it
	if( $val ){
		$menu_item_widget_areas[$widget_area_id] = $val;
	}
	//Remove if Widget Area name is blank
	else{
		unset( $menu_item_widget_areas[$widget_area_id] );
	}

	update_option( BELLOWS_MENU_ITEM_WIDGET_AREAS , $menu_item_widget_areas );

}


add_action( 'init' , 'bellows_register_menu_item_auto_widget_areas' , 500 );
function bellows_register_menu_item_auto_widget_areas(){
	$menu_item_widget_areas = get_option( BELLOWS_MENU_ITEM_WIDGET_AREAS , array() );

	foreach( $menu_item_widget_areas as $id => $name ){
		register_sidebar( array(
			'name'			=> '[Bellows] '.$name,
			'id'			=> 'bellowsitem_'.$id,
			'description'	=> __( 'Bellows Custom Widget Area for Menu Item ', 'bellows' ).$id, // . '. <a href="'.admin_url('themes.php?page=bellows-settings&do=widget-manager').'">Manage</a>',
			'before_title'	=> '<h3 class="bellows-widgettitle bellows-target">',
			'after_title'	=> '</h3>',
			'before_widget'	=> '<li id="%1$s" class="widget %2$s bellows-widget bellows-column bellows-item-header">',
			'after_widget'	=> '</li>',
			//'class'			=> 'bellows-widget',
		));
	}
	
}




add_action( 'before_delete_post' , 'bellows_delete_item_widgets' );
function bellows_delete_item_widgets( $post_id ){

	if( 'nav_menu_item' == get_post_type( $post_id ) ){

		$menu_item_widget_areas = get_option( BELLOWS_MENU_ITEM_WIDGET_AREAS , array() );

		unset( $menu_item_widget_areas[$post_id] );

		update_option( BELLOWS_MENU_ITEM_WIDGET_AREAS , $menu_item_widget_areas );
		
	}
}