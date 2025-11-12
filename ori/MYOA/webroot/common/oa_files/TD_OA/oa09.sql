show tables like '%menu%';

-- 导航菜单目录表
select * from  sys_menu;
-- 菜单子功能表结构
select * from sys_function 
where MENU_ID like '5001%'
-- FUNC_CODE = '@hr'
FUNC_NAME like '%人事管理%'; 
order by menu_id  
-- 系统变量对应表
select * from sys_code;
-- 系统日志表
select * from sys_log;
-- 系统常量参数表
select * from sys_para;
-- 系统用户表
select * from user;

SELECT * from department
-- 用户扩展信息表
select * from  user_ext;
-- 系统用户权限管理表
select * from user_priv;
-- 首页模块表
select * from mytable;
-- 组织部门表
select * from department;
-- 扩展表
select * from ext_user;
 -- 论坛讨论区文章表
select * from bbs_comment where comment_id = 31

select * from sys_function where func_name='KPI季度考核'

select a.USER_ID,a.USER_NAME,b.DH from inhe_kpigl a,inhe_kpimb b where a.ID=b.ID and b.LX=3
select MAX(JLDH) JLDH from inhe_kpikh


select * from inhe_kpikh


select b.MANAGER from user a,department b where a.DEPT_ID=b.DEPT_ID and a.USER_NAME='测试3'
select * from inhe_kpikh where NAME='测试3' and ZQ='2019年09月'


select * from inhe_kpimbmx3

select * from star_pm_user


select * from inhe_kpimbmx3

desc inhe_kpimbmx3
desc inhe_kpimbmx3_tmp

CREATE table inhe_kpimbmx3_tmp as 
SELECT * FROM inhe_kpimbmx3 



select * from inhe_kpimb


select * from inhe_kpikh

select * from inhe_kpikh where  like '%3%' ORDER BY RQ desc limit 0,10

-- 20190910
-- 审批记录表
select * from inhe_hr_flow_prcs order by id desc;
desc inhe_hr_flow_prcs
-- 审批流程分类对照表
select * from inhe_hr_flow_type;
-- 审批状态对照表
select * from inhe_hr_prcs_type;

select * from user;
select * from department;

select 
p.flow_ID,p.flow_TYPE_ID,ft.flow_NAME,p.USER_ID,u.USER_NAME,p.PRCS_FLAG,p.PRCS_NUM,
p.FLOW_PRCS,ct.PRCS_NAME,p.FROM_USER,uu.USER_NAME FROM_USER_NAME,p.CREATE_TIME,p.USER_BU
from inhe_hr_flow_prcs p
left join inhe_hr_flow_type ft on ft.flow_TYPE_ID = p.flow_TYPE_ID
left join inhe_hr_prcs_type ct on ct.FLOW_PRCS = p.FLOW_PRCS
left join user u on u.user_id = p.user_id
left join user uu on uu.user_id = p.from_user

	SELECT PLAN_ID,NAME,BEGIN_DATE,END_DATE,TYPE,SUSPEND_FLAG,MANAGER from WORK_PLAN
	SELECT TYPE_NAME from PLAN_TYPE

select 
p.flow_ID,p.flow_TYPE_ID,ft.flow_NAME,p.USER_ID,u.USER_NAME,p.PRCS_FLAG,p.PRCS_NUM,
p.FLOW_PRCS,ct.PRCS_NAME,p.FROM_USER,uu.USER_NAME,p.CREATE_TIME,p.USER_BU
from inhe_hr_flow_prcs p
LEFT JOIN inhe_hr_flow_type ft on ft.flow_TYPE_ID = p.flow_TYPE_ID
LEFT JOIN inhe_hr_prcs_type ct on ct.FLOW_PRCS = p.FLOW_PRCS
LEFT JOIn user u on u.user_id = p.user_id
left JOIN user uu on uu.user_id = p.from_user 

insert into inhe_hr_flow_prcs (ID, flow_ID, flow_TYPE_ID, USER_ID, PRCS_FLAG, PRCS_NUM, FLOW_PRCS, 
            FROM_USER, CREATE_TIME, USER_BU) values (NULL,'MO0190910001','F02','ceshi1',1,4,'T','admin','2019-09-10 15:26:51','INHENERGY');


select * from rsyd
select * from yijian1

SELECT * from Interview
SELECT * from Interview where STATUS in ('1','2') and APPROVE_PERSON='admin'

select * from pingjia;
select * from fujian;
select * from jingli;
select * from Interviewmx1;


select * from company
select * from company where ID='$company'
-- 流程步骤表
select * from canzhao
select * from canzhao2
select * from canzhao3
select * from canzhao4
select * from kpiqx

-- 
select * from department
select * from user


select 

from user u
left join department d on d.DEPT_ID = u.dept_id



select * from inhe_kpikh

kpikh
kpigl
kpimb
kpiqx

select * from inhe_kpikh

inhe_kpikh

inhe_kpikhmx1
-- 
inhe_kpikhmx2
-- 国际业务部kpi考核数据
inhe_kpikhmx3

inhe_kpimb
inhe_kpigl
inhe_kpimbmx1
inhe_kpimbmx2
inhe_kpimbmx3

select 
d.DEPT_NAME, u.USER_NAME, u.USER_PRIV_NAME

from user u
left join department d on d.DEPT_ID = u.dept_id
LEFT JOIN inhe_kpikh yd on yd.NAME = u.USER_NAME and yd.BM


select * from inhe_kpikh
select * from kpikh
desc inhe_kpikh

SELECT * from inhe_hr_flow_prcs
where id = '' OR 1=1 -- 4 

-- 查询kpi考核统计数据
select @rd := @rd+1 as ROW_NUM, b.* from (
select @rd:=0 row_init,  
d.DEPT_NAME, u.USER_NAME, u.USER_PRIV_NAME, 
yd.ZHD P_SCORE, 
jd.sj11 S_SCORE, 
jd.sj26 B_SCORE 
from user u
left join department d on d.DEPT_ID = u.dept_id
LEFT JOIN inhe_kpikh yd on yd.NAME = u.USER_NAME and yd.BM = d.DEPT_NAME
left join kpikh jd on jd.NAME = u.USER_NAME and jd.BM = d.DEPT_NAME
) b
where b.P_SCORE != ''


select @rd := @rd+1  as rownum, b.name from (select @rd:=0, name from test) b


