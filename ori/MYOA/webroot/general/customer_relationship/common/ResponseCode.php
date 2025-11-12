<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_file.php");
require_once("inc/utility_field.php");

/**
 * 返回码
 * @author ysr
 */
class ResponseCode
{
    // 自定义返回状态码
    const SUCCESS = "200";  // 成功
    const FAILED = "0";  // 失败

    public function success($dataArray)
    {
        $result = array(
            'code' => self::SUCCESS,
            'status' => 'success',
            'msg' => '成功',
            'data' => $dataArray
        );
        return $result;
    }

    public function failed($dataArray)
    {
        $result = array(
            'code' => self::FAILED,
            'status' => 'failed',
            'msg' => '失败',
            'data' => $dataArray
        );
        return $result;
    }

    public function listPage($dataArray)
    {
        $result = array(
            'code' => self::SUCCESS,
            'status' => 'success',
            'msg' => '查询数据列表成功',
            'data' => $dataArray
        );
        return $result;
    }

    public static $MSG_NOTICE = array(
        'T' => '待提交',
        'P' => '审核中'
    );

    /**
     * 检查数组参数非空
     * @param {array} 待校验数组
     * @param {array} $noEmptyKeyArray 需要校验非空的key数组
     * @param {array} $errorMsgArray 错误消息数组
     * @return {array} $result =array('empty'=>true or false ,'msg'=>'错误提示') 
     * array("id" => "参数不能为空","chg_type" => "变动类型不能为空", "chg_amt" => "变动金额不能为空",  "chg_remark" => "变动说明不能为空")
     */
    public function arrayKeyEmpty($data, $noEmptyKeyArray)
    {
        $result = array('empty' => false, 'msg' => '');
        if (empty($data)) {
            return array('empty' => true, 'msg' => '数据不能为空');
        }
        foreach ($noEmptyKeyArray as $key => $value) {
            //若$data[$key]的值为0，使用empty($data[$key])判断时，值为true，会把0当空判断
            if (!array_key_exists($key, $data) || $data[$key] == "") {
                $result['empty'] = true;
                $result['msg'] = !empty($value) ? $value : 'param ' . $key . ' not be empty ';
                return $result;
            }
        }
        return $result;
    }
}
