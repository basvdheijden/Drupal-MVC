<?php
class Klacht extends Controller {
  /*
   * Override prefix
   */
  public $prefix = 'klacht';
  
  /**
   *
   * @var Complaint
   */
  public $Complaint;
  public $theme = __CLASS__;
  
  public function __construct() {
    module_load_include('class', 'klacht','complaint');
    $this->Complaint = new Complaint();
  }
  
  /**
  * @Type: MENU_NORMAL_ITEM
  * @Access arguments: access content
  * @Title: Post a Klacht
  * @Themed
  */
  public function Post_KlachtAction($id = Controller::NEW_INSTANCE, $name) {
    $transaction = db_transaction();
    try{
    $this->Complaint->load($id);
    $return = '';
    if($this->Complaint->save()){
      $this->Complaint->title->set($name);
      $return = "Je hebt klacht {$this->Complaint->title->value()} met id {$id} opgeslagen als {$name}.";
    }
    }  catch (Exception $e){
      $transaction->rollback();
    }
    return $return;
  }

  /**
   * @Type: MENU_NORMAL_ITEM
   * @Access arguments: access content
   * @Title: Add a new klacht
   * @Form
   */
  public function NewAction() {
    //if ($form->isPosted() && $form->isValid()) {
      //run submit action
    //}
    //else {
      //display form
    //}
    
    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => 'Titel',
      '#default_value' => '',
    );
    
    $form['body'] = array(
      '#type' => 'textarea',
      '#title' => 'Inhoud',
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Nieuwe klacht indienen',
    );
    
    
    $f = new Form();
    $f->addFields($form);

    return $f;
  }
  
  /**
  * @Type: MENU_NORMAL_ITEM
  * @Access arguments: access content
  * @Title: Homepage voor klachten
  * @Cache: CONTROLLER_CACHE_PER_USER:86400
  */
  public function IndexAction() {
    
//    $this->Complaint->load(1);
//    var_dump($this->Complaint->body->value());die;
    
//    $this->Complaint->body->value->set('Koekjes voor kip');
//    $this->Complaint->save();
    
    
    global $user;
    return "Homepage voor klachten voor gebruiker: " . ( isset($user->name) ? $user->name : 'Anonieme gebruiker' );
  }
  
  /**
  * @Type: MENU_CALLBACK
  * @Access arguments: access content
  * @Title: Get a Klacht
  * @Cache: CONTROLLER_CACHE_PATH:86400
  * @Format: json|xml
  */
  public function GetAction($id, $name) {
    $build = array(
      'data' => 'kip',
      'id'=> $id,
      'name'=> $name,
    );

    return $build;
  }

  /**
  * @Type: MENU_LOCAL_TASK
  * @Access arguments: access content
  * @Title: Overview Page
  * @Themed
  * @Cache: CONTROLLER_CACHE_GLOBAL:10
  */  
  public function OverviewAction() {
    $a = new stdClass();
    $a->name = 'Bas';
    return $a;
    
    //return $this->render('custom path to view', $a);
  }

}