<?php
	if (! defined('ABSPATH') ) exit;
	
	$slider_properties = self::getSliderProperties();
?>

<script>
    jQuery(document).ready(function () {
        var $ = jQuery;
        
        <?php foreach ($slider_properties as $propertyArr): ?>
        
        var trs_bx_slider_<?php echo $propertyArr['id']; ?> = $('#trs-bx-slider-<?php echo $propertyArr['id']; ?>').bxSlider({
			<?php echo $propertyArr['properties']; ?>
            captions: true
        });
	
		<?php endforeach; ?>
    });
</script>
