<?php

/**
 * @category   W3S
 * @package    W3S_Error
 * @copyright  Copyright (c) 2010-2011 w3s.nl (http://www.w3s.nl)
 * @license    MIT License
 * @author     Andreas Warnaar <andreas@w3s.nl>
 * @version    1.0
 */
class W3S_Error {

  public function W3SErrorHandler($errno, $errstr, $errfile, $errline) {
    $old_error_handler = set_error_handler("W3SErrorHandler");
    $errors = array(1 => 'E_ERROR',
        4096 => 'E_RECOVERABLE_ERROR',
        2 => 'E_WARNING',
        4 => 'E_PARSE',
        8 => 'E_NOTICE',
        2048 => 'E_STRICT',
        8192 => 'E_DEPRECATED',
        16 => 'E_CORE_ERROR',
        32 => 'E_CORE_WARNING',
        64 => 'E_COMPILE_ERROR',
        128 => 'E_COMPILE_WARNING',
        256 => 'E_USER_ERROR',
        512 => 'E_USER_WARNING',
        1024 => 'E_USER_NOTICE',
        16384 => 'E_USER_DEPRECATED',
        30719 => 'E_ALL'
    );


    if (!(error_reporting() & $errno)) {
// This error code is not included in error_reporting
      return;
    }

    switch ($errno) {
      case E_USER_ERROR:
        echo '<pre>';
        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "Aborting...<br />\n";
        echo '</pre>';
        exit(1);
        break;

      case E_USER_WARNING:
        echo '<pre>';
        echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        echo '</pre>';
        break;

      case E_USER_NOTICE:
        echo '<pre>';
        echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        echo '</pre>';
        break;

      default:
        echo '<div style="position:absolute; background:#fff; z-index:9999; padding: 15px;">';
        echo '<pre>';
        echo $errors[$errno];
        __backtrace();
        echo "Unknown error type: [$errno] $errstr<br />\n";
        echo '</pre>';
        echo '</div>';
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
  }

// set to the user defined error handler

  function __backtrace() {
    $bt = debug_backtrace();
    echo("<br /><br />Backtrace (most recent call last):<br /><br />\n");
    for ($i = 0; $i <= count($bt) - 1; $i++) {
      if (!isset($bt[$i]["file"]))
        echo("[PHP core called function]<br />");
      else
        echo("File: " . $bt[$i]["file"] . "<br />");

      if (isset($bt[$i]["line"]))
        echo("&nbsp;&nbsp;&nbsp;&nbsp;line " . $bt[$i]["line"] . "<br />");
      echo("&nbsp;&nbsp;&nbsp;&nbsp;function called: " . $bt[$i]["function"]);

      if ($bt[$i]["args"]) {
        echo("<br />&nbsp;&nbsp;&nbsp;&nbsp;args: ");
//            for($j = 0; $j <= count($bt[$i]["args"]) - 1; $j++)
//            {
//                if(is_array($bt[$i]["args"][$j]))
//                {
//                    print_r($bt[$i]["args"][$j]);
//                }
//                else
//                    echo($bt[$i]["args"][$j]);   
//                           
//                if($j != count($bt[$i]["args"]) - 1)
//                    echo(", ");
//            }
      }
      echo("<br /><br />");
    }
  }

}

?>
