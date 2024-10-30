<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cloudimage.io
 * @since      1.0.0
 *
 * @package    Cloudimage
 * @subpackage Cloudimage/admin/partials
 */
?>

<?php
// Grab all options
$options = get_option($this->plugin_name);

$domain = $options['cloudimage_domain'];
$removes_v7 = isset($options['cloudimage_removes_v7'])
    ? $options['cloudimage_removes_v7'] : 1; // 0 = enabling, 1 = disabling
$use_js_powered_mode = isset($options['cloudimage_use_js_powered_mode'])
    ? $options['cloudimage_use_js_powered_mode'] : 0;
$use_for_logged_in_users = isset($options['cloudimage_use_for_logged_in_users'])
    ? $options['cloudimage_use_for_logged_in_users'] : 1;
$use_new_version = isset($options['cloudimage_new_version'])
    ? $options['cloudimage_new_version'] : 0;
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="cloudimg-plugin-container">
    <div class="cloudimg-lower">
        <div class="cloudimg-box">
            <div class="content-container">
                <div class="top_part">
                   
                    <div class="a_logo">
                        <a target="_blank" href="http://cloudimg.io/">
                              <img src=" <?php echo plugin_dir_url(__FILE__); ?>../images/logo_new_cloudimage.png"
                                 alt="cloudimage logo">
                        </a>
                    </div>
                    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
                </div>


                <div class="intro_text">
                    <p class="big_p">
                        <?php echo "The Cloudimage Plugin will resize, compress and optimize all of your WordPress visuals, and then deliver responsive images lightning-fast over CDN all around the world."; ?>
                    </p>
                    <p class="big_p">
                        <?php echo "To start enjoying faster images, simply add your Cloudimage token below, and the plugin will start working its magic."; ?>
                    </p>
                    <p class="big_p">
                        <?php echo "How to start using Cloudimage? Sign up for a Cloudimage account to obtain your token. You can enjoy our entry-tier subscription for free. Sign-up will take a few seconds."; ?>
                    </p>
                    <p class="big_p">
                        <a href="https://www.cloudimage.io/en/registration?utm_source=WordPress&utm_medium=referral&utm_campaign=cloudimage_wordpress_plugins_admin_panel&utm_content=organic_plugin_profile"
                           target="_blank"><?php echo "Get your Cloudimage token here"; ?></a>
                    </p>
                </div>
            </div>
        </div>

        <form method="post" name="cloudimg_settings" action="options.php" class="cloudimg-boxes">
            <?php
            settings_fields($this->plugin_name);
            do_settings_sections($this->plugin_name);
            ?>
            <div class="cloudimg-box">
                <div class="content-container">
                    <h1><?php echo "Configuration"; ?></h1>
                    <table class="form-table">
                        <tbody>
                            <!-- Use new version -->
                            <tr id="js-powered-section">
                                <th scope="row" class="titledesc">
                                    <label for="<?php echo esc_attr($this->plugin_name); ?>-use_new_version">
                                        <?php echo "Use new version"; ?>
                                        <div class="tooltip">?
                                            <span class="tooltiptext"><?php echo "If enabled, you will use the new version of Cloudimage."; ?></span>
                                        </div>
                                    </label>
                                </th>
                                <td class="forminp forminp-text general-switch">
                                    <label class="switch">
                                        <input type="hidden" name="cloudimage_new_version"
                                               value="<?php if ($use_new_version) { echo 1; } else { echo 0; } ?>"
                                               id="cloudimage_new_version">
                                        <input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-new_version"
                                               name="<?php echo esc_attr($this->plugin_name); ?>[new_version]" <?php checked($use_new_version, 1); ?> >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>

                            <!-- domain -->
                            <tr>
                                <th scope="row" class="titledesc">
                                    <label for="<?php echo esc_attr($this->plugin_name); ?>-domain" class="cloudimage-domain">
                                        <?php echo "Cloudimage token or custom domain: "; ?>
                                        <div class="tooltip">?
                                            <span class="tooltiptext"><?php echo "Cloudimage token to link your account and be able to process optimizations according to your settings. Can be found in your Cloudimage admin panel. (CNAME)"; ?></span>
                                        </div>
                                    </label>
                                </th>
                                <td class="forminp forminp-text">
                                    <input type="text" id="<?php echo esc_attr($this->plugin_name); ?>-domain" placeholder="my-token"
                                           name="<?php echo esc_attr($this->plugin_name); ?>[domain]"
                                           class="widefat"
                                           value="<?php if (!empty($domain)) echo esc_textarea($domain); ?>">
                                    <div class="cloudimage__description">
                                        <?php echo "Enter token: "; ?>
                                        <code><?php echo "for example azbxuwxXXX or img.acme.com"; ?></code>
                                    </div>
                                </td>
                            </tr>
                         
                            <tr>
                                <td colspan="2">
                                    <span class="cloudimage-demo">
                                        <?php echo 'By default, the plugin will resize all images and deliver them over the Cloudimage CDN. Your Theme\'s Wordpress native support for <i>srcset</i> will continue to be used for delivering responsive images.<br><br>Cloudimage offers a powerful alternative for enabling responsive images using the <a href="https://scaleflex.github.io/js-cloudimage-responsive/" target="_blank">Cloudimage Responsive Images JS plugin</a> below:'; ?>
                                    </span>
                                </td>
                            </tr>

                            <!-- Use JS powered mode -->
                            <tr id="js-powered-section">
                                <th scope="row" class="titledesc">
                                    <label for="<?php echo esc_attr($this->plugin_name); ?>-use_js_powered_mode">
                                        <?php echo "Javascript mode (Please check all options in Advance settings)"; ?>
                                        <div class="tooltip">?
                                            <span class="tooltiptext"><?php echo "JavaScript Responsive Plugin will process additional optimizations in the following order: make images responsive, add lazy-loading, and finally add progressive loading effect."; ?></span>
                                        </div>
                                    </label>
                                </th>
                                <td class="forminp forminp-text general-switch">
                                    <label class="switch">
                                        <input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-use_js_powered_mode"
                                               name="<?php echo esc_attr($this->plugin_name); ?>[use_js_powered_mode]" <?php checked($use_js_powered_mode, 1); ?> >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <!-- Remove v7 -->
                            <tr id="remove-v7-section">
                                <th scope="row" class="titledesc">
                                    <label for="<?php echo esc_attr($this->plugin_name); ?>-removes_v7">
                                        <?php echo 'Version-less URL format (remove "v7" in URL for recent tokens)'; ?>
                                        <div class="tooltip">?
                                            <span class="tooltiptext"><?php echo "Switch ON for tokens created after 01.10.2021. OFF for tokens created before. This option will be automatically switched at save, as relevant. You will be asked to confirm the setup in case the automated setting process fails (eg. network issue at the time of test)."; ?></span>
                                        </div>
                                    </label>
                                </th>
                                <td class="forminp forminp-text general-switch">
                                    <label class="switch">
                                        <input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-removes_v7"
                                               name="<?php echo esc_attr($this->plugin_name); ?>[removes_v7]" <?php checked($removes_v7, 1); ?> >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>

                            <!-- Use if user is logged in -->
                            <tr id="use-for-logged-in-users">
                                <th scope="row" class="titledesc">
                                    <label for="<?php echo esc_attr($this->plugin_name); ?>-use_for_logged_in_users">
                                        <?php echo "Use when logged in (Recommended)"; ?>
                                        <div class="tooltip">?
                                            <span class="tooltiptext"><?php echo "When ON, the Cloudimage processing and CDN delivery will be activated at all times, while when OFF the image delivery will be without acceleration for any user logged in the Wordpress admin (including in other tabs), and as such will avoid using CDN bandwidth allowance during tests."; ?></span>
                                        </div>
                                    </label>
                                </th>
                                <td class="forminp forminp-text general-switch">
                                    <label class="switch">
                                        <input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-use_for_logged_in_users"
                                                name="<?php echo esc_attr($this->plugin_name); ?>[use_for_logged_in_users]"<?php echo  checked($use_for_logged_in_users ==0 ? $use_for_logged_in_users:1);?>>
                                        
                                        <span class="slider round"  /span>
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="warning-wrapper">
                        <p><?php echo "We recommend checking all pages, after turning on Cloudimage JavaScript Plugin, especially on JavaScript-heavy themes. Cloudimage plugin filters all the HTML content on your website. For that reason, it is a good idea to use a caching plugin for the changes to be cached."; ?></p>
                        <p><?php echo "Please check your website in Incognito mode of the browser. We disabled image optimization when the current user is logged to WP-admin to save CDN traffic."; ?></p>
                    </div>

                    <div class="warning-wrapper">
                        <p class="v7-check-processing" style="display:none;">
                            <?php echo "The v7 checking process is currently ongoing. Please try not to press 'Save' until this notice disappears"; ?>
                        </p>
                    </div>

                    <div class="warning-wrapper">
                        <p class="v7-check-notices" style="display:none;"></p>
                    </div>

                    <?php submit_button("Save all changes", ['primary', 'large', 'cloudimage-save'], 'submit', true); ?>
                    <h4>
                        <a href="?page=cloudimage-advanced" class="cloudimage-link">
                            <strong>
                                <?php echo "Open Advanced settings"; ?>
                            </strong>
                        </a>
                    </h4>
                </div>
            </div>

            <div class="cloudimg-box">
                <h4>
                    <?php echo "Notes about compatibility: The current version of the plugin optimizes all images included in the final HTML, generated from every theme or plugin. It will not optimize images in the external CSS files (background-image properties for example)."; ?>
                </h4>
            </div>

            <br>

            <div class="cloudimg-box">
                <h4>
                    <?php echo "To your Cloudimage administration panel for all configuration options:"; ?>
                    <a href="https://www.cloudimage.io/en/login" class="cloudimage-link" target="_blank">
                        <?php echo "Cloudimage Admin "; ?>
                    </a>
                </h4>
            </div>
        </form>
    </div>
</div>

<script>
    jQuery(document).ready(function () {
        //Variables initialization
        var cloudimage_use_js_powered_mode = jQuery('#cloudimage-use_js_powered_mode');
        var cloudimage_current_mode = jQuery('#cloudimage-current-mode');
        var cloudimage_new_version = jQuery('#cloudimage-new_version');

        //Check if JavaScript is enabled to display lazy loading section
        if (cloudimage_use_js_powered_mode.is(':checked')) {
            // cloudimage_current_mode.text("JS Powered");
        } else {
            // cloudimage_current_mode.text("PHP Powered");
        }

        //Attach event to change of Cloudimage use resposnive JS checkbox
        cloudimage_use_js_powered_mode.change(function () {
            if (this.checked) {
                //If checked - show additional table row with checkbox
                // cloudimage_current_mode.text("JS Powered");

            } else {
                //If turned off - hide the additional table row and unmark the checkbox
                // cloudimage_current_mode.text("PHP Powered");
            }
        });

        cloudimage_new_version.change(function () {
            if (this.checked) {
                //If checked - show additional table row with checkbox
                // cloudimage_current_mode.text("JS Powered");
                jQuery('#cloudimage_new_version').val(1);
            } else {
                //If turned off - hide the additional table row and unmark the checkbox
                // cloudimage_current_mode.text("PHP Powered");
                jQuery('#cloudimage_new_version').val(0);
            }
        });

    });
</script>
