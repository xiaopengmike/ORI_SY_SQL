-- OA-11
-- 主要表查询
select * from company;
select * from hukou;

select * from interview;
select * from interviewmx1
select * from interviewmx2

SELECT * FROM wenhua
select * from canzhao2
select * from yijian1
select * from fujian 

select * from lzsq
select * from rzdj
select * from gzjj
select * from rsyd 

-- 考核
select * from inhe_kpimb
select * from inhe_kpimbmx1
select * from inhe_kpimbmx2
select * from inhe_kpimbmx3

select * from inhe_kpikh
select * from inhe_kpikhmx1
select * from inhe_kpikhmx2
select * from inhe_kpikhmx3
TRUNCATE table inhe_kpikhmx3

-- 审批记录表
select * from inhe_hr_flow_prcs order by id desc;
-- 审批流程分类对照表
select * from inhe_hr_flow_type;
-- 审批状态对照表
select * from inhe_hr_prcs_type;

select * from sales_performance ORDER BY ID DESC;
SELECT * FROM inhe_system_admin;

-- 
select * from inhe_kpikhmx1
select * from inhe_kpikhmx2

select * from inhe_kpikh 
select * from inhe_public_kh
--  ---- --------------------------------------------------

select c.name,c.final_score,d.final_score from inhe_kpikh_1105 c 
left join inhe_kpikh d on c.id = d.id

select * from inhe_kpikh

select * from inhe_system_admin;
alter table inhe_system_admin add type VARCHAR(20);
-- -----

SELECT u.uid,d.DEPT_NAME, u.USER_NAME, yd.ID, yd.JLDH, yd.STU, yd.final_score 
            FROM  USER u
            LEFT JOIN department d ON d.DEPT_ID = u.dept_id
            LEFT JOIN inhe_kpikh yd ON yd. NAME = u.USER_NAME AND yd.BM = d.DEPT_NAME AND yd.ZQ = '".$zqVal."'
            WHERE u.is_kh IS NULL
            AND ( d.dept_id = '46' OR d.dept_parent = '46' )
            AND u.uid != '20065'
-- 20191114
-- 1.建立语种数据表(页面栏位参数表)
select * from inhe_oa_paras
select * from flow_step

select * from djgz
select * from Interviewmx1;
select * from Interviewmx2;

select * from user where user_id = 'admin1'
update user set user_bu='INHE,INHENERGY' where user_id = 'admin1'

select * from inhe_oa_paras

insert into inhe_oa_paras(paras_group, type, flow_bz, paras_desc, paras_value)
select id paras_group, 'lzsq' type,bz flow_bz,role paras_desc,shr paras_value from canzhao4

update inhe_oa_paras set user_bu = 'ALL' where user_bu ='all'

select * from company

select * from canzhao
select * from canzhao2
select * from canzhao3
select * from canzhao4

select * from minz
select * from hukou
select * from huny

select * from flow_step

select * from inhe_system_admin



select * from inhe_kpikhmx3



SELECT d.DEPT_NAME, u.USER_NAME, yd.ID, yd.JLDH, yd.STU, yd.final_score 
                FROM  USER u
                LEFT JOIN department d ON d.DEPT_ID = u.dept_id
                LEFT JOIN inhe_kpikh yd ON yd. NAME = u.USER_NAME AND yd.BM = d.DEPT_NAME
-- AND yd.ZQ = '".$zqVal."'
                WHERE u.is_kh IS NULL
             --   AND ( d.dept_id = '$deptId' OR d.dept_parent = '$deptId' )
              --  AND u.uid != '$uid'
and yd.final_score is not null


select * from inhe_kpikh





update inhe_kpikh set STU='2' where stu = '1' and id in(
    SELECT yd.id FROM  USER u
                LEFT JOIN department d ON d.DEPT_ID = u.dept_id
                LEFT JOIN inhe_kpikh yd ON yd. NAME = u.USER_NAME AND yd.BM = d.DEPT_NAME AND yd.ZQ = '2019年11月'
                WHERE u.is_kh IS NULL
                AND ( d.dept_id = '48' OR d.dept_parent = '48' )
                AND u.uid != '20067'
)


   SELECT yd.id FROM  USER u
                LEFT JOIN department d ON d.DEPT_ID = u.dept_id
                LEFT JOIN inhe_kpikh yd ON yd. NAME = u.USER_NAME AND yd.BM = d.DEPT_NAME 
 AND yd.ZQ = '2019年11月'
                WHERE u.is_kh IS NULL and yd.id is not null 
             --   AND ( d.dept_id = '48' OR d.dept_parent = '48' )
             --   AND u.uid != '20067'

select * from inhe_kpikh
select * from USER


select * from inhe_system_admin


select * from inhe_kpikh where name like '%赖利团%'

select * from inhe_kpikhmx1 where jldh=''
select * from inhe_kpikhmx2 where jldh=''
select * from inhe_kpikhmx3 where jldh=''

select * from inhe_hr_flow_prcs where flow_id = 'JK20191029006' 


select * from inhe_kpikhmx3 where user_id = 'admin1'



select user_name,reach_rate,sales_ranking,completion_rate from sales_performance where user_name in('赵政洁') and cycle = '2019年11月';

select name,jldh from inhe_kpikh where name in('左亚楠','李美娇','何森华') and zq ='2019年11月';

select * from inhe_kpikhmx3 where jldh in(select jldh from inhe_kpikh where name in('左亚楠'));


--  --------------------------------
select * from inhe_kpikhmx3;
update inhe_kpikhmx3 set QK1='30.14%',QK1_2='0.00%',QK2='0.00%' where jldh='10269';  -- 辛方腾
update inhe_kpikhmx3 set QK1='30.14%',QK1_2='0.00%',QK2='0.00%' where jldh='10281';  -- 刘畑杏
update inhe_kpikhmx3 set QK1='30.14%',QK1_2='0.00%',QK2='32.94%' where jldh='10247';  -- 陈雁
update inhe_kpikhmx3 set QK1='30.14%',QK1_2='0.00%',QK2='0.00%' where jldh='10276';  -- 付为
update inhe_kpikhmx3 set QK1='30.14%',QK1_2='72.03%',QK2='158.34%',DF2='100' where jldh='10261';  -- 汤伟


update inhe_kpikhmx3 set QK1='30.14%',QK1_2='0.00%',QK2='0.91%',DF1='70',DF2='70' where jldh='10309';  -- 左亚楠
update inhe_kpikhmx3 set QK1='30.14%',QK1_2='0.07%',QK2='3.18%',DF1='70',DF2='70' where jldh='10338';  -- 李美娇
update inhe_kpikhmx3 set QK1='30.14%',QK1_2='0.00%',QK2='0.00%',DF1='70',DF2='70' where jldh='10319';  -- 何森华


select * from sales_performance

select * from inhe_kpimb


select * from kpikh order by id desc 

select * from inhe_kpikhmx3 where jldh='10309';


INSERT into inhe_kpikhmx3(jldh,qk1,qk1_2,qk2,df1,df2) values ('10309','30.14%','0.00%','0.91%','70','70');
INSERT into inhe_kpikhmx3(jldh,qk1,qk1_2,qk2,df1,df2) values ('10338','30.14%','0.07%','3.18%','70','70');
INSERT into inhe_kpikhmx3(jldh,qk1,qk1_2,qk2,df1,df2) values ('10319','30.14%','0.00%','0.00%','70','70');

-- ---------------------------------
select * from gzjj 


select * from inhe_kpigl

select * from inhe_kpikhmx3 where jldh = '10393';
INSERT into inhe_kpikhmx3(jldh,qk1,qk1_2,qk2,df1,df2) values ('10393','30.14%','5.61%','13.01%','70','70');
update inhe_kpikhmx3 set QK1='30.14%',QK1_2='5.61%',QK2='13.01%',DF1='70',DF2='70' where jldh='10393'; 

--  ----------------------------------------------------------

select * from inhe_kpigl


select uid from inhe_kpikh where zq = '2019年11月' group by uid
select user_id from inhe_kpigl

-- 2019/11/28
-- 查询尚未提交考核的人员(去除电网和已离职员工)
select u.uid,u.user_name from user u
where u.user_id in(select user_id from inhe_kpigl)
and u.uid not in (select uid from inhe_kpikh where zq = '2019年11月' group by uid)
and u.user_bu != 'INHEGRID'
and u.dept_id != 0 and u.dept_id is not null and u.dept_id != ''


-- gzjj 由HR发起，黄主管备案
select * from gzjj

select * from lzsq


select * from user where USER_ID= 'admin1'
select * from department 


call getChild('46');

select * from department
select * from user where dept_id in (46,47,48);

select * from fujian3



select k.*, d.dept_name, c.company,m.NAME REALNAME
		from inhe_kpigl k 
    left join inhe_kpimb m on m.id = k.id
		left join user u on u.user_id = k.user_id
		left join department d on d.dept_id = u.dept_id
		left join company c on c.user_bu = k.user_bu 

select * from inhe_kpigl where USER_ID='INHE-0566'
select * from inhe_kpimb where id =39

select * from inhe_kpikh where jldh = '10313'
select * from inhe_kpikhmx1 where jldh = '10313'
select * from inhe_kpikh where jldh = '10365'



select * from inhe_kpimbmx1


select * from inhe_oa_paras
select * from flow_step

select * from gzjj


select * from rsyd





