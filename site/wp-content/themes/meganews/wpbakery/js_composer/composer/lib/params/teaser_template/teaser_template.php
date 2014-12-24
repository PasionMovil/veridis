<?php
/**
 * Block template to realize ability to choose custom template
 *
 */

function vc_teaser_template_form_field($settings, $value) {
    $teaser_templates = VcTeaserTemplates::getInstance();
    $output = '<select name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-input wpb-select '.$settings['param_name'].' '.$settings['type'].'">';
    $templates  = $teaser_templates->templates();
    foreach ( $templates as $key => $params ) {
        $title = is_array($params) && !empty($params['title']) ? $params['title'] : $params;
        $selected = $key == $value ? ' selected="selected"' : '';
        $output .= '<option class="'.$key.'" value="'.$key.'"'.$selected.'>'.$title.'</option>';
    }
    $output .= '</select>';
    return $output;
}

class VcTeaserTemplates {
    protected $templates;
    public function __construct() {
        $this->templates = array(
            "title_thumbnail_text" => __("Title + Thumbnail + Text", "meganews"),
            "thumbnail_title_text" => __("Thumbnail + Title + Text", "meganews"),
            "thumbnail_text" => __("Thumbnail + Text", "meganews"),
            "thumbnail_title" => __("Thumbnail + Title", "meganews"),
            "thumbnail" => __("Thumbnail only", "meganews"),
            "title_text" =>  __("Title + Text", "meganews"));
    }

    public static function getInstance() {
        static $instance=null;
        if ($instance === null)
            $instance = new VcTeaserTemplates();
        return $instance;
    }

    public function getTemplatePath($key) {
        $template = isset($this->templates[$key]) ? $this->templates[$key] : false;
        if(!$template) return false;
        // Check template path in shortcode's mapping settings
        if(is_array($template) && isset($template['path']) && !empty($template['path'])) return $template['path'];

        // Check template in theme directory
        $user_template = WPBakeryVisualComposer::getUserTemplate($this->getFileName($key));
        if(is_file($user_template)) {
            return $this->setTemplate($key, $user_template);
        }
        // Check default place
        $default_dir = WPBakeryVisualComposer::defaultTemplatesDIR();

        if(is_file($default_dir.$this->getFilename($key))) {
            return $this->setTemplate($key, $default_dir.$this->getFileName($key));
        }
    }
    public function templates() {
        return $this->templates;
    }

    protected function setTemplate($key, $template) {

        if(!isset($this->templates[$key])) $this->templates[$key] = array('title' => $key, 'path' => '');
        if(!is_array($this->templates[$key])) $this->templates[$key] = array('title' => $this->templates[$key], 'path' => '');
        $this->templates[$key]['path'] = $template;
        return $this->templates[$key]['path'];
    }
    protected function getFilename($key) {
        return 'teaser/'.$key.'.php';
    }
    public function add($key, $title, $path = '') {
        if(isset($this->templates[$key])) return false;
        $this->templates[$key] = array('title' => $title, 'path' => $path);
        return $this->templates[$key];
    }
    public function remove($key) {
        if(!isset($this->templates[$key])) return false;
        unset($this->templates[$key]);
        return $key;
    }
    public function change($key, $title, $path = '') {
        if(!isset($this->templates[$key])) return false;
        $this->templates[$key] = array('title' => $title, 'path' => $path);
        return $this->templates[$key];
    }
}