<?php

namespace View;

use \Throwable;
use \App;
use Http\Response;
use Files\Path;
use LogicException;
use Utils\NotFoundException;

/**
 * A renderable view template
 */
class View {

    protected const REGEX_EXTENDS = '/<!--\s*extends\s+(.+?)\s*-->/';
    protected const REGEX_SLOT    = '/<!--\s*slot\s+(.+?)\s*-->/';
    protected const REGEX_BEGIN   = '/<!--\s*begin\s+(.+?)\s*-->/';
    protected const REGEX_BLOCK   = '/<!--\s*begin\s+(.+?)\s*-->([.\s\S]*?)<!--\s*end\s*-->/';

    /**
     * The template
     * @var string
     */
    private $template;

    /**
     * Name of the template which this template extends
     * @var null|View
     */
    private $extends = null;

    /**
     * Slot positions and
     */
    private $slots = [];

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

        // <!-- extends template -->
        preg_match(self::REGEX_EXTENDS, $this->template, $matches);

        if($matches !== []) {
            $this->extends = View::load($matches[1]);
            if($this->extends->extends !== null)
                throw new LogicException("Multi-level template inheritance not allowed");
        }

        // <!-- begin slot --> ... <!-- end -->
        preg_match_all(self::REGEX_BLOCK, $this->template, $matches, PREG_SET_ORDER);

        foreach($matches as $match)
            $this->slots[$match[1]] = $match[2];
    }

    /**
     * Renders this view and returns the resulting HTML document
     *
     * @param array $params Parameters for the template
     */
    public function render($params = []): string {
        // TODO: Should the parent template be rendered?

        $template = $this->template;
        $template = preg_replace(self::REGEX_EXTENDS, '', $template);
        $template = preg_replace(self::REGEX_BLOCK, '', $template);

        if($this->extends !== null) {
            $template = $template .
                preg_replace_callback(
                    self::REGEX_SLOT,
                    fn($m) => $this->slots[$m[1]] ?? '',
                    $this->extends->template);
        }

        ob_start();
        try {
            eval('?>'.$template);
            return ob_get_clean();
        } catch(Throwable $e) {
            ob_end_clean();

            // TODO: Correct line number if template is extending another
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
     * Returns the name of the template file or `null` if the template is not
     * loaded from a file.
     */
    public function getFilename(): ?string {
        return $this->file;
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
