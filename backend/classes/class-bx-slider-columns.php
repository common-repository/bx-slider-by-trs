<?php 
if (! defined('ABSPATH') ) exit;

if (! class_exists('TRS_BX_SLIDER_COLUMNS')){
	class TRS_BX_SLIDER_COLUMNS extends TRS_BX_SLIDER{
		private $custom_shortcode_column_id;
		private $shortcode_start;
		private $shortcode_end;
		
		
		function __construct ()
		{
			parent::__construct();
			
			$this->custom_shortcode_column_id = parent::getPluginSlug() . '_short_code';
			
			
			$this->shortcode_start = '[' . $this->get_registered_shortcode() . ' ';
			$this->shortcode_end = ']';
			
			add_filter( 'manage_'.parent::getPluginSlug().'_posts_columns', [$this, 'set_custom_shortcode_columns'] );
			add_action( 'manage_'.parent::getPluginSlug().'_posts_custom_column' , [$this, 'set_custom_shortcode_columns_data'], 10, 2 );
		}
		
		private function get_registered_shortcode(){
			return  TRS_BX_SLIDER_SHORTCODE_HANDLER::getShortcodeSlug();
		}
		
		public function set_custom_shortcode_columns($columns) {
			$columns[$this->custom_shortcode_column_id] = __( 'Shortcode', parent::getPluginTextDomain());
			
			//@TODO: Set Sorting in Future
			//uksort($columns, [$this, 'cmp_func']);
			//$this->debug($columns, true);
			
			asort( $columns );
			
			
			return $columns;
		}
		
		
		private function cmp_func($a, $b){
			return strcmp($a, $b);
		}
		
		
		public function set_custom_shortcode_columns_data ($column, $post_id)
		{
			switch ($column) {
				case  $this->custom_shortcode_column_id:
					echo $this->get_shortcode($post_id);
					break;
				
			}
		}
		
		public function get_shortcode ($post_id)
		{
			return '<input type="text" readonly onfocus="this.select();" value="' . $this->shortcode_start . 'id=' . $post_id . $this->shortcode_end .'" class="large-text code"/>';
		}
	}
	
	new TRS_BX_SLIDER_COLUMNS();
}