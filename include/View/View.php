<?php

namespace View;

use \Throwable;
use \App;
use Http\Response;
use Files\Path;
use Utils\NotFoundException;

/**
 * A renderable view template
 */
class View {

    /**
     * The template
     * @var string
     */
    private $template;

    /**
     * The template filename, if template loaded from file
     * @var null|string
     */
    private $file;

    /**
     * Constructs a new `View` object
     *
     * @param string $template The template to render
     * @param null|string $file The template filename, if template loaded from file
     */
    public function __construct(string $template, ?string $file = null) {
        $this->constructTrace = debug_backtrace();
        $this->template = $template;
        $this->file = $file;
    }

    /**
     * Renders this view and returns the resulting HTML document
     *
     * @param array $params Parameters for the template
     */
    public function render($params = []): string {
        ob_start();
        try {
            eval('?>'.$this->template);
            return ob_get_clean();
        } catch(Throwable $e) {
            ob_end_clean();
            throw new TemplateException($e, $this->file);
        }
    }

    /**
     * Renders this view and prepares an HTML response
     *
     * @param array $params Parameters for the template
     * @param int $status The HTTP status for the response
     */
    public function toResponse($params = [], int $status = 200): Response {
        $response = new Response($status);
        $response->setHeader('Content-Type', 'text/html; charset=utf-8');
        $response->setBody($this->render($params));
        return $response;
    }

    /**
     * Loads a view from an appropriate file based on the given name
     *
     * @param string $name The name of the view to load
     */
    public static function load(string $name): self {
        $filename = (new Path(
            App::getRootDir(),
            App::getConfig('views.dir'),
            $name.App::getConfig('views.fileSuffix')))->__toString();

        if(!file_exists($filename)) {
            throw new NotFoundException("View '".$name."' not found");
        }

        return new self(file_get_contents($filename), $filename);
    }

    /**
     * Includes a reusable component and returns it rendered with the given parameters.
     * To be used from a template
     *
     * @param string $name The name of the component to include
     * @param array $params The params to render the component with
     */
    private function include(string $name, $params = []): string {
        $filename = new Path(
            App::getRootDir(),
            App::getConfig('views.dir'),
            $name.App::getConfig('views.fileSuffix'));

        if(!file_exists($filename)) {
            throw new NotFoundException("View '".$name."' not found");
        }

        return (new self(file_get_contents($filename)))->render($params);
    }

}
