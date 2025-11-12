function check_product_manage()
{
	var PRO_NAME = $("input[name=PRO_NAME]").val();
	if(PRO_NAME=='')
	{
		alert('办公用品名称不能为空');
		return false;
	}
	var OFFICE_DEPOSITORY = $("select[name=OFFICE_DEPOSITORY]").val();
	if(OFFICE_DEPOSITORY=='')
	{
		alert('请选择办公用品名库');
		return false;
	}
	var OFFICE_TYPE2 = $("select[name=OFFICE_TYPE2]").val();
	if(OFFICE_TYPE2=='')
	{
		alert('请选择办公用品物品类型');
		return false;
	}
	var OFFICE_PROTYPE = $("select[name=OFFICE_PROTYPE]").val();
	if(OFFICE_PROTYPE=='')
	{
		alert('请选择办公用品类别');
		return false;
	}
	var PRO_CODE = $("input[name=PRO_CODE]").val();
	if(PRO_CODE=='')
	{
		alert('办公用品编码不能为空');
		return false;
	}
	var PRO_STOCK = $("input[name=PRO_STOCK]").val();
	if(PRO_STOCK=='')
	{
		alert('当前库存不能为空');
		return false;
	}
	$('#check_product').submit();
}