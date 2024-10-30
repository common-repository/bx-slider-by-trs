<?php if (!defined('ABSPATH')) exit; ?>



<div class="item ui-state-default">
    <div class="img_and_caption_wrapper">
        <div class="img_container">
            <?php echo $element; ?>
            <input type="hidden" value="<?php echo $img_id; ?>" id="image_id" class="image_id"
                   name="<?php echo parent::getPluginSlug(); ?>[<?php echo $id; ?>][image_id]"/>
            <input type="hidden" value="<?php echo $img_url; ?>" id="img_url" class="img_url"
                   name="<?php echo parent::getPluginSlug(); ?>[<?php echo $id; ?>][img_url]">
        </div>

        <div class="img_caption_wrapper">
            <label for="img_caption"><?php _e('Image Caption: ', parent::getPluginTextDomain()); ?></label>
            <input type="text" value="<?php echo $img_caption; ?>" placeholder="Image Title"
                   name="<?php echo parent::getPluginSlug(); ?>[<?php echo $id; ?>][img_caption]" class="img_caption"
                   id="img_caption">
            <span class="description"><?php _e('Please enter caption to display on the image. You may write your html here.', parent::getPluginTextDomain()); ?></span>


        </div>
        <div class="buttons_wrapper">
            <button class="set_custom_images primary-btn button"><?php _e('Click to add image/video', parent::getPluginTextDomain()) ?></button>
            <div class="remove_slide_wrapper"><a href="#." id="remove_slide"
                                                 class="remove_slide"><?php _e('Remove Slide', parent::getPluginTextDomain()) ?></a>
            </div>

        </div>
    </div>
</div>