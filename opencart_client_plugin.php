<?php
/*
Plugin Name: OpenCart Client Plugin
Plugin URI: http://ombing.info/2014/05/15/simple-opencart-api/
Description: OpenCart Client Plugin
Author: Norbert Christian L. Feria
Version: 1.0
Author URI: http://ombing.info/2014/05/15/simple-opencart-api/
*/

include('opencart-widget.php');
include('includes/plugshort_class.php');
include('includes/opencart_client.php');

include('occp_class.php');
$occp = new opencart_client_class(__FILE__);
?>
