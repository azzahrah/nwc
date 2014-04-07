<?php

namespace app\controllers;
use app\models\Fragment;

class DashboardController extends \yii\web\Controller
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
	
    public function actionIndex()
    {
        $fragments = Fragment::getFragments();
        
        return $this->render('index', [
            'fragments' => $fragments
        ]);
    }
    
    public function actionFragment($id) {
        echo Fragment::render(intval($id));
    }

}
