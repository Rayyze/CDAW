<?php
define('__ROOT_DIR', dirname(__DIR__, 3));
include_once __ROOT_DIR . '/libs/php-jwt/src/JWTExceptionWithPayloadInterface.php';
include_once __ROOT_DIR . '/libs/php-jwt/src/BeforeValidException.php';
include_once __ROOT_DIR . '/libs/php-jwt/src/ExpiredException.php';
include_once __ROOT_DIR . '/libs/php-jwt/src/SignatureInvalidException.php';
include_once __ROOT_DIR . '/libs/php-jwt/src/JWT.php';
use \Firebase\JWT\JWT;

class LoginController extends Controller {

   public function __construct($requestMethod, $params) {
      parent::__construct($requestMethod, $params);
   }

	public function processRequest() {
      $input = json_decode(file_get_contents("php://input"), true);
      if($this->requestMethod !== 'POST')
         return Response::errorResponse('{ "message" : "Unsupported endpoint" }' );

      if(!isset($input['pwd']) || !isset($input['login'])) {
         $r = new Response(422,"login and pwd fields are mandatory");
			$r->send();
      }

      $user = UserModel::tryLogin($input['login']);
		if(empty($user) || !password_verify($input['pwd'], $user->pwd)) {
			$r = new Response(422,"wrong credentials, comparing $password_hash and " . $user->password());
			$r->sendWithLog();
      }

      // generate json web token
      $issued_at = time();
      $expiration_time = $issued_at + (60 * 60); // valid for 1 hour

      $token = array(
         "iat" => $issued_at,
         "exp" => $expiration_time,
         "iss" => JWT_ISSUER,
         "data" => array(
            "id" => $user->id,
            "firstname" => $user->name,
            "email" => $user->email
         )
      );

      $jwt = JWT::encode($token, JWT_BACKEND_KEY, 'HS256');
      $jsonResult = json_encode(
            array(
               "jwt_token" => $jwt
            )
      );

		$response = Response::okResponse($jsonResult);
	}
}
