<?php

function bellows_item_save_inherit_featured_image( $item_id , $setting , $val , &$saved_settings ){

	if( $val == 'cache' ){

		//Determine Featured Image
		$post_id = get_post_meta( $item_id , '_menu_item_object_id' , true );
		$thumb_id = get_post_thumbnail_id( $post_id );

		//Assign Featured Image
		$saved_settings['item_image'] = $thumb_id;
		update_post_meta( $item_id , BELLOWS_MENU_ITEM_META_KEY , $saved_settings );

	}
}