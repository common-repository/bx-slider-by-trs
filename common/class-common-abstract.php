<?php 
if (! defined('ABSPATH') ) exit;

if (! class_exists('TRS_BX_SLIDER_COMMON_CLASS_ABSTRACT')){
	abstract class TRS_BX_SLIDER_COMMON_CLASS_ABSTRACT{
		
		protected function debug($arr, $exit = false){
			echo '<pre>';
			print_r($arr);
			echo '</pre>';
			
			if ($exit){
				echo '<hr />' . 'exiting from ' . __CLASS__ . ' on line #' . __LINE__;
				exit;
			}
		}
		
		
		
		/**
		 * is_ajax - Returns true when the page is loaded via ajax.
		 * @return bool
		 */
		public static function is_ajax()
		{
			return defined( 'DOING_AJAX' );
		}
	}
	
	
	
}