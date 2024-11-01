/**
 * keywords_mce_shortcode
 *
 * @since 1.3
 */
(function() {
    tinymce.PluginManager.add('ss_kw', function( editor, url ) {
        var sh_tag = 'ss_kw';

        //add popup
        editor.addCommand('ss_kw_popup', function(ui, v) {
            //setup defaults
            var name = 'keywords';
            if (v.name)
                name = v.name;
            var tag = 'strong';
            if (v.tag)
                tag = v.tag;
            var content = '';

            // since 1.5
            content = editor.selection.getContent();

            if (v.content)
                content = v.content;
            var custom_tag = '';
            //open the popup
            editor.windowManager.open( {
                title: 'Keywords Shortcode',
                body: [
                    {//add property select
                        type: 'listbox',
                        name: 'name',
                        label: 'Property',
                        value: name,
                        'values': [
                            {text: 'keywords', value: 'keywords'}
                        ],
                        tooltip: 'Required as is'
                    },
                    {//add tag select
                        type: 'listbox',
                        name: 'tag',
                        label: 'HTML tag',
                        value: tag,
                        'values': [
                            {text: 'h1', value: 'h1'},
                            {text: 'h2', value: 'h2'},
                            {text: 'h3', value: 'h3'},
                            {text: 'h4', value: 'h4'},
                            {text: 'h5', value: 'h5'},
                            {text: 'h6', value: 'h6'},
                            {text: 'strong', value: 'strong'},
                            {text: 'em', value: 'em'},
                            {text: 'span', value: 'span'},
                            {text: 'p', value: 'p'},
                            {text: 'div', value: 'div'},
                            {text: 'meta', value: 'meta'}
                        ],
                        tooltip: 'Select the HTML tag you want'
                    },
                    {//add footer input
                        type: 'textbox',
                        label: 'Other tag',
                        name: 'custom_tag',
                        value: custom_tag,
                        tooltip: 'Leave blank for none'
                    },
                    {//add content textarea
                        type: 'textbox',
                        name: 'content',
                        label: 'Content',
                        value: content,
                        multiline: true,
                        minWidth: 300,
                        minHeight: 100
                    }
                ],
                onsubmit: function( e ) { //when the ok button is clicked
                    //start the shortcode tag
                    var shortcode_str = '[' + sh_tag;// + ' name="'+e.data.name+'"';

                    //check for tag
                    if (typeof e.data.custom_tag != 'undefined' && e.data.custom_tag.length && e.data.custom_tag.toLowerCase() !== 'strong' )
                        shortcode_str += ' tag="' + e.data.custom_tag.toLowerCase() + '"';
                    else
                        if (typeof e.data.tag != 'undefined' && e.data.tag.length && e.data.tag.toLowerCase() !== 'strong' )
                            shortcode_str += ' tag="' + e.data.tag + '"';

                    //add panel content
                    shortcode_str += ']' + e.data.content + '[/' + sh_tag + ']';

                    //insert shortcode to tinymce
                    editor.insertContent( shortcode_str );
                }
            });
        });

        //add button
        editor.addButton('ss_kw', {
            icon: 'ss_kw',
            tooltip: 'Insert keywords',
            onclick: function() {
                editor.execCommand('ss_kw_popup','',{
                    name   : 'keywords',
                    tag    : 'strong',
                    content: ''
                });
            }
        });

        //        //helper functions
        //        function getAttr(s, n) {
        //            n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
        //            return n ?  window.decodeURIComponent(n[1]) : '';
        //        };
        //
        //        function html( cls, data ,con) {
        //            //var placeholder = url + '/img/' + getAttr(data,'type') + '.jpg';
        //            data = window.encodeURIComponent( data );
        //            content = window.encodeURIComponent( con );
        //
        //            return '<i style="cursor:pointer" class="mceItem ' + cls + ' mce-ico mce-i-' + sh_tag + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con + '" data-mce-resize="false" data-mce-placeholder="1" title="'+con+'"></i>';
        //        }
        //
        //        function replaceShortcodes( content ) {
        //            //match [ss_kw(attr)](con)[/ss_kw]
        //            return content.replace( /\[ss_kw([^\]]*)\]([^\]]*)\[\/ss_kw\]/g, function( all,attr,con) {
        //                return html( 'wp-ss_kw', attr , con);
        //            });
        //        }
        //
        //        function restoreShortcodes( content ) {
        //            //match any image tag with our class and replace it with the shortcode's content and attributes
        //            return content.replace( /(?:<p(?: [^>]+)?>)*(<img [^>]+>)(?:<\/p>)*/g, function( match, image ) {
        //                var data = getAttr( image, 'data-sh-attr' );
        //                var con = getAttr( image, 'data-sh-content' );
        //
        //                if ( data ) {
        //                    return '[' + sh_tag + data + ']' + con + '[/'+sh_tag+']';
        //                }
        //                return match;
        //            });
        //        }
        //
        //        //replace from shortcode to an image placeholder
        //        editor.on('BeforeSetcontent', function(event){
        //            event.content = replaceShortcodes( event.content );
        //        });
        //
        //        //replace from image placeholder to shortcode
        //        editor.on('GetContent', function(event){
        //            event.content = restoreShortcodes(event.content);
        //        });
        //
        //        //open popup on placeholder double click
        //        editor.on('DblClick',function(e) {
        //            var cls  = e.target.className.indexOf('wp-ss_kw');
        //            if ( e.target.nodeName == 'I' && e.target.className.indexOf('wp-ss_kw') > -1 ) {
        //                var title = e.target.attributes['data-sh-attr'].value;
        //                title = window.decodeURIComponent(title);
        //                console.log(title);
        //                var content = e.target.attributes['data-sh-content'].value;
        //                editor.execCommand('ss_kw_popup','',{
        //                    name   : getAttr(title,'name'),
        //                    tag    : getAttr(title,'tag'),
        //                    content: content
        //                });
        //            }
        //        });
    });
})();