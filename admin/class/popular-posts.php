<?php

//Including the file for the required hooks

require_once 'hook.php';
class Wli_Popular_Posts extends WP_Widget {
	
/**
	 * 
	 * Unique identifier for your widget.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * widget file.
	 *
	 * @since    1.0.1
	 *
	 * @var      string
	 */
	protected $widget_slug = 'wli_popular_posts';

/**
	 *   popular_posts() is constructor
	 *
	 *  @since    1.0.1
	 *
	 *  @return             void
	 *  @var                No arguments passed
	 *  @author             weblineindia
	 *
	 */
	public function __construct()
	{
		
		// widget settings		
		parent::__construct(
				$this->get_widget_slug(),
				__( 'Popular Posts by Webline', $this->get_widget_slug() ),
				array(
						'classname'     =>  $this->get_widget_slug().'-class',
						'description'   => __('A Simple plugin to show the posts as per the filter applied.',$this->get_widget_slug()),
				)
		);
	
	}

	/**
	 * get_widget_slug() is use to get the widget slug.
	 *
	 * @since    1.0.1
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_widget_slug() {
		return $this->widget_slug;
	}

	/**
	 *  form() is used to generates the administration form for the widget.
	 *
	 *  @since    1.0.1
	 *
	 *  @return             void
	 *  @var                $instance
	 *  @author             weblineindia
	 *
	 */

	function form( $instance ) {
		
		// Check values
		$defaults = array(
				'category' => array(),
				'title'	   => 'Popular Posts',
				'no_posts' => '3',
				'days_filter'=>'None',
				'featured_image'=>'no',
                                'content'=>'no',
			
		);
		$instance		= wp_parse_args( (array) $instance, $defaults );
		$title			= esc_attr( $instance['title'] );
		$no_posts		= esc_attr( $instance['no_posts'] );
		$days_filter	= $instance['days_filter'];
		$category		= $instance['category'];
		$featured_image = $instance['featured_image'];
		$content		= $instance['content'];
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', $this->get_widget_slug()); ?>
			</label> <input class="widefat"
				id="<?php echo $this->get_field_id('title'); ?>"
				name="<?php echo $this->get_field_name('title'); ?>" type="text"
				value="<?php echo $title;?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('no_posts'); ?>"><?php _e('No. of Posts to Show :', $this->get_widget_slug()); ?>
			</label> <input class="widefat" maxlength="4"
				id="<?php echo $this->get_field_id('no_posts'); ?>"
				name="<?php echo $this->get_field_name('no_posts'); ?>" type="text"
				value="<?php echo $no_posts; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('days_filter'); ?>"><?php _e('Show Post Before (Days):', $this->get_widget_slug()); ?>
			</label> <select id="<?php echo $this->get_field_id('days_filter'); ?>"
				name="<?php echo $this->get_field_name('days_filter'); ?>"
				class="widefat">
				<?php $filterby=array( 'None', '7', '15', '30', '45' );?>
				<?php foreach($filterby as $post_type) { ?>
				<option <?php selected( $instance['days_filter'], $post_type ); ?>
					value="<?php echo $post_type; ?>">
					<?php echo $post_type; ?>
				</option>
				<?php } ?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('featured_image'); ?>"><?php _e('Show Featured Image', $this->get_widget_slug()); ?>
			</label> <br>
			
				<input type="radio" name="<?php echo $this->get_field_name('featured_image'); ?>" value="yes" class="widefat" <?php echo checked( 'yes', $instance['featured_image'], true ); ?>>Yes &nbsp;			
			<input type="radio" name="<?php echo $this->get_field_name('featured_image'); ?>" value="no" class="widefat" <?php echo checked( 'no', $instance['featured_image'], true ); ?>>No
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Show Excerpt', $this->get_widget_slug()); ?>
			</label> <br>
				<input type="radio" name="<?php echo $this->get_field_name('content'); ?>" value="yes" class="widefat" <?php echo checked( 'yes', $instance['content'], true ); ?>>Yes
			
			<input type="radio" name="<?php echo $this->get_field_name('content'); ?>" value="no" class="widefat" <?php echo checked( 'no', $instance['content'], true ); ?> >No
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select Category:', $this->get_widget_slug()); ?>
			</label>
			<?php
				// instantiate the walker passing name and id as arguments to constructor
				$walker = new Walker_Category_Checklist_Widget(
						$this->get_field_name('category'), $this->get_field_id('category')
				);
				echo '<ul class="categorychecklist">';
				wp_category_checklist( 0, 0, $instance['category'], FALSE, $walker, FALSE);
				echo '</ul>';
			?>
		</p>
<?php
	}

	/**
	 *  update() is used to replace the new value when the Saved button is clicked.
	 *
	 *  @since    1.0.1
	 *
	 *  @return             $instance
	 *  @var                $new_instance,$old_instance
	 *  @author             weblineindia
	 *
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		// Fields
		$instance['title']			= sanitize_text_field( $new_instance['title'] );
		$instance['no_posts'] 		= sanitize_text_field( $new_instance['no_posts'] );
		$instance['days_filter']	= $new_instance['days_filter'];
		$instance['category']		= isset($new_instance['category'])?$new_instance['category'] :array();
		$instance['featured_image'] 	= $new_instance['featured_image'];
		$instance['content']		= $new_instance['content'];
		return $instance;
	}
	
	/**
	 * widget() is used to show the frontend part .
	 *
	 *  @since    1.0.1
	 *
	 *  @return             void
	 *  @var                $args,$instance
	 *  @author             weblineindia
	 *
	 */
	function widget($args, $instance) {
	
		extract( $args,EXTR_SKIP);
	
		// these are the widget options
		$title		 	= apply_filters( 'widget_title', $instance['title'] );
		$no_posts	 	= $instance['no_posts'];
		$days_filter 	= $instance['days_filter'];
		$category	 	= $instance['category'];
		$featured_image = $instance['featured_image'];
		$content		= $instance['content'];
		echo $before_widget;
	
		// Display the widget
		echo $before_title . $title . $after_title;
	
	
		/**
		 * Following conditions will filter according to the days selected from the dropdown
		 */
		$args = array(
				'posts_per_page'=>	$no_posts,
				'orderby'		=>	'comment_count',
				'category__in' => $category,
				'date_query' => array(
						array(
								'column' => 'post_date_gmt',
								'after'  => $days_filter.'days ago',
						)
				)
		);
	
		$the_query = new WP_Query( $args );
			
		// The Loop
		if ( $the_query->have_posts() ) {
			echo '<ul>';
			while ( $the_query->have_posts() )
			{
				$the_query->the_post();
					
				?>
					<li><a href="<?php the_permalink(); ?>"
						title="<?php printf(esc_attr('Permalink to %s'), the_title_attribute('echo=0')); ?>">
							<?php 
								the_title(); 
								if($featured_image =='yes')
								{
									if ( has_post_thumbnail() ) 
									{ // check if the post has a Post Thumbnail assigned to it.
										the_post_thumbnail( 'medium' );
									}
								}
								if($content == 'yes')
								{
									the_excerpt();
								}
							?>
					</a>
					</li>
		  <?php 
				}
				echo '</ul>';
			}
				
			/* Restore original Post Data */
			wp_reset_postdata();
				
			echo $after_widget;
		}
}
add_action( 'widgets_init', create_function('', 'return register_widget("Wli_Popular_Posts" );'));


