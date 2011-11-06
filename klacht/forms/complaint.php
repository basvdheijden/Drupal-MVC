<?php
class Form_Complaint {
  
  public $formData;
  public $form;
  
  public function __construct(){
    
  }
  
  /**
   * Populate the form with data
   * @param array $formData
   * @return Form_Complaint 
   */
  public function populate($formData = array()){
    return $this;
  }
  
/**
 * Return a drupal form array
 * @param array $fields
 * @return array 
 */
  public function build($fields = array()){
    
    $form = array();
    if(count($fields)){
      $form = array_merge($form,$fields);
    }
    
    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => 'Titel',
      '#default_value' => '',
    );
    
    $form['body[und][0][value]'] = array(
      '#type' => 'textarea',
      '#title' => 'Inhoud',
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Nieuwe klacht indienen',
    );
    
    $this->form = $form;
    return $this;
  }
  
  public function render(){
    foreach($this->form as $element){
      
    }
    return $this->form;
  }


  public function validate(){
    
  }
  
  public function __toString(){
    drupal_render_children($this->form);
  }
 
  public function getElementName(){
    
  }
  
}
