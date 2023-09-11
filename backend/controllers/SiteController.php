<?php

namespace backend\controllers;

use backend\models\AppleAR;
use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

require_once ('../models/appleState.php');

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Главная страница.
     * А так же интерактивное взаимодействие - изменение свойств яблока
     *
     * @return string
     */
    public function actionIndex()
    {
        $req = Yii::$app->request;

        $action = $req->get('action');
        if ($action) {
            $id = $req->get('id');
            $a = AppleAR::findOne($id);
            if ($a) {
                switch ($action) {
                    case 'fall':
                                $a->fall_date = time();
                                $a->state = \appleState::onGround->value;
                                break;
                    case 'eat':
                                if ($a->state === \appleState::onTree->value)  throw new \Exception('Откусить не получится, яблоко на дереве!');
                                $percent = (int)$req->get('percent');
                                if (is_int($percent)) {
                                    if ($a->size * 100 <= $percent) {
                                        $a->size = 0;
                                        $a->state = \appleState::deleted->value;
                                    }
                                    else $a->size -= $percent / 100;
                                }
                                break;
                    case 'delete':
                                if ($a->state === \appleState::onTree->value)  throw new \Exception('Съесть нельзя, яблоко на дереве!');
                                $a->size = 0;
                                $a->state = \appleState::deleted->value;
                                break;
                }
                $a->save();
            }
            elseif ($action === 'generate') {
                for ($i = mt_rand(1, 5); $i > 0; $i--) {
                    $a = new AppleAR();
                    $a->color = sprintf('#%02X%02X%02X', mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
                    $a->birth_date = mt_rand(time() - 60 * 60 * 24 * 7, time());
                    $a->save();
                }
            }
        }

        $apples = AppleAR::findExistsApples();
        return $this->render('apple', ['apples' => $apples]);
    }

    /**
     * Авторизация.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
