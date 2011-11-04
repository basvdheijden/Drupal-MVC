<?php
class Project_Entity_Base{
  
  /**
   * Constant defining a new instance to be created.
   */
  const NEW_INSTANCE = 'new';
  
   /**
   * Entity type 
   * @var string 
   */
  protected $_entityType = 'node';
  
  /**
   * Data Wrapper Entity_API
   * @var EntityDrupalWrapper
   */
  protected $_entity;
  
  /**
   * Primary identity key for this entity type
   * i.e. 'nid' for node
   * 
   * @var string Primary-Identifier
   */
  public $primary;
  
  /**
   * Entity node complaint id
   * @var int 
   */
  protected $_entityId;
  
  /**
   * Bundle type
   * @var string
   */
  protected $_bundle = 'page';
  
  /**
   * Entity metadata info
   * @var array 
   */
  protected $_entityInfo;
  
  public function __construct($id = null) {
    $this->getInfo();
    $this->_entityId = $id;
  }  
  
  public function load($id){
    /* Get entity information */
    if ($this->_entityId === self::NEW_INSTANCE) return $this->load($this->create());
    $this->_entity = entity_metadata_wrapper($this->_entityType,$this->_entityId);
    
    if (!$id) throw new InvalidArgumentException('No Id is given for entity');
  }
  
  /**
   *
   * @param string $bundle
   * @return Project_Entity_Base 
   */
  public function setBundle($bundle){
    $this->_bundle = $bundle;
    return $this;
  }

  /*
   * Load the metadata for this entity type
   * Sets the primary Id
   * Store it in the $_entityInfo
   * @return array
   */
  public function getInfo(){
    if(null === $this->_entityInfo){
      $this->_entityInfo = entity_get_info($this->_entityType);
      $this->primary = $this->_entityInfo['entity keys']['id'];
    }
    return $this->_entityInfo;
  }

  /**
   * @param bool $renew
   * @return EntityDrupalWrapper
   */
  public function getEntity($renew = false){
  
    if(null === $this->_entity OR ($renew === true)){
      $this->_entity = entity_metadata_wrapper($this->_entityType,$this->_entityId);
    }
    return $this->_entity;
  }
  
  /**
   * Create a new Entity
   * @return Project_Entity_Base::getEntity()
   */
  public function create() {
    try{
    $node = call_user_func($this->_entityInfo['creation callback'], array($this->_entityInfo['entity keys']['bundle'] => $this->_bundle));
    node_object_prepare($node);
    $node->body ='kip';
    node_save($node);
    $this->load($node->nid);
    var_dump($this->_entity);
    print_r($this->getEntity());
    die;
    }catch(Exception $e){
      echo $e;die;
    }
  }
 
  /**
   * @param string $offset
   * @return mixed
   *  $offset isset == false it returns null and triggers a User Notice error 
   */
  public function __get($offset) {
    if (isset($this->_entity->$offset)) {
      return $this->_entity->$offset;
    }
    trigger_error('Property "' . htmlentities($offset) . '" not found in : '.__CLASS__, E_USER_NOTICE);
    return null;
  }
  
  public function __set($name, $value) {
    $this->_entity->{$name} = $value;
  }
  
  /**
   * Saves the entity to the database
   * @return mixed
   * onsuccess return Project_Entity_Base
   */
  public function save() {
    if (isset($this->_entity)) {
      if($this->_entity->save()){
        return $this;
      }else{
        return false;
      }
    }
    throw new RuntimeException('No entity to save!');
  }
  
  public static function getWatchdogType(){
    return 'Project_Entity_Save';
  }
}