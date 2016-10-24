<?php
namespace app\modules\admin\models;
use Yii;
use yii\base\Model;
use app\models\Noticeinfo;
use app\models\Noticereader;
use app\models\Noticeuser;
use app\modules\admin\models\IctWebService;

class Action extends Model{
	function __construct(){
	}
    public function getNotices($search, $uid, $fromID = 0, $limit = 10) {
        $model = new Noticeinfo();
        $time  = time();
        //获取思路 先获取置顶的 再获取非置顶的 然后结果集unison 需要注意fromID的处理 如果fromID是置顶的则按照正常获取 如果是非置顶的则只获取去非置顶的
        if($fromID == 0){
            $where1 = "";
            $where2 = "";
            $sql="(select * from noticeinfo t1 join noticereader t2 where t2.uid='".$uid."' and t1.title like '%".$search."%' and t1.announce_id = t2.announce_id $where1 and ((t1.top_time > $time and t1.comment_switch>0) or (t1.top_time is null and t1.comment_switch>0)) order by t1.comment_switch desc,t1.announce_id desc limit 1000 )"."union (select * from noticeinfo  t3 join noticereader t4 where t4.uid='".$uid."' and t3.title like '%".$search."%' and t3.announce_id = t4.announce_id $where2 and (t3.comment_switch=0 or ( t3.top_time < $time  and t3.comment_switch>0)) order by t3.announce_id desc limit 1000) limit $limit";
        }else{
            $fromInfo = Noticeinfo::findOne(["announce_id"=>$fromID]);
            if($fromInfo){
                //如果fromID是置顶
                if(($fromInfo->top_time > $time && $fromInfo->comment_switch > 0) || (!$fromInfo->top_time && $fromInfo->comment_switch > 0)){
                    $where1 = "and t1.announce_id < $fromID";
                    $where2 = "";
                    $sql="(select * from noticeinfo t1 join noticereader t2 where t2.uid='".$uid."' and t1.title like '%".$search."%' and t1.announce_id = t2.announce_id $where1 and ((t1.top_time > $time and t1.comment_switch>0) or (t1.top_time is null and t1.comment_switch>0)) order by t1.comment_switch desc,t1.announce_id desc limit 1000 )"."union (select * from noticeinfo  t3 join noticereader t4 where t4.uid='".$uid."' and t3.title like '%".$search."%' and t3.announce_id = t4.announce_id $where2 and (t3.comment_switch=0 or ( t3.top_time < $time  and t3.comment_switch>0)) order by t3.announce_id desc limit 1000) limit $limit";
                }else{
                    $where1 = "";
                    $where2 = "and t3.announce_id < $fromID";
                    $sql="select * from noticeinfo  t3 join noticereader t4 where t4.uid='".$uid."' and t3.title like '%".$search."%' and t3.announce_id = t4.announce_id $where2 and (t3.comment_switch=0 or ( t3.top_time < $time  and t3.comment_switch>0)) order by t3.announce_id desc limit $limit";
                }
            }
        }
        //$sql="select * from (select * from noticeinfo where title like '%".$search."%') t1 join (select * from noticereader where uid='".$uid."') t2 on t1.announce_id = t2.announce_id $where1 order by t1.comment_switch desc,t1.announce_id desc limit $limit";
        
        $result = \yii::$app->db->createCommand($sql);

        $integralArr = $result->queryAll();
        return $integralArr;
    }
    public function deleteNotice($nid, $uid){
        $notice = Noticeinfo::findOne(['announce_id'=>$nid,"sender"=>$uid]);
        if($notice){
            $notice->delete();
        }

    }
    public function getNotice($nid, $uid){
        $result = array();
        $noticeInfo = Noticeinfo::find()->where(["announce_id"=>$nid])->asArray()->one();
        if($noticeInfo){
            $noticereader = Noticereader::find()->where(["announce_id"=>$nid, "uid"=>$uid])->asArray()->one();
            if($noticereader){
                $result = array_merge($noticeInfo, $noticereader);
                $result["isConfirm"] = $noticeInfo["confirm"];
                //已读人数
                $result["readCount"] = Noticereader::find()->where(['relation' => 'read','announce_id' => $nid])->count();
                //已经确认人数
                $result["confirmCount"] = Noticereader::find()->where(['confirm' => 'yes','announce_id' => $nid])->count();
                //查看总人数
                $result["allCount"] = Noticereader::find()->where(['announce_id' => $nid])->count();
                
            }
        }
        return $result;
    }
    public function getReaders($announce_id){
        //已读人数
    	$read_count = Noticereader::find()->where(['relation' => 'read','announce_id' => $announce_id])->count();
        //已经确认人数
    	$confirm_count = Noticereader::find()->where(['confirm' => 'yes','announce_id' => $announce_id])->count();
        //查看总人数
    	$all_count = Noticereader::find()->where(['announce_id' => $announce_id])->count();
    	//查询未查看人员
        $unreaders = Noticereader::find()->where(['relation' => 'unread','confirm'=>'no','announce_id' => $announce_id])->asArray()->all();
    	//查询已查看人员
        $readers = Noticereader::find()->where(['relation' => 'read','confirm' => 'no','announce_id' => $announce_id])->asArray()->all();
    	//查询已确认人员
        $confirmusers = Noticereader::find()->where(['confirm' => 'yes','announce_id' => $announce_id])->asArray()->all();
        $result = [];
        $result["readCount"] = $read_count;
        $result["confirmCount"] = $confirm_count;
        $result["allCount"] = $all_count;
        $result["unReaders"] = $unreaders;
        $result["readers"] = $readers;
        $result["confirmers"] = $confirmusers;
        $ws = new IctWebService();
        $wip = $ws->getNodeInfo("root",['wip']);
        $photoip = "http://".$wip['result'][0]['data']['wip'][0];
        $result["photoIp"] = $photoip;
        return $result;
    }
    public function canSave($uid){
        $noticeUser = Noticeuser::findOne(["uid"=>$uid]);
        if($noticeUser){
            return true;
        }else{
            return false;
        }
    }
    public function alreadyRead($nid, $uid, $action){
        $noticeInfo = Noticereader::findOne(["uid"=>$uid, "announce_id"=>$nid]);
        if($noticeInfo){
            if($action == 1)
                $noticeInfo->relation = "read";
            if($action == 2)
                $noticeInfo->confirm = "yes";
            $noticeInfo->save();
        }
    }

}
