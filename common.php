<?php

/**
 * Contains commonly used global utility functions
 */

/**
 * Converts the given object in to a string
 * 
 * @param mixed $obj The object to stringify
 */
function stringify($obj) {
    if(is_null($obj)) {
        return 'null';
    } else if(is_object($obj)) {
        return get_class($obj);
    } else if(is_string($obj)) {
        return '"'.stripcslashes($obj).'"';
    } else if(is_array($obj)) {
        $str = '[ ';
        foreach($obj as $item) {
            if($str !== '[ ') $str .= ', ';
            $str .= stringify($item);
        }
        return $str.' ]';
    }
}
