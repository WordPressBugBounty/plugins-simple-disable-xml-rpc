/**
 * Admin Script
 * Simple Disable XML-RPC
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Add visual feedback when settings are saved
        if (window.location.search.indexOf('settings-updated=true') > -1) {
            // Scroll to top to show success message
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        }

        // Optional: Add confirmation before saving
        $('#sdxrpc_disable_enabled').on('change', function() {
            var isChecked = $(this).is(':checked');
            var message = isChecked 
                ? 'You are about to disable XML-RPC. This may affect some plugins or services.' 
                : 'You are about to enable XML-RPC.';
            
            // You can add a confirmation dialog here if needed
            // console.log(message);
        });

    });

})(jQuery);