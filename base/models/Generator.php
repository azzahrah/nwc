<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class Generator extends Model {

	public $id;
    public $name;
    public $description;
	public $requirement;
	public $steps;
	public $path;
	public $fields;
    public $js = [];
    public $css = [];

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
        ];
    }

    private static $_path = "";

    public static function getPath() {
        if (Generator::$_path == "") {
            $path = explode(DIRECTORY_SEPARATOR, Yii::$app->getBasePath());
            $path[count($path) - 1] = 'generators';
            Generator::$_path = implode(DIRECTORY_SEPARATOR, $path);
        }
        return Generator::$_path . DIRECTORY_SEPARATOR;
    }

    public static function get($id) {
        $generators = Generator::getAll();
        foreach ($generators as $f) {
            if ($f->id == $id) {
                return $f;
            }
        }

        throw new \yii\base\Exception("Generator Not Found");
    }

    public static function getAll() {
        $generators_path = glob(Generator::getPath() . "*");
        $generators = [];
        foreach ($generators_path as $f) {
			$file= $f . DIRECTORY_SEPARATOR . "info.json";
			if (!is_file($file)) continue;
			
			$id = explode(DIRECTORY_SEPARATOR, $f);
			$id = array_pop($id);
            $json = json_decode(file_get_contents($file));
             
            $gen = new Generator();
			$gen->id = $id;
            $gen->name = $json->name;
            $gen->description = $json->description;
			$gen->steps = $json->steps;
			$gen->fields = $json->fields;
            $gen->path = $f;
			$gen->requirement = $json->requirement;
            
            if (isset($json->css)) {
                $gen->css = $json->css;
            }

            if (isset($json->js)) {
                $gen->js = $json->js;
            }
            $generators[] = $gen;
        }
        return $generators;
    }

}
