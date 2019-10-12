<?php

namespace View;

use \App;
use Http\Response;
use Files\Path;

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
     * Constructs a new `View` object
     * 
     * @param string $template The template to render
     */
    public function __construct(string $template) {
        $this->template = $template;
    }

    /**
     * Renders this view and returns the resulting HTML document
     * 
     * @param array $params Parameters for the template
     */
    public function render($params = []): string {
        ob_start();
        eval('?>'.$this->template);
        return ob_get_clean();
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
        $app = App::get();
        $filename = new Path(
            $app->getRootDir(),
            $app->getConfig('views.dir'),
            $name.$app->getConfig('views.fileSuffix'));

        return new self(file_get_contents($filename));
    }

    /* BEGIN TEMPLATE FUNCTIONS */

    /**
     * Includes a reusable component and returns it rendered with the given parameters
     * 
     * @param string $name The name of the component to include
     * @param array $params The params to render the component with
     */
    private function include(string $name, $params = []): string {
        $app = App::get();
        $filename = new Path(
            $app->getRootDir(),
            $app->getConfig('views.includeDir'),
            $name.$app->getConfig('views.fileSuffix'));

        return (new self(file_get_contents($filename)))->render($params);
    }

}
