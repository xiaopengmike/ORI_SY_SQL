-- 201912

select * from rsyd where zdr = 'admin1' group by flow_id ORDER BY ZDRQ desc limit 0,10

select * from YDLX

delete from rsyd;


select * from logo

desc rsyd

desc inhe_salary_change

select * from inhe_salary_change

DELETE from inhe_salary_change

select * from user

select * from (
select * from inhe_salary_change 
ORDER BY id DESC
) t
group by user_id 


select * from inhe_system_admin

select * from interview ORDER by time desc
select * from interviewmx1
select * from interviewmx2


select * from fujian where JLDH='MS20191208001'

select * from pingjia 

select * from canzhao 

select * from department

select * from jingdu

select * from company
	select * from rsyd
	select * from fujian2
RY20191210001
	select * from yijian1
select * from canzhao2

desc rsyd
select * from  ydlx

select * from user where user_id like 'INHE-%' order by uid desc

select user_id from user where uid = (
select max(uid) from user where user_id like 'INHE-%' order by uid desc
)


select * from user 
select * from rsyd
select * from inhe_salary_change

SELECT r.*,c.company,d.dept_name,y.mc from rsyd r
		left join company c on r.gs = c.id 
		left join department d on d.dept_id = r.bm 
		left join ydlx y on y.id = r.lx 
group by flow_id 
		 ORDER BY ZDRQ desc

select * from interviewmx2

select * from inhe_hr_flow_prcs

-- --------------------
select * from rzdj 
jiating
jiaoyu
gongz
fujian1
inhe_language_skill

select * from user where user_id ='admin1'

select * from fujian

select * from interview
select * from interviewmx1
select * from interviewmx2
select * from logo

select * from user 
ORDER BY uid desc

select * from province;
select * from city;
select * from area;

select p.pro_id,p.pro_code,p.pro_name,c.id,c.code,c.name from province p
left join city c on c.province_id = p.pro_id 

select * from rzdj

select * from fujian1
select * from fujian

select * from user

-- 薪资基准表
select * from interview order by jldh
select * from interviewmx2 order by jldh
select * from user where user_id in(select rzgh from interviewmx2)
select * from interviewmx2 a 
left join interview b on a.JLDH=b.JLDH 
where a.BM_ID<>'0' ORDER BY je desc

select * from user_priv

select * from inhe_salary_change
select * from djgz
select * from rsyd where flow_id='RY20191211001'
select * from user_priv where USER_PRIV = 'IT工程师'
select * from rsyd
select * from rzdj
select * from interview where 
select * from inhe_language_skill
select * from fujian 
select * from yijian1 
select * from fujian1
select * from interviewmx2
select * from canzhao2 where ID=1

select * from yijian1 where flow_Id='RY20191212001' and BZ=6



select * from interview
select * from interviewmx1
select * from interviewmx2

select * from fujian


select * from daqx 


select 
          a.GH,b.BM_ID,a.NAME,SEX,b.ZW,RZSJ,TIME1,MZ,HYZK,WH,YX,ZY,GRADUATION_DATE,JG,b.gs
          from rzdj a 
          LEFT JOIN interviewmx2 b on a.GH=b.RZGH 
          LEFT JOIN interview c ON b.JLDH=c.JLDH 
          where BM_ID<>'0'  and b.GS in (1,2,3,4) ORDER BY GH desc

select * from  rzdj;
select * from  interview;

select * from interviewmx1;
select * from interviewmx2;
select * from yijian;
select * from yijian where JLDH='MS20191216001' and shr = '郭主管'
-- ---------------------------------
select * from province;
select * from city;
select * from area;


select * from user  order by uid desc


select * from canzhao
select * from canzhao2
select * from canzhao3
select * from canzhao4

SELECT * from logo
-- ----------------------
select * from company 

select * from fujian1

select * from inhe_hr_flow_prcs

SELECT * from interview
SELECT * from interview where jldh =  'MS20191213001'
update interview set APPROVE_PERSON  ='t04' where jldh =  'MS20191213001'
select * from Interviewmx2
update Interviewmx2 set rzgh='' where jldh ='MS20191213001'

select * from user 
password = '$1$lA5.Kc0.$abx7aIvyeBEmohJcufy3M0' -- 默认密码

update user set 
user_name = '测试新员工',user_priv='65',USER_PRIV_NO='115',USER_PRIV_NAME='测试工程师'，
theme='1',ON_STATUS='3',
where user_id ='INHE-0917'


select * from lzsq

select * from gzjj
update gzjj set status='2' ,bz='2' where gh = 'fxs'
select * from fujian3

select * from inhe_hr_flow_prcs

select * from quanx where ID='3'

select * from canzhao3

select * from flow_step
select * from inhe_hr_flow_type
部门经理/主管

select * from inhe_salary_change

select * from Interview 

select * from inhe_system_admin
insert into inhe_system_admin VALUES(null,'INHENERGY-0042','INHENERGY','1','kh_admin');


select * from inhe_hr_flow_prcs where flow_ID = 'RY20191217001'

select * from country_area

select * from email_body 

select * from SMS,SMS_BODY

select * from SMS_BODY order by body_id desc limit 0,10

select * from mail_body

select * from INFORMATION_SCHEMA.TABLES where TABLE_SCHEMA = 'TD_OA'  order by create_time desc;

select * from country_area


select * from inhe_oa_paras where type = 'kpikh_remind' and is_used = '1'
desc inhe_oa_paras
select * from inhe_flow_step
select * from inhe_system_admin
select * from inhe_attach
select * from inhe_opinion
select * from inhe_evaluation

select * from attachment
select * from quanx;

YK20191223016


select * from canzhao 
select * from canzhao2 
select * from company

SELECT * from rzdj ORDER BY RQ1 desc

-- 220191225 test

SELECT * FROM interview 


select * from kpikh where  gz15<=60 and id>2200


select * from kpikh where (SHR='JXINHE-0060' and STU='1') or (SHR1='JXINHE-0060' and STU='2') ORDER BY RQ desc
select * from inhe_kpikh where shr = 'INHE-0599'
select * from kpikh where id >2200 and name like '%卢锦%'

select * from interview where create_user_id is null


select * from inhe_kpimbmx3

SELECT * from inhe_kpigl where user_id = 'admin1'


select * from inhe_kpimb


select * from sales_performance where cycle  = '2019年12月' 



select * from inhe_kpimbmx3 where dh in ('3003','3004')
select * from inhe_kpikhmx3 where jldh= 10019


select * from inhe_kpikh where  jldh= 10019 uid= 20001

select * from inhe_system_admin
desc inhe_system_admin 

alter TABLE inhe_system_admin MODIFY user_bu varchar(50);


select * from inhe_kpimbmx3

select * from inhe_kpimb where id >=120
select * from inhe_kpigl

select * from department

select * from rsyd
select * from company

select * from user where user_id = 'admin1'

desc interviewmx2
select * from interview where flow_Id = 'MS20191230002'
select * from interviewmx1
select * from interviewmx2

select * from yijian1


select * from company


select * from inhe_hr_flow_prcs
select * from inhe_hr_prcs_type
E	结束
N	未批准
P	审批中
T	待提交
Y	已审批



select * from user 

select * from interview where flow_id = 'MS20191230002'






