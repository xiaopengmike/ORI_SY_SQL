-- SELECT * from crs_report where repname like '%项目0号取号%' repno='CRM05'
SELECT * from crs_report where repno like '%crm%' id = 1005
select * from crs_workflow  where repid = 220 and label like '%备案-%'
select * from crs_workflow  where is_link ='' and repid = 224
select * from crs_columnindex where tableid = 220
select * from crs_tableindex where repid = 220   20191  20199  20207 -- col28456 = '1131-5140100'
select * from crs_tableindex where repid = 221
select * from crs_tabledata2323 where col28456 = ' 1131-5140100' title like '%LMP01%'  -- 白云814  -- APPLUS NORCONTROL COLOMBIA LTD  lxdj2020061602/MPD
select * from crs_tabledata772 where writetime like '%2019-04-25 14:12:03%' or  writetime like '%2020-03-27 17:26:10%'  -- 2020-11-15 21:35:13   2020-03-27 17:26:10
select * from user where user_id = 'INHEGRID-0013'
select * from crs_tabledata696 where writetime like '%2020-12-10 13:57:52%';
select * from crs_tabledata786 where writetime like '%2021-02-05 08:45:53%';

select * from crs_tabledata786 where col13543= '' and id > 228
where title like '%ENEO CAMEROON S.A./<CF>2011051718 & <CF>2011051721 & <CF>2010049300 & <CF>2010049296 & <CF>2010049303  & <CF>2010049375 & <CF>2010049372 & <CF>2010048782
/CMN02/合同评审%'
select * from crs_tabledata696 where col12862 like '%INHESC2021012101%' 

show tables like '%crs_%'
select * from crs_autocode where curvalue >0 and last_date > '2010'
-- select * from crscell.crs_tabledata1530 where col20191 like '%Libango%' or col20199 like '%Libango%' or col20207 like '%Libango%'
-- select * from crscell.crs_tabledata686 where col12787 like '%Libango%' or col12794 like '%Libango%' or col20316 like '%Libango%'
-- or writetime like '%2020-10-16 16:01:47%'  -- Ascentch Service Co. W.L.L
-- select * from crscell.crs_tabledata778  -- 不同意。报价有效期已过，美元汇率和原材料上涨因素导致该价格无法覆盖成本。 谌冬辉2020-12-02 10:25:23
desc crs_tabledata703_bak
select col13017,count(col13017) tt from crs_tabledata719 group by col13017 order by tt desc like '%Safvolt Switchgears Private Limited%'    lxdj2020100903/ALL
select * from crs_tabledata1477 where col19433 != '' GROUP BY writetime like '%Safvolt Switchgears Private Limited%'
select * from crs_tabledata2260 where col13393 like '%REGIE DE PRODUCTION ET DE DISTRIBUTION D“EAU ET D“ELECTRICITE%'   --  Meter Mate Prepaid Electric Meters cc    Meter Mate Metering (Pty)Ltd  Meter Mate Metering (Pty)Ltd
select writetime,title,count(writetime) tt from crs_tabledata746   group by writetime order by tt desc
-- insert into crs_tabledata719_bak
select * from crs_tabledata719 where writetime like '%2021-01-08 16:20:53%'   广东启明电力工程有限公司
select * from crs_tabledata1533 where title like '%TQA%'   广东启明电力工程有限公司
select * from crs_tabledata3163 where title like '%N008%' or title like '%N004%' writetime = '2020-11-10 13:53:08'
select * from crs_tabledata699 where writetime = '2020-11-13 14:01:53' title like '%N004%' 
-- 不同意。（1）塑壳断路器没有165A的规格；请客户中心明确。（2）塑壳断路器是否一定要按技术规格指定品牌进行外购？限定品牌将影响到采购价格洽谈。（3） JAC01项目2018年曾经提供过产品，本次是否要求与上次供货产品兼容？EOI项目库存产品之间存在偏差。
 where title like '%ALP02%'
where title = 'lxdj2020101001/MJT'
-- KP1/9A.3/OT/34/19-20   KP1/9A.3/OT/34/19-20 
 where id>=2236 title like '%Sivli Sdn Bhd%'  -- LIBANGO HOLDINGS, INC.
-- {BDCEE0E9E9A34A6585220E2C121AA8BBD4CC22B6C7B643389146528155CECD45}_BFN11 80%尾款发票.pdf
-- {BDCEE0E9E9A34A6585220E2C121AA8BBD4CC22B6C7B643389146528155CECD45}_BFN11 80%尾款发票.pdf
-- SELECT * from crs_tableindex where repid='738'
select * from crs_quick_reportstate_bak where writetime='2020-08-21 10:32:08' ;
select * from crs_reportstate_bak where writetime='2020-10-17 16:41:30' ;
INSERT INTO crs_quick_reportstate_bak 
select * from crs_quick_reportstate where writetime='2021-01-21 13:58:04' or writetime = '2021-01-14 09:02:54' order by writetime,id  -- 2020-12-02 10:29:17
INSERT INTO crs_reportstate_bak 
select * from crs_reportstate where writetime='2021-01-21 13:58:04' or writetime = '2021-01-14 09:02:54' order by writetime,id  -- 2020-12-02 10:25:33

select * from crs_quick_reportstate where writer='INHE-0036' order by id desc limit 0,10
select * from crs_reportstate order by id desc limit 0,10
select * from crs_quick_reportstate where workflow like '%申请人%'
select * from crscell.crs_reportstate where repid='778' and writetime='2020-11-23 14:23:13' and (endtime='' or endtime is null) and (writetimelist<>'' and writetimelist is not null) order by id desc limit 0, 1
and userid='' 
-- select * from crs_quick_reportstate where writetime2 like '2020-06-18%' and writer = 'meterw' ;  -- 842

1949  -- 鲁慧珍2020-09-11 16:01:05
show tables like '%code%'
select * from crs_autocode where id = 80
select * from crs_codeindex 
select * from crs_codeitem 

-- select * from crs_quick_reportstate_bak WHERE writetime='2020-09-08 17:30:57'
-- SELECT * from crs_reportstate_bak WHERE writetime='2020-09-08 17:30:57'

-- SELECT * from crs_tabledata719  where writetime='2019-06-21 14:06:17'  #主表
-- SELECT * from crs_tabledata717 where title='sctz2016092101/BY06/生产通知单'
-- SELECT * from crs_tabledata718 where title='sctz2016092101/BY06/生产通知单'
-- SELECT * from crs_tabledata720 where title='sctz2016092101/BY06/生产通知单'
-- SELECT * from crs_tabledata721 where title='sctz2016092101/BY06/生产通知单'
2020-10-12 09:08:25  2020-10-10 15:57:41
-- select * from crs_quick_reportstate WHERE writetime='2020-09-08 17:30:57' #or writetime='2018-09-28 10:27:08' #######删这里，用 writetime 查
94698	221	INHE-0576	2020-10-10 09:31:14	商务组审核	INHE-0051				INHE-0576	2020-10-10 10:19:24	79502			
94640	420	INHE-0721	2020-10-09 11:41:18	项目经理审核	INHE-0654	2020-10-09 17:38:49			INHE-0721	2020-10-09 17:07:04	79464			
-- SELECT * from crs_reportstate WHERE writetime='2020-09-08 17:30:57' or writetime='2018-09-28 10:27:08' #######删这里，用 writetime 查
79502	221	75	INHE-0576	2020-10-10 09:31:14	商务组审核	待办	0000-00-00 00:00:00	166	INHE-0051				75	INHE-0576	2020-10-10 10:19:24			{4B5DE2F2-C45F-FE92-FF8E-9EEEBA270976}				
2020-10-10 10:19:24
79464	420	175	INHE-0721	2020-10-09 11:41:18	项目经理审核	待办	0000-00-00 00:00:00	175	INHE-0654	2020-10-09 17:38:49			175	INHE-0721	2020-10-09 17:07:04			{1F1ABD7E-8660-77E5-597D-982A96EF792C}				

-- crm 
-- update crs_tabledata719 set col13022 = '第三方检验' where col13022 = '01'
-- update crs_tabledata719 set col13022 = '' where col13022 = '02'

-- 70%预付款已到账，可以下单。
-- 付款方式:合同金额的70%在签订合同10天内作为预付款T/T支付，30%余款在发货前T/T支付		
-- 当前已收到70%预付款，可以下单。
show tables like '%report%'
select * from crs_workflow where repid = 420 and label like '%董事长%'
select * from crs_workflow where repid = 420 and id = 1838
select * from crs_detailreadpriv where reportid = 420  -- 0381  0578
select * from crs_detailreadpriv where roleid = 'INHE-0381.'  -- 张柳芳（法语业务）
select * from crs_detailreadpriv where roleid = 'INHE-0578.'  -- 覃湘君（英语业务）
--
select * from crscell.crs_tableindex where repid = 420
select * from crscell.crs_tableindex where repid = 219
select * from crscell.crs_tabledata3303 where title like '%GAB%'
select * from crscell.crs_tabledata785 where title like '%Groupement%'


select * from crscell.crs_tableindex where repid = 218
select * from crscell.crs_tabledata686 where title like '%Globle Merchants%'  -- 陈旭鹏(Torres)
select * from user where user_name like '%易双荣%'

1704	INHE-0900	易双荣	y*s*r*	INHE-0900INHE	0	0	$1$rT4.YN1.$e63OQDKAlURZ0ZqsXSNQS1			65	115	IT工程师	0		17		0	1994-03-14	0												0	1	2020-03-12 17:23:16	0	1	2019-09-02 08:58:45	1	3,4,147,8,9,16,130,5,131,132,182,183,15,76,		1	9008100	1	0	0	ALL	ALL		10	0	0	0	0			192.168.4.119	0	0	0					0		1	0000-00-00 00:00:00	0	a:1:{s:11:"login_first";i:4;}	0	INHE-0900		INHE,DEMO	

select * from crscell.crs_tableindex where repid = 220
select * from crscell.crs_tabledata696 where title like '%ADC-5%'


show variables like 'log_error'
show variables like 'general_log_file'
show variables like 'slow_query_log_file'

