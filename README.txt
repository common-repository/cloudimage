 === Cloudimage ===
Contributors: scaleflex, cloudimage, cloudimageio
Tags: CDN, convert webp, image resizing, optimize images, SEO, resize, fast, compression, optimize, image optimization, image optimizer, optimize, image compression, optimize images, images optimization, optimize images, image compressor, image optimisation, webp
Requires PHP: 5.6
Tested up to: 6.5.5
Requires at least: 4.8
Stable tag: 4.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The easiest way to resize, compress, optimise and deliver lightning fast images to your users on any device via CDN.

== Description ==

**Did you know?**
Faster images increase conversion and thus revenue.

Did you know digital content impacts conversion rates more than you think? The first 4 seconds of your page load time have the highest impact on your conversion rate. Faster images increase conversion and revenue!

Cloudimage resizes, optimizes, compresses and distributes your images lightning-fast over CDN on any device around the world.

You can apply image filters, make custom transformations as well as remove watermarks. Start getting the most out of your images and convert more users thanks to beautiful and fast visuals. Enjoy Visual AI capabilities that can automatically and smartly resize, transform, or optimize your images with features such as background removal and lightning optimization.

Cloudimage embeds lazyloading as well as a progressive loading effect to ensure the best user experience possible on your websites. Our Cloudimage WordPress Plugin leverages the Cloudimage v7 API and offers two different options for making images responsive:

1. Using standard HTML5 [srcscet](https://developer.mozilla.org/en-US/docs/Learn/HTML/Multimedia_and_embedding/Responsive_images) tags. To use this option your WordPress theme must support natively the HTML5 tags for responsive images. Using this method, images in the WordPress media gallery will be automatically delivered over Cloudimage.
2. Using the powerful [Cloudimage Responsive JS Plugin](https://scaleflex.github.io/js-cloudimage-responsive/). This plugin smartly identifies the image container width, and then delivers the optimal image size. There’s no need for your Theme to support responsive images when choosing this method. This plugin also adds lazyloading and progressive loading to your images for an optimal user experience. This option guarantees the lightest possible output code and does not modify the images in the WordPress media gallery.

The Cloudimage WordPress Plugin needs no development: just plug-and-play!

You can easily [register](https://www.cloudimage.io/en/registration?utm_source=WordPress&utm_medium=referral&utm_campaign=cloudimage_wordpress_plugins_page&utm_content=organic_plugin_profile) for a free Cloudimage account and start enjoying fast and responsive images today. Every month, you get 25GB of CDN traffic and image cache for free. If this limit is exceeded, our teams will contact you and we can explore our different plans! For any small to medium-sized WordPress site, 25GB seems to do the trick.

You can find more information on our paid plans [here](https://www.scaleflex.com/pricing?utm_source=WordPress&utm_medium=referral&utm_campaign=cloudimage_wordpress_plugins_page&utm_content=organic_plugin_profile).

**How does the Cloudimage WordPress Plugin work?**

The Cloudimage plugin rewrites the WordPress image URLs and replaces them with Cloudimage URLs. Your origin images will be downloaded from your storage, resized by Cloudimage and then distributed over CDN.

You can now also enjoy a statistics dashboard within the Cloudimage plugin configuration page in your WordPress admin!

If you have suggestions for new features, feel free to email us at hello@scaleflex.com

Cloudimage is crafted by the [Scaleflex team](https://www.scaleflex.com/?utm_source=WordPress&utm_medium=referral&utm_campaign=cloudimage_wordpress_plugins_page&utm_content=organic_plugin_profile). Also, follow [Scaleflex on Twitter](https://twitter.com/scaleflex_com) for the latest news!

== Installation ==

Effortlessly incorporate the plugin into your WordPress site by following these steps:

1. Discover and install the plugin via the Plugins > Add New page in your WordPress dashboard. Alternatively, you can upload the plugin's .zip file directly on this page.
2. If you haven't already, [sign up for a free account](https://www.cloudimage.io/en/registration?utm_source=WordPress&utm_medium=referral&utm_campaign=cloudimage_wordpress_plugins_page&utm_content=organic_plugin_profile) on the Cloudimage website to obtain your unique token.
3. Activate the Cloudimage plugin within the Plugins page on your WordPress dashboard.
4. Navigate to the plugin's configuration page and input either your Cloudimage token or custom CNAME to complete the setup.

== Frequently Asked Questions ==

= Question 1: How does Cloudimage resize and optimize my WordPress images? =

When you first load your WordPress site after the activation of the Cloudimage Plugin, your origin images will be directly downloaded by the Cloudimage management infrastructure. They will then be resized, optimized and delivered over CDN to your final users.

Cloudimage adds an extra layer of image cache (shield) on top of the CDN, in order to make any further request from the CDN to an origin image as fast as possible.

Cloudimage does not store your WordPress visuals permanently. Always keep your images in your WordPress gallery.

= Question 2: Why are my images not going through Cloudimage? =

If your images aren’t processing through Cloudimage, check if you have cache services like W3 Total Cache, WP Super Cache, or others. If this is the case, you will need to reload the cache to enable the transformation of your URL.

If however the problem persists, please contact us directly at: hello@scaleflex.com

= Question 3: How much does Cloudimage cost? =

Cloudimage SaaS offers a free tier subscription for 25GB CDN traffic and 25GB image cache per month. We also offer paid plans with higher CDN traffic as well as image cache allowances. You may find our pricing [here](https://www.scaleflex.com/pricing?utm_source=WordPress&utm_medium=referral&utm_campaign=cloudimage_wordpress_plugins_page&utm_content=organic_plugin_profile).

= Question 4: Will my original images be affected? =

Cloudimage downloads your images automatically and does not modify your original images.

= Question 5: What happens if I deactivate the Cloudimage WP Plugin? =

If you were to deactivate the Cloudimage WP plugin, your website would revert to the condition it was in prior to the activation of the Cloudimage WP plugin. We do not apply any permanent changes to your WordPress.

== Screenshots ==

1. Cloudimage website
2. Benchmark your images before and after Cloudimage
3. Plugin configuration page
4. Cloudimage Admin - Usage Statistics
5. Cloudimage Admin - Performance Statistics

== Changelog ==

= 1.0.0 =
* First version of Cloudimage WP plugin adapted from photon (Jetpack)

= 2.0.0 =
* Added support for Cloudimage v7 API
* Re-designed plugin configuration page
* Added support for the [Cloudimage Responsive JS Plugin](https://scaleflex.github.io/js-cloudimage-responsive/)
* Added native <noscript> tags to load images if JavaScript is disabled on user's browser

= 2.0.5 =
* Added option to disable lazyloading if handled by another plugin

= 2.1.0 =
* BlurHash implementation of progressive loading as alternative. Newly uploaded images and existing images on updated articles will load with the BlurHash progressive loading effect. See demo (link to blurhash demo page).

= 2.1.1 =
* Styling improvements in admin section
* Added better text on tooltips with additional information

= 2.1.2 =
* Improvements on blurhash loading

= 2.1.3 =
* Text improvements in admin section

= 2.1.4 =
* Bug fixes for unused variables, planned for version 3.0

= 2.1.5 =
* Insert different JavaScript responsive library if blurhash is used. Save progressive loading.

= 2.1.6 =
* Add default ci-ration = 1
* Change the version of the JavaScript responsive libraries

= 2.1.7 =
* Added new baloon with additional information in footer
* Changed link to cloudimage login page in footer

= 2.2.0 =
* Change version of the JavaScript responsive plugins
* fixed bug with is-resized class for resized images

= 2.3.0 =
* Change the default function of Cloudimage picture resizing from "fit" to "bound"

= 2.3.5 =
* Better error handlig for the base Cloudimage class
* Blurhash PHP comptability fix

= 2.4.0 =
* Add support for the latest versions of Cloudimage JavaScript plugin
* Add additional functions to fix Cloudimage JavaScript plugin bugs

= 2.4.1 =
* Switch to Cloudimage JavaScript version 3.3.2

= 2.4.2 =
* Switch to Cloudimage JavaScript version 3.3.3

= 2.5.0 =
* Add hook for the content in Elementor theme

= 2.6.0 =
* Remove unused functions and files
* Visual styling of the admin section
* Added two type of modes for simplicity
* Added new type of hook for full content filtering (output buffering)

= 2.6.1 =
* Add hint in admin to use a caching plugin

= 2.6.2 =
* Add new version of the JavaScript responsive plugin 3.5.0

= 2.7.0 =
* Start using the filters: 'the_content', 'the_excerpt', 'comment_text', 'widget_text_content' for filtering the content for the JavaSript responsive mode

= 2.7.1 =
* Improve localhost detection

= 2.7.2 =
* Improvement for handle bad configuration of WordPress base URLs

= 2.7.3 =
* Improved filtering content in Elementor
* Speed improvement in filtering content
* Better Error handling, when you don't have all images size (Comming from WP 4.x)

= 2.7.4 =
* Improved content filtering for JavaScript mode

= 2.7.5 =
* SVG excluded from JavaScript mode
* Improved background-images detection in the JavaScript mode

= 2.7.6 =
* Detection of logged in user, to avoid using Cloudimage for saving bandwidth in admin
* Upgrade to the newest version of JavaScript responsive plugin 4.2.1
* Text improvements in admin section
* Improve regex to detect a not fully W3 compliant background images
* Improve speed of content filtering

= 2.7.7 =
* Fix text typo in admin section

= 2.7.8 =
* Add custom hook for custom changes on cloudimage URL - filter_cloudimage_build_url

= 2.8.0 =
* MP3 files exclude in backend mode, as some of JavaScript widgets not work fine
* image_downsize remove in JavaScript mode
* Not using CDN and URL prefixing in backend mode, when there is logged in user
* Extend and add new srcsets in background mode, even the theme is not adding them (as option)

= 2.8.1 =
* image_downsize turn off, when user is logged in

= 2.8.2 =
* fix an issue in WooCommerce Single Product view

= 2.8.3 =
* reorder JavaScript initialization scripts
* increment JavaScript responsive plugin to 4.4.0
* add function for detection of checkout page
* add improved scripts on checkout pages
* add inline styles in JavaScript mode for better visualization
* admin section texts improvement

= 2.8.4 =
* fix an issue in JavaScript mode with custom domain for background images
* fix an issue with custom img_filters in build URL construction

= 2.8.5 =
* improve RegEx for the background image filtering
* improve validator of Cloudimage input token

= 2.8.6 =
* fix bug with some styles used in the JS mode

= 2.8.7 =
* fix issue with custom Cloudimage name and background image

= 2.8.8 =
* fix some styles in admin section

= 2.8.9 =
* Instagram widget fix

= 2.9.0 =
* Add options to choose if plugin should work when user is logged in

= 2.9.1 =
* Tested with WP version 5.4.2
* Improved wordings in the admin section

= 2.9.2 =
* Changed text in admin section

= 2.9.3 =
* Compatibility checks with WordPress 5.5

= 2.9.4 =
* Fix problem for icons in dashboard in JavaScript mode and admin logged-in in some cases

= 3.0.0 =
* Adding advanced settings page with a lot of advanced configurations for customizing the service on your website
* Srcset adding support to img tag if you enabled the option in advanced settings page
* Improving the loading for JS libraries by loading them through ultrafast CDN

= 3.0.1 =
* Fix an issue with some PHP version and Advanced tab

= 3.0.2 =
* Fix an issue with double CDN of Cloudimage JS
* Add the newest, improved version of Cloudimage JS - 4.6.3
* Fix an issue with RegEx in JS mode (WP_DEBUG warning)

= 3.0.3 =
* Fix preg_match issue in some PHP versions

= 3.0.4 =
* Fix preg_match issue in some PHP versions

= 3.0.5 =
* Adding option (Remove v7) in general settings page for removing api versioning in the URL's.
* Fix issue of repeating of URLs.

= 3.0.6 =
* automate the remove-v7 switch by making it dependant on the token field. added contributors.

= 3.0.7 =
* language string and css changes.

= 3.0.8 =
* this version was just for a pre-publish demo (which includes reverting v3.0.6 and make & make the use-when-logged-in dial switched on by defaut).

= 3.0.9 =
* this version was just for a pre-publish demo (which includes the language string improvements of v3.1.0).

= 3.1.0 =
* language string improvements.

= 3.1.1 =
* fix setting plugin config options.

= 3.1.2 =
* remove-v7 dial turned on by default. checking by token disabled.

= 3.1.3 =
* brought back the ability to check v7 or not by token. corrected the checking logic. addressed the slowness problem

= 3.1.4 =
* corrected the v7 checking logic again

= 3.1.5 =
* improve appearances and text

= 3.1.6 =
* patch-up for fr_FR sites

= 3.2.0 =
* tested up to WP version 6.1.1

= 3.2.1 =
* Fixed error and tested up to WP version 6.2.2

= 4.0.0 =
* Release new version for Cloudimage

= 4.0.1 =
* Update information for Cloudimage Plugin

= 4.0.2 =
* Set low priority for function cloudimage_output_buffer
* Add new config "URL signature" (work only with Standard mode)

== Upgrade Notice ==
* Upgrading from version 1 to 2 or 3 can show you warnings in the admin section
