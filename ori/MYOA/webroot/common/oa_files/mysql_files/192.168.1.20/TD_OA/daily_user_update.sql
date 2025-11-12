select * from inhe_product_detail

select number from inhe_product_detail group by number

select * from user where user_name like '%吴楠%'  -- INHE-0362 INHE-0972 $1$wm1.3F3.$qfkdvngauAY5uJZiZDQhP0
select * from user where user_name like '%聂浩英%' -- INHE-0566 INHE-0975  $1$lA5.Kc0.$abx7aIvyeBEmohJcufy3M0

select * from interview where name like '%聂浩英%'
select * from interviewmx1 where flow_id = 'MS20201130001'
select * from interviewmx2 where flow_id = 'MS20201130001'


select * from interviewmx2 where rzgh = 'INHE-0362'


select * from inhe_oa_paras where type = 'crm_process_type'

select * from inhe_kpikh where name like '%刘治兴%'
select * from kpikh where name like '%刘治兴%'
select * from inhe_hr_flow_prcs where flow_id = 'JK20201231058'
select * from user where user_id = 'INHENERGY-0071'
-- 169134
select * from mail where  from_id = 'INHE-0972' or send_to like '%INHE-0972%' or cc_to like '%INHE-0972%' or fw_to like '%INHE-0972%'
select * from mail_body   where author = 'INHE-0362'
select * from mail_fw where fw_to = 'INHE-0972' or fw_from = 'INHE-0972'
select * from mail_to where to_id = 'INHE-0972'  -- 1072667
select * from mail_to where to_id = 'INHE-0362'
select * from mail where SUBJECT like '%MAP%'

select * from user where user_name like '%吴楠%'  or user_id = 'INHE-0900' or user_name like '%聂浩英%' 

select * from notes where name like '%吴楠%'
select * from work_plan_item where plan_id = 
show tables like '%note%'
select * from rzdj where name like '%聂浩英%'