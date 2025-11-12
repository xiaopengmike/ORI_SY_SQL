-- SELECT * from flow_run WHERE RUN_ID=76563
SELECT * from flow_run WHERE flow_ID=131 and attachment_name like '%汤海%'
-- 
-- SELECT * from flow_data_206 where RUN_ID=76563 or run_id = 50402  or run_id = 50401
SELECT * from flow_data_171 where data_525 is not null or data_525 != ''
select * from user where user_id = 'INHEGRID-0018'
SELECT run_id,data_6158 from flow_data_171 group by data_6158
select a.gh,a.name,a.jg,a.hk,a.sfzdz,b.name hk_name, a.dz from rzdj a
left join hukou b on b.id = a.hklx
left join user u on u.user_byid = a.gh 
where a.user_bu in ('INHE','WITLINK') and u.dept_id !=0
order by u.uid
-- 26835	74578	470974@2012_890067433	海光系统合同 (改） - V.pdf	1	INHE-0622	2020-12-11 17:23:53
select * from inhe_attachment 
select * from flow_run_attach where attachment_name like '%测试费%' run_id = 74578  51632  51762
select * from attachment where attach_name like '%测试费%'   -- 470974	2	2	2012	890067433	海光系统合同 (改） - V.pdf	海光系统合同 (改） - V.pdf	0	0
select * from rzdj where user_bu in ('INHE','WITLINK')
select * from ATTACHMENT where aid like '%468505%'  1924008343.家具订购合同评审版.docx
SELECT * from flow_run_feedback where run_id = 74578   -- aid=468505  2012  1957017484  1924008343
-- SELECT * from flow_run_attach where run_id = 74166
show tables like '%attach%'
SELECT run_id,data_1,data_27,data_31,data_35,data_39,data_43 from flow_data_145 where data_35 like '%付强%'
-- select * from user where user_id = 'lhz'
insert into flow_run_prcs_bak 
select * from user where user_id = 'WL-0008'
SELECT * from flow_run_prcs where  run_id = 76563 or run_id = 50402 or run_id = 50401) and user_id in('INHE-0918','meterw') order by run_id,id
-- insert into flow_run_prcs_bak  SELECT * from flow_run_prcs where  run_id = 75087
-- 421203	73616	2	szyaj	2020-11-18 18:30:59	2020-11-18 18:39:52	4	3	1	0	1	0				,	0	2020-11-18 18:08:23		0000-00-00 00:00:00		98	1	0	0	0	0		
426705	74618	2	szyaj	0000-00-00 00:00:00	0000-00-00 00:00:00	1	4	1	0	1	0				,	0	2020-12-14 11:17:41		0000-00-00 00:00:00		98	1	0	0	0	1		
-- 410965	71681	2	INHE-0036	0000-00-00 00:00:00	0000-00-00 00:00:00	1	3	1	0	1	0				,	0	2020-09-29 09:58:53		0000-00-00 00:00:00		59	1	0	0	0	0		
-- select * from inhe_kpimb where name like '%研发%'

-- select * from inhe_kpimbmx1 where sdh = 1141
select * from department where user_bu is null
select * from department where dept_name like '%仓储部%'
select * from user where user_name like '卢宗昌(Andy Lu)%'  -- 167
select * from user where user_id = 'INHE-0969'  -- 153
select * from department where dept_id = 153
select * from rsyd where gh =  'INHE-0969'
select * from user_priv where func_id_str like '%1071%'  -- 78   103
select * from sys_function where func_name like '%翻译%' -- 1048  1012
select * from interviewmx2 where rzgh = 'INHE-0969'
sele
select * from interview ORDER BY tbsj desc
select * from user where USER_NAME like '%罗蕾%'  -- 张祥英
select * from department where dept_id =171
select * from inhe_system_admin where user_id like '%INHENERGY%'
select * from inhe_hr_flow_type

select * from rsyd where flow_id like 'RY202101%'
0
select * from interviewmx2 where rzgh= 'INHE-0018'   --  2019-12-23
select * from interview where name ='古华锋'
select zdrq from rsyd where rsyj like '%1月%' and zdrq like '%%'
-- 鲁慧珍   2020-09-14 17:00:03   同意Approval 

-- 407132	71063	2	lhz	2020-09-14 17:00:03	2020-09-14 17:00:24	3	9	1	0	1	0				,	0	2020-09-14 16:54:36		0000-00-00 00:00:00		55	1	0	0	0	0		
-- 407134	71063	3	INHE-0051	0000-00-00 00:00:00	0000-00-00 00:00:00	1	6	1	0	9	0	2			,	0	2020-09-14 17:00:24		0000-00-00 00:00:00		166	2	0	0	0	0		
-- data_17 18 341371 鲁慧珍	2020-09-14 17:00:03  同意Approval 

select * from rzdj where gh = 'INHE-0953'  -- 6230947200032373490

select * from user where is_kh = 'T' and dept_id != 0
select * from user where user_name like '%(%)' and user_bu = 'INHE'
select * from user where user_name like '%淦永妍%'
select * from interviewmx2 where rzgh = 'INHE-0953'
select * from rsyd where gh = 'INHEGRID-0070'
select * from user_pr 

select * from sys_function where func_code like '%inter%' -- 1033/1034/1070
select * from user_priv where func_id_str like '%1034%'
select * from user where user_id = 'JXINHE-4046'
select * from interview where flow_id = 'MS20201215001'
select * from inhe_hr_flow_prcs where flow_id = 'MS20201215001'
select * from yijian where flow_id = 'MS20201215001'
-- MS20201215001	3	同意	计然	2020-12-15 09:37:07	1	1	1
select * from hukou
select * from department where dept_name like '%总监%'
select * from inhe_kpimbmx3
select * from inhe_kpikhmx3 where jldh = 12581
select * from kpikh where name like '%邓彧(Daniel)%' and zq = '2020年10月'
select * from inhe_kpigl where user_name like '%孙立中%'
select * from inhe_kpikh where name like '郑秋萍%' and zq = '2020年10月'
select * from user where user_id='INHE-0803'
select * from inhe_hr_flow_prcs where user_id = 'INHE-0800' and from_user = 'INHE-0876' flow_id= 'JK20200928029'
select * from user where user_priv_name like '%项目经理%' and dept_id != 0
select * from department where dept_id = 133  国际业务部
select * from rsyd where flow_id = 'RY20201029001'
select * from inhe_hr_flow_prcs where flow_id = 'RY20201029001'
select * from inhe_hr_flow_prcs where flow_id = 'MS20201106002'
select * from interview where flow_id = 'MS20201106002'
select * from department where dept_id = 133
select * from yijian1 where flow_id = 'RY20201029001'
select * from department where dept_id = 178 
select * from djgz where dj = 'Ⅱ1A'
select * from interviewmx2 where rzgh = 'INHE-0010'

select * from interview where name like '%杨松%'

select * from interviewmx2 where rzgh = 'WL-0048'
select * from inhe_oa_paras  -- 'INHE-0231','WL-0008','INHE-0298','INHE-0853','INHE-0634','INHE-0576'   'meterw','szwy','INHE-0918','INHE-0381','IM-0044','INHE-0800'
select * from inhe_kpikh where shr = 'meterw'

show tables like '%meet%';
select * from meeting where m_room = 5 and M_START like '2020-09-25%';
select * from meeting_room;


-- 758	软件事业部投标模拟预演示	投标模拟内部预演示		INHE-0949	2020-09-24 09:00:09	INHE-0949,INHE-0599,INHE-0938,INHE-0945,INHE-0848,INHE-0903,INHE-0818,	2020-09-25 16:30:00	2020-09-25 17:30:00	5	0	INHE-0238		1	0									0	0			1				0			1	0
select * from interview where flow_id = 'MS20200925001'  -- 人力资源虽再三跟候选人强调按时到岗，但亦不能百分百保证候选人能如期到岗，

select * from rsyd where flow_id = 'RY20200925001'

select * from inhe_hr_flow_prcs where flow_id = 'RY20200925001'


select * from yijian1 where flow_id = 'RY20200925001'

select * from inhe_control_library
select * from inhe_control_library_history
select * from inhe_control_library_temp

select * from sales_performance where cycle = '2020年09月'

select * from inhe_contract_protocol order by serial_number desc;
select * from inhe_sales_contract order by serial_number desc;
select * from inhe_authorization_letter order by id desc;
select * from inhe_guarantee_letter order by id desc;
select * from inhe_system_admin where user_id = 'INHE-0260'
where type = 'contract_protocol' or type='sales_contract' or type ='authorization_letter' or type ='guarantee_letter'
select * from inhe_attachment
select * from user where user_id = 'INHE-0260'

select * from inhe_kpikh where shr = 'INHE-0599' and name like '%田%' and zq = '2020年09月'
select * from user where is_kh = 'T' and dept_id !=0
select * from sys_function where func_code like '%protocol_archive%'
select * from user_priv  where user_priv = 65

 select * from inhe_oa_paras 
 select * from inhe_oa_paras_import where id <=212

select * from department

select * from inhe_sales_contract where manage_dept like '%电网%'

select * from inhe_contract_protocol where manage_dept not in(01,02,03,04,05) order by serial_number desc;
select * from inhe_sales_contract where manage_dept not in(01,02,03,04,05) 
select * from inhe_authorization_letter where manage_dept not in(01,02,03,04,05) 

select * from inhe_kpimb where name = '2020销售经理（山东）'
select * from inhe_kpimbmx1 where dh = 1152

select * from inhe_kpikh where  bm='软件事业部' and zq = '2020年09月' user_id ='INHE-0903' and  name = '卢锦' shr = 'IM-0044' 
select * from inhe_kpikhmx3 where jldh =12533
select * from kpikh where id = 3106
select * from kpigl
select * from inhe_hr_flow_prcs where flow_id = 'YK20200927055'

select * from kpigl k where k.user_id in(
select  a.user_id from kpigl a
left join user u on a.user_id = u.user_id
where u.dept_id = 0 )

select * from rsyd where gh = 'INHE-0685'
select * from interviewmx2 where rzgh = 'INHE-0685'
select * from user where user_id = 'INHE-0029'

select * from user_priv order by flow_id desc 

select * from sys_function

 -- 华北区域总监  MS20200721003 --  销售经理  -- 广东省级销售经理何海鹏  -- 山西省级销售经理冯海斌  安徽省级销售经理梁龙
select * from user where user_name = '姜久明' 
select * from interview where name = '姜久明'
select * from interviewmx2 where flow_id = 'MS20200824001'
select * from rzdj where name = '姜久明'

select * from rzdj where gh='INHE-0962'
select * from department where dept_name = '山东区区域总监' 

select * from user where user_id = 'INHENERGY-0066'

select * from inhe_kpimb where user_bu = 'INHENERGY'
select * from inhe_kpimbmx1 where dh = 1151
select * from inhe_kpimb where 

select * from star_pm where pm_id = 'NCT'
select * from star_pm_user

select * from mail where PM_ID != ''
select * from star_pm 
select * from star_pm_user 

select * from mail where send_time > 1574938646 and send_time < 1575025046
select * from department where dept_name like '%支持总监%'

SELECT * from UNIT
select count(*) from USER where NOT_LOGIN='0' or NOT_MOBILE_LOGIN='0'
select value from crscell.crs_para where name = 'CUR_VER_NO'
select value from crscell.crs_para where name = 'CUR_CRSCELL_VER_NO'
select value from crscell.crs_para where name = 'REGISTER_NAME'
select value from crscell.crs_para where name = 'REGISTER_CODE'

select lifecycle from crscell.crs_workflow where repid = '420'

select * from interview where flow_id like 'MS20201106002%'
select * from inhe_hr_flow_prcs where flow_id like 'MS20201106002'

select * from canzhao
select * from canzhao2
select * from company
select * from mail_body where mail_id = 158980
select * from department where dept_id = 20
select * from user where user_id = 'INHE-0701'   -- 20    0701  0955  0870
select * from inhe_kpikh where zq = '2020年11月' and name like '%徐井敏%'
select * from inhe_hr_flow_prcs where flow_id = 'YK20201127003'

-- select * from rsyd where name like '%陈雁%' order by rq3
select * from rsyd where name like '%何易%';
select * from user where user_id = 'INHE-0797'
select * from inhe_hr_flow_prcs where flow_id = 'MS20201207001'
select * from canzhao2 where id =1
-- select dept_id,dept_name,dept_parent,manager,leader1,USER_BU,is_director from department where is_director = 'Y' dept_id = 178
select * from user where user_name like '%傅秋%'; 
select * from user_priv where func_id_str like '85,%' user_priv = 99
select * from sys_function where func_name like '%会议%'
select * from pingjia where flow_id = 'MS20201202001'
-- select * from sms_body where from_id = 'IM-0015'  and remind_url like '%MS20201202001%'
-- select * from sms where body_id = 1926543
-- select * from mail_body where subject like '%2020年午餐报销明细%'
-- select * from mail_body where mail_id = 156817
-- 948433	156817	0	INHEGRID-0039	1607044800		岑经理：11月考勤统计表请以此份为准，感谢！	468491@2012_396104595,	2020年11月考勤统计表.xls*
-- 948447	156817	0	INHEGRID-0039	1607045837		岑经理：11月考勤统计表请以此份为准，感谢！	468497@2012_64064350,	2020年11月午餐报销明细.xls*	
select * from mail where subject like '%麦格瑞能在巴基斯坦客户的批量产品需求%'  167084  168220  JXINHE-0276  BAL项目执行-商务相关   BAL项目 代理准备资料  RRP和RWS项目信息更新
select * from mail_body where mail_id = 169547  -- ,INHE-0819
select * from user where user_name like '%何剑%'  -- INHE-0819
select * from user where user_id = 'INHE-0029'
select * from interviewmx2 where gs ='' rzgh = 'INHE-0971'
select * from mail_to where mail_id = 169547 -- 933885	150644	INHE-0819	0	8	1568627234	1568857492  1067438	168220	JXINHE-0276	0	2	1607484746	1607571938
select * from mail_fw where mail_id = 169547 -- 149944	150644	INHE-0381	INHE-0819,	1568600534	  183198	168220	syz	JXINHE-0276,	1607477624	

select * from interview where flow_id like '%MS202012%'

select * from rzdj where gh= 'INHE-0848'
select * from user where user_name like '%黄庆%'

select * from inhe_control_library_temp 
insert into inhe_control_library_temp (type,chinese,english,create_user,create_time) values ()
select * from rsyd where flow_id = 'RY20201221001'  
select * from inhe_hr_flow_prcs where flow_id = 'YK20201231024'
select * from inhe_kpikh where name like '%王晶%' and stu = 1

select * from user where user_id = 'INHENERGY-0071'  -- 103,
select * from user_priv where FUNC_ID_STR like '%1042%'
select * from sys_function where func_code like '%kpi%'

select * from interview where name like '%朱芬%'
select * from rsyd where rsyj like '%1月%'
select * from yijian1 where flow_id like 'RY20210113001%'
select * from interview where flow_id = 'MS20210105001' -- meterw
select * from inhe_hr_flow_prcs where flow_id like 'RY20210113001%' -- meterw
select * from yijian where flow_id like 'RY20210113001%' -- meterw

select * from interviewmx1 where flow_id = 'MS20210105001'
select * from interviewmx2 where flow_id = 'MS20210105001'  INHE-0247
select * from sms where body_id = 1965475 limit 0,1
select * from sms_body where content like '%人事异动%' order by body_id desc limit 0,10

select * from meeting
select * from meeting_room
select * from meeting_rule
select * from user where user_name like '%林丽%'  -- 191 INHE-0091

select * from department where dept_name like '%国际二组 INTL.Team%'
select * from user where user_id = 'INHE-0621'
select * from user_0112

select * from user where user_priv_no = 100
select * from user where user_id = 'INHE-0800'
select * from user where user_name like '%李静%'
select * from interview where name like '%陈志辉%'
select * from interview where flow_id like '%MS202012%'

select * from user_priv where PRIV_NO = 113
select * from department where dept_name like '%组%'  -- G

select * from inhe_control_library where type = 'SmartMMR（web)'
SmartMMR(web)

select * from user_priv where func_id
select * from user where user_name like '%叶勇%'
select * from interviewmx2 where rzgh = 'INHEGRID-0039'   INHEGRID-0039  MSJL2011011901  MSJL2019083001
select * from interview  where name like '%傅%'   -- MSJL2019083001
select * from interview a LEFT JOIN interviewmx2 b on a.flow_id=b.flow_id where RZGH='INHEGRID-0039'
select * from interviewmx2 where flow_id = 'MSJL2019083001'

select * from hukou where LX='2'

select c.company, c.id companyId, d.dept_name from rzdj r 
			left join company c on c.user_bu = r.user_bu 
			left join department d on d.dept_id = r.bm 
			where GH='INHEGRID-0039'

select * from user where user_name like '%测试（杨%'

select * from department where dept_id = 156  -- JXINHE-4050
select * from user where user_name like '%林丽璇%'
-- select * from interview where name like '%林丽璇%'
select * from user where user_id = 'INHE-0091'
select m.*,u.dept_id from interviewmx2 m left join user u on u.user_byid= m.rzgh where m.bm_id != u.dept_id
update 
select * from interviewmx2 where flow_id = 'MS20200610001'
select * from interviewmx2 order by rzsj desc
select * from interview where flow_id = 'MS20210115001'
select * from sys_function where func_name like '%测试%'
select * from department where dept_id = 1
select * from  user where user_byid= 'INHE-0362'
select * from  rsyd where name like '%王维%'

select v.name, p.user_priv, p.priv_no, v.ypgw, v.dept_id, v.gs, v.gwlx, v.date, v.sex, d.dept_name from interview v 
            left join user_priv p on v.ypgw = p.priv_name 
            left join department d on v.dept_id = d.dept_id
            where flow_id = 'MS20210115001'

select * from inhe_oa_paras where type like '%kpi%'
select * from user where user_name like '%康维%' INHE-0927  INHE-0773  111

select * from department where dept_name like '%国内市场总监(DMD)%'  -- szyaj,  148
select * from department where dept_name like '%国内业务部(DBD)%'    111  192
select * from department where dept_id = 93   111  192

select * from rsyd where flow_id like '%RY20210127%'

select * from rsyd where name like '%徐紫涵璇%';
select * from rsyd where gh like '%INHE-0900%';
select * from rsyd where flow_id like 'RY202102%'
select * from interviewmx2 where rzgh = 'INHE-0909';