-- 更新销售排名数据
select * from sales_performance_1201
create table sales_performance_1201 as 
select * from sales_performance where cycle  = '2020年12月' 
select * from sales_performance where cycle  = '2021年01月' and user_id != '' order by id desc
select * from sales_performance where cycle  = '2021年01月' order by sales_ranking desc

update sales_performance set sales_proportion = sales_ranking where cycle  = '2021年01月'

update sales_performance a left join(
select @rd := @rd+1 as ROW_NUM, b.id,b.sales_proportion from (
select 
@rd:=0 row_init,  
	 s.* from sales_performance s where s.cycle = '2021年01月' 
order by sales_proportion*1 desc 
 ) b ) t on t.id = a.id 
set a.sales_ranking = t.ROW_NUM
where a.sales_proportion > 0 and a.cycle  = '2021年01月'


update sales_performance set sales_ranking = CONCAT(FORMAT(sales_ranking*100/13,2),'%')  where cycle  = '2021年01月' and sales_ranking>0 

-- 备份回滚数据使用
update sales_performance set sales_proportion = CONCAT(FORMAT(person_actual/com_actual*100,2),'%') where cycle  = '2021年01月'
update sales_performance set sales_ranking = sales_proportion where cycle  = '2021年01月'

update sales_performance a 
left join user u on u.user_name = a.user_name 
left join department d on d.dept_name = a.bm_name 
set a.user_id = u.user_id, a.bm_id = d.dept_id
where a.cycle  = '2021年01月' 

update sales_performance a 
left join user u on u.user_id = a.user_id 
set a.user_name = u.user_name, a.bm_id = u.dept_id
where a.cycle  = '2021年01月' 

update sales_performance a  
left join department d on d.dept_id = a.bm_id
set a.bm_name = d.dept_name
where a.cycle  = '2021年01月' and a.bm_id !=0

select * from sales_performance_director where cycle  = '2021年01月' order by id desc;

update sales_performance_director a 
left join user u on u.user_name = a.user_name 
left join department d on d.dept_name = a.bm_name 
set a.user_id = u.user_id, a.bm_id = d.dept_id
where a.cycle  = '2021年01月' 
-- 公共考核分数
select * from inhe_public_kh where cycle = '2021年01月'
-- 一键纠正分数，已有自评分和审核分，未正确计算核定分及总分
select * from inhe_kpikh where ZQ='2021年01月' and final_score = '0.00';

update inhe_kpikhmx1 set hdf = format(shf*byzd5/100,2) where hdf= '' and jldh =11510;

select sum(hdf) from inhe_kpikhmx1 where jldh = 11510;

update inhe_kpikh set zhd ='88.00',final_score='88.00' where jldh = 11510;

-- 
select jldh,format(sum(hdf),2) zhd from inhe_kpikhmx1  where jldh >= 11313 group by jldh order by jldh

select name,jldh,zhd,final_score from inhe_kpikh where jldh >= 11313

select * from user where uid = 785
select * from inhe_kpikh where uid = 785

-- 
update sales_performance set bm_name = '国际三部 INTL.Business Dept.3' where cycle  = '2021年01月' and id in(375,374,373,372);

update sales_performance set user_name = '徐周泽弈' where cycle  = '2021年01月' and id = 374
select * from user where user_name like '%张新海%'
-- inhe_hr_flow_prcs UPDATE
select * from inhe_kpikh where final_score is null  and zq = '2021年01月'
select * from inhe_kpikh a where a.final_score is null 
and a.zq = '2021年01月' and a.stu = 1
and not EXISTS (select * from inhe_hr_flow_prcs p where p.flow_id = a.flow_id)

select * from kpikh a where (a.bt = '2021年01月' or a.bt = '2020第4季度') and a.stu = 1
and not EXISTS (select * from inhe_hr_flow_prcs p where p.flow_id = a.flow_id)

select * from inhe_hr_flow_prcs where flow_id = 'YK20200927069'   

insert into inhe_hr_flow_prcs (flow_id,flow_type_id,user_id,prcs_flag,prcs_num,flow_prcs,from_user,create_time,user_bu)
select a.flow_id,'F05' flow_type_id,a.shr user_id,1 prcs_flag,0 prcs_num,'P' flow_prcs,a.USER_ID from_user,a.RQ create_time,a.user_bu 
from inhe_kpikh a where a.final_score is null 
and a.zq = '2021年01月' and a.stu = 1
and not EXISTS (select * from inhe_hr_flow_prcs p where p.flow_id = a.flow_id)
-- jd
insert into inhe_hr_flow_prcs (flow_id,flow_type_id,user_id,prcs_flag,prcs_num,flow_prcs,from_user,create_time,user_bu)
select a.flow_id,'F04' flow_type_id,a.shr user_id,1 prcs_flag,0 prcs_num,'P' flow_prcs,a.USER_ID from_user,a.RQ create_time,a.user_bu 
from kpikh a where (a.bt = '2021年01月' or a.bt = '2020第4季度') and a.stu = 1
and not EXISTS (select * from inhe_hr_flow_prcs p where p.flow_id = a.flow_id)
-- user_id
update kpikh a left join user u on u.uid = a.uid set a.user_id = u.user_id where a.bt = '2021年01月' or a.bt = '2020第3季度'
select * from kpikh  a where a.bt = '2021年01月' or a.bt = '2020第3季度'

YK20200927039
YK20200927041
YK20200927060
YK20200927061
YK20200927069

insert into inhe_hr_flow_prcs VALUES (null,'YK20200927039','F05','meterw',1,0,'P','INHE-0260','2020-09-27 15:10:12','INHE');
insert into inhe_hr_flow_prcs VALUES (null,'YK20200925031','F05','INHE-0247',1,0,'P','INHE-0928','2020-09-25 17:42:42','INHE');
insert into inhe_hr_flow_prcs VALUES (null,'YK20200926003','F05','INHE-0144',1,0,'P','INHE-0532','2020-09-26 11:30:49','INHE');
insert into inhe_hr_flow_prcs VALUES (null,'YK20200927016','F05','INHE-0599',1,0,'P','INHE-0848','2020-09-27 09:39:26','INHE');
insert into inhe_hr_flow_prcs VALUES (null,'YK20200927018','F05','INHE-0662',1,0,'P','INHE-0909','2020-09-27 09:56:50','INHE');
insert into inhe_hr_flow_prcs VALUES (null,'YK20200927030','F05','INHE-0711',1,0,'P','INHE-0685','2020-09-27 14:12:47','INHE');

update sales_performance a 
left join user u on SUBSTR(u.user_name,1,2) = a.user_name 
set a.user_id = u.user_id
where a.cycle  = '2021年01月'  and a.user_id is null

update sales_performance a 
left join user u on u.user_id = a.user_id
set a.user_name = u.user_name,a.bm_id = u.dept_id
where a.cycle  = '2021年01月' 

select a.*,u.user_id uuid,u.user_name uuname from sales_performance a 
left join user u on SUBSTR(u.user_name,1,3) = a.user_name 
where a.cycle  = '2021年01月' 

update sales_performance_director a 
left join user u on SUBSTR(u.user_name,1,3) = a.user_name 
set a.user_id = u.user_id
where a.cycle  = '2021年01月' 

select a.*,u.user_id uuid,u.user_name uuname from sales_performance_director a 
left join user u on SUBSTR(u.user_name,1,3) = a.user_name 
where a.cycle  = '2021年01月' 

update sales_performance_director a 
left join user u on u.user_id = a.user_id
set a.user_name = u.user_name,a.bm_id = u.dept_id
where a.cycle  = '2021年01月' 

select * from user where user_id ='INHE-0375'

update sales_performance a 
left join department d on d.dept_id = a.bm_id 
set a.bm_name = d.dept_name
where a.cycle  = '2021年01月' 

update sales_performance_director a 
left join department d on d.dept_id = a.bm_id 
set a.bm_name = d.dept_name
where a.cycle  = '2021年01月' 

select GROUP_CONCAT(user_name) from user where user_id in('lhz','INHE-0051')

select * from user where user_id ='INHEGRID-0070'

show tables like '%sms%'
select * from sms_body where from_id ='INHE-0876' order by body_id desc limit 0,30
select * from sms

select * from user where user_name like '%陈志超%'
select * from kpikh where bt = '2020第3季度' and name like '%戴%'


update inhe_kpigl a 
left join user u on u.user_id = a.user_id 
set a.user_name = u.user_name

select a.* from inhe_kpigl a 
left join user u on u.user_id = a.user_id 
where u.dept_id = 0

select * from kpigl k where not EXISTS (
select* from kpikh  a where (a.bt = '2021年01月' or a.bt = '2020第3季度' ) or a.user_id = k.user_id )

select count(*) from kpigl 
select count(*) from kpikh where (bt = '2021年01月' or bt = '2020第3季度' ) 

select * from kpigl g
where not EXISTS (select * from (
select u.user_id from kpikh k  
left join user u on u.uid = k.uid
where (k.bt = '2020第4季度' or k.bt = '2021年01月') and k.stu != 4
) t where t.user_id = g.user_id)
and
user_id not in(select user_id from user u where u.is_kh = 'T' or dept_id = 0 )

-- 更新考核状态
select * from inhe_kpikh where zq = '2020年12月' and stu =1 and user_bu = 'INHE'  and stu =2 and
select * from inhe_kpikh where zq = '2021年01月' and user_bu = 'WITLINK' and final_score <0 and user_bu = 'WITLINK' 
-- select * from inhe_kpikhmx3 where jldh = 13531
select * from kpikh where bt = '2021年01月' and user_bu = 'INHE' and stu =1
select * from kpikh where bt = '2020第3季度' and user_bu = 'WITLINK' and stu !=3

select * from inhe_system_admin where user_id = 'INHE-0900'
select * from user where user_id = 'INHE-0797'
select * from user where user_name like '%江丽%'

select * from inhe_kpikhmx3 where jldh = 13007  12974
select * from inhe_kpikh where user_id='INHE-0073'  0500 -- INHE-0955  szdfm  INHE-0870  INHE-0701  YK20201127001  YK20201127003  YK20201127036
select * from inhe_kpikhmx1 where jldh = 12783
select * from inhe_hr_flow_prcs where flow_id = 'YK20201127036'
select * from inhe_oa_paras where type like '%kpi_%'

select * from inhe_kpikh where flow_id in ('YK20201127001','YK20201127003','YK20201127036','YK20201127135','YK20201129001','YK20201126041');
select * from inhe_hr_flow_prcs where flow_id in ('YK20201127001','YK20201127003','YK20201127036','YK20201127135','YK20201129001','YK20201126041');

select * from inhe_kpikh where name like '%吴楠%'
select * from user where user_name like '%吴楠%'
select * from user where is_kh = 'T' and dept_id != 0
select * from interviewmx2 where rzgh = 'INHE-0978'
select * from inhe_hr_flow_prcs where flow_id = 'YK20201231051'
select * from inhe_kpikh where name like '%林丹敏%'
select * from inhe_kpikh where jldh = 13226

select * from department - 193 - 197  180
select * from inhe_kpikh where zq = '2021年01月' and 
select * from kpikh where  bt = '2020第4季度' 
select * from inhe_kpikh_1105
select * from inhe_kpikh_tmp
select * from inhe_kpikh where bm is null

update inhe_kpikh a left join user u on u.user_id = a.user_id 
left join department d on u.dept_id = d.dept_id 
set a.bm = d.dept_name
where zq = '2021年01月'

update inhe_kpikh a left join user u on u.uid = a.uid 
set a.user_id = u.user_id
where zq = '2021年01月'


update kpikh a left join user u on u.uid = a.uid 
left join department d on u.dept_id = d.dept_id 
set a.bm = d.dept_name
where  bt = '2020第4季度' 

select * from inhe_kpikh where lx=3 and zq = '2021年01月' and final_score != ''
select * from inhe_kpikhmx3 where jldh = 13480

select * from kpikh where name like '%章春%'

select * from sales_performance where cycle  = '2021年01月' and user_id = 'INHE-0946'  -- 39.70%
-- update inhe_kpikhmx3 a left join inhe_kpikh b on a.jldh = b.jldh and b.zq = '2021年01月'
left join sales_performance c on c.user_id = b.user_id and c.cycle  = '2021年01月'
set a.qk1 = c.reach_rate,a.qk1_2 = c.sales_ranking, a.qk2=c.completion_rate
where b.zq = '2021年01月' and c.cycle  = '2021年01月' and qk1 = '' 


select a.jldh,a.qk1,a.qk1_2,a.qk2,c.reach_rate,c.sales_ranking,c.completion_rate from  inhe_kpikhmx3 a left join inhe_kpikh b on a.jldh = b.jldh and b.zq = '2021年01月'
left join sales_performance c on c.user_id = b.user_id and c.cycle  = '2021年01月'
where b.zq = '2021年01月' and c.cycle  = '2021年01月' and qk1 = '' 


select * from inhe_kpikh where name like '%刘治%'
select * from inhe_hr_flow_prcs where flow_id = 'YK20210129024'
select * from inhe_kpikh where name like '%姜久%'
select * from user where user_id = 'INHE-0825'
select * from inhe_kpimb where dh = 1080
select * from inhe_kpikhmx3 where jldh = 13504
select * from inhe_kpimbmx1 where type = '' or type is null
select * from inhe_kpimbmx1 where byzd5 = ''

select max(LENGTH(byzd2)),max(LENGTH(byzd3)),max(LENGTH(byzd4)),max(LENGTH(byzd5)),
max(LENGTH(byzd6)),max(LENGTH(byzd7)),max(LENGTH(byzd8)),max(LENGTH(byzd9)),max(LENGTH(byzd10))
 from inhe_kpimbmx1

select * from inhe_kpigl where user_name like '%陈雁%'
select * from user where user_name like '%陈雁%'  INHE-0634
select * from inhe_kpimb  

select * from user where user_name like '%张祥%'
select * from interviewmx2 where rzgh= 'INHE-0860'
select * from rsyd where gh= 'INHE-0860'

select * from user where dept_id != 0 and user_bu = 'INHE' and user_id in (
select user_id from inhe_kpigl where user_id not in (select user_id from inhe_kpikh where zq = '2021年01月'))

select * from user where user_id = 'INHE-0364'
select * from department where dept_id = '83'