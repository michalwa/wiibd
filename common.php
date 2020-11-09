<?php

/**
 * Contains commonly used global utility functions
 */

/**
 * Escapes the given string to be safely displayed in HTML
 */
function htmlescape(string $unsafe): string {
    return htmlentities($unsafe, ENT_QUOTES, 'utf-8');
}

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
 *  - objects get converted to their class name enclosed in parentheses unless
 *    they implement `__toString()` which is called in such case
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
 * Echoes out the value stringified and HTML-escaped
 */
function dump($value): void {
    echo htmlentities(stringify($value));
}

/**
 * Shortcut for `array_map($callback, array_keys($array), $array)`
 */
function array_map_assoc($callback, array $array): array {
    return array_map($callback, array_keys($array), $array);
}

/**
 * Removes the first occurence of the specified value from the given array
 *
 * @param array $array The array to remove the element from
 * @param mixed $search The element to remove
 * @param bool $strict Whether to use strict comparison for searching
 */
function array_remove(array &$array, $search, bool $strict = true): void {
    array_splice($array, array_search($search, $array, $strict), 1);
}

/**
 * Appends the given arrays to the first array
 *
 * @param array $array The array to append to
 * @param array[] $new The arrays to append to the first array
 */
function array_append(array &$array, array ...$new): void {
    $array = array_merge($array, ...$new);
}

/**
 * Finds the index of the first character of the n-th line in the given
 * haystack string, where 0 is the first line.
 *
 * @param int $n The index of the line to find where 0 is the first line
 * @param string $haystack The string to search
 * @param string $linesep The line separator to use
 *
 * @return int|false The index of the first character of the n-th line or `false`
 *         if the line cannot be found
 */
function linepos(int $n, string $haystack, string $linesep = "\n") {
    $pos = 0;
    while($n-- > 0) {
        if(($pos = strpos($haystack, $linesep, $pos)) === false) return false;
        $pos += strlen($linesep);
    }
    return $pos;
}
