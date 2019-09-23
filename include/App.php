<?php

use Files\Path;

/**
 * Stores app configuration and environment
 */
class App {

    /**
     * The path to the root directory of the app
     * @var Path
     */
    private $rootDir;

    /**
     * The app configuration
     * @var Config
     */
    private $config;

    /**
     * Constructs a new `App` object
     * 
     * @param string $rootDir The path to the root directory of the app. If constructing
     *  from index.php in root directory, use `__DIR__`.
     * @param Config $config Loaded app configuration
     */
    public function __construct(string $rootDir, Config $config) {
        $this->rootDir = new Path($rootDir);
        $this->config = $config;
    }

    /**
     * The path to the root directory of the app.
     */
    public function getRootDir(): Path {
        return $this->rootDir;
    }

    /**
     * Returns the specified configuration option. If no such option exists,
     * return `$default`. Nested array accesses are concatenated into one string joined with dots,
     * e.g. to access `config['app']['rootUrl']` call `get('app.rootUrl')`
     * 
     * @param string $option Name of the option to return
     */
    public function getConfig(string $option) {
        return $this->config->get($option);
    }

    /**
     * Constructs and returns a `Path` object based on the config option `app.rootUrl`
     */
    public function getRootUrl(): Path {
        return new Path($this->config->get('app.rootUrl'));
    }

    /**
     * Returns the full URL path to the specified public resource
     * 
     * @param string $resource The path to the resource (relative to the public directory)
     */
    public function getPublicUrl(string $resource): string {
        return '/'.(new Path(
            $this->config->get('app.rootUrl'),
            $this->config->get('app.publicDir'),
            $resource));
    }

}
