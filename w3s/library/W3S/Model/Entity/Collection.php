<?php
class W3S_Model_Entity_Collection extends W3S_Model_Entity_Collection_Abstract
{
  public $replaceTags = array();
  public $tagValues = array();
  public function getTags(){
    return $this->replaceTags;
  }
  public function getTagValues(){
    foreach($this->replaceTags as $tag => $trans){
      if(isset($this->$trans)){
        $values[$tag] = $this->$trans;
      }
      if(method_exists($this, $trans)){
        $values[$tag] = $this->$trans();
        if($values[$tag] instanceof W3S_Model_Entity_Abstract OR $values[$tag] instanceof W3S_Model_Entity_Collection_Abstract){
           $values[$tag] = $values[$tag]->getTagValues();
        }
      }
    }
    return $values;
  }
  
}