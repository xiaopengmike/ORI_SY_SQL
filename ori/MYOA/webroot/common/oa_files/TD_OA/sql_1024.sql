-- 20191024
alter table company add USER_BU VARCHAR(30);
alter table djgz add USER_BU VARCHAR(30);
alter table quanx add USER_BU VARCHAR(30);

alter table inhe_kpikh add USER_ID VARCHAR(30);
alter table inhe_kpigl add USER_BU VARCHAR(30);
alter table inhe_kpikh add USER_BU VARCHAR(30);
alter table inhe_kpimb add USER_BU VARCHAR(30);

alter table kpikh add USER_ID VARCHAR(30);
alter table kpigl add USER_BU VARCHAR(30);
alter table kpikh add USER_BU VARCHAR(30);
alter table kpimb add USER_BU VARCHAR(30);

alter table sales_performance add USER_BU VARCHAR(30);
alter table inhe_public_kh add USER_BU VARCHAR(30);

alter table gzjj add USER_BU VARCHAR(30);
alter table lzsq add USER_BU VARCHAR(30);
alter table rsyd add USER_BU VARCHAR(30);
alter table rzdj add USER_BU VARCHAR(30);
alter table interview add USER_BU VARCHAR(30);

-- ---------------------------------------- --
select * from company
SELECT * from user
select * from department

select * from company
select * FROM inhe_hr_flow_prcs
SELECT * from inhe_hr_flow_type
select * from inhe_hr_prcs_type
 
select u.uid, u.user_id, u.user_name, u.dept_id, u.USER_BU,
                d.dept_name, d.dept_parent, d.manager, d.leader1, d.leader2 
                from user u
                left join department d on d.DEPT_ID = u.DEPT_ID 
where 1=1 and u.user_id = 'fxs'


select * from company where user_bu in ('inhe','')
-- 定薪档级工资
select * from djgz;
-- 流程查看数据权限控制(待完善)
select * from quanx;
-- 系统数据管理员权限，用于特殊情况维护系统数据（一般关闭）
select * from inhe_system_admin;
select * from user where user_id = 'fxs'

-- 考核部分 -----------------------------------------------------------------------
select * from kpigl ;
select * from kpikh ;
select * from kpimb ;

select * from sales_performance ;
select * from inhe_public_kh ;

select * from inhe_kpigl;
select * from inhe_kpikh;
select * from inhe_kpimb;

select * from company;

select k.*,c.company from kpimb k 
			left join company c on c.user_bu = k.user_bu 

-- -------------------------
-- 耐吉季度考核数据 26
select * from crs_tabledata2638 where col31741='2019第3季度';
-- 电网季度考核数据 20
select * from crs_tabledata2366 where col28809='2019第3季度';
-- 深圳季度考核数据 156
select * from crs_tabledata1484 where col19695='2019第3季度';


select * from kpikh ;
desc kpikh;

desc crs_tabledata1484;


select * from inhe_system_admin



select * from inhe_public_kh

select * from company


select * from sales_performance
update sales_performance set user_bu ='INHE'

select * from inhe_kpikh

select * from inhe_kpimbmx1


select * from user where user_id='ysr'


update inhe_kpigl a
left join inhe_kpimb b on b.name = a.name
set a.id = b.id



select k.*, d.dept_name, c.company
		from kpigl k 
		left join user u on u.user_id = k.user_id
		left join department d on d.dept_id = u.dept_id
		left join company c on c.user_bu = k.user_bu 


select * from  inhe_kpikh k where  shr = 'admin1' and  stu = '1' 

select k.bm,count(k.bm) from inhe_kpikh k where  shr = 'admin1' and  stu = '1' 
group by bm 



select k.bm,count(k.bm) from inhe_kpikh k where  shr = 'admin1' and  stu = '1' 
and final_score>=90
group by bm 



select * from inhe_kpikh where shr = 'admin1' and  stu = '1' 

select * from inhe_kpikh order by id desc
select STU from inhe_kpikh where flow_id =

select * FROM inhe_hr_flow_prcs order by id desc

select USER_BU from user where UID = '20001'

select * from inhe_kpikh;
select * from inhe_kpikhmx1;
select * from inhe_kpikhmx2;
select * from inhe_kpikhmx3;

select * from inhe_kpimb
truncate table inhe_kpikhmx3

select * from inhe_hr_flow_prcs;
select * from inhe_hr_flow_type;
select * from inhe_hr_prcs_type;

select * from company;


select * from inhe_system_admin;


create table inhe_kpigl_1028 as 
select * from inhe_kpigl

select * from inhe_kpimb where name like '%助理工程师%'

select * from inhe_kpigl where id is null or id = ''

select * from inhe_kpimb where name like '%实验室/工程师/助理工程师%'

select name from inhe_kpigl where id is null or id = ''
group by name
 
select * from inhe_kpigl where name like '%陈金刚%'


select * from inhe_kpimb where name like '%陈金刚%'


update inhe_kpigl a
left join inhe_kpimb b on b.name = a.name
set a.id = b.id

国内业务部  -- wu 
会计部经理  --  待补录
孔维红  -- 待补录
商务助理  -- wu 
实验室/工程师/助理工程师
实验室（陈金刚） -- 
实验室经理   -- >  实验室（经理）  余祥林
项目组长（大区经理） -- 待补录
业务经理（小区经理）/业务助理   -- 待补录
智能配电事业部 (试用期员工)  -- 待补录


select * from user where dept_id != ''

select * from inhe_kpikh

select * from inhe_kpimb
36	1	1002	文案策划岗	测试（编号）	2019-10-23 15:40:18		


select * from inhe_kpikh

select * from inhe_kpikhmx1

select * from inhe_kpikhmx2


select * from inhe_kpimbmx2
2001	1	1	111	222	月度	50	333	444	555		


alter table inhe_kpimbmx2 add user_id VARCHAR(20);
alter table inhe_kpimbmx2 add uid VARCHAR(20);

 --  20191029
-- 跨部门
select * from user

select * from inhe_system_admin


select dept_id_other from user where dept_id_other != ''
48


select  *  from user where CHARINDEX('48',dept_id_other)>0

SELECT user_id FROM user WHERE find_in_set('18', dept_id_other); 

select MANAGER,LEADER1  from department

select * from department
where dept_name like '%国际%';

select * from sales_performance where user_name = '系统管理员1';


select * from user where  user_name like '%岑%'
user_id = 'INHE-0285'


select * from department where  user_id = 'INHE-0285'
select * from inhe_kpikhmx3 

select * from inhe_kpikhmx1 

select * from inhe_kpikh
select * from inhe_hr_flow_prcs
select * from inhe_hr_prcs_type

select * from inhe_hr_flow_prcs

赵郑洁  辛方腾  陈嘉豪  何森华   


select * from user where user_id in ('admin1','ysr')


select * from inhe_system_admin 
select is_admin from inhe_system_admin where user_id = 'admin1' 


insert into inhe_system_admin VALUES (null, 'INHE-0475','INHE,INHENERGY','1'),
(null, 'INHE-0808','INHENERGY','1'),(null, 'INHEGRID-0018','INHEGRID','1');




select * from inhe_kpikh


select user_id,count(user_id) sum  from inhe_kpigl
group by user_id
order by sum desc

order by sum desc


INHE-0070
INHE-0191
szyuxl
yxw
INHE-0807
INHE-0450
INHE-0571


select * from inhe_kpigl 
where user_id in(
'szyuxl',
'yxw',	
'INHE-0807',	
'INHE-0450',	
'INHE-0571',	
'INHE-0866',	
'INHE-0413',	
'INHE-0070',	
'INHE-0829',	
'INHE-0649',	
'INHE-0418',	
'INHE-0677',	
'INHE-0661',	
'INHE-0338',	
'INHE-0803',	
'INHE-0191',	
'INHE-0656',	
'INHE-0423',	
'INHE-0870',	
'INHE-0473',	
'INHE-0202',	
'INHE-0701',	
'INHE-0445',	
'INHE-0666'
)


select * from inhe_kpikh  k


select k.bm,count(k.bm) total from inhe_kpikh k where  shr = 'admin1'  and uid != '20001' 
    group by bm 

select * from user where user_id ='INHE-0144'

select * from  inhe_kpikh k where  shr = 'INHE-0144'  and uid != '620' 

--  20191030 考核合计校准
create table inhe_kpikh_tmp as select * from inhe_kpikh;
create table inhe_kpikhmx1_tmp as select * from inhe_kpikhmx1;
select * from inhe_kpikh;


select * from inhe_kpikhmx1 where jldh= '10001'
update inhe_kpikhmx1 set sum = 0.00 where jldh= '10029'
select * from inhe_kpikhmx2 where jldh= '10031'
select * from inhe_kpikhmx3 where jldh= '10031'
select * from inhe_kpikh where jldh= '10029'

qz 			40
byzd3		88.35   ** 
sum1		88.35   ** 
sum2		78.44   ** 
SHF			81.15   ** 
hdf     32.46   ** 
zhd     77.21   ** 
yfhj    81.15   ++
final_score  77.21  ++

inhe_kpikhmx1  KPI考核
ZP      86   88  XX 
SUM  78.44   ++ 
SHF   90    85  XX
HDF     40.50   4.25   ++ 
BYZD5  45    5   XX 

inhe_kpikhmx2   研发KPI考核
ZP   90   87  xx 
SUM   88.35  88.35  ++ 
SHF  85   78  xx 
HDF  38.25   42.90  ++ 
BYZD5   45   55  XX 










