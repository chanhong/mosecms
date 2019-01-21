tinyMCE.importPluginLanguagePack('print');
var TinyMCE_PrintPlugin = {getInfo: function() {
        return{longname: 'Print', author: 'Moxiecode Systems AB', authorurl: 'http://tinymce.moxiecode.com', infourl: 'http://tinymce.moxiecode.com/tinymce/docs/plugin_print.html', version: tinyMCE.majorVersion + "." + tinyMCE.minorVersion}
    }, getControlHTML: function(cn) {
        switch (cn) {
            case"print":
                return tinyMCE.getButtonHTML(cn, 'lang_print_desc', '{$pluginurl}/images/print.gif', 'mcePrint')
        }
        return""
    }, execCommand: function(editor_id, element, command, user_interface, value) {
        switch (command) {
            case"mcePrint":
                tinyMCE.getInstanceById(editor_id).contentWindow.print();
                return true
        }
        return false
    }};
tinyMCE.addPlugin("print", TinyMCE_PrintPlugin);