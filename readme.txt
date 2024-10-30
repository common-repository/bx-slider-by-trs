=== BX Slider by TRS ===
Contributors: alishanvr
Tags: bx slider, wordpress slider, slider, logo slider, ticker slider, full width slider, news slider, carousel slider, carousel
Donate link: #
Requires at least: 5.0
Tested up to: 6.1
Stable tag: stable
License: GPL2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt


BX Slider by TRS is multipurpose slider with horizontal and vertical mode. This provides multiple features like Full width slider, Logo Slider and Ticker Slider etc. This is Wonderful BX slider for WordPress.

== Description ==
WordPress Slider plugin based on BX Slider. This plugin has multiple features like Full Width Slider, Logo Slider and Ticker Slider etc. This plugin provides wide range of hooks that any user can get result as his/her own requirement without touching core files.

Please note that this plugin will always free and all of its future releases and updates will always be free.  But we accept donations for future work.

== Installation ==
Upload the plugin to your blog, Activate it and have fun.

== Special Features ==
<ol>
    <li>jQuery Easing</li>
    <li>Vertical and Horizontal mode</li>
    <li>Slider Ticker & options</li>
    <li>Drag & Drop sorting</li>
    <li>Support of Images and Videos</li>
    <li>Many filter hooks are available to do almost any task / changing without touching core.</li>
    <li>Slider Options</li>
    <li>Slider short code</li>
    <li>Slider adaptive height</li>
    <li>much more</li>
</ol>

== Frequently Asked Questions ==

= Does this plugin work with newest WP version and also older versions? =
Yes, this plugin works really fine with newest version as old version!

= I want to wrap slider output in a div element =
You can use <code>trs_bx_slider_return_shortcode_html</code> filter like this way.

<code>
    add_filter('trs_bx_slider_return_shortcode_html', 'test_func', 10, 3);
    function test_func( $slider, $slider_id ){
        if (absint($slider_id) === 4){
            $html = '<div class="your-custom-class">';
            $html .= $slider;
            $html .= '</div>';

            return $html;
        }

        // default
        return $slider;
    }
</code>

= I am getting error that Extension of provided images / video is not supported. Please read documentation. =
You are getting this error because you are using image or video that is supported by BX slider by TRS.
We support mp4, webm and ogg video type that is compatible with HTML video element. Same in case with images.
But you can extend and add your own extensions by using below filters.
<code>
    'trs_bx_slider_add_image_types'
    'trs_bx_slider_add_video_types'
</code>

Example:
<strong>Add More Images Extensions</strong>
<code>
    add_filter('trs_bx_slider_add_image_types', 'test_func', 10);
    function test_func( $image_types ){
        $image_types[] = 'dwg';
        $image_types[] = 'any-other-image-extension';


        return $image_types;
    }
</code>

<strong>Add More Videos Extensions</strong>

<code>
    add_filter('trs_bx_slider_add_video_types', 'test_func', 10);
    function test_func( $video_types ){
        $video_types[] = 'mp3';
        $video_types[] = 'avi';
        $video_types[] = 'any-other-video-extension';

        return $video_types;
    }
</code>

== Changelog ==
<strong>v 2.1.1</strong>
<ul>
    <li>Tested with latest WordPress version</li>
    <li>Removed the demo link and donation link</li>
    <li>Update the readme file</li>
</ul>

<strong>v 2.1</strong>
<ul>
    <li>jQuery easing feature is now enabled.</li>
    <li>BX Slider option for 'Manual show without infinite loop' is now added.</li>
    <li>Show / Hide pager feature is now available.</li>
    <li>Slider Mode [Vertical / Horizontal added] is now available.</li>
    <li>Ticker mode is now available</li>
    <li>Settings for Standard Responsive Carousel is now available.</li>
    <li>limit WordPress Media to choose only one item at a time.</li>
</ul>

<strong>v 1.1</strong>
<ul>
    <li>Now you can sort slides with Drag and Drop feature.</li>
    <li>BX Slider has now adaptive height option. You can set it while creation of slider.</li>
    <li>Enhanced slider dashboard view little bit.</li>
</ul>

<strong>v 1.0.3</strong>
<ul>
    <li>Minor Bug Fixes</li>
</ul>

<strong>v 1.0.2</strong>
<ul>
    <li>Slider height meta option added</li>
    <li>Minor bug fixed - image disappeared when slider is second time update [FIXED]</li>
    <li>Add one more class for bootstrapping</li>
</ul>

<strong>v 1.0.1</strong>
<ul>
    <li>Dashboard GUI has been updated</li>
    <li>Some Labels has been updated</li>
    <li>Add Slider Short code on 'add slider' page</li>
</ul>

<strong>v 1.0.0</strong>
<ul>
    <li>Starting the jurney</li>
</ul>