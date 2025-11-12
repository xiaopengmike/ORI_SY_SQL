-- pm_manage
-- 审核记录表； 项目比例数据表； 历史数据表

select * from inhe_pm_proportion;
select * from inhe_pm_proportion_history;
select * from inhe_pm_proportion_item ORDER BY user_item ;

desc inhe_pm_proportion

select * from rzdj
select * from interview where name like '%赵涛%'
select * from interviewmx2 where flow_id = 'MS20200403001'



select * from user where user_name like '%郭涛%'

select * from fujian1

select k.*, u.user_name approver, u.dept_id, d.dept_name from inhe_pm_proportion k
            left join user u on u.user_id = k.Reviewed_ID 
            left join department d on d.dept_id = u.dept_id




