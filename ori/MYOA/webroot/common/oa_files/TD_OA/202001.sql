select * from department where dept_parent = 2 and (dept_name like '%总监%' or dept_name like '%董事会%')



select * from department where leader1= 'meterw,' and dept_parent = 2

select * from Interview

select DEPT_ID,GS from Interview where flow_id = 'MS20191225001'

select MANAGER,LEADER1 from department where DEPT_ID='48'

select * from canzhao 
select * from company
INHE
INHEGRID
INHENERGY
WITLINK
JXINHE
CORP

F01	面试记录审核流程
F02	入职登记表流程
F03	人事异动流程
F04	工作能力和工作态度绩效考评
F05	KPI考核
F06	离职申请流程
F07	工作交接流程
入职登记	 	0
人事异动		1
入职登记	 	2
离职申请	 	3
SELECT * from sales_performance

SELECT * from company
SELECT * from rzdj
SELECT * from interviewmx2

select * from inhe_hr_flow_type
select * from inhe_system_admin
select * from interviewmx2 

select * from quanx
desc quanx

select user_bu,is_admin from inhe_system_admin where 1=1 and user_id = 'admin1' and type = 'F01'


SELECT * from Interview

select * from kpikh k where (NAME='测试（编号）' or SHR='t03' or SHR1='t03') and k.bt like '%2019第4季度%' ORDER BY RQ desc 

select * from rzdj where 1=1 and user_bu in ('INHE','INHEGRID','INHENERGY') ORDER BY rq1 desc limit 0,10
desc rzdj

select * from rzdj

select * from department_pro
select * from user_priv
select * from interviewmx2
select * from interview

select * from rzdj a 
			LEFT JOIN interviewmx2 b on a.GH=b.RZGH 
			LEFT JOIN interview c ON b.flow_id=c.flow_id 




desc djgz
select * from djgz
update djgz set gwgz = gwgz/2
update djgz set jxgz = jxgz/2
update djgz
update djgz set zfbt = hsbt
update djgz set hsbt = 300

select * from wenhua
select * from yijian
select * from company
select * from interview	 

truncate table department
insert into department
select * from department_old
select * from user
select * from user where user_name like '%范%' 

select d.DEPT_ID, d.DEPT_NAME, d.DEPT_PARENT from department d  where 1=1  and (d.user_bu = 'INHE' or d.dept_id = '2') 

select * from yijian1 

select * from rsyd 
SELECT * from company
select ID,company from company

select * from djgz where 1=1 
select * from fujian2

desc jingli
select * from jingli
select * from interview 
desc interview
select * from interview a left join company b on a.GS=b.ID where a.flow_id='MS20200114001'

select * from fujian where flow_id = 'MS20200114001' and id = '46' 
select * from pingjia 
select * from city
select * from province

select * from interview
select * from company

select * from department

select DEPT_ID,GS from Interview where flow_id=''

select * from department_new where  is_director = 'Y' 

select * from yijian1
desc department_new

-- 20200117  ----------------------
select * from rsyd



delete from djgz where user_bu = 'INHEGRID';

select * from inhe_system_admin 

select * from Interviewmx2 




select count(r.*) sum from rsyd r where r.stu in(1,2,3,4) and 
r.user_bu in('INHE')

select count(r.*) from rsyd r 


select * from yijian1 where flow_id = 'RY20200118002'

SELECT count(*) sum from rsyd r where r.STU in ('1','2','3','4') and r.user_bu in('INHE','INHEGRID') 





