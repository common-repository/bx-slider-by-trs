<?php 
if (! defined('ABSPATH') ) exit;

if (! function_exists('TRS_BX_SLIDER_POST')){
	class TRS_BX_SLIDER_POST extends TRS_BX_SLIDER{
		
		private static $db_slider_key;
		private static $db_slider_meta_key;
		
		function __construct ()
		{
			parent::__construct();
			
			self::$db_slider_key = parent::getPluginSlug() . 'slider_details';
			self::$db_slider_meta_key = parent::getPluginSlug() . 'slider_meta';
			
			add_action('init', [$this, 'bx_slider_post_type']);
			add_action('add_meta_boxes_' . parent::getPluginSlug(), [$this, 'slider_meta_box']);
 			add_action ( 'admin_enqueue_scripts', [$this, 'add_media_button']);
			add_action( 'save_post_' . parent::getPluginSlug(), [$this, 'save_slider_post'], 10, 2);
			add_action( 'wp_ajax_get_slider_item_template', [$this, 'get_slider_item_template'] );
			
			// Media Filters
			add_filter('media_view_strings', [$this, 'remove_unwanted_buttons_from_media']);
			
			
		}
		
		public function remove_unwanted_buttons_from_media($strings) {
 		    global $pagenow, $typenow;
			
			if ( ($pagenow === 'edit.php' || $pagenow === 'post-new.php') && $typenow === $this->getPluginSlug()){
				$strings['insertIntoPost'] = 'insert into slider';
				
				$strings['createGalleryTitle'] = '';
				$strings['createVideoPlaylistTitle'] = '';
				$strings['insertFromUrlTitle'] = '';
				$strings['createPlaylistTitle'] = '';
			}
			
			return $strings;
   
		}
		
		/**
		 * @return string
		 */
		public static function getDbSliderKey ()
		{
			return self::$db_slider_key;
		}
		
		
		
		// Register Custom Post Type
		public function bx_slider_post_type() {
			
			$labels = array(
				'name'                  => _x( 'BX Sliders', 'Post Type General Name', 'therightsol' ),
				'singular_name'         => _x( 'BX Slider', 'Post Type Singular Name', 'therightsol' ),
				'menu_name'             => __( 'BX Slider', 'therightsol' ),
				'name_admin_bar'        => __( 'BX Slider', 'therightsol' ),
				'archives'              => __( 'Item Archives', 'therightsol' ),
				'attributes'            => __( 'Item Attributes', 'therightsol' ),
				'parent_item_colon'     => __( 'Parent Slider:', 'therightsol' ),
				'all_items'             => __( 'All Sliders', 'therightsol' ),
				'add_new_item'          => __( 'Add New Slider', 'therightsol' ),
				'add_new'               => __( 'Add New Slider', 'therightsol' ),
				'new_item'              => __( 'New Slider', 'therightsol' ),
				'edit_item'             => __( 'Edit Slider', 'therightsol' ),
				'update_item'           => __( 'Update Slider', 'therightsol' ),
				'view_item'             => __( 'View Slider', 'therightsol' ),
				'view_items'            => __( 'View Sliders', 'therightsol' ),
				'search_items'          => __( 'Search Slider', 'therightsol' ),
				'not_found'             => __( 'Not found', 'therightsol' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'therightsol' ),
				'featured_image'        => __( 'Featured Image', 'therightsol' ),
				'set_featured_image'    => __( 'Set featured image', 'therightsol' ),
				'remove_featured_image' => __( 'Remove featured image', 'therightsol' ),
				'use_featured_image'    => __( 'Use as featured image', 'therightsol' ),
				'insert_into_item'      => __( 'Insert into item', 'therightsol' ),
				'uploaded_to_this_item' => __( 'Uploaded to this item', 'therightsol' ),
				'items_list'            => __( 'Items list', 'therightsol' ),
				'items_list_navigation' => __( 'Items list navigation', 'therightsol' ),
				'filter_items_list'     => __( 'Filter items list', 'therightsol' ),
			);
			$args = array(
				'label'                 => __( 'BX Slider', 'therightsol' ),
				'description'           => __( 'Create BX Slider', 'therightsol' ),
				'labels'                => $labels,
				'supports'              => array( 'title', ),
				'hierarchical'          => false,
				'public'                => false,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'menu_icon'             => 'dashicons-format-gallery',
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => false,
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'capability_type'       => 'page',
			);
			register_post_type( parent::getPluginSlug(), $args );
		}
		
		
		public function slider_meta_box( ){
			add_meta_box(
				'bx-slider-slides-meta-box',
				__( 'Add Slides' ),
				[$this, 'render_slider_slides_meta_box'],
				parent::getPluginSlug(),
				'normal',
				'default'
			);
			
			add_meta_box(
				'bx-slider-shortcode-box',
				__( 'Slider Shortcode' ),
				[$this, 'render_slider_shortcode_meta_box'],
				parent::getPluginSlug(),
				'side',
				'default'
			);
			
			add_meta_box(
				'bx-slider-add-more-meta-box',
				__( 'Add More Slides' ),
				[$this, 'render_slider_add_more_meta_box'],
				parent::getPluginSlug(),
				'side',
				'default'
			);
			
			add_meta_box(
				'bx-slider-options-meta-box',
				__( 'Slider Options' ),
				[$this, 'render_slider_options_meta_box'],
				parent::getPluginSlug(),
				'side',
				'default'
			);
			
		}
		
		public function render_slider_options_meta_box ($post)
		{
		    $slider_meta = get_post_meta($post->ID, self::getDbSliderMetaKey(), true);
			$slider_height = (isset($slider_meta['slider_height']) && !empty($slider_meta['slider_height'])) ? $slider_meta['slider_height'] : '';
			$adaptive_height = (isset($slider_meta['adaptive_height']) && !empty($slider_meta['adaptive_height'])) ? $slider_meta['adaptive_height'] : '';
			$mswil = (isset($slider_meta['mswil']) && !empty($slider_meta['mswil'])) ? $slider_meta['mswil'] : '';
			$bx_pager = (isset($slider_meta['bx_pager']) && !empty($slider_meta['bx_pager'])) ? $slider_meta['bx_pager'] : '';
			
			$slider_mode = (isset($slider_meta['slider_mode']) && !empty($slider_meta['slider_mode'])) ? $slider_meta['slider_mode'] : '';
			$slide_speed = (isset($slider_meta['slide_speed']) && !empty($slider_meta['slide_speed'])) ? $slider_meta['slide_speed'] : '2000';
			
			$slide_width = (isset($slider_meta['slide_width']) && !empty($slider_meta['slide_width'])) ? $slider_meta['slide_width'] : '';
			$min_slides = (isset($slider_meta['min_slides']) && !empty($slider_meta['min_slides'])) ? $slider_meta['min_slides'] : '';
			$max_slides = (isset($slider_meta['max_slides']) && !empty($slider_meta['max_slides'])) ? $slider_meta['max_slides'] : '';
			$slide_margin = (isset($slider_meta['slide_margin']) && !empty($slider_meta['slide_margin'])) ? $slider_meta['slide_margin'] : '';
			$move_slides = (isset($slider_meta['move_slides']) && !empty($slider_meta['move_slides'])) ? $slider_meta['move_slides'] : '';
			
			$slider_easing = (isset($slider_meta['slider_easing']) && !empty($slider_meta['slider_easing'])) ? $slider_meta['slider_easing'] : '';
			$ticker_mode = (isset($slider_meta['ticker_mode']) && !empty($slider_meta['ticker_mode'])) ? $slider_meta['ticker_mode'] : 'false';
			$ticker_hover = (isset($slider_meta['ticker_hover']) && !empty($slider_meta['ticker_hover'])) ? $slider_meta['ticker_hover'] : 'false';
			
            ob_start();
            ?>

            <div class="trs_meta_box">
                <div class="slider_height_wrapper">
                    <label for="slider_height"><?php _e('Slider Height', parent::getPluginTextDomain()); ?></label>
                    <input type="number" min="200" max="1600" step="1" name="slider_meta[slider_height]" class="slider_height" id="slider_height" value="<?php echo $slider_height; ?>">
                    <?php _e('px', parent::getPluginTextDomain()); ?>
                    <p class="description"><?php _e('Leave blank for auto height.'); ?></p>
                </div>

                <hr />
                
                <div class="slider_adaptive_height_wrapper">
                    <label for="adaptive_height"><?php _e('Adaptive Height', parent::getPluginTextDomain()); ?></label>
                    <input type="checkbox" <?php echo checked('true', $adaptive_height) ?> name="slider_meta[adaptive_height]" class="adaptive_height" id="adaptive_height" value="true">
					<?php _e('Enable', parent::getPluginTextDomain()); ?>
                    <p class="description"><?php _e('Check to enable adaptive height. If adaptive height is checked then Slider height will not work.'); ?></p>
                </div>

                <hr />

                <div class="slider_without_infinite_loop_show_wrapper">
                    <label for="mswil"><?php _e('Manual show without infinite loop', parent::getPluginTextDomain()); ?></label>
                    <input type="checkbox" <?php echo checked('true', $mswil) ?> name="slider_meta[mswil]" class="mswil" id="mswil" value="true">
					<?php _e('Enable', parent::getPluginTextDomain()); ?>
                    <p class="description"><?php _e('Check to enable Manual show without infinite loop.'); ?></p>
                </div>

                <hr />

                <div class="slider_pager_wrapper">
                    <label for="bx_pager"><?php _e('Show Pager', parent::getPluginTextDomain()); ?></label>
                    <input type="checkbox" <?php echo checked('true', $bx_pager) ?> name="slider_meta[bx_pager]" class="bx_pager" id="bx_pager" value="true">
					<?php _e('Enable', parent::getPluginTextDomain()); ?>
                    <p class="description"><?php _e('Do you want to display pager ?'); ?></p>
                </div>

                <hr />

                <div class="slider_mode_wrapper">
                    <label for="slider_mode"><?php _e('Choose slider mode', parent::getPluginTextDomain()); ?></label>
                    <select name="slider_meta[slider_mode]" id="slider_mode">
                        <option value="v" <?php selected('v', $slider_mode) ?>><?php _e('Vertical', parent::getPluginTextDomain()); ?></option>
                        <option value="h" <?php selected('h', $slider_mode) ?>><?php _e('Horizontal', parent::getPluginTextDomain()); ?></option>
                    </select>
                </div>

                <hr />

                <div class="slider_pager_wrapper">
                    <strong><?php _e('Settings for Standard Responsive Carousel:', parent::getPluginTextDomain()); ?></strong>

                    <label for="slide_width"><?php _e('Slide Width', parent::getPluginTextDomain()); ?></label>
                    <input type="text"  name="slider_meta[slide_width]" class="slide_width" id="slide_width" value="<?php echo $slide_width; ?>">
					<?php _e('px', parent::getPluginTextDomain()); ?>

                    <label for="min_slides"><?php _e('Minimum Slides to show', parent::getPluginTextDomain()); ?></label>
                    <input type="number" min="1" step="1" name="slider_meta[min_slides]" class="min_slides" id="min_slides" value="<?php echo $min_slides; ?>">

                    <label for="max_slides"><?php _e('Maximum Slides to show', parent::getPluginTextDomain()); ?></label>
                    <input type="number" min="1" step="1" name="slider_meta[max_slides]" class="max_slides" id="max_slides" value="<?php echo $max_slides; ?>">

                    <label for="slide_margin"><?php _e('Slide Margin', parent::getPluginTextDomain()); ?></label>
                    <input type="text"  name="slider_meta[slide_margin]" class="slide_margin" id="slide_margin" value="<?php echo $slide_margin; ?>">
					<?php _e('px', parent::getPluginTextDomain()); ?>

                    <label for="move_slides"><?php _e('Number of slides to move at a time', parent::getPluginTextDomain()); ?></label>
                    <input type="number" min="0" step="1" name="slider_meta[move_slides]" class="move_slides" id="move_slides" value="<?php echo $move_slides; ?>">
                    <p class="description"><?php _e('Empty or 0 means default', parent::getPluginTextDomain());; ?></p>
                </div>

                <hr />

                <div class="slider_mode_wrapper">
                    <label for="slider_easing"><?php _e('Choose Easing', parent::getPluginTextDomain()); ?></label>
                    <select name="slider_meta[slider_easing]" id="slider_easing">
                        
                        <option value="linear" <?php selected('linear', $slider_easing) ?>><?php _e('linear  (Default)', parent::getPluginTextDomain()); ?></option>
                        <option value="swing" <?php selected('swing', $slider_easing) ?>><?php _e('swing', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInQuad" <?php selected('easeInQuad', $slider_easing) ?>><?php _e('easeInQuad', parent::getPluginTextDomain()); ?></option>
                        <option value="easeOutQuad" <?php selected('easeOutQuad', $slider_easing) ?>><?php _e('easeOutQuad', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInOutQuad" <?php selected('easeInOutQuad', $slider_easing) ?>><?php _e('easeInOutQuad', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInCubic" <?php selected('easeInCubic', $slider_easing) ?>><?php _e('easeInCubic', parent::getPluginTextDomain()); ?></option>
                        <option value="easeOutCubic" <?php selected('easeOutCubic', $slider_easing) ?>><?php _e('easeOutCubic', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInOutCubic" <?php selected('easeInOutCubic', $slider_easing) ?>><?php _e('easeInOutCubic', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInQuart" <?php selected('easeInQuart', $slider_easing) ?>><?php _e('easeInQuart', parent::getPluginTextDomain()); ?></option>
                        <option value="easeOutQuart" <?php selected('easeOutQuart', $slider_easing) ?>><?php _e('easeOutQuart', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInOutQuart" <?php selected('easeInOutQuart', $slider_easing) ?>><?php _e('easeInOutQuart', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInQuint" <?php selected('easeInQuint', $slider_easing) ?>><?php _e('easeInQuint', parent::getPluginTextDomain()); ?></option>
                        <option value="easeOutQuint" <?php selected('easeOutQuint', $slider_easing) ?>><?php _e('easeOutQuint', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInOutQuint" <?php selected('easeInOutQuint', $slider_easing) ?>><?php _e('easeInOutQuint', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInExpo" <?php selected('easeInExpo', $slider_easing) ?>><?php _e('easeInExpo', parent::getPluginTextDomain()); ?></option>
                        <option value="easeOutExpo" <?php selected('easeOutExpo', $slider_easing) ?>><?php _e('easeOutExpo', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInOutExpo" <?php selected('easeInOutExpo', $slider_easing) ?>><?php _e('easeInOutExpo', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInSine" <?php selected('easeInSine', $slider_easing) ?>><?php _e('easeInSine', parent::getPluginTextDomain()); ?></option>
                        <option value="easeOutSine" <?php selected('easeOutSine', $slider_easing) ?>><?php _e('easeOutSine', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInOutSine" <?php selected('easeInOutSine', $slider_easing) ?>><?php _e('easeInOutSine', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInCirc" <?php selected('easeInCirc', $slider_easing) ?>><?php _e('easeInCirc', parent::getPluginTextDomain()); ?></option>
                        <option value="easeOutCirc" <?php selected('easeOutCirc', $slider_easing) ?>><?php _e('easeOutCirc', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInOutCirc" <?php selected('easeInOutCirc', $slider_easing) ?>><?php _e('easeInOutCirc', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInElastic" <?php selected('easeInElastic', $slider_easing) ?>><?php _e('easeInElastic', parent::getPluginTextDomain()); ?></option>
                        <option value="easeOutElastic" <?php selected('easeOutElastic', $slider_easing) ?>><?php _e('easeOutElastic', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInOutElastic" <?php selected('easeInOutElastic', $slider_easing) ?>><?php _e('easeInOutElastic', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInBack" <?php selected('easeInBack', $slider_easing) ?>><?php _e('easeInBack', parent::getPluginTextDomain()); ?></option>
                        <option value="easeOutBack" <?php selected('easeOutBack', $slider_easing) ?>><?php _e('easeOutBack', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInOutBack" <?php selected('easeInOutBack', $slider_easing) ?>><?php _e('easeInOutBack', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInBounce" <?php selected('easeInBounce', $slider_easing) ?>><?php _e('easeInBounce', parent::getPluginTextDomain()); ?></option>
                        <option value="easeOutBounce" <?php selected('easeOutBounce', $slider_easing) ?>><?php _e('easeOutBounce', parent::getPluginTextDomain()); ?></option>
                        <option value="easeInOutBounce" <?php selected('easeInOutBounce', $slider_easing) ?>><?php _e('easeInOutBounce', parent::getPluginTextDomain()); ?></option>
                    </select>
                </div>

                <hr />

                <label for="slide_speed"><?php _e('Slide Speed:', parent::getPluginTextDomain()); ?></label>
                <input type="number" min="0" step="250" name="slider_meta[slide_speed]" class="slide_speed" id="slide_speed" value="<?php echo $slide_speed; ?>">
                
                <hr />

                <div class="slider_ticker_mode_wrapper">
                    <label for="ticker_mode"><?php _e('Enable Ticker Mode?', parent::getPluginTextDomain()); ?></label>
                    <input type="checkbox" <?php echo checked('true', $ticker_mode) ?> name="slider_meta[ticker_mode]" class="ticker_mode" id="ticker_mode" value="true">
					<?php _e('Enable', parent::getPluginTextDomain()); ?>
                    <p class="description"><?php _e('Check to enable ticker mode. '); ?></p>


                    <label for="ticker_hover"><?php _e('Do you want to enable ticker on hover?', parent::getPluginTextDomain()); ?></label>
                    <input type="checkbox" <?php echo checked('true', $ticker_hover) ?> name="slider_meta[ticker_hover]" class="ticker_hover" id="ticker_hover" value="true">
					<?php _e('Enable', parent::getPluginTextDomain()); ?>
                    <p class="description"><?php _e('Check to enable pause on ticker hover. '); ?></p>
                </div>

            </div>
            
            <?php
			$html = ob_get_clean();
			echo apply_filters('render_slider_options_meta_box', $html);
		}
		
		public function render_slider_shortcode_meta_box ($post)
		{
			ob_start();
			?>
            <div class="slider_shortcode_metabox_wrapper">
                <?php echo ( new TRS_BX_SLIDER_COLUMNS( ) )->get_shortcode( $post->ID ); ?>
                <p class="description"><?php _e('You can copy / paste this short code into any page or page to display this slider.'); ?></p>
            </div>
			<?php
			$html = ob_get_clean();
			echo apply_filters('render_slider_shortcode_meta_box', $html);
		}
		
		public function render_slider_add_more_meta_box ( )
		{
		    ob_start();
		    ?>
            <div class="add_more_wrapper">
                <a href="#." id="add_more" class="primary-btn button add_more"><?php _e('Add More', parent::getPluginTextDomain()) ?></a>
                <span class="spinner is-active hide addmore_spinner"></span>
            </div>
			
			<?php
            $html = ob_get_clean();
			echo apply_filters('render_slider_add_more_meta_box', $html);
 		}
		
		public function add_media_button(){
			if (is_admin ())
				wp_enqueue_media ();
		}
		
		public function render_slider_slides_meta_box( $post ){
			
			$slider_imgs = (!empty(get_post_meta($post->ID, self::$db_slider_key, true))) ? get_post_meta($post->ID, self::$db_slider_key, true) : [];
			$id = (! empty($slider_imgs)) ? ( sizeof($slider_imgs) + 1 ) : 0;
			
			ob_start();
			
			echo '<div class="item-parent">';
			
			foreach ($slider_imgs as $index => $imgArr){
				$template = $this->get_slider_item_template($index, $imgArr);
				echo $template;
			}
			?>
            <div class="wrap post_slider_imgs_wrapper">
				<?php if (intval($id) === 0) :
					echo $this->get_slider_item_template($id, []);
                endif; ?>
                <input type="hidden" value="<?php echo $id; ?>" id="i" name="i">
            </div>
			<?php
            
            echo '</div>';
            
			$html = ob_get_clean();
			
			echo apply_filters('render_slider_slides_meta_box', $html);
		}
		
		// This function return Template and this function called from aJax and from this Class.
		// So, you can see required if statements.
		public function get_slider_item_template ( $id = false, array $imgArr = [])
		{
			if (parent::is_ajax()){
				$img_id = '';
				$img_url = BACKEND_ASSET_URL . '/images/placeholder.jpg';
				$img_caption = '';
				
				$get_data = filter_input_array(INPUT_GET);
				$id = intval(sanitize_text_field($get_data['index']));
				
				$element = $this->get_element($img_url, $id);
				
				include ( BACKEND_INCLUDES_DIR . '/template-slider-item.php' );
				
				wp_die();
			}
			
			
			$img_id = ( isset($imgArr['image_id']) && !empty($imgArr['image_id']) ) ? $imgArr['image_id'] : '';
			$img_url = (isset($imgArr['img_url']) && ! empty($imgArr['img_url'])) ? $imgArr['img_url'] : BACKEND_ASSET_URL . '/images/placeholder.jpg';
			$img_caption = (isset($imgArr['img_caption']) && !empty($imgArr['img_caption'])) ? $imgArr['img_caption'] : '';
			
			$element = $this->get_element($img_url, $id);
			
			include ( BACKEND_INCLUDES_DIR . '/template-slider-item.php' );
		}
		
		
		/**
         * this function return element for video or image
		 * @param $url
		 */
		private function get_element( $url, $id ){
		 
			$ext = pathinfo($url, PATHINFO_EXTENSION);
			
			if (TRS_BX_SLIDER_SHORTCODE_HANDLER::is_image($ext))
				$element = '<img src="' . $url . '" alt="img" width="200" height="200" class="slider_img" id="slider_img_'.$id.'"/>';
			else if (TRS_BX_SLIDER_SHORTCODE_HANDLER::is_video($ext))
				$element = '<video controls="" ><source src="'.$url.'" type="video/'.strtolower($ext).'"></video>';
			else
				$element = '<img src="'.FRONTEND_ASSET_URL.'/images/ext-not-supported.png" title="" />';
			 
			return $element;
        }
		
		/**
		 * @return string
		 */
		public static function getDbSliderMetaKey ()
		{
			return self::$db_slider_meta_key;
		}
		
		
		
		public function save_slider_post ( $post_id, $post )
		{
			if (wp_is_post_revision( $post_id ))
				return;
			
			if (wp_is_post_autosave( $post_id ))
				return;
			
			if ( ! current_user_can( 'manage_options' ) )
				return;
			
			if (get_post_type( $post_id ) !== parent::getPluginSlug())
				return;
			
			$posted_data = filter_input_array(INPUT_POST);
			
 		
			if ( isset($posted_data[parent::getPluginSlug()]) ){
				update_post_meta( $post_id, self::$db_slider_key, $posted_data[parent::getPluginSlug()] );
			}else {
				update_post_meta( $post_id, self::$db_slider_key, '' );
			}
			
			if (!isset($posted_data['slider_meta']['adaptive_height']))
				$posted_data['slider_meta']['adaptive_height'] = false;
			
			// Manual show without infinite loop
			if (!isset($posted_data['slider_meta']['mswil']))
				$posted_data['slider_meta']['mswil'] = false;
			
			if (!isset($posted_data['slider_meta']['bx_pager']))
				$posted_data['slider_meta']['bx_pager'] = false;
			
			if (isset($posted_data['slider_meta'])){
			    update_post_meta( $post_id, self::$db_slider_meta_key, $posted_data['slider_meta']);
            }
			
			return;
		}
		
	}
	new TRS_BX_SLIDER_POST();
}

