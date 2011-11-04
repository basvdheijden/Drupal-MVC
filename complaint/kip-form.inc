<?php

function complaint_from($from, $from_state){
  $from = array();
  $form['element_1'] = array(
      '#type' => 'textfield',
      '#title' => 'iets',
      '#description' => 'KIP ',
  );

  $form['submit'] = array(
      "#value" => 'Verstuur',
      '#type' => 'submit'
  );
  return $form;
}
