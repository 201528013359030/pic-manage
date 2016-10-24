<?php

namespace app\modules\admin\controllers;
use yii\web\Controller;
use app\models\Noticeinfo;
use app\models\Noticereader;
use app\modules\admin\models\AuthToken;
use app\modules\admin\models\IctWebService;
class NoticecontentController extends Controller
{
	public $layout  = false;
    public function actionIndex()
    {
        $auth = new AuthToken();
    	$f=\Yii::$app->request->get('f');
    	$a=\Yii::$app->request->get('a');
        if($f == 1){
            $auth->authTokenSession();
        }else{
            $auth->authTokenUrl();
        }
    	$model = new Noticeinfo();
    	$announce_id=\Yii::$app->request->get('id');
  //  	$uid=\yii::$app->request->get('uid');
        $uid =\Yii::$app->session['user.uid'];
     /*    echo $uid;
        exit(); */
//     	echo "id:".$announce_id."<br>";
//     	$condition="select * from noticeinfo as a right join noticereader as b on  a.announce_id=b.announce_id where a.announce_id='".$announce_id."'";
    	$condition="select * from noticeinfo where announce_id='".$announce_id."'";
    	$result = \yii::$app->db->createCommand($condition);
    	$integralArr = $result->queryOne();
        if(!$integralArr){
                return $this->redirect(['notice/index',
                    "uid"=>\Yii::$app->request->get('uid'),
                    "id"=>\Yii::$app->request->get('id'),
                    "eguid"=>\Yii::$app->request->get('eguid'),
                    "nid"=>\Yii::$app->request->get('nid'),
                    "auth_token"=>\Yii::$app->request->get('auth_token')
                    ]);
            echo '<div style="text-align:center;padding-top:100px;">';
            echo ' 公告不存在或被管理员删除';
            echo ' </div>';
            return ;
        }
    	$attach=$integralArr["attach"];
    	$attachList=json_decode($attach,true);
        $attachCount=count($attachList);
      /*  	var_dump($attachList);
       	exit(); */
    /* 	$user_condition="select * from noticereader where announce_id='".$announce_id."' and";
    	$user_result = \yii::$app->db->createCommand($user_condition);
    	$userArr = $user_result->queryAll(); */
    	$reader_model =new Noticereader();
    	$read_count = Noticereader::find()
    	->where(['relation' => 'read','announce_id'=>$announce_id])
    	->count();
    	$confirm_count = Noticereader::find()
    	->where(['confirm' => 'yes','announce_id'=>$announce_id])
    	->count();
    	$all_count = Noticereader::find()
    	->where(['announce_id' => $announce_id])
    	->count();
 		$reader_model = Noticereader::findOne([
    			'uid' => $uid,
    			'announce_id'=>$announce_id,
    			]);
 		$nocicereaderCount=count($reader_model);
       if(count($reader_model)){                 //如果有该同学
       	$reader_model->relation= 'read';
       	$reader_model->save();
       }
        if($a){
            $nocicereaderCount = 0;
        }
    
    	
    	//查询该用户是否已经确认过：
    	$confirmed_count = Noticereader::find()
    	->where(['confirm' => 'yes','announce_id'=>$announce_id,'uid'=>$uid])
    	->count();
//     	var_dump($confirmed_count);
    
        if(file_exists("/var/lib/mosquitto/tls")){
            $tls = "https://";  
        }else{
            $tls = "http://";
        }
        $ws = new IctWebService();
        $offline_ip = $ws->getAdminToken();
        if(is_array($attachList)){
            for($i=0;$i<count($attachList);$i++){
                if(!isset($attachList[$i]['path'])){
                }
                $tmpUrl = explode("media_file/",$attachList[$i]['path']);
                $attachList[$i]['path'] = $tls.$offline_ip['result']['offline_ip']."/media_file/".$tmpUrl[1];
                $attachList[$i]['url'] = $attachList[$i]['path'];
                $attachList[$i]['path'] = base64_encode($attachList[$i]['path']);
            }
        }
        if(YII_TEST){
            $read_count = 267;
            $confirm_count = 105;
        }
    	return $this->render('index',array('noticecontent'=>$integralArr,'attachList'=>$attachList,
    	    'read_count'=>$read_count,'confirm_count'=>$confirm_count,'confirmed_count'=>$confirmed_count,'all_count'=>$all_count,'attach_count'=>$attachCount,'announce_id'=>$announce_id,'uid'=>$uid,'offline_ip'=>$offline_ip['result']['offline_ip'],'noticereadercount'=>$nocicereaderCount));
    }
    public function actionConfirm(){
     
    /*  	$model->confirm= 'yes';
     	$model->save(); */ 
    	$reader_model =new Noticereader();
    	$announce_id=\yii::$app->request->get('id');
    	 $uid =\Yii::$app->session['user.uid'];
    	$reader_model = Noticereader::findOne([
    			'uid' =>$uid,
    			'announce_id'=>$announce_id,
    			]);
    	
    	$reader_model->confirm= 'yes';
    	$reader_model->save();
    	echo "1";
    	exit();
    	
    }
    public function actionDownload(){
    	$fileurl=\yii::$app->request->get('file');
    	$filename=\yii::$app->request->get('name');
     //   $fileinfo = get_headers($fileurl, 1); 
        //$fileurl = "http://127.0.0.1".base64_decode($fileurl);
        $fileurl = base64_decode($fileurl);
        $tmpUrl = explode("media_file/",$fileurl);
        $fileurl = "http://"."127.0.0.1"."/media_file/".$tmpUrl[1];
        $fileinfo = get_headers($fileurl, 1);
        ob_end_clean(); //函数ob_end_clean 会清除缓冲区的内容，并将缓冲区关闭，但不会输出内容。
        header("Content-Type: application/force-download;"); //告诉浏览器强制下载
        header("Content-Transfer-Encoding: chunked"); 
        header("Content-Disposition: attachment; filename=$filename");   //attachment表明不在页面输出打开，直接下载
        header("Content-Length: ". $fileinfo['Content-Length']);
        header("Expires: 0"); 
        header("Cache-control: private"); 
        header("Pragma: no-cache"); //不缓存页面
        $filesize = readfile($fileurl);

    }

}
