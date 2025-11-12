<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_file.php");
require_once("inc/utility_field.php");
require_once("inc/utility_sms1.php");
require_once("inc/utility_sms2.php");
//require_once("ResponseCode.php");

/**
 * 表单用户权限管理
 * @author chenzy
 */
class FormUserManageModel
{
    /**
     * $type表单类型：以英文为标识(销售合同评审sales_contract_review；
     * 生产通知单production_order；发货通知单shipping_notice)
     */
    public function isHide($type = 'production_order',$user_bu){
        try{
            $userId = $_SESSION["LOGIN_USER_ID"];
            $where = " where 1=1 and used=1 and find_in_set('$type',type) and find_in_set('$userId',user_id)";
            if(!empty($user_bu)){
                $where .= " and (find_in_set('$user_bu',user_bu) or user_bu='ALL') ";
            }
            $sql = " select * from inhe_form_user_manage  " . $where;
            $res = exequery(TD::conn(), $sql);
            if($item = mysql_fetch_assoc($res)){
                return true;
            }
            // while ($item = mysql_fetch_assoc($res)) {
            //     $userIds=explode(',',$item['user_id']);
            //     if($userId == $item['user_id']||(is_array($userIds)&&in_array($userId,$userIds))){
            //         mysql_free_result($res);
            //         return true;
            //     }    
            // }
            mysql_free_result($res);
            return false;
       }catch(Exception $e){
            return false;    
       }
    }
}
