<?php
/**
 * Entity abstract class
 * 
 * @category   W3S_Model_Entity
 * @package    W3S_Model_Entity_Abstract
 * @copyright  Copyright (c) 2011-2011 w3s.nl (http://www.w3s.nl)
 * @license    MIT License
 * @author     Andreas Warnaar <andreas@w3s.nl>
 * @uses       W3S_Model_Entity_Abstract
 * @uses       W3S_Model_Entity_Collection_Abstract
 * @version    1.0
 */
abstract class W3S_Model_Entity_Abstract implements SeekableIterator, ArrayAccess, Countable {

    /**
     * @var array
     */
    protected $_data = array();

    /**
     * @param  array $data
     * @return void 
     */
    public function __construct(array $data) {
        $this->_data = $data;
    }

    /**
     * @return Model_EntityAbstract
     */
    public function rewind() {
        reset($this->_data);

        return $this;
    }

    /**
     * @return mixed
     */
    public function current() {
        return current($this->_data);
    }

    /**
     * @return mixed
     */
    public function key() {
        return key($this->_data);
    }

    /**
     * @return mixed
     */
    public function next() {
        return next($this->_data);
    }

    /**
     * @return mixed
     */
    public function valid() {
        return (current($this->_data) !== false);
    }

    /**
     * @param  unknown $position
     * @return void   
     */
    public function seek($position) {
        
    }

    /**
     * @return integer
     */
    public function count() {
        return count($this->_data);
    }

    /**
     * @param  mixed   $offset
     * @return boolean
     */
    public function offsetExists($offset) {
        return array_key_exists($offset, $this->_data);
    }

    /**
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet($offset) {
        return $this->__get($offset);
    }

    /**
     * @param  mixed                $offset
     * @param  mixed                $value 
     * @return Model_EntityAbstract
     */
    public function offsetSet($offset, $value) {
        $this->__set($offset, $value);

        return $this;
    }

    /**
     * @param  mixed                $offset
     * @return Model_EntityAbstract
     */
    public function offsetUnset($offset) {
        $this->__unset($offset);

        return $this;
    }

    /**
     * @param  mixed $offset
     * @return mixed
     */
    public function __get($offset) {
        if (array_key_exists($offset, $this->_data)) {
            return $this->_data[$offset];
        }
    }

    /**
     * @param  mixed $offset
     * @param  mixed $value 
     * @return void 
     */
    public function __set($offset, $value) {
        $this->_data[$offset] = $value;
    }

    /**
     * @param  mixed   $offset
     * @return boolean
     */
    public function __isset($offset) {
        return isset($this->_data[$offset]);
    }

    /**
     * @param  mixed $offset
     * @return void 
     */
    public function __unset($offset) {
        unset($this->_data[$offset]);
    }

    /**
     * @return array
     */
    public function toArray() {
        $returnData = array();
        foreach ($this->_data as $key => $value) {
            if ($value instanceof W3S_Model_Entity_Abstract) {
                $value = $value->toArray();
            } elseif ($value instanceof W3S_Model_Entity_Collection_Abstract) {
                $value = $value->toArray();
            }
            $returnData[$key] = $value;
        }
        return $returnData;
    }

}