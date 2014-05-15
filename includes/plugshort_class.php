<?php

class occp_plugshort {

    var $shortcodes = array();
	var $foundshortcodes = array();
	
    public function __construct() {
        add_filter('the_content', array(&$this, 'browse'));
    }
#construct

    public function browse($content = '') {
	 	foreach ($this->shortcodes as $shortcode) {
			$func = $this->check_shortcodes($content);
			if ($func == FALSE) {
				return $content;
			} else {
				$content = $this->$func[1]($content, $func[0]);
			}#if func
		}#foreach shortcodes
		return $content;
    }

#function browse;

    public function check_shortcodes($thecontent) {
        $gotshortcode = FALSE;
        foreach ($this->shortcodes as $shortcode) {
			if(!in_array($shortcode[0],$this->foundshortcodes)){
				if (strstr($thecontent, "[" . $shortcode[0] . "]")) {
					$this->foundshortcodes[] = $shortcode[0];
					return $shortcode;
				}#if strstr
			}#in array
	    }#for each
        return $gotshortcode;
    }

#check shortcodes

    public function define_shortcodes($shortcode, $function_name) {
        $this->shortcodes[] = array($shortcode, $function_name);
		
    }

#function define_shortcodes

    static function formplugin_activate() {
        
    }

#plguinstartup_activate

    public function current_page_url() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"])) {
            if ($_SERVER["HTTPS"] == "on") {
                $pageURL .= "s";
            }
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

}

#class
?>
