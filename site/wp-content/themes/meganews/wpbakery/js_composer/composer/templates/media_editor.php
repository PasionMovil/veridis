<script type="text/html" id="vc-settings-image-block">
    <li class="added">
        <div class="inner" style="width: 75px; height: 75px; overflow: hidden;text-align: center;">
            <img rel="<%= id %>" src="<%= url %>" />
        </div>
        <a href="#" class="icon-remove"></a>
    </li>
</script>
<?php foreach(WPBMap::getShortCodes() as $sc_base => $el): ?>
<script type="text/html" id="vc-shortcode-template-<?php echo $sc_base ?>">
<?php
    echo visual_composer()->getShortCode($sc_base)->template();
?>
</script>
<?php endforeach; ?>
<script type="text/html" id="vc-row-inner-element-template">
<?php
    echo visual_composer()->getShortCode('vc_row_inner')->template();
?>
</script>
<script type="text/html" id="vc-settings-page-param-block">
    <div class="row-fluid wpb_el_type_<%= type %>">
        <div class="wpb_element_label"><%= heading %></div>
        <div class="edit_form_line">
            <%= form_element %>
        </div>
    </div>
</script>

