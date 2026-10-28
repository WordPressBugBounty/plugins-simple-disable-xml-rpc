/**
 * Admin Script
 * Simple Disable XML-RPC v1.4.1
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        /**
         * 1. Dynamic Toggle Switch Feedback
         */
        var $masterToggle = $('#sdxrpc_disable_enabled');
        var $hardeningCard = $('.sdxrpc-hardening-card');

        $masterToggle.on('change', function() {
            var isChecked = $(this).is(':checked');
            if (isChecked) {
                $hardeningCard.removeClass('is-disabled');
            } else {
                $hardeningCard.addClass('is-disabled');
            }
        });

        /**
         * 2. Live Diagnostics: Test XML-RPC Endpoint
         */
        $('#sdxrpc-run-test').on('click', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var $resultBox = $('#sdxrpc-test-result');
            var originalText = $btn.find('.button-text').text();

            $btn.addClass('is-loading').prop('disabled', true);
            $btn.find('.button-text').text(sdxrpc_vars.strings.testing);
            $resultBox.hide().removeClass('is-protected is-exposed is-error');

            $.ajax({
                url: sdxrpc_vars.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'sdxrpc_test_xmlrpc',
                    nonce: sdxrpc_vars.admin_nonce
                },
                success: function(response) {
                    $btn.removeClass('is-loading').prop('disabled', false);
                    $btn.find('.button-text').text(originalText);

                    if (response.success && response.data) {
                        var data = response.data;
                        var statusClass = 'is-protected';

                        if (data.status === 'exposed') {
                            statusClass = 'is-exposed';
                        } else if (data.status === 'custom') {
                            statusClass = 'is-exposed';
                        }

                        $resultBox
                            .addClass(statusClass)
                            .find('.sdxrpc-test-status-pill')
                            .text(data.title);

                        $resultBox
                            .find('.sdxrpc-test-http-code')
                            .text('HTTP ' + data.code);

                        $resultBox
                            .find('.sdxrpc-test-message')
                            .text(data.message);

                        $resultBox.fadeIn(200);
                    } else {
                        var errorMsg = (response.data && response.data.message) ? response.data.message : sdxrpc_vars.strings.error;
                        $resultBox
                            .addClass('is-error')
                            .find('.sdxrpc-test-status-pill')
                            .text('Error');

                        $resultBox.find('.sdxrpc-test-http-code').text('N/A');
                        $resultBox.find('.sdxrpc-test-message').text(errorMsg);
                        $resultBox.fadeIn(200);
                    }
                },
                error: function() {
                    $btn.removeClass('is-loading').prop('disabled', false);
                    $btn.find('.button-text').text(originalText);

                    $resultBox
                        .addClass('is-error')
                        .find('.sdxrpc-test-status-pill')
                        .text('Connection Error');

                    $resultBox.find('.sdxrpc-test-http-code').text('500');
                    $resultBox.find('.sdxrpc-test-message').text(sdxrpc_vars.strings.error);
                    $resultBox.fadeIn(200);
                }
            });
        });

        /**
         * 3. Recommended Plugins: One-Click Install, Activate, Deactivate
         */
        $(document).on('click', '.sdxrpc-plugin-action', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var action = $btn.data('action');
            var slug = $btn.data('slug');
            var file = $btn.data('file') || '';
            var $cardActions = $btn.closest('.sdxrpc-card-actions');

            if (action === 'deactivate') {
                if (!confirm(sdxrpc_vars.strings.confirm_deactivate)) {
                    return;
                }
            }

            var loadingText = sdxrpc_vars.strings.installing;
            if (action === 'activate') {
                loadingText = sdxrpc_vars.strings.activating;
            } else if (action === 'deactivate') {
                loadingText = sdxrpc_vars.strings.deactivating;
            }

            $btn.addClass('is-loading').prop('disabled', true);
            var prevText = $btn.text();
            $btn.text(loadingText);

            $.ajax({
                url: sdxrpc_vars.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'sdxrpc_plugin_action',
                    plugin_action: action,
                    slug: slug,
                    plugin_file: file,
                    nonce: sdxrpc_vars.plugin_action_nonce
                },
                success: function(response) {
                    $btn.removeClass('is-loading').prop('disabled', false);

                    if (response.success && response.data) {
                        var res = response.data;
                        showToast(res.message, 'success');

                        if (action === 'install') {
                            // Turn into Activate button
                            $btn
                                .removeClass('button-secondary')
                                .addClass('button-primary')
                                .data('action', 'activate')
                                .data('file', res.plugin_file)
                                .text(sdxrpc_vars.strings.activate);
                        } else if (action === 'activate') {
                            // Render Active badge + Deactivate button
                            $cardActions.html(
                                '<span class="sdxrpc-badge-active"><span class="dashicons dashicons-yes"></span> ' + sdxrpc_vars.strings.active + '</span>' +
                                '<button type="button" class="button button-secondary sdxrpc-plugin-action" data-action="deactivate" data-slug="' + slug + '" data-file="' + (res.plugin_file || file) + '">' +
                                sdxrpc_vars.strings.deactivate +
                                '</button>'
                            );
                        } else if (action === 'deactivate') {
                            // Turn back into Activate button
                            $cardActions.html(
                                '<button type="button" class="button button-primary sdxrpc-plugin-action" data-action="activate" data-slug="' + slug + '" data-file="' + (res.plugin_file || file) + '">' +
                                sdxrpc_vars.strings.activate +
                                '</button>'
                            );
                        }
                    } else {
                        var errorMsg = (response.data && response.data.message) ? response.data.message : sdxrpc_vars.strings.error;
                        $btn.text(prevText);
                        showToast(errorMsg, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    $btn.removeClass('is-loading').prop('disabled', false);
                    $btn.text(prevText);
                    showToast(sdxrpc_vars.strings.error, 'error');
                }
            });
        });

        /**
         * 4. Refresh Recommended Plugins via AJAX
         */
        $('#sdxrpc-refresh-plugins').on('click', function(e) {
            e.preventDefault();

            var $btn = $(this);
            $btn.addClass('is-loading').prop('disabled', true);

            $.ajax({
                url: sdxrpc_vars.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'sdxrpc_refresh_recommended',
                    nonce: sdxrpc_vars.plugin_action_nonce
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.data.message, 'success');
                        setTimeout(function() {
                            window.location.reload();
                        }, 800);
                    } else {
                        $btn.removeClass('is-loading').prop('disabled', false);
                        showToast(sdxrpc_vars.strings.error, 'error');
                    }
                },
                error: function() {
                    $btn.removeClass('is-loading').prop('disabled', false);
                    showToast(sdxrpc_vars.strings.error, 'error');
                }
            });
        });

        /**
         * 5. Real-time Search Filter for Plugin Cards
         */
        $('#sdxrpc-plugin-search').on('input', function() {
            var query = $(this).val().toLowerCase().trim();
            var visibleCount = 0;

            $('.sdxrpc-plugin-card').each(function() {
                var $card = $(this);
                var name = $card.data('name') || '';
                var desc = $card.find('.sdxrpc-plugin-desc').text().toLowerCase();

                if (name.indexOf(query) > -1 || desc.indexOf(query) > -1) {
                    $card.show();
                    visibleCount++;
                } else {
                    $card.hide();
                }
            });

            $('.sdxrpc-filter-stats').text('Showing ' + visibleCount + ' plugins');
        });

        /**
         * Helper: Show Toast Notification
         */
        function showToast(message, type) {
            var $toast = $('#sdxrpc-ajax-notice');
            if (!$toast.length) {
                $toast = $('<div id="sdxrpc-ajax-notice" class="sdxrpc-toast"></div>').appendTo('body');
            }

            $toast
                .removeClass('toast-success toast-error')
                .addClass('toast-' + type)
                .html('<span class="dashicons dashicons-' + (type === 'success' ? 'yes' : 'warning') + '"></span> ' + message)
                .fadeIn(200);

            setTimeout(function() {
                $toast.fadeOut(300);
            }, 3500);
        }

    });

})(jQuery);
