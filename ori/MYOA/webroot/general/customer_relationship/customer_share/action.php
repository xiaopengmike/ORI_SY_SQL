<?php

include_once("ActionModel.php");  // 引用数据库操作类
include_once("../common/DataModel.php");  // 公共类

/**
 * 增删改操作更细数据方法
 * @param $action 用于判断当前操作
 * $action : add  modify  delete  
 * crud: Create  Retrieve  Update  Delete
 * 使用多态调用函数，避免使用switch和if else
 */

abstract class Action
{
    abstract function operation();
}

class Retrieve extends Action
{
    private function queryData()
    {
        $model = new DataModel();
        $result = $model->queryData();

        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])){
            echo json_encode($model->array_iconv($result));
            exit;
        }else{
            echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
            include_once("inc/auth.inc.php");
            echo '<link rel="stylesheet" type="text/css" href="/theme/1/style.css">';
            $msg=$_REQUEST['id']?'修改':'添加';
            if($result['code']==200){
                Message("", $msg."成功");
                Button_Close();
                echo '<script language=JavaScript> self.opener.location.reload(); </script>';
                exit;
            }else{
                Message("错误", $msg."失败");
                Button_Back();
                exit;
            }
        }
        exit;
    }

    public function operation()
    {
        $this->queryData();
    }
}

class Add extends Action
{
    private function addData()
    {
        $model = new ActionModel();
        $result = $model->add();

        echo json_encode($model->array_iconv($result));
        exit;
    }

    public function operation()
    {
        $this->addData();
    }
}

class Submit extends Action
{
    private function submitData()
    {
        $model = new ActionModel();
        $result = $model->submit();

        echo json_encode($model->array_iconv($result));
        exit;
    }

    public function operation()
    {
        $this->submitData();
    }
}

class Save extends Action
{
    private function saveData()
    {
        $model = new ActionModel();
        $result = $model->save();

        echo json_encode($model->array_iconv($result));
        exit;
    }

    public function operation()
    {
        $this->saveData();
    }
}

class Modify extends Action
{
    private function updateData()
    {
        $model = new ActionModel();
        $result = $model->update();

        echo json_encode($model->array_iconv($result));
        exit;
    }

    public function operation()
    {
        $this->updateData();
    }
}

class Change extends Action
{
    private function changeData()
    {
        $model = new ActionModel();
        $result = $model->change();

        echo json_encode($model->array_iconv($result));
        exit;
    }

    public function operation()
    {
        $this->changeData();
    }
}

class ChangeSave extends Action
{
    private function changeSaveData()
    {
        $model = new ActionModel();
        $result = $model->changeSave();

        echo json_encode($model->array_iconv($result));
        exit;
    }

    public function operation()
    {
        $this->changeSaveData();
    }
}

class Delete extends Action
{
    private function deleteData()
    {
        $model = new ActionModel();
        $result = $model->delete();

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
        $result = $model->updateApproveOpinion();

        echo json_encode($model->array_iconv($result));
        exit;
    }

    public function operation()
    {
        $this->approveData();
    }
}

class Back extends Action
{
    private function backFlow()
    {
        $model = new ActionModel();
        $result = $model->updateApproveOpinion();

        echo json_encode($model->array_iconv($result));
        exit;
    }

    public function operation()
    {
        $this->backFlow();
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
