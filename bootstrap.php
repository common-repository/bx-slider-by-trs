<?php
	
	if (! defined('ABSPATH')) exit;
	
	/**
	 * BX Slider by TRS
	 *
	 * @package     bx-slider
	 * @author      TheRightSolutions (TRS)
	 * @copyright   2017 © TheRightSol - All rights are reserved
	 * @license     GPL-2.0+
	 *
	 * @wordpress-plugin
	 * Plugin Name: BX Slider by TRS
	 * Plugin URI:  https://vessno.com
	 * Description: Wonderful BX slider for WordPress.
	 * Version:     2.1.1
	 * Author:      alishanvr
	 * Author URI:  https://vessno.com
	 * Text Domain: therightsol
	 * License:     GPL-2.0+
	 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
	 */
	
	if (! class_exists('BX_SLIDER_BOOTSTRAP')){
		class BX_SLIDER_BOOTSTRAP{
			function __construct ()
			{
				
				define('TRS_PLUGIN_URL', plugin_dir_url(__FILE__));
				define('TRS_PLUGIN_PATH', plugin_dir_path(__FILE__));
				
				// abstract classes first
				require_once('common/class-common-abstract.php');
				
				// required most first classes
				require_once ('backend/classes/class-shortcode-handler.php');
				
				// in series - Backend classes
				require_once ('backend/classes/class-bootstrap.php');
				require_once ('backend/classes/class-bx-slider-post.php');
				require_once ('backend/classes/class-bx-slider-columns.php');
				require_once ('backend/classes/class-add-about-page.php');
				
				
				// --------------------------------------------------
				
				
				// in series - Frontend classes
				require_once ('frontend/classes/class-frontend-trs-bx-slider.php');
			
			
			}
		}
		
		new BX_SLIDER_BOOTSTRAP;
	}
	
	