<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Noticeuser;
use app\modules\admin\models\NoticeuserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\modules\admin\models\IctWebService;
/**
 * NoticeuserController implements the CRUD actions for Noticeuser model.
 */
class NoticeuserController extends Controller
{
    public $enableCsrfValidation = false;
    public $layout  = 'main';
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['get'],
                ],
            ],
        ];
    }

    /**
     * Lists all Noticeuser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NoticeuserSearch();
        $queryParams = Yii::$app->request->queryParams;
        $queryParams['NoticeuserSearch']['eid'] = Yii::$app->session['user.eid'];
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Noticeuser model.
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
     * Creates a new Noticeuser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Noticeuser();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    public function actionAdd()
    {
        $uid = explode(",",Yii::$app->request->post('uid'));
        $name = explode(",",Yii::$app->request->post('name'));
        $mobile = explode(",",Yii::$app->request->post('mobile'));
        $eid = Yii::$app->session['user.eid'];
        for($i=0;$i<count($uid);$i++){
            if(!$mobile[$i]){
                continue;
            } 
            $user = new Noticeuser();
            $user->eid = $eid;
            $user->uid = $uid[$i];
            $user->name = $name[$i];
            $user->mobile = $mobile[$i];
            $user->time = time();
            $user->level = 1;
            $user->save();

        }
        return $this->actionIndex();
        exit;
        $ictWS = new IctWebService();
        $contacts= $ictWS->getICTContacts();
        $tree = $ictWS->createTreeData($contacts['result']['0']['data']);
        $tree['isExpand'] = true;
        $userTree['status'] = 1;
        $userTree['tree'] = ["data"=>[$tree],'ajaxType'=>"get"];

        return $this->render('add', [
            'userTree' => json_encode($userTree),
        ]);
    }


    /**
     * Updates an existing Noticeuser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Noticeuser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index',"subflag"=>Yii::$app->request->get('subflag')]);
    }

    /**
     * Finds the Noticeuser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Noticeuser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Noticeuser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
