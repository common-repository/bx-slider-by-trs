<?php 
if (! defined('ABSPATH') ) exit;

if (! class_exists('TRS_BX_SLIDER')){
	class TRS_BX_SLIDER extends TRS_BX_SLIDER_COMMON_CLASS_ABSTRACT{
		
		
		private $plugin_name;
		private $plugin_slug; // post type
		private $plugin_version;
		private $plugin_text_domain;
		
		
		protected function __construct ()
		{
			$this->plugin_name = 'BX Slider by TRS';
			$this->plugin_slug = 'trs-bx-slider';
			$this->plugin_version = '2.1';
			$this->plugin_text_domain = 'therightsol';
			
			$this->define_constants();
			add_filter('admin_footer_text', [$this, 'update_footer_text']);
			
			add_action('admin_enqueue_scripts', [$this, 'register_scripts']);
			
			//$this->debug(BACKEND_ASSET_URL);
		}
		
		public function update_footer_text(){
			global $pagenow, $typenow;
			
			$text = sprintf( __( 'Thank you for creating with <a href="%s">WordPress</a>.' ), __( 'https://wordpress.org/' ) );
			
			if ( ($pagenow === 'edit.php' || $pagenow === 'post-new.php') && $typenow === $this->getPluginSlug()){
				$text = sprintf( __('TRS BX Slider is based on <a href="%s">BX Slider</a> and WP version is developed by <a href="%s">TheRightSolutions</a><br />Contact Us: <a href="%s">plugins@therightsol.com</a>'), __('http://bxslider.com'), __('http://www.therightsol.com'), __('mailto:plugins@therightsol.com') );
			}
			
			return $text;
		}
		
		protected function define_constants(){
			define('BACKEND_DIR', untrailingslashit(TRS_PLUGIN_PATH . 'backend'));
			define('BACKEND_URL', untrailingslashit(TRS_PLUGIN_URL . 'backend'));
			define('BACKEND_ASSET_DIR', untrailingslashit( BACKEND_DIR ) . '/assets' );
			define('BACKEND_ASSET_URL', untrailingslashit( BACKEND_URL . '/assets'));
			define('BACKEND_INCLUDES_DIR', untrailingslashit( BACKEND_DIR . '/includes'));
		}
		
		public function register_scripts(){
			global $pagenow, $typenow;
			
			if ( $pagenow !== 'edit.php' && $typenow !== $this->getPluginSlug())
				return;
			
			
			// registering
			wp_register_script('bx-slider', BACKEND_ASSET_URL . '/js/bxslider.js', ['jquery'], null, true);
			wp_register_style('bx-style', BACKEND_ASSET_URL . '/css/bxslider.css', null, null, 'all');
			
			// Enqueuing
			wp_enqueue_style('bx-style');
			wp_enqueue_script('bx-slider');
			
			// jQuery Sortable
			//wp_enqueue_script('jquery-ui-sortable');
			
			// Localization
			wp_localize_script('bx-slider', 'bx_slider', [ 'ajax_url' => admin_url('admin-ajax.php') ]);
			
		}
		
		/**
		 * @return string
		 */
		public function getPluginName ()
		{
			return $this->plugin_name;
		}
		
		/**
		 * @param string $plugin_name
		 */
		public function setPluginName ($plugin_name)
		{
			$this->plugin_name = $plugin_name;
		}
		
		/**
		 * @return string
		 */
		public function getPluginSlug ()
		{
			return $this->plugin_slug;
		}
		
		/**
		 * @param string $plugin_slug
		 */
		public function setPluginSlug ($plugin_slug)
		{
			$this->plugin_slug = $plugin_slug;
		}
		
		/**
		 * @return string
		 */
		public function getPluginVersion ()
		{
			return $this->plugin_version;
		}
		
		/**
		 * @param string $plugin_version
		 */
		public function setPluginVersion ($plugin_version)
		{
			$this->plugin_version = $plugin_version;
		}
		
		/**
		 * @return string
		 */
		public function getPluginTextDomain ()
		{
			return $this->plugin_text_domain;
		}
		
		/**
		 * @param string $plugin_text_domain
		 */
		public function setPluginTextDomain ($plugin_text_domain)
		{
			$this->plugin_text_domain = $plugin_text_domain;
		}
		
		
	}
}

