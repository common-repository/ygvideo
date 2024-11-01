<?php

/*
  Plugin Name: YGVideo
  Description: YGVideo Embed WordPress Plugin.
  Version: 1.1
  Author: Nikadimas
 */

class YGVideo {

    const EMBED_TYPE_JS = 'js';
    const EMBED_TYPE_IFRAME = 'iframe';
    const BASE_URL = 'https://videoplayer.rrdiscovery.com/get-details/';

    public static $version = '1.0';

    public function __construct() {
        register_activation_hook(__FILE__, array(get_class(), 'plugin_activation'));
        add_action('media_buttons', array(get_class(), 'media_button_wizard'), 11);
        add_action('admin_enqueue_scripts', array(get_class(), 'admin_enqueue_scripts'), 10, 1);

        add_action('admin_menu', array(get_class(), 'plugin_menu'));
        if (!is_admin()) {
            add_shortcode('ygplayer', array(get_class(), 'ygplayer_shortcode'));
        }
    }

    /**
     * Plugin activation handler
     */
    public static function plugin_activation() {
        if (!in_array('curl', get_loaded_extensions())) {
            echo 'cURL is not installed on this server. cURL is required to activate this plugin';
            exit;
        }
    }

    /**
     * Shortcode handler
     * @param array $attributes 
     * @param string $content
     * @return string
     */
    public static function ygplayer_shortcode($attributes, $content = null) {
        $values = shortcode_atts(array(
            'type' => self::EMBED_TYPE_JS,
            'width' => '100%',
            'height' => '100%'
            ), $attributes);

        switch ($values['type']) {
            case self::EMBED_TYPE_IFRAME:
                $content = "<iframe src='$content' frameborder='0' width='{$values['width']}' height='{$values['height']}'></iframe>";
                break;
            case self::EMBED_TYPE_JS:
            default :
                $content = "<script src='$content'></script>";
                break;
        }

        $allowed_html = [
            'script' => [
                'src' => []
            ],
            'iframe' => [
                'src' => [],
                'frameborder' => [],
                'width' => [],
                'height' => []
            ]
        ];
        
        return wp_kses( $content, $allowed_html );
    }

    /**
     * Add plugin media button
     * @return void
     */
    public static function media_button_wizard() {
        add_thickbox();
        $wizhref = admin_url('admin.php?page=ygvideo-wizard') .
            '&random=' . rand(1, 1000) .
            '&TB_iframe=true&width=950&height=800';


        echo self::render('media_button', ['href' => esc_attr($wizhref)]);
    }

    /**
     * Register wizard action
     */
    public static function plugin_menu() {
        add_submenu_page(null, 'YGVideo Wizard', 'YGVideo Wizard', 'edit_posts', 'ygvideo-wizard', array(get_class(), 'wizard'));
    }

    /**
     * Register plugin assets
     * @param string $hook
     * @return void 
     */
    public static function admin_enqueue_scripts($hook) {
        if (is_admin()) {
            wp_enqueue_style('__ygv_admin_css', plugins_url('styles/ygvideo.css', __FILE__), array(), self::$version);
            wp_enqueue_script('__ytprefs_admin__', plugins_url('scripts/ygvideo-admin.js', __FILE__), array('jquery'), self::$version, false);
        }

        if ($hook == 'admin_page_ygvideo-wizard') {
            wp_enqueue_script('__ygv_admin__wizard_script', plugins_url('scripts/ygvideo-wizard.js', __FILE__), array('jquery'), self::$version);

            wp_enqueue_style('__ygv_admin__wizard', plugins_url('styles/ygv-wizard.css', __FILE__), array(), self::$version);
            wp_enqueue_style('__ygv_admin__wizard_bootstrap', plugins_url('styles/bootstrap.min.css', __FILE__), array(), self::$version);
        }
    }

    /**
     * Wizard action
     */
    public static function wizard() {
        $errorMessage = null;
        if (!empty($_POST['media_id'])) {
            $mediaID = filter_input(INPUT_POST, 'media_id', FILTER_SANITIZE_ENCODED);
            $response = wp_remote_get( self::BASE_URL . sanitize_text_field($mediaID));
            if(!is_wp_error( $response )) {
                $body = wp_remote_retrieve_body( $response );
                $data = json_decode($body);
                $data->data->typeList = [
                    self::EMBED_TYPE_JS => 'Javascript Code',
                    self::EMBED_TYPE_IFRAME => 'Iframe Code'
                ];
                
                if (!empty($data->success)) {
                    echo self::render('wizard_preview', (array) $data->data);
                    return;
                }
            }
            
            $errorMessage = 'No results';
        }
        
        echo self::render('wizard_form', ['errorMessage' => $errorMessage]);
    }

    /**
     * Render view file
     * @param string $view
     * @param array $_params_
     * @return type
     * @throws \Exception
     */
    public static function render($view, $_params_ = []) {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        try {
            require YGVIDEO_VIEWS_PATH . $view . '.php';
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
    
}

define('YGVIDEO_VIEWS_PATH', rtrim(dirname(__FILE__), "\\/") . '/views/');

$ygvideo = new YGVideo();
