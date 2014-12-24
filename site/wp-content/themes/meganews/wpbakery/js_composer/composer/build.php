<?php
/**
 * WPBakery Visual Composer build plugin
 *
 * @package WPBakeryVisualComposer
 *
 */
if (!defined('ABSPATH')) die('-1');
if (!class_exists('WPBakeryVisualComposerSetup')) {
class WPBakeryVisualComposerSetup extends WPBakeryVisualComposerAbstract {
    protected $composer;
    protected $url = 'http://bit.ly/vcomposer';
    public function __construct() {
    }

    public function init($settings) {
        parent::init($settings);
        $this->composer = WPBakeryVisualComposer::getInstance();

        $this->composer->createColumnShortCode(); // Refactored
        $this->addAction('init', 'setUp');
    }
    
    public function setUp() {
        global $vc_as_theme;
        //  old version: if ( preg_match('/'.preg_quote(VC_THEME_DIR, '/').'/', preg_replace('/\\\/', '/', self::$config['APP_ROOT']) ) )
        if ( $vc_as_theme ) {
            $this->composer->setTheme();
            $this->setUpTheme();
            load_theme_textdomain('js_composer', locate_template(self::$config['APP_DIR'].'locale/'));
        } else {
            $this->composer->setPlugin();
            $this->setUpPlugin();
            load_plugin_textdomain( 'js_composer', false,  self::$config['APP_DIR'].'locale/' );
        }
        if ( function_exists( 'add_theme_support' ) ) {
            add_theme_support( 'post-thumbnails');
        }
        add_post_type_support( 'page', 'excerpt' );
        $this->composer->addFilter('the_excerpt', 'excerptFilter');

    }
    public function addUpgradeMessageLink() {
        echo ' <a href="'.$this->url.'">'.__('Dowload new version', 'meganews').'</a> from CodeCanyon.';
    }
    public function setUpPlugin() {
        if (!is_admin()) {
            $this->addAction('template_redirect', 'frontCss');
            $this->addAction('template_redirect', 'frontJsRegister');
            $this->addAction('wp_enqueue_scripts', 'frontendJsLoad');
            $this->composer->addAction( 'customize_controls_enqueue_scripts', 'customizeControlsFooterScripts', 0);
            $this->addFilter('the_content', 'fixPContent', 11); //If weight is higher then 11 it doesn work... why?
            $this->addFilter('body_class', 'jsComposerBodyClass');

        }
    }

    public function fixPContent($content = null) {
        //$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content );
        $s = array(
                    '/'.preg_quote('</div>', '/').'[\s\n\f]*'.preg_quote('</p>', '/').'/i',
                    '/'.preg_quote('<p>', '/').'[\s\n\f]*'.preg_quote('<div ', '/').'/i',
                    '/'.preg_quote('<p>', '/').'[\s\n\f]*'.preg_quote('<section ', '/').'/i',
                    '/'.preg_quote('</section>', '/').'[\s\n\f]*'.preg_quote('</p>', '/').'/i'
                  );
        $r = array("</div>", "<div ", "<section ", "</section>");
        $content = preg_replace($s, $r, $content);
        return $content;
    }
    public function frontendJsLoad() {
        wp_enqueue_script( 'jquery' );
    }

    public function frontCss() {
        //wp_register_style( 'bootstrap', $this->composer->assetURL( 'bootstrap/css/bootstrap.css' ), false, WPB_VC_VERSION, 'screen' );
        //wp_register_style( 'ui-custom-theme', $this->composer->assetURL( 'css/ui-custom-theme/jquery-ui-' . WPB_JQUERY_UI_VERSION . '.custom.css' ), false, WPB_VC_VERSION, 'screen');
        wp_register_style( 'flexslider', $this->composer->assetURL( 'lib/flexslider/flexslider.css' ), false, WPB_VC_VERSION, 'screen' );
        wp_register_style( 'nivo-slider-css', $this->composer->assetURL( 'lib/nivoslider/nivo-slider.css' ), false, WPB_VC_VERSION, 'screen' );
        wp_register_style( 'nivo-slider-theme', $this->composer->assetURL( 'lib/nivoslider/themes/default/default.css' ), array('nivo-slider-css'), WPB_VC_VERSION, 'screen' );
        wp_register_style( 'prettyphoto', $this->composer->assetURL( 'lib/prettyphoto/css/prettyPhoto.css' ), false, WPB_VC_VERSION, 'screen' );
        wp_register_style( 'isotope-css', $this->composer->assetURL( 'css/isotope.css' ), false, WPB_VC_VERSION, 'screen' );

        $front_css_file = $this->composer->assetURL( 'css/js_composer_front.css' );
        $upload_dir = wp_upload_dir();
        $custom_front_css_file = $upload_dir['basedir'].$this->composer->uploadDir().'/js_composer_front_custom.css';
        if ( WPBakeryVisualComposerSettings::get('use_custom') == '1' && is_file($custom_front_css_file)) {
            $front_css_file = WPBakeryVisualComposerSettings::uploadURL().'/js_composer_front_custom.css';
        }
        wp_register_style( 'js_composer_front', $front_css_file, false, WPB_VC_VERSION, 'screen' );
        if(is_file($upload_dir['basedir'].$this->composer->uploadDir().'/custom.css')) {
            wp_register_style( 'js_composer_custom_css', WPBakeryVisualComposerSettings::uploadURL().'/custom.css', array('js_composer_front'), WPB_VC_VERSION, 'screen' );
        }
    }

    public function frontJsRegister() {
        wp_register_script( 'jquery_ui_tabs_rotate', $this->composer->assetURL( 'lib/jquery-ui-tabs-rotate/jquery-ui-tabs-rotate.js' ), array( 'jquery', 'jquery-ui-tabs' ), WPB_VC_VERSION, true);
        wp_register_script( 'wpb_composer_front_js', $this->composer->assetURL( 'js/js_composer_front.js' ), array( 'jquery' ), WPB_VC_VERSION, true);

        wp_register_script( 'tweet', $this->composer->assetURL( 'lib/jquery.tweet/jquery.tweet.js' ), array( 'jquery' ), WPB_VC_VERSION, true);
        wp_register_script( 'isotope', $this->composer->assetURL( 'lib/isotope/jquery.isotope.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true);
        wp_register_script( 'jcarousellite', $this->composer->assetURL( 'lib/jcarousellite/jcarousellite_1.0.1.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true);

        wp_register_script( 'nivo-slider', $this->composer->assetURL( 'lib/nivoslider/jquery.nivo.slider.pack.js' ), array( 'jquery' ), WPB_VC_VERSION, true);
        wp_register_script( 'flexslider', $this->composer->assetURL( 'lib/flexslider/jquery.flexslider-min.js' ), array( 'jquery' ), WPB_VC_VERSION, true);
        wp_register_script( 'prettyphoto', $this->composer->assetURL( 'lib/prettyphoto/js/jquery.prettyPhoto.js' ), array( 'jquery' ), WPB_VC_VERSION, true);
        wp_register_script( 'waypoints', $this->composer->assetURL( 'lib/jquery-waypoints/waypoints.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true);

        //wp_register_script( 'jcarousellite', $this->composer->assetURL( 'js/jcarousellite_1.0.1.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true);
        //wp_register_script( 'anythingslider', $this->composer->assetURL( 'js/jquery.anythingslider.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true);
    }

    public function setUpTheme() {
        $this->setUpPlugin();
    }


    public function jsComposerBodyClass($classes) {
        $classes[] = 'wpb-js-composer js-comp-ver-'.WPB_VC_VERSION;
        $disable_responsive = WPBakeryVisualComposerSettings::get('not_responsive_css');
        if($disable_responsive!=='1') $classes[] = 'vc_responsive';
        return $classes;
    }
}

/* Setup for admin */

class WPBakeryVisualComposerSetupAdmin extends WPBakeryVisualComposerSetup {
    public function __construct() {
        parent::__construct();
    }

    /**
     * @deprecated
     */
    public function setupNotifications() {
        // New version notification.
        $new_version = get_option('wpb_js_composer_show_new_version_message');
        if($new_version!==false && !empty($new_version) && $new_version != WPB_VC_VERSION) {
            $this->addACtion('admin_notices', 'adminNoticeNewVersion');
        }
    }
    /**
     * @deprecated
     */
    public function adminNoticeNewVersion() {
        $version = get_option('wpb_js_composer_show_new_version_message');
        if(!empty($version))
            echo '<div class="updated"><p>'.sprintf(__('There is a new version of Visual Composer available. <a href="http://bit.ly/vcomposer" target="_blank">Download version <b>%s</b></a>.', 'meganews'), $version).'</p></div>';
    }
    public function showSettingsNotification() {
        $this->composer->settings->showNotification();
    }
    /* Setup plugin composer */

    public function setUpPlugin() {
    	global $current_user;
    	get_currentuserinfo();
    	
        /** @var $settings - get use group access rules */
        $settings = WPBakeryVisualComposerSettings::get('groups_access_rules');

        parent::setUpPlugin();
        $show = true;
        foreach($current_user->roles as $role) {
	        if(isset($settings[$role]['show']) && $settings[$role]['show']==='no') {
		        $show = false;
		        break;
	        }
        }
        
        if ($show) {
            $this->composer->addAction( 'edit_post', 'saveMetaBoxes' );
            $this->composer->addAction( 'wp_ajax_wpb_get_element_backend_html', 'elementBackendHtmlJavascript_callback' );
            $this->composer->addAction( 'wp_ajax_wpb_get_convert_elements_backend_html', 'Convert2NewVersionJavascript_callback' );
            $this->composer->addAction( 'wp_ajax_wpb_get_row_element_backend_html', 'elementRowBackendHtmlJavascript_callback' );
            $this->composer->addAction( 'wp_ajax_wpb_shortcodes_to_visualComposer', 'shortCodesVisualComposerJavascript_callback' );
            $this->composer->addAction( 'wp_ajax_wpb_single_image_src', 'singleImageSrcJavascript_callback' );
            $this->composer->addAction( 'wp_ajax_wpb_gallery_html', 'galleryHTMLJavascript_callback' );
            $this->composer->addAction( 'wp_ajax_wpb_get_loop_suggestion', 'getLoopSuggestionJavascript_callback' );
            $this->composer->addAction( 'wp_ajax_wpb_remove_settings_notification_element_css_class', 'removeSettingsNotificationJavascript_callback' );
            /*
             * Create edit form html
             * @deprecated
             */
            $this->composer->addAction( 'wp_ajax_wpb_show_edit_form', 'showEditFormJavascript_callback' );
            $this->composer->addAction( 'wp_ajax_wpb_get_edit_form', 'getEditFormJavascript_callback' );

            $this->composer->addAction('wp_ajax_wpb_save_template', 'saveTemplateJavascript_callback');
            $this->composer->addAction('wp_ajax_wpb_load_template', 'loadTemplateJavascript_callback');
            $this->composer->addAction('wp_ajax_wpb_load_template_shortcodes', 'loadTemplateShortcodesJavascript_callback');

            $this->composer->addAction('wp_ajax_wpb_delete_template', 'deleteTemplateJavascript_callback');
            $this->composer->addAction('wp_ajax_wpb_get_loop_settings', 'getLoopSettingsJavascript_callback');
            $this->addAction( 'admin_init', 'jsComposerEditPage', 5 );
        }
        // Add specific CSS class by filter
        $this->addFilter('body_class', 'jsComposerBodyClass');
        $this->addFilter( 'get_media_item_args', 'jsForceSend' );

        $this->addAction( 'admin_init', 'composerRedirect' );


        $this->addAction( 'admin_init', 'registerCss' );
        $this->addAction( 'admin_init', 'registerJavascript' );

        $this->addAction( 'admin_menu','composerSettings' );

        $this->addAction( 'admin_print_scripts-post.php', 'editScreen_js' );
        $this->addAction( 'admin_print_scripts-post-new.php', 'editScreen_js' );

        // Upgrade message in plugins list.
        $plugin_file_name = 'js_composer/js_composer.php';
        new wp_auto_update (WPB_VC_VERSION, 'http://wpbakery.com/version/?'.time(), $plugin_file_name);
        $this->addAction('in_plugin_update_message-'.$plugin_file_name, 'addUpgradeMessageLink');
    }

    /*
     * Set up theme filters and actions
     *
     */
    public function setUpTheme() {
        $this->setUpPlugin();
        $this->addAction('admin_init', 'themeScreen_js');
    }


    public function jsForceSend($args) {
        $args['send'] = true;
        return $args;
    }

    public function themeScreen_js() {
        wp_enqueue_script('wpb_js_theme_admin');
    }

    public function editScreen_js() {

        if(in_array(get_post_type(), $this->composer->getPostTypes())) {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_style('farbtastic');
            wp_enqueue_style('ui-custom-theme');
            wp_enqueue_style('isotope-css');
            wp_enqueue_style('animate-css');
            wp_enqueue_style('js_composer');
            wp_enqueue_style('wpb_jscomposer_autosuggest');

            WPBakeryShortCode_Settings::enqueueCss();

            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-droppable');
            wp_enqueue_script('jquery-ui-draggable');
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_script('jquery-ui-autocomplete');

            wp_enqueue_script('farbtastic');

            //MMM wp_enqueue_script('bootstrap-js');
            wp_enqueue_script('isotope');
            wp_enqueue_script('wpb_bootstrap_modals_js');
            wp_enqueue_script('wpb_scrollTo_js');
            wp_enqueue_script('wpb_php_js');
            WPBakeryShortCode_Settings::enqueueJs();
            // js composer js app {{
            // wpb_js_composer_js_sortable
            wp_enqueue_script('wpb_js_composer_js_sortable');
            wp_enqueue_script('wpb_json-js');


            wp_enqueue_script('wpb_js_composer_js_tools');
            wp_enqueue_script('wpb_js_composer_js_storage');
            wp_enqueue_script('wpb_js_composer_js_models');
            wp_enqueue_script('wpb_js_composer_js_view');
            wp_enqueue_script('wpb_js_composer_js_custom_views');

            wp_enqueue_script('wpb_js_composer_js_backbone');
            wp_enqueue_script('wpb_jscomposer_composer_js');
            wp_enqueue_script('wpb_jscomposer_shortcode_js');
            wp_enqueue_script('wpb_jscomposer_modal_js');
            wp_enqueue_script('wpb_jscomposer_templates_js');
            wp_enqueue_script('wpb_jscomposer_stage_js');
            wp_enqueue_script('wpb_jscomposer_layout_js');
            wp_enqueue_script('wpb_jscomposer_row_js');
            wp_enqueue_script('wpb_jscomposer_settings_js');
            wp_enqueue_script('wpb_jscomposer_media_editor_js');
            wp_enqueue_script('wpb_jscomposer_autosuggest_js');
            // }}
            wp_enqueue_script('wpb_js_composer_js');
        }
    }

    public function registerJavascript() {
        wp_register_script('isotope', $this->composer->assetURL( 'lib/isotope/jquery.isotope.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true);
        wp_register_script('wpb_bootstrap_modals_js', $this->composer->assetURL( 'lib/bootstrap_modals/js/bootstrap.min.js' ), array('jquery'), WPB_VC_VERSION, true);
        wp_register_script('wpb_scrollTo_js', $this->composer->assetURL( 'lib/scrollTo/jquery.scrollTo.min.js' ), array('jquery'), WPB_VC_VERSION, true);
        wp_register_script('wpb_php_js', $this->composer->assetURL( 'lib/php.default/php.default.min.js' ), array('jquery'), WPB_VC_VERSION, true);
        wp_register_script('wpb_json-js', $this->composer->assetURL( 'lib/json-js/json2.js' ), false, WPB_VC_VERSION, true);

        wp_register_script('wpb_js_composer_js_tools', $this->composer->assetURL( 'js/backend/composer-tools.js' ), array('jquery', 'backbone', 'wpb_json-js'), WPB_VC_VERSION, true);
        wp_register_script('wpb_js_composer_js_atts', $this->composer->assetURL( 'js/backend/composer-atts.js' ), array('wpb_js_composer_js_tools'), WPB_VC_VERSION, true);
        wp_register_script('wpb_js_composer_js_storage', $this->composer->assetURL( 'js/backend/composer-storage.js' ), array('wpb_js_composer_js_atts'), WPB_VC_VERSION, true);
        wp_register_script('wpb_js_composer_js_models', $this->composer->assetURL( 'js/backend/composer-models.js' ), array('wpb_js_composer_js_storage'), WPB_VC_VERSION, true);
        wp_register_script('wpb_js_composer_js_view', $this->composer->assetURL( 'js/backend/composer-view.js' ), array('wpb_js_composer_js_models'), WPB_VC_VERSION, true);
        wp_register_script('wpb_js_composer_js_custom_views', $this->composer->assetURL( 'js/backend/composer-custom-views.js' ), array('wpb_js_composer_js_view'), WPB_VC_VERSION, true);
        wp_register_script('wpb_jscomposer_media_editor_js', $this->composer->assetURL( 'js/backend/media-editor.js' ), array('wpb_js_composer_js_view'), WPB_VC_VERSION, true);
        wp_register_script('wpb_jscomposer_autosuggest_js', $this->composer->assetURL( 'lib/autosuggest/jquery.autoSuggest.js' ), array('wpb_js_composer_js_view'), WPB_VC_VERSION, true);
        wp_localize_script( 'wpb_js_composer_js_view', 'i18nLocale', array(
            'add_remove_picture' => __( 'Add/remove picture', 'meganews' ),
            'finish_adding_text' => __( 'Finish Adding Images', 'meganews' ),
            'add_image' => __( 'Add Image', 'meganews' ),
            'add_images' => __( 'Add Images', 'meganews' ),
            'main_button_title' => __( 'Visual Composer', 'meganews' ),
            'main_button_title_revert' => __( 'Classic editor', 'meganews' ),
            'please_enter_templates_name' => __('Please enter templates name', 'meganews'),
            'confirm_deleting_template' => __('Confirm deleting "{template_name}" template, press Cancel to leave. This action cannot be undone.', 'meganews'),
            'press_ok_to_delete_section' => __('Press OK to delete section, Cancel to leave', 'meganews'),
            'drag_drop_me_in_column' => __('Drag and drop me in the column', 'meganews'),
            'press_ok_to_delete_tab' => __('Press OK to delete "{tab_name}" tab, Cancel to leave', 'meganews'),
            'slide' => __('Slide', 'meganews'),
            'tab' => __('Tab', 'meganews'),
            'section' => __('Section', 'meganews'),
            'please_enter_new_tab_title' => __('Please enter new tab title', 'meganews'),
            'press_ok_delete_section' => __('Press OK to delete "{tab_name}" section, Cancel to leave', 'meganews'),
            'section_default_title' => __('Section', 'meganews'),
            'please_enter_section_title' => __('Please enter new section title', 'meganews'),
            'error_please_try_again' => __('Error. Please try again.', 'meganews'),
            'if_close_data_lost' => __('If you close this window all shortcode settings will be lost. Close this window?', 'meganews'),
            'header_select_element_type' => __('Select element type', 'meganews'),
            'header_media_gallery' => __('Media gallery', 'meganews'),
            'header_element_settings' => __('Element settings', 'meganews'),
            'add_tab' => __('Add tab', 'meganews'),
            'are_you_sure_convert_to_new_version' => __('Are you sure you want to convert to new version?', 'meganews'),
            'loading' => __('Loading...', 'meganews'),
            // Media editor
            'set_image' => __('Set Image', 'meganews'),
            'are_you_sure_reset_css_classes' => __('Are you sure taht you want to remove all your data?', 'meganews'),
            'loop_frame_title' => __('Loop settings', 'meganews'),
            'enter_custom_layout' => __('Enter custom layout for your row:', 'meganews'),
            'wrong_cells_layout' => __('Wrong row layout format! Example: 1/2 + 1/2 or span6 + span6.', 'meganews'),
        ) );

        wp_register_script('wpb_js_theme_admin', $this->composer->assetURL( 'js/theme_admin.js' ), array('jquery'), WPB_VC_VERSION, true);
    }

    public function registerCss() {
        //MMM wp_register_style( 'bootstrap', $this->composer->assetURL( 'bootstrap/css/bootstrap.css' ), false, WPB_VC_VERSION, false );
        wp_register_style( 'bootstrap_modals', $this->composer->assetURL( 'lib/bootstrap_modals/css/bootstrap.modals.css' ), false, WPB_VC_VERSION, false );

        wp_register_style( 'ui-custom-theme', $this->composer->assetURL( 'css/ui-custom-theme/jquery-ui-' . WPB_JQUERY_UI_VERSION . '.custom.css' ), false, WPB_VC_VERSION, false );
        wp_register_style( 'isotope-css', $this->composer->assetURL( 'css/isotope.css' ), false, WPB_VC_VERSION, 'screen' );
        wp_register_style( 'animate-css', $this->composer->assetURL( 'css/animate.css' ), false, WPB_VC_VERSION, 'screen' );

        wp_register_style( 'js_composer', $this->composer->assetURL( 'css/js_composer.css' ), array('isotope-css', 'animate-css', 'bootstrap_modals'), WPB_VC_VERSION, false );
        wp_register_style( 'js_composer_settings', $this->composer->assetURL( 'css/js_composer_settings.css' ), false, WPB_VC_VERSION, false );
        wp_register_style( 'wpb_jscomposer_autosuggest', $this->composer->assetURL( 'lib/autosuggest/jquery.autoSuggest.css' ), false, WPB_VC_VERSION, false );

    }
    /* Call to generate main template editor */

    public function jsComposerEditPage() {
        $pt_array = $this->composer->getPostTypes();
        foreach ($pt_array as $pt) {
            add_meta_box( 'wpb_visual_composer', __('Visual Composer', "meganews"), Array($this->composer->getLayout(), 'output'), $pt, 'normal', 'high');
        }
    }

    /* Add option to Settings menu */
    public function composerSettings() {
        if(isset($this->composer->settings)) return $this->composer->settings;
        if ( current_user_can('manage_options') && $this->composer->isPlugin()) {
            $this->composer->settings = new WPBakeryVisualComposerSettings($this->composer);
            $this->composer->settings->init();
        } elseif($this->composer->isTheme() && current_user_can('edit_theme_options')) {
            $this->composer->settings = new WPBakeryVisualComposerSettings($this->composer);
            $this->composer->settings->init();
        } else {
            $this->composer->settings = false;
        }
        /*
         * Soon will be enabled.
         *
        if($this->composer->settings->requireNotification()) {
            $this->addAction('admin_notices', 'showSettingsNotification');

        }
        */
        return $this->composer->settings;
    }



    public function composerRedirect() {
        if ( get_option('wpb_js_composer_do_activation_redirect', false) ) {
            delete_option('wpb_js_composer_do_activation_redirect');
            wp_redirect(admin_url().'options-general.php?page=wpb_vc_settings&build_css=1');
        }
    }
}
}


