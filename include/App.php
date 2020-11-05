<?php

use Files\Path;
use Routing\Router;

/**
 * Stores app configuration and environment
 */
class App {

    /**
     * The path to the root directory of the app
     * @var Path
     */
    private static $rootDir;

    /**
     * The app configuration
     * @var Config
     */
    private static $config;

    /**
     * The router
     * @var Router
     */
    private static $router;

    /**
     * Initializes and returns the app
     *
     * @param string $rootDir The path to the root directory of the app. If constructing
     *  from index.php in root directory, use `__DIR__`.
     * @param Config $config Loaded app configuration
     */
    public static function init(string $rootDir, Config $config) {
        self::$rootDir = new Path($rootDir);
        self::$config = $config;
        self::$router = new Router();
    }

    /**
     * The app name
     */
    public static function getName(): string {
        return self::getConfig('app.name');
    }

    /**
     * The path to the root directory of the app.
     */
    public static function getRootDir(): Path {
        return self::$rootDir;
    }

    /**
     * Returns the specified configuration option. If no such option exists,
     * return `$default`. Nested array accesses are concatenated into one string joined with dots,
     * e.g. to access `config['app']['rootUrl']` call `get('app.rootUrl')`
     *
     * @param string $option Name of the option to return
     * @param string $default The default/fallback value
     */
    public static function getConfig(string $option, string $default = '') {
        return self::$config->get($option, $default);
    }

    /**
     * Constructs and returns a `Path` object based on the config option `app.rootUrl`
     */
    public static function getRootUrl(): Path {
        return new Path(self::$config->get('app.rootUrl'));
    }

    /**
     * Returns the full URL path to the specified public resource
     *
     * @param string $resource The path to the resource (relative to the public directory)
     */
    public static function getPublicUrl(string $resource): string {
        return '/'.(new Path(
            self::$config->get('app.rootUrl'),
            self::$config->get('app.publicDir'),
            $resource));
    }

    /**
     * Returns the router
     */
    public static function getRouter(): Router {
        return self::$router;
    }

    /**
     * Shorthand for unparsing a URL for a controller route
     *
     * @param string $controller The class name of the controller containing the route
     * @param string $name The name of the route (handler)
     * @param mixed[string] $params The parameters for the route URL
     * @param mixed[string] $query The query parameters to be appended to the URL
     */
    public static function routeUrl(
        string $controller,
        string $name,
        array $params = [],
        array $query = []
    ): string {
        $url = self::$router->getRoute("$controller::$name")->unparseUrl($params);
        if($query !== []) {
            $url .= '?'.http_build_query($query);
        }
        return $url;
    }

}
