<?php

function bellows_generator_ui(){

	$preview_nonce = wp_create_nonce( 'bellows-preview' );;

	?>
	<div class="bellows-generator-ui">

		<p class="bellows-generator-tip"><i class="fa fa-question-circle"></i> <span class="bellows-generator-tip-pop">Use this tool to generate shortcodes or PHP code to add an accordion menu to your site.</span></p>

		<h3>Select Accordion Content Source</h3>
<?php //global $current_screen; echo $current_screen->id; bellp( get_current_screen() ); ?>
		<div class="bellows-generator-content-source bellows-generator-main-selector">
			<label>
				<input type="radio" name="bellows_gen_source" value="menu" />
				<span class="bellows-content-source-label-inner">
					<span class="bellows-content-source-label-title">WordPress Menu</span>
					<span class="bellows-content-source-label-description">The content of your menu will be controlled by adding items to a menu that you manage in Appearance &gt; Menus</span>
				</span>
			</label>
			<label>
				<input type="radio" name="bellows_gen_source" value="auto"/>
				<span class="bellows-content-source-label-inner">
					<span class="bellows-content-source-label-title">Auto Population</span>
					<span class="bellows-content-source-label-description">The content of your menu will be automatically generated based on your sites's post or taxonomy term hierarchy.</span>
				</span>
			</label>
		</div>



		<!-- SOURCE: MENU -->
		<div class="bellows-generator-tbr bellows-generator-tbr-menu">
			<div class="bellows-generator-podium">
				<div class="bellows-generator-code-toggles">
					<span class="bellows-generator-code-toggle bellows-generator-code-toggle-selected bellows-generator-code-toggle-shortcode" data-code-type="shortcode">Shortcode</span>
					<span class="bellows-generator-code-toggle bellows-generator-code-toggle-php" data-code-type="php">PHP</span>
				</div>
				<code class="bellows-generator-code-shortcode">[bellows config_id="main"]</code>
				<code class="bellows-generator-code-php"><?php echo esc_html( '<?php bellows(); ?>' ); ?></code>
			</div>

			<div class="bellows-generator-fields">
				<?php
					$menus_fields = bellows_generator_menus_fields();
					bellows_ui_generator_show_fields( $menus_fields , 'menu_' );
				?>
			</div>
			<div class="bellows-generator-preview-container">
				<h3>Preview</h3>
				<div class="bellows-generator-preview" data-nonce="<?php echo $preview_nonce; ?>">

				</div>
			</div>
		</div>





		<!-- SOURCE: AUTOMATIC -->
		<div class="bellows-generator-tbr bellows-generator-tbr-auto">


			<h3>Select Auto Population Content Type</h3>

			<div class="bellows-generator-auto-type bellows-generator-main-selector">
				<label>
					<input type="radio" name="bellows_gen_auto_type" value="posts" />
					<span class="bellows-content-source-label-inner">
						<span class="bellows-content-source-label-title">Posts</span>
						<span class="bellows-content-source-label-description">A menu of post items</span>
					</span>
				</label>
				<label>
					<input type="radio" name="bellows_gen_auto_type" value="terms"/>
					<span class="bellows-content-source-label-inner">
						<span class="bellows-content-source-label-title">Terms</span>
						<span class="bellows-content-source-label-description">A menu of taxonomy terms</span>
					</span>
				</label>
			</div>


			<!-- AUTOMATIC TERMS -->
			<div class="bellows-generator-tbr bellows-generator-tbr-terms">
				<div class="bellows-generator-podium">
					<div class="bellows-generator-code-toggles">
						<span class="bellows-generator-code-toggle bellows-generator-code-toggle-selected bellows-generator-code-toggle-shortcode" data-code-type="shortcode">Shortcode</span>
						<span class="bellows-generator-code-toggle bellows-generator-code-toggle-php" data-code-type="php">PHP</span>
					</div>
					<code class="bellows-generator-code-shortcode">[bellows_terms]</code>
					<code class="bellows-generator-code-php"><?php echo esc_html( "<?php bellows_terms(); ?>" ); ?></code>
				</div>

				<div class="bellows-generator-fields">
				<?php
					$terms_fields = bellows_generator_terms_fields();

					bellows_ui_generator_show_fields( $terms_fields , 'terms_');

				?>
				</div>

				<div class="bellows-generator-preview-container">

				<?php bellows_ui_generator_show_save_query_ui( 'term' ); ?>

					<h3>Preview</h3>
					<div class="bellows-generator-preview" data-nonce="<?php echo $preview_nonce; ?>">

					</div>
				</div>

			</div>




			<!-- AUTOMATIC POSTS -->
			<div class="bellows-generator-tbr bellows-generator-tbr-posts">
				<div class="bellows-generator-podium">
					<div class="bellows-generator-code-toggles">
						<span class="bellows-generator-code-toggle bellows-generator-code-toggle-selected bellows-generator-code-toggle-shortcode" data-code-type="shortcode">Shortcode</span>
						<span class="bellows-generator-code-toggle bellows-generator-code-toggle-php" data-code-type="php">PHP</span>
					</div>
					<code class="bellows-generator-code-shortcode">[bellows_posts]</code>
					<code class="bellows-generator-code-php"><?php echo esc_html( "<?php bellows_posts(); ?>" ); ?></code>
				</div>

				<div class="bellows-generator-fields">
				<?php
					$posts_fields = bellows_generator_posts_fields();

					bellows_ui_generator_show_fields( $posts_fields , 'posts_');

				?>
				</div>

				<div class="bellows-generator-preview-container">

					<?php bellows_ui_generator_show_save_query_ui( 'post' ); ?>
				

					<h3>Preview</h3>
					<div class="bellows-generator-preview" data-nonce="<?php echo $preview_nonce; ?>">

					</div>
				</div>

			</div>




		</div>
	</div>
	<?php
}

function bellows_ui_generator_show_save_query_ui( $query_type ){

	?>
		<div class="bellows-generator-save bellows-generator-save-<?php echo $query_type; ?>s">
			<input type="text" placeholder="Enter query name" class="bellows-save-query-title"/>
			<div class="bellows-save-query-toggle"><i class="fa fa-chevron-down"></i></div>

			<button class="bellows-save-button" 
				data-nonce="<?php echo wp_create_nonce( 'bellows-query-save-nonce' ); ?>" 
				data-query-type="<?php echo $query_type; ?>" 
				data-query-id="-1" 
				>Save Query</button>

			<div class="bellows-saved-queries">

				<div class="bellows-saved-queries-list">
					<?php bellows_saved_query_list( $query_type , true ); ?>
				</div>

				<div class="bellows-new-query">
					<a><i class="fa fa-plus-square"></i> Create new query</a>
				</div>
			</div>

			<div class="bellows-save-query-status-wrapper">

			</div>
		</div>

	<?php
}

function bellows_ui_generator_show_fields( $fields , $prefix = '' ){
	foreach( $fields as $field ){
		bellows_generator_ui_field( $field , $prefix );
	}
}


function bellows_generator_ui_field( $data , $prefix ){

	
	
	$arg_att = 'data-arg="'.$data['name'].'"';
	$name = 'name="'.$prefix.$data['name'].'" '.$arg_att;
	$data_default = isset( $data['default'] ) ? 'data-default="'.$data['default'].'"' : '';
	$data_val_selector = '[name='.$prefix.$data['name'].']'; //$data['type'].
	switch( $data['type'] ){
		case 'multicheck':
		case 'radio':
			$data_val_selector.= ':checked';
			break;
	}

	?>
	<div class="bellows-generator-field bellows-generator-field-<?php echo $data['name']; ?>" data-<?php echo $name; ?> <?php echo $data_default; ?> data-val-selector="<?php echo $data_val_selector; ?>" data-type="<?php echo $data['type']; ?>">
		<h5><?php echo $data['title']; ?></h5>
		<div class="bellows-generator-field-inner">
		<?php
			switch( $data['type'] ){
				case 'multicheck':
					foreach( $data['options'] as $val => $label ){
						?>
						<div class="bellows-generator-field-op bellows-generator-field-<?php echo $data['name']; ?>">
							<label><input <?php echo $name; ?> type="checkbox" value="<?php echo $val; ?>"> <?php echo $label; ?></label>
						</div>
						<?php
					}
					break;
				
				case 'radio':
					foreach( $data['options'] as $val => $label ){
						?>
						<div class="bellows-generator-field-op bellows-generator-field-op-<?php echo $data['name']; ?>">
							<label><input <?php echo $name; ?> type="radio" value="<?php echo $val; ?>" <?php checked( $val , $data['default'] ); ?> > <?php echo $label; ?></label>
						</div>
						<?php
					}
					break;

				case 'checkbox':
					?>
					<input type="checkbox" <?php echo $name; ?> <?php if( isset( $data['default'] ) && $data['default'] == 'on' ) echo 'checked="checked"'; ?>/>
					<?php
					break;

				case 'text':
					?>
					<input type="text" <?php echo $name; ?> <?php if( isset( $data['default'] ) ) echo 'value="'.$data['default'].'"'; ?>/>
					<?php
					break;

				case 'post_author':
					?>
					<input type="text" <?php echo $name; ?> placeholder="Author ID" />
					<a href="#" class="bellows-generator-post-author-load" data-nonce="<?php echo wp_create_nonce( 'bellows-post-author-list' ); ?>">Load Authors</a>
					<?php
					break;

				case 'post_parent':
					?>
					<label data-nonce="<?php echo wp_create_nonce( 'bellows-post-list' ); ?>">
						<input type="text" <?php echo $name; ?> placeholder="Post ID" />
						<span></span>
					</label>
					<?php //wp_dropdown_pages( array( 'post_type' => 'page' ) ); ?>
					<?php
					break;
				case 'post_terms':
					?>
					<input type="text" <?php echo $name; ?> placeholder="Term ID" />
					<a href="#" class="bellows-generator-post-terms-load" data-tax-id="<?php echo $data['tax_id']; ?>" data-nonce="<?php echo wp_create_nonce( 'bellows-post-terms-list' ); ?>">Load <?php echo $data['title']; ?></a>
					<?php
					break;

				case 'parent_terms':
					?>
					<input type="text" <?php echo $name; ?> placeholder="Term ID" data-nonce="<?php echo wp_create_nonce( 'bellows-parent-terms' ); ?>" />

					<?php
					break;
			}
		
		?>
		</div>

		<?php if( isset( $data['desc'] ) ): ?>
			<span class="bellows-generator-field-description">
			<?php echo $data['desc']; ?>
			</span>
		<?php endif; ?>

		<?php if( isset( $data['required'] ) && $data['required'] == true ): ?>
			<span class="bellows-generator-field-required">
			Required
			</span>
		<?php endif; ?>

		<span class="bellows-generator-field-toggle" title="Expand field"><i class="fa fa-angle-down"></i></span>



	</div>
	<?php
}

function bellows_generator_get_config_ops(){
	$config_ops = array( 'main' => 'Main Configuration' );
	$configs = bellows_get_menu_configurations();
	foreach( $configs as $config_id ){
		$config_ops[$config_id] = '+'.$config_id;
	}
	return $config_ops;
}


function bellows_generator_menus_fields(){
	$menu_ops = array();
	$menus = wp_get_nav_menus( array('orderby' => 'name') );
	foreach( $menus as $menu ){
		$menu_ops[$menu->term_id] = $menu->name;
	}

	// $config_ops = array( 'main' => 'Main Configuration' );
	// $configs = bellows_get_menu_configurations();
	// foreach( $configs as $config_id ){
	// 	$config_ops[$config_id] = '+'.$config_id;
	// }

	$config_ops = bellows_generator_get_config_ops();

	$menus_fields = array(
		
		array(
			'name'		=> 'config_id',
			'title'		=> __( 'Configuration' , 'bellows' ),
			'type'		=> 'radio',
			'options'	=> $config_ops,
			'default'	=> 'main',
			'desc'		=> __( 'The Bellows Settings Configuration to use to display this menu' , 'bellows' ),
			'required'	=> true,
		),
		array(
			'name'		=> 'menu',
			'title'		=> __( 'Menu' , 'bellows' ),
			'type'		=> 'radio',
			'options'	=> $menu_ops,
			'default'	=> '',
			'desc'		=> __( 'The menu to display' , 'bellows' ),
			'required'	=> true,
		),

	);
	
	return $menus_fields;
}

function bellows_generator_get_taxonomy_ops(){
	$taxonomies = get_taxonomies(
			array(
				'public'	=> true,
			),
			'objects'
		);
	$t = array();
	foreach( $taxonomies as $tax_id => $tax ){
		$t[$tax_id] = $tax->label;
	}
	return $t;

}


function bellows_generator_get_post_type_ops(){
	$post_types = get_post_types( array(
		'public'		=> true,
	), 'objects' );
//bellp( $post_types );
	$ops = array();
	foreach( $post_types as $slug => $post_type ){
		$ops[$slug] = $post_type->label;
	}
	return $ops;

}


function bellows_generate_preview_callback(){
	check_ajax_referer( 'bellows-preview' , 'bellows_nonce' );


	$response = array();

	$response['nonce'] = wp_create_nonce( 'bellows-preview' );

	$dirty_args = $_POST['args'];
	//$response['d'] = $_POST; //$dirty_args;
	$defaults = array();

	$menu = 'No Preview Available';

	switch( $_POST['source'] ){

		case 'terms':
//$response['raw_args'] = $dirty_args;
			$fields = bellows_generator_terms_fields();
			foreach( $fields as $field ){
				$defaults[$field['name']] = $field['default'];
			}
			$defaults['number'] = 100;	//Sanity check
			$args = wp_parse_args( $dirty_args , $defaults );

			// foreach( $fields as $field ){
			// 	if( $field['type'] == 'checkbox' ){
			// 		switch( $args[$field['name']] ){
			// 			case 'on':
			// 				$args[$field['name']] = true;
			// 				break;
			// 			//case 'off':
			// 				//$args[$field['name']] = false;
			// 				break;
			// 			default:
			// 				//$args[$field['name']] = $field['default'] == 'on' ? true : false;
			// 				$args[$field['name']] = false;
			// 				break;
			// 		}
			// 	}
			// }


			$config_id = isset( $args['config_id'] ) ? $args['config_id'] : 'main';
			unset( $args['config_id'] );
			
			$taxonomies = explode( ',' , $args['taxonomy'] );	//taxonomy
			//$args['taxonomies'] = $taxonomies;
			//unset( $args['taxonomy'] );
			$args['taxonomy'] = $taxonomies;

			//Convert any true/false strings to booleans
			$bools = array( 'hide_empty' , 'hierarchical' , 'counts' );
			foreach( $args as $key => $val ){
				if( in_array( $key , $bools ) ){
			 		if( $val === 'true' ) $val = $args[$key] = true;
			 		else if( $val === 'false' ) $args[$key] = false;
				}
			}

			$response['query_args'] = $args;
			$menu = bellows_terms( $config_id , $args , array( 'echo' => false ) );
			break;

		case 'posts':
			$fields = bellows_generator_posts_fields();
			foreach( $fields as $field ){
				$defaults[$field['name']] = $field['default'];
			}
			$defaults['numberposts'] = 100;	//Sanity check
			$args = wp_parse_args( $dirty_args , $defaults );

			$post_type = explode( ',' , $args['post_type'] );
			$args['post_type'] = $post_type;
			
			$config_id = isset( $args['config_id'] ) ? $args['config_id'] : 'main';
			unset( $args['config_id'] );
			
			$menu = bellows_posts( $config_id , $args , array( 'echo' => false ) );
			
			unset( $args['depth'] );  //because this is not the real menu "depth", which is not a get_posts argument
			$response['query_args'] = $args;

			break;

		case 'menu':

			$fields = bellows_generator_menus_fields();
			foreach( $fields as $field ){
				$defaults[$field['name']] = $field['default'];
			}
			$args = wp_parse_args( $dirty_args , $defaults );
			$args['echo'] = false;

			//Configuration ID
			$config_id = isset( $args['config_id'] ) ? $args['config_id'] : 'main';
			unset( $args['config_id'] );

			$response['dirty'] = $dirty_args;
			$response['query_args'] = $args;

			$menu = bellows( $config_id , $args );


			break;

	}

	$response['menu'] = $menu;

	//echo '-1'; die(); //test nonce expiration
	echo json_encode( $response );

	die();

}
add_action( 'wp_ajax_bellows_generate_preview', 'bellows_generate_preview_callback' );




function bellows_generator_post_author_list_callback(){
	check_ajax_referer( 'bellows-post-author-list' , 'bellows_nonce' );

	if( !current_user_can( BELLOWS_ADMIN_CAP ) ) die();

	$authors = get_users( array(
		'role__in'	=> array( 'author' , 'editor' , 'administrator' ),
	));

	$ops = '<div class="bellows-generator-ops-checklist">';
	foreach( $authors as $author ){
		$ops.= '<label title="ID: '. $author->ID .'"><input type="checkbox" value="'.$author->ID.'" />'.$author->display_name.'</label>';
	}
	$ops.= '</div>';

	$response = array();
	$response['nonce'] = wp_create_nonce( 'bellows-post-author-list' );
	$response['ops'] = $ops;

	echo json_encode( $response );
	die();

}
add_action( 'wp_ajax_bellows_generator_post_author_list' , 'bellows_generator_post_author_list_callback' );




function bellows_generator_post_list_callback(){

	check_ajax_referer( 'bellows-post-list' , 'bellows_nonce' );

	if( !current_user_can( BELLOWS_ADMIN_CAP ) ) die();

	$post_types = get_post_types();
	$post_type = $_POST['post_type'];
	if( !in_array( $post_type , $post_types ) ) die();

	$post_type_obj = get_post_type_object( $post_type );
	$post_type_name = $post_type_obj->labels->name;

	$response = array();
	$response['nonce'] = wp_create_nonce( 'bellows-post-list' );
//$response['pt'] = $post_type_obj;
	if( is_post_type_hierarchical( $post_type ) ){
		$response['select'] = bellows_dropdown_pages( array( 
				'post_type' => $post_type , 
				'echo' => false , 
				'show_option_none' => '-- Select a ' . $post_type .' --',
				'show_option_no_change' => '[Current '.$post_type.']',
				'pre_options'	=> '<option value="-2">'.'[Current '.$post_type.' parent]'.'</option>'.
									'<option value="-3">'.'[Current '.$post_type.' root]'.'</option>',
				) );
	}
	else{
		$response['select'] = '('. $post_type_name . ' are not hierarchical)';
	}

	echo json_encode( $response );
	die();


}
add_action( 'wp_ajax_bellows_generator_post_list', 'bellows_generator_post_list_callback' );

function bellows_dropdown_pages( $args = '' ) {
	$defaults = array(
		'depth' => 0, 'child_of' => 0,
		'selected' => 0, 'echo' => 1,
		'name' => 'page_id', 'id' => '',
		'class' => '',
		'show_option_none' => '', 'show_option_no_change' => '',
		'option_none_value' => '',
		'value_field' => 'ID',
		'pre_options' => '',
	);

	$r = wp_parse_args( $args, $defaults );

	$pages = get_pages( $r );
	$output = '';
	// Back-compat with old system where both id and name were based on $name argument
	if ( empty( $r['id'] ) ) {
		$r['id'] = $r['name'];
	}

	if ( ! empty( $pages ) ) {
		$class = '';
		if ( ! empty( $r['class'] ) ) {
			$class = " class='" . esc_attr( $r['class'] ) . "'";
		}

		$output = "<select name='" . esc_attr( $r['name'] ) . "'" . $class . " id='" . esc_attr( $r['id'] ) . "'>\n";
		if ( $r['show_option_no_change'] ) {
			$output .= "\t<option value=\"-1\">" . $r['show_option_no_change'] . "</option>\n";
		}
		if ( $r['show_option_none'] ) {
			$output .= "\t<option value=\"" . esc_attr( $r['option_none_value'] ) . '">' . $r['show_option_none'] . "</option>\n";
		}
		$output .= $r['pre_options'];
		$output .= walk_page_dropdown_tree( $pages, $r['depth'], $r );
		$output .= "</select>\n";
	}

	/**
	 * Filter the HTML output of a list of pages as a drop down.
	 *
	 * @since 2.1.0
	 * @since 4.4.0 `$r` and `$pages` added as arguments.
	 *
	 * @param string $output HTML output for drop down list of pages.
	 * @param array  $r      The parsed arguments array.
	 * @param array  $pages  List of WP_Post objects returned by `get_pages()`
 	 */
	$html = apply_filters( 'wp_dropdown_pages', $output, $r, $pages );

	if ( $r['echo'] ) {
		echo $html;
	}
	return $html;
}


function bellows_generator_post_terms_list_callback(){
	check_ajax_referer( 'bellows-post-terms-list' , 'bellows_nonce' );

	if( !current_user_can( BELLOWS_ADMIN_CAP ) ) die();

	$tax_id = $_POST['tax_id'];

	$taxonomies = get_taxonomies( array(
					'public'	=> true,
					//'_builtin'	=> true,
					) );

	if( !in_array( $tax_id , $taxonomies ) ) die( 'invalid taxonomy' );

	$terms = get_terms( $tax_id );
	$ops = '<div class="bellows-generator-ops-checklist">';
	foreach( $terms as $term ){
		$ops.= '<label title="ID: '. $term->term_id .' Count: '.$term->count.'"><input type="checkbox" value="'.$term->term_id.'" />'.$term->name.'</label>';
	}
	$ops.= '</div>';

	$response = array();
	$response['nonce'] = wp_create_nonce( 'bellows-post-terms-list' );
	$response['ops'] = $ops;
	$response['tax_id'] = $tax_id;

	echo json_encode( $response );
	die();

}
add_action( 'wp_ajax_bellows_generator_post_terms_list' , 'bellows_generator_post_terms_list_callback' );


function bellows_generator_parent_terms_callback(){
	check_ajax_referer( 'bellows-parent-terms' , 'bellows_nonce' );
	if( !current_user_can( BELLOWS_ADMIN_CAP ) ) die();

	$tax_id = $_POST['tax_id'];

	$taxonomies = get_taxonomies( array(
					'public'	=> true,
					//'_builtin'	=> true,
					) );

	if( !in_array( $tax_id , $taxonomies ) ) die( 'invalid taxonomy' );

	$response = array();
	$response['nonce'] = wp_create_nonce( 'bellows-parent-terms' );
	

	if( is_taxonomy_hierarchical( $tax_id) ){

		$terms = get_terms( $tax_id );
		$ops = '<select class="bellows-generator-ops-select">';
		$ops.= '<option value="">-- Select a '.$tax_id.' --</option>';
		foreach( $terms as $term ){
			$ops.= '<option title="ID: '. $term->term_id .' Count: '.$term->count.'" value="'.$term->term_id.'">'.$term->name.'</option>';
		}
		$ops.= '</select>';

		$response['ops'] = $ops;
	}
	else{
		$response['ops'] = '(The '.$tax_id.' taxonomy is not hierarchical)';
	}

	
	$response['tax_id'] = $tax_id;

	echo json_encode( $response );
	die();

}
add_action( 'wp_ajax_bellows_generator_parent_terms' , 'bellows_generator_parent_terms_callback' );

