<?php

class opencart_client_class extends occp_plugshort {
	var $setting_group;
	public function __construct($file) {        
        parent::__construct();
		$this->setting_group = 'occp_settings';
		add_action('admin_menu', array( $this, '_occp_settingspage') );
		add_action('admin_init', array( $this,'occp_register_settings') );
		
		$this->define_shortcodes("latestproducts","_occp_latestproducts");
		$this->define_shortcodes("listcategories","_occp_listcategories"); 
	}#construct
	
	function occp_register_settings(){
    	register_setting($this->setting_group, $this->setting_group);
	}

	public function _occp_settingspage(){
		add_options_page( 'Opencart Settings', 'Opencart Client Settings', 'manage_options', 'occp-settings', array( $this, '_occp_settings'));
	}#function
	
	public function _occp_settings(){ ?>
		<div class="wrap">
		<h2>OpenCart Client Plugin Settings Page</h2>
        <p>You may get the values for these settings on your opencart admin and go to<BR>
Extensions->Product Feeds->OpenCart API and then Click Edit<BR><BR>

*Please make sure that you have saved your settings on your opencart API</p>
		<form action="options.php" method="post"><?php
			settings_fields( $this->setting_group );
			do_settings_sections( __FILE__ );
	
			$options = get_option($this->setting_group); 
			?>
			<table class="form-table">
				<tr>
					<th scope="row">Application I.D.</th>
					<td>
						<fieldset>
							<label>
                            	<?php $setting_index = "app_id"; ?>
								<input name="<?php echo $this->setting_group; ?>[<?php echo $setting_index; ?>]" type="text" id="<?php echo $setting_index; ?>" value="<?php echo (isset($options[$setting_index]) && $options[$setting_index] != '') ? $options[$setting_index] : ''; ?>"/>
								<br />
								<span class="description">Please enter the Application I.D.</span>
							</label>
						</fieldset>
					</td>
				</tr>
                <tr>
					<th scope="row">Application Public Key</th>
					<td>
						<fieldset>
							<label>
                            	<?php $setting_index = "pub_key"; ?>
								<input size="40" name="<?php echo $this->setting_group; ?>[<?php echo $setting_index; ?>]" type="text" id="<?php echo $setting_index; ?>" value="<?php echo (isset($options[$setting_index]) && $options[$setting_index] != '') ? $options[$setting_index] : ''; ?>"/>
								<br />
								<span class="description">Please enter the Public Key</span>
							</label>
						</fieldset>
					</td>
				</tr>
                <tr>
					<th scope="row">Application Private Key</th>
					<td>
						<fieldset>
							<label>
                            	<?php $setting_index = "app_key"; ?>
								<input size="40" name="<?php echo $this->setting_group; ?>[<?php echo $setting_index; ?>]" type="text" id="<?php echo $setting_index; ?>" value="<?php echo (isset($options[$setting_index]) && $options[$setting_index] != '') ? $options[$setting_index] : ''; ?>"/>
								<br />
								<span class="description">Please enter the Private Key</span>
							</label>
						</fieldset>
					</td>
				</tr>
                <tr>
					<th scope="row">Application URL</th>
					<td>
						<fieldset>
							<label>
                            	<?php $setting_index = "app_url"; ?>
								<input size="50" name="<?php echo $this->setting_group; ?>[<?php echo $setting_index; ?>]" type="text" id="<?php echo $setting_index; ?>" value="<?php echo (isset($options[$setting_index]) && $options[$setting_index] != '') ? $options[$setting_index] : ''; ?>"/>
								<br />
								<span class="description">Please enter the Application url e.g. www.youropencart.com *withouth trailing slash (/)</span>
							</label>
						</fieldset>
					</td>
				</tr>
			</table>
			<input type="submit" value="Save" />
		</form>
        <p><h2>Shortcodes:</h2><BR />
        [latestproducts] = List Products according to publish date<BR />
        [listcategories] = List all store categories
        </p>
        <p><h2>Styling:</h2><BR />
		to customize the content shown by the shortcodes above<BR />
        ul.products_content{}<BR />
		ul.categories_content{}<BR /><BR />
        to customize the widget content<BR />
        ul.products{}<BR />
        ul.categories{}<BR />
        </p>
	</div>
		
		<?php
	}#function
	
	public function _occp_latestproducts($thecontent,$shortcode){
		$sdstr = '<h2>View Our Latest Products</h2>';
		  $OAC = new OCApiClient();
		  
		  $options = get_option('occp_settings');
		  
		  $OAC->app_id = $options['app_id'];
		  $OAC->app_key = $options['app_key'];
		  $OAC->pub_key = $options['pub_key'];
		  $OAC->app_url= $options['app_url'].'/?route=feed/oc_api/';
		  
			$content = $OAC->getLatestProducts();
			  if(!$content){
				  $content = $OAC->getError();
			  }else{
				  $params = array(
					'container_open' => '<div>',
					'container_close' =>  '</div>',
					'ulclass' => 'products_content',
					'showimages' => 1,
					'imagewidth' => '100px',
					'imageheight' => '',
					);
				  $content = $OAC->getProductsData($content,$params);
			  }
		$sdstr .= $content;
        $thecontent = str_replace('['.$shortcode.']', $sdstr, $thecontent); 
        return $thecontent;
	}
	
	public function _occp_listcategories($thecontent,$shortcode){
		$sdstr = '<h2>Store Categories</h2>';
		$OAC = new OCApiClient();
		  
		  $options = get_option('occp_settings');
		  
		  $OAC->app_id = $options['app_id'];
		  $OAC->app_key = $options['app_key'];
		  $OAC->pub_key = $options['pub_key'];
		  $OAC->app_url= $options['app_url'].'/?route=feed/oc_api/';
		  
			$content = $OAC->getCategories();
			  if(!$content){
				  $content = $OAC->getError();
			  }else{
				  $params = array(
					'container_open' => '<div>',
					'container_close' =>  '</div>',
					'ulclass' => 'categories_content',
					'showimages' => 0,
					'imagewidth' => '100px',
					'imageheight' => '',
					);
				  $content = $OAC->getCategoriesData($content,$params);
			  }
			  
		$sdstr .= $content;
        $thecontent = str_replace('['.$shortcode.']', $sdstr, $thecontent); 
        return $thecontent;
	}
	
}#class

?>
