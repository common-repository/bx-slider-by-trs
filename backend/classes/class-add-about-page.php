<?php 
if (! defined('ABSPATH') ) exit;

if (! class_exists('TRS_BX_SLIDER_ABOUT_PAGE')){
	class TRS_BX_SLIDER_ABOUT_PAGE extends TRS_BX_SLIDER{
		
		private $slider_about_menu_slug;
		
		
		function __construct ()
		{
			parent::__construct();
			
			
			$this->slider_about_menu_slug = parent::getPluginSlug() . '-about';
			add_action('admin_menu', [$this, 'bx_slider_about_page']);
		}
		
		public function bx_slider_about_page ()
		{
			add_submenu_page('edit.php?post_type=' . parent::getPluginSlug(), __('TRS BX Slider - About Us', parent::getPluginSlug()), __('About', parent::getPluginSlug()), 'manage_options', $this->slider_about_menu_slug, [$this, 'render_about_page']);
		}
		
		public function render_about_page ()
		{
			ob_start();
			?>
			
			<div class="wrap">
				<h1><?php _e('TRS BX Slider', parent::getPluginSlug()); ?></h1>
				
				<p><?php _e('TRS BX Slider is based on BX Slider. Please donate us <a href="http://www.therightsol.com/donations/"> Click Here </a>', parent::getPluginSlug()); ?></p>
				
				<hr />
				<p class="description"><?php _e('Please note that this plugin will always free. And all of its future releases and updates will always be free of cost.'); ?></p>
				<div class="description contact"><?php _e('Contact Us: <a href="mailto:plugins@therightsol.com">plugins@therightsol.com</a>', parent::getPluginSlug()); ?></div>

                <hr />
                
                <a target="_blank" href="http://www.therightsol.com/donations/">
                    <img src="<?php echo BACKEND_ASSET_URL . '/images/donate-us.jpg' ?>" alt="donate-us-banner" width="300" height="150">
                </a>
                
			</div>

			<?php
			echo apply_filters('trs_bx_slider_return_settings_page_html', ob_get_clean());
		}
	}
	
	new TRS_BX_SLIDER_ABOUT_PAGE;
}