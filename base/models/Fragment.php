<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class Fragment extends Model {

    public $template;
    public $name;
    public $data;
    public $number;
    public $interval = false;
    public $path;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // username and password are both required
            [['template', 'name', 'data', 'number'], 'required'],
        ];
    }

    private static $_path = "";

    public static function getPath() {
        if (Fragment::$_path == "") {
            $path = explode(DIRECTORY_SEPARATOR, Yii::$app->getBasePath());
            $path[count($path) - 1] = 'fragments';
            Fragment::$_path = implode(DIRECTORY_SEPARATOR, $path);
        }
        return Fragment::$_path . DIRECTORY_SEPARATOR;
    }

    public static function render($fragment) {

        if (is_int($fragment)) {
            $fragment = Fragment::get($fragment);
        }

        $data = get_object_vars($fragment->data);
        foreach ($data as $k => $d) {
            $ext = explode(".", $d);
            $ext = array_pop($ext);
            $result = "";
            switch ($ext) {
                case "sh":
                    chmod($fragment->path . DIRECTORY_SEPARATOR . $d, 755);
                    $result = shell_exec($fragment->path . DIRECTORY_SEPARATOR . $d);
                    break;
                case "php":
                    ob_start();
                    include($fragment->path . DIRECTORY_SEPARATOR . $d);
                    $result = ob_get_clean();
                    break;
            }
            $data[$k] = $result;
        }
		
        $data['number'] = $fragment->number;
        $data['name'] = $fragment->name;
        $data['interval'] = $fragment->interval;
        $data['path'] = $fragment->path;
        
		extract($data);
		ob_start();
		include($fragment->path . DIRECTORY_SEPARATOR . 'template.html');
		$html = ob_get_clean();
		return $html;
    }

    public static function renderJS($fragment) {
        if ($fragment->interval !== false) {
            echo 'setInterval(function() {
                reloadFragment(' . $fragment->number . ');
            },' . ($fragment->interval * 1000) . ');';
        }
    }

    public static function get($id) {
        $fragments = Fragment::getFragments();
        foreach ($fragments as $f) {
            if ($f->number == $id) {
                return $f;
            }
        }

        throw new \yii\base\Exception("Fragment Not Found");
    }

    public static function getFragments() {
        $fragments_path = glob(Fragment::getPath() . "*");
        $fragments = [];
        foreach ($fragments_path as $f) {
            $number = explode(DIRECTORY_SEPARATOR, $f);
            $number = explode(".", array_pop($number));
            $number = array_shift($number);
            $json = json_decode(file_get_contents($f . DIRECTORY_SEPARATOR . "info.json"));
            $template = file_get_contents($f . DIRECTORY_SEPARATOR . "template.html");

            $frag = new Fragment();
            $frag->name = $json->name;
            $frag->template = $template;
            $frag->data = $json->data;
            if (isset($json->interval)) {
                $frag->interval = $json->interval;
            }
            $frag->number = $number;
            $frag->path = $f;
            $fragments[] = $frag;
        }
        return $fragments;
    }

}
