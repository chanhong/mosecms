<?php

// ================================================
// SPAW v.2.0
// ================================================
// English language file
// ================================================
// Author: Alan Mendelevich, UAB Solmetra
// Translated: Szentgy�rgyi J�nos, info@dynamicart.hu
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.2.0
// ================================================
// charset to be used in dialogs
$spaw_lang_charset = 'iso-8859-2';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = array(
    'cut' => array(
        'title' => 'Kiv�g�s'
    ),
    'copy' => array(
        'title' => 'M�sol'
    ),
    'paste' => array(
        'title' => 'Beilleszt'
    ),
    'undo' => array(
        'title' => 'Visszavon�s'
    ),
    'redo' => array(
        'title' => 'M�gis'
    ),
    'image_prop' => array(
        'title' => 'K�p',
        'ok' => '   OK   ',
        'cancel' => 'M�gsem',
        'source' => 'Forr�s',
        'alt' => 'Alternat�v sz�veg',
        'align' => 'Igaz�t�s',
        'left' => 'Balra',
        'right' => 'Jobbra',
        'top' => 'Fentre',
        'middle' => 'K�z�pre',
        'bottom' => 'Lentre',
        'absmiddle' => 'Teljesen k�z�pre',
        'texttop' => 'Sz�vegtetej�re',
        'baseline' => 'Alapvonalra',
        'width' => 'Sz�less�g',
        'height' => 'Magass�g',
        'border' => 'Keret',
        'hspace' => 'V�zszintes hely',
        'vspace' => 'F�gg�leges hely',
        'error' => 'Hiba',
        'error_width_nan' => 'Sz�less�g nem egy sz�m',
        'error_height_nan' => 'Magass�g nem egy sz�m',
        'error_border_nan' => 'Keret nem egy sz�m',
        'error_hspace_nan' => 'V�zszintes hely nem egy sz�m',
        'error_vspace_nan' => 'F�gg�leges hely nem egy sz�m',
    ),
    'flash_prop' => array(// <= new in 2.0
        'title' => 'Flash',
        'ok' => '   OK   ',
        'cancel' => 'M�gse',
        'source' => 'Forr�s',
        'width' => 'Sz�less�g',
        'height' => 'Magass�g',
        'error' => 'Hiba',
        'error_width_nan' => 'Sz�less�g nem egy sz�m',
        'error_height_nan' => 'Magass�g nem egy sz�m',
    ),
    'inserthorizontalrule' => array(// <== v.2.0 changed from hr
        'title' => 'V�zszintes elv�laszt�'
    ),
    'table_create' => array(
        'title' => 'T�bla k�sz�t�s'
    ),
    'table_prop' => array(
        'title' => 'T�bla tulajdons�gok',
        'ok' => '   OK   ',
        'cancel' => 'M�gse',
        'rows' => 'Sorok',
        'columns' => 'Oszlopok',
        'css_class' => 'CSS oszt�ly',
        'width' => 'Sz�less�g',
        'height' => 'Magass�g',
        'border' => 'Keret',
        'pixels' => 'Pixelek',
        'cellpadding' => 'Cella kit�lt�se',
        'cellspacing' => 'Cell�k k�z�tti hely',
        'bg_color' => 'H�tt�rsz�n',
        'background' => 'H�tt�rk�p',
        'error' => 'Hiba',
        'error_rows_nan' => 'Sorok nem egy sz�m',
        'error_columns_nan' => 'Oszlopok nem egy sz�m',
        'error_width_nan' => 'Sz�less�g nem egy sz�m',
        'error_height_nan' => 'Magass�g nem egy sz�m',
        'error_border_nan' => 'Keret nem egy sz�m',
        'error_cellpadding_nan' => 'Cella kit�lt�s nem egy sz�m',
        'error_cellspacing_nan' => 'Cell�k k�z�tti hely nem egy sz�m',
    ),
    'table_cell_prop' => array(
        'title' => 'Cella tulajdons�gok',
        'horizontal_align' => 'V�zszintes igaz�t�s',
        'vertical_align' => 'F�gg�leges igaz�t�s',
        'width' => 'Sz�less�g',
        'height' => 'Magass�g',
        'css_class' => 'CSS oszt�ly',
        'no_wrap' => 'Nincs t�r�s',
        'bg_color' => 'H�tt�rsz�n',
        'background' => 'H�tt�rk�p',
        'ok' => '   OK   ',
        'cancel' => 'M�gse',
        'left' => 'Balra',
        'center' => 'K�z�pre',
        'right' => 'Jobbra',
        'top' => 'Fentre',
        'middle' => 'K�z�pre',
        'bottom' => 'Lentre',
        'baseline' => 'Alapvonalra',
        'error' => 'Hiba',
        'error_width_nan' => 'Sz�less�g nem egy sz�m',
        'error_height_nan' => 'Magass�g nem egy sz�m',
    ),
    'table_row_insert' => array(
        'title' => 'Sor besz�r�sa'
    ),
    'table_column_insert' => array(
        'title' => 'Oszlop besz�r�sa'
    ),
    'table_row_delete' => array(
        'title' => 'Sor t�rl�se'
    ),
    'table_column_delete' => array(
        'title' => 'Oszlop t�rl�se'
    ),
    'table_cell_merge_right' => array(
        'title' => 'Cell�k egyes�t�se jobbra'
    ),
    'table_cell_merge_down' => array(
        'title' => 'Cell�k egyes�t�se lefele'
    ),
    'table_cell_split_horizontal' => array(
        'title' => 'Cell�k v�zszintes sz�tszak�t�sa'
    ),
    'table_cell_split_vertical' => array(
        'title' => 'Cell�k f�gg�leges sz�tkasz�t�sa'
    ),
    'style' => array(
        'title' => 'St�lus'
    ),
    'fontname' => array(// <== v.2.0 changed from font
        'title' => 'Bet�'
    ),
    'fontsize' => array(
        'title' => 'M�ret'
    ),
    'formatBlock' => array(// <= v.2.0: changed from paragraph
        'title' => 'Bekezd�s'
    ),
    'bold' => array(
        'title' => 'F�lk�v�r'
    ),
    'italic' => array(
        'title' => 'D�lt'
    ),
    'underline' => array(
        'title' => 'Al�h�z�s'
    ),
    'strikethrough' => array(
        'title' => '�th�z�s'
    ),
    'insertorderedlist' => array(// <== v.2.0 changed from ordered_list
        'title' => 'Sz�moz�s'
    ),
    'insertunorderedlist' => array(// <== v.2.0 changed from bulleted list
        'title' => 'Felsorol�s'
    ),
    'indent' => array(
        'title' => 'Beh�z�s n�vel�se'
    ),
    'outdent' => array(// <== v.2.0 changed from unindent
        'title' => 'Beh�z�s cs�kkent�se'
    ),
    'justifyleft' => array(// <== v.2.0 changed from left
        'title' => 'Balra'
    ),
    'justifycenter' => array(// <== v.2.0 changed from center
        'title' => 'K�z�pre'
    ),
    'justifyright' => array(// <== v.2.0 changed from right
        'title' => 'Jobbra'
    ),
    'justifyfull' => array(// <== v.2.0 changed from justify
        'title' => 'Sorkiz�r�s'
    ),
    'fore_color' => array(
        'title' => 'Sz�n'
    ),
    'bg_color' => array(
        'title' => 'H�tt�rsz�n'
    ),
    'design' => array(// <== v.2.0 changed from design_tab
        'title' => 'V�lt�s a WYSWYG (design) m�dra'
    ),
    'html' => array(// <== v.2.0 changed from html_tab
        'title' => 'V�lt�s a HTML (k�d) m�dra'
    ),
    'colorpicker' => array(
        'title' => 'Sz�nv�laszt�',
        'ok' => '   OK   ',
        'cancel' => 'M�gse',
    ),
    'cleanup' => array(
        'title' => 'HTML tiszt�t�s (st�lusokat megsz�ntet)',
        'confirm' => 'Ezzel a cselekedettel t�rli az alkalmazott st�lusokat, bet�t�pusokat �s a f�l�sleges adatokat a jelen dokumentumban. Valamennyi vagy minden form�z�s el fog veszni.',
        'ok' => '   OK   ',
        'cancel' => 'M�gse',
    ),
    'toggle_borders' => array(
        'title' => 'Szeg�ly megmutat�sa',
    ),
    'hyperlink' => array(
        'title' => 'Hiperhivatkoz�s',
        'url' => 'Hivatkozott c�m (URL)',
        'name' => 'N�v',
        'target' => 'C�l',
        'title_attr' => 'C�m',
        'ok' => '   OK   ',
        'cancel' => 'M�gse',
    ),
    'hyperlink_targets' => array(// <=== new 1.0.5
        '_self' => 'saj�t keret (_self)',
        '_blank' => '�j keret (_blank)',
        '_top' => 'legfels� keret (_top)',
        '_parent' => 'f� keret (_parent)'
    ),
    'unlink' => array(// <=== new v.2.0
        'title' => 'Hiperhivatkoz�s elt�vol�t�sa'
    ),
    'table_row_prop' => array(
        'title' => 'Sor tulajdons�gai',
        'horizontal_align' => 'V�zszintes igaz�t�s',
        'vertical_align' => 'F�gg�eges igaz�t�s',
        'css_class' => 'CSS oszt�ly',
        'no_wrap' => 'Nincs csomagol�s',
        'bg_color' => 'H�tt�rsz�n',
        'ok' => '   OK   ',
        'cancel' => 'M�gse',
        'left' => 'Balra',
        'center' => 'K�z�pre',
        'right' => 'Jobbra',
        'top' => 'Tetej�re',
        'middle' => 'K�z�pre',
        'bottom' => 'Alj�ra',
        'baseline' => 'Alapvonalra',
    ),
    'symbols' => array(
        'title' => 'Speci�lis karakterek',
        'ok' => '   OK   ',
        'cancel' => 'M�gse',
    ),
    'templates' => array(
        'title' => 'Sablonok',
    ),
    'page_prop' => array(
        'title' => 'Oldal tulajdons�gok',
        'title_tag' => 'C�me',
        'charset' => 'Karakter t�pus',
        'background' => 'H�tt�rk�p',
        'bgcolor' => 'H�tt�rsz�n',
        'text' => 'Sz�veg sz�ne',
        'link' => 'Hivatkoz�s sz�ne',
        'vlink' => 'L�togatott hivatkoz�s sz�ne',
        'alink' => 'Akt�v hivatkoz�s sz�ne',
        'leftmargin' => 'Bal marg�',
        'topmargin' => 'Tet� marg�',
        'css_class' => 'CSS oszt�ly',
        'ok' => '   OK   ',
        'cancel' => 'M�gse',
    ),
    'preview' => array(
        'title' => 'El�n�zet',
    ),
    'image_popup' => array(
        'title' => 'El�ugr� k�p',
    ),
    'zoom' => array(
        'title' => 'Nagy�t�s',
    ),
    'subscript' => array(
        'title' => 'Als�index',
    ),
    'superscript' => array(
        'title' => 'Fels�index',
    ),
);
?>