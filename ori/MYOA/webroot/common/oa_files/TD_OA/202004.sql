-- 202004
select * from company
select * from canzhao
select * from canzhao2

select * from inhe_hr_flow_prcs
select * from inhe_hr_flow_type
select * from inhe_hr_prcs_type
-- select * from user where real_name like '%宣翠%'

select * from canzhao2 where ID=4


select * from fujian2 where flow_id = 'RY20200408001'
select * from rsyd where flow_id = 'RY20200408001'
select * from yijian1 where flow_id = 'RY20200408001'

select * from inhe_hr_flow_prcs where flow_id = 'RY20200408001'

select * from department where dept_id = 73
select * from user where user_id = 'WL-0008'

-- update rsyd set stu='1', SHR = 'WL-0008',bz='3' where flow_id = 'RY20200407001'
-- update inhe_hr_flow_prcs set user_id ='WL-0008' where flow_id = 'RY20200407001'

select * from inhe_oa_paras

select company, HR_ID, USER_BU from company where ID='4'
select * from company where ID='4'
select * from user where real_name like '%刘伟%'  -- JXINHE-4040    JXINHE-1753   JXINHE-2860

select c.*,cc.user_bu from canzhao2 c
left join company cc on cc.id= c.id
 where c.id='4' and c.bz='1'

select manager from department d where d.dept_id = (select dept_parent from department where dept_id = '73')
select * from user where user_id = 'INHE-0224'
select * from department where dept_id in('41','68','95','98')
select * from canzhao2 where ID='4' and BZ='3'
1		        行政人事部经理
2		        原部门
3		        用人部门经理
4	IM-0015	  营运总监
5	INHE-0224	总经理
6	meterw	  董事长
select MANAGER, DEPT_ID, DEPT_PARENT, IS_DIRECTOR from department where DEPT_ID='73'

-- 
select real_name from user where user_byid = 'JXINHE-4040';

select * from rzdj where  gh='INHE-0364'
select * from department where dept_id = 83  
select * from interviewmx2 

select * from rzdj where gh='INHE-0900'
select * from jiating where gh='INHE-0900'
select * from inhe_language_skill where gh='INHE-0900'
select * from jiaoyu where gh='INHE-0900'
select * from gongz where gh='INHE-0900'

select * from interviewmx2 where rzgh='INHE-0468'
select * from interviewmx2 where rzgh='WL-0008'
MS20200312013	4	WL-0008		项目经理	73	研发部(RDD)	Ⅱ1C	9620	9620	300	1600		0	2860	0	0		24000	2016-01-25
MS20200312016	4	INHE-0468		软件开发工程师	73	研发部(RDD)	II2B	7440	7440	300	1200		0	120	0	0		16500	2015-12-17
MS20200312016	4	INHE-0468		项目经理	73	研发部(RDD)	Ⅱ1B	10660	10660	300	1600		0	0	0	0	0	23220	2015-12-17

select * from rsyd  where flow_id like 'RY202004080%' and type is null
desc interviewmx2

select * from inhe_system_admin where type='F10'

select * from inhe_contract_registration where user_bu = 'INHEGRID'

select user_name from inhe_contract_registration where user_bu = 'INHEGRID'

select * from inhe_contract_registration where user_bu = 'INHEGRID';
select a.real_name from user a
left join department d on d.dept_id = a.dept_id
 where d.user_bu = 'INHEGRID';

update inhe_contract_registration c
set c.create_user_id='INHEGRID-0039',c.create_user_name='傅秋婵（Autumn Fu）',c.create_time='2020-04-09 10:13:36'
where c.user_bu = 'INHEGRID';

desc inhe_contract_registration

create table inhe_contract_registration_export as 
select * from inhe_contract_registration where user_bu = 'INHEGRID';

select * from user where user_id = 'INHEGRID-0060'


select v.name, p.user_priv, p.priv_no, v.ypgw, v.dept_id, v.gs, v.gwlx, v.date, v.sex from interview v 
            left join user_priv p on v.ypgw = p.priv_name 
            where flow_id = 'MS20200320003'


select c.*,cc.user_bu from canzhao2 c 
	 left join company cc on cc.id= c.id 
	 where c.id='1' and c.bz='1'

select DUTY_ALLOWANCE1 from rsyd where flow_id = 'RY20200409003'
UPDATE rsyd set DUTY_ALLOWANCE1= FORMAT(DUTY_ALLOWANCE1,2)
desc rsyd  

select * from rsyd where flow_id = 'RY20200409003'

select * from interviewmx2 where flow_id = 'RY20200409003'

select c.*,cc.user_bu from canzhao2 c 
left join company cc on cc.ID= c.ID 
where c.ID='1' and c.BZ='1'

select * from canzhao2

select c.* from canzhao2 c 
	left join company cc on cc.id= c.id 
	 where cc.user_bu='INHE' and c.bz='1'


select * from inhe_contract_registration where work_number = 'INHE-0014'

select * from user where USER_byID = 'INHE-0059'

select * from rzdj 

select * from interview
select * from interviewmx2

select a.* from interviewmx2 a left join user u on u.user_byid = a.rzgh where u.user_id = 'INHE-0925'

select u.user_byid,u.real_name,d.dept_name,p.priv_name,d.manager,d.leader1,d.leader2 from user u 
left join department d on u.dept_id = d.dept_id 
left join user_priv p on u.user_priv = p.user_priv
where d.is_director = 'Y'

INHEGRID-0018
select * from interview where user_bu = 'INHEGRID' and flow_id = 'MS20200414001'

select * from yijian where flow_id = 'MS20200414001'

select * from inhe_hr_flow_prcs where flow_id = 'MS20200414001'

select * from canzhao where role LIKE '营运总监%'
select DEPT_ID,GS from Interview where flow_id='MS20200414001'
select MANAGER from department where DEPT_ID='94'


select * from inhe_oa_paras

desc inhe_oa_paras
select * from inhe_oa_paras group by type

select * from inhe_system_admin 
select * from inhe_hr_flow_prcs 
select * from inhe_hr_flow_type
select * from inhe_hr_prcs_type

select * from rzdj 
select * from province
select * from city
select * from area

transaction_type
select * from ydlx

select * from rsyd
select * from yijian1

select * from user where real_name ='陈凤英'
select * from user where user_byid = 'INHE-0100'


select r.flow_id ,c.company,d.dept_name,y.mc,i.shr from rsyd r 
 left join company c on r.gs = c.id 
 left join department d on d.dept_id = r.bm 
 left join ydlx y on y.id = r.lx 
 left join yijian1 i on i.flow_id = r.flow_id 
where i.shr='唐剑'
group by r.flow_id 


select * from rsyd where flow_id in(
'RY20200107001',
'RY20200107002',
'RY20200107003',
'RY20200108001',
'RY20200114002',
'RY20200305001',
'RY20200306001',
'RY20200311001'
)

select * from rsyd where flow_id  = 'RY20200408001'


select * from canzhao2 where id = 4 

select * from user where user_id = 'HR02'

show tables like '%%'

select * from mail where mail_id = '159901'
select * from mail_body where mail_id = '159901'
select * from mail_bu where mail_id = '159901'
select * from mail_to where mail_id = '159901'
select * from vi_mail_star where mail_id = '159901'

select * from yijian1 where flow_id = 'RY20200408001'
 order by sj desc


select user_id from inhe_hr_flow_prcs where flow_id = 'RY20200408001' and  user_id != ''
group by user_id 

select * from rsyd where flow_id='RY20200408001'
select * from interviewmx2 where flow_id='RY20200408001'

select * from rsyd where name = '章春鹏'

select * from user where REAL_NAME  like '%顺心%'
select * from department where dept_id = '111'
-- MAIL
select * from MAIL;
select * from MAIL_BODY;
select * from MAIL_TO;

select * from inhe_kpikh
select * from inhe_oa_paras where type = 'process_status'

-- 保留历史审核记录
select * from yijian where flow_id = 'MS20200403001'
select * from yijian1;
select * from yijian2;

update yijian set APPROVE_ROUND = 1;
update yijian1 set APPROVE_ROUND = 1;
update yijian2 set APPROVE_ROUND = 1;

select * from canzhao where id =2
select * from interview where gs=2

select * from interview group by dept_id
select BM from rsyd group by BM

select * from inhe_kpimbmx1
select * from inhe_kpimb
select * from inhe_kpigl



