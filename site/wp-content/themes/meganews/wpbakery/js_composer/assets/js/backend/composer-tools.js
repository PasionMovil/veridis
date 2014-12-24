(function ($) {
    $.log = function (text) {
        if (typeof(window.console) !== 'undefined' && window.console.log) window.console.log(text);
    };
    $.expr[':'].containsi = function (a, i, m) {
        return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };

    window.vc_get_column_size = function ($column) {
        if ($column.hasClass("vc_span12")) //full-width
            return "1/1";
        else if ($column.hasClass("vc_span11")) //three-fourth
            return "11/12";
        else if ($column.hasClass("vc_span10")) //three-fourth
            return "4/6";
        else if ($column.hasClass("vc_span9")) //three-fourth
            return "3/4";
        else if ($column.hasClass("vc_span8")) //three-fourth
            return "5/6";
        else if ($column.hasClass("vc_span8")) //two-third
            return "2/3";
        else if ($column.hasClass("vc_span7")) // 7/12
            return "7/12";
        else if ($column.hasClass("vc_span6")) //one-half
            return "1/2";
        else if ($column.hasClass("vc_span5")) //one-half
            return "5/12";
        else if ($column.hasClass("vc_span4")) // one-third
            return "1/3";
        else if ($column.hasClass("vc_span3")) // one-fourth
            return "1/4";
        else if ($column.hasClass("vc_span2")) // one-fourth
            return "1/6";
        else if ($column.hasClass("vc_span1")) // one-fourth
            return "1/12";
        else
            return false;
    };
})(window.jQuery);


function vc_convert_column_size(width) {
    var prefix = 'vc_span',
        numbers = width ? width.split('/') : [1,1],
        range = _.range(1,13),
        num = !_.isUndefined(numbers[0]) && _.indexOf(range, parseInt(numbers[0], 10)) >=0 ? parseInt(numbers[0], 10) : false,
        dev = !_.isUndefined(numbers[1]) && _.indexOf(range, parseInt(numbers[1], 10)) >=0 ? parseInt(numbers[1], 10) : false;
    if(num!==false && dev!==false) {
        return prefix + (12*num/dev);
    }
    return prefix + '12';
}
/**
 * @deprecated
 * @param width
 * @return {*}
 */
function vc_column_size(width) {
    return vc_convert_column_size(width);
}
function vc_convert_column_span_size(width) {
    width = width.replace(/^vc_/, '');
    if (width == "span12")
        return '1/1';
    else if (width == "span11")
        return '11/12';
    else if (width == "span10") //three-fourth
        return '5/6';
    else if (width == "span9") //three-fourth
        return '3/4';
    else if (width == "span8") //two-third
        return '2/3';
    else if (width == "span7")
        return '7/12';
    else if (width == "span6") //one-half
        return '1/2';
    else if (width == "span5") //one-half
        return '5/12';
    else if (width == "span4") // one-third
        return '1/3';
    else if (width == "span3") // one-fourth
        return '1/4';
    else if (width == "span2") // one-fourth
        return '1/6';
    else if(width == "span1")
        return '1/12';

    return false;
}

function vc_get_column_mask(cells) {
    var columns = cells.split('_'),
        columns_count = columns.length,
        numbers_sum = 0,
        i;
    for(i in columns) {
        var sp = columns[i].match(/(\d{1,2})(\d{1,2})/);
        numbers_sum += _.reduce(sp.slice(1), function(memo, num) {
            return memo + parseInt(num, 10);}, 0); //TODO: jshint
    }
    return columns_count + '' + numbers_sum;
}

/**
 * Create Unique id for records in storage.
 * Generate a pseudo-GUID by concatenating random hexadecimal.
 * @return {String}
 */
function vc_guid() {
    return (VCS4() + VCS4() + "-" + VCS4());
}

// Generate four random hex digits.
function VCS4() {
    return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
}

/**
 * Taxonomies filter
 *
 * Show or hide taxonomies depending on selected post types

 * @param $element - post type checkbox object
 * @param $object -
 */
var wpb_grid_post_types_for_taxonomies_handler = function () {
    var $labels = this.$content.find('.wpb_el_type_taxonomies label[data-post-type]'),
        $ = jQuery;
    $labels.hide();
    $('.grid_posttypes:checkbox', this.$content).change(function () {
        if ($(this).is(':checked')) {
            $labels.filter('[data-post-type=' + $(this).val() + ']').show();
        } else {
            $labels.filter('[data-post-type=' + $(this).val() + ']').hide();
        }
    }).each(function () {
            if ($(this).is(':checked')) $labels.filter('[data-post-type=' + $(this).val() + ']').show();
        });
};
var wpb_single_image_img_link_dependency_callback = function () {
    var $img_link_large = this.$content.find('#img_link_large-yes'),
        $ = jQuery,
        $img_link_target = this.$content.find('[name=img_link_target]').closest('.vc_row-fluid');
    this.$content.find('#img_link_large-yes').change(function () {
        var checked = $(this).is(':checked');
        if (checked) {
            $img_link_target.show();
        } else {
            if ($('.wpb-edit-form [name=img_link]').val().length > 0) {
                $img_link_target.show();
            } else {
                $img_link_target.hide();
            }
        }
    });
    if (this.$content.find('#img_link_large-yes').is(':checked')) {
        $img_link_target.show();
    } else {
        if ($('.wpb-edit-form [name=img_link]').val().length > 0) {
            $img_link_target.show();
        } else {
            $img_link_target.hide();
        }
    }
};
