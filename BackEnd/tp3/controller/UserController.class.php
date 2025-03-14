<?php

class UserController extends Controller {

	public function __construct($name, $request) {
		parent::__construct($name, $request);
	}

	public function processRequest() {
        $input = $this->request->getRequestBody();
         switch ($this->request->getHttpMethod()) {
            case 'GET':
                if(isset($this->request->getUriParameters()[0])) {
                    return $this->getUser($this->request->getUriParameters()[0]);
                } else {
                    return $this->getAllUsers();
                }
                break;
            case 'PUT':
                if(isset($this->request->getUriParameters()[0]) && isset($input['name']) && isset($input['email']) && isset($input['pwd'])) {
                    return $this->updateUser($this->request->getUriParameters()[0], $input);
                }
                break;
        }
        echo($this->request->getUriParameters()[0]);
        return Response::errorResponse("unsupported parameters or method in users");
    }

    protected function getAllUsers() {
        $users = User::getList();
        $responseBody = array();
        foreach( $users as $user) {
            array_push($responseBody, $user->getProps());
        }
        $response = Response::okResponse(json_encode($responseBody));
        return $response;
    }

    protected function getUser($id) {
        $user = User::getUser($id);
        if (isset($user)) {
            $response = Response::okResponse(json_encode($user->getProps()));
        } else {
            $response = Response::errorResponse("user not found");
        }
        return $response;
    }

    protected function updateUser($id, $data) {
        $user = User::getUser($id);
        if (isset($user)) {
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->pwd = $data['pwd'];
            $user->updateUser();
        } else {
            $response = Response::errorResponse("user not found");
        }
        return $response;
    }
}