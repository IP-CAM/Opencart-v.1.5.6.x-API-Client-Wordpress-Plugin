<?php
class OCApiClient{
	var $app_id = '';
  	var $app_key = '';
  	var $pub_key = '';
	var $enc_request =  array();
	var $app_url ='';
    var $app_action = 'products';
	var $error = '';
  
	 public function __construct($app_id = '',$app_key = '',$pub_key = '') {
		 $this->app_id = $app_id;
		 $this->app_key = $app_key;
		 $this->pub_key = $pub_key;
	 }#construct
	 
	 private function genRequest($args){
		if(!$this->checkAppCredentials()){
			return FALSE;
		}
		$args['publickkey'] =  $this->pub_key;
		$this->enc_request = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->app_key, json_encode($args), MCRYPT_MODE_ECB));	 
		
		return TRUE;
	 }
	 
	 public function getResults(){
		 $content = file_get_contents($this->app_url.$this->app_action.'&appid='.$this->app_id.'&request='.urlencode($this->enc_request).'');
		 
		 #echo $this->app_url.$this->app_action.'&appid='.$this->app_id.'&request='.urlencode($this->enc_request).'';
		 
		 return $content;
	 }
	 
	 private function checkAppCredentials(){
		if(strlen($this->app_url) == 0 ){
			return FALSE;
		}
		if(strlen($this->app_id) == 0 ){
			return FALSE;
		}
		if(strlen($this->app_key) == 0 ){
			return FALSE;
		}
		if(strlen($this->pub_key) == 0 ){
			return FALSE;
		}
		return TRUE;
	 }#function
	 
	 public function getCategories(){
		 $this->app_action = 'categories';
		 $args = array();
		 if($this->genRequest($args) == TRUE){
			 $content = $this->getResults();
			 $content = json_decode($content, true);
			 if($content['success'] <> 1){
				$this->error = $content["code"]." ".$content["message"];
				return FALSE;
			 }else{
			 	 return $content['categories'];
			 }
		 }else{
			  return FALSE;
		 }
	 }#function
	 
	 public function getLatestProducts(){
		 $this->app_action = 'products';
		 $args = array(
		 	'latest' => 1
		 );
		 if($this->genRequest($args) == TRUE){
			 $content = $this->getResults();
			 #var_dump($content);
			 $content = json_decode($content,true);
			 if($content['success'] <> 1){
				$this->error = $content["code"]." ".$content["message"];
				return FALSE;
			 }else{
			 	 return $content["products"];
			 }
		 }else{
			  return FALSE;
		 }
	 }#function
	 
	 public function getCategoryProducts($category){
		 $this->app_action = 'products'; 
		 $args = array(
			'category' => $category
		 );
		 if($this->genRequest($args) == TRUE){
			 $content = $this->getResults();
			 $content = json_decode($content, true);
			 if($content['success'] <> 1){
				$this->error = $content["code"]." ".$content["message"];
				return FALSE;
			 }else{
			 	 return $content["products"];
			 } 
		 	
		 }else{
			  return FALSE;
		 }
	 }#function
	 
	 
	 public function getCategoriesData($categories,$params){
		 $sdstr = '';
		 foreach($params as $key => $value){
			${$key} = $value;	
		 }
		 $sdstr .= $container_open;
		 $sdstr .= '<ul class="'.$ulclass.'">';
		 foreach($categories as $category){
			$sdstr .= '<li>';
			if($showimages == 1 && $category["image"] <> FALSE){
				$sdstr .= '<a href="'.$category["href"].'"><img src="'.$category["image"].'"  width="'.$imagewidth.'" height="'.$imageheight.'"></a><BR>'; 
			}
			$sdstr .= '<a href="'.$category["href"].'">'.$category["name"].'</a><BR>';
			$sdstr .= '</li>';
		 }
		 $sdstr .= '</ul>';
		 $sdstr .= $container_close;
		 return $sdstr;
	 }
	 
	 public function showCategoriesData($categories,$params){
		 echo $this->getCategoriesData($categories,$params);
	 }#function
	 
	 public function getProductsData($products,$params){
		 $sdstr = '';
		 foreach($params as $key => $value){
			${$key} = $value;	
		}
		 $sdstr .= $container_open;
		 $sdstr .= '<ul class="'.$ulclass.'">';
		 foreach($products as $product){
			$sdstr .= '<li>';
			$sdstr .= '<a href="'.$product["href"].'">';
			if($showimages == 1){
				$sdstr .= '<img src="'.$product["thumb"].'" width="'.$imagewidth.'" height="'.$imageheight.'">'; 
			}
			$sdstr .= $product["name"];
			$sdstr .= '</a>';
			$sdstr .= '</li>';
		 }
		 $sdstr .= '</ul>';
		 $sdstr .= $container_close;
		 return $sdstr;
	 }
	 
	 public function showProductsData($products,$params){
		 echo $this->getProductsData($products,$params);
	 }#function
	 
	 public function geterror(){
		 return $this->error;
	 }
	 
	 public function showError(){
		 echo $this->geterror();
	 }#function
	 
}#class
?>
