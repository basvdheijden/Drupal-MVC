<?php

abstract class W3S_Model_Abstract {

	 protected $_protectedColumns = array();
	 /**
	  * Static dbtable container
	  *
	  * @var array
	  */
	 protected static $_dbTables = array();
	 /**
	  * Default dbTable class name
	  *
	  * @var string
	  */
	 protected $_dbTableClass;
	 /**
	  * Default form class name
	  *
	  * @var string
	  */
	 protected $_formClass;
	 protected $_form;
	 /**
	  * Default entity class name
	  *
	  * @var string
	  */
	 protected $_entityClass = 'W3S_Model_Entity';
	 /**
	  * Default entity collection class name
	  *
	  * @var string
	  */
	 protected $_collectionClass = 'W3S_Model_Entity_Collection';

	 public function setProtectedColumns($columns = array()) {
		  if (is_array($columns)) {
				$this->_protectedColumns = $columns;
		  }
		  return $this;
	 }

	 /**
	  * Set dbtable in container
	  *
	  * @param  Zend_Db_Table_Abstract $dbTable
	  * @return W3S_Model_ModelAbstract
	  */
	 public function setDbTable(Zend_Db_Table_Abstract $dbTable) {
		  self::$_dataSource[get_class($dbTable)] = $dbTable;

		  return $this;
	 }

	 /**
	  * @param  unknown               $dbTableClass
	  * @param  array                 $options
	  * @return Zend_DbTable_Abstract
	  * @throws RuntimeException
	  */
	 public function getDbTable($dbTableClass = null, array $options = array()) {
		  if (null === $dbTableClass) {
				if (null === $this->_dbTableClass) {
					 throw new RuntimeException('Cannot return dbTable instance as dbTableClass is not set.');
				}
				$dbTableClass = $this->_dbTableClass;
		  }
		  if (!array_key_exists($dbTableClass, self::$_dbTables)) {
				self::$_dbTables[$dbTableClass] = new $dbTableClass($options);
		  } else {
				self::$_dbTables[$dbTableClass]->setOptions($options);
		  }
		  return self::$_dbTables[$dbTableClass];
	 }

	 /**
	  * Set form instance
	  *
	  * @param  Zend_Form           $form
	  * @return W3S_Model_ModelAbstract
	  */
	 public function setForm(Zend_Form $form) {
		  $this->_form = $form;

		  return $this;
	 }

	 /**
	  * Get form instance or load default if none set yet
	  *
	  * @param  array            $options
	  * @return Zend_Form
	  * @throws RuntimeException
	  */
	 public function getForm(array $options = null) {
		  if (null === $this->_form) {
				if (null === $this->_formClass) {
					 throw new RuntimeException('No form instance or formClass definition set');
				}
				$this->_form = new $this->_formClass($options);

				return $this->_form;
		  }
		  if (is_array($options)) {
				$this->_form->setOptions($options);
		  } elseif ($options instanceof Zend_Config) {
				$this->_form->setConfig($options);
		  }
		  return $this->_form;
	 }

	 /**
	  * @param  mixed                    $data         array|Zend_Db_Table_Row_Abstract
	  * @param  string                   $dbTableClass
	  * @return array                   
	  * @throws InvalidArgumentException
	  */
	 public function getFilteredData($data, $dbTableClass = null) {
		  $dbTable = $this->getDbTable($dbTableClass);

		  if ($data instanceof Zend_Db_Table_Row_Abstract) {
				$data = $data->toArray();
		  } elseif (!is_array($data)) {
				throw new InvalidArgumentException('Data must be an array or instance of Zend_Db_Table_Row_Abstract');
		  }

		  $info = $dbTable->info();
		  foreach ($data as $key => $value) {
				if (!in_array($key, $info['cols'], true)) {
					 //echo 'Not in table array :'. $key.PHP_EOL;
					 unset($data[$key]);
				} elseif (in_array($key, $this->_protectedColumns)) {
					 //echo 'Forbidden key: '.$key . PHP_EOL;
					 unset($data[$key]);
				} elseif ($info['metadata'][$key]['DEFAULT'] !== NULL && ($value === '' || $value === null)) {
					 unset($data[$key]);
					 //echo 'Empty :'.$key . PHP_EOL;
				} elseif ($info['metadata'][$key]['NULLABLE'] && ($value === '' || $value === null)) {
					 $data[$key] = new Zend_Db_Expr('NULL');
					 //echo 'Empty And null :'.$key . PHP_EOL;
				}
		  }

		  return $data;
	 }

	 /**
	  * Create entity instance from data
	  *
	  * @param  array                       $data
	  * @param  string                      $entityClass
	  * @return W3S_Model_Entity_EntityAbstract
	  */
	 public function toEntity($data, $entityClass = null) {
		  if (null === $data) {
				return;
		  } elseif ($data instanceof Zend_Db_Table_Row_Abstract) {
				$data = $data->toArray();
		  } elseif (!is_array($data)) {
				throw new InvalidArgumentException('Data must be an array or instance of Zend_Db_Table_Row_Abstract');
		  }

		  if (null === $entityClass) {
				if (null === $this->_entityClass) {
					 throw new RuntimeException('Cannot return entity instance as entityClass is not set.');
				}
				$entityClass = $this->_entityClass;
		  }
		  return new $this->_entityClass($data);
	 }

	 /**
	  * @param  mixed                           $data            array|Zend_Db_Table_Rowset_Abstract
	  * @param  string                          $entityClass
	  * @param  string                          $collectionClass
	  * @return W3S_Model_Entity_CollectionAbstract
	  * @throws InvalidArgumentException
	  */
	 public function toCollection($data, $entityClass = null, $collectionClass = null) {
		  if ($data instanceof Zend_Db_Table_Rowset_Abstract) {
				$data = $data->toArray();
		  } elseif (!is_array($data)) {
				throw new InvalidArgumentException('Data must be an array or instance of Zend_Db_Table_Rowset_Abstract');
		  }

		  if (null === $entityClass) {
				$entityClass = $this->_entityClass;
		  }

		  if (null === $collectionClass) {
				$collectionClass = $this->_collectionClass;
		  }

		  return new $collectionClass($data, $entityClass);
	 }

	 public function getPermaname($permaname, $id = null) {
		  $dbTable = $this->getDbTable();
		  $filterSanitize = new W3S_Filter_Sanitize();
		  $permaname = $filterSanitize->filter($permaname);

		  $select = $dbTable->select('COUNT(*)')
								->where($dbTable->getAdapter()->quoteInto('permaname LIKE ?', $permaname . '%'))
								->where('is_deleted != ?', 1);
		  $result = $dbTable->fetchAll($select);
		  if (count($result) > 1) {
				$highest = 1;
				foreach ($result as $row => $values) {
					 if (preg_match('/[0-9]$/', $values['permaname'], $match) AND $match[0] > $highest) {
						  $highest = $match[0];
					 }
				}
				$highest++;
				$permaname = $permaname . $highest;
		  }
		  return $permaname;
	 }

	 public function getUrlSlug($urlSlug, $field = 'url_slug') {
		  if (empty($urlSlug)) {
				throw new RuntimeException('UrlSlug is empty string');
		  }
		  $dbTable = $this->getDbTable();
		  $filterSanitize = new W3S_Filter_Sanitize();
		  $urlSlug = $filterSanitize->filter($urlSlug);

		  $select = $dbTable->select('COUNT(*)')
								->where($dbTable->getAdapter()->quoteInto($field . ' LIKE ?', $urlSlug . '%'))
								->where('is_deleted = ?', 0);
		  $result = $dbTable->fetchAll($select);
		  if (count($result) > 0) {
				$highest = 1;
				foreach ($result as $row => $values) {
					 if (preg_match('/[0-9]$/', $values[$field], $match) AND $match[0] > $highest) {
						  $highest = $match[0];
					 }
				}
				$highest++;
				$urlSlug = $urlSlug . $highest;
		  }
		  return $urlSlug;
	 }

}
