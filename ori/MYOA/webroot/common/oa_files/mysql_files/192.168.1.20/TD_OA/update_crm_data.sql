-- update inhe_customer_data a set a.status = 'P'
select * from inhe_flow_manage where type = 'shipping_notice'
where a.form_id in(
-- 96083
select * from inhe_flow_opinion 
-- where form_id = '';   
where status='U' and approve_user='meterw' and type = 'customer_data' and step is null and work_flow like '%申请人%' and form_id != '')

update inhe_flow_opinion a
left join inhe_flow_manage b on a.work_flow = b.role and a.type = b.type
set a.step = b.step
where a.type = 'customer_data'
select * from inhe_flow_opinion 
select * from inhe_order_detail


select * from inhe_flow_opinion where type = 'customer_data'  and approve_user = 'meterw' 
update inhe_flow_opinion set user_bu = 'INHE' where type = 'customer_data'

update inhe_flow_opinion a 
left join crscell.crs_tabledata684 b on a.user_id=b.userid and a.write_time=b.writetime
set a.title = b.title , a.form_id = b.col12780
where type = 'customer_data' 

select type from inhe_flow_opinion  group by type
select b.id,b.repname from inhe_flow_opinion a
left join crscell.crs_report b on a.type = b.id
group by  b.id,b.repname

select * from inhe_flow_opinion order by type
select * from inhe_flow_opinion  where type= 221
update inhe_flow_opinion set type = 'sales_contract_review_change' where type = 508
delete from inhe_flow_opinion where type
in(416,
417,
418,
419,
615,
616,
709,
710
)

update inhe_flow_opinion set type = 'sales_receipt'
where  type = 241

select * from inhe_customer_data

update inhe_flow_opinion a
left join inhe_project_main b on a.user_id = b.create_user and a.write_time = b.create_time
set a.title =b.title,a.form_id = b.form_id
where a.type =  'project_review'


-- select * from crscell.crs_quick_reportstate

select * from inhe_system_admin where user_id = 'INHE-0900'


update inhe_bidding_strategy_ip a
left join crscell.crs_tabledata1533 b on a.id = b.id 
set a.title =b.title, 
a.serial_number = col20226,
a.product= col20227,
a.price= col20228,
a.first_price= col20229,
a.standard= col20230,
a.number= col20231,
a.attach_name= col20232

update inhe_bidding_strategy a
left join inhe_project_main b on a.title = b.title
set a.form_id = b.form_id , a.customer_id = b.id


update inhe_customer_info a
left join inhe_customer_data b on a.customer_name = b.customer_name
set a.name = b.id 

