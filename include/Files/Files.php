<?php

namespace Files;

class Files {

    /**
     * Lists files and directories inside the specified directory.
     * `.` and `..` are excluded.
     * 
     * @param string $directory Path to the directory to scan
     */
    public static function scandir(string $directory): array {
        return array_diff(scandir($directory), ['.', '..']);
    }

    /**
     * `require`-s all files from the specified directory.
     * If `$instantiate` is `true`, files will be treated as class files and
     * the classes they contain will each be instantiated.
     * 
     * @param string $dir The directory to scan for files
     */
    public static function requireAll(string $dir, bool $instantiate = false): void {
        foreach(self::scandir($dir) as $file) {
            if(strpos($file, '.php') === strlen($file) - 4) {
                $path = new Path($dir, $file);
                require $path;

                $code = file_get_contents($path);
                if(preg_match('/namespace (.*?);/', $code, $matches)) {
                    $namespace = $matches[1].'\\';
                }

                if($instantiate) {
                    $className = $namespace.explode('.', $file)[0];
                    new $className();
                }
            }
        }
    }

}
