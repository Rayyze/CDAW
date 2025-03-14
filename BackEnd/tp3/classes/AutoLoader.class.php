<?php 

class AutoLoader {

    public function __construct() {
        spl_autoload_register( array($this, 'load') );
        // spl_autoload_register(array($this, 'loadComplete'));
    }

    // This method will be automatically executed by PHP whenever it encounters an unknown class name in the source code
    private function load($className) {
        $directories = ["classes", "model", "controller" ];
        foreach($directories as $dir) {
            $classFile = __ROOT_DIR . '/' . $dir . '/' . $className . '.class.php';
            if (file_exists($classFile)) {
                include $classFile;
                if ($dir === "model" && $className != "Model") {
                    include __ROOT_DIR . '/sql/' . $className . '.sql.php';
                }
                return;
            }
        }
        // TODO : compute path of the file to load (cf. PHP function is_readable)
        // it is in one of these subdirectory '/classes/', '/model/', '/controller/'
        // if it is a model, load its sql queries file too in sql/ directory

    }
}

$__LOADER = new AutoLoader();