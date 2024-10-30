<?php 
if (! defined('ABSPATH') ) exit;

if (! class_exists('TRS_BX_SLIDER_SHORTCODE_HANDLER')){
	class TRS_BX_SLIDER_SHORTCODE_HANDLER extends TRS_BX_SLIDER_COMMON_CLASS_ABSTRACT {
	
		public static $shortcode_slug = 'trs-bx-slider';
		private $dynamic_bx_slider_js = '';
		private static $slider_ids = [];
		private static $slider_properties = [];
		
		function __construct ()
		{
			add_shortcode(self::getShortcodeSlug(), [$this, 'render_shortcode']);
			add_action('wp_footer', [$this, 'render_dynamic_bx_slider_js']);
		}
		
		/**
		 * @return array
		 */
		public static function getSliderProperties ()
		{
			return self::$slider_properties;
		}
		
		/**
		 * @param array $slider_properties
		 */
		public static function setSliderProperties (array $slider_properties)
		{
			self::$slider_properties[] = $slider_properties;
		}
		
		
		
		/**
		 * @return array
		 */
		public static function getSliderIds ()
		{
			return self::$slider_ids;
		}
		
		/**
		 * @param array $slider_ids
		 */
		public static function setSliderIds ($slider_ids)
		{
			self::$slider_ids[] = $slider_ids;
		}
		
		
		
		/**
		 * @return string
		 */
		public static function getShortcodeSlug ()
		{
			return self::$shortcode_slug;
		}
		
		
		public function render_dynamic_bx_slider_js ()
		{
 			echo apply_filters('render_dynamic_bx_slider_js', (include_once (FRONTEND_INCLUDES_DIR . '/dynamic-bx-slider.php')) );
		}
		
		
		public function render_shortcode ($atts )
		{
		    extract(
				shortcode_atts(
					[
						'id'	=> false
					], $atts
				)
			);
			
			
			
			if ($id && !empty($id)){
				$post = get_post( $id );
				$sliderArr = get_post_meta($post->ID, TRS_BX_SLIDER_POST::getDbSliderKey(), true);
				$sliderMetaArr = get_post_meta($post->ID, TRS_BX_SLIDER_POST::getDbSliderMetaKey(), true);
				
				$slider_height = (isset($sliderMetaArr['slider_height']) && !empty($sliderMetaArr['slider_height'])) ? $sliderMetaArr['slider_height'] : '';
				$adaptive_height = (isset($sliderMetaArr['adaptive_height']) && !empty($sliderMetaArr['adaptive_height'])) ? $sliderMetaArr['adaptive_height'] : '';
				$mswil = (isset($sliderMetaArr['mswil']) && !empty($sliderMetaArr['mswil'])) ? $sliderMetaArr['mswil'] : '';
				$bx_pager = (isset($sliderMetaArr['bx_pager']) && !empty($sliderMetaArr['bx_pager'])) ? $sliderMetaArr['bx_pager'] : '';
				
				$slider_easing = (isset($sliderMetaArr['slider_easing']) && !empty($sliderMetaArr['slider_easing'])) ? $sliderMetaArr['slider_easing'] : '';
 
  				$slide_width = (isset($sliderMetaArr['slide_width']) && !empty($sliderMetaArr['slide_width'])) ? $sliderMetaArr['slide_width'] : '';
				$min_slides = (isset($sliderMetaArr['min_slides']) && !empty($sliderMetaArr['min_slides'])) ? $sliderMetaArr['min_slides'] : '';
				$max_slides = (isset($sliderMetaArr['max_slides']) && !empty($sliderMetaArr['max_slides'])) ? $sliderMetaArr['max_slides'] : '';
				$slide_margin = (isset($sliderMetaArr['slide_margin']) && !empty($sliderMetaArr['slide_margin'])) ? $sliderMetaArr['slide_margin'] : '';
				$move_slides = (isset($sliderMetaArr['move_slides']) && !empty($sliderMetaArr['move_slides'])) ? $sliderMetaArr['move_slides'] : '';
				
				$slider_mode = (isset($sliderMetaArr['slider_mode']) && !empty($sliderMetaArr['slider_mode'])) ? $sliderMetaArr['slider_mode'] : '';
				$slide_speed = (isset($sliderMetaArr['slide_speed']) && !empty($sliderMetaArr['slide_speed'])) ? $sliderMetaArr['slide_speed'] : '2000';
				$ticker_mode = (isset($sliderMetaArr['ticker_mode']) && !empty($sliderMetaArr['ticker_mode'])) ? $sliderMetaArr['ticker_mode'] : 'false';
				$ticker_hover = (isset($sliderMetaArr['ticker_hover']) && !empty($sliderMetaArr['ticker_hover'])) ? $sliderMetaArr['ticker_hover'] : 'false';
				
				
				// configure class variables.
				if (! empty($adaptive_height)){
					$this->dynamic_bx_slider_js .= 'adaptiveHeight: true,';
                }
				
				if (! empty($mswil)){
					$this->dynamic_bx_slider_js .= ' infiniteLoop: false, hideControlOnEnd: true,'; // manual show without infinite loop
				}
				
				if (! empty($bx_pager)){
					$this->dynamic_bx_slider_js .= ' pager: true,';
				}else {
					$this->dynamic_bx_slider_js .= ' pager: false,';
                }
				
				if (! empty($slide_width) )
					$this->dynamic_bx_slider_js .= ' slideWidth: ' . $slide_width . ' ,';
				
				if (! empty($min_slides) )
					$this->dynamic_bx_slider_js .= ' minSlides: ' . $min_slides . ' ,';
				
				if (! empty($max_slides) )
					$this->dynamic_bx_slider_js .= ' maxSlides: ' . $max_slides . ' ,';
				
				if (! empty($slide_margin) )
					$this->dynamic_bx_slider_js .= ' slideMargin: ' . $slide_margin . ' ,';
				
				if (! empty($move_slides) and ( absint($move_slides) !== 0 ) )
					$this->dynamic_bx_slider_js .= ' moveSlides: ' . $move_slides . ' ,';
				
				if (!empty($slider_mode))
					$this->dynamic_bx_slider_js .= ' mode: ' . ( ($slider_mode === 'v') ? '"vertical"'  : ( (($slider_mode === 'h')) ? '"horizontal"' : '' ) ). ' ,';
				
				if (!empty($slider_easing))
				    $this->dynamic_bx_slider_js .= 'easing: \'' . $slider_easing . '\',  useCSS: false,';
				
				if (!empty($ticker_mode))
				    $this->dynamic_bx_slider_js .= 'ticker: ' . $ticker_mode . ', ';
				
				if (!empty($ticker_hover))
					$this->dynamic_bx_slider_js .= 'tickerHover: ' . $ticker_hover . ',';
				
				$this->dynamic_bx_slider_js .= 'speed: ' . $slide_speed . ', ';
				
				
				$style = '';
				if (empty($adaptive_height)){
					if (!empty($slider_height)):
						$style .= 'style="';
						
						$style .= 'height: ' . $slider_height . 'px ; ';
						$style .= 'width: 100%; ';
						
						
						$style .= '"';
					
					endif;
                }
				
				ob_start();
				?>
				<ul id="trs-bx-slider-<?=$id?>" class="trs-bx-slider bxslider slider-<?php echo $id; ?> slider-title-<?php echo $post->post_title; ?>" >
				
				<?php
                    
                    foreach ($sliderArr as $slide ) :
                        $ext = pathinfo($slide['img_url'], PATHINFO_EXTENSION);
						
                        if (self::is_image($ext))
                            $slider_internal = '<img ' . $style . 'src="' . $slide['img_url'] . '" title="'. $slide['img_caption'] . '" />';
                        else if (self::is_video($ext))
                            $slider_internal = '<video '.$style.' controls=""  name="'.$slide['img_caption'] .'"><source src="'.$slide['img_url'].'" type="video/'.strtolower($ext).'"></video>';
                        else
                            $slider_internal = '<img ' . $style . 'src="'.FRONTEND_ASSET_URL.'/images/ext-not-supported.png" title="" />';
                ?>
					<li <?php echo $style; ?> style="" class="slide-image-<?php echo $slide['image_id']; ?>" >
                        <?php echo $slider_internal; ?>
					</li>
				<?php endforeach; ?>
				
				</ul>
				
				<?php
				// setting slider properties.
				self::setSliderProperties(['id' => $id, 'properties' => $this->dynamic_bx_slider_js]);
    
				$slider_html = ob_get_clean();
				return apply_filters('trs_bx_slider_return_shortcode_html', $slider_html, $id);
 			}
			
			return '';
		}
		
		
		public static function is_image ( $ext )
		{
			$available_image_types = [
                'bm',
                'bmp',
                'gif',
                'jpe',
                'JPEG',
                'jpg',
                'png',
                'tif',
            ];
			
			$available_image_types = apply_filters('trs_bx_slider_add_image_types', $available_image_types);
			
			return in_array(strtolower($ext), array_map('strtolower', $available_image_types));
			
		}
		
		public static function is_video ( $ext )
		{
			$available_video_types = [
				'ogg',
				'webm',
				'mp4'
			];
			
			$available_video_types = apply_filters('trs_bx_slider_add_video_types', $available_video_types);
			
			return in_array(strtolower($ext), array_map('strtolower', $available_video_types));
			
		}
	}
	
	new TRS_BX_SLIDER_SHORTCODE_HANDLER();
}

