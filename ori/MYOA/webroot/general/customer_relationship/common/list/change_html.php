<?php if (count($changeData) >= 1) : ?>
<br/>
<table class="TableBlock" width="100%" align="center">
    <tr align="center">
        <td colspan="4" class="TableContent">变更记录</td>
    </tr>
    <tr align="center">
        <td class="TableContent" width="10%">版本</td>
        <td class="TableContent" width="15%">详情</td>
        <td class="TableContent">变更说明</td>
    </tr>
    <?php $line = 0;
    foreach ($changeData as $fk => $fv) :
    ?>
        <tr align="center">
            <td class="<?= $id==$fv['form_id']?'TableData':'TableContent'?>" >
                <?= $line==0?'原版本':'第'.$line.'次变更'?>
            </td>
            <td class="TableData">
            <a href="javascript:;" onclick="openFullModel('detail.php?id=<?=$fv['form_id']?>&noChange=<?= $line==0?1:''?>');"><?=$fv['form_id']?></a>
            </td>
            <td class="TableContent" align="left" ><?= $fv['change_explain'] ?></td>
        </tr>
    <?php $line++;endforeach; ?>
</table>
<?php endif; ?>