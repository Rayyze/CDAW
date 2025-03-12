<?php
class Controller {
    protected $requestMethod;
    protected $params;

    public function __construct($requestMethod, $params) {
        $this->requestMethod = $requestMethod;
        $this->params = $params;
    }
}
