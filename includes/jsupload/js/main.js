/*
 * jQuery File Upload Plugin JS Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */
(function( $ ) {
$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#adminForm').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: 'index.php?option=com_joomsport&task=uploadGallery&tmpl=component&no_html=1'
    });

    // Enable iframe cross-domain access via redirect option:
    $('#adminForm').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );





});
})(jQuery);