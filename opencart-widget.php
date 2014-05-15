<?php

class opencart_widget extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'opencart_widget', // Base ID
			'OpenCart Client Widget', // Name
			array( 'description' => __( 'Opencart Widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$showtype = $instance['showtype'];
		echo $before_widget;
		if($title) echo $before_title . $title . $after_title;  
		echo $this->opencart_widget_content($showtype);
		echo $after_widget;
	}
        
	private function opencart_widget_content($showtype){
		
		  $OAC = new OCApiClient();
		  
		  $options = get_option('occp_settings');
		  
		  $OAC->app_id = $options['app_id'];
		  $OAC->app_key = $options['app_key'];
		  $OAC->pub_key = $options['pub_key'];
		  $OAC->app_url= $options['app_url'].'/?route=feed/oc_api/';
		 
		  if($showtype == 'latestproducts'){
			  $content = $OAC->getLatestProducts();
			  if(!$content){
				  echo $OAC->showError();
			  }else{
				  $params = array(
					'container_open' => '<div>',
					'container_close' =>  '</div>',
					'ulclass' => 'products',
					'showimages' => 1,
					'imagewidth' => '100px',
					'imageheight' => '',
					);
				  $OAC->showProductsData($content,$params);
			  }
		  }#if latest products
		  
		  if($showtype == 'categories'){
			  $content = $OAC->getCategories();
			  if(!$content){
				  echo $OAC->showError();
			  }else{
				  $params = array(
					'container_open' => '<div>',
					'container_close' =>  '</div>',
					'ulclass' => 'categories',
					'showimages' => 0,
					'imagewidth' => '100px',
					'imageheight' => '',
					);
				  $sdstr = $OAC->showCategoriesData($content,$params);
			  }
		  }#if categories
		return $sdstr;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['showtype'] = strip_tags( $new_instance['showtype'] );
		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}else{
			$title = __( 'New title', 'text_domain' );
		}
		if ( isset( $instance[ 'showtype' ] ) ) {
			$showtype = $instance[ 'showtype' ];
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        
        <p>  
    	<label for="<?php echo $this->get_field_id( 'showtype' ); ?>"><?php _e('Display Type:', 'example'); ?></label>  
    	<select id="<?php echo $this->get_field_id( 'showtype' ); ?>" name="<?php echo $this->get_field_name( 'showtype' ); ?>" >  
        <?php if($instance['showtype'] == categories){
			$label = 'Show Categories';
		}else{
			$label = 'Show Latest Products';
		}
		?>
        <option value="<?php echo $instance['showtype']; ?>"><?php echo $label; ?></option>
        <option value="categories">Show Categories</option>
        <option value="latestproducts">Show Latest Products</option>
        </select>
		</p>
        
		<?php 
	}

} // class Foo_Widget

add_action( 'widgets_init', create_function( '', 'register_widget( "opencart_widget" );' ) );
