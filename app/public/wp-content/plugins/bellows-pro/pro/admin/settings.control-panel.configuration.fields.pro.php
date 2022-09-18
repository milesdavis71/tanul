<?php

add_filter( 'bellows_configuration_fields' , 'bellows_pro_get_configuration_fields' , 10 , 2 );
function bellows_pro_get_configuration_fields( $settings , $config_id ){


	$settings[65] = array(
		'name'	=> 'menu_type',
		'label'	=> __( 'Menu Type' , 'bellows' ),
		'type'	=> 'radio_advanced',
		'desc'	=> __( '' , 'bellows' ),
		'options'=> array(
			'group' => array(
				'accordion'	=> array(
					'name' => __( 'Standard Accordion' , 'bellows' ),
					'desc'	=> __( 'A standard accordion menu, with the toggles aligned to the far edge.' , 'bellows' )
				),
				'tree'	=> array(
					'name' => __( 'Tree', 'bellows' ),
					'desc'	=> __( 'A tree-style navigation, with toggles appearing before the item content, indented with the content within sub-trees.' , 'bellows' )
				),
			),
		),
		'default'=> 'accordion',
		'group'	=> 'basic',

		'customizer'	=> true,
		'customizer_section' => 'general',
	);

	$settings[75] = array(
			'name'	=> 'default_submenu_state',
			'label'	=> __( 'Default Submenu State' , 'bellows' ),
			'type'	=> 'radio',
			'desc'	=> __( 'Should the submenus start open or closed?' , 'bellows' ),
			'options'=> array(
				'closed'	=> __( 'Closed' , 'bellows' ),
				'open'	=> __( 'Open', 'bellows' ),
			),
			'default'=> 'closed',
			'group'	=> 'basic',

			'customizer'	=> true,
			'customizer_section' => 'general',
		);

	$settings[85] = array(
			'name'	=> 'submenu_indent',
			'label'	=> __( 'Submenu Indentation' , 'bellows' ),
			'type'	=> 'text',
			'desc'	=> __( 'Indentation distance for submenu items.' , 'bellows' ),
			
			'default'=> '',
			'group'	=> 'basic',

			'customizer'	=> true,
			'customizer_section' => 'general',
			'custom_style' => 'submenu_indent',
		);

	$settings[87] = array(
			'name'	=> 'slide_speed',
			'label'	=> __( 'Submenu Slide Speed' , 'bellows' ),
			'type'	=> 'text',
			'desc'	=> __( 'The duration of the submenu open/close slide, in milliseconds.' , 'bellows' ),
			
			'default'=> '400',
			'group'	=> 'basic',

			'customizer'	=> true,
			'customizer_section' => 'general',
		);



	$toggle_icons = array(
		'chevron-down',
		'chevron-circle-down',
		'angle-down',
		'angle-double-down',
		'arrow-circle-down',
		'arrow-down',
		'caret-down',
		'toggle-down',
		'plus',
		'plus-circle',
		'plus-square',
		'plus-square-o',
	);
	$toggle_icon_ops = array();
	foreach( $toggle_icons as $i ){
		$toggle_icon_ops[$i] = '<i class="fa fa-'.$i.'"></i>';
	}

	$settings[300]	= array(
		'name'		=> 'submenu_toggle_icon_expand',
		'label'		=> __( 'Submenu Toggle Icon [Expand]' , 'bellows' ),
		'desc'		=> __( 'The icon that will appear beside collapsed items, when clicked, it will expand the submenu.', 'bellows' ),
		'type'		=> 'radio',
		'options'	=> $toggle_icon_ops,
		'default'	=> 'chevron-down',
		'group'		=> 'layout',

		'customizer'			=> true,
		'customizer_section' 	=> 'general',
		'customizer_control'	=> 'radio_html'
	);


	$toggle_icons = array(
		'chevron-up',
		'chevron-circle-up',
		'angle-up',
		'angle-double-up',
		'arrow-circle-up',
		'arrow-up',
		'caret-up',
		'toggle-up',
		'minus',
		'minus-circle',
		'minus-square',
		'minus-square-o',
		'times',
		'times-circle',
		'times-circle-o',
	);
	$toggle_icon_ops = array();
	foreach( $toggle_icons as $i ){
		$toggle_icon_ops[$i] = '<i class="fa fa-'.$i.'"></i>';
	}

	$settings[310]	= array(
		'name'		=> 'submenu_toggle_icon_collapse',
		'label'		=> __( 'Submenu Toggle Icon [Collapse]' , 'bellows' ),
		'desc'		=> __( 'The icon that will appear beside expanded items, when clicked, it will collapse the submenu.', 'bellows' ),
		'type'		=> 'radio',
		'options'	=> $toggle_icon_ops,
		'default'	=> 'chevron-up',
		'group'		=> 'layout',

		'customizer'			=> true,
		'customizer_section' 	=> 'general',
		'customizer_control'	=> 'radio_html'
	);



	/** IMAGES **/

	$settings[350] = array(
		'name'	=> 'header_images',
		'label'	=> __( 'Images' , 'bellows' ),
		'type'	=> 'header',
		'desc'	=> __( 'Image settings' , 'bellows' ),
		'group'	=> 'images',
	);

	$settings[360] = array(
		'name'		=> 'image_size',
		'label'		=> __( 'Image Size' , 'bellows' ),
		'type'		=> 'radio_advanced',
		'options' 	=> 'bellows_get_image_size_ops_inherit',
		'default' 	=> 'full',
		'desc'		=> __( 'This is the size of the image file that will be used in the menu' , 'bellows' ),
		'group'		=> 'images',
	);


	$settings[2000] = array(
			'name'	=> 'header_style_customizations',
			'label' => __( 'Style Customizations' , 'bellows' ),
			'desc'	=> __( 'You can find these settings in the Customizer as well, with a live preview' , 'bellows' ),
			'type'	=> 'header',
			'group'	=> 'styles',
		);

	$settings[2010] = array(
			'name'	=> 'top_level_background_color',
			'label'	=> __( 'Top Level Background Color' , 'bellows' ),
			'desc'	=> __( 'Background color of the menu when collapsed' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_background_color',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);

	$settings[2020] = array(
			'name'	=> 'top_level_background_color_hover',
			'label'	=> __( 'Top Level Background Color [Hover]' , 'bellows' ),
			'desc'	=> __( 'Background color of hovered top level items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_background_color_hover',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);

	$settings[2030] = array(
			'name'	=> 'top_level_background_color_active',
			'label'	=> __( 'Top Level Background Color [Active]' , 'bellows' ),
			'desc'	=> __( 'Background color of expanded top level items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_background_color_active',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);

	$settings[2040] = array(
			'name'	=> 'top_level_background_color_current',
			'label'	=> __( 'Top Level Background Color [Current]' , 'bellows' ),
			'desc'	=> __( 'Background color of current top level items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_background_color_current',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);

	$settings[2050] = array(
			'name'	=> 'top_level_font_color',
			'label'	=> __( 'Top Level Font Color' , 'bellows' ),
			'desc'	=> __( 'Default font color of top level items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_font_color',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);

	$settings[2060] = array(
			'name'	=> 'top_level_font_color_hover',
			'label'	=> __( 'Top Level Font Color [Hover]' , 'bellows' ),
			'desc'	=> __( 'Font color of hovered top level items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_font_color_hover',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);

	$settings[2070] = array(
			'name'	=> 'top_level_font_color_active',
			'label'	=> __( 'Top Level Font Color [Active]' , 'bellows' ),
			'desc'	=> __( 'Font color of expanded top level items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_font_color_active',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);

	$settings[2080] = array(
			'name'	=> 'top_level_font_color_current',
			'label'	=> __( 'Top Level Font Color [Current]' , 'bellows' ),
			'desc'	=> __( 'Font color of current top level items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_font_color_current',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);



	$settings[2100] = array(
			'name'	=> 'top_level_font_size',
			'label'	=> __( 'Top Level Font Size' , 'bellows' ),
			'type'	=> 'text',
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_font_size',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);

	$settings[2110] = array(
			'name'	=> 'top_level_font_weight',
			'label'	=> __( 'Top Level Font Weight' , 'bellows' ),
			'type'	=> 'radio',
			'options'	=> array(
				''			=> __( 'Default', 'bellows' ),
				'normal'	=> __( 'Normal' , 'bellows' ),
				'bold'		=> __( 'Bold' , 'bellows' ),
			),
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_font_weight',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);

	$settings[2120] = array(
			'name'	=> 'top_level_item_padding',
			'label'	=> __( 'Top Level Item Padding (Deprecated)' , 'bellows' ),
			'desc'	=> __( 'Controls the spacing around the item content.  Controls all padding.  Deprecated in favor of individual padding controls below.  Not compatible with left-edge toggle buttons.' , 'bellows' ),
			'type'	=> 'text',
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_item_padding',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);

	$settings[2122] = array(
			'name'	=> 'top_level_item_v_padding',
			'label'	=> __( 'Top Level Item Vertical Padding' , 'bellows' ),
			'desc'	=> __( 'Controls the spacing above and below the item content.  Overrides general padding.' , 'bellows' ),
			'type'	=> 'text',
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_item_v_padding',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);
	$settings[2123] = array(
			'name'	=> 'top_level_item_h_padding',
			'label'	=> __( 'Top Level Item Horizontal Padding' , 'bellows' ),
			'desc'	=> __( 'Controls the spacing to the left and right of the item content.  Overrides general padding.' , 'bellows' ),
			'type'	=> 'text',
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_item_h_padding',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);

	$settings[2130] = array(
			'name'	=> 'top_level_divider_color',
			'label'	=> __( 'Top Level Divider Color' , 'bellows' ),
			'desc'	=> __( 'The color of the lines dividing top level menu items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'top_level_divider_color',
			'customizer'			=> true,
			'customizer_section' 	=> 'top_level',
		);






	/* SUBMENU */


	$settings[2500] = array(
			'name'	=> 'submenu_background',
			'label'	=> __( 'Submenu Background' , 'bellows' ),
			'desc'	=> __( 'Background color of the submenus' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'submenu_background',
			'customizer'			=> true,
			'customizer_section' 	=> 'submenu',
		);

	$settings[2510] = array(
			'name'	=> 'submenu_item_background_hover',
			'label'	=> __( 'Submenu Item Background [Hover]' , 'bellows' ),
			'desc'	=> __( 'Background color of hovered submenu items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'submenu_item_background_hover',
			'customizer'			=> true,
			'customizer_section' 	=> 'submenu',
		);

	$settings[2520] = array(
			'name'	=> 'submenu_item_background_current',
			'label'	=> __( 'Submenu Item Background [Current]' , 'bellows' ),
			'desc'	=> __( 'Background color of current submenu items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'submenu_item_background_current',
			'customizer'			=> true,
			'customizer_section' 	=> 'submenu',
		);

	$settings[2530] = array(
			'name'	=> 'submenu_item_color',
			'label'	=> __( 'Submenu Item Color' , 'bellows' ),
			'desc'	=> __( 'Font color of submenu items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'submenu_item_color',
			'customizer'			=> true,
			'customizer_section' 	=> 'submenu',
		);

	$settings[2540] = array(
			'name'	=> 'submenu_item_color_hover',
			'label'	=> __( 'Submenu Item Color [Hover]' , 'bellows' ),
			'desc'	=> __( 'Font color of hovered submenu items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'submenu_item_color_hover',
			'customizer'			=> true,
			'customizer_section' 	=> 'submenu',
		);

	$settings[2550] = array(
			'name'	=> 'submenu_item_color_current',
			'label'	=> __( 'Submenu Item Color [Current]' , 'bellows' ),
			'desc'	=> __( 'Font color of current submenu items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'submenu_item_color_current',
			'customizer'			=> true,
			'customizer_section' 	=> 'submenu',
		);

	$settings[2560] = array(
			'name'	=> 'submenu_item_font_size',
			'label'	=> __( 'Submenu Item Font Size' , 'bellows' ),
			'type'	=> 'text',
			'group'	=> 'styles',

			'custom_style'			=> 'submenu_item_font_size',
			'customizer'			=> true,
			'customizer_section' 	=> 'submenu',
		);


	$settings[2570] = array(
			'name'	=> 'submenu_item_padding',
			'label'	=> __( 'Submenu Item Padding (Deprecated)' , 'bellows' ),
			'desc'	=> __( 'Controls the spacing around the item content.  Deprecated in favor of individual padding controls below. Not compatible with left-edge toggle buttons.' , 'bellows' ),
			'type'	=> 'text',
			'group'	=> 'styles',

			'custom_style'			=> 'submenu_item_padding',
			'customizer'			=> true,
			'customizer_section' 	=> 'submenu',
		);

	$settings[2571] = array(
			'name'	=> 'submenu_item_v_padding',
			'label'	=> __( 'Submenu Item Vertical Padding' , 'bellows' ),
			'desc'	=> __( 'Controls the top and bottom padding the item content. Overrides general padding.' , 'bellows' ),
			'type'	=> 'text',
			'group'	=> 'styles',

			'custom_style'			=> 'submenu_item_v_padding',
			'customizer'			=> true,
			'customizer_section' 	=> 'submenu',
		);
	$settings[2572] = array(
			'name'	=> 'submenu_item_h_padding',
			'label'	=> __( 'Submenu Item Horizontal Padding' , 'bellows' ),
			'desc'	=> __( 'Controls the left and right padding around the item content. Overrides general padding.  Will inherit top level padding for alignment if not set here.' , 'bellows' ),
			'type'	=> 'text',
			'group'	=> 'styles',

			'custom_style'			=> 'submenu_item_h_padding',
			'customizer'			=> true,
			'customizer_section' 	=> 'submenu',
		);


	$settings[2580] = array(
			'name'	=> 'submenu_item_divider_color',
			'label'	=> __( 'Submenu Item Divider Color' , 'bellows' ),
			'desc'	=> __( 'Color of dividers between submenu items' , 'bellows' ),
			'type'	=> 'color',
			'group'	=> 'styles',

			'custom_style'			=> 'submenu_item_divider_color',
			'customizer'			=> true,
			'customizer_section' 	=> 'submenu',
		);

	$settings[2590] = array(
			'name'	=> 'submenu_item_divider',
			'label'	=> __( 'Show Submenu Item Divider' , 'bellows' ),
			'desc'	=> __( 'Disable this if you do not want borders between the submenu items' , 'bellows' ),
			'type'	=> 'checkbox',
			'default'=> 'on',
			'group'	=> 'styles',

			'custom_style'			=> 'submenu_item_divider',
			'customizer'			=> true,
			'customizer_section' 	=> 'submenu',
		);

	

	return $settings;

}






function bellows_get_image_size_ops_inherit(){
	return bellows_get_image_size_ops( array( 'inherit' ) );
}

function bellows_get_image_size_dimensions( $s ){

	if( $s == 'inherit' || $s == 'full' ) return false;

	$available_sizes = get_intermediate_image_sizes();

	$w = $h = $c = 0;

	if( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ){
		$w = get_option( $s . '_size_w' );
		$h = get_option( $s . '_size_h' );
		$c = (bool) get_option( $s . '_crop' );
	}
	else if ( isset( $_wp_additional_image_sizes[ $s ] ) ) {
		
		$w = $_wp_additional_image_sizes[ $s ]['width'];
		$h = $_wp_additional_image_sizes[ $s ]['height'];
		$c = $_wp_additional_image_sizes[ $s ]['crop'];
	}

	return compact( 'w', 'h', 'c' );
}

function bellows_get_image_size_ops( $exclude = array() ){

		
		$o = array(
			'inherit'	=> array(
				'name'	=> __( 'Inherit' , 'bellows' ),
				'desc'	=> __( 'Inherit settings from the menu instance settings' , 'bellows' )
			),
			'full'	=> array(
				'name'	=> __( 'Full' , 'bellows' ),
				'desc'	=> __( 'Display image at natural dimensions' , 'bellows' )
			),

		);

		$available_sizes = get_intermediate_image_sizes();

		//$o = array_merge( $o , $available_sizes );
		foreach( $available_sizes as $s ){

			$name = ucfirst( $s );
			$desc = '<small>'.__( 'Registered image size ' , 'bellows' ) . '<code>'.$s.'</code></small>';

			global $_wp_additional_image_sizes;

			$w = false;
			if( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ){
				$w = get_option( $s . '_size_w' );
				$h = get_option( $s . '_size_h' );
				$c = (bool) get_option( $s . '_crop' );
			}
			else if ( isset( $_wp_additional_image_sizes[ $s ] ) ) {
				
				$w = $_wp_additional_image_sizes[ $s ]['width'];
				$h = $_wp_additional_image_sizes[ $s ]['height'];
				$c = $_wp_additional_image_sizes[ $s ]['crop'];
			}
			if( $w ){
				 $desc = "($w &times; $h)" . ( $c ? ' [cropped]' : '' ).'<br/>' . $desc;
			}

			$o[$s] = array(
				'name'	=> $name,
				'desc'	=> $desc,
			);
		}

		foreach( $exclude as $ex ){
			unset( $o[$ex] );
		}

		$ops = array(
			'group'	=> $o,
		);

		return $ops;
}



function bellows_integration_autogeneration_code_ui( $config_id ){
	?>
	<div class="bellows-autogenerator-code">

	<?php
		//Taxonomies
		$taxonomies = get_taxonomies(
			array(
				'public'	=> true,
			),
			'objects'
		);
		foreach( $taxonomies as $tax_id => $tax ){
			?>
			<label><input type="checkbox" name="tax" value="<?php echo $tax_id; ?>"/> <?php echo $tax->label; ?></label>
			<?php
		}


		//orderby

		//order

		//hide_empty

		//number

		//child_of //ajax?



	?>

	</div>

	
	<?php
	//return $integration_code;
}



function bellows_settings_links_pro(){
	echo '<a target="_blank" class="button button-secondary bellows-button-support" href="'.bellows_get_support_url().'"><i class="fa fa-life-ring"></i> Support</a> ';
}
add_action( 'bellows_settings_before_title' , 'bellows_settings_links_pro' );