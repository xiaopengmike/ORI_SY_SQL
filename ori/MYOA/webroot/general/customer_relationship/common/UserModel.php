<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_file.php");
require_once("inc/utility_field.php");

/**
 * 公共操作类
 * @author ysr
 * 已有功能：权限查询；附件操作；参数获取；编码转换；消息发送；返回结果封装；
 * 邮件发送；下属部门获取；获取部门人员；获取公司归属；获取admin权限；翻页控件
 * 待补充：
 */
class UserModel
{

    /**
     * 根据deptId获取所有归属部门集合（包括本部门）
     */
    public function getSubDept($deptId)
    {
        if (empty($deptId)) {
            $deptId = $_SESSION['LOGIN_DEPT_ID'];
        }

        $subDeptSql = " call getChild($deptId); "; // 查询下属部门函数getChild
        $res = exequery(TD::conn(), $subDeptSql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    /**
     * 根据部门ID获取所有对应的用户信息
     */
    public function getUsersByDept($deptId, $whereStr = '')
    {
        if (empty($deptId)) {
            $deptId = $_SESSION['LOGIN_DEPT_ID'];
        }

        $userSql = " select u.uid, u.user_id, u.user_name, u.byname, u.user_byid, u.user_bu, u.dept_id, d.dept_name 
                    from user u 
                    left join department d on d.dept_id = u.dept_id 
                    where u.dept_id in($deptId) ";
        $userSql .= $whereStr;
        $res = exequery(TD::conn(), $userSql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    /**
     * 针对user表公司部分人员未设置公司归属userBu，采用department表的userBu获取公司归属信息
     * 根据deptId获取userBu
     * TODO: 添加跨公司流程操作取公司别 ; 流程需分公司划分多线路，同时需管控流程范围权限
     */
    function getUserBu($deptId)
    {
        // 公司公司归属
        $comSql = " select user_bu from department where dept_id = '" . $deptId . "' ";
        $comRes = exequery(TD::conn(), $comSql);
        $com = mysql_fetch_assoc($comRes);
        $company = "";
        $company = $com['user_bu'];

        return $company;
    }

    /**
     * 获取指定用户信息
     */
    public function getUserInfo($userId)
    {
        $result = array();
        if (empty($userId)) {
            return $result;
        }
        $where = " where u.user_id = '" . $userId . "' ";

        $sql = " select u.*, d.dept_name deptName, c.id companyId, c.company companyName, c.user_bu company_user_bu from user u " .
            " left join department d on d.dept_id = u.dept_id " .
            " left join company c on c.user_bu = d.user_bu " . $where;
        $res = exequery(TD::conn(), $sql);
        $result = mysql_fetch_assoc($res);

        return $result;
    }


    /**
     * 获取用户对应模块的查询权限
     * 流程类型从inhe_hr_flow_type获取
     */
    public function getAdminDataQuery($userId, $deptId, $type)
    {
        $isAdmin = $systemAdmin = false;
        $where = " where 1=1 ";
        if ($userId != "") {
            $where .= " and user_id = '" . $userId . "' ";
        } else if ($deptId != "") {
            $where .= " and dept_id = '" . $deptId . "' ";
        }
        if ($type != "") {
            $where .= " and type = '" . $type . "' ";
        }
        $sql = " select user_bu, dept_ids, user_ids, is_admin, system_admin from inhe_system_admin " . $where . " order by id desc ";
        $res = exequery(TD::conn(), $sql);
        $comArr = $userIdArr = $deptIdArr = $result = array();
        $comStr = $userIdStr = $deptIdStr = "";
        while ($item = mysql_fetch_assoc($res)) {
            $userBu = $item['user_bu'];
            $userIds = $item['user_ids'];
            $deptIds = $item['dept_ids'];
            $isAdmin = $item['is_admin'];
            $systemAdmin = $item['system_admin'];
        }
        
        if (!empty($userBu)) {
            $comArr = explode(',', $userBu);
            foreach ($comArr as $v) {
                $comStr .= "'" . $v . "',";
            }
            $comStr = rtrim($comStr, ',');
        }
        if (!empty($userIds)) {
            $userIdArr = explode(',', $userIds);
            foreach ($userIdArr as $kv) {
                $userIdStr .= "'" . $kv . "',";
            }
            $userIdStr = rtrim($userIdStr, ',');
        }
        if (!empty($deptIds)) {
            $deptIdArr = explode(',', $deptIds);
            foreach ($deptIdArr as $kv) {
                $deptIdStr .= "'" . $kv . "',";
            }
            $deptIdStr = rtrim($deptIdStr, ',');
        }
        // 释放结果集，减少内存占用
        mysql_free_result($res);
        $result = array(
            'systemAdmin' => $systemAdmin,  // 系统管理员权限开放，主要用于功能权限控制
            'isAdmin' => $isAdmin,  // 数据权限开放
            'userBu' => $comStr,  // 公司范围权限
            'deptIds' => $deptIdStr,  // 部门范围权限
            'userIds' => $userIdStr  // 指定用户范围权限
        );

        return $result;
    }
}
