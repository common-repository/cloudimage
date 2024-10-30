<?php
/**
 * This version will upgrade all functions and optimize it more effectively than the previous version.
 */
class Cloudimage {
    private function __construct() {

    }

    public static function init_actions() {
        // Register plugin in admin menu
        add_action('admin_menu', [__CLASS__, 'cloudimage_menu']);

        // Add plugin settings
        add_action('admin_init', [__CLASS__, 'cloudimage_settings_init']);

        // Add plugin scripts
        add_action('wp_enqueue_scripts', [__CLASS__, 'cloudimage_enqueue_scripts']);

        // Disable all image srcset
        add_filter('wp_calculate_image_srcset', [__CLASS__, 'cloudimage_disable_srcset'], 10, 5);

        // Overide img tag
        add_action('wp_head', [__CLASS__, 'cloudimage_output_buffer'], 1, 2);
        add_filter('wp_head', [__CLASS__, 'cloudimage_modify_html'], 10, 3);
        add_filter('wp_get_attachment_image_attributes', [__CLASS__, 'cloudimage_add_ci_src_tags_to_images'], 10, 3);
//        add_filter('the_content', [__CLASS__, 'cloudimage_override_img_before_rendered']);

        // Add settings link to plugin
        add_filter( 'plugin_action_links', [__CLASS__, 'add_action_links'], 10, 5 );
    }

    public static function add_action_links($actions, $plugin_file) {
        static $plugin;
        if (!isset($plugin))
            $plugin = plugin_basename(__FILE__);
        if ($plugin == $plugin_file) {
            $settings = array('settings' => '<a href="' . admin_url('admin.php?page=cloudimage') . '">' . __('Settings', 'General') . '</a>');
            $site_link = array('support' => '<a href="https://www.cloudimage.io/en/contact-us" target="_blank">Support</a>');
            $actions = array_merge($site_link, $actions);
            $actions = array_merge($settings, $actions);
        }
        return $actions;
    }

    public static function cloudimage_menu() {
        add_menu_page(
            'Cloudimage', // page title
            'Cloudimage', // menu title
            'manage_options', // capability
            'cloudimage', // menu slug
            [__CLASS__, 'cloudimage_settings_page'], // callback function
            plugin_dir_url(__FILE__) . '/assets/images/Cloudimage_BW.png'
        );
    }

    public static function cloudimage_settings_init() {
        add_settings_section(
            'cloudimage-settings-section', // section ID
            'Cloudimage Configuration', // section title
            '', // callback function
            'cloudimage-settings' // page
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_new_version', // option name
            [
                'type' => 'boolean',
                'default' => true
            ]
        );
        add_settings_field(
            'cloudimage_new_version', // field ID
            'Use new version', // field title
            [__CLASS__, 'cloudimage_new_version_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_token_cname' // option name
        );
        add_settings_field(
            'cloudimage_token_cname', // field ID
            'Token or CNAME', // field title
            [__CLASS__, 'cloudimage_token_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_use_origin_url', // option name
            [
                'type' => 'boolean',
                'default' => false
            ]
        );
        add_settings_field(
            'cloudimage_use_origin_url', // field ID
            'Use Origin URL', // field title
            [__CLASS__, 'cloudimage_use_origin_url_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_lazy_loading', // option name
            [
                'type' => 'boolean',
                'default' => false
            ]
        );
        add_settings_field(
            'cloudimage_lazy_loading', // field ID
            'Lazy Loading', // field title
            [__CLASS__, 'cloudimage_lazy_loading_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_ignore_svg', // option name
            [
                'type' => 'boolean',
                'default' => false
            ]
        );
        add_settings_field(
            'cloudimage_ignore_svg', // field ID
            'Ignore SVG Image', // field title
            [__CLASS__, 'cloudimage_ignore_svg_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_prevent_image_upsize', // option name
            [
                'type' => 'boolean',
                'default' => false
            ]
        );
        add_settings_field(
            'cloudimage_prevent_image_upsize', // field ID
            'Prevent Image Upsize', // field title
            [__CLASS__, 'cloudimage_prevent_image_upsize_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_image_quality', // option name
            [
                'type' => 'string',
                'sanitize_callback' => [__CLASS__, 'cloudimage_image_quality_validate_select_option']
            ]
        );
        add_settings_field(
            'cloudimage_image_quality', // field ID
            'Image Quality', // field title
            [__CLASS__, 'cloudimage_image_quality_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section', // section
            [
                'options' => [
                    '5' => '5',
                    '10' => '10',
                    '15' => '15',
                    '20' => '20',
                    '25' => '25',
                    '30' => '30',
                    '35' => '35',
                    '40' => '40',
                    '45' => '45',
                    '50' => '50',
                    '55' => '55',
                    '60' => '60',
                    '65' => '65',
                    '70' => '70',
                    '75' => '75',
                    '80' => '80',
                    '85' => '85',
                    '90' => '90',
                    '95' => '95',
                    '100' => '100'
                ]
            ]
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_maximum_pixel_ratio', // option name
            [
                'type' => 'string',
                'sanitize_callback' => [__CLASS__, 'cloudimage_maximum_pixel_ratio_validate_select_option']
            ]
        );
        add_settings_field(
            'cloudimage_maximum_pixel_ratio', // field ID
            'Maximum "Pixel Ratio"', // field title
            [__CLASS__, 'cloudimage_maximum_pixel_ratio_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section', // section
            [
                'options' => [
                    '1' => '1',
                    '1.5' => '1.5',
                    '2' => '2'
                ]
            ]
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_remove_v7', // option name
            [
                'type' => 'boolean',
                'default' => true
            ]
        );
        add_settings_field(
            'cloudimage_remove_v7', // field ID
            'Remove V7', // field title
            [__CLASS__, 'cloudimage_remove_v7_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_image_size_attributes' // option name
        );
        add_settings_field(
            'cloudimage_image_size_attributes', // field ID
            'Image Size Attributes', // field title
            [__CLASS__, 'cloudimage_image_size_attributes_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_custom_function' // option name
        );
        add_settings_field(
            'cloudimage_custom_function', // field ID
            'Custom javascript function', // field title
            [__CLASS__, 'cloudimage_custom_function_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_custom_library' // option name
        );
        add_settings_field(
            'cloudimage_custom_library', // field ID
            'Custom library options', // field title
            [__CLASS__, 'cloudimage_custom_library_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_skip_classes' // option name
        );
        add_settings_field(
            'cloudimage_skip_classes', // field ID
            'Skip classes', // field title
            [__CLASS__, 'cloudimage_skip_classes_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_skip_files' // option name
        );
        add_settings_field(
            'cloudimage_skip_files', // field ID
            'Skip files', // field title
            [__CLASS__, 'cloudimage_skip_files_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_local_javascript_libraries', // option name
            [
                'type' => 'boolean',
                'default' => false
            ]
        );
        add_settings_field(
            'cloudimage_local_javascript_libraries', // field ID
            'Local JavaScript libraries', // field title
            [__CLASS__, 'cloudimage_local_javascript_libraries_callback'], // callback function
            'cloudimage-settings', // page
            'cloudimage-settings-section' // section
        );

        add_settings_section(
            'standard-mode-section', // section ID
            'Standard mode', // section title
            '', // callback function
            'cloudimage-settings' // page
        );

        add_settings_section(
            'standard-mode-section', // section ID
            'Standard mode', // section title
            '', // callback function
            'cloudimage-settings' // page
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_standard_mode', // option name
            [
                'type' => 'boolean',
                'default' => false
            ]
        );
        add_settings_field(
            'cloudimage_standard_mode', // field ID
            'Activate/Deactivate', // field title
            [__CLASS__, 'cloudimage_standard_mode_callback'], // callback function
            'cloudimage-settings', // page
            'standard-mode-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_url_signature' // option name
        );
        add_settings_field(
            'cloudimage_url_signature', // field ID
            'URL Signature', // field title
            [__CLASS__, 'cloudimage_url_signature_callback'], // callback function
            'cloudimage-settings', // page
            'standard-mode-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_enable_srcset', // option name
            [
                'type' => 'boolean',
                'default' => false
            ]
        );
        add_settings_field(
            'cloudimage_enable_srcset', // field ID
            'Enable srcset adding', // field title
            [__CLASS__, 'cloudimage_enable_srcset_callback'], // callback function
            'cloudimage-settings', // page
            'standard-mode-section' // section
        );

        register_setting(
            'cloudimage-settings-group', // option group
            'cloudimage_srcset_widths' // option name
        );
        add_settings_field(
            'cloudimage_srcset_widths', // field ID
            'Srcset widths (px)', // field title
            [__CLASS__, 'cloudimage_srcset_widths_callback'], // callback function
            'cloudimage-settings', // page
            'standard-mode-section' // section
        );

        if ($_POST)
        {
            register_setting('cloudimage-settings-group', 'cloudimage', [__CLASS__, 'validate']);
        }
    }

    /**
     * Validate data from admin
     *
     * @version  4.0.1
     * @since    1.0.0
     */
    public static function validate() {
        // All options
        $valid = get_option('cloudimage');
        $valid['cloudimage_new_version'] = (int)$_POST['cloudimage_new_version'];
        return $valid;
    }

    public static function cloudimage_token_callback() {
        $token = get_option('cloudimage_token_cname');
        echo '<input type="text" name="cloudimage_token_cname" value="' . esc_attr($token) . '" class="regular-text">
        <p class="description">Cloudimage token or custom domain.</p>';
    }

    public static function cloudimage_standard_mode_callback() {
        $standard_mode = get_option('cloudimage_standard_mode', false);
        echo '<label for="cloudimage_standard_mode">
            <input name="cloudimage_standard_mode" type="checkbox" value="1" id="cloudimage_standard_mode" ' . checked(true, $standard_mode, false) . '>
        </label>
        <p class="description">Replace image URLs not using any Javascript or Javascript library.</p>';
    }

    public static function cloudimage_new_version_callback() {
        $new_version = get_option('cloudimage_new_version', true);
        echo '<label for="cloudimage_new_version">
            <input name="cloudimage_new_version" type="checkbox" value="1" id="cloudimage_new_version" ' . checked(true, $new_version, false) . '>
        </label>
        <p class="description">If not enabled, you will change to use old version of Cloudimage (optional).</p>';
    }

    public static function cloudimage_use_origin_url_callback() {
        $use_origin_url = get_option('cloudimage_use_origin_url', false);
        echo '<label for="cloudimage_use_origin_url">
            <input name="cloudimage_use_origin_url" type="checkbox" value="1" id="cloudimage_use_origin_url" ' . checked(true, $use_origin_url, false) . '>
        </label>
        <p class="description">If enabled, the plugin will only add query parameters to the image source URL, avoiding double CDN in some cases, like if you have aliases configured.</p>';
    }

    public static function cloudimage_lazy_loading_callback() {
        $lazy_loading = get_option('cloudimage_lazy_loading', false);
        echo '<label for="cloudimage_lazy_loading">
            <input name="cloudimage_lazy_loading" type="checkbox" value="1" id="cloudimage_lazy_loading" ' . checked(true, $lazy_loading, false) . '>
        </label>
        <p class="description">If enabled, only images close to the current viewpoint will be loaded.</p>';
    }

    public static function cloudimage_ignore_svg_callback() {
        $ignore_svg = get_option('cloudimage_ignore_svg', false);
        echo '<label for="cloudimage_ignore_svg">
            <input name="cloudimage_ignore_svg" type="checkbox" value="1" id="cloudimage_ignore_svg" ' . checked(true, $ignore_svg, false) . '>
        </label>
        <p class="description">By default, No.</p>';
    }

    public static function cloudimage_prevent_image_upsize_callback() {
        $prevent_image_upsize = get_option('cloudimage_prevent_image_upsize', true);
        echo '<label for="cloudimage_prevent_image_upsize">
            <input name="cloudimage_prevent_image_upsize" type="checkbox" value="1" id="cloudimage_prevent_image_upsize" ' . checked(true, $prevent_image_upsize, false) . '>
        </label>
        <p class="description">If you set Maximum "Pixel ratio" equal to 2, but some of your assets does not have min retina size(at least 2560x960), please enable this to prevent image resized. By default, Yes.</p>';
    }

    public static function cloudimage_image_quality_callback($args) {
        $image_quality = get_option('cloudimage_image_quality', '90');
        $options = $args['options'];
        $output = '<select name="cloudimage_image_quality" id="cloudimage_image_quality">';
        foreach ($options as $value => $label) {
            $selected = selected($image_quality, $value, false);
            $output .= '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
        }
        $output .= '</select>';
        echo $output . '<p class="description">The smaller the value, the more your image will compressed. Careful - the quality of the image will decrease as well. By default, 90.</p>';
    }

    public static function cloudimage_image_quality_validate_select_option($input) {
        $valid_options = [
            '5' => '5',
            '10' => '10',
            '15' => '15',
            '20' => '20',
            '25' => '25',
            '30' => '30',
            '35' => '35',
            '40' => '40',
            '45' => '45',
            '50' => '50',
            '55' => '55',
            '60' => '60',
            '65' => '65',
            '70' => '70',
            '75' => '75',
            '80' => '80',
            '85' => '85',
            '90' => '90',
            '95' => '95',
            '100' => '100'
        ];
        if (in_array($input, $valid_options)) {
            return $input;
        } else {
            return '90'; // default value
        }
    }

    public static function cloudimage_maximum_pixel_ratio_callback($args) {
        $maximum_pixel_ratio = get_option('cloudimage_maximum_pixel_ratio', '2');
        $options = $args['options'];
        $output = '<select name="cloudimage_maximum_pixel_ratio" id="cloudimage_maximum_pixel_ratio">';
        foreach ($options as $value => $label) {
            $selected = selected($maximum_pixel_ratio, $value, false);
            $output .= '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
        }
        $output .= '</select>';
        echo $output . '<p class="description">List of supported device pixel ratios, eg 2 for Retina devices.</p>';
    }

    public static function cloudimage_maximum_pixel_ratio_validate_select_option($input) {
        $valid_options = [
            '1' => '1',
            '1.5' => '1.5',
            '2' => '2'
        ];
        if (in_array($input, $valid_options)) {
            return $input;
        } else {
            return '2'; // default value
        }
    }

    public static function cloudimage_remove_v7_callback() {
        $remove_v7 = get_option('cloudimage_remove_v7', true);
        echo '<label for="cloudimage_remove_v7">
            <input name="cloudimage_remove_v7" type="checkbox" value="1" id="cloudimage_remove_v7" ' . checked(true, $remove_v7, false) . '>
        </label>
        <p class="description">Removes the "/v7" part in URL format. Activate for token created after October 20th, 2021.</p>';
    }

    public static function cloudimage_image_size_attributes_callback() {
        $image_size_attributes = get_option('cloudimage_image_size_attributes', 'use');
        echo '<input type="text" name="cloudimage_image_size_attributes" value="' . esc_attr($image_size_attributes) . '" class="regular-text">
        <p class="description">Used to calculate width and height of images.</p>';
    }

    public static function cloudimage_url_signature_callback() {
        $cloudimage_url_signature = get_option('cloudimage_url_signature', '');
        echo '<input type="text" name="cloudimage_url_signature" value="' . esc_attr($cloudimage_url_signature) . '" class="regular-text">
        <p class="description">Pleate note: This config use only for <b>"Standard Mode"</b>. To prevent URL tampering and protect your token, every Cloudimage URL can be secured 
        with an SHA-1 HMAC signature. Read more at
        <a href="https://docs.cloudimage.io/setup/security/token-security/url-signature" target="_blank">
            <b>here</b></a>
        </p>';
    }

    public static function cloudimage_custom_function_callback() {
        $custom_function = get_option('cloudimage_custom_function');
        echo '<textarea name="cloudimage_custom_function" rows="5" cols="50" id="cloudimage_custom_function">' . $custom_function . '</textarea>
        <p class="description">The valid js function starting with { and finishing with }.</p>';
    }

    public static function cloudimage_custom_library_callback() {
        $custom_library = get_option('cloudimage_custom_library');
        echo '<input type="text" name="cloudimage_custom_library" value="' . esc_attr($custom_library) . '" class="regular-text">
        <p class="description">Automatically adds Cloudimage parameters for all images, e.g. watermark=1 to put a watermark on all images. The list of all available parameters can be found on docs.cloudimage.io</p>';
    }

    public static function cloudimage_skip_classes_callback() {
        $skip_classes = get_option('cloudimage_skip_classes');
        echo '<input type="text" name="cloudimage_skip_classes" value="' . esc_attr($skip_classes) . '" class="regular-text">
        <p class="description">HTML tags with the specified classes would be skipped. Separated by comma (,)</p>';
    }

    public static function cloudimage_skip_files_callback() {
        $skip_files = get_option('cloudimage_skip_files');
        echo '<input type="text" name="cloudimage_skip_files" value="' . esc_attr($skip_files) . '" class="regular-text">
        <p class="description">Files with these extensions would be skipped. Separated by comma (,)</p>';
    }

    public static function cloudimage_local_javascript_libraries_callback() {
        $local_javascript_libraries = get_option('cloudimage_local_javascript_libraries', false);
        echo '<label for="cloudimage_local_javascript_libraries">
            <input name="cloudimage_local_javascript_libraries" type="checkbox" value="1" id="cloudimage_local_javascript_libraries" ' . checked(true, $local_javascript_libraries, false) . '>
        </label>
        <p class="description">The host where libraries files would be imported from. OFF for CDN\'s files, ON for plugin\'s local files.</p>';
    }

    public static function cloudimage_enable_srcset_callback() {
        $enable_srcset = get_option('cloudimage_enable_srcset', false);
        echo '<label for="cloudimage_enable_srcset">
            <input name="cloudimage_enable_srcset" type="checkbox" value="1" id="cloudimage_enable_srcset" ' . checked(true, $enable_srcset, false) . '>
        </label>
        <p class="description">Adding srcset for img tag.</p>';
    }

    public static function cloudimage_srcset_widths_callback() {
        $srcset_widths = get_option('cloudimage_srcset_widths');
        echo '<input type="text" name="cloudimage_srcset_widths" value="' . esc_attr($srcset_widths) . '"
            class="regular-text" placeholder="Default: 320,576,940,1080">
        <p class="description">The widths in pixels that would be generated for srcset tag if srcset adding option is enabled. Separated by comma (,)</p>';
    }

    public static function cloudimage_background_url_callback() {
        $srcset_widths = get_option('cloudimage_srcset_widths');
        echo '<input type="text" name="cloudimage_srcset_widths" value="' . esc_attr($srcset_widths) . '"
            class="regular-text" placeholder="Default: 320,576,940,1080">
        <p class="description">The widths in pixels that would be generated for srcset tag if srcset adding option is enabled. Separated by comma (,)</p>';
    }

    // Settings page callback function
    public static function cloudimage_settings_page() {
        ?>
        <div class="wrap">
            <div style="text-align: center; margin: 10px 0;">
                <img src="<?php echo plugin_dir_url(__FILE__) ?>assets/images/logo_new_cloudimage.png" style="max-width: 500px"
                     alt="Cloudimage By Scaleflex">
            </div>
            <h2 style="font-weight: bold; margin: 30px 0; text-align: center; font-size: 26px;">Welcome to the Cloudimage WordPress Plugin</h2>
            <?php
            $token_cname = get_option('cloudimage_token_cname', '');
            if ($token_cname == ''):
                ?>
                <div class="warning-box" style="padding: 10px;
                background-image: linear-gradient(to bottom,#f2dede 0,#e7c3c3 100%);
                border-radius: 6px;
                background-repeat: repeat-x;
                border: 1px solid #dca7a7;
                text-shadow: 0 1px 0 rgba(255,255,255,.2);
                box-shadow: inset 0 1px 0 rgba(255,255,255,.25), 0 1px 2px rgba(0,0,0,.05);">
                    <span style="font-size: 15px">This version will upgrade all functions and optimize it more effectively than the previous version.</span>
                    <br>
                    <span style="font-size: 15px">You need to configure again to use the Cloudimage Plugin.</span>
                </div>
            <?php endif; ?>
            <div class="description" style="margin-bottom: 30px; font-size: 15px">
                <p style="font-size: 15px">The Cloudimage Plugin will resize, compress and optimize all of your WordPress visuals, and then deliver responsive images lightning-fast over CDN all around the world.</p>
                <p style="font-size: 15px">To start enjoying faster images, simply add your Cloudimage token below, and the plugin will start working its magic.</p>
                <p style="font-size: 15px">How to start using Cloudimage? Sign up for a Cloudimage account to obtain your token. You can enjoy our entry-tier subscription for free. Sign-up will take a few seconds.</p>
                <p style="font-size: 15px"><a href="https://www.cloudimage.io/en/registration?utm_source=WordPress&utm_medium=referral&utm_campaign=cloudimage_wordpress_plugins_admin_panel&utm_content=organic_plugin_profile" target="_blank">Get your Cloudimage token here</a></p>
            </div>
            <form action="options.php" method="post">
                <?php settings_fields('cloudimage-settings-group'); ?>
                <?php do_settings_sections('cloudimage-settings'); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public static function cloudimage_enqueue_scripts() {
        $token_cname = get_option('cloudimage_token_cname', '');
        $standard_mode = get_option('cloudimage_standard_mode', false);
        if ($token_cname != '' && !$standard_mode) {
            $local_javascript_libraries = get_option('cloudimage_local_javascript_libraries', false);
            $lazy_loading = get_option('cloudimage_lazy_loading', false);
            if ($local_javascript_libraries) {
                $jsLib = plugins_url('assets/js/js-cloudimage-responsive.min.js', __FILE__);
                $lazyLoadLib = plugins_url('assets/js/lazysizes.min.js', __FILE__);
            } else {
                $jsLib = 'https://cdn.scaleflex.it/plugins/js-cloudimage-responsive/latest/wp/js-cloudimage-responsive.min.js';
                $lazyLoadLib = 'https://cdn.scaleflex.it/filerobot/js-cloudimage-responsive/lazysizes.min.js';
            }

            wp_enqueue_script(
                'cloudimage-script', // handle
                $jsLib, // source
                ['jquery'], // dependencies
                CLOUDIMAGE_VERSION, // version
                true // in footer
            );

            if ($lazy_loading) {
                wp_enqueue_script(
                    'cloudimage-lazyload-script', // handle
                    $lazyLoadLib, // source
                    ['jquery'], // dependencies
                    CLOUDIMAGE_VERSION, // version
                    true // in footer
                );
            }

            wp_enqueue_script(
                'cloudimage-config-script', // handle
                plugins_url('assets/js/cloudimage.js', __FILE__), // source
                ['jquery'], // dependencies
                CLOUDIMAGE_VERSION, // version
                true // in footer
            );

            $srcset_widths = get_option('cloudimage_srcset_widths', '320,576,940,1080');
            if ($srcset_widths == '') {
                $srcset_widths = '320,576,940,1080';
            }

            $config_data = [
                'new_version' => (int)get_option('cloudimage_new_version', false),
                'baseUrl' => get_site_url(),
                'token_or_cname' => get_option('cloudimage_token_cname'),
                'standard_mode' => (int)get_option('cloudimage_standard_mode', false),
                'use_original_url' => (int)get_option('cloudimage_use_origin_url', false),
                'lazy_loading' => (int)get_option('cloudimage_lazy_loading', false),
                'ignore_svg_image' => (int)get_option('cloudimage_ignore_svg', false),
                'prevent_image_upsize' => (int)get_option('cloudimage_prevent_image_upsize', true),
                'image_quality' => get_option('cloudimage_image_quality', '90'),
                'maximum_pixel_ratio' => get_option('cloudimage_maximum_pixel_ratio', '2'),
                'remove_v7' => (int)get_option('cloudimage_remove_v7', 'true'),
                'image_size_attributes' => get_option('cloudimage_image_size_attributes', 'use'),
                'custom_function' => get_option('cloudimage_custom_function', ''),
                'custom_library' => get_option('cloudimage_custom_library', ''),
                'skip_classes' => get_option('cloudimage_skip_classes', ''),
                'skip_files' => get_option('cloudimage_skip_files', ''),
                'local_javascript_libraries' => (int)get_option('cloudimage_local_javascript_libraries', false),
                'enable_srcset' => (int)get_option('cloudimage_enable_srcset', false),
                'srcset_widths' => $srcset_widths,
            ];

            $ciConfigJson = "const ciSettings = " . json_encode($config_data);
            wp_add_inline_script('cloudimage-script', $ciConfigJson);
        }
    }

    public static function cloudimage_disable_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
        if (!is_admin()) {
            $url = $image_src;

            $standard_mode = get_option('cloudimage_standard_mode', false);
            $enable_srcset = get_option('cloudimage_enable_srcset', false);
            if ($standard_mode && !$enable_srcset) {
                return false;
            }

            $checkFile = false;
            $skip_files = get_option('cloudimage_skip_files', '');
            $skipFilesArray = [];
            if ($skip_files != '') {
                $skipFilesArray = explode(',', $skip_files);
            }
            if (count($skipFilesArray) > 0) {
                foreach ($skipFilesArray as $skipFile) {
                    $arrPath = explode('/', $url);
                    $filename = end($arrPath);
                    if (strpos($filename, '.' . $skipFile) !== false) {
                        $checkFile = true;
                        break;
                    }
                }
            }

            if ($checkFile) {
                return false;
            }

            $token_cname = get_option('cloudimage_token_cname', '');
            $srcset_widths = get_option('cloudimage_srcset_widths', '320,576,940,1080');

            $ciImageQuality = get_option('cloudimage_image_quality', '90');
            $ciCustomLibrary = get_option('cloudimage_custom_library', '');
            $ciPreventImageUpsize = get_option('cloudimage_prevent_image_upsize', true);

            $ciURLSignature = get_option('cloudimage_url_signature', '');

            $ciRemoveV7 = get_option('cloudimage_remove_v7', false);
            $v7 = '';
            if (!$ciRemoveV7) {
                $v7 = 'v7/';
            }

            $ciUrl = 'https://' . $token_cname . '.cloudimg.io/' . $v7;
            if (strpos($token_cname, '.')) {
                $ciUrl = 'https://' . $token_cname . '/' . $v7;
            }

            $buildUrl = self::buildUrl($url, $ciImageQuality, $ciCustomLibrary, $ciPreventImageUpsize);
            if ($srcset_widths == '') {
                foreach ($sources as $key => $source) {
                    $url = $buildUrl;
                    $sources[$key]['url'] = $ciUrl . $url . '&w=' . $source['value'];
                    if ($ciURLSignature != '' && $standard_mode) {
                        $ci_sign = sha1($ciURLSignature . $url . '&w=' . $source['value']);
                        $sources[$key]['url'] = $sources[$key]['url'] . '&ci_sign=' . $ci_sign;
                    }
                }
            } else {
                $customSizeArray = explode(',', $srcset_widths);
                $newSources = [];
                foreach($customSizeArray as $item) {
                    $newSources[$item]['url'] = $ciUrl . $buildUrl . '&w=' . $item;
                    if ($ciURLSignature != '' && $standard_mode) {
                        $ci_sign = sha1($ciURLSignature . $buildUrl . '&w=' . $item);
                        $newSources[$item]['url'] = $newSources[$item]['url'] . '&ci_sign=' . $ci_sign;
                    }
                    $newSources[$item]['descriptor'] = 'w';
                    $newSources[$item]['value'] = $item;
                }
                $sources = $newSources;
            }
        }
        return $sources;
    }

    public static function cloudimage_output_buffer() {
        ob_start([__CLASS__, 'cloudimage_modify_html']);
    }

    /**
     * This function will replace the cloudimage_build_url function in old version
     */
    public static function cloudimage_modify_html($content) {
        if (!is_admin()) {
            $token_cname = get_option('cloudimage_token_cname', '');
            $standard_mode = get_option('cloudimage_standard_mode', false);
            $enable_srcset = get_option('cloudimage_enable_srcset', false);
            $srcset_widths = get_option('cloudimage_srcset_widths', '320,576,940,1080');
            if ($srcset_widths == '') {
                $srcset_widths = '320,576,940,1080';
            }
            $ignoreSvg = get_option('cloudimage_ignore_svg', false);

            $skip_classes = get_option('cloudimage_skip_classes', '');
            $skipClassesArray = [];
            if ($skip_classes != '') {
                $skipClassesArray = explode(',', $skip_classes);
            }

            $skip_files = get_option('cloudimage_skip_files', '');
            $skipFilesArray = [];
            if ($skip_files != '') {
                $skipFilesArray = explode(',', $skip_files);
            }

            $ciRemoveV7 = get_option('cloudimage_remove_v7', false);
            $v7 = '';
            if (!$ciRemoveV7) {
                $v7 = 'v7/';
            }

            $ciURLSignature = get_option('cloudimage_url_signature', '');
//            $ci_sign = sha1($ciURLSignature . $url);

            $ciUrl = 'https://' . $token_cname . '.cloudimg.io/' . $v7;
            if (strpos($token_cname, '.')) {
                $ciUrl = 'https://' . $token_cname . '/' . $v7;
            }

            $ciImageQuality = get_option('cloudimage_image_quality', '90');
            $ciCustomLibrary = get_option('cloudimage_custom_library', '');
            $ciPreventImageUpsize = get_option('cloudimage_prevent_image_upsize', true);

            $currentDomain = home_url();

            if ($token_cname != '') {
                if (!function_exists('is_plugin_active')) {
                    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                }

                if (is_plugin_active('woocommerce/woocommerce.php')) {
                    if (stripos($content, 'data-thumb') !== false) {
                        $dom = new DOMDocument();
                        $dom->loadHTML($content);
                        $div_tags = $dom->getElementsByTagName('div');
                        foreach ($div_tags as $div) {
                            if ($div->hasAttribute('data-thumb')) {
                                $url = $div->getAttribute('data-thumb');
                                if ($ciURLSignature != '' && $standard_mode) {
                                    $ci_sign = sha1($ciURLSignature . $url);
                                    $position = strpos($url, "?");
                                    if ($position !== false) {
                                        $url = $url . '&ci_sign=' . $ci_sign;
                                    } else {
                                        $url = $url . '?ci_sign=' . $ci_sign;
                                    }
                                }
                                $div->setAttribute('data-thumb', $ciUrl . $url);
                            }
                        }
                        $content = $dom->saveHTML();
                    }
                }

                if (stripos($content, '<img') !== false) {
                    $dom = new \DOMDocument();
                    $useErrors = libxml_use_internal_errors(true);
                    $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
                    libxml_use_internal_errors($useErrors);
                    $dom->preserveWhiteSpace = false;

                    $quality = '?q=' . $ciImageQuality;

                    foreach ($dom->getElementsByTagName('img') as $element) {
                        if ($ignoreSvg && strtolower(pathinfo($element->getAttribute('src'), PATHINFO_EXTENSION)) === 'svg') {
                            continue;
                        }

                        $checkClass = false;
                        $checkFile = false;
                        if ($element->hasAttribute('src')) {
                            if (count($skipClassesArray) > 0) {
                                foreach ($skipClassesArray as $skipClass) {
                                    if (strpos($element->getAttribute('class'), $skipClass) !== false) {
                                        $checkClass = true;
                                        break;
                                    }
                                }
                            }

                            if (count($skipFilesArray) > 0) {
                                foreach ($skipFilesArray as $skipFile) {
                                    $tmpSrc = $element->getAttribute('src');
                                    $arrPath = explode('/', $tmpSrc);
                                    $filename = end($arrPath);
                                    if (strpos($filename, '.' . $skipFile) !== false) {
                                        $checkFile = true;
                                        break;
                                    }
                                }
                            }
                        }

                        if ($checkClass) {
                            continue;
                        }

                        if ($checkFile) {
                            continue;
                        }

                        if ($element->hasAttribute('src') && strpos($element->getAttribute('src'), '.cloudimg.io') !== false) {
                            continue;
                        }

                        if (!$standard_mode) {
                            if ($element->hasAttribute('src')) {
                                $src = $element->getAttribute('src');
                                if (!$element->hasAttribute('ci-src')) {
                                    $src = $src . $quality;
                                    $element->setAttribute('ci-src', $src);
                                    $element->removeAttribute('src');
                                }
                            } else {
                                if ($element->hasAttribute('srcset')) {
                                    $srcset = $element->getAttribute('srcset');
                                    $srcsetArray = explode(' ', $srcset);
                                    $src = $srcsetArray[0];

                                    $src = $src . $quality;
                                    $element->setAttribute('ci-src', $src);
                                }
                            }

                            if (isset($src)) {
                                if (strpos($src, 'http://') === false && strpos($src, 'https://') === false) {
                                    $src = $currentDomain . $src;
                                }
                                $src = $src . $quality;

                                if ($element->hasAttribute('data-src')) {
                                    $element->setAttribute('data-src', $ciUrl . $src);
                                }

                                if ($element->hasAttribute('data-large_image')) {
                                    $element->setAttribute('data-large_image', $ciUrl . $src);
                                }
                            }

                            if ($element->hasAttribute('srcset')) {
                                $element->removeAttribute('srcset');
                            }
                        } else {
                            // standard mode
                            if (!$element->hasAttribute('src')) {
                                $src = '';
                                if ($element->hasAttribute('srcset')) {
                                    $srcset = $element->getAttribute('srcset');
                                    $srcsetArray = explode(' ', $srcset);
                                    $src = $srcsetArray[0];
                                }
                            } else {
                                $src = $element->getAttribute('src');
                            }

                            if (strpos($src, 'http://') === false && strpos($src, 'https://') === false) {
                                $src = $currentDomain . $src;
                            }

                            $buildUrl = self::buildUrl($src, $ciImageQuality, $ciCustomLibrary, $ciPreventImageUpsize);
                            if ($ciURLSignature != '') {
                                $ci_sign = sha1($ciURLSignature . $buildUrl);
                                $src = $buildUrl . '&ci_sign=' . $ci_sign;
                            }
                            $element->setAttribute('src', $ciUrl . $src);

                            if (!$enable_srcset) {
                                if ($element->hasAttribute('srcset')) {
                                    $element->removeAttribute('srcset');
                                }
                            } else {
                                $newSrcset = self::buildNewSrcset($srcset_widths, $ciUrl, $buildUrl, $ciURLSignature);
                                $element->setAttribute('srcset', $newSrcset);
                            }

                            if ($element->hasAttribute('data-src')) {
                                $element->setAttribute('data-src', $ciUrl . $src);
                            }

                            if ($element->hasAttribute('data-large_image')) {
                                $element->setAttribute('data-large_image', $ciUrl . $src);
                            }
                        }
                    }

                    $content = $dom->saveHTML($dom->documentElement);
                    $content = str_replace('https%3A', 'https:', $content);
                    $content = str_replace('http%3A', 'http:', $content);
                }
            }
        }
        return $content;
    }

    public static function cloudimage_add_ci_src_tags_to_images($attr, $attachment, $size)
    {
        if (!is_admin()) {
            $token_cname = get_option('cloudimage_token_cname', '');
            $standard_mode = get_option('cloudimage_standard_mode', false);
            $ciImageQuality = get_option('cloudimage_image_quality', '90');
            $ciCustomLibrary = get_option('cloudimage_custom_library', '');
            $ciPreventImageUpsize = get_option('cloudimage_prevent_image_upsize', true);
            $ciURLSignature = get_option('cloudimage_url_signature', '');

            $checkClass = false;
            $skip_classes = get_option('cloudimage_skip_classes', '');
            $skipClassesArray = [];
            if ($skip_classes != '') {
                $skipClassesArray = explode(',', $skip_classes);
            }
            foreach ($skipClassesArray as $skipClass) {
                if (strpos($attr['class'], $skipClass) !== false) {
                    $checkClass = true;
                }
            }

            $checkFile = false;
            $skip_files = get_option('cloudimage_skip_files', '');
            $skipFilesArray = [];
            if ($skip_files != '') {
                $skipFilesArray = explode(',', $skip_files);
            }
            if (count($skipFilesArray) > 0) {
                foreach ($skipFilesArray as $skipFile) {
                    $tmpSrc = $attr['src'];
                    $arrPath = explode('/', $tmpSrc);
                    $filename = end($arrPath);
                    if (strpos($filename, '.' . $skipFile) !== false) {
                        $checkFile = true;
                        break;
                    }
                }
            }

            if ($token_cname != '' && !$checkClass && !$checkFile) {
                $ignoreSvg = get_option('cloudimage_ignore_svg', false);
                if (isset($attr['src'])) {
                    if ($ignoreSvg && strtolower(pathinfo($attr['src'], PATHINFO_EXTENSION)) === 'svg') {
                        return $attr;
                    }

                    if (!$standard_mode) {
                        $attr['ci-src'] = $attr['src'] . '?q=' . $ciImageQuality;
                    } else {
                        $src = self::buildUrl($attr['src'], $ciImageQuality, $ciCustomLibrary, $ciPreventImageUpsize);
                        if ($ciURLSignature != '') {
                            $ci_sign = sha1($ciURLSignature . $src);
                            $src = $src . '&ci_sign=' . $ci_sign;
                        }
                        $attr['src'] = $src;
                    }
                }
            }
        }
        return $attr;
    }

    private static function buildNewSrcset(string $srcset_widths, string $ciUrl, string $url, string $ciURLSignature = ''): string
    {
        $customSizeArray = explode(',', $srcset_widths);
        $newSrcset = '';
        foreach($customSizeArray as $item) {
            if ($ciURLSignature != '') {
                $ci_sign = sha1($ciURLSignature . $url . '&w=' . $item);
                $buildNewSrcset = $ciUrl . $url . '&w=' . $item . '&ci_sign=' . $ci_sign;
            } else {
                $buildNewSrcset = $ciUrl . $url . '&w=' . $item;
            }

            if ($newSrcset == '') {
                $newSrcset = $buildNewSrcset . ' ' . $item . 'w';
            } else {
                $newSrcset .= ', ' . $buildNewSrcset . ' ' . $item . 'w';
            }
        }
        return $newSrcset;
    }

    private static function buildUrl(string $url, int $ciImageQuality, string $ciCustomLibrary, bool $ciPreventImageUpsize): string
    {
        if ($ciImageQuality != '' && $ciImageQuality <= 100) {
            $quality = '?q=' . $ciImageQuality;
            if (strpos($url, '?')) {
                $quality = '&q=' . $ciImageQuality;
            }
            $url = $url . $quality;
        }

        if ($ciCustomLibrary != '') {
            if (strpos($url, '?')) {
                $url = $url . '&' . $ciCustomLibrary;
            } else {
                $url = $url . '?' . $ciCustomLibrary;
            }
        }

        if ($ciPreventImageUpsize) {
            if (strpos($url, '?')) {
                $url = $url . '&org_if_sml=1';
            } else {
                $url = $url . '?org_if_sml=1';
            }
        }

        return $url;
    }

    /*
    // Override the content using hook the_content
    public static function cloudimage_override_img_before_rendered($content) {
        if (!is_admin()) {
            $token_cname = get_option('cloudimage_token_cname', '');
            $standard_mode = get_option('cloudimage_standard_mode', false);
            $skip_classes = get_option('cloudimage_skip_classes', '');
            $ignoreSvg = get_option('cloudimage_ignore_svg', false);
            $skipClassesArray = [];
            if ($skip_classes != '') {
                $skipClassesArray = explode(',', $skip_classes);
            }

            if ($token_cname != '' && !$standard_mode) {
                if (stripos($content, '<img') !== false) {
                    $dom = new \DOMDocument();
                    $useErrors = libxml_use_internal_errors(true);
                    $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
                    libxml_use_internal_errors($useErrors);
                    $dom->preserveWhiteSpace = false;

                    foreach ($dom->getElementsByTagName('img') as $element) {
                        if ($ignoreSvg && strtolower(pathinfo($element->getAttribute('src'), PATHINFO_EXTENSION)) === 'svg') {
                            continue;
                        }

                        $checkClass = false;
                        if ($element->hasAttribute('src')) {
                            foreach ($skipClassesArray as $skipClass) {
                                if (strpos($element->getAttribute('class'), $skipClass) !== false) {
                                    $checkClass = true;
                                }
                            }
                        }

                        if (!$checkClass) {
                            if ($element->hasAttribute('src')) {
                                $element->setAttribute('ci-src', $element->getAttribute('src'));
                                $element->removeAttribute('src');

                                if ($element->hasAttribute('srcset')) {
                                    $element->removeAttribute('srcset');
                                }
                            }
                        }
                    }

                    $content = $dom->saveHTML($dom->documentElement);
                    $content = str_ireplace(['<html><body>', '</body></html>'], '', $content);
                }
            }
        }
        return $content;
    }
    */
}
add_action('plugins_loaded', ['Cloudimage', 'init_actions']);