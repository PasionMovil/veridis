<?php
/**
 * WPBakery Visual Composer layout to display elements of administration iinterface
 *
 * @package WPBakeryVisualComposer
 *
 */

class WPBakeryVisualComposerLayoutButton implements WPBakeryVisualComposerTemplateInterface {
    protected $params = Array();

    public function setup($params) {
        if(empty($params['id']) || empty($params['title']))
            trigger_error( __("Wrong layout params", "meganews"));
        $this->params = (array)$params;
        return $this;
    }

    public function output($post = null) {
        if(empty($this->params)) return '';
        $output = '<li class="category-layout wpb-layout-element-button not-column-inherit_o"><a id="'.$this->params['id'].'" data-element="vc_column" data-width="'.$this->params['id'].'" class="'.$this->params['id'].' dropable_el clickable_action" href="#"><span>'.__($this->params['title'], "meganews").'</span></a></li>';
        return $output;
    }
}

class WPBakeryVisualComposerTemplateMenuButton implements WPBakeryVisualComposerTemplateInterface {
    protected $params = Array();
    protected $id;

    public function setID($id) {
        $this->id = (string)$id;
        return $this;
    }
    public function setup($params) {
        $this->params = (array)$params;
        return $this;
    }

    public function output($post = null) {
        if(empty($this->params)) return '';
        $output = '<li class="wpb_template_li"><a data-template_id="'.$this->id.'" href="#">'.htmlspecialchars(__(stripslashes($this->params['name']), "meganews")).'</a> <span class="wpb_remove_template" rel="'.$this->id.'"><i class="icon wpb_template_delete_icon"> </i> </span></li>';
        return $output;
    }
}

class WPBakeryVisualComposerElementButton implements WPBakeryVisualComposerTemplateInterface {
    protected $params = Array();
    protected $base;

    public function setBase($base) {
        $this->base = $base;
        return $this;
    }
    public function setup($params) {
        $this->params = $params;
        return $this;
    }
    protected function getIcon() {
        return !empty($this->params['icon']) ? '<i class="' . sanitize_title($this->params['icon']) . '"></i> ' : '';
    }
    public function output($post = null) {
        if(empty($this->params)) return '';
        $output = $class = $class_out = $data = '';
        if ( !empty($this->params["class"]) ) {
            $class_ar = $class_at_out = explode(" ", $this->params["class"]);
            for ($n=0; $n<count($class_ar); $n++) {
                $class_ar[$n] .= "_nav";
                $class_at_out[$n] .= "_o";
            }
            $class = ' ' . implode(" ", $class_ar);
            $class_out = ' '. implode(" ", $class_at_out);
        }
        if(isset($this->params['is_container']) && $this->params['is_container']===true) $data .= ' data-is-container="true"';
        $output .= '<li data-element="' . $this->base . '" class="category-'.$this->params['_category_id'].' wpb-layout-element-button'.$class_out.'"'.$data.'><a id="' . $this->base . '" class="dropable_el clickable_action'.$class.'" href="#">' . $this->getIcon() . htmlspecialchars(__(stripslashes($this->params["name"]), "meganews")) .'</a></li>';
        return $output;
    }
}

class WPBakeryVisualComposerTemplateMenu implements WPBakeryVisualComposerTemplateInterface {
    protected $params = Array();

    public function setup($params) {
        $this->params = (array)$params;
        return $this;
    }

    public function output( $only_list = false ) {
        // if(empty($this->params)) return '';
        $output = '';
        if($only_list===false) {
            $output .=  '<li><ul>
                        <li id="wpb_save_template"><a href="#" id="wpb_save_template_button" class="button">'.__('Save current page as a Template', 'meganews').'</a></li>
                        <li class="divider"></li>
                        <li class="nav-header">'.__('Load Template', 'meganews').'</li>
                        </ul></li>
                        <li>
                        <ul class="wpb_templates_list">';
        }
        $is_empty = true;
        foreach($this->params as $id => $template) {
            if( is_array( $template) ) {
                $template_button = new WPBakeryVisualComposerTemplateMenuButton();
                $output .= $template_button->setup($template)->setID($id)->output();
               $is_empty = false;
            }
        }
        if($is_empty) $output .= '<li class="wpb_no_templates"><span>'.__('No custom templates yet.', 'meganews').'</span></li>';
        if($only_list===false) {
            $output .= '</ul></li>';

        }
        return $output;
    }
}

class WPBakeryVisualComposerTemplate_r extends WPBakeryVisualComposerAbstract {

    protected $templates = Array();

    public function getMenu($only_list = false) {
        $template_menu = new WPBakeryVisualComposerTemplateMenu();
        return $template_menu->setup($this->getTemplatesList())->output($only_list);
    }
    protected function getTemplates() {
        if($this->templates==null)
            $this->templates = (array)get_option('wpb_js_templates');
        return $this->templates;
    }

    public function getTemplatesList() {
        return $this->getTemplates();
    }
}

class WPBakeryVisualComposerNavBar implements WPBakeryVisualComposerTemplateInterface {
    public function __construct() {

    }
    public function getColumnLayouts() {
        $output = '';
        foreach ( WPBMap::getLayouts() as $layout ) {
            $layout_button = new WPBakeryVisualComposerLayoutButton();
            $output .= $layout_button->setup($layout)->output();
        }
        return $output;
    }

    public function getContentCategoriesLayouts() {
        $output = '<li><ul class="isotope-filter"><li class="active"><a href="#" data-filter="*">'
                  .__('Show all', 'meganews').'</a></li>';
        // $output .= '<li><a href="#" data-filter=".category-layout" class="category-layout-filer">'
        //           .__('Layout', 'meganews').'</a></li>';
        $_other_category_index = 0;
        $show_other = false;
        foreach(WPBMap::getUserCategories() as $key => $name) {
            if($name === '_other_category_') {
                $_other_category_index  = $key;
                $show_other = true;
            } else {
                $output .='<li><a href="#" data-filter=".category-'.md5($name).'">'.__($name, "meganews").'</a></li>';
            }
        }

        if($show_other) $output .= '<li><a href="#" data-filter=".category-'.$_other_category_index.'">'
                                    .__('Other', 'meganews').'</a></li>';
        //$output .= '<li class="name_filter"><input id="vc_elements_name_filter" type="text" name="vc_content_filter" placeholder="'.__('Search by element name', 'meganews').'"/></li>';
        $output .= '</ul></li>';
        return $output;
    }

    public function getElementsModal() {
        $output = '<div class="wpb_bootstrap_modals">
        <script id="wpb-elements-list-modal-template" type="text/template">
        <div class="modal wpb-elements-list-modal">
          <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>'.__('Select Content Element', 'meganews').'</h3>
            <input id="vc_elements_name_filter" type="text" name="vc_content_filter" placeholder="'.__('Search by element name', 'meganews').'"/>
          </div>
          <div class="modal-body wpb-elements-list">
            <ul class="wpb-content-layouts-container" style="position: relative;">
                '.$this->getContentCategoriesLayouts().'
                '.$this->getContentLayouts().'
            </ul>
          </div>
          <!--<div class="modal-body wpb-edit-form">
            <div class="vc_row-fluid wpb-edit-form-inner">
            </div>
          </div>
          <div class="modal-body wpb-image-gallery">
          </div>-->
          <div class="modal-footer hide">
            <button class="btn" data-dismiss="modal" aria-hidden="true">'.__('Close','meganews').'</button>
          </div>
        </div>
        </script>
        <script id="wpb-element-settings-modal-template" type="text/template">
        <div class="modal wpb-element-edit-modal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3></h3>
            </div>
            <div class="modal-body wpb-edit-form">
                <div class="vc_row-fluid wpb-edit-form-inner">
                    <img src="images/wpspin_light.gif" alt="spinner" /> '.__('Loading...', 'meganews').'
                </div>
            </div>
            <div class="modal-footer text-center">
                <button class="button-primary wpb_save_edit_form" data-dismiss="modal" aria-hidden="true">'.__('Save','meganews').'</button>
                <button class="button" data-dismiss="modal" aria-hidden="true">'.__('Cancel','meganews').'</button>
          </div>
        </div>
        </script>
        </div>';

        return $output;
    }

    public function getContentLayouts() {

        $output = '<li><ul class="wpb-content-layouts">';
        // $output .= $this->getColumnLayouts();

        foreach (WPBMap::getUserShortCodes() as $sc_base => $el) {
            if(isset($el['content_element']) && $el['content_element'] === false) continue;
                $element_button = new WPBakeryVisualComposerElementButton();
                $output .= $element_button->setBase($sc_base)->setup($el) ->output();
        }
        $output .= '</ul></li>';
        return $output;
    }

    public function getTemplateMenu($only_list = false) {
        $template_r = new WPBakeryVisualComposerTemplate_r();
        return $template_r->getMenu($only_list);
    }


    public function output($post = null) {
        global $current_user;
        get_currentuserinfo();
        /** @var $settings - get use group access rules */
        $settings = WPBakeryVisualComposerSettings::get('groups_access_rules');
        $role = $current_user->roles[0];
        $show_role = isset($settings[$role]['show']) ? $settings[$role]['show'] : '';
        $buttons = $this->buildButtons();
        $output = '
            <div id="wpb_visual_composer-elements" class="vc_navbar">
                <input type="hidden" name="wpb_js_composer_group_access_show_rule" class="wpb_js_composer_group_access_show_rule" value="'.$show_role.'" />
                <div class="vc_navbar-inner clearfix">
                    <!--<div class="container">-->
                        <ul class="vc_nav">
                          <li>
                            <a title="'.__('Visual Composer for WordPress', 'meganews').'" href="http://vc.wpbakery.com/" class="vc_logo" target="_blank"></a>
                          </li>
                        </ul>
                        '.$buttons.'
                            <ul class="vc_nav pull-right wpb-update-button">
                                <li><a class="button" id="wpb-save-post">'.__('Update', 'meganews').'</a></li>
                            </ul>
                    <!--</div>-->
                </div>
            </div>';
        return $output;
    }
    function buildButtons() {
        $html = '';
        $buttons = array(
            array(
                'add_element',
                '<ul class="vc_nav">
                                <li>
                                    <a class="wpb_add_new_element dropable_el button" id="wpb-add-new-element"><i class="icon"></i>'.__('Add element', 'meganews').'</a>
                                </li>
                            </ul>'
            ),
            array(
                'add_row',
                '<ul class="vc_nav">
                              <li>
                                <a class="wpb_add_new_row dropable_row button" id="wpb-add-new-row" data-element="vc_row"><i class="icon"></i>'.__('Add row', 'meganews').'</a>
                                </li>
                            </ul>'
            ),
            array(
                'templates',
                '<ul class="vc_nav">
                                <li class="vc_dropdown">
                                    <a class="wpb_templates button"><i class="icon"></i>'.__('Templates', 'meganews').' <b class="caret"></b></a>
                                    <ul class="vc_dropdown-menu wpb_templates_ul">
                                        '.$this->getTemplateMenu().'
                                    </ul>
                                </li>
                            </ul>'
            )
        );
        $buttons = apply_filters('vc_nav_controls', $buttons);
        foreach($buttons as $button) {
            $html .= $button[1];
        }

        return $html;
    }
}

class WPBakeryVisualComposerLayout implements  WPBakeryVisualComposerTemplateInterface {
    protected $navBar;
    public function __construct() {

    }
    public function getNavBar() {
        if($this->navBar==null) $this->navBar = new WPBakeryVisualComposerNavBar();
        return $this->navBar;
    }

	public function getContainerHelper() {
		// return '<div class="container-helper">' . __('<h2>No content yet! You should add some...</h2>', 'meganews') . __("<p>Use the buttons under <a href='javascript:open_elements_dropdown();' class='open-dropdown-content-element'><i class='icon'></i> Content Elements</a> on the top or add <a href='#' class='add-text-block-to-content'><i class='icon'></i> Text block</a> with single click.", 'meganews') . '</p></div>';
        //return '<div class="container-helper"><span>' . __('<h2>No content yet! You should add some...</h2>', 'meganews') . __('<p>Click <a href="#" class="add-element-to-layout" title="Add to this column"><i class="icon"></i></a> to add new element inside this column.', 'meganews') . '</p></span></div>';
        return '';
    }

    public function output($post = null) {

        $output = $this->getNavBar()->getElementsModal();

        $output .= $this->getNavBar()->output();

        $output .= '
        <div class="metabox-composer-content">
					<div id="wpb-convert-message">
					   <div class="messagebox_text"><p>'.__('Your page layout was created with previous Visual Composer version. Before converting your layout to the new version, make sure to <a target="_blank" href="http://kb.wpbakery.com/index.php?title=Update_Visual_Composer_from_3.4_to_3.5">read this page</a>.', 'meganews').'</p>
					     <div class="wpb-convert-buttons">
					       <a class="wpb_convert button" id="wpb-convert"><i class="icon"></i>'.__('Convert to new version', 'meganews').'</a>
					     </div>
					 </div>
				</div>
				
				<div class="vc_loading_block" style="display: none;">
				  <img src="'.get_site_url().'/wp-admin/images/wpspin_light.gif" /> '.__("Loading, please wait...", "meganews").'
				</div>
				
				<div id="visual_composer_content" class="wpb_main_sortable main_wrapper"></div>
					<div id="wpb-empty-blocks">
					 <h2>' . __("No content yet! You should add some...", "meganews") .'</h2>
					 <table class="helper-block">
					   <tr>
					     <td><span>1</span></td>
					     <td><p> '. __("This is a visual preview of your page. Currently, you don't have any content elements. Click or drag the button <a href='#' class='add-element-to-layout'><i class='icon'></i> Add element</a> on the top to add content elements on your page. Alternatively add <a href='#' class='add-text-block-to-content' parent-container='#visual_composer_content'><i class='icon'></i> Text block</a> with single click.", "meganews") . '</p></td>
					   </tr>
					 </table>
					 <table class="helper-block">
					   <tr>
					     <td><span>2</span></td><td><p class="one-line"> '. __("Click the pencil icon on the content elements to change their properties.", "meganews") . '</p></td>
					   </tr>
					   <tr>
					     <td colspan="2">
					       <div class="edit-picture"></div>
					     </td>
					   </tr>
					 </table>
				  </div>
				</div>
				<div id="container-helper-block" style="display: none;">' . $this->getContainerHelper() . '</div>';
        ?>
        <script type="text/javascript">
            var vc_user_mapper = <?php echo json_encode(WPBMap::getUserShortCodes()) ?>,
                vc_mapper = <?php echo json_encode(WPBMap::getShortCodes()) ?>;
        </script>
        <?php

        $wpb_vc_status = get_post_meta($post->ID, '_wpb_vc_js_status', true);
        if ( $wpb_vc_status == "" || !isset($wpb_vc_status) ) {
            $wpb_vc_status = 'false';
        }
        $output .= '<input type="hidden" id="wpb_vc_js_status" name="wpb_vc_js_status" value="'. $wpb_vc_status .'" />';
        $output .= '<input type="hidden" id="wpb_vc_loading" name="wpb_vc_loading" value="'. __("Loading, please wait...", "meganews") .'" />';
        $output .= '<input type="hidden" id="wpb_vc_loading_row" name="wpb_vc_loading_row" value="'. __("Crunching...", "meganews") .'" />';

        $output .= '<input type="hidden" id="wpb_vc_js_interface_version" name="wpb_vc_js_interface_version" value="'. vc_get_initerface_version() .'" />';
        echo $output;
        require_once WPBakeryVisualComposer::config('COMPOSER').'templates/media_editor.php';
        require_once WPBakeryVisualComposer::config('COMPOSER').'lib/params/loop/templates.html';
    }
}
?>
