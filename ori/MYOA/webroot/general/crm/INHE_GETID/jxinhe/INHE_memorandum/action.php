<?php

include_once("ActionModel.php");  // 引用数据库操作类

/**
 * 增删改操作更细数据方法
 * @param $action 用于判断当前操作
 * $action : add  modify  delete  
 * 使用多态调用函数，避免使用switch和if else
 */

// $model = new ActionModel();
// $paras = $model->getParamToArray();
// var_export($id);exit;

abstract class Action
{
    abstract function operation();
}
class Add extends Action
{
    private function addData()
    {
        $model = new ActionModel();
        $result = $model->add();
        $result = array(
            'status' => 'success',
            'msg' => '成功'
        );

        echo json_encode($model->array_iconv($result));
        exit;
    }

    public function operation()
    {
        $this->addData();
    }
}

class Modify extends Action
{
    private function updateData()
    {
        $model = new ActionModel();
        $result = $model->update();
        $result = array(
            'status' => 'success',
            'msg' => '成功'
        );

        echo json_encode($model->array_iconv($result));
        exit;
    }

    public function operation()
    {
        $this->updateData();
    }
}

class Delete extends Action
{
    private function deleteData()
    {
        $model = new ActionModel();
        $result = $model->delete();
        $result = array(
            'status' => 'success',
            'msg' => '成功'
        );

        echo json_encode($model->array_iconv($result));
        exit;
    }

    public function operation()
    {
        $this->deleteData();
    }
}

class Approve extends Action
{
    private function approveData()
    {
        $model = new ActionModel();
        $result = $model->approve();
        $result = array(
            'status' => 'success',
            'msg' => '成功'
        );

        echo json_encode($model->array_iconv($result));
        exit;
    }

    public function operation()
    {
        $this->approveData();
    }
}

class Operation
{
    public function operateData(Action $actionObj)
    {
        //添加父类类型限制传参类型,使其满足多态第三点要求，父类指向子类
        $actionObj->operation();
    }
}

//调用
$obj = new Operation();
$obj->operateData(new $action());
