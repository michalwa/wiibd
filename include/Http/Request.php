<?php

namespace Http;

use \App;
use Files\Path;
use Auth\Credentials;

/**
 * Contains information about an HTTP request being processed
 */
class Request {

    /**
     * Original, unparsed URL of the request
     * @var string
     */
    private $unparsed;

    /**
     * HTTP request method
     * @var string
     */
    private $method;

    /**
     * Credentials specified in the URL
     * @var Credentials
     */
    private $credentials;

    /**
     * The requested Path specified in the URL
     * @var Path
     */
    private $path;

    /**
     * Parsed query parameters
     * @var array
     */
    private $query;

    /**
     * `POST` parameters
     * @var array
     */
    private $post;

    /**
     * HTTP request headers
     * @var Headers
     */
    private $headers;

    /**
     * Resolved route name
     * @var string
     */
    private $routeName = '<unresolved>';

    /**
     * Constructs a new `Request` object
     *
     * @param string $method The request method used
     * @param string $url The requested URL
     * @param array $post Submitted `POST` parameters
     * @param array $post HTTP Headers attached to the request
     */
    public function __construct(
        string $method,
        string $url,
        $post,
        $headers
    ) {
        $this->unparsed = $url;
        $req = parse_url($url);

        $this->credentials = new Credentials($req['user'] ?? '', $req['pass'] ?? '');

        $this->path = (new Path($req['path']))->toRelative(App::getRootUrl());

        parse_str($req['query'] ?? '', $this->query);
        $this->method = $method;
        $this->post = $post;
        $this->headers = $headers;
    }

    /**
     * Returns a textual representation of this request
     */
    public function __toString(): string {
        return $this->method.' '.$this->unparsed;
    }

    /**
     * The method of this request
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * The credentials specified in the URL
     */
    public function getCredentials(): Credentials {
        return $this->credentials;
    }

    /**
     * The requested path specified in the URL
     */
    public function getPath(): Path {
        return $this->path;
    }

    /**
     * Returns an associative array of query parameters, if `param` is `null`.
     * Otherwise, returns the value of the query parameter with the given name.
     *
     * @param null|string $param Name of the parameter to return or `null`
     * @return array|string
     */
    public function getQuery(?string $param = null) {
        if($param === null) {
            return $this->query;
        } elseif(key_exists($param, $this->query)) {
            return $this->query[$param];
        } else {
            return null;
        }
    }

    /**
     * Returns an associative array of form parameters if `param` is `null`.
     * Otherwise, returns the value of the specified form parameter.
     *
     * @param null|string $param Name of the parameter to return or `null`
     * @return array|string
     */
    public function getPost(?string $param = null) {
        return $param !== null ? $this->post[$param] : $this->post;
    }

    /**
     * HTTP headers attached to the request
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * Sets the route name
     *
     * @param string $routeName The new route name
     */
    public function setRouteName(string $routeName) {
        $this->routeName = $routeName;
    }

    /**
     * Returns the route name for this request
     */
    public function getRouteName(): string {
        return $this->routeName;
    }

    /**
     * Constructs a request based on the current context
     *
     * @param App $app The app
     */
    public static function get(): self {
        return new self(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_POST,
            self::currentHeaders());
    }

    /**
     * Reads and retruns all headers from the current context
     */
    public static function currentHeaders() {
        $headers = [];
        foreach($_SERVER as $key => $value) {

            // HTTP_HEADER_NAME -> Header-Name
            if(strpos($key, 'HTTP_') === 0) {
                $name = ucwords(str_replace('_', '-', strtolower(substr($key, 5))), '-');
                $headers[$name] = $value;
            }
        }
        return $headers;
    }

}
