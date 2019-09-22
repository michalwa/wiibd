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
     * The root URL of the app
     * @var Path
     */
    private $rootUrl;

    /**
     * The name of the public resource directory. This directory resides in the root
     * directory and its contents can be accessed with a direct request.
     * @var string
     */
    private $publicDir = 'public';

    /**
     * The name of the directory from which views will be loaded. This directory must
     * reside in the root directory.
     * @var string
     */
    private $viewsDir = 'views';

    /**
     * The suffix, together with extension of view files in the views directory.
     * @var string
     */
    private $viewSuffix = '.view.php';

    /**
     * Constructs a new `App` object
     * 
     * @param string $rootDir The path to the root directory of the app. If constructing
     *  from index.php in root directory, use `__DIR__`.
     * @param string $rootUrl The root URL of the app. If the app is served using Apache
     *  and the files are located in a subdirectory, this must be the name of that directory.
     */
    public function __construct(
        string $rootDir,
        string $rootUrl
    ) {
        $this->rootDir = new Path($rootDir);
        $this->rootUrl = new Path($rootUrl);
    }

    /**
     * The path to the root directory of the app.
     * The returned directory path will not have a trailing slash.
     */
    public function getRootDir(): Path {
        return $this->rootDir;
    }

    /**
     * The root URL of the app
     */
    public function getRootUrl(): Path {
        return $this->rootUrl;
    }

    /**
     * The name of the public resource directory.
     */
    public function getPublicDirName(): string {
        return $this->publicDir;
    }

    /**
     * Returns the full URL path to the specified public resource
     * 
     * @param string $resource The path to the resource (relative to the public directory)
     */
    public function getPublicUrl(string $resource): string {
        return '/'.(new Path($this->rootUrl, $this->publicDir, $resource));
    }

    /**
     * Sets the name of the public resource directory.
     * 
     * @param string $publicDir The new name of the public resource directory
     */
    public function setPublicDirName(string $publicDir): void {
        $this->publicDir = $publicDir;
    } 

    /**
     * Returns the path to the file where the specified view is located.
     * 
     * @param string $view Name of the view to return the filename of
     */
    public function getViewFilename(string $view): string {
        return (new Path($this->viewsDir, $view.$this->viewSuffix))->prepend($this->rootDir);
    }

    /**
     * Sets the name of the directory containing view files
     * 
     * @param string $viewsDir The new name of the views directory
     */
    public function setViewsDirName(string $viewsDir): void {
        $this->viewsDir = $viewsDir;
    }

    /**
     * Sets the suffix (including extension) of view files
     * 
     * @param string $viewSuffix The new view file suffix
     */
    public function setViewSuffix(string $viewSuffix): void {
        $this->viewSuffix = $viewSuffix;
    }

}
