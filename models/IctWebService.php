<?php
namespace app\models;
use Yii;
use yii\base\Model;

use app\models\Curl;
/**
 *
 */
class IctWebService extends Model
{
	public $ictIp;
	public $eid;
    public $url;
    public $params = [];
    public $attr = [];
    public $noticeUrl = "/notice/web/index.php?r=admin/noticecontent/index&uid=&eguid=&auth_token=&id=";
    public $allTypeMemberUid;
    public $allTypeMemberName;
    private $list = [];

	function __construct(){
//         $this->ictIp = '127.0.0.1' ;
        $this->ictIp = '192.168.139.160';
        /*$eid = explode('@',Yii::$app->session['user.uid']);
        if(isset($eid[1])){
            $this->eid = $eid[1];
        }*/
        $this->eid = yii::$app->session['eid'];
        //$this->eid = 83273;
        $this->url = "http://$this->ictIp/elgg/services/api/rest/json/?method=";
        $this->params['auth_token'] = Yii::$app->session['user.token'];
        $this->params['api_key'] = Yii::$app->session['user.apiKey'];

    }
    public function storeList(){
        $this->list = array_flip(array_flip($this->list));
        yii::$app->session['list'] = json_encode($this->list);
    }
    public function getAdminToken(){
        if($this->params["auth_token"] && $this->params["api_key"]){
            return;
        }
        $curl = new Curl();
        $url = $this->url . "auth.gettoken";
        $params['name']="18900913302";
        $params['password']="123456";
        $params['api_key']="36116967d1ab95321b89df8223929b14207b72b1";
        $elggAdmin = json_decode($curl->post($url, $params), true);
        $this->params = null;
        $this->params['auth_token'] = $elggAdmin['result']['auth_token'];
        $this->params['api_key'] = $params['api_key'];
    }

    /**
     * auth.gettoken 获取用户认证信息
     *
     * @param
     *        	array
     * @return mixed
     * @author fyq
     */
    public function getAuth_Token($params) {

    	// auth_token认证
    	$api_key = Yii::$app->session['user.apiKey'];
    	$params = [
    			'name' => $params ['name'],
    			'password' => $params ['password'],
    			'api_key' => $api_key
    	];
    	$webService = "http://192.168.139.160/elgg/services/api/rest/json/?method=auth.gettoken";
    	$curl = new Curl();
    	$result = $curl->post ( $webService, $params );

    	$result = json_decode ( $result );
    	return $result;
    }

	public function createTreeData($oData){
        $temp = $oData;
        unset($temp['member']);
        unset($temp['child']);
        $tData = array();
        if(!isset( $temp['nodename'][0])){
            return;
        }
        $tData['text'] = $temp['nodename'][0];
        $tData['isExpand'] = false;
        $tData['children'] = array();
        $tData['icon'] = "images/icons/address.png";
        $tData["ischecked_m"] = "incomplete";
        $tData["ischecked_c"] = "incomplete";
        $mcount = count($oData['member']);
        if($mcount > 0){
            $flag = 0;
            $flag_1 = 0;
            foreach($oData['member'] as $m){
                if(isset($m['membername'][0])){
                    $dbResult = PaiUser::findOne(["user_id"=>$m["uid"][0], "admin"=>1]);
                    if($dbResult){
                        $this->list[] = $m['uid'][0];
                        $flag++;
                    }else{
                        $flag_1++;
                    }
                    $tData['children'][] = ["text"=>$m['membername'][0],
                                            "id"=>$m['uid'][0],
                                            "isExpand"=>false,
                                            "icon"=>"images/icons/memeber.gif",
                                            "ischecked"=>($dbResult?"complete":"none"),
                                                ];
                }
            }
            if($mcount == $flag){
                $tData["ischecked_m"] = "complete";
            }

            if($mcount == $flag_1){
                $tData["ischecked_m"] = "none";
            }
        }else{
            $tData["ischecked_m"] = "complete_none";
        }
        $ocount = count($oData['child']);
        if($ocount > 0){
            $flag = 0;
            $flag_1 = 0;
            foreach($oData['child'] as $c){
                $array = $this->createTreeData($c);
                if($array["ischecked"] == "complete"){
                    $flag++;
                }
                if($array["ischecked"] == "none"){
                    $flag_1++;
                }
                $tData['children'][] = $array;
                unset($array);
            }
            if($ocount == $flag){
                $tData["ischecked_c"] = "complete";
            }
            if($ocount == $flag_1){
                $tData["ischecked_c"] = "none";
            }
            if(($tData["ischecked_m"] == "complete" || $tData["ischecked_m"] == "complete_none" ) && $tData["ischecked_c"] == "complete"){
                $tData["ischecked"] = "complete";
            }else if($tData["ischecked_m"] == "none" && $tData["ischecked_c"] == "none"){
                $tData["ischecked"] = "none";
            }else if($tData["ischecked_m"] == "complete_none" && $tData["ischecked_c"] == "none"){
                $tData["ischecked"] = "none";
            }else{
                $tData["ischecked"] = "incomplete";
            }
        }else{
            $tData["ischecked"] = $tData["ischecked_m"];
            if($tData["ischecked"] == "complete_none"){
                $tData["ischecked"] = "none";
            }
        }
        if($ocount == 0 && $mcount == 0){
            $tData["ischecked"] = "none";
        }
        return $tData;
    }

    public function getICTContacts(){
//         \Yii::$app->redis->select(11);
//         $contacts['result'] = json_decode(\Yii::$app->redis->get("EDATA:".$this->eid),true);
//         if($contacts['result']){
//             $contacts['status'] = 0;
//             return $contacts;
//         }

        $this->getAdminToken();
        $curl = new Curl();
        $url = "http://$this->ictIp/elgg/services/api/rest/json/?method=ldap.web.search";
        $params['eid'] = $this->eid;
        $params['id_list[0]'] =  "null";
        $params['attr_list[0]'] = "membername";
        $params['attr_list[1]'] = "uid";
        $params['auth_token'] = $this->params['auth_token'];
        $params['api_key'] = $this->params['api_key'];
        $contacts = json_decode($curl->post($url, $params),true);
        return $contacts;

    }
    public function getICTContact($uid){
        $this->getAdminToken();
        $curl = new Curl();
        $url = "http://$this->ictIp/elgg/services/api/rest/json/?method=ldap.web.get.node.info";
        $params['eid'] = $this->eid;
        $params['id_list[0]'] =  $uid;
        $params['attr_list[0]'] = "true";
        $params['auth_token'] = $this->params['auth_token'];
        $params['api_key'] = $this->params['api_key'];
        $contacts = json_decode($curl->post($url, $params),true);
        return $contacts;

    }
    public function authToken($uid,$token){
        $curl = new Curl();
        $url = $this->url."check.user.token";
        $uidexp = explode('@',$uid);
        if($uidexp[0] == 'buliping'){
            $uid = $uidexp[0];
        }
        $this->params['uid'] = $uid;
        $this->params['auth_token'] = $token;
        $auth = json_decode($curl->post($url, $this->params),true);
        if(isset($auth['result']['success'])){
            return 1;
        }else{
            return 0;
        }
    }
    public function checkWordDay($time){
        $curl = new Curl();
        $d = date("Ymd", $time);
        $url = "http://www.easybots.cn/api/holiday.php?d=$d";
        $result = json_decode($curl->get($url),true);
        if($result["$d"] == 0){
            return 1;//工作日
        }else{
            return 0;//非工作日
        }
    }
}

