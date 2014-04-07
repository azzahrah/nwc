<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "site".
 *
 * @property integer $id
 * @property integer $name
 */
class Site extends Model {

    public $name;
    public $path;
    public $enabled = false;

	public static function disable($name) {
		$path= Setting::get("nginx_path") . "/" . "sites-enabled" . "/" . $name;
		if (is_file($path)) {
			unlink($path);
		}
		exec("service nginx reload");
	}
	
	public static function enable($name) {
		$path = Setting::get("nginx_path") . "/" . "sites-available" . "/" . $name;
		$path_enabled = Setting::get("nginx_path") . "/" . "sites-enabled" . "/" . $name;
		
		if (is_file($path)) {
			symlink($path,$path_enabled);
		}
		exec("service nginx reload");
	}

	public static function delete($name) {
		$path = Setting::get("nginx_path") . "/" . "sites-available" . "/" . $name;
		$path_enabled = Setting::get("nginx_path") . "/" . "sites-enabled" . "/" . $name;

		if (is_file($path)) {
			## parse site data from nginx config
			$nginx = file_get_contents($path);
			$data = explode("\n", $nginx);
			$rawdata = @json_decode(substr($data[1],3));

			if (is_null($rawdata)) {
				## nginx config is not created by nwc, just delete it
				unlink($path_enabled);
				unlink($path);
			} else {
				## format data
				$json = substr($data[1],3);
				$data = [];
				foreach ($rawdata as $k=>$s) {
					$data['{{{' . $k . '}}}'] = $s;
				}
				$data['{{{json_data}}}'] = $json;
				$data['{{{settings}}}'] = json_encode(\app\models\Setting::getAll());
				
				## get current generator
				$model = Generator::get($rawdata->id);

				## delete script path
				$ds = $model->path . "/delete.php";
				$dsg = $model->path . "/delete_generated.php";

				## generate delete script
				$dscontent = file_get_contents($ds);
				$content = strtr($dscontent,$data);
				file_put_contents($dsg, $content);

				## execute delete script
	            ob_start();
	            include($dsg);
	            $result = ob_get_clean();
	            unlink($dsg);


	        }
		}		
	}
	
	public static function getConfig($name) {
		$file = Setting::get("nginx_path") . "/" . "sites-available" ."/" .$name;
		if (is_file($file)) {
			return file_get_contents($file);
		} else {
			return "";
		}
	}
	
	public static function saveConfig($name, $content) {
		$file = Setting::get("nginx_path") . "/" . "sites-available" ."/" .$name;
		if (is_file($file)) {
			return file_put_contents($file, $content);
		} else {
			return "";
		}
	}
	
    public function search($params) {
        $sites_available = glob(Setting::get("nginx_path") . "/" . "sites-available" . "/*");
        $sites_enabled = glob(Setting::get("nginx_path") . "/" . "sites-enabled" . "/*");
		
		foreach ($sites_enabled as $k=>$s) { 
			$sites_enabled[$k] = str_replace(Setting::get("nginx_path") . "/" . "sites-enabled" . "/","",$s); 
		}	

        $sites = [];
        foreach ($sites_available as $s) {
            $site = new Site();
            $site->path = $s;
            $site->name = explode("/", $site->path);
            $site->name = $site->name[count($site->name) - 1];
            $site->enabled = in_array($site->name, $sites_enabled);
            $sites[] = $site;
        }
	
        return new \yii\data\ArrayDataProvider(['allModels'=>$sites]);
    }

}
