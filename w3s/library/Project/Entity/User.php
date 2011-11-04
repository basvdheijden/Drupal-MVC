<?php
class Project_Entity_User extends W3S_Model_Entity_Abstract{
  
  public function __construct($data) {
    parent::__construct((array)$data);
  }
  
  function getUserPicture($style = 'original'){
    if(isset($this->picture)){
    return file_create_url(image_style_path($style,$this->picture->uri));
    }
    return null;
  }
}
