(function( $ ) {
    'use strict';

    tinymce.PluginManager.add('wpvwk_shortcode_mce_button', function(editor, url) {
        editor.addButton('wpvwk_shortcode_mce_button', {
            icon: 'dashicon dashicons-video-alt3',
            tooltip: 'WP ViewWorks Shortcode',
            onclick: function() {
                var win = editor.windowManager.open({
                    title: 'WP ViewWorks Shortcode',
                    body: [
                    {
                        type  : 'textbox',
                        name  : 'model_url',
                        id    : 'model_url',
                        label : '3D Model file URL',
                        value : '',
                    }, {
                        type   : 'button',
                        name   : 'model_wpmedia',
                        label  : '',
                        text   : 'Select 3D Model',
                        onclick: function() {
                            if ( wp && wp.media && wp.media.editor ) {
                                wp.media.editor.send.attachment = function( a, media ) {
                                    win.find('#model_url').value( media.url );
                                };
                                wp.media.editor.open( editor.id, {
                                    title : 'Select 3D Model',
                                    multiple : false,
                                    library : {
                                        type : 'application/sla'
                                    },
                                    button : {
                                        text : 'Select'
                                    },
                                });
                            }
                        },
                    }],
                    onsubmit: function(e) {
                        if (e.data.model_url === '') {
                            var window_id = this._id;
                            var inputs = jQuery('#' + window_id + '-body').find('.mce-formitem input');
                            jQuery(inputs.get(0)).css('border-color', 'red');
                            editor.windowManager.alert('You must select a valid model file from Wordpress media');
                            return false;
                        }
                        var content = '[wp-viewworks';
                        content = content + ' model_file=&quot;' + e.data.model_url + '&quot;';
                        content = content + '][/wp-viewworks]';

                        editor.insertContent(content);
                    }
                });
            }
        });
    });
})( jQuery );