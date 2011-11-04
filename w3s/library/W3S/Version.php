<?php
/**
 * Class to store and retrieve the version of W3S Library
 * @category   W3S
 * @package    W3S_Version
 * @copyright  Copyright (c) 2011-2011 w3s.nl (http://www.w3s.nl)
 * @license    MIT License
 * @author     Andreas Warnaar <andreas@w3s.nl>
 * @version    1.0
 */
class W3S_Version {
    const VERSION = '0.2 beta';

    public function getVersion() {
        return self::VERSION;
    }
    
    public function __toString() {
        return self::VERSION;
    }
}
