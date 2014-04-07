<?php

namespace app\controllers;

class NginxController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
    		'access' => [
				'class' => 'yii\filters\AccessControl',
				'rules' => [
					['allow' => true, 'roles' => ['@']],
					['allow' => false, 'roles' => ['?']],
				],
			],
        ];
    }
	
	public function actionStart() {
		exec("service nginx start");
		$this->redirect(["/dashboard/index"]);
	}
	
	public function actionStop() {
		exec("service nginx stop");
		$this->redirect(["/dashboard/index"]);
	}
	
	public function actionReload() {
		exec("service nginx reload");
		$this->redirect(["/dashboard/index"]);
	}
	
	public function actionForcereload() {
		exec("service nginx forcereload");
		$this->redirect(["/dashboard/index"]);
	}
}
