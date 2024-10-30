<?php 
if (! defined('ABSPATH') ) exit;

if (! class_exists('TRS_BX_SLIDER_FRONTEND')){
	class TRS_BX_SLIDER_FRONTEND extends TRS_BX_SLIDER_COMMON_CLASS_ABSTRACT {
		
		function __construct ()
		{
			$this->define_constants();
			add_action('wp_enqueue_scripts', [$this, 'register_scripts']);
		}
		
		protected function define_constants(){
			define('FRONTEND_DIR', untrailingslashit(TRS_PLUGIN_PATH . 'frontend'));
			define('FRONTEND_URL', untrailingslashit(TRS_PLUGIN_URL . 'frontend'));
			define('FRONTEND_ASSET_DIR', untrailingslashit( FRONTEND_DIR ) . '/assets' );
			define('FRONTEND_ASSET_URL', untrailingslashit( FRONTEND_URL . '/assets'));
			define('FRONTEND_INCLUDES_DIR', untrailingslashit( FRONTEND_DIR . '/includes'));
		}
		
		
		public function register_scripts ()
		{
			// registering
			wp_register_script('bx-slider-original', FRONTEND_ASSET_URL . '/js/jquery.bxslider.min.js', ['jquery'], null, true);
			wp_register_script('bx-slider-jquery-easing', FRONTEND_ASSET_URL . '/js/jquery.easing.1.3.js', ['jquery'], null, true);
			wp_register_script('bx-slider-fitvids', FRONTEND_ASSET_URL . '/js/jquery.fitvids.js', ['jquery'], null, true);
			wp_register_script('bx-slider-frontend', FRONTEND_ASSET_URL . '/js/bxslider-frontend.js', ['jquery'], null, true);
			wp_register_style('bx-style-original', FRONTEND_ASSET_URL . '/css/jquery.bxslider.min.css', null, null, 'all');
			
			// Enqueuing
			wp_enqueue_style('bx-style-original');
			wp_enqueue_script('bx-slider-jquery-easing');
			wp_enqueue_script('bx-slider-fitvids');
			wp_enqueue_script('bx-slider-original');
			wp_enqueue_style('bx-style-frontend');
			
			// localization
			//wp_localize_script('bx-slider', 'bx_slider', [ 'ajax_url' => admin_url('admin-ajax.php') ]);
		}
	}
	
	new TRS_BX_SLIDER_FRONTEND();
}
