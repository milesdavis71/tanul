<?php


function bellows_configuration_subsections_pro(  $subsections , $config_id ){
	$subsections['images']	= array(
		'title'	=> __( 'Images' , 'bellows' )
	);
	$subsections['styles']	= array(
		'title'	=> __( 'Style Customizations' , 'bellows' )
	);
	return $subsections;
}
add_filter( 'bellows_settings_subsections' , 'bellows_configuration_subsections_pro' , 50 , 2 );