<?php

namespace Files;

use \App;

/**
 * A filesystem or URL path
 */
class Path {

    /**
     * The path split into parts/subdirectories
     * @var string[]
     */
    private $parts = [];

    /**
     * Parses the given string into a new `Path` object
     * 
     * @param string $path Parts of the path to parse
     */
    public function __construct(string ...$paths) {
        foreach($paths as $path) {
            foreach(explode('/', str_replace('\\', '/', $path)) as $part) {
                if(strlen($part) > 0) {
                    $this->parts[] = $part;
                }
            }
        }
    }

    /**
     * Assembles the path into a string
     */
    public function __toString(): string {
        return implode('/', $this->parts);
    }

    /**
     * Returns the length (numer of elements) of this path
     */
    public function length(): int {
        return count($this->parts);
    }

    /**
     * Parts of the path split by `/` or `\` characters
     */
    public function getParts(): array {
        return $this->parts;
    }

    /**
     * Returns the last element of the path.
     */
    public function filename(): string {
        $len = count($this->parts);
        return $len > 0 ? $this->parts[$len - 1] : '';
    }

    /**
     * Returns a copy of this path that is prepended with the specified directory path
     * 
     * @param Path $prepend The path to prepend
     */
    public function prepend(Path $prepend): Path {
        $new = new self('');
        $new->parts = array_merge($prepend->parts, $this->parts);
        return $new;
    }

    /**
     * Returns the full path to the resource pointed to by this path.
     * Treats the path as relative to the app root directory.
     * 
     * @param App $app The app
     */
    public function rooted(App $app): Path {
        return $this->prepend($app->getRootDir());
    }

    /**
     * Returns `true` if the path contains the app root directory and `false` otherwise.
     * 
     * @param App $app The app
     */
    public function isRooted(App $app): bool {
        return $this->startsWith($app->getRootDir());
    }

    /**
     * Returns `true` if this path starts with the given path
     * 
     * @param Path $path The path to check
     */
    public function startsWith(Path $path): bool {
        return $path->length() === 0 || strpos($this.'', $path.'') === 0;
    }

    /**
     * Returns a new path that is a copy of this path, relative to the given path.
     * 
     * @param Path $path The "root" path
     */
    public function relativeTo(Path $path): Path {
        $pathLen = count($path->parts);
        if(!$this->startsWith($path)) {
            die("This path does not contain the given path.");
        }

        $result = new self($this.'');
        $i = 0;
        while($i < $pathLen && $path->parts[$i] === $result->parts[0]) {
            array_shift($result->parts);
            $i++;
        }
        return $result;
    }

    /**
     * Returns `true`, if the path points to an existing, readable file or `false` otherwise.
     * 
     * @param string $root_dir Root directory path that will be prepended
     *  to this path before checking if the file exists
     */
    public function isReadableFile(): bool {
        return is_readable($this) && is_file($this);
    }

    /**
     * Returns `true`, if the path points to an existing public resource or `false` otherwise.
     * Treats the path as relative to the app root directory.
     * 
     * @param App $app The app
     */
    public function isPublicResource(App $app): bool {
        // Long enough
        return count($this->parts) >= 2
        // Inside public directory
        && in_array($app->getPublicDirName(), $this->parts)
        // Is readable file
            && $this->prepend($app->getRootDir())->isReadableFile();
    }

}
