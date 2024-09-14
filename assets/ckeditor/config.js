/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.allowedContent = true;
    config.extraAllowedContent = 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}';
    config.removeButtons = "Image,Paste,Templates,Print,Cut,Copy,Preview";
    config.removePlugins = 'elementspath,save,font,dialogui,dialog,about,a11yhelp,dialogadvtab,bidi,blockquote,clipboard,button,panelbutton,panel,floatpanel,templates,menu,contextmenu,copyformatting,div,resize,enterkey,entities,popup,filebrowser,find,fakeobjects,flash,floatingspace,listblock,richcombo,font,forms,format,horizontalrule,htmlwriter,iframe,indent,indentblock,indentlist,smiley,menubutton,language,link,magicline,maximize,newpage,pagebreak,pastetext,pastefromword,removeformat,showblocks,showborders,specialchar,scayt,stylescombo,tab,undo,wsc,selectall';//basicstyles,table,tabletools,colorbutton,colordialog,list,liststyle,justify,
    //CKEDITOR.dtd.$removeEmpty.i = 0;
    config.toolbar =[
            ['Font','FontSize', 'Bold','Italic','Underline','Strike','-', 'Blockquote', '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
            ['BidiLtr','BidiRtl'],
            ['TextColor','BGColor'],
            ['customimage','customsmiley','Link',],
            ['Flash','customfiles','Table','-','Outdent','Indent'],
            ['NumberedList','BulletedList', 'HorizontalRule'],
            ['Styles','Format'],['-'], ['Paste','PasteText','PasteFromWord'],
            ['-','Source'],
            ['Maximize']
            ];
    
};
