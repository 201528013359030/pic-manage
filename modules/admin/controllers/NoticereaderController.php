<?php

namespace app\modules\admin\controllers;
use yii\web\Controller;
use app\models\Noticeinfo;
use app\models\Noticereader;
use app\modules\admin\models\IctWebService;
class NoticereaderController extends Controller
{
	public $layout  = false;
    public function actionIndex()
    {
    	$announce_id=\Yii::$app->request->get('id');
    	//查询已确认人员、已读人员、
    	$reader_model =new Noticereader();
    	$read_count = Noticereader::find()
    	->where(['relation' => 'read','announce_id' => $announce_id])
    	->count();
    	$confirm_count = Noticereader::find()
    	->where(['confirm' => 'yes','announce_id' => $announce_id])
    	->count();
    	$all_count = Noticereader::find()
    	->where(['announce_id' => $announce_id])
    	->count();
    	//查询未查看人员
    	$unreaders = Noticereader::find()
    	->where(['relation' => 'unread','confirm'=>'no','announce_id' => $announce_id])
    	->orderBy('announce_id')
    	->all();
    	//查询已查看人员
    	$readers = Noticereader::find()
    	->where(['relation' => 'read','confirm' => 'no','announce_id' => $announce_id])
    	->orderBy('announce_id')
    	->all();
    	//查询已确认人员
    	$confirmusers = Noticereader::find()
    	->where(['confirm' => 'yes','announce_id' => $announce_id])
    	->orderBy('announce_id')
    	->all();
 	/* 	var_dump($confirmusers);
    	 exit();   */
        $ws = new IctWebService();
        $wip = $ws->getNodeInfo("root",['wip']);
        $photoip = "http://".$wip['result'][0]['data']['wip'][0];
    	
        return $this->render('index',array('read_count'=>$read_count,'confirm_count'=>$confirm_count,'all_count'=>$all_count,
        		'announce_id'=>$announce_id,'unreaders'=>$unreaders,'readers'=>$readers,'confirmusers'=>$confirmusers,'photoip'=>$photoip));
    }

}
