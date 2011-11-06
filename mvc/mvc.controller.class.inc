<?php
class Controller {
  
  /**
   * Primary identity key for this entity type
   * i.e. 'nid' for node
   * @var string Primary-Identifier
   */
  public $primary;
  
  /**
   * Routing prefix (defaults to the name of controller)
   * @var string 
   */
  public $prefix;

  
  public function __construct() {

  }

  /**
   * Builds the prefix (defaults to the name of the controller
   */
  protected function buildPrefix() {
    $className = get_class($this);
    $className = strtolower(str_replace('_', '/', $className));
    $this->prefix = $className;
  }
  
  /**
   * Alias of drupal_set_message
   * @param string $message
   * @param string $type
   */
  protected function message($message, $type = NULL) {
    drupal_set_message($message, $type);
  }
  
  /**
   * Return a specific status code
   * @Todo: Needs to be expanded
   */
  protected function statusCode($status) {
    switch($status) {
      case 404:
        $return = drupal_not_found();
        break;
      case 403:
        $return = drupal_access_denied();
        break;
      case 500:
        $return = drupal_site_offline();
        break;
    }
    
    return $return;
  }
  
  /** 
   * Alias of drupal_goto
   * @param string $url
   * @param string|array $queryString
   */
  protected function redirect($url, $queryString = NULL) {
    drupal_goto($url, $queryString);
  }

  /**
   * Formats an array as XML
   * @param array $object
   * @param SimpleXMLElement $xmlDocument
   * @return string XML
   */
  public function toXML($object, &$xmlDocument = FALSE) {
    if (!$xmlDocument){
      $xmlDocument = new SimpleXMLElement("<{$this->prefix}/>");
    }

    if (is_string($object)) {
      $xmlDocument->addChild("item","$object");
    }
    else if (is_array($object)) {
      foreach($object as $key => $value) {
        if(is_array($value)) {
          if(!is_numeric($key)){
            $this->toXML($value, $xmlDocument->addChild("$key"));
          }
          else{
            $this->toXML($value, $xmlDocument);
          }
        }
        else {
          $xmlDocument->addChild("$key","$value");
        }
      }
    }
    return $xmlDocument->asXML();
  }

  /**
   * Formats the request as JSON or XML
   * @param string $format json or xml
   * @param array $data the data to be rendered
   * @return string $build
   */
  public function format($format, $data) {
    switch($format) {
      case 'json':
        drupal_json_output($data);
        exit;
        break;
      case 'xml':
        $build = $this->toXML($data);
        break;
      default:
        return drupal_not_found();
        break;
    }

    return $build;
  }
}