<?php

/**
 *  系统常量参数类
 */
class SystemEnum
{
    public static $STATUS = array(
        'T' => '待提交',
        'P' => '审核中',
        'Y' => '已审核',
        'E' => '结束',
        'N' => '退回',
        'H' => '办理完成',
        'U' => '待办',
        'R' => '备案中'
    );

    public static $STATUS_CSS = array(
        "T" => "unSubmit",    // 未提交 or 待提交  blue
        "P" => "underApproval",    // 审核中 green
        "Y" => "approved",     // 已审核  orange
        "N" => "returnBack",    // 未批准 or 退回  yellow
        "E" => "finished",    // 结束 or 已完成  red
        "H" => "underApproval",    // 办理完成
        "U" => "finished",    // 待办
        "R" => "approved"    // 备案中
    );

    public static $FORM_TYPE = array(
        // 'update_approver' => 'update_approver'
    );
}
