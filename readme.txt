=== Dual Size Responsive Slider ===
Contributors: Hiroki Kanazawa
Tags: slider,slider show,responsive,image
Requires at least: 3.5
Tested up to: 4.4.0
License: GPLv2 or later
Stable Tag: 1.2.0

This plugin displays slide show for responsive using FlexSlider2. You can set 2 images its size is different, size for PC and smart phone.

== Description ==
Responsive Slider is plugin to display HTML and Javascript for Flexslider2. You can use small size image when screen width is small. It is effective to load page.

* You can set Large Slide size in option page.
* You can set Small Slide size in option page.
* You can set Threshold Width of screen in option page. It is choose loading image whether Large Slide or Small Slide through the screen width.
* If you want to use two or more Slider in your site, you can add Slider post. Slider is based on parent relationship.
* Slider parent post can control slide order through click operation.
* You can customize Flexslider2 option "animation", "direction", "animationLoop", "slideshowSpeed", "animationSpeed", "pauseOnHover", "controlNav" and "directionNav".


== Installation ==
1. Upload "dual-size-responsive-slider" directory to the "/wp-content/plugins/" directory
2. Activate the plugin through the "Plugins" menu in WordPress
3. Then plugin will create post type "responsive-slider", add new slide through the "Slider" menu 
4. Use shortcode "responsive-slider" or function "responsive_slider"

== Changelog ==
= 1.2 =
* Slider script is added resize event.
* Method of generating slider HTML is changed.
* Flexslider version is up to 2.6.0

= 1.1 =
* Rename plugin name from Two Image Responsive Slider to Dual Size Responsive Slider.
* "slide link" custom field is added at Slide post.
* Some file names are changed.

= 1.0 =
* Initial version.