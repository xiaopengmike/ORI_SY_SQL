JXINHE-0385,lhz,INHE-0796,INHE-0051,INHE-0249
select * from user where user_id='INHE-0051' or user_id = 'INHE-0900'
select * from user where user_name like '%林丽%'
select * from inhe_system_admin where user_id = 'INHE-0900' and type = 'customer_data'   -- INHEGRID
select * from inhe_attachment
-- customer_data  project_review  sales_contract_review  production_order  shipping_notice  project_participant  project_number_participant  sales_receipt
-- 11	1	2	1	申请人		2	add							customer_data	客户信息登记	INHEGRID
insert into inhe_flow_manage
select * from inhe_attachment
select * from inhe_flow_manage where type = 'production_order'  and user_bu = 'INHE' order by step; 
select * from inhe_flow_manage where type like 'project_review'  and user_bu = 'INHEGRID' order by step;
select * from inhe_flow_manage where type like 'sales_contract_review_%'  and user_bu = 'INHE' order by step;
select * from inhe_oa_paras where type = 'crm_process_type'
select * from inhe_oa_paras where type = 'inhegrid_notice_type'
select * from inhe_flow_manage where type = 'project_review' order by step;
select * from inhe_attachment where relatedId = 'INHESC2020112601'
select * from inhe_flow_manage where type = 'project_number_participant' order by step;  -- INHE-0036,INHE-0702,IM-0044,WL-0048,INHE-0051
select * from inhe_oa_paras where type = 'other_condition'
select * from inhe_contract_detail
select * from rsyd where flow_id = 'RY20200601001'
select * from inhe_project_main where  form_id =  'xssk2021020204';
select * from inhe_flow_opinion where  form_id =  'xssk2021020204';
select * from inhe_flow_opinion where  form_id =  'xssk2021020301';
select * from inhe_sales_receipt_attach where  form_id =  'xssk2021020204';
select * from inhe_project_main where  form_id =  'lxdj2021012101';
select * from inhe_oa_paras where type = 'crm_process_type'
select * from inhe_detail_item
select * from inhe_production_order where form_id = 'sctz2020081001'
select * from inhe_order_detail where form_id = 'sctz2020081001'  -- related_attachment   attachment   non_attachment
select * from inhe_attachment where relatedId = 'sctz2020081001' 
select * from inhe_production_detail_one order by id desc;
select * from inhe_production_detail_two order by id desc;
select * from inhe_production_detail_three order by id desc;
select * from inhe_production_detail_non order by id desc;
select * from api_test
select * from inhe_contract_review
where project_name = 'INHESC2020112901'
select * from inhe_product_detail where form_id = ''
select * from inhe_project_main where project_code = 'NGJ'
select * from inhe_detail_item
-- select uid,user_id,user_name,user_priv_other from user 
-- update user set user_priv_other = CONCAT(user_priv_other,'97,')
select * from inhe_oa_paras where type like '%yes_or_no%'

select * from inhe_oa_paras where type_desc like '%按照表号顺序包装%'
select * from inhe_customer_data   order by id desc ;
select * from inhe_project_main   order by id desc ;
select * from inhe_oa_paras where type = 'check_opinion'
-- select * from crs_quick_reportstate
select * from inhe_flow_opinion where status = 'U' and approve_user='INHE-0900' type = 'project_review' 
select * from inhe_flow_opinion where type = 'project_review'  and form_id = 'lxdj2020110701'  

update inhe_flow_opinion a left join  crs_quick_reportstate b on a.id =b.id 
set a.form_id = b.form_id, a.title=b.title where a.type =  'sales_contract_review' 

-- select * from department where dept_name like '%国际业务%'
select * from crs_quick_reportstate where type =  'shipping_notice'  and form_id = 'khxx202008051109'
update crs_quick_reportstate a left join inhe_project_main b on b.create_user = a.user_id and b.create_time = a.write_time
set a.form_id = b.form_id,a.title = b.title where a.type = 'sales_contract_review' 

-- insert into inhe_flow_opinion (id, type, form_id, title, work_flow, approve_user, approve_time, status, prev_task, prev_writer, prev_time)
-- select id, type, form_id, title, work_flow, approve_user, approve_time, status, prev_task, prev_writer, prev_time from crs_quick_reportstate 

update crs_quick_reportstate a
left join inhe_customer_data b on a.user_id = b.user_id and a.write_time = b.write_time
set a.title =b.title,a.form_id = b.form_id
where a.type =  'customer_data'


update inhe_customer_company a
left join inhe_customer_data b on a.title = b.title
set a.form_id = b.form_id, a.customer_id = b.id


update inhe_customer_info a left join inhe_customer_data b on a.name = b.id 
        set a.customer_name = b.customer_name where a.customer_name = '' or a.customer_name is null

select * from inhe_approver_manage;
select * from department where dept_id = 141
select * from user where user_id = 'INHE-0662'
select * from user

select * from inhe_project_main
select * from inhe_customer_info
select * from my_fund_season

select * from fund_collect 
delete from fund_collect where half_year < '77.6727'
-- create table fund_collect as 
select * from my_fund_select order by one_year desc limit 0,205
select * from my_fund_select order by mark desc,three_months desc
select * from my_fund_select where (sort is null or sort='') and name like '%科技%' order by mark desc,three_months desc
select * from my_fund_select order by three_years desc limit 0,327
-- select * from my_fund_select where month >= '8.9021'
select * from my_fund_select where mark = 0 and (this_year is null or this_year = '' ) 

update my_fund_select a set mark=mark+1 
where three_years >= '90.0448'
select * from  fund_season_collect ORDER BY g_name, proportion desc
select * from my_fund_season where g_name like '%信维通信%'  order by proportion desc   COM

insert into my_fund_select (code, name, total, flag)
select t.* from (
select code,name,FORMAT(sum(proportion),2) total,flag from my_fund_season -- where flag = 'YY'  
GROUP BY code 
) t 
-- where t.total > 10
order by t.total desc
limit 0,14

select id,type,type_desc,paras_desc,paras_value,extra,sort_code,is_used from inhe_oa_paras where type ='crm_process_type'

select * from inhe_report_column
select * from inhe_form_change
show tables  like '%change%'
select * FROM inhe_attachment where relatedid='lxdj2020112604'

select * from inhe_control_library_temp

select * from department where dept_id = 193
select * from department where dept_name like '%组%'
select * from user where user_name like '%何梓%'
select * from interview where flow_id = 'MS20210114001'
select * from rsyd where flow_id = 'RY2021013001'
select * from interviewmx2 where flow_id = 'MS20210112001'
select * from user where user_id = 'INHE-0977'
select * from user_0112
select * from inhe_attachment where relatedId= 'lxdj2021010701'

select * from mail;
select * from mail_body;
select * from mail_bu;
select * from mail_to;

-- select * from mail_group
select * from ADDRESS_GROUP;


select * from department where dept_name like '%组%' and user_bu = 'INHE'

select * from rsyd where flow_id = 'RY20210203005'
select * from rsyd where flow_id = 'RY20210203004'
select * from inhe_approver_manage where id>29
select * from rsyd where flow_id = 'RY20210203001'