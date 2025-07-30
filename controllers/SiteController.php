<?php
/*
 *   Jamshidbek Akhlidinov
 *   30 - 7 2025 17:13:49
 *   https://ustadev.uz
 *   https://github.com/JamshidbekAkhlidinov
 */

namespace app\controllers;

use app\forms\ShortLinkForm;
use app\models\Data;
use app\models\History;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

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
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $form = new ShortLinkForm(
            new Data()
        );
        return $this->render('index', [
            'form' => $form,
        ]);
    }

    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $form = new ShortLinkForm(new Data());

        if (!Yii::$app->request->isPost) {
            Yii::$app->response->statusCode = 405;
            return [
                'success' => false,
                'message' => 'Method not allowed'
            ];
        }
        $form->load(Yii::$app->request->post());

        if (!$form->validate()) {
            return ['success' => false, 'message' => $form->errors];
        }
        return $form->save();
    }

    public function actionRedirect($code)
    {
        $model = Data::findOne(['code' => $code]);
        if (!$model) {
            throw new NotFoundHttpException('Page not found');
        }
        $model->count++;
        $model->save();

        $historyModel = new History([
            'data_id' => $model->id,
            'ip_address' => Yii::$app->request->userIP,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $historyModel->save();

        return $this->redirect($model->url);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

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

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
