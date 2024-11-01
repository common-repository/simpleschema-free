/**
 * itemscope_mce_shortcode
 *
 * @since 1.3
 */
(function() {
    tinymce.PluginManager.add('ss_is', function( editor, url ) {
        var sh_tag = 'ss_is';

        //add popup
        editor.addCommand('ss_is_popup', function(ui, v) {
            //setup defaults
            var itemtype = '';
            if (v.itemtype)
                itemtype = v.itemtype;
            var tag = '';
            if (v.tag)
                tag = v.tag;
            var content = '';

            // since 1.5
            content = editor.selection.getContent();

            if (v.content)
                content = v.content;
            var custom_itemtype = '';
            var custom_tag = '';
            //open the popup
            editor.windowManager.open( {
                title: 'Itemscope Shortcode',
                body: [
                    {//add property select
                        type: 'listbox',
                        name: 'itemtype',
                        label: 'Itemtype',
                        value: itemtype,
                        'values': [
                            {text: 'LocalBusiness', value: 'LocalBusiness'},
                            {text: 'Product', value: 'Product'},
                            {text: 'PostalAddress', value: 'PostalAddress'},
                            {text: 'City', value: 'City'},
                            {text: 'GeoCoordinates', value: 'GeoCoordinates'},
                            {text: 'blogPosting', value: 'blogPosting'}
                        ],
                        tooltip: 'Select the itemtype you want'
                    },
                    {//add tag select
                        type: 'listbox',
                        name: 'tag',
                        label: 'HTML tag',
                        value: tag,
                        'values': [
                            {text: 'div', value: 'div'},
                            {text: 'p', value: 'p'},
                            {text: 'span', value: 'span'}
                        ],
                        tooltip: 'Select the HTML tag you want'
                    },
                    {//add header input
                        type: 'textbox',
                        label: 'Other itemtype',
                        name: 'custom_itemtype',
                        value: custom_itemtype,
                        tooltip: 'Leave blank for none'
                    },
                    {//add footer input
                        type: 'textbox',
                        label: 'Other tag',
                        name: 'custom_tag',
                        value: custom_tag,
                        tooltip: 'Leave blank for none'
                    }/*,
                    {//add content textarea
                        type: 'textbox',
                        name: 'content',
                        label: 'Content',
                        value: content,
                        multiline: true,
                        minWidth: 300,
                        minHeight: 100
                    }*/
                ],
                onsubmit: function( e ) { //when the ok button is clicked
                    //start the shortcode tag

                    var the_itemtype = '';
                    var the_itemprop = '';
                    var the_tag = '';
                    var shortcode_str = '[' + sh_tag;// + ' name="'+e.data.name+'"';


                    //check for itemtype
                    if (typeof e.data.custom_itemtype != 'undefined' && e.data.custom_itemtype.length)
                        the_itemtype = e.data.custom_itemtype.toLowerCase();
                    else
                        if (typeof e.data.itemtype != 'undefined' && e.data.itemtype.length)
                            the_itemtype = e.data.itemtype;

                    if ( the_itemtype !== '' )
                        shortcode_str += ' itemtype="' + the_itemtype + '"';

                    //check for tag
                    if (typeof e.data.custom_tag != 'undefined' && e.data.custom_tag.length)
                        the_tag = e.data.custom_tag.toLowerCase();
                    else
                        if (typeof e.data.tag != 'undefined' && e.data.tag.length)
                            the_tag = e.data.tag;

                    if ( the_tag !== '' && the_tag !=='div' )
                        shortcode_str += ' tag="' + the_tag + '"';

                    //add panel content
                    shortcode_str += ' start /]' + shortcode_str + ' end /]';

                    //insert shortcode to tinymce
                    editor.insertContent( shortcode_str);
                }
            });
        });

        //add button
        editor.addButton('ss_is', {
            icon: 'ss_is',
            tooltip: 'Insert itemscope',
            onclick: function() {
                editor.execCommand('ss_is_popup','',{
                    itemtype   : '',
                    tag    : 'div',
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
        //            var placeholder = url + '/img/' + getAttr(data,'type') + '.jpg';
        //            data = window.encodeURIComponent( data );
        //            content = window.encodeURIComponent( con );
        //
        //            return '<img src="' + placeholder + '" class="mceItem ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        //        }
        //
        //        function replaceShortcodes( content ) {
        //            //match [ss_is(attr)](con)[/ss_is]
        //            return content.replace( /\[ss_is([^\]]*)\]([^\]]*)\[\/ss_is\]/g, function( all,attr,con) {
        //                return html( 'wp-ss_is', attr , con);
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
        //            var cls  = e.target.className.indexOf('wp-ss_is');
        //            if ( e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-ss_is') > -1 ) {
        //                var title = e.target.attributes['data-sh-attr'].value;
        //                title = window.decodeURIComponent(title);
        //                console.log(title);
        //                var content = e.target.attributes['data-sh-content'].value;
        //                editor.execCommand('ss_is_popup','',{
        //                    name   : getAttr(title,'name'),
        //                    tag    : getAttr(title,'tag'),
        //                    content: content
        //                });
        //            }
        //        });
    });
})();