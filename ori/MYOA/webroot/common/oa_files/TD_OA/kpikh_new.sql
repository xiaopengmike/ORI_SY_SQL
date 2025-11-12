select c.* from inhe_kpigl a left join inhe_kpimb b on b.id = a.id left join inhe_kpimbmx1 c on c.dh = b.dh where a.user_id = 'admin1'
select * from inhe_kpigl a left join inhe_kpimb b on b.id = a.id  where a.user_id = 'admin1'

select * from inhe_kpimbmx3 where user_id='admin1' 

select * from inhe_kpimb  where lx=4

select * from inhe_kpigl  where lx=2


select * from inhe_kpikhmx1 where byzd5<1 and byzd5>0
select * from inhe_kpikhmx2 
select * from inhe_kpikhmx3;

select * from inhe_kpikh where  zq='2020年04月'
select * from kpikh where bt='2020第1季度'   -- uid user_id

select * from user

select * from inhe_kpikh where  zq='2020年03月'

select *  from inhe_kpimb where dh in(1023,1024,1025,2004)

select dh,count(1) sum from inhe_kpimbmx1 where byzd5 = 0  group by dh

select * from inhe_kpikh where jldh >11094

select * from sales_performance order by id  desc
select * from sales_performance where user_id = 'INHE-0900' and cycle = '2020年04月'
order by id  desc limit 0,1

select * from inhe_kpimbmx1

select * from inhe_kpikh a 
left join inhe_kpikhmx3 b on a.jldh =b.jldh

where a.zq = '2020年04月' 


select * from inhe_kpikhmx3 a
left join inhe_kpikh b on a.jldh =b.jldh
where b.zq = '2020年04月' 

select * from sales_performance

select * from inhe_kpikhmx3 a
left join sales_performance b on a.name = b.user_name
where name != ''

select jldh,qk1,qk1_2,qk2,QK2_2,b.user_name,b.reach_rate,b.sales_ranking,b.completion_rate from inhe_kpikhmx3 a
left join sales_performance b on a.name = b.user_name and b.cycle = '2020年04月'
where name != ''

select jldh,qk1,qk1_2,qk2,QK2_2,b.user_name,b.reach_rate,b.sales_ranking,b.completion_rate from inhe_kpikhmx3 a
left join sales_performance b on a.name = b.user_name and b.cycle = '2020年04月'
where name != ''
order by jldh desc

select jldh,qk1,qk1_2,qk2,QK2_2,b.user_name,b.reach_rate,b.sales_ranking,b.completion_rate from inhe_kpikhmx3 a
left join sales_performance b on a.name = b.user_name and b.cycle = '2020年04月'
where name != '' and user_name !=''
order by jldh 

select * from interview where user_bu = 'INHEGRID'
select * from inhe_hr_flow_prcs where flow_id = 'MS20200407001'

select * from inhe_system_admin where type = 'kh_new'

select * from inhe_kpimb
select * from inhe_kpimbmx3

select * from  inhe_kpikh where name like '%易%'
select * from  inhe_kpikhmx1 where JLDH=11095

select * from inhe_kpikh where UID='1704' and ZQ='2020年04月'
select * from sales_performance where user_name ='邓彧'

select * from department where dept_name like '%智能%'
desc inhe_kpikhmx1

select u.dept_id,d.dept_name,a.* from inhe_kpikh a
left join user u on u.uid = a.uid
left join department d on d.dept_id = u.dept_id
where ZQ='2020年04月' and d.dept_name!=a.bm

select * from user where user_name like '%丁%'  INHE-0144  58

select * from inhe_public_kh where user_bu = 'INHEGRID'

select @rd := @rd+1 as ROW_NUM, b.* from (select @rd:=0 row_init,  
	d.DEPT_NAME, u.USER_NAME, u.USER_PRIV_NAME, jd.user_bu, c.company, yd.ID, yd.JLDH, yd.ZQ, yd.BT, yd.STU, yd.final_score P_SCORE, jd.ID jd_id, jd.sj26 S_SCORE, jd.sj11 B_SCORE 
	from user u 
	left join department d on d.DEPT_ID = u.dept_id
	LEFT JOIN inhe_kpikh yd on yd.NAME = u.USER_NAME and yd.BM = d.DEPT_NAME AND yd.ZQ = '2020年04月'
	LEFT JOIN kpikh jd on jd.NAME = yd.NAME and jd.BM = yd.BM and jd.BT = yd.BT 
	left join company c on c.user_bu = yd.user_bu
  WHERE 1=1 and u.is_kh is null and yd.STU != 4 
and (d.dept_id = '58' or d.dept_parent = '58' or d.dept_id in(162,163,164)  or yd.shr = 'INHE-0144' ) and u.uid != '620'
 ) b order by ZQ DESC,company,DEPT_NAME

select * from user where user_id = 'szdfm'

select * from user where user_name like '% %'
select * from department where dept_id = 155

select * from province;
select * from city;
select * from area;
-- select * from country_area;
select * from inhe_public_kh  where cycle = '2020年04月' order by id  desc

select DISTINCT a.NAME,a.TIME,a.ZW,a.XB,a.GH,a.TIME1,a.MZ,a.HYZK,a.YX,a.ZY,a.TIME3,a.JG,a.SHR,a.USER_BU,cc.company,mz.NAME mzDesc, hy.NAME hyDesc, a.stu, d.dept_name from rzdj a  
  left join company cc on cc.user_bu = a.user_bu 
  left join department d on d.dept_id = a.BM 
  left join interviewmx2 b on a.GH=b.RZGH  
  left join minz mz on mz.ID = a.MZ 
  left join huny hy on hy.ID = a.HYZK
where 1=1 and a.user_bu in("WITLINK")

select * from user where user_name like '%测试%'

select * from inhe_kpikh_library

-- 
select u.user_id,u.user_byid,u.user_name,u.dept_id,u.user_bu,d.dept_name,d.user_bu d_user_bu from user u 
left join department d on  u.dept_id = d.dept_id
where u.dept_id != '0' and (d.user_bu = 'INHEGRID' or u.user_bu  = 'INHEGRID' )
order by u.user_byid asc

--
select * from inhe_kpimb
select * from inhe_kpikh 
where zq = '2020年05月'

select * from inhe_kpikh where uid=1605 and lx=3 and jldh = 11004
select * from inhe_kpikhmx1 where jldh = 11034
select * from inhe_kpikhmx2 where jldh = 11034
select * from inhe_kpikhmx3 where jldh = 11004

select a.JLDH, a.QK, a.ZP, a.SUM, a.FJMC, a.SHF, a.HDF, 
		a.BYZD1 , a.BYZD2, a.BYZD3, a.BYZD4, a.BYZD5, a.BYZD6, a.BYZD7, a.BYZD8, a.BYZD9, a.BYZD10
		from inhe_kpikhmx1 a 
		left join inhe_kpikh b on a.JLDH = b.JLDH 
		where a.JLDH=11096

select a.JLDH, a.QK, a.ZP, a.SUM, a.FJMC, a.SHF, a.HDF, 
		a.BYZD1 , a.BYZD2, a.BYZD3, a.BYZD4, a.BYZD5, a.BYZD6, a.BYZD7, a.BYZD8, a.BYZD9, a.BYZD10
		from inhe_kpikh b 
		left join inhe_kpikhmx1 a on a.JLDH = b.JLDH 
		where a.JLDH=11096

select k.BM, k.NAME, k.ZQ, k.RQ, m.* from inhe_kpikh k left join inhe_kpikhmx1 m on m.JLDH = k.JLDH where k.JLDH = '11096' 

select * from inhe_kpimbmx3 where dh= 3004

select * from sales_performance_director

select * from inhe_public_kh 

select * from inhe_kpimbmx1 where dh = 1116  type = 'extra' and  
select * from inhe_kpimbmx2 where dh = 1116   lx =2 and dh = 2007 
select * from inhe_kpimbmx3
select * from inhe_kpimb where dh = 2008

normal
extra

select max(DH) maxVal from inhe_kpimb where LX = '1'
select max(DH) maxVal from inhe_kpimb where LX = '2'


select * from (
select dh, CHAR_LENGTH(byzd6) maxLength from inhe_kpimbmx1 
-- where  lx =2 
) t order by t.maxLength desc

select * from inhe_kpikh  where jldh = 11096 
select * from inhe_kpikhmx1 where jldh = 11096 order by type, byzd1
select * from inhe_kpikhmx2

select * from inhe_kpimb where dh=2004 order by name

select * from inhe_kpikh_library where type = 'select'
desc inhe_kpikh_library

select * from inhe_kpigl a 
           left join inhe_kpimb b on b.id = a.id 
          where a.user_id = ''

select * from inhe_kpikh where user_id='INHE-0900'  lx =2 
select * from inhe_kpikhmx3 where jldh = '11106'


select user_bu from department GROUP BY user_bu

-- 1118
select * from inhe_kpimbmx1  where dh in(1130,1131,1132,1133)
select * from inhe_kpimb where dh in(1130,1131,1132,1133)
select * from inhe_kpimbmx1 where user_bu = 'INHEGRID' and uid = 1706;
项次	类别	考核指标	考核周期	权重	定义	评分标准	目标值	输出依据	数据来源  -- 文档
项次	类别	指标名称	考核周期	权重  定义	目标值	输出依据	评分标准	数据来源	-- 数据库							
1			2				3					4					5			6			7				8						9					10
select * from inhe_kpikh where user_bu = 'INHEGRID' and uid = 1706

select * from inhe_kpigl

select * from inhe_oa_paras
select * from inhe_form_data

select m.* from inhe_kpikhmx2 m where m.JLDH = '11103'
select * from inhe_kpikhmx1 m where m.JLDH = '11103'

select * from inhe_kpimbmx2 where  DH='2006' and LX=2 and user_id='' and uid=''

select * from inhe_kpikh_library
select * from sales_performance where bm_name ='企业管理与信息化部' and cycle='2020年05月'
select * from sales_performance_director

 select p.* from sales_performance p left join department d on d.dept_name = p.bm_name where d.dept_id = '17' and cycle = '2020年05月' 

select * from inhe_public_kh where user_bu = 'INHEGRID'

select * from inhe_system_admin where type = 'F05'

select * from inhe_oa_paras where type = 'process_status'

select m.*,n.paras_desc statusDesc from inhe_kpikh m left join inhe_oa_paras n on n.paras_value=m.stu and n.type='process_status' and is_used = '1'
where 1=1 and m.name = '易双荣'


select * from inhe_kpikh where user_bu = 'INHEGRID'

select kk.name,kk.zhd,kk.final_score,k.total,format(kk.zhd-IFNULL(k.total,0), 2) from inhe_kpikh kk 
		left join user u on u.uid = kk.uid  
		left join inhe_public_kh k on k.user_id = u.user_id  and k.cycle = kk.zq  
where   kk.zq = '2020年04月'

select * from inhe_kpikh where name like '%傅秋婵（Autumn Fu）%'

update inhe_kpikh kk 
left join user u on u.uid = kk.uid  
left join inhe_public_kh k on k.user_id = u.user_id  and k.cycle = kk.zq 
	 set final_score=format(kk.zhd-IFNULL(k.total,0), 2) where kk.zq = ''

select * from inhe_kpikh where lx = 4
select * from inhe_kpikhmx3

update inhe_kpikh k 
left join inhe_kpikhmx3 kk on kk.jldh = k.jldh
set k.zhd = kk.hdf
where k.lx = 4


select * from sales_performance where cycle  = '2020年05月' order by id desc;
update sales_performance a 
left join user u on u.user_name = a.user_name
left join department d on d.dept_name = a.bm_name
set a.bm_id = d.dept_id,a.user_id = u.user_id


alter table inhe_kpimbmx1 add type varchar(20);
alter table inhe_kpimbmx2 add type varchar(20);

alter table inhe_kpikhmx1 add type varchar(20);
alter table inhe_kpikhmx2 add type varchar(20);

desc inhe_kpikhmx2

select * from sales_performance where cycle  = '2020年05月' order by id desc;

-- ALTER TABLE inhe_kpikhmx2 DROP PRIMARY KEY,ADD PRIMARY KEY (JLDH,BYZD1,type);

select * from inhe_kpikh where jldh = 11110
select * from inhe_kpikhmx3 where jldh = 11109

select * from user where user_name like  '%刘长%'

select * from inhe_kpikh

select dh,user_id,name,jldh,zhd,final_score,lx 
from inhe_kpikh k
where k.zq = '2020年05月' and k.lx = 3


select jldh,format(sum(hdf),2) zhd from inhe_kpikhmx1 kk
where kk.jldh in(select jldh
from inhe_kpikh k
where k.zq = '2020年05月' and k.lx = 1)
group by jldh


select * from inhe_oa_paras where type = 'process_status'


select * from inhe_kpikhmx3
desc inhe_kpikhmx2

alter TABLE inhe_kpikhmx2 modify BYZD1 int(11);

select * from inhe_kpikhmx1
select * from inhe_kpikhmx2


select a.jldh,a.name,a.zhd,a.final_score,t.total from inhe_kpikh a
left join (select jldh,format(sum(hdf),2) total from inhe_kpikhmx1 group by jldh) t on t.jldh = a.jldh 
where a.zq = '2020年05月' and a.lx = 1

select * from inhe_kpikhmx3
select * from inhe_kpikhmx2 kk
where kk.jldh in
(
select jldh from inhe_kpikh k
where k.zq = '2020年05月' and k.lx = 2
)

select jldh,qz from inhe_kpikh k
where k.zq = '2020年05月' and k.lx = 2

select * from inhe_hr_flow_prcs
YK20200528091 F05 meterw 1 0 P INHE-0260 2020-05-28 11:53:37 INHE 

-- insert into inhe_hr_flow_prcs VALUES(null,'YK20200528096','F05','szdfm',1,0,'P','INHE-0156','2020-05-28 13:51:45','INHE');

select * from  inhe_kpikh set zhd='79.50',final_score='79.50' where zq = '2020年05月' and name like '%左亚%'


select * from inhe_kpikh  where zq = '2020年05月' and name like '%喻%'
select * from inhe_hr_flow_prcs where flow_id = 'YK20200528091'
select * from inhe_kpikh where 1=1 and zq='2020年05月' and (SHR='meterw' and STU='1') or (SHR1='meterw' and STU='2') ORDER BY RQ desc 

select * from inhe_kpikhmx1 WHERE jldh = 11520

select * from user where user_id in('meterw','admin1')

update inhe_kpikh set final_score = null where zq = '2020年05月' and name like '%喻%'










