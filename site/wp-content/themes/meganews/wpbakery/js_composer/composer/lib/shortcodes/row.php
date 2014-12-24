<?php
/**
 * WPBakery Visual Composer row
 *
 * @package WPBakeryVisualComposer
 *
 */

class WPBakeryShortCode_VC_Row extends WPBakeryShortCode {
    protected $predefined_atts = array(
        'el_class' => '',
    );



    /* This returs block controls
   ---------------------------------------------------------- */
    public function getColumnControls($controls, $extended_css = '') {
        global $vc_row_layouts;
        $controls_start = '<div class="controls controls_row clearfix">';
        $controls_end = '</div>';

        $right_part_start = '';//'<div class="controls_right">';
        $right_part_end = '';//'</div>';

        //Create columns
        $controls_center_start = '<span class="vc_row_layouts">';
        $controls_layout = '';
        foreach($vc_row_layouts as $layout) {
            $controls_layout .= '<a class="set_columns '.$layout['icon_class'].'" data-cells="'.$layout['cells'].'" data-cells-mask="'.$layout['mask'].'" title="'.$layout['title'].'"></a> ';
        }
        $controls_layout .= '<br/><a class="set_columns custom_columns" data-cells="custom" data-cells-mask="custom" title="'.__('Custom layout', 'meganews').'">'.__('Custom layout', 'meganews').'</a> ';
        $controls_move = ' <a class="column_move" href="#" title="'.__('Drag row to reorder', 'meganews').'"></a>';
        $controls_delete = '<a class="column_delete" href="#" title="'.__('Delete this row', 'meganews').'"></a>';
        $controls_edit = ' <a class="column_edit" href="#" title="'.__('Edit this row', 'meganews').'"></a>';
        $controls_clone = ' <a class="column_clone" href="#" title="'.__('Clone this row', 'meganews').'"></a>';
        $controls_center_end = '</span>';

        $row_edit_clone_delete = '<span class="vc_row_edit_clone_delete">';
        $row_edit_clone_delete .= $controls_delete . $controls_clone . $controls_edit;
        $row_edit_clone_delete .= '</span>';

        //$column_controls_full =  $controls_start. $controls_move . $controls_center_start . $controls_layout . $controls_delete . $controls_clone . $controls_edit . $controls_center_end . $controls_end;
        $column_controls_full =  $controls_start. $controls_move . $controls_center_start . $controls_layout . $controls_center_end . $row_edit_clone_delete . $controls_end;

        return $column_controls_full;
    }

    public function contentAdmin($atts, $content = null) {
        $width = $el_class = '';
        extract(shortcode_atts($this->predefined_atts, $atts));

        $output = '';

        $column_controls = $this->getColumnControls($this->settings('controls'));

        for ( $i=0; $i < count($width); $i++ ) {
            $output .= '<div'.$this->customAdminBockParams().' data-element_type="'.$this->settings["base"].'" class="wpb_'.$this->settings['base'].' wpb_sortable">';
            $output .= str_replace("%column_size%", 1, $column_controls);
            $output .= '<div class="wpb_element_wrapper">';
            $output .= '<div class="vc_row-fluid wpb_row_container vc_container_for_children">';
            if($content=='' && !empty($this->settings["default_content_in_template"])) {
                $output .= do_shortcode( shortcode_unautop($this->settings["default_content_in_template"]) );
            } else {
                $output .= do_shortcode( shortcode_unautop($content) );

            }
            $output .= '</div>';
            if ( isset($this->settings['params']) ) {
                $inner = '';
                foreach ($this->settings['params'] as $param) {
                    $param_value = isset($$param['param_name']) ? $$param['param_name'] : '';
                    //var_dump($param_value);
                    if ( is_array($param_value)) {
                        // Get first element from the array
                        reset($param_value);
                        $first_key = key($param_value);
                        $param_value = $param_value[$first_key];
                    }
                    $inner .= $this->singleParamHtmlHolder($param, $param_value);
                }
                $output .= $inner;
            }
            $output .= '</div>';
            $output .= '</div>';
        }

        return $output;
    }
    public function customAdminBockParams() {
        return '';
    }
}


class WPBakeryShortCode_VC_Row_Inner extends WPBakeryShortCode_VC_Row {
    protected function getFileName() {
        return 'vc_row';
    }
    public function template($content = '') {
        return $this->contentAdmin($this->atts);
    }
}



