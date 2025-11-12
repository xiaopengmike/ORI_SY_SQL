select @rd := @rd+1 as ROW_NUM, b.* from (select @rd:=0 row_init,  
	d.DEPT_NAME, u.USER_NAME, u.USER_PRIV_NAME, yd.ZQ, yd.BT,yd.final_score P_SCORE, jd.sj26 S_SCORE, jd.sj11 B_SCORE 
	from user u
	left join department d on d.DEPT_ID = u.dept_id
	LEFT JOIN inhe_kpikh yd on yd.NAME = u.USER_NAME and yd.BM = d.DEPT_NAME  
	LEFT JOIN kpikh jd on jd.NAME = yd.NAME and jd.BM = yd.BM   and jd.BT = yd.BT
-- 	left join kpikh jd on jd.NAME = u.USER_NAME and jd.BM = d.DEPT_NAME  and jd.BT = '' 
-- where u.USER_NAME = '易**'
	 ) b 
order by zq DESC ,dept_name desc
limit 1,3



-- ZQ: 2019年06月
select * from inhe_kpikh

select * from kpikh where name ='易**' and bm = '信息部' and BT= '2019第1季度'

-- BT: 2019第3季度
select bt,sj11,sj26  from kpikh where name ='易**'



select * from inhe_kpikh a
LEFT JOIN kpikh b on b.bt =a.bt and b.name =a.name and b.bm =a.bm
where name ='易**'

select * from user where DEPT_ID='47'

select * from kpimb


select * from inhe_kpimb

select * from  inhe_kpimbmx1

select * from inhe_kpimbmx2

select * from inhe_kpimbmx3

select * from rsyd

select * from lzsq

select * from inhe_kpikh

select * from inhe_kpikhmx1
select * from inhe_kpikhmx2
select * from inhe_kpikhmx3

-- 20190910
-- 审批记录表
select * from inhe_hr_flow_prcs order by id desc;
desc inhe_hr_flow_prcs
-- 审批流程分类对照表
select * from inhe_hr_flow_type;
-- 审批状态对照表
select * from inhe_hr_prcs_type;


 SELECT * from rsyd where STU in('1','2','4')  ORDER BY ZDRQ desc limit

select * from Interview

select * from rzdj

select * from gzjj

select * from inhe_kpikh

select * from kpikh

select * from rsyd 


select * from fujian 

select u.dept_id from user u where user_bu = ()
group by u.dept_id

select * from department

select * from sales_performance ORDER BY ID DESC;

desc sales_performance;


select
u.user_id, u.user_name, u.dept_id, d.dept_name
from user u 
LEFT JOIN department d on d.dept_id =u.dept_id
order by u.dept_id 


select * from rzdj
desc rzdj

select * from fujian
desc fujian

select * from inhe_kpikh order by id desc 
select * from inhe_hr_flow_prcs


select * from inhe_kpikhmx1
select * from inhe_kpikhmx2
select * from inhe_kpikhmx3

select * from inhe_kpimb

select * from kpikh

select * from kpimb

alte table 

select * from inhe_kpimbmx1
select * from inhe_kpimbmx2
select * from inhe_kpimbmx3

select * from interview

alter table company add USER_BU VARCHAR(30) 


SELECT * FROM inhe_system_admin


廖宇翔

select * from inhe_kpimbmx

UPDATE inhe_kpikh set stu =2 where lx =2


select * from inhe_kpikh where jldh = '10194';
select * from inhe_kpikhmx1 where jldh = '10194';
select * from inhe_kpikhmx2 where jldh = '10047';
select * from inhe_kpikhmx3 where jldh = '10194';


select * from inhe_kpikh where lx =1


select * from inhe_kpikhmx3 


select * from inhe_kpikhmx1 where JLDH='10036'


select user_id,user_name,count(user_id) sum from inhe_kpigl
group by user_id
ORDER BY sum desc


select * from inhe_kpikh 

delete from inhe_kpikh where zq like '%2019年10月%'


select * from inhe_kpimbmx3

select * from inhe_kpikh where jldh = (select max(jldh) from inhe_kpikh );
select * from inhe_hr_flow_prcs where flow_id = (select max(flow_id) from inhe_hr_flow_prcs );




select * from user 
