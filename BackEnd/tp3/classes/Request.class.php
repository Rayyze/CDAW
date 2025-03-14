<?php
class Request {

	protected $controllerName;
	protected $uriParameters;
   protected $baseURI;

    public static function getCurrentRequest(){
         $request = new Request();
         $request->initBaseURI();
         $request->initControllerAndParametersFromURI();
         return $request;
    }

   public function __construct() {
      $this->initBaseURI();
      $this->initControllerAndParametersFromURI();
   }

   // intialise baseURI
   // e.g. http://eden.imt-nord-europe.fr/~luc.fabresse/api.php => __BASE_URI = /~luc.fabresse
   // e.g. http://localhost/CDAW/api.php => __BASE_URI = /CDAW
   protected function initBaseURI() {
      $this->baseURI = "/BackEnd/tp3";
   }

   // intialise controllerName et uriParameters
   // controllerName contient chaîne 'default' ou le nom du controleur s'il est présent dans l'URI (la requête)
   // uriParameters contient un tableau vide ou un tableau contenant les paramètres passés dans l'URI (la requête)
   // e.g. http://eden.imt-nord-europe.fr/~luc.fabresse/api.php
   //    => controllerName == 'default'
   //       uriParameters == []
   // e.g. http://eden.imt-nord-europe.fr/~luc.fabresse/api.php/user/1
   //    => controllerName == 'user'
   //       uriParameters == [ 1 ]
   //
   // Aide :
   // En utlisant la fonction PHP phpinfo et en faisant des tests
   // http://localhost/info.php/test/test
   // on peut voir que
   // $_SERVER['SCRIPT_NAME'] donne le préfixe
   // et que parse_url($_SERVER['REQUEST_URI']
   protected function initControllerAndParametersFromURI(){
      if (isset($_GET['controller'])) {
         $this->controllerName = $_GET['controller'];
      } else {
         $this->controllerName = 'Default';
      }
      if (isset($_GET['parameters'])) {
         $this->uriParameters = explode(',', $_GET['parameters']);
      } else {
         $this->uriParameters = [];
      }
  }

   // ==============
   // Public API
   // ==============

	// retourne le name du controleur qui doit traiter la requête courante
   public function getControllerName() {
      return $this->controllerName;
   }

   public function getUriParameters() {
      return $this->uriParameters;
   }

   public function getRequestBody() {
      return json_decode(file_get_contents("php://input"), true);
   }

	// retourne la méthode HTTP utilisée dans la requête courante
   public function getHttpMethod() {
      return $_SERVER["REQUEST_METHOD"];
   }

}
