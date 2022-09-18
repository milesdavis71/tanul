<?php

function bellows_menu_item_settings(){

	$settings = array();
	$panels = bellows_menu_item_settings_panels();
	foreach( $panels as $id => $panel ){
		$settings[$id] = array();
	}


	$settings['general'][10] = array(
		'id' 		=> 'disable_link',
		'title'		=> __( 'Disable Link', 'bellows' ),
		'type'		=> 'checkbox',
		'default' 	=> 'off',
		'desc'		=> __( 'Check this box to remove the link from this item; clicking a disabled link will not result in any URL being followed.  If this item has children, the entire item will act as a toggle for the submenu.' , 'bellows' ),
	);

	$settings['general'][20] = array(
		'id' 		=> 'disable_text',
		'title'		=> __( 'Disable Text', 'bellows' ),
		'desc'		=> __( 'Do not display the text for this item' , 'bellows' ),
		'type'		=> 'checkbox',
		'default' 	=> 'off',
	);

	$settings['general'][45] = array(
		'id' 		=> 'custom_url',
		'title'		=> __( 'Custom URL Override', 'bellows' ),
		'type'		=> 'text',
		'default' 	=> '',
		'desc'		=> __( 'Override this item\'s URL.  Shortcodes in this field will be processed.' , 'bellows' ),
	);

	$settings['general'][50] = array(
		'id'		=> 'default_submenu_state',
		'title'		=> __( 'Default Submenu State' , 'bellows' ),
		'desc'		=> __( 'Leave set to "Inherit" to use the value set in the Control Panel.  This setting can be used to override the default state for this specific item\'s submenu.' , 'bellows' ),
		'type'		=> 'radio',
		'type_class'=> 'ssmenu-radio-blocks',
		'ops'		=> array(
			'group'	=> array(
			 	'inherit'	=> array(
					'name'	=> __( 'Inherit' , 'bellows' ),
					'desc'	=> __( 'Inherit the value set in the Control Panel' , 'bellows' )
				),
				'closed'	=> array(
					'name'	=> __( 'Closed' , 'bellows' ),
					'desc'	=> __( 'The submenu will load in the closed state' , 'bellows' )
				),
				'open'	=> array(
					'name'	=> __( 'Open' , 'bellows' ),
					'desc'	=> __( 'The submenu will load in the open state' , 'bellows' )
				),

			),
		),
		'default'	=> 'inherit',
	);

	$settings['general'][60] = array(
		'id' 		=> 'show_more',
		'title'		=> __( 'Show More', 'bellows' ),
		'type'		=> 'checkbox',
		'default' 	=> 'off',
		'desc'		=> __( 'Display this item as a "Show More" item.  All items after this item in this level will be hidden by default, and will be revealed when this item is clicked.  Note that this item will not do anything other than toggle the items after it.' , 'bellows' ),
	);
	$settings['general'][70] = array(
		'id' 		=> 'show_less_text',
		'title'		=> __( 'Show Less Text', 'bellows' ),
		'type'		=> 'text',
		'default' 	=> __( 'Show Less', 'bellows' ),
		'desc'		=> __( 'If you enable "Show More", this is the text that will appear in the corresponding "Show Less" item.  By default, it will say "Show Less", but you can change this to whatever you like.' , 'bellows' ),
	);


	$settings['icon'][10] = array(
		'id' 		=> 'icon',
		'title'		=> __( 'Icon', 'bellows' ),
		'type'		=> 'icon',
		'default' 	=> '',
		'desc'		=> '',
		'ops'		=> bellows_get_icon_ops(),
	);

	$settings['icon'][20] = array(
		'id'	=> 'icon_custom_class',
		'title'	=> __( 'Custom Icon Class' , 'bellows' ),
		'type'	=> 'text',
		'default'=> '',
		'desc'	=> __( 'Add a custom class to the i tag.  If an icon is set above, this class will be appended.  If no icon is set above, an icon will appear with this class.', 'bellows' ),
	);


	// $settings['submenu'][20] = array(
	// 	'id' 		=> 'submenu_type',
	// 	'title'		=> __( 'Submenu Type', 'bellows' ),
	// 	'type'		=> 'select',
	// 	'default'	=> 'default',
	// 	'desc'		=> __( '[Requires Pro Version] Overrides the default submenu type.  For the Lite version, only "Always visible" is available.  Can be changed to "Accordion" or "Shift" with the Pro version.' , 'bellows' ),
	// 	'ops'		=> array(
	// 					'default'	=>  __( 'Menu Default', 'bellows' ),
	// 					'always'	=>	__( 'Always visible', 'bellows' ),
	// 				),
	// );

	$settings['image'][10] = array(
		'id'		=> 'item_image',
		'title'		=> __( 'Image' , 'bellows' ),
		'desc'		=> __( 'Click "Select" to upload or choose a new image.  Click "Remove" to remove the image.  Click "Edit" to edit the currently selected image.' , 'bellows' ),
		'type'		=> 'media',
		'default'	=> '',

	);

	$settings['image'][20] = array(
		'id'		=> 'inherit_featured_image',
		'title'		=> __( 'Inherit Featured Image' , 'bellows' ),
		'desc'		=> __( 'For Post Menu Items, automatically inherit the Post\'s featured image for this item.' , 'bellows' ),
		'type'		=> 'radio',
		'type_class'=> 'ssmenu-radio-blocks',
		'ops'		=> array(
			'group'	=> array(
				'off'	=> array(
					'name'	=> __( 'Disabled' , 'bellows' ),
					'desc'	=> __( 'Do not inherit the image' , 'bellows' )
				),
				'cache'	=> array(
					'name'	=> __( 'Assign Featured Image on Save' , 'bellows' ),
					'desc'	=> __( '[More efficient] When this item is saved, the current featured image from the post will be assigned.  It will not be updated until you save this item again.', 'bellows' )
				),
				'on'	=> array(
					'name'	=> __( 'Dynamically Inherit' , 'bellows' ),
					'desc'	=> __( '[Less efficient] Each time the menu item is displayed, dynamically find the item\'s featured image.' , 'bellows' )
				),
			)
		),
		'default'	=> 'off',
		'scenario'	=> __( 'Page/Post Menu Items' , 'bellows' ),
		'on_save'	=> 'inherit_featured_image',

	);

	$settings['image'][30] = array(
		'id'		=> 'image_position',
		'title'		=> __( 'Image Position' , 'bellows' ),
		'desc'		=> __( 'Position the image above or below the menu item text' , 'bellows' ),
		'type'		=> 'radio',
		'type_class'=> 'ssmenu-radio-blocks',
		'ops'		=> array(
			'group' => array(
						'before'	=> array( 'name' => __( 'Before', 'bellows' ) ),
						'after'		=> array( 'name' => __( 'After' , 'bellows' ) ),
					)
		),
		'default'	=> 'before',

	);

	$settings['image'][40] = array(
		'id'		=> 'image_padding',
		'title'		=> __( 'Pad Image' , 'bellows' ),
		'desc'		=> __( 'By default, the image expands to the full width of the container.  To pad it and align with the text, check this option.' , 'bellows' ),
		'type'		=> 'checkbox',
		'default'	=> 'off',

	);

	$settings['custom_content'][10] = array(
		'id'		=> 'custom_content',
		'title'		=> __( 'Custom Content' , 'bellows' ),
		'type'		=> 'textarea',
		'default'	=> '',
		'desc'		=> __( 'Can contain HTML and shortcodes.', 'bellow' ),
	);


	$settings['custom_content'][20] = array(
		'id'		=> 'pad_custom_content',
		'title'		=> __( 'Pad Custom Content' , 'bellows' ),
		'type'		=> 'checkbox',
		'default'	=> 'on',
		'desc'		=> __( 'Pad the content area so that it aligns similarly to other menu items', 'bellows' ),
	);

	$settings['custom_content'][30] = array(
		'id'		=> 'custom_content_only',
		'title'		=> __( 'Custom Content Only' , 'bellows' ),
		'type'		=> 'checkbox',
		'default'	=> 'off',
		'desc'		=> __( 'Only display custom content, no other part of this item.  Note that submenus will not be accessible.', 'bellows' ),
	);




	/** WIDGETS **/

	$settings['widgets'][10] = array(
		'id' 		=> 'auto_widget_area',
		'title'		=> __( 'Custom Widget Area' , 'bellows' ),
		'type'		=> 'text',
		'default' 	=> '',
		'desc'		=> __( 'Enter a name for your Widget Area, and a widget area specifically for this menu item will be automatically be created in the ' , 'bellows' ) . '<a target="_blank" href="'.admin_url('widgets.php').'">'.__( 'Widgets Screen' , 'bellows' ).'</a>',
		//'tip'		=> '',
		'on_save'	=> 'create_auto_widget_area'
	);






	$settings['custom_style'][10] = array(
		'id'		=> 'background_color',
		'title'		=> __( 'Background Color' , 'bellows' ),
		'type'		=> 'color',
		'default'	=> '',
		'desc'		=> '',
		'on_save'	=> 'background_color'
	);

	$settings['custom_style'][20] = array(
		'id'		=> 'font_color',
		'title'		=> __( 'Font Color' , 'bellows' ),
		'type'		=> 'color',
		'default'	=> '',
		'desc'		=> '',
		'on_save'	=> 'font_color'

	);

	$settings['custom_style'][25] = array(
		'id'		=> 'background_color_hover',
		'title'		=> __( 'Background Color [Hover]' , 'bellows' ),
		'type'		=> 'color',
		'default'	=> '',
		'desc'		=> '',
		'on_save'	=> 'background_color_hover'

	);

	$settings['custom_style'][30] = array(
		'id'		=> 'font_color_hover',
		'title'		=> __( 'Font Color [Hover]' , 'bellows' ),
		'type'		=> 'color',
		'default'	=> '',
		'desc'		=> '',
		'on_save'	=> 'font_color_hover'

	);

	$settings['custom_style'][35] = array(
		'id'		=> 'background_color_active',
		'title'		=> __( 'Background Color [Active]' , 'bellows' ),
		'type'		=> 'color',
		'default'	=> '',
		'desc'		=> '',
		'on_save'	=> 'background_color_active'

	);

	$settings['custom_style'][40] = array(
		'id'		=> 'font_color_active',
		'title'		=> __( 'Font Color [Active]' , 'bellows' ),
		'type'		=> 'color',
		'default'	=> '',
		'desc'		=> '',
		'on_save'	=> 'font_color_active'

	);

	$settings['custom_style'][45] = array(
		'id'		=> 'background_color_current',
		'title'		=> __( 'Background Color [Current]' , 'bellows' ),
		'type'		=> 'color',
		'default'	=> '',
		'desc'		=> '',
		'on_save'	=> 'background_color_current'

	);

	$settings['custom_style'][50] = array(
		'id'		=> 'font_color_current',
		'title'		=> __( 'Font Color [Current]' , 'bellows' ),
		'type'		=> 'color',
		'default'	=> '',
		'desc'		=> '',
		'on_save'	=> 'font_color_current'

	);

	$settings['custom_style'][60] = array(
		'id'		=> 'font_size',
		'title'		=> __( 'Font Size' , 'bellows' ),
		'type'		=> 'text',
		'default'	=> '',
		'desc'		=> '',
		'on_save'	=> 'font_size'

	);

	$settings['custom_style'][70] = array(
		'id'		=> 'font_weight',
		'title'		=> __( 'Font Weight' , 'bellows' ),
		'type'		=> 'radio',
		'default'	=> '',
		'ops'		=> array( 'group1' => array(
						'' 			=> array( 'name' => 'Default' ),
						'normal'	=> array( 'name' => 'Normal' ),
						'bold'		=> array( 'name' => 'Bold' ),
					),
		),
		'desc'		=> '',
		'on_save'	=> 'font_weight'

	);

	$settings['custom_style'][80] = array(
		'id'		=> 'padding',
		'title'		=> __( 'Padding' , 'bellows' ),
		'type'		=> 'text',
		'default'	=> '',
		'desc'		=> __( 'Set the padding for this specific item' , 'bellows' ),
		'on_save'	=> 'padding'

	);

	return apply_filters( 'bellows_menu_item_settings' , $settings );

}



add_filter( 'bellows_icon_custom_class' , 'bellows_icons_custom_class_setting' , 10 , 3 );
function bellows_icons_custom_class_setting( $icon_classes , $item_id , $custom_class ){
	if( $custom_class ){
		$icon_classes.= ' '.$custom_class;
	}
	return $icon_classes;
}
