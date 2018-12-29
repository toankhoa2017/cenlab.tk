<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI Smarty
 *
 * Smarty templating for Codeigniter
 *
 * @package   CI Smarty
 * @author    Dwayne Charrington
 * @copyright Copyright (c) 2012 Dwayne Charrington and Github contributors
 * @link      http://ilikekillnerds.com
 * @license   http://www.apache.org/licenses/LICENSE-2.0.html
 * @version   2.0
 */

//require_once "Smarty/Smarty.class.php";
require_once "Smarty/SmartyBC.class.php";
class CI_Smarty extends SmartyBC {
    public $template_ext = '.php';
    public function __construct()
    {
		parent::__construct();
		$config = array(
			'cache_status' => FALSE,//Smarty caching enabled by default unless explicitly set to FALSE
			'theme_path' => FCPATH . '/themes/',//The path to the themes
			'theme_name' => 'default',//The default name of the theme to use (this can be overridden)
			'cache_lifetime' => 3600,//Cache lifetime. Default value is 3600 seconds (1 hour) Smarty's default value
			'compile_directory' => 'cache/compiled/',//Where templates are compiled
			'cache_directory' => 'cache/cached/',//Where templates are cached
			'config_directory' => '',//Where Smarty configs are located
			'template_ext' => 'tpl',//Default extension of templates if one isn't supplied
			'template_error_reporting' => E_ALL & ~E_NOTICE,//Error reporting level to use while processing templates
			'smarty_debug' => FALSE,//Debug mode turned on or off (TRUE / FALSE)
		);
		$CI = get_instance(); //Store the Codeigniter super global instance... whatever
		//$CI->load->config('smarty'); //Load the Smarty config file
		$this->loadPlugin('smarty_compiler_switch'); //Load the smarty switch plugin
		//$this->debugging = config_item('smarty_debug'); //Turn on/off debug
		$this->debugging = $config['smarty_debug'];
		//Set some pretty standard Smarty directories
		$this->setCompileDir($config['compile_directory']);
		$this->setCacheDir($config['cache_directory']);
		$this->setConfigDir($config['config_directory']);

		$this->template_ext = $config['template_ext']; //Default template extension

		$this->cache_lifetime = $config['cache_lifetime']; //How long to cache templates for

		$this->disableSecurity(); //Disable Smarty security policy

		//If caching is enabled, then disable force compile and enable cache
		if ($config['cache_status'] === TRUE) $this->enable_caching();
		else $this->disable_caching();

		$this->error_reporting   = $config['template_error_reporting']; //Set the error reporting level

		// Should let us access Codeigniter stuff in views
		// This means we can go for example {$this->session->userdata('item')}
		// just like we normally would in standard CI views
		$this->assign("this", $CI);
    }

    /**
     * Enable Caching
     *
     * Allows you to enable caching on a page by page basis
     * @example $this->smarty->enable_caching(); then do your parse call
     */
    public function enable_caching()
    {
        $this->caching = 1;
    }
    
    /**
     * Disable Caching
     *
     * Allows you to disable caching on a page by page basis
     * @example $this->smarty->disable_caching(); then do your parse call
     */
    public function disable_caching()
    {
        $this->caching = 0; 
    }

}