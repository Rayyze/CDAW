<?php
class UsersController {

    private $requestMethod;
    private $params;

    public function __construct($requestMethod, $params) {
        $this->requestMethod = $requestMethod;
        $this->params = $params;
    }

    public function processRequest() {
        $input = json_decode(file_get_contents("php://input"), true);
        switch ($this->requestMethod) {
            case 'GET':
                if (!isset($this->params[0])) {
                    $response = $this->getAllUsers();
                } else {
                    $response = $this->getUserById($this->params[0]);
                }
                break;
            case 'POST':
                if (isset($input['name']) && isset($input['email']) && isset($input['pwd'])) {
                    $response = $this->createUser($input['name'], $input['email'], $input['pwd']);
                } else {
                    $response = $this->badRequestResponse();
                }
                break;
            case 'PUT':
                if (!isset($this->params[0]) || !isset($input['name']) || !isset($input['email']) || !isset($input['pwd'])) {
                    $response = $this->badRequestResponse();
                } else {
                    $response = $this->updateUser($this->params[0], $input['name'], $input['email'], $input['pwd']);
                }
                break;
            case 'DELETE':
                if (!isset($this->params[0])) {
                    $response = $this->badRequestResponse();
                } else {
                    $response = $this->deleteUser($this->params[0]);
                }
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllUsers() {
        $users = UserModel::getAllUsers();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $data = [];
        foreach ($users as $user) {
            $data[$user->id] = $user->props;
        }
        $response['body'] = json_encode(['data' => $data]);
        return $response;

    }

    private function getUserById($id) {
        $user = UserModel::getUserById($id);
        if (!$user) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($user->props);
        return $response;
    }

    private function createUser($name, $email, $pwd) {
        $user = new UserModel();
        $user->name = $name;
        $user->email = $email;
        $user->pwd = $pwd;
        $user->createUser($user);
        if (!$user) {
            return $this->internalErrorResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($user->props);
        return $response;
    }

    private function updateUser($id, $name, $email, $pwd) {
        $user = UserModel::getUserById($id);
        if (!$user) {
            return $this->notFoundResponse();
        }
        $user->id = $id;
        $user->name = $name;
        $user->email = $email;
        $user->pwd = $pwd;
        if (!$user->updateUser()) {
            return $this->internalErrorResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($user->props);
        return $response;
    }

    private function deleteUser($id) {
        $user = UserModel::getUserById($id);
        if (!$user) {
            return $this->notFoundResponse();
        }
        if (!$user->deleteUser()) {
            return $this->internalErrorResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($user->props);
        return $response;
    }

    private function notFoundResponse() {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }

    private function internalErrorResponse() {
        $response['status_code_header'] = 'HTTP/1.1 500 Internal Server Error';
        $response['body'] = null;
        return $response;
    }

    private function badRequestResponse() {
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = null;
        return $response;
    }
}