<?php

class Bellows_Widget_Auto extends WP_Widget {

    protected $widget_slug = 'bellows_navigation_widget_autopop';

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			$this->get_widget_slug(),
			__( 'Bellows Accordion - Autopopulated Menu', 'bellows' ),
			array(
				'classname'  => $this->get_widget_slug().'-class',
				'description' => __( 'A Bellows Accordion Menu, based on a saved autopopulated menu.', 'bellows' )
			)
		);

		// Refreshing the widget's cached output with each new post
		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

	} // end constructor


    /**
     * Return the widget slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_widget_slug() {
        return $this->widget_slug;
    }

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array args  The array of form elements
	 * @param array instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		echo $before_widget;

		extract( wp_parse_args(
			(array) $instance , array(
				'query_id'		=> 0,
			)
		) );

		bellows_menu( $query_id );

		echo $after_widget;

	} // end widget
	
	
	public function flush_widget_cache() 
	{
    	wp_cache_delete( $this->get_widget_slug(), 'widget' );
	}
	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array new_instance The new instance of values to be generated via the update.
	 * @param array old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		// TODO: Here is where you update your widget's old values with the new, incoming values
		$instance['query_id'] = isset( $new_instance['query_id'] ) ? $new_instance['query_id'] : 0;

		return $instance;

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		extract( wp_parse_args(
			(array) $instance , array(
				'query_id'		=> 0,
			)
		) );



		$saved_queries = get_posts( array(
			'post_type'	=> 'bellows_query',
			'numberposts' => -1,
		)); 


		if( !$saved_queries || !count( $saved_queries ) ): ?>
			<p>WARNING: No saved queries available.  Please visit the Bellows Control Panel &gt; Code Generator to create a saved autopopulation query to use here</p>

		<?php else: ?>
		<p>
			<strong><label for="<?php echo $this->get_field_id('query_id'); ?>"><?php _e( 'Select Saved Autpopulation Query:' , 'bellows' ); ?></label></strong>
			<select id="<?php echo $this->get_field_id('query_id'); ?>" name="<?php echo $this->get_field_name('query_id'); ?>">
		<?php
			foreach ( $saved_queries as $query ) {
				$query_type = get_post_meta( $query->ID , 'query_type' , true );
				echo '<option value="' . $query->ID . '"'
					. selected( $query_id, $query->ID, false )
					. '>'. $query->post_title . ' ['.$query_type. ' :: ' .$query->ID.']' . '</option>';
			}
		?>
			</select>
		</p>
		
		<?php endif; 
		

	} // end form

} // end class



function bellows_register_autopop_widget(){
	register_widget( 'Bellows_Widget_Auto' );
}
add_action( 'widgets_init', 'bellows_register_autopop_widget' );