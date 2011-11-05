<?php
function complaint_from($from, &$form_state){
  $form = array();
  
  $form['element_1'] = array(
      '#type' => 'textfield',
      '#title' => 'iets',
      '#description' => 'KIP ',
  );

  $form['submit'] = array(
      '#value' => 'Verstuur',
      '#type' => 'submit'
  );
  
  return $form;
}


