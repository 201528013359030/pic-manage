<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\IctWebService;
use app\models\PaiUser;

class TestController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout = false;
    public function actionIndex(){
        return $this->render('index');

    }
    public function actionContacts(){

        $ictWS = new IctWebService();
        $contacts= $ictWS->getICTContacts();
        $tree = $ictWS->createTreeData($contacts['result']['0']['data']);
        $ictWS->storeList();
        $tree['isExpand'] = true;
        $return['status'] = 1;
        $return['tree'] = ["data"=>[$tree],'ajaxType'=>"get"];
        echo json_encode($return);
        exit;

    }
    public function actionBind(){
        $id = \Yii::$app->request->post('id');
        $id = substr($id, 0, strlen($id) - 1);
        $data = explode(",", $id);
        $data = array_flip(array_flip($data));
        $new_array = array_diff($data, json_decode(yii::$app->session['list'], true));
        $def_array = array_diff(json_decode(yii::$app->session['list'], true), $data);
        $model = new PaiUser();
        $ictWS = new IctWebService();
        //增加
        if($new_array){
            foreach($new_array as $d){
                $result = $model->findOne(["user_id"=>"$d"]);
                if($result){
                    $result->admin = "1";
                    $result->save();
                }else{
                    $model_ = new PaiUser();
                    $info = $ictWS->getICTContact($d);
                    $model_->user_name = $info["result"][0]['data']['membername'][0];
                    if($info["result"][0]['data']['sex'][0] == 1){
                        $sex = "男";
                    }else if($info["result"][0]['data']['sex'][0] == 0){
                        $sex = "女";
                    }else{
                        $sex = "未知";
                    }
                    $model_->user_sex = $sex;
                    $model_->user_id = $d;
                    $model_->admin = "1";
                    //file_put_contents("/tmp/1","eid:" . yii::$app->session['eid'] . "uid:" . $d. "name:" . $model_->name . "mobile:" . $model_->mobile. 'department' . $model_->department . "time:". $model_->time . "\n" , FILE_APPEND);
                    $model_->save();
                }

            }
        }
        //删除
        if($def_array){
            foreach($def_array as $d){
                $result = $model->findOne(["user_id"=>"$d"]);
                $result->admin = "0";
                $result->save();
            }
        }
        yii::$app->session['list'] = json_encode($data);
        echo json_encode(1);
        exit;
    }



}
