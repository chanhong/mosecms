<?php

// type: text, spacer, radio, list
/* sample
  '2'=>array(
  'name'=>'spacer1',
  'type'=>'spacer',
  'default'=>'Debug Setting',
  'desc'=>'Debug Setting',
  'label'=>'>>> Debug'
  ),
  '3'=>array(
  'name'=>'debug',
  'type'=>'radio',
  'default'=>'0',
  'desc'=>'use this to turn debug On/Off',
  'label'=>'Radio On/Off',
  'value'=>array("1"=>'True',"0"=>'False'),
  ),
  '4'=>array(
  'name'=>'debuglist',
  'type'=>'list',
  'default'=>'id',
  'desc'=>'Section Order by',
  'label'=>'Debug On/Off',
  'value'=>array(''=>'none',"id"=>'id',"title"=>'title','ordering'=>'ordering'),
  ),
 */
$params = array(
    '0' => array(
        'name' => 'foldername',
        'type' => 'text',
        'default' => 'mosmodule',
        'desc' => 'Folder where MosModule will execute php script',
        'label' => 'FolderName'
    ),
);
?>