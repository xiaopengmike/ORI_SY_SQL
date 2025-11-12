-- pm_manage
-- 审核记录表； 项目比例数据表； 历史数据表
select * from inhe_pm_proportion;
select * from inhe_pm_proportion_history;
select * from inhe_pm_proportion_item ORDER BY user_item ;
-- 人事模块优化改造，主要是数据库结构
select * from inhe_system_admin;
select * from inhe_oa_paras; 
select * from company;
select * from djgz;
select * from interview;
select * from interviewmx1;
select * from interviewmx2;
select * from rzdj;
select * from rsyd; 
select * from lzsq;
select * from gzjj;
-- 数据字典管理
select * from inhe_data_dictionary
-- 历史记录处理
select * from yijian where flow_id = 'MS20200103005'
select * from yijian1 where flow_id = 'MS20200103005'
select * from canzhao where id = 5
select * from canzhao2 where id = 5
select * from company where id = 5
select * from inhe_hr_flow_type;
select * from inhe_hr_flow_prcs where flow_id like 'YK%'
select * from department
-- SQL   JXINHE-2272  INHE-0038
-- JXINHE-4040  田沛;   JXINHE-2860   熊茂东  -- JXINHE-1753  熊秋
select * from user where user_name like '%刘长%'
select y.*,c.ROLE from yijian1 y left join canzhao2 c on c.id='1' and c.BZ = y.BZ where y.flow_id = 'RY20200107003'

select * from interview where flow_id = 'MS20200605001';
select * from interviewmx1 where flow_id = 'MS20200604001';
select * from department where dept_id = 22

SELECT
  a.TABLE_NAME '表名',
  a.COLUMN_NAME '字段名',
  a.COLUMN_TYPE '数据类型',
  a.DATA_TYPE '类型',
  a.CHARACTER_MAXIMUM_LENGTH '长度',
  a.IS_NULLABLE '是否为空',
  a.COLUMN_DEFAULT '默认值',
  a.COLUMN_COMMENT '备注'
FROM
information_schema.COLUMNS a
where
 a.table_schema ='TD_OA'  
and a.TABLE_NAME = 'inhe_salary_change'
order by a.TABLE_NAME;

show tables

select * from user where user_id = 'INHE-0038'
select * from user where user_id = 'INHE-0777'

select * from  department where dept_name like '%研发部(RDD)%'

select * from rzdj 
select * from inhe_system_admin where type ='F01'
select * from user_priv

select * from interview where flow_id = 'MS20200608001';

select * from canzhao where ID='3' and BZ='4'

select * from interviewmx2 where rzgh = 'INHENERGY-0063'

select * from rsyd where flow_id = 'RY20200610001'

select * from  fujian

select * from inhe_worker_attach

select * from interview where flow_id = 'MS20200611001'

select * from user where user_bu = 'WITLINK'

select * from inhe_system_admin where type = 'F03'

select * from inhe_kpimb  where dh = 1026
select * from inhe_kpimbmx1 where byzd4 = '季度'
select * from department where dept_id = 22
select * from inhe_kpigl where name = '实验室/经理'

select * from kpigl where user_id = 'WL-0027'

select * from yijian where flow_id = 'MS20200616001'
select * from interview where flow_id = 'MS20200616004'
select * from interviewmx1 where flow_id = 'MS20200616001'
desc interview

select * from interviewmx2 where flow_id = 'MS20200616004'
select * from user where user_id = 'INHENERGY-T-0001'

select * from djgz 

select * from rsyd where flow_id = 'RY20200618001'
select * from canzhao2 

-- select * from user where user_id ='INHE-0247' or  user_id ='WL-0008'

select * from yijian1 where flow_id = 'RY20200618001'
 select * from user where user_id ='WL-0046'
 select * from interviewmx2 where rzgh ='WL-0046'
 select * from inhe_hr_flow_prcs where flow_id = 'RY20200618001'

show TABLES like '%data%'

select * from user where User_id = 'INHE-0621'

select data_19117,data_19118 from flow_data_209_copy1  where run_id = 67532
select data_19117,data_19118 from flow_data_209_copy  where run_id = 67532

select * from inhe_public_kh

select * from inhe_system_admin where type = 'dept_select' 

select * from inhe_form_data

desc inhe_form_data

select * from inhe_kpigl where user_bu = 'WITLINK'
select * from kpigl 


select * from inhe_kpimb where lx = 3

select * from inhe_oa_paras
select * from user where user_name = '姚丽娟'

select * from kpikh 
'INHE-0231','WL-0008','INHE-0298'

desc inhe_public_kh
alter table inhe_public_kh add office varchar(5);
alter table inhe_public_kh add plan varchar(5);

select k.id,k.flow_id,k.bt,k.name,k.bm,k.user_bu,c.company,k.zw,k.sj26 s_score, k.sj11 b_score,k.stu from kpikh k 
left join company c on c.user_bu = k.user_bu
 where 1=1  and k.user_bu in('INHEGRID') ORDER BY id desc

select * from department where dept_id = 125

select * from inhe_hr_flow_type
select * from inhe_system_admin where user_id = 'INHE-0808' and type = 'dept_select'
insert into inhe_system_admin values (null,'INHE-0808','INHE,INHENERGY','1','dept_select')

select * from yijian1 where shr = '王功勇'

select * from user where user_id = 'INHE-0900'


select * from interview where user_bu = 'INHENERGY'
select * from interviewmx1 where flow_id = 'MS20200611003'
select * from interviewmx2 where flow_id = 'MS20200611003'

5378	MS20200701001	F01	INHENERGY-0042	1	0	P	INHENERGY-0050	2020-07-01 14:41:08	INHENERGY

select * from user where user_id = 'INHE-0900'

