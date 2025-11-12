<table id="tab1" width="100%" style="text-align: center;">
    <tr>
        <td class="TableHeader" colspan="8" style="color: red;">（一）客户信息</td>
    </tr>
    <tr>
        <td class="TableHeader" colspan="8" style="text-align:left">1. 代理商/经销商基本信息</td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">代理商/经销商名称</td>
        <td class="TableData" width="18%" align="left">
            <div class="layui-input-inline">
            <?if($changeStar!=1):?>
                <select id="name1" name="name1" lay-search lay-filter="customer_name" title="1">
                    <option value=''>请选择</option>
                    <option value="<?= $customerData['name1'] ?>" selected><?= $customerData['customer_name1'] ?></option>
                    <?php foreach ($customerSelect as $cusId => $cusName) : ?>
                        <option value="<?= $cusId ?>" <?php if ($cusId == $customerData['name1']) : ?>selected<?php endif; ?>><?= $cusName ?></option>
                    <?php endforeach; ?>
                </select>
                <?else:?>
                    <select id="name1" name="name1" lay-search lay-filter="customer_name" title="1">
                        <option value="<?= $customerData['name1'] ?>" ><?= $customerData['customer_name1'] ?></option>
                    </select>
                <?endif;?>
            </div>
        </td>
        <td class=" TableContent" width="15%">客户编号</td>
        <td class="TableData" width="18%">
            <input type="text" name="customer_id1" id="customer_id1" class="layui-input" value="<?= $customerData['customer_id1'] ?>" />
        </td>
        <td class=" TableContent" width="15%">代理商/经销商所在国</td>
        <td class="TableData">
            <input type="text" name="country1" id="country1" class="layui-input" value="<?= $customerData['country1'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">联系人</td>
        <td class="TableData">
            <input type="text" name="contact1" id="contact1" class="layui-input" value="<?= $customerData['contact1'] ?>" />
        </td>
        <td class=" TableContent">联系人电话</td>
        <td class="TableData">
            <input type="text" name="phone1" id="phone1" class="layui-input" value="<?= $customerData['phone1'] ?>" />
        </td>
        <td class=" TableContent">联系人电邮</td>
        <td class="TableData">
            <input type="text" name="email1" id="email1" class="layui-input" value="<?= $customerData['email1'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">代理商/经销商简介（主营业务范围，销售收入，公司规模/人数，销售业绩，业务负责人/老板能力=家世/学历/工作经历/性格/做事风格）</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="introduction1" name="introduction1"><?= $customerData['introduction1'] ?></textarea>
        </td>
        <td class=" TableContent">添加附件：</td>
        <td class="TableData" style="text-align: left;">
            <input id="introduction1_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">合作历史 （说明是首次合作还是老代理/经销商。若是首次合作，必须有展示代理实力的相关信息；若是老代理/经销商，必须注明合作过的项目和成功的项目。）</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="cooperate_history" name="cooperate_history" <?if($changeStar!=1):?>lay-verify="required"<?endif?> ><?= $project['cooperate_history'] ?></textarea>
        </td>
        <td class=" TableContent">添加附件：</td>
        <td class="TableData" style="text-align: left;">
            <input id="cooperate_history_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
    </tr>
    <tr>
        <td class="TableHeader" colspan="8" style="text-align:left">2. 招标方/业主基本信息 （投标项目必填）</td>
    </tr>
    <tr>
        <td class="TableContent">招标方/业主名称</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline">
            <?if($changeStar!=1):?>
                <select id="name2" name="name2" lay-search lay-filter="customer_name" title="2">
                    <option value=''>请选择</option>
                    <option value="<?= $customerData['name2'] ?>" selected><?= $customerData['customer_name2'] ?></option>
                    <?php foreach ($customerSelect as $cusId => $cusName) : ?>
                        <option value="<?= $cusId ?>" <?php if ($cusId == $customerData['name2']) : ?>selected<?php endif; ?>><?= $cusName ?></option>
                    <?php endforeach; ?>
                </select>
                <?else:?>
                    <select id="name2" name="name2" lay-search lay-filter="customer_name" title="2">
                        <option value="<?= $customerData['name2'] ?>" ><?= $customerData['customer_name2'] ?></option>
                    </select>
                <?endif;?>
            </div>
        </td>
        <td class=" TableContent">客户编号</td>
        <td class="TableData">
            <input type="text" name="customer_id2" id="customer_id2" class="layui-input" value="<?= $customerData['customer_id2'] ?>" />
        </td>
        <td class=" TableContent">招标方/业主所在国</td>
        <td class="TableData">
            <input type="text" name="country2" id="country2" class="layui-input" value="<?= $customerData['country2'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">联系人</td>
        <td class="TableData">
            <input type="text" name="contact2" id="contact2" class="layui-input" value="<?= $customerData['contact2'] ?>" />
        </td>
        <td class=" TableContent">联系人电话</td>
        <td class="TableData">
            <input type="text" name="phone2" id="phone2" class="layui-input" value="<?= $customerData['phone2'] ?>" />
        </td>
        <td class=" TableContent">联系人电邮</td>
        <td class="TableData">
            <input type="text" name="email2" id="email2" class="layui-input" value="<?= $customerData['email2'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">招标方/业主简介（总资产和负债金额，上一年度利润和现金流结余金额；直属上级；大致组织架构；前10大股东构成/股价和股数（上市公司）；董事会和高管简介；上一年度同类产品/工程采购额；付款及时性；供应商评价）</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="introduction2" name="introduction2"><?= $customerData['introduction2'] ?></textarea>
        </td>
        <td class=" TableContent">添加附件：</td>
        <td class="TableData" style="text-align: left;">
            <input id="introduction2_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
    </tr>
    <tr>
        <td class="TableHeader" colspan="8" style="text-align:left;color:red">3. 买方信息（必填项，匹配销售合同评审流程的买方）</td>
    </tr>
    <tr>
        <td class="TableContent">买方名称</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline">
            <?if($changeStar!=1):?>
                <select id="name3" name="name3" lay-search lay-filter="customer_name" title="3" lay-verify="required">
                    <option value=''>请选择</option>
                    <option value="<?= $customerData['name3'] ?>" selected><?= $customerData['customer_name3'] ?></option>
                    <?php foreach ($customerSelect as $cusId => $cusName) : ?>
                        <option value="<?= $cusId ?>" <?php if ($cusId == $customerData['name3']) : ?>selected<?php endif; ?>><?= $cusName ?></option>
                    <?php endforeach; ?>
                </select>
                <?else:?>
                    <select id="name3" name="name3" lay-search lay-filter="customer_name" title="3" lay-verify="required">
                        <option value="<?= $customerData['name3'] ?>" ><?= $customerData['customer_name3'] ?></option>
                    </select>
                <?endif;?>
            </div>
        </td>
        <td class=" TableContent">客户编号</td>
        <td class="TableData">
            <input type="text" name="customer_id3" id="customer_id3" class="layui-input" value="<?= $customerData['customer_id3'] ?>" />
        </td>
        <td class=" TableContent">买方所在国</td>
        <td class="TableData">
            <input type="text" name="country3" id="country3" class="layui-input" value="<?= $customerData['country3'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">联系人</td>
        <td class="TableData">
            <input type="text" name="contact3" id="contact3" class="layui-input" value="<?= $customerData['contact3'] ?>" />
        </td>
        <td class=" TableContent">联系人电话</td>
        <td class="TableData">
            <input type="text" name="phone3" id="phone3" class="layui-input" value="<?= $customerData['phone3'] ?>" />
        </td>
        <td class=" TableContent">联系人电邮</td>
        <td class="TableData">
            <input type="text" name="email3" id="email3" class="layui-input" value="<?= $customerData['email3'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">买方简介</td>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="introduction3" name="introduction3"><?= $customerData['introduction3'] ?></textarea>
        </td>
    </tr>
</table>