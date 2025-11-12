- 220 销售合同评审表
SELECT
	a.id,
	a.writetime,
	col12869 '合同日期',
	col12860 '客户名称',
	col12861 '合同相对方',
	col12862 '销售合同编号',
	col12863 '项目名称',
	col12864 '项目编号',
	col12865 '合同总金额',
	col19264 '价格术语',
	col19265 '发货条件',
	col19270 '结算方式（信用政策）',
	b.`name` '销售模式',
	c.`name` '合同币种'
FROM
	crs_tabledata696 a
LEFT JOIN crs_codeitem b ON a.col19449 = b. CODE
AND b.codeid = '61'
LEFT JOIN crs_codeitem c ON a.col12891 = c. CODE
AND c.codeid = '39'
WHERE a.id >= '830'
order by id

select * from crscell.crs_tableindex where id = 696 
SELECT * from crs_report where id = 220  -- 销售合同评审表  CRM03
-- 


SELECT
	a.id,
-- 	a.writetime,
	col36763 '合同日期',
	col36754 '客户名称',
	col36755 '合同相对方',
	col36756 '销售合同编号',
	col36757 '项目名称',
	col36758 '项目编号',
	col36759 '合同总金额',
	col36786 '发货条件',
	col36791 '结算方式（信用政策）',
	b.`name` '销售模式',
	c.`name` '合同币种'
FROM
	crs_tabledata3165 a
LEFT JOIN crs_codeitem b ON a.col36796 = b. CODE
AND b.codeid = '61'
LEFT JOIN crs_codeitem c ON a.col36784 = c. CODE
AND c.codeid = '39'
WHERE a.id >= '5'
order by id

select * from crscell.crs_tableindex where id = 3165 
SELECT * from crs_report where id = 772 -- 销售合同评审表(Inhegrid)(InheGrid)  CRM03_Inhegrid_cn
select * from crs_tabledata3165 where title like '%200-HC-20714号%'
select * from crscell.crs_tableindex where repid = 772  

---

SELECT
	a.id,
a.writetime,
	col27791 '合同日期',
	col27782 '客户名称',
	col27783 '合同相对方',
	col27784 '销售合同编号',
	col27785 '项目名称',
	col27786 '项目编号',
	col27787 '合同总金额',
	col27813 '价格术语',
	col27819 '发货条件',
	col27814 '结算方式（信用政策）',
	b.`name` '销售模式',
	c.`name` '合同币种'
FROM
	crs_tabledata2251 a
LEFT JOIN crs_codeitem b ON a.col27821 = b. CODE
AND b.codeid = '61'
LEFT JOIN crs_codeitem c ON a.col27812 = c. CODE
AND c.codeid = '39'
WHERE a.id >= '19'
order by id

select * from crscell.crs_tableindex where id = 2251 
SELECT * from crs_report where id = 569  -- 销售合同评审表(Inhegrid)  CRM03_Inhegrid
select * from crs_tabledata2251 where title like '%IHGISC20081101%'
select * from crscell.crs_tableindex where repid = 569

---

SELECT
	a.id,
a.writetime,
	col33382 '合同日期',
	col33373 '客户名称',
	col33374 '合同相对方',
	col33375 '销售合同编号',
	col33378 '合同总金额',
	b.`name` '合同类型',
	c.`name` '合同币种'
FROM
	crs_tabledata2789 a
LEFT JOIN crs_codeitem b ON a.col33426 = b. CODE
AND b.codeid = '86'
LEFT JOIN crs_codeitem c ON a.col33403 = c. CODE
AND c.codeid = '39'
-- WHERE a.id >= '19'
order by id

select * from crscell.crs_tableindex where id = 2789 
SELECT * from crs_report where id = 691   -- 销售合同评审表(Inhegrid)(NERGY)  CRM03_Inhegrid_NERGY
select * from crs_tabledata2789 where title like '%NGCN00472011021%'
select * from crscell.crs_tableindex where repid = 691

select * from crs_codeitem where codeid = 39