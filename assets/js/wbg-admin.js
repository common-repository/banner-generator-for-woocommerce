jQuery(document).ready(function () {
    var $sortable2 = jQuery('#sortable2');
    var $alert = jQuery('.wbgLastFrameAlert');

    jQuery(".wbg_confirm").click(function (event) {
        if (!confirm(jQuery(this).data('confirmation')))
            event.preventDefault();
    });

    jQuery(".wbg_prompt").click(function (event) {
        var enteredText = prompt(jQuery(this).data('message'), jQuery(this).data('default'));
        if (enteredText !== jQuery(this).data('condition_key')) {
            event.preventDefault();
        }

    });


    jQuery(function () {
        jQuery("#sortable1, #sortable2").sortable({
            connectWith: ".wbgSortable",
            remove: function (e, li) {
                if (li.item.parent().attr("id") === 'sortable2') {
                    copyHelper = li.item.clone().insertAfter(li.item);
                    jQuery(this).sortable('cancel');
                    return li.item.clone();
                } else {
                    return li.item.remove();
                }
            },
            update: function (event, ui) {
                $totalItems = wbgUpdateIds();
                var $total = jQuery('ul#sortable2 li').length
            }
        }).disableSelection();
    });


    function wbgUpdateIds() {
        var $wooItems = jQuery('#wbg-woo-items');
        $wooItems.val('');
        var $values = [];
        $sortable2.children('li').each(function (i, obj) {
            $values.push(jQuery(obj).data('wbg-woo-item'));
        });
        $wooItems.val(JSON.stringify($values));
    }

});

