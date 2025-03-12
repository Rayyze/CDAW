<?php
class Response {
    private $statusCode;
    private $message;

    public function __construct($statusCode, $message) {
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    public function send() {
        header("HTTP/1.1 " . $this->statusCode);
        echo json_encode(["message" => $this->message]);
        exit;
    }

    public function sendWithLog() {
        error_log($this->message);
        $this->send();
    }

    public static function errorResponse($message) {
        header("HTTP/1.1 500 Internal Server Error");
        echo $message;
        exit;
    }

    public static function okResponse($message) {
        header("HTTP/1.1 200 OK");
        echo $message;
        exit;
    }
}
