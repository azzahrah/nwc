<?php

namespace app\controllers;

use Yii;
use app\models\Site;
use app\models\Generator;
use app\models\search\SiteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\VerbFilter;

/**
 * SitesController implements the CRUD actions for Site model.
 */
class SitesController extends Controller
{
    public function behaviors()
    {
        return [
			'access' => [
				'class' => 'yii\web\AccessControl',
				'rules' => [
					['allow' => true, 'roles' => ['@']],
					['allow' => false, 'roles' => ['?']],
				],
			],
        ];
    }

    /**
     * Lists all Site models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Site;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
	
	public function actionCreate($name) {
		$session = Yii::$app->session['last_generate'];

		if (isset($_POST['error'])) {
			$s_error = new Yii::$app->session;
			$s_error->open();
			$s_error['last_generate_error'] = json_decode($_POST['error']);
			$s_error->close();
		}
		$error = Yii::$app->session['last_generate_error'];

		if (!is_null($error)) {
			$s_error = new Yii::$app->session;
			$s_error->open();
			$s_error['last_generate_error'] = null;
			$s_error->close();
		}
		
		return $this->render('create', [
			'model' => Generator::get($name),
			'session' => $session,
			'error' => $error,
		]);
	}
	
	public function actionGenerate() {
		$session = new Yii::$app->session;
		$model = Generator::get($_POST['id']);
		$session->open();

		foreach ($_POST as $k => $p) {
			if (isset($model->fields->$k->post_process)) {
				$field = $_POST[$k];
				eval('$_POST[$k] = ' . $model->fields->$k->post_process . ';');
			}
		}

		$session['last_generate'] = $_POST;
		$session->close();
		
		$session = Yii::$app->session['last_generate'];

		return $this->render('generate', [
			'model' => Generator::get($session['id']),
			'session' => $session
		]);
	}
	
	public function actionGenerate_step($name,$number) {
		$model = Generator::get($name);
		$step = $model->steps[$number];
		$script = Generator::getPath()  . $name . "/" . $step->script;
		$session = Yii::$app->session['last_generate'];
		$error = Yii::$app->session['last_generate_error'];
		
		## define generated file
		$generated = explode(".",$step->script);
		$ext = array_pop($generated);
		$generated = Generator::getPath() . $name . "/" . implode(".", $generated) . "_generated." . $ext;

		## prepare to replace session data
		$session_replaced = [];
		foreach ($session as $k=>$s) {
			$session_replaced['{{{' . $k . '}}}'] = $s;
		}
		unset($session['_csrf']);
		$session_replaced['{{{json_data}}}'] = json_encode($session);
		$session_replaced['{{{settings}}}'] = json_encode(\app\models\Setting::getAll());

		if (is_file($script)) {
			##remove previous error
			$s_error = new Yii::$app->session;
			$s_error->open();
			$s_error['last_generate_error'] = null;
			$s_error->close();

			$content = file_get_contents($script);
			
			$content = strtr($content,$session_replaced);
			file_put_contents($generated, $content);

            switch ($ext) {
                case "sh":
                    chmod($generated, 755);
                    $result = shell_exec($generated);
                    break;
                case "php":
                    ob_start();
                    include($generated);
                    $result = ob_get_clean();
                    break;
            }
			
            if ($result == "SUCCESS" && count($model->steps) == $number +1) {
				## reset last_generate data when create is successfull
				$s_lg = new Yii::$app->session;
				$s_lg->open();
				$s_lg['last_generate'] = null;
				$s_lg->close();
			};

			unlink($generated);
            echo $result;
		}

	}
	
	public function actionChoose() {
		$dataProvider = new \yii\data\ArrayDataProvider([
			'allModels' => Generator::getAll()
		]);
		
		return $this->render('choose', [
			'dataProvider' => $dataProvider
		]);
	}
	
	public function actionEditconfig($name) {
		$error = "";
		if (isset($_POST['content'])) {
			Site::saveConfig($name,$_POST['content']);
			exec("service nginx configtest 2>&1 ", $output);
			if (count($output) > 1) {
				$error = implode("\n<hr/>\n",$output);
			} else {
				exec("service nginx reload");
			}
		}
		
		return $this->render('editconfig', [
			'content' => Site::getConfig($name),
			'error'=> $error,
		]);
	}
	
	

	/**
     * Disable site.
     * @param string $name
     * @return mixed
     */
    public function actionDisable($name)
    {
		Site::disable($name);
		return $this->redirect(['index']);
    }

	/**
     * Enable site.
     * @param string $name
     * @return mixed
     */
    public function actionEnable($name)
    {
		Site::enable($name);
		return $this->redirect(['index']);
    }


    /**
     * Deletes an existing Site model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($name)
    {
    	Site::delete($name);
		return $this->redirect(['index']);
    }

    /**
     * Finds the Site model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Site the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Site::find($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
