<?php

namespace Files;

use Validation\Validator;

/**
 * Matches & captures path elements
 */
class PathPattern {

    /** Literal path part element type */
    private const LITERAL = 'LITERAL';
    /** Parameter element type */
    private const PARAM = 'PARAM';
    /** Validated parameter element type */
    private const PARAM_VALIDATED = 'PARAM_VALIDATED';

    /**
     * The elements of this pattern.
     *
     * Each entry is either of these:
     * - `[LITERAL, '<literal>']`
     * - `[PARAM, '<param_name>']`
     * - `[PARAM_VALIDATED, '<param_name>', '<validator_name>']`
     *
     * @var array
     */
    private $elements = [];

    /**
     * Parses and constructs a path pattern
     *
     * The pattern expression consists of elements separated by slashes (`/`), each of which
     * is either a literal path element or a parameter. Parameters are denoted with a name
     * in braces: `{parameter}`. A parameter can also use a validator to narrow down the set
     * of values it can take. Validators are specified after the parameter name and a colon:
     * `{parameter:validator}`, e.g. `{id:uint}`
     *
     * @param string $expr The expression to parse
     */
    public function __construct(string $expr) {
        foreach(explode('/', $expr) as $element) {
            if($element === '') {
                continue;
            } else if(preg_match('/\{(\w+)(:(\w+))?\}/', $element, $matches)) {
                if(count($matches) === 4) {
                    $this->elements[] = [self::PARAM_VALIDATED, $matches[1], $matches[3]];
                } else {
                    $this->elements[] = [self::PARAM, $matches[1]];
                }
            } else {
                $this->elements[] = [self::LITERAL, $element];
            }
        }
    }

    /**
     * Tries to match this pattern against the given path. If successful, returns `true`
     * and parameters get passed to `params`. Otherwise, `false` is returned.
     *
     * @param Path $path The path to match
     * @param string[] $params Matched and validated parameters
     */
    public function match(Path $path, &$params): bool {
        $params = [];

        $len = count($this->elements);
        if($path->length() !== $len) {
            return false;
        }

        for($i = 0; $i < $len; $i++) {
            $expect = $this->elements[$i];
            $actual = $path->getElement($i);

            if($expect[0] === self::PARAM_VALIDATED || $expect[0] === self::PARAM) {
                if($expect[0] === self::PARAM_VALIDATED) {
                    if(!Validator::validate($actual, $expect[2])) return false;
                }

                $params[$expect[1]] = $actual;
            } else {
                if($expect[1] !== $actual) return false;
            }
        }

        return true;
    }

    /**
     * Renders the pattern filling in the parameters with the specified values.
     *
     * @param mixed[string] $params Values for the parameters
     */
    public function render(array $params = []): Path {
        $path = new Path();
        foreach($this->elements as $elt) {
            if($elt[0] === self::LITERAL) {
                $path = $path->append($elt[1]);
            } else {
                $path = $path->append($params[$elt[1]] ?? '{'.$elt[1].'}');
            }
        }
        return $path;
    }

}
