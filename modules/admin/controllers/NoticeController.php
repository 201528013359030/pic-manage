<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use app\models\Noticeinfo;
use app\models\Noticeuser;
use yii\data\Pagination;
use app\modules\admin\models\AuthToken;


class NoticeController extends Controller
{
	public $layout  = false;
// 	public $username="";
    public function actionIndex()
    {
        $auth = new AuthToken();
        $auth->authTokenUrl();
    	$model = new Noticeinfo();
/*     	$view = \Yii::$app->view; 	
    	$view->params['layoutData']='lucy'; */
//     	$searchcontent=\Yii::$app->session['searchcontent'];
    	$uid =\Yii::$app->session['user.uid'];
        $admin = Noticeuser::findOne(['uid'=>$uid]);

    /* 	 echo "uid:".$uid;
    	 return ; */
    	/* if(strlen($searchcontent)>0){
    		$sql="select * from (select * from noticeinfo where title like '%".$searchcontent."%') t1 join (select * from noticereader where uid='".$uid."') t2 on t1.announce_id = t2.announce_id order by t1.announce_id desc limit 5";
    	}else { */
    		//$sql="select * from (select * from noticeinfo) t1 join (select * from noticereader where uid='".$uid."') t2 on t1.announce_id = t2.announce_id order by t1.comment_switch desc,t1.announce_id desc limit 10";
    		//$sql="(select * from (select * from noticeinfo) t1 join (select * from noticereader where uid='".$uid."') t2 on t1.announce_id = t2.announce_id and((t1.top_time > 10 and t1.comment_switch>0) or (t1.top_time is null and t1.comment_switch>0)) order by t1.comment_switch desc,t1.announce_id desc)"." UNION  (select * from (select * from noticeinfo) t3 join (select * from noticereader where uid='".$uid."' ) t4 on t3.announce_id = t4.announce_id and (t3.comment_switch<>1 or ( t3.top_time < 10  and t3.comment_switch>0)) order by t3.announce_id desc) limit 100";
//        $sql="(select * from (select * from noticeinfo) t1 join (select * from noticereader where uid='".$uid."') t2 where t1.announce_id = t2.announce_id and((t1.top_time > 10 and t1.comment_switch>0) or (t1.top_time is null and t1.comment_switch>0)) order by t1.comment_switch desc,t1.announce_id desc )";
 //       $sql = "(select * from (select * from noticeinfo) t3 join (select * from noticereader where uid='".$uid."' ) t4 where t3.announce_id = t4.announce_id and (t3.comment_switch=0 or ( t3.top_time < 10  and t3.comment_switch>0)) order by t3.announce_id desc) limit 100";
        $time  = time();
        $sql="(select * from (select * from noticeinfo) t1 join (select * from noticereader where uid='".$uid."') t2 where t1.announce_id = t2.announce_id and((t1.top_time > $time and t1.comment_switch>0) or (t1.top_time is null and t1.comment_switch>0)) order by t1.comment_switch desc,t1.announce_id desc limit 1000 )"."union (select * from (select * from noticeinfo) t3 join (select * from noticereader where uid='".$uid."' ) t4 where t3.announce_id = t4.announce_id and (t3.comment_switch=0 or ( t3.top_time < $time  and t3.comment_switch>0)) order by t3.announce_id desc limit 1000) limit 10";
//     	}
    /* 	var_dump($sql);
    	exit(); */
    	

//     	$sql="select * from noticeinfo as a  join noticereader as b  on a.announce_id=b.announce_id where b.uid='1001'  order by a.announce_id desc limit 5";
    	$result = \yii::$app->db->createCommand($sql);
    	$integralArr = $result->queryAll();   
    	$count=count($integralArr);
    	
    	\Yii::$app->session['public_count']=$count;   
       return $this->render('index',array('NoticeList'=>$integralArr,'count'=>$count,'admin'=>$admin,'time'=>$time));
    }
    public function actionGetdata(){

    	$start=\yii::$app->request->get('start');
    	$searchcontent=\yii::$app->request->get('searchcontent');
    	 $uid =\Yii::$app->session['user.uid'];
//     	echo $public_count;
     /*    global $public_count2;
        echo $public_count2; */
    	$public_count=\Yii::$app->session['public_count'];
    	if(strlen($searchcontent)>0){
    	    $sql="select * from (select * from noticeinfo where title like '%".$searchcontent."%') t1 join (select * from noticereader where uid='".$uid."') t2 on t1.announce_id = t2.announce_id order by t1.comment_switch desc, t1.announce_id desc limit ".$public_count.",5";
//     	    $sql="select * from noticeinfo where title like '%".$searchcontent."%' limit ".$start.",3";
    	}else{
            $time  = time();
//    		$sql="select * from (select announce_id,type,title,content,attach,sender,comment_switch,enterpris_id,FROM_UNIXTIME(time,'%Y-%m-%d %H:%i:%S') as noticetime,confirmNum,sender_name from noticeinfo) t1 join (select * from noticereader where uid='".$uid."') t2 on t1.announce_id = t2.announce_id order by t1.comment_switch desc, t1.announce_id desc limit ".$public_count.",5";
            $sql="(select * from (select announce_id,type,title,content,attach,sender,comment_switch,enterpris_id,FROM_UNIXTIME(time,'%Y-%m-%d %H:%i:%S') as noticetime,confirmNum,sender_name,top_time from noticeinfo) t1 join (select * from noticereader where uid='".$uid."') t2 where t1.announce_id = t2.announce_id and((t1.top_time > $time and t1.comment_switch>0) or (t1.top_time is null and t1.comment_switch>0)) order by t1.comment_switch desc,t1.announce_id desc limit 1000 )"."union (select * from (select announce_id,type,title,content,attach,sender,comment_switch,enterpris_id,FROM_UNIXTIME(time,'%Y-%m-%d %H:%i:%S') as noticetime,confirmNum,sender_name,top_time from noticeinfo) t3 join (select * from noticereader where uid='".$uid."' ) t4 where t3.announce_id = t4.announce_id and (t3.comment_switch=0 or ( t3.top_time < $time  and t3.comment_switch>0)) order by t3.announce_id desc limit 1000) limit $public_count,5";
//     	    $sql="select * from (select * from noticeinfo) t1 join (select * from noticereader where uid='".$uid."') t2 on t1.announce_id = t2.announce_id order by t1.announce_id desc limit ".$public_count.",5";
//     	    $sql="select * from noticeinfo limit ".$start.",3";
    	}
    
//     	var_dump($sql);
    	//         $NoticeList=$model->findAll($condition);
    	$result = \yii::$app->db->createCommand($sql);
//     	$model = Noticeinfo::findBySql($sql)->all();
    	$integralArr = $result->queryAll();
    	
     	$public_count=$public_count+5;
    	\Yii::$app->session['public_count']=$public_count; 
    	\Yii::$app->session['searchcontent']=$searchcontent;
    	echo json_encode($integralArr);
    	exit();
    }
    public function actionSearch() {
         $model = new Noticeinfo();
        $searchtitle=\yii::$app->request->get('searchtitle');
       $uid =\Yii::$app->session['user.uid'];
        $sql="select * from (select * from noticeinfo where title like '%".$searchtitle."%') t1 join (select * from noticereader where uid='".$uid."') t2 on t1.announce_id = t2.announce_id order by t1.comment_switch desc,t1.announce_id desc limit 10";
        //         $NoticeList=$model->findAll($condition);
        $result = \yii::$app->db->createCommand($sql);
       
        //     	$model = Noticeinfo::findBySql($sql)->all();
        $integralArr = $result->queryAll();
        $public_count=count($integralArr);
        \Yii::$app->session['public_count']=$public_count;
        echo json_encode($integralArr);
        exit();
        
    }
    public function actionDel() {
        $auth = new AuthToken();
        $auth->authTokenSession();
    	$nid=\yii::$app->request->get('nid');
        $notice = Noticeinfo::findOne(['announce_id'=>$nid,"sender"=>\yii::$app->session['user.uid']]);
        if($notice){
            $notice->delete();
            echo 1;
        }else{
            echo 0;
        }
        exit;
        return $this->redirect(['notice/index',
            "uid"=>\yii::$app->session['user.uid'],
            "eguid"=>\yii::$app->session['user.enterprisId'],
            "auth_token"=>\yii::$app->session['user.token']
            ]); 
    }
}
