<?php

use Files\Path;

/**
 * Reads and stores the configuration file
 */
class Config {

    /**
     * The actual loaded configuration object
     */
    private $config;

    /**
     * Loads the specified configuration file
     * 
     * @param string $filename The name of the file to load the configuration from
     */
    public function __construct(string $filename) {
        if((new Path($filename))->isReadableFile()) {
            $this->config = parse_ini_file($filename, true);
        }
    }

    /**
     * Returns the specified configuration option. If no such option exists,
     * return `$default`. Nested array accesses are concatenated into one string joined with dots,
     * e.g. to access `config['app']['rootUrl']` call `get('app.rootUrl')`
     * 
     * @param string $option Name of the option to return
     * @param string $default The default/fallback value
     */
    public function get(string $option, string $default = '') {
        $value = $this->config;
        foreach(explode('.', $option) as $key) {
            $value = $value[$key];
            if($value === null) {
                return $default;
            }
        }
        return $value;
    }

}
