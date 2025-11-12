-- 202002
select * from interviewmx1
desc interviewmx1


select * from inhe_hr_flow_type


select * from user
select * from canzhao
select * from canzhao2
select * from canzhao3
select * from canzhao4	
select * from interviewmx2	where rzgh = 'INHE-0910'

MS20200107001	1	INHE-0910		测试工程师	48		B1-1	4320	4320	270	810		0	0	0	0		9720	2020-02-21
MS20200107001	1	INHE-0910			47		R3-5	4640	4640	300	1200	0	0	0	0	0		10780	2020-02-21


select * from rsyd where dh = 70
select * from rzdj

select * from department

select * from Interview

select * from inhe_hr_prcs_type

select * from inhe_kpikhmx1

select * from inhe_system_admin

select * from inhe_kpimbmx1

select * from sales_performance
select * from sales_performance_director

select * from inhe_kpikh
select * from inhe_kpikhmx3


select * from user where user_id = 'INHE-0611'

select * from inhe_kpikh
desc inhe_kpikh

select * from inhe_hr_flow_prcs where flow_ID LIKE 'yk20200225%'

select * from inhe_hr_flow_prcs
select * from inhe_hr_flow_type

select * from user where user_name in ('张建刚','王顺万','何梓劲','王占颍','章春鹏')

select * from sales_performance where cycle='2020年02月';
delete from sales_performance where cycle='2020年02月';



select * from inhe_kpikh where name in ('张建刚','王顺万','何梓劲','王占颍','章春鹏') and zq = '2020年02月';
select * from inhe_hr_flow_prcs where flow_id in (select flow_id from inhe_kpikh where name in ('张建刚','王顺万','何梓劲','王占颍','章春鹏') and zq = '2020年02月');


select * from rzdj 

select * from hukou


select * from inhe_kpimb
desc inhe_kpimb
select * from inhe_hr_flow_type
select * from inhe_hr_flow_prcs 
select * from canzhao2 

-- -------------------------------
select * from interviewmx2
RY20200221005
select * from rsyd where DH=71;
select * from  yijian1 where flow_id = 'RY20200221005'

select * from user where is_kh = ''
select dh from inhe_kpimbmx1 where byzd9 like '%\＜%'

select * from interviewmx2 where rzgh = 'INHE-0910';
MS20200107001	1	INHE-0910		职员	47		R3-5	4640	4640	300	1200	0	0	0	0	0		10780	2020-02-21
MS20200107001	1	INHE-0910		职员	48		B2-10	4970	4970	300	900	0	0	0	0	0		11140	2020-02-21

-- 20200228
select * from inhe_kpimbmx1
 select * from inhe_kpimbmx1 where dh = '1113' and byzd1 = '1'

819 3004   YK20200226018 10815 
821 3003 YK20200226020 10817 

select * from sales_performance_director

select * from inhe_kpikhmx3 where jldh = 10006

select * from inhe_kpikhmx3

desc inhe_kpikhmx3

select * from rzdj
select * from gongz 

-- 201903 ---------------------------------------------------------
select * from interviewmx2 where rzgh = 'INHE-0910';

select * from rzdj

select * from inhe_kpikh order by flow_id desc


select * from inhe_kpikhmx1

select * from inhe_kpimbmx1
select * from inhe_kpikh

select * from inhe_kpikhmx1 a 
left join inhe_kpikh b on b.jldh = a.jldh 
left join inhe_kpimbmx1 c on c.dh = b.dh and c.byzd1 = a.byzd1
where a.jldh = 10011


select * from inhe_kpikhmx1 a 
left join inhe_kpikh b on b.jldh = a.jldh 
left join inhe_kpimbmx1 c on c.dh = b.dh and c.byzd1 = a.byzd1
where a.jldh = 10859

select * from sales_performance_director  where  cycle = '2020年02月' order by id desc


select * from interview a LEFT JOIN interviewmx2 b on a.flow_id=b.flow_id

select * from interview

select * from inhe_kpimbmx3


select * from inhe_kpikhmx3

select * from inhe_kpikhmx3 where jldh = 10013


select DISTINCT a.NAME,a.TIME,a.ZW,a.XB,a.GH,a.TIME1,a.MZ,a.HYZK,a.YX,a.ZY,a.TIME3,a.JG,a.SHR,a.USER_BU,cc.company,mz.NAME mzDesc, hy.NAME hyDesc from rzdj a  
left join company cc on cc.user_bu = a.user_bu 
left join department d on d.dept_id = a.BM 
left join interviewmx2 b on a.GH=b.RZGH  
left join minz mz on mz.ID = a.MZ 
left join huny hy on hy.ID = a.HYZK
where 1=1 and a.GH='INHE-0918' or a.SHR='INHE-0918' 
     or b.flow_id in (select flow_id from interview a LEFT JOIN department b on a.dept_id=b.DEPT_ID where b.MANAGER='INHE-0918,' or b.LEADER1='INHE-0918,') 
 order by a.TIME desc


select * from rzdj

select count(*) number from inhe_hr_flow_prcs where user_id = '" . $_SESSION['LOGIN_USER_ID'] . "' and flow_prcs = 'P' and flow_ID = '" . $flowId . "'

select * from inhe_hr_flow_prcs set user_id = '' where flow_id = 'RY20200304006' and id = '2145'

select * from djgz
select * from inhe_salary_change
select * from interviewmx2

select * from rsyd where gh = 'INHE-0432'

desc rsyd
select * from lzsq
desc interviewmx2
select * from interviewmx2 where rzgh = 'tj'
select * from inhe_salary_change order by id desc

select r.*,c.company,c.user_bu company_abbr, d.dept_name,y.mc from rsyd r
        left join company c on r.gs = c.id 
        left join department d on d.dept_id = r.bm 
        left join ydlx y on y.id = r.lx 
        where flow_id='RY20200305001' and type = 'bm'

desc rsyd
desc inhe_salary_change

select * from rsyd where flow_id = 'RY20200310001' order by flow_id desc 

select * from yijian1 ORDER BY sj desc
select * from inhe_hr_flow_type

select kk.uid,kk.name,kk.final_score,k.total,format(kk.final_score-k.total,2) from inhe_kpikh kk
left join inhe_public_kh k on k.uid = kk.uid 
where k.total is not null and k.cycle='2020年02月' and kk.zq = '2020年02月'

desc interviewmx2
select * from interview where flow_id = 'MS20200220001' order by flow_id desc

select flow_id,tpdz from interview 
up

CREATE TABLE user_priv_202003 as 

select * from user_202003 where user_id = 'admin1'

TRUNCATE TABLE department

show tables like '%202003%'

select * from canzhao2
select * from company

select * from user where user_id in('admin','inhe-0359','admin1','inhe-0247')

select * from user where user_id = 'admin1'


insert into user
select * from user where user_id = 'admin1'

select * from rsyd order by flow_id desc

select * from department where dept_id= 11

select * from user where user_name like '%傅%'

select * from interview order by flow_id  desc 
select * from canzhao2

select * from interview where flow_id = 'MS20200311001'
select * from interviewmx2 where flow_id = 'MS20200311001'

select * from interviewmx1 
select * from Interviewmx2
desc  Interviewmx2

select * from inhe_hr_flow_prcs where flow_id = 'MS20200311001' 
select * from yijian where flow_id = 'MS20200311001' 

select * from inhe_system_admin 

select * from gongz
select * from inhe_hr_flow_type

select * from interviewmx2 where flow_id = 'MS20200114002'

select * from interview where flow_id in(
select flow_id from interviewmx2 where rzgh = '')

select * from(
SELECT
        U.uid,
U.real_name,
u.user_name,
        RPAD(substring(U.user_name,1,1),CHAR_LENGTH(U.user_name),'*') realName
FROM
        USER U) t 
where t.user_name != t.realName

-- update USER set  user_name = RPAD(substring(user_name,1,1),CHAR_LENGTH(user_name),'*')


select * from interviewmx2 where rzgh = 'INHE-0776'
 select * from user where user_id = 'admin1'
select * from department where user_bu= 'CORP'

select * from department where dept_parent = 2 order by dept_no 
select * from user where user_name like '%张*%' and dept_id ='21'

desc department
select * from user where user_id = 'IM-0044'
select * from department where dept_name like '%总经办%'
select * from company
select * from inhe_reward_punishment;
select * from inhe_reward_punishment_detail;

select * from company

select m.*,s.*, u.user_name createUserName, d.dept_name deptName, uu.user_name userName from inhe_reward_punishment m
left join inhe_reward_punishment_detail s on s.flow_id = m.flow_id 
left join user u on u.user_id = m.create_user_id 
 left join department d on d.dept_id = s.dept_id
 left join user uu on uu.user_id = s.user_id


select * from rzdj

select * from user where real_name like '覃湘君%' or real_name like '%张柳%'  or user_id = 'INHE-0711'

desc user

select * from department 
select * from company
select * from rsyd order by flow_id desc
select * from inhe_system_admin 

select dept_id from department where dept_parent = '5'
select * from inhe_hr_flow_type

select uid,user_id,user_name,dept_id from user

select * from inhe_contract_registration;
desc user

desc MAIL
desc MAIL_TO


select * from interviewmx2 where rzgh ='INHE-0010'
select * from interview order by flow_id desc

select * from inhe_oa_paras

show tables like '%para%'

select u.uid, u.user_id, u.user_name, u.user_byid, p.rzsj from user u 
left join interviewmx2 p on p.rzgh = u.user_byid where u.dept_id = '57'

select b.real_name,a.* from interviewmx2 a left join user b on a.rzgh = b.user_byid order by rzsj asc


select * from inhe_hr_flow_prcs where flow_id = 'YK20191029068'


select * from inhe_reward_punishment_detail

select f.* from flow_data_209 f where f.data_19118 != '' order by f.id desc
-- -------------------------------------------------------------
select * from inhe_hr_flow_type;
select * from inhe_hr_prcs_type;
select * from inhe_hr_flow_prcs;
select * from company;

-- inhe_oa系统参数管理表
select * from inhe_oa_paras ORDER BY id desc
desc inhe_oa_paras

select * from inhe_contract_registration where user_bu = 'INHENERGY'
desc inhe_contract_registration

select * from department where dept_id = 56

select * from inhe_reward_punishment_detail;
select g.name,u.user_name from inhe_reward_punishment g left join user u on u.user_id = g.create_user_id  where g.flow_id = JC20200330001

select * from canzhao where id=2
select * from company


desc yijian
MS20200324001

select * from department where dept_name like '%品牌%'
select * from sales_performance_director where user_name = '张柳芳' and cycle = '2020年03月' order by id 

select * from user where real_name like '%张建刚%'
select * from user where real_name like '%丁富民%'

select * from user where real_name like '谌%'   or user_id ='INHE-0272'
user_byid = 'INHE-0005'
select * from inhe_contract_registration where dept_id = 0
-- update inhe_contract_registration a
left join user b on b.user_byid = a.work_number 
set a.dept_id = b.dept_id where a.dept_id = 0

--  update inhe_contract_registration set end_time='2013-01-14',period = '01',type='01' where id= 148
INHE-0193

select MANAGER,LEADER1 from department d where d.dept_id = '108'


select * from user where 
dept_id in('108',(select d.dept_parent from department d where d.dept_id = '108'))
or find_in_set('108', dept_id_other)
or user_id in ('INHE-0193',	'INHE-0247')

select * from department where dept_id = 11

select * from inhe_hr_flow_prcs
select * from inhe_kpimbmx1 where dh = 1116
select * from inhe_kpikhmx1 where jldh = 10014
select * from inhe_kpikh where jldh = 10014

select * from inhe_kpimb

show tables like "%mail%"

select * from vi_mail_star 

select * from kpimb 
select * from kpikh 
select real_name from user  where user_id = 'INHEGRID-0039'


select * from inhe_form_data 

select * from kpikh
select * from kpimb where user_bu = 'INHEGRID'

insert into  inhe_system_admin values(null,'INHEGRID-0039','INHEGRID','1','F04');
insert into  inhe_system_admin values(null,'INHEGRID-0039','INHEGRID','1','F04');



select * from inhe_hr_flow_prcs where flow_id in (
select flow_id from kpikh  where bt='2020第1季度' and rq < '2020-03-01' 
)

select * from inhe_hr_flow_prcs where flow_id like 'JK202002%'

select * from pingjia
select * from fujian
select * from fujian2

select * from interviewmx1 where flow_id = 'MS20200224001'
desc interviewmx2



select * from interview where flow_id = 'MS20200224001'
select * from interviewmx1 where flow_id = 'MS20200224001'
select * from interviewmx2 where flow_id = 'MS20200224001'
flow_id  GS RZGH ZH ZW BM_ID BM dj
gwgz
jxgz
hsbt
zfbt
tbjx
jtbt
xjxd
ng
txbz
duty_allowance
je
RZSJ

insert into interviewmx2 (flow_id,)values 
select flow_id, from interviewmx1 where flow_id = 'MS20200224001'

insert into interviewmx2 values 

select a.flow_id, a.GS,'INHENERGY-0045' RZGH,'' ZH,a.ypgw ZW,a.dept_id BM_ID,'' BM,
b.DJ, b.GWGZ,b.JXGZ,b.HSBT,b.ZFBT,b.TBJX,b.JTBT,b.XJXD,b.NG,b.TXBZ,b.DUTY_ALLOWANCE,b.JE,'2020-03-31' RZSJ
from interview a 
left join  interviewmx1 b on b.flow_id = a.flow_id
where a.flow_id = 'MS20200224001'

select * from interview where flow_id = 'MS20200224001'

select * from  inhe_kpimbmx1
alter table inhe_kpimbmx1 modify BYZD6 text ; 
alter table inhe_kpimbmx1 modify BYZD8 text ; 
alter table inhe_kpimbmx1 modify BYZD9 text ; 

select * from user_priv where PRIV_NAME like '%省级%'

select YPGW from interview a LEFT JOIN interviewmx2 b on a.flow_id=b.flow_id where RZGH='INHENERGY-0045'

select * from rzdj where name ='冯海斌'

select * from interviewmx2 where flow_id = 'MS20200224001'

select * from inhe_reward_punishment
select * from inhe_reward_punishment_detail

select * from interview ORDER BY flow_id desc limit 0,1
select * from fujian ORDER BY flow_id desc 
select * from pingjia ORDER BY flow_id desc
desc interview
select * from rsyd ORDER BY flow_id 


select * from yijian 
select * from yijian1

select * from department  where dept_id =74

select * from user where user_id='INHE-0925'

select * from inhe_system_admin where type='F10'

select * from pingjia where flow_id like 'MS2%';
select * from fujian where flow_id like 'MS2%';
select * from fujian2 where flow_id like 'RY2%';






