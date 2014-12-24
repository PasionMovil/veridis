<?php
/**
 */
define('TAB_TITLE', __("Tab", "meganews"));
class WPBakeryShortCode_VC_Tab extends WPBakeryShortCode_VC_Column {
    protected $predefined_atts = array(
                        'tab_id' => TAB_TITLE,
                        'title' => ''
                        );
    public function customAdminBlockParams() {
        return ' id="tab-'.$this->atts['tab_id'] .'"';
    }
    public function mainHtmlBlockParams($width, $i) {
        return 'data-element_type="'.$this->settings["base"].'" class="wpb_'.$this->settings['base'].' wpb_sortable wpb_content_holder"'.$this->customAdminBlockParams();
    }
    public function containerHtmlBlockParams($width, $i) {
        return 'class="wpb_column_container vc_container_for_children"';
    }
    public function getColumnControls($controls, $extended_css = '') {
        $controls_start = '<div class="controls controls_column'.(!empty($extended_css) ? " {$extended_css}" : '').'">';
        $controls_end = '</div>';
        
        if ($extended_css=='bottom-controls') $control_title = sprintf(__('Append to this %s', 'meganews'), strtolower($this->settings('name')));
        else $control_title = sprintf(__('Prepend to this %s', 'meganews'), strtolower($this->settings('name')));
        
        $controls_add = ' <a class="column_add" href="#" title="'.$control_title.'"></a>';
        $controls_edit = ' <a class="column_edit" href="#" title="'.sprintf(__('Edit this %s', 'meganews'), strtolower($this->settings('name'))).'"></a>';
        $controls_clone = '<a class="column_clone" href="#" title="'.sprintf(__('Clone this %s', 'meganews'), strtolower($this->settings('name'))).'"></a>';

        $controls_delete = '<a class="column_delete" href="#" title="'.sprintf(__('Delete this %s', 'meganews'), strtolower($this->settings('name'))).'"></a>';
        return $controls_start .  $controls_add . $controls_edit . $controls_clone . $controls_delete . $controls_end;
    }
}



function tab_id_settings_field($settings, $value) {
    $dependency = vc_generate_dependencies_attributes($settings);
    return '<div class="my_param_block">'
        .'<input name="'.$settings['param_name']
        .'" class="wpb_vc_param_value wpb-textinput '
        .$settings['param_name'].' '.$settings['type'].'_field" type="hidden" value="'
        .$value.'" ' . $dependency . ' />'
        .'<label>'.$value.'</label>'
        .'</div>';
    // TODO: Add data-js-function to documentation
}

add_shortcode_param('tab_id', 'tab_id_settings_field');

class WPBakeryShortCode_VC_Tabs extends WPBakeryShortCode {

    public function __construct($settings) {
        parent::__construct($settings);
        // WPBakeryVisualComposer::getInstance()->addShortCode( array( 'base' => 'vc_tab' ) );
    }

    public function contentAdmin($atts, $content = null) {
        $width = $custom_markup = '';
        $shortcode_attributes = array('width' => '1/1');
        foreach ( $this->settings['params'] as $param ) {
            if ( $param['param_name'] != 'content' ) {
                //$shortcode_attributes[$param['param_name']] = $param['value'];
                if (isset($param['value']) && is_string($param['value']) ) {
                    $shortcode_attributes[$param['param_name']] = __($param['value'], "meganews");
                } elseif(isset($param['value'])) {
                    $shortcode_attributes[$param['param_name']] = $param['value'];
                }
            } else if ( $param['param_name'] == 'content' && $content == NULL ) {
                //$content = $param['value'];
                $content = __($param['value'], "meganews");
            }
        }
        extract(shortcode_atts(
            $shortcode_attributes
            , $atts));

        // Extract tab titles

        preg_match_all( '/vc_tab title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );
        /*
        $tab_titles = array();
        if ( isset($matches[1]) ) { $tab_titles = $matches[1]; }
        */
        $output = '';
        $tab_titles = array();

        if ( isset($matches[0]) ) { $tab_titles = $matches[0]; }
        $tmp = '';
        if( count($tab_titles) ) {
            $tmp .= '<ul class="clearfix tabs_controls">';
            foreach ( $tab_titles as $tab ) {
                preg_match('/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
                if(isset($tab_matches[1][0])) {
                    $tmp .= '<li><a href="#tab-'. (isset($tab_matches[3][0]) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) .'">' . $tab_matches[1][0] . '</a></li>';

                }
            }
            $tmp .= '</ul>'."\n";
        } else {
            $output .= do_shortcode( $content );
        }



        /*
        if ( count($tab_titles) ) {
            $tmp .= '<ul class="clearfix">';
            foreach ( $tab_titles as $tab ) {
                $tmp .= '<li><a href="#tab-'. sanitize_title( $tab[0] ) .'">' . $tab[0] . '</a></li>';
            }
            $tmp .= '</ul>';
        } else {
            $output .= do_shortcode( $content );
        }
        */
        $elem = $this->getElementHolder($width);

        $iner = '';
        foreach ($this->settings['params'] as $param) {
            $custom_markup = '';
            $param_value = isset($$param['param_name']) ? $$param['param_name'] : '';
            if ( is_array($param_value)) {
                // Get first element from the array
                reset($param_value);
                $first_key = key($param_value);
                $param_value = $param_value[$first_key];
            }
            $iner .= $this->singleParamHtmlHolder($param, $param_value);
        }
        //$elem = str_ireplace('%wpb_element_content%', $iner, $elem);

        if ( isset($this->settings["custom_markup"]) &&$this->settings["custom_markup"] != '' ) {
            if ( $content != '' ) {
                $custom_markup = str_ireplace("%content%", $tmp.$content, $this->settings["custom_markup"]);
            } else if ( $content == '' && isset($this->settings["default_content_in_template"]) && $this->settings["default_content_in_template"] != '' ) {
                $custom_markup = str_ireplace("%content%",$this->settings["default_content_in_template"],$this->settings["custom_markup"]);
            } else {
                $custom_markup =  str_ireplace("%content%", '', $this->settings["custom_markup"]);
            }
            //$output .= do_shortcode($this->settings["custom_markup"]);
            $iner .= do_shortcode($custom_markup);
        }
        $elem = str_ireplace('%wpb_element_content%', $iner, $elem);
        $output = $elem;

        return $output;
    }

    public function getTabTemplate() {
        return '<div class="wpb_template">'.do_shortcode('[vc_tab title="Tab" tab_id=""][/vc_tab]').'</div>';
    }


}