<?php

namespace Http;

use Files\Path;
use Files\Mime;

/**
 * An HTTP response to be sent back to the client
 */
class Response {

    /**
     * The status
     * @var int
     */
    private $status = 200;

    /**
     * Headers
     * @var array
     */
    private $headers = [];

    /**
     * Response body
     * @var string
     */
    private $body = '';

    /**
     * Constructs a response with the given status
     * 
     * @param int $status The status to set
     */
    public function __construct(int $status) {
        $this->status = $status;
    }

    /**
     * Sets the status code
     * 
     * @param int $status The new status code
     */
    public function setStatus(int $status): void {
        $this->status = $status;
    }

    /**
     * Sets the specified header to the given value
     * 
     * @param string $header The header to set
     * @param string $value The value to set
     */
    public function setHeader(string $header, string $value): void {
        $this->headers[$header] = $value;
    }

    /**
     * Sets the body of this response to the given value
     * 
     * @param string $body The body for this response
     * @param bool $setLength Whether to set the `Content-Length` header automatically
     */
    public function setBody(string $body, bool $setLength = true): void {
        $this->body = $body;
        if($setLength) $this->setHeader('Content-Length', strlen($body));
    }

    /**
     * Sends the response to the client. This should be the terminal operation of the entire app.
     */
    public function send(): void {
        header($_SERVER['SERVER_PROTOCOL'].' '.Status::toString($this->status));
        foreach($this->headers as $header => $value) {
            header($header.': '.$value);
        }
        echo($this->body);
    }

    /**
     * Constructs a plain text resposne containing the given text
     * 
     * @param string $text The text to send
     * @param string $charset The charset to send in the Content-Type header
     */
    public static function text(string $text, string $charset = 'utf-8'): Response {
        $response = new self(200);
        $response->setHeader('Content-Type', 'text/plain; charset='.$charset);
        $response->setBody($text);
        return $response;
    }

    /**
     * Constructs a file response containing the specified file
     * 
     * @param Path $path The path to the file to send
     * @param string|null $mime A custom MIME type to use.
     *  If not provided, the type will be detected automatically
     */
    public static function file(Path $path, ?string $mime = null): self {
        $file = $path.'';
        $mime = $mime ?? Mime::get($file);

        $response = new self(200);
        $response->setHeader('Content-Type', $mime);
        $response->setHeader('Content-Transfer-Encoding', 'Binary');
        $response->setBody(file_get_contents($file), false);
        return $response;
    }

}
