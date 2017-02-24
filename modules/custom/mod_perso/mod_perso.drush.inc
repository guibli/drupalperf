<?php
function mod_perso_drush_command(){
  $items=array();
  $items['test'] = [
    'description' => 'test',
    'arguments' => [
      'name' =>'name test'
    ],
    'aliases' => ['tn'],
  ];
  return $items;
}

function drush_mod_perso_test($name='bitch'){
  drush_print('Waddup '. $name .'?!');
}
