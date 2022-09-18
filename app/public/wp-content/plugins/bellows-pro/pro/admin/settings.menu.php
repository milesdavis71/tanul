<?php

/**
 * Load the assets for nav-menu.php, as well as the settings for individual items 
 */
add_action( 'admin_print_styles-nav-menus.php' , 'bellows_admin_menu_load_assets' );
function bellows_admin_menu_load_assets() {

	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script('wp-color-picker');
	wp_enqueue_script( 'jquery' );


	$assets = BELLOWS_URL . 'assets/';
	$pro_assets = BELLOWS_URL . 'pro/admin/assets/';
	wp_enqueue_style( 'bellows-menu-admin', $pro_assets.'css/admin.menu.css' );
	wp_enqueue_style( 'bellows-menu-admin-font-awesome', $assets.'css/fontawesome/css/font-awesome.min.css' );
	wp_enqueue_script( 'bellows-menu-admin', $pro_assets.'js/admin.menu.js' , array( 'jquery' ) , BELLOWS_VERSION , true );

	$bellows_menu_data = bellows_get_menu_items_data();

	wp_localize_script( 'bellows-menu-admin' , 'bellows_menu_item_data' , $bellows_menu_data );

	wp_localize_script( 'bellows-menu-admin' , 'bellows_meta' , array( 
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'		=> bellows_menu_item_settings_nonce(),
	) );
}



function bellows_menu_item_settings_panel(){

	$panels = bellows_menu_item_settings_panels();
	$settings = bellows_menu_item_settings();

	?>
	<div class="bellows-js-check">
		<div class="bellows-js-check-peek"><i class="fa fa-truck"></i> Loading Bellows...</div>
		<div class="bellows-js-check-details">
			<p>
			If this message does not disappear, it means that Bellows has not been able to load.  
			This most commonly indicates that you have a javascript error on this page, which will need to be resolved in order to allow Bellows to run.
			</p>
		</div>
	</div>
	<div class="bellows-menu-item-settings-wrapper">

		<div class="bellows-menu-item-settings-topper">
			<i class="fa fa-cogs"></i> BELLOWS SETTINGS
		</div>

		<div class="bellows-menu-item-panel bellows-menu-item-panel-negative">

			<div class="bellows-menu-item-panel-info" >

				<div class="bellows-menu-item-stats shift-clearfix">
					<div class="bellows-menu-item-title">Menu Item [Unknown]</div>
					<div class="bellows-menu-item-id">#menu-item-X</div>
					<div class="bellows-menu-item-type">Custom</div>		
				</div>
				<ul class="bellows-menu-item-tabs">
					<?php foreach( $panels as $panel_id => $panel ): ?>
					<li class="bellows-menu-item-tab" ><a href="#" data-bellows-tab="<?php echo $panel_id; ?>" ><?php echo $panel['title']; ?></a></li>
					<?php endforeach; ?>

					<?php /*
					<li class="bellows-menu-item-tab" data-bellows-tab="general" ><a href="#">General</a></li>
					<li class="bellows-menu-item-tab" data-bellows-tab="submenu" ><a href="#">Submenu</a></li>
					<li class="bellows-menu-item-tab" data-bellows-tab="customize" ><a href="#">Customize</a></li>
					*/ ?>
				</ul>

			</div>

			<div class="bellows-menu-item-panel-settings shift-clearfix" >
				<form class="bellows-menu-item-settings-form" action="" method="post" enctype="multipart/form-data" >

					<?php foreach( $panels as $panel_id => $panel ): 
							$panel_settings = $settings[$panel_id]; ?>

						<div class="bellows-menu-item-tab-content" data-bellows-tab-content="<?php echo $panel_id; ?>">

							<?php foreach( $panel_settings as $setting_id => $setting ): ?>

								<div class="bellows-menu-item-setting bellows-menu-item-setting-<?php echo $setting['type']; ?>">
									<label class="bellows-menu-item-setting-label"><?php echo $setting['title']; ?></label>
									<div class="bellows-menu-item-setting-input-wrap">
										<?php bellows_show_menu_item_setting( $setting ); ?>
									</div>
								</div>

							<?php endforeach; ?>

						</div>
					

					<?php endforeach; ?>

					<div class="bellows-menu-item-save-button-wrapper">

						<a class="bellows-menu-item-settings-close" href="#"><i class="fa fa-times"></i></a>

						<input class="bellows-menu-item-save-button" type="submit" value="Save Menu Item" />
						<div class="bellows-menu-item-status bellows-menu-item-status-save">
							<i class="bellows-status-save fa fa-floppy-o"></i>
							<i class="bellows-status-success fa fa-check"></i>
							<i class="bellows-status-working fa fa-cog" title="Working..."></i> 
							<i class="bellows-status-warning fa fa-exclamation-triangle"></i>
							<i class="bellows-status-error fa fa-exclamation-circle"></i>

							<span class="bellows-status-message"></span>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'admin_footer-nav-menus.php' , 'bellows_menu_item_settings_panel');


function bellows_show_menu_item_setting( $setting ){

	if( isset( $setting['pro_only'] ) && $setting['pro_only'] ){
		//echo '<a class="bellows-upgrade-link" target="_blank" href="http://goo.gl/9UuoWS">Upgrade to Bellows Pro</a> to use this feature.';
		return;
	}


	$id = $setting['id'];
	$type = $setting['type'];
	$default = $setting['default'];
	$desc = '<span class="bellows-menu-item-setting-description">'.$setting['desc'].'</span>';
	
	$name = 'name="'.$id.'"';
	$value = 'value="'.$default.'"';
	$data_setting = 'data-bellows-setting="'.$id.'"';

	$class = 'class="bellows-menu-item-setting-input"';

	$ops;
	if( isset( $setting['ops'] ) ){
		$ops = $setting['ops'];
		if( !is_array( $ops ) && function_exists( $op ) ){
			$ops = $ops();
		}
	}

	switch( $type ){
		case 'checkbox': ?>
			<input <?php echo $class; ?> type="checkbox" <?php echo "$name $data_setting"; checked( $default , 'on' ); ?> />
			<?php break;

		case 'text': ?>
			<input <?php echo $class; ?> type="text" <?php echo "$name $value $data_setting"; ?> />
			<?php break;

		case 'textarea': ?>
			<textarea <?php echo $class; ?> <?php echo "$name $data_setting"; ?> ></textarea>
			<?php break;

		case 'select': ?>
			<select <?php echo $class; ?> <?php echo "$name $data_setting"; ?> >
				<?php foreach( $ops as $_val => $_name ): ?>
				<option value="<?php echo $_val; ?>" <?php selected( $default , $_val ); ?> ><?php echo $_name; ?></option>
				<?php endforeach; ?>
			</select>
			<?php break;

		case 'icon': ?>
			<div class="bellows-icon-settings-wrap">
				<div class="bellows-icon-selected">
					<i class="<?php echo $default; ?>"></i>
					<span class="bellows-icon-set-icon">Set Icon</span>
				</div>
				<div class="bellows-icons shift-clearfix">
					<div class="bellows-icons-search-wrap">
						<input class="bellows-icons-search" placeholder="Type to search" />
					</div>

				<?php foreach( $ops as $_val => $data ): if( $_val == '' ) continue; ?>
					<span class="bellows-icon-wrap" title="<?php echo $data['title']; ?>" data-bellows-search-terms="<?php echo strtolower( $data['title'] ); ?>"><i class="bellows-icon <?php echo $_val; ?>" data-bellows-icon="<?php echo $_val; ?>" ></i></span>
				<?php endforeach; ?>
					<span class="bellows-icon-wrap bellows-remove-icon" title="Remove Icon"><i class="bellows-icon" data-bellows-icon="" >Remove Icon</i></span>
				</div>
				<select <?php echo $class; ?> <?php echo "$name $data_setting"; ?> >
					<?php foreach( $ops as $_val => $data ): ?>
					<option value="<?php echo $_val; ?>" <?php selected( $default , $_val ); ?> ><?php echo $data['title']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php break;

		case 'color': ?>
			<input type="text" class="bellows-colorpicker" <?php echo $name . ' '. $value . ' ' . $data_setting; ?> />
			
			<?php break;

		case 'radio': ?>
			<div class="bellows-radio-group <?php if( isset($setting['type_class'] ) ) echo $setting['type_class']; ?> <?php if( count( $ops ) > 1 ) echo 'bellows-radio-multiple-subgroups'; ?>">

				<?php foreach( $ops as $_group_id => $group ): ?>

					<div class="bellows-radio-subgroup bellows-radio-subgroup-<?php echo $_group_id; ?>">

						<?php if( isset( $group['group_title'] ) ): ?>
							<h4 class="bellows-radio-group-title"><?php echo $group['group_title']; ?></h4>
						<?php endif; ?>

						<?php foreach( $group as $_val => $_data ):
						
							if( $_val == 'group_title' ) continue;

							$_name = isset( $_data['name'] ) ? $_data['name'] : $_val;
							$selected = false;
							if( $default == $_val ) $selected = true;

							$img = '';
							if( isset( $_data['img'] ) ) $img = $_data['img'];

							$img_icon = '';
							if( isset( $_data['img_icon'] ) ) $img_icon = $_data['img_icon'];
							?>
						<div class="bellows-radio-option">
							<label class="bellows-radio-label shift-clearfix<?php if( $img || $img_icon ) echo ' bellows-radio-label-with-image'; ?><?php if( $selected ) echo ' bellows-radio-label-selected'; ?>">
								<input <?php echo $class; ?> type="radio" value="<?php echo $_val; ?>" <?php echo "$name $data_setting"; ?> <?php checked( $default , $_val ); ?>>
								
								<?php if( $img ): ?>
									<img src="<?php echo $img; ?>" />
								<?php endif; ?>

								<?php if( $img_icon ): ?>
									<span class="bellows-radio-img-icon">
										<i class="<?php echo $img_icon; ?>"></i>
									</span>
								<?php endif; ?>

								<?php echo $_name; ?>

								<?php if( isset( $_data['desc'] ) ): ?>
									<div class="bellows-radio-desc">
										<?php echo $_data['desc']; ?>
									</div>
								<?php endif; ?>
							</label>
							
							
						</div>
						<?php endforeach; ?>
					</div>

				<?php endforeach; ?>

			</div>

			<?php break;

		case 'media': ?>
			<div class="bellows-media-wrapper">
				<div class="media-preview-wrap"></div>
				<input class="bellows-menu-item-setting-input bellows-media-id" type="text" <?php echo "$name $value $data_setting"; ?> />

				<div class="bellows-media-buttons">
					<a class="bellows-setting-button" data-uploader-title="Upload or Choose from Media Library" ><i class="fa fa-picture-o"></i> Select</a>
					<a class="bellows-remove-button">&times; Remove</a>
					<a class="bellows-edit-media-button" target="_blank"><i class="fa fa-pencil"></i> Edit</a>
				</div>
			</div>
			<?php break;

		default: ?>
			What's a "<?php echo $type; ?>"?
			<?php
	}

	echo $desc;

}



function bellows_menu_item_settings_panels(){
	$panels = array();
	$panels['general'] = array(
		'title'	=> 'General',
	);
	$panels['icon'] = array(
		'title'	=> 'Icon',
	);
	$panels['image'] = array(
		'title'	=> 'Image',
	);
	$panels['custom_content'] = array(
		'title'	=> 'Custom Content',
	);
	$panels['widgets'] = array(
		'title'	=> 'Widgets',
	);
	$panels['custom_style'] = array(
		'title'	=> 'Customize Style',
	);

	return $panels;
}


function bellows_menu_item_setting_defaults(){
	$defaults = array();
	$settings = bellows_menu_item_settings();
	foreach( $settings as $panel => $panel_settings ){
		foreach( $panel_settings as $setting ){
			$defaults[$setting['id']] = $setting['default'];
		}
	}
	return $defaults;
}


function bellows_get_menu_items_data( $menu_id = -1 ){

	if( $menu_id == -1 ){
		global $nav_menu_selected_id;
		$menu_id = $nav_menu_selected_id;
	}

	if( $menu_id == 0 ) return array();

	$bellows_menu_data = array();
	$menu_items = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );

	foreach( $menu_items as $item ){
		$_item_settings = bellows_get_menu_item_data( $item->ID );
		if( $_item_settings != '' ){
			$bellows_menu_data[$item->ID] = $_item_settings;
		}
	}
	//shiftp( $bellows_menu_data );

	return $bellows_menu_data;
}




function bellows_menu_item_settings_nonce(){
	return wp_create_nonce( 'bellows-menu-item-settings' );
}


/* AJAX */

add_action( 'wp_ajax_bellows_save_menu_item', 'bellows_save_menu_item_callback' );

function bellows_save_menu_item_callback() {
	global $wpdb; // this is how you get access to the database

	//CHECK NONCE
	check_ajax_referer( 'bellows-menu-item-settings' , 'bellows_nonce' );

	$menu_item_id = $_POST['menu_item_id'];
	$menu_item_id = substr( $menu_item_id , 10 );

	$serialized_settings = $_POST['settings'];
	$dirty_settings = array();
	parse_str( $serialized_settings, $dirty_settings );


	//CHECKBOXES
	//Since unchecked checkboxes won't be submitted, detect them and set the 'off' value
	$_defined_settings = bellows_menu_item_settings();
	foreach( $_defined_settings as $panel => $panel_settings ){
		foreach( $panel_settings as $_priority => $_setting ){
			if( $_setting['type'] == 'checkbox' ){
				$_id = $_setting['id'];
				if( !isset( $dirty_settings[$_id] ) ){
					$dirty_settings[$_id] = 'off';
				}
			}
		}
	}
	
	//ONLY ALLOW SETTINGS WE'VE DEFINED	
	$settings = wp_parse_args( $dirty_settings, bellows_menu_item_setting_defaults() );
	
	//SAVE THE SETTINGS
	update_post_meta( $menu_item_id, BELLOWS_MENU_ITEM_META_KEY , $settings );

	//RUN CALLBACKS
	//Reset styles for this menu item here
	//bellows_reset_item_styles( $menu_item_id );

	foreach( $_defined_settings as $panel => $panel_settings ){
		foreach( $panel_settings as $_priority => $_setting ){
			if( isset( $_setting['on_save'] ) ){
				$callback = 'bellows_item_save_'.$_setting['on_save'];
				if( function_exists( $callback ) ){
					$callback( $menu_item_id , $_setting , $settings[$_setting['id']] , $settings );
				}
			}
		}
	}


	do_action( 'bellows_after_menu_item_save' , $menu_item_id );

	$response = array();

	$response['settings'] = $settings;
	$response['menu_item_id'] = $menu_item_id;

	//send back nonce
	$response['nonce'] = bellows_menu_item_settings_nonce();

	//print_r( $response );
	echo json_encode( $response );

	//echo $data;

	die(); // this is required to return a proper result
}