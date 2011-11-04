<?php

class W3S_Template extends stdClass {

  public $html;

  public function __construct($viewScript = null, $string = null) {
    if ($viewScript !== null) {
      ob_start();
      include($viewScript);
      $this->html = ob_get_clean();
    }
    if ($string !== null) {
      $this->html = $string;
    }
  }

  public function __toString() {
    return $this->html;
  }

}