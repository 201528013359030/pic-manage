<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Noticeinfo;
use app\modules\admin\models\Noticelist;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NoticelistController implements the CRUD actions for Noticeinfo model.
 */
class NoticelistController extends Controller
{

    public $enableCsrfValidation = false;
    public $layout  = 'main';
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
    //                'delete' => ['post'],
                ],
            ],
        ];
    }
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
     * Lists all Noticeinfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        //throw new \yii\web\HttpException(200,'Invalid method',20100);
        $searchModel = new Noticelist();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Noticeinfo();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Noticeinfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Noticeinfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Noticeinfo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->announce_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Noticeinfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->announce_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    public function actionModify()
    {

        $id = Yii::$app->request->get('id');
        $sort = Yii::$app->request->get('sort');
        $topTime = Yii::$app->request->get('topTime');
        $notice = Noticeinfo::findOne($id);
        if($topTime){
            $notice->top_time = $notice->time+($topTime * 24 * 60 * 60);
            $notice->top_day = $topTime;
        }else{
            $notice->top_time = null;
            $notice->top_day = null;
        }
        $notice->comment_switch = $sort;
        $notice->save();
        return $this->redirect(['index','subflag'=>'0102']);
    }

    /**
     * Deletes an existing Noticeinfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index','subflag'=>'0102']);
    }

    /**
     * Finds the Noticeinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Noticeinfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Noticeinfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
