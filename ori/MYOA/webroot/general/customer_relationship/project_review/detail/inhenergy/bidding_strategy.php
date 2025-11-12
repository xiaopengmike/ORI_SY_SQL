<?if($main[is_bidding_project]==01):?>
<table id="tab3" class="is_bidding_project" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" colspan="8" style="color: red;text-align:center">（三） 竞投策略【投标项目必填】</td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">1.标号（Tender No.）</td>
        <td class="TableData" width="35%">
            <input type="text" name="tender_no" id="tender_no" class="layui-input" value="<?= $bidding['tender_no'] ?>" />
        </td>
        <td class="TableContent" width="15%">2.投标截止时间</td>
        <td class="TableData">
            <input type="text" name="end_date" id="end_date" class="layui-input" value="<?= $bidding['end_date'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">3.项目名称（中外文）</td>
        <td class="TableData" colspan="3">
            <input type="text" name="project_name" id="project_name" class="layui-input" value="<?= $bidding['project_name'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">4. 标书/合同主要商务条款：价格条款，付款主体，付款条件，交期，质保和违约条款（付款条件和交期必须填写）；若代理商直接投标，必须注明代理商对银河的付款方式
        </td>
        <td class="TableData">
            <textarea class="layui-textarea" id="tender" name="tender"><?= $bidding['tender'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="tender_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">5.评标规则（评标流程，打分规则和实质不符项）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="bid_rule" name="bid_rule"><?= $bidding['bid_rule'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="bid_rule_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">6.中标规则（几家中标，一家几个LOT，一家制造商可授权几个投标人）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="biding_rule" name="biding_rule"><?= $bidding['biding_rule'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="biding_rule_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">7.投标/履约/质保保函要求(开立方，金额，货币，有效期，开立行资质等)</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="effective_demand" name="effective_demand"><?= $bidding['effective_demand'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="effective_demand_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">8.投标资格要求（公司年限，财务数据，供货记录，资质证书，测试报告）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="tender_demand" name="tender_demand"><?= $bidding['tender_demand'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="tender_demand_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">9.投标资料要求（需要业务部以外部门或机构协助的资料；需要提交的最迟时间）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="file_demand" name="file_demand"><?= $bidding['file_demand'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="file_demand_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">10.参与方式（银河投标/代理商投标/联合投标；代理直采/银河直供）</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="format" name="format"><?= $bidding['format'] ?></textarea>
            <!-- <input type="text" name="format" id="format" class="layui-input" value="<?= $bidding['format'] ?>" /> -->
        </td>
    </tr>
    <tr>
        <td class="TableContent">11. 如采用联合投标，必须说明：1）采取联合投标的原因。
            2）谁主投，谁是联合体成员，为什么？3） 联合体各方大致的权责。</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="reason" name="reason"><?= $bidding['reason'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">12.招标方/业主采购决策过程说明（政府/政治对决策有多大影响）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="process_description" name="process_description"><?= $bidding['process_description'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="process_description_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">13.招标方/业主付款流程</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="pay_process" name="pay_process"><?= $bidding['pay_process'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="pay_process_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">14.招标方/业主支付能力（电力公司剔除买电或发电支出，包括物资和工程常规采购每月/年支出多少）；电力公司/代理商上年财报（能调动多少钱）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="pay_energy" name="pay_energy"><?= $bidding['pay_energy'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="pay_energy_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">15.合作伙伴竞争力分析（电力公司客户关系，代理主战团队经验/能力，老板年龄/性格/能力，公司经济和政治实力/经验；代理公司内部团队关系是否融洽/内斗严重）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="partner_analysis" name="partner_analysis"><?= $bidding['partner_analysis'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="partner_analysis_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">16.银河客户关系（一线/项目负责人，业主关系层次和深度）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="relationship" name="relationship"><?= $bidding['relationship'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="relationship_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">17.市场竞争分析（对手，对手的合作伙伴以及后台是谁，什么产品和价格，和电力公司是怎样的关系，市场地位等）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="market_analysis" name="market_analysis"><?= $bidding['market_analysis'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="market_analysis_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">18.竞争策略和优劣势分析（银河在此项目的状态和情势，为什么做，凭什么做，如何做？）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="strategy_analysis" name="strategy_analysis"><?= $bidding['strategy_analysis'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="strategy_analysis_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">19.项目成功关键和需要的支持</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="key_to_success" name="key_to_success"><?= $bidding['key_to_success'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">20.项目可行性分析会议纪要（铁三角）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="feasibility_analysis" name="feasibility_analysis"><?= $bidding['feasibility_analysis'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="feasibility_analysis_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <?if($isChange):?>
        <tr>
            <td class="TableHeader" colspan="8" style="color: red;text-align:center">变更说明</td>
        </tr>
        <tr>
            <td class="TableContent" width="15%">变更说明<br />
            <span style="color: red;text-align:center">（请详细描述变更内容）<span></td>
            <td class="TableData" colspan="3">
                <textarea class="layui-textarea" id="remark" name="remark"><?= $main['remark'] ?></textarea>
            </td>
        </tr>
    <?endif;?>
    <tr>
        <td class="TableContent" rowspan="3">个人意见</td>
        <td colspan="3">
            <table id="tab31" width="100%">
                <tr>
                    <td colspan="6" class="TableData"><textarea class="layui-textarea" id="personal_opinion" name="personal_opinion"><?= $bidding['personal_opinion'] ?></textarea></td>
                </tr>
                <tr style="text-align: center;">
                    <td class="TableContent" width="15%">项目星级</td>
                    <td class="TableData" width="15%">
                        <div class="layui-input-inline">
                            <b>
                                <font style="color: red;font-size:20px"><?= $psArr[$bidding['project_level']]['paras_desc'] ?></font>
                            </b>
                        </div>
                    </td>
                    <td class="TableContent" width="15%">星级图标</td>
                    <td class="TableData" width="15%">
                        <div id="star_icon">
                            <font style="color: red;font-size:20px"><?= $psArr[$bidding['project_level']]['extra'] ?></font>
                        </div>
                    </td>
                    <td class="TableContent" width="15%">是否需经过项目经理：</td>
                    <td class="TableData" align="left">
                        <!-- <input type="text" name="have_manager" id="have_manager" class="layui-input" /> -->
                        <!-- <input type="radio" name="have_manager" value="01" title="是" <?php if ($bidding['have_manager'] == '01') : ?>checked<?php endif; ?>>
                        <input type="radio" name="have_manager" value="02" title="否" <?php if ($bidding['have_manager'] == '02') : ?>checked<?php endif; ?>> -->
                        <h2>
                            <b>
                                <font style="color: red;"><?= $yes_or_no[$bidding['have_manager']] ?></font>
                            </b>
                        </h2>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="TableData" style="text-align: left;">
                        <?= $main['createUserName'] . ' ' . $main['write_time'] ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?else:?>
<table id="tab31" width="100%" style="text-align: left;">
<?if($isChange):?>
        <tr>
            <td class="TableHeader" colspan="8" style="color: red;text-align:center">变更说明</td>
        </tr>
        <tr>
            <td class="TableContent" width="15%">变更说明<br />
            <span style="color: red;text-align:center">（请详细描述变更内容）<span></td>
            <td class="TableData" colspan="3">
                <textarea class="layui-textarea" id="remark" name="remark"><?= $main['remark'] ?></textarea>
            </td>
        </tr>
    <?endif;?>
<tr>
        <td class="TableContent" rowspan="3" width="15%">个人意见</td>
        <td colspan="3">
            <table id="tab31" width="100%">
                <tr>
                    <td colspan="6" class="TableData"><textarea class="layui-textarea" id="personal_opinion" name="personal_opinion"><?= $bidding['personal_opinion'] ?></textarea></td>
                </tr>
                <tr style="text-align: center;">
                    <td class="TableContent" width="15%">项目星级</td>
                    <td class="TableData" width="15%">
                        <div class="layui-input-inline">
                            <b>
                                <font style="color: red;font-size:20px"><?= $psArr[$bidding['project_level']]['paras_desc'] ?></font>
                            </b>
                        </div>
                    </td>
                    <td class="TableContent" width="15%">星级图标</td>
                    <td class="TableData" width="15%">
                        <div id="star_icon">
                            <font style="color: red;font-size:20px"><?= $psArr[$bidding['project_level']]['extra'] ?></font>
                        </div>
                    </td>
                    <!-- <td class="TableContent" width="15%">是否需经过项目经理：</td>
                    <td class="TableData" align="left">

                        <h2>
                            <b>
                                <font style="color: red;"><?= $yes_or_no[$bidding['have_manager']] ?></font>
                            </b>
                        </h2>
                    </td> -->
                </tr>
                <tr>
                    <td colspan="6" class="TableData" style="text-align: left;">
                        <?= $main['createUserName'] . ' ' . $main['write_time'] ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?endif;?>