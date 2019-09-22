<?php

namespace View;

use \App;
use Http\Response;

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
     * @param App $app The app
     * @param array $params Parameters for the template
     */
    public function render(App $app, $params = []): string {
        ob_start();
        eval('?>'.$this->template);
        return ob_get_clean();
    }

    /**
     * Renders this view and prepares an HTML response
     * 
     * @param App $app The App
     * @param array $params Parameters for the template
     * @param int $status The HTTP status for the response
     */
    public function toResponse(App $app, $params = [], int $status = 200): Response {
        $response = new Response($status);
        $response->setHeader('Content-Type', 'text/html; charset=utf-8');
        $response->setBody($this->render($app, $params));
        return $response;
    }

    /**
     * Loads a view from an appropriate file based on the given name
     * 
     * @param App $app The app
     * @param string $name The name of the view to load
     */
    public static function load(App $app, string $name): self {
        return new self(file_get_contents($app->getViewFilename($name)));
    }

}
