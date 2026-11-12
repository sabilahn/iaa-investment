/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 * 
 */

"use strict";

function initAutoDismissAlerts() {
    if (typeof jQuery === 'undefined') return;
    jQuery('.alert').each(function() {
        var $alert = jQuery(this);
        if (!$alert.data('auto-dismiss-bound')) {
            $alert.data('auto-dismiss-bound', true);
            setTimeout(function() {
                $alert.fadeOut(400, function() {
                    jQuery(this).remove();
                });
            }, 3000);
        }
    });
}

if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(function() {
        initAutoDismissAlerts();
    });
    jQuery(document).ajaxComplete(function() {
        initAutoDismissAlerts();
    });
}
