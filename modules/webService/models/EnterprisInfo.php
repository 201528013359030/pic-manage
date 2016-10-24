<?php
namespace app\modules\webService\models;
use app\modules\webService\models\Wechat;
use app\modules\webService\models\WechatToken;
use app\models\Attendancer;
use app\models\CheckRecord;
use Yii;


class EnterprisInfo extends \app\modules\webService\models\WebService
{


	public $registerApi = [];
    private $conn = "";
	function __construct(){
        parent::init();
        parent::registerApi("attendance.info.get",
                "getAttendanceInfo",
                [
                "info"=>['type'=>'string'],
                ],
                false
                );
        parent::registerApi("admin.check",
                "checkAdmin",
                [
                "info"=>['type'=>'string'],
                ],
                false
                );
        parent::registerApi("admin.get",
                "getAdmin",
                [
                "info"=>['type'=>'string'],
                ],
                false
                );
        parent::registerApi("department.record.get",
                "getDepartmentRecord",
                [
                "info"=>['type'=>'string'],
                ],
                false
                );
        parent::registerApi("own.record.get",
                "getOwnRecord",
                [
                "info"=>['type'=>'string'],
                ],
                false
                );
        parent::registerApi("department.statistics.get",
                "getDepartmentStatistics",
                [
                "info"=>['type'=>'string'],
                ],
                false
                );
        parent::registerApi("own.statistics.get",
                "getOwnStatistics",
                [
                "info"=>['type'=>'string'],
                ],
                false
                );
        parent::registerApi("record.update",
                "updateRecord",
                [
                "info"=>['type'=>'string'],
                ],
                false
                );
	}
    private function connectToSSQL(){
        if($this->conn){
            return;
        }
        $server ="heshi";  //服务器IP地址,如果是本地，可以写成localhost
        $uid ="sa";  //用户名
        $pwd ="sa_520"; //密码
        $database ="master";  //数据库名称

        $this->conn =mssql_connect($server,$uid,$pwd) or die ("connect failed");
        mssql_select_db($database,$this->conn);
        
    }
    public function getAttendanceInfo($info){
        $info = json_decode($info, true);
        $this->connectToSSQL();
        $date  = date("M j Y", strtotime($info["time"]));
        $time = strtotime($date);

        $connection = Yii::$app->db;
        $sql = "SELECT tNo, tName FROM ict2he.teacherinfo WHERE uid = '{$info["uid"]}' limit 1";
        $command = $connection->createCommand($sql);
        $result_ = $command->queryAll();

        $query ="select * from CHECKINOUT where CHECKTIME like '%$date%' and USERID = '{$result_[0]["tNo"]}'";
        $row =mssql_query($query);
        $result["name"] = $result_[0]["tName"];
        $result["info"] = [];
        while($list=mssql_fetch_array($row)){
            $tmp["time"] = date("H:i:s", strtotime($list["CHECKTIME"]));
            $result["info"][] = $tmp;
        }
        return $result;
    }
    public function checkAdmin($info){
        $info = json_decode($info, true);
        $model = Attendancer::findOne(["uid"=>"{$info["uid"]}"]);
        if($model){
            return 1;
        }else{
            return 0;
        }
    }
    public function getDepartmentStatistics($info){
        $info = json_decode($info, true);
        $connection = Yii::$app->db;
        $sql = "SELECT tNo, institute FROM ict2he.teacherinfo WHERE institute in (select institute from ict2he.teacherinfo where uid= '{$info["uid"]}') $where_ order by tName asc";
        $command = $connection->createCommand($sql);
        $result_ = $command->queryAll();
        
        $fromTime = strtotime($info["ftime"]);
        $toTime = strtotime($info["ttime"]) + 86400;
        $where = "date >= $fromTime and date <= $toTime";
        $where .= " and flag <> '22' ";
        $inExc = 0;
        $inNone = 0;
        $outExc = 0;
        $outNone = 0;
        $result = [];
        if($result_){
            foreach($result_ as $r_){
                $sql = "select * from check_record where tid = '{$r_["tNo"]}' and $where";
                $command = $connection->createCommand($sql);
                $result_r = $command->queryAll();
                if($result_r){
                    foreach($result_r as $r_r){
                        $result[] = $r_r;
                    }
                }
            }
        }
        foreach($result as $r){
            if(substr($r["flag"], 0, 1) == "0"){
                $inNone += 1;
            }
            if(substr($r["flag"], 0, 1) == "1"){
                $inExc += 1;
            }
            if(substr($r["flag"], 1, 1) == "0"){
                $outNone += 1;
            }
            if(substr($r["flag"], 1, 1) == "1"){
                $outExc += 1;
            }
        }
        $return["in_no"]= $inNone;
        $return["in_exc"]= $inExc;
        $return["out_no"]= $outNone;
        $return["out_exc"]= $outExc;
        return $return;
    
    }
    public function getOwnStatistics($info){
        $info = json_decode($info, true);
        $connection = Yii::$app->db;
        $sql = "SELECT tNo FROM ict2he.teacherinfo WHERE uid = '{$info["uid"]}'";
        $command = $connection->createCommand($sql);
        $result_ = $command->queryAll();
        
        $fromTime = strtotime($info["ftime"]);
        $toTime = strtotime($info["ttime"]) + 86400;
        $where = "date >= $fromTime and date <= $toTime";
        $where .= " and flag <> '22' ";
        $inExc = 0;
        $inNone = 0;
        $outExc = 0;
        $outNone = 0;
        $result = [];
        if($result_){
            foreach($result_ as $r_){
                $sql = "select * from check_record where tid = '{$r_["tNo"]}' and $where";
                $command = $connection->createCommand($sql);
                $result_r = $command->queryAll();
                if($result_r){
                    foreach($result_r as $r_r){
                        $result[] = $r_r;
                    }
                }
            }
        }
        foreach($result as $r){
            if(substr($r["flag"], 0, 1) == "0"){
                $inNone += 1;
            }
            if(substr($r["flag"], 0, 1) == "1"){
                $inExc += 1;
            }
            if(substr($r["flag"], 1, 1) == "0"){
                $outNone += 1;
            }
            if(substr($r["flag"], 1, 1) == "1"){
                $outExc += 1;
            }
        }
        $return["in_no"]= $inNone;
        $return["in_exc"]= $inExc;
        $return["out_no"]= $outNone;
        $return["out_exc"]= $outExc;
        return $return;
    
    }
    public function getOwnRecord($info){
        $info = json_decode($info, true);
        $connection = Yii::$app->db;
        $where_ = "";
        $sql = "SELECT tName, tNo, uid, institute FROM ict2he.teacherinfo WHERE uid = '{$info["uid"]}' limit 1";
        $command = $connection->createCommand($sql);
        $result_ = $command->queryAll();
        
        $fromTime = strtotime($info["ftime"]);
        $toTime = strtotime($info["ttime"]) + 86400;
        $where = "date >= $fromTime and date <= $toTime";
        if($info["exception"]){
            $where .= " and flag <> '22' ";
        }
        $result = [];
        $limit_ = 0;
        $offset_ = 0;
        if($result_){
            foreach($result_ as $r_){
                $sql = "select * from check_record where tid = '{$r_["tNo"]}' and $where";
                $command = $connection->createCommand($sql);
                $result_r = $command->queryAll();
                if($result_r){
                    foreach($result_r as $r_r){
                        $tmp = $r_r;
                        $tmp["name"] = $r_["tName"];
                        $tmp["uid"] = $r_["uid"];
                        $tmp["department"] = $r_["institute"];
                        if($tmp["check_in"]){
                            $tmp["check_in"] = date('H:i', $tmp["check_in"]);
                        }
                        if($tmp["check_out"]){
                            $tmp["check_out"] = date('H:i', $tmp["check_out"]);
                        }
                        $tmp["date"] = date('Y-m-d', $tmp["date"] ) . " " . $this->transDate(date('w', $tmp["date"]));
                        if(!$tmp["note"]){
                            unset($tmp["note"]);
                        }
                        $result[] = $tmp;
                    }
                }
            }
        }
        $result_f["count"] = 0;
        $result_f["info"] = [];
        if($result){
            $result_f["count"] = count($result);
            foreach($result as $r){
                if($offset == $offset_){
                    $result_f["info"][] = $r;
                    $limit_ ++;
                }else{
                    $offset_ ++;
                }
                if($limit_ == $limit){
                    goto result;
                }
            }
        }
        result:
        return $result_f;
    }
    public function getDepartmentRecord($info){
        $info = json_decode($info, true);
        $connection = Yii::$app->db;
        $where_ = "";
        if($info["name"]){
            $where_ = " and tName like '%{$info["name"]}%'";
        }
        $sql = "SELECT tName, tNo, uid,institute FROM ict2he.teacherinfo WHERE institute in (select institute from ict2he.teacherinfo where uid= '{$info["uid"]}') $where_ order by tName asc";
        $command = $connection->createCommand($sql);
        $result_ = $command->queryAll();
        
        $fromTime = strtotime($info["ftime"]);
        $toTime = strtotime($info["ttime"]) + 86400;
        $where = "date >= $fromTime and date <= $toTime";
        if($info["exception"]){
            $where .= " and flag <> '22' ";
        }
        $result = [];
        $limit = $info["limit"]?$info["limit"]:10;
        $offset = $info["offset"]?$info["offset"]:0;
        $limit_ = 0;
        $offset_ = 0;
        if($result_){
            foreach($result_ as $r_){
                $sql = "select * from check_record where tid = '{$r_["tNo"]}' and $where";
                $command = $connection->createCommand($sql);
                $result_r = $command->queryAll();
                if($result_r){
                    foreach($result_r as $r_r){
                        $tmp = $r_r;
                        $tmp["name"] = $r_["tName"];
                        $tmp["uid"] = $r_["uid"];
                        $tmp["department"] = $r_["institute"];
                        if($tmp["check_in"]){
                            $tmp["check_in"] = date('H:i', $tmp["check_in"]);
                        }
                        if($tmp["check_out"]){
                            $tmp["check_out"] = date('H:i', $tmp["check_out"]);
                        }
                        $tmp["date"] = date('Y-m-d', $tmp["date"] ) . " " . $this->transDate(date('w', $tmp["date"]));
                        if(!$tmp["note"]){
                            unset($tmp["note"]);
                        }
                        $result[] = $tmp;
                    }
                }
            }
        }
        $result_f["count"] = 0;
        $result_f["info"] = [];
        if($result){
            $result_f["count"] = count($result);
            foreach($result as $r){
                if($offset == $offset_){
                    $result_f["info"][] = $r;
                    $limit_ ++;
                }else{
                    $offset_++;
                }
                if($limit_ == $limit){
                    goto result;
                }
            }
        }
        result:
        return $result_f;
    }
    public function getAdmin($info){
        $info = json_decode($info, true);
        list($tmp, $eid) = explode("@", $info["uid"]);
        $models = Attendancer::find()->where(["eid"=>"$eid"])->asArray()->all();
        return $models;
    }
    public function updateRecord($info){
        $info = json_decode($info, true);
        $connection = Yii::$app->db;
        $sql = "select * from activiti.a_leavebill  where id = '{$info["id"]}' limit 1";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        if($result){
            $startTime = strtotime($result[0]["leaveStartTime"]);
            $endTime = strtotime($result[0]["leaveEndTime"]);
            $type = $result[0]["leaveType"];
            $tid = $this->findTidByUid($result[0]["userid"]);
            $sql_ = "select * from attendance.check_record where tid = '$tid' and date >= '$startTime' and date <= '$endTime'";
            $command_ = $connection->createCommand($sql_);
            $result_ = $command_->queryAll();
            if($result_){
                foreach($result_ as $r_){
                    $model = CheckRecord::findOne(["id"=>"{$r_['id']}"]);
                    $model->note = "{$result[0]["leaveType"]}";
                    $model->save();
                }
            }
        }
        return 1;
    }
    private function findTidByUid($uid){
        $connection = Yii::$app->db;
        $sql = "select tNo from ict2he.teacherinfo  where uid = '$uid' limit 1";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        return $result[0]['tNo']?$result[0]['tNo']:0;
    }
    private function transDate($number){
        $date = "周1";
        switch($number){
            case 0 : $date = "周天";break;
            case 1 : $date = "周一";break;
            case 2 : $date = "周二";break;
            case 3 : $date = "周三";break;
            case 4 : $date = "周四";break;
            case 5 : $date = "周五";break;
            case 6 : $date = "周六";break;
            default: break;
        }
        return $date;
    }

}

?>
