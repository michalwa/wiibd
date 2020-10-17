<?php

namespace Files;

use \App;

/**
 * A filesystem or URL path
 */
class Path {

    /**
     * The path split into elements/subdirectories
     * @var string[]
     */
    private $elements = [];

    /**
     * Constructs a new `Path` object from the given path elements.
     * Each argument can be either a string - in which case it will be split
     * into elements or another `Path`.
     *
     * @param mixed $paths Parts of the path to parse
     */
    public function __construct(...$paths) {
        foreach($paths as $path) {
            if($path instanceof self) {
                foreach($path->elements as $elt) {
                    $this->elements[] = $elt;
                }
            } else if(is_string($path)) {
                foreach(explode('/', str_replace('\\', '/', $path)) as $part) {
                    if(strlen($part) > 0) {
                        $this->elements[] = $part;
                    }
                }
            } else {
                throw new PathException('Invalid path element: '.$path);
            }
        }
    }

    /**
     * Assembles the path into a string
     */
    public function __toString(): string {
        return implode('/', $this->elements);
    }

    /**
     * Returns the length (numer of elements) of this path
     */
    public function length(): int {
        return count($this->elements);
    }

    /**
     * Returns the specified element of this path.
     *
     * @param int $index The index of the element to return
     */
    public function getElement(int $index): string {
        return $this->elements[$index];
    }

    /**
     * Sets the specified element of this path to the given value.
     *
     * @param int $index The index of the element to set
     * @param string $value The new value for the element
     */
    public function setElement(int $index, string $value): void {
        if($index >= $this->length()) {
            $this->elements[] = $value;
        } else {
            $this->elements[$index] = $value;
        }
    }

    /**
     * Returns the last element of the path.
     */
    public function lastElement(): string {
        $len = count($this->elements);
        return $len > 0 ? $this->elements[$len - 1] : '';
    }

    /**
     * Returns a copy of this path appended with the given path
     *
     * @param self|string $append The path to append
     */
    public function append($append): self {
        return new self($this, $append);
    }

    /**
     * Returns a copy of this path prepended with the given path
     *
     * @param self|string $prepend The path to prepend
     */
    public function prepend($prepend): self {
        return new self($prepend, $this);
    }

    /**
     * Returns `true` if this path starts with the given path
     *
     * @param self $path The path to check
     */
    public function startsWith(self $path): bool {
        return $path->length() === 0
            || $this->length() === 0
            || strpos($this.'', $path.'') === 0;
    }

    /**
     * Returns `true` if this path ends with the given path
     *
     * @param self $path The path to check
     */
    public function endsWith(self $path): bool {
        $thisStr = $this.'';
        $pathStr = $path.'';

        return $path->length() === 0
            || $this->length() === 0
            || strpos($thisStr, $pathStr) === strlen($thisStr) - strlen($pathStr);
    }

    /**
     * Returns a new path that is a copy of this path, relative to the given path.
     *
     * @param self $path The "root" path
     */
    public function toRelative(self $path): self {
        if(count($this->elements) == 0) {
            return $path;
        }

        $pathLen = count($path->elements);
        if(!$this->startsWith($path)) {
            throw new PathException('The path '.$this.' does not start with '.$path);
        }

        $result = new self($this);
        $i = 0;
        while($i < $pathLen && $path->elements[$i] === $result->elements[0]) {
            array_shift($result->elements);
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
     */
    public function isPublicResource(): bool {
        $app = App::get();
        // Long enough
        return count($this->elements) >= 2
            // Inside public directory
            && $this->startsWith(new self($app->getConfig('app.publicDir')))
            // Is readable file
            && $this->prepend($app->getRootDir())->isReadableFile();
    }

}
