<?php

/**
 * Contains commonly used global utility functions
 */

/**
 * Returns `true` if the length of the given iterable is equal to `0`.
 *
 * @param iterable $iterable The iterable to test
 */
function is_empty(iterable $iterable): bool {
    if(is_array($iterable)) {
        return count($iterable) === 0;
    }

    foreach($iterable as $_) {
        return false;
    }
    return true;
}

/**
 * Finds a predicate in the keys of `$cases` that returns `true` for `$value`,
 * then returns the value associated with that predicate or the result of calling the value
 * with `$value` as an argument, if the value is a function.
 *
 * If no predicate succeeds, uses the mapper associated with a `null` key or returns `$value` unchanged.
 *
 * Examples:
 *  - `predicate_map(3, [ 'is_object' => 'foo', null => 'bar' ])` returns `'bar'`
 *  - `predicate_map(null, [ 'is_null' => fn($_) => 'is null' ])` returns `'is null'`
 *  - `predicate_map(123, [])` returns `123`
 *  - `predicate_map(123, [ null => 456 ])` returns `456`
 *
 * @param mixed $value The value to map
 * @param callable[callable] $cases Predicates associated with mapping functions
 */
function predicate_map($value, $cases) {
    foreach($cases as $test => $mapper) {
        if(is_callable($test) && call_user_func($test, $value)) {
            if(is_callable($mapper)) {
                return call_user_func($mapper, $value);
            } else {
                return $mapper;
            }
        }
    }
    if(key_exists(null, $cases)) {
        return call_user_func($cases[null], $value);
    }
    return $value;
}

/**
 * Converts the given value to a string according to the following rules:
 *  - `null` gets converted to `'null'`
 *  - booleans get converted to `'true'` and `'false'` respectively
 *  - objects get converted to their class name enclosed in parentheses
 *  - strings get converted to their unescaped form, enclosed in single-
 *    or double-quotes if they contain escaped characters
 *  - arrays get converted to strings starting with `'['`,
 *    followed by each element also converted using `stringify()`
 *    separated with `', '` and ending with `']'`
 *
 * @param mixed $value The value to stringify
 *
 * @return string The stringified value
 */
function stringify($value): string {
    return predicate_map($value, [
        'is_null'   => 'null',
        'is_bool'   => fn($bool) => $bool ? 'true' : 'false',

        'is_object' => fn($obj) =>
            method_exists($obj, '__toString')
                ? $obj->__toString()
                : '<'.get_class($obj).'>',

        'is_string' => function($str) {
            $unesc = addcslashes($str, "\0..\37\\\"\177..\377"); // ASCII 000..037, \, ", 177..377
            if($unesc !== $str) {
                return '"'.$unesc.'"';
            }
            return "'".$str."'";
        },

        'is_array' => function($arr) {
            $assoc = array_keys($arr) !== range(0, count($arr) - 1);
            $str = '[';
            foreach($arr as $key => $item) {
                if($str !== '[') $str .= ', ';
                $str .= ($assoc ? stringify($key).' => ' : '').stringify($item);
            }
            return $str.']';
        },

        null => fn($any) => (string)$any
    ]);
}

/**
 * Shortcut for `array_map($callback, array_keys($array), $array)`
 */
function array_map_assoc($callback, array $array): array {
    return array_map($callback, array_keys($array), $array);
}
