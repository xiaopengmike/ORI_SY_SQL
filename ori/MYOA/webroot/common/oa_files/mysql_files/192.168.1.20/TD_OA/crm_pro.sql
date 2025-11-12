-- crm system manage - 202006
select * from crscell.crs_tableindex where repid = 420
select * from crscell.crs_tabledata34 where col13388 like '%%'
select * from crscell.crs_workflow where repid = 420  -- and is_begin = '是'
select * from crscell.crs_quick_reportstate limit 0,10
select * from crscell.crs_quick_reportstate  where repid= 691 and userid = 'INHENERGY-0066' and writetime like '%2020-09-07 %'  order by id 
select * from crscell.crs_reportstate where repid = 691 and userid = 'INHENERGY-0066' and writetime like '%2020-09-07 %' order by id
-- col13388  - 772   col13385 - 771  696 - col12867 
select * from crscell.crs_tabledata719 order by id desc limit 0,10
-- customer_data
select * from continent_code
select * from country_code
-- 
desc inhe_contract_settle_model
select * from inhe_customer_data where form_id = 'lxbg2021020102'
select * from inhe_customer_contact where form_id = 'khxx202012301210'
select * from inhe_customer_company where form_id = 'khxx202012301209'
select * from inhe_customer_share; 
select * from inhe_project_code  
select * from inhe_temp_code  
desc inhe_customer_contact;
-- update inhe_customer_contact a left join inhe_customer_data b on b.title = a.title set a.customer_id = b.id,a.form_id = b.form_id
-- project_review
select * from crscell.crs_tableindex where repid = 420
select * from crscell.crs_tabledata690
-- project_main_vo
select * from inhe_order_detail_ip 
create table inhe_project_code_ip as select * from crscell.crs_tabledata1542 
-- insert into inhe_project_main select * from inhe_project_main_ip 
select * from inhe_attachment order by id desc;
select * from inhe_project_main where form_id = 'lxdj2021012901'
select * from inhe_customer_info where form_id =''
select * from inhe_customer_info_ip ;
select * from inhe_project_info_ip ;
select * from inhe_project_info ;
select * from inhe_project_detail_ip where id>=2236;
select * from inhe_project_detail 
select * from inhe_bidding_strategy_ipp ;
select * from inhe_bidding_strategy  where title = 'lxdj2020101001/MJT'
select * from inhe_project_manager ;
-- select * from user where user_priv_name like '%项目经理%' and dept_id != 0
-- insert into inhe_bidding_strategy (id) select id from inhe_bidding_strategy_ip
-- sales_contract_review
-- 1,2,4,6,7,8,12,13,19,24,27,35,40,41,47,50,52,59,63,64,65,67,68,69,70,71,82,103  103,
-- select * from sys_function where func_id in(1060,1041,1042,1061,1045,1046)
-- select * from user_priv where func_id_str like '%1046%'
select uid,dept_id,user_priv,user_priv_name,USER_PRIV_OTHER from user where user_name like '%孙梦圆%';
select * from inhe_oa_paras where type = 'kpi_approver_others'
select * from user where user_name like '%余祥林%';  -- 92, JXINHE-4046  156  INHE-0571  szyuxl
select * from interview where flow_id = 'MS20201123001'
select * from interviewmx1 where flow_id = 'MS20201123001'
select * from interviewmx2 where flow_id = 'MS20201123001'
select * from interviewmx2 where rzgh = 'JXINHE-4046'
select * from rzdj where gh = 'JXINHE-4046'
select * from djgz where dj = 'M4-10'
select * from rsyd where gh = 'INHEGRID-0066'
select * from rsyd where flow_id = 'RY20201112002'
select * from inhe_hr_flow_prcs where flow_id = 'RY20201112002'
select * from canzhao
select * from yijian1 where flow_id = 'RY20201112001'
select * from user where user_byid = 'INHE-0018'
select * from department where MANAGER = 'INHE-0015'
select MANAGER, DEPT_ID, DEPT_PARENT, IS_DIRECTOR from department where DEPT_ID=142
select MANAGER, DEPT_ID, DEPT_PARENT, IS_DIRECTOR from department where DEPT_ID=153

select * from department where dept_id = 105
select * from department where dept_name like '%项目组%'
select * from inhe_approver_manage
select MANAGER from department where DEPT_ID=142
select b.ypgw,u.user_priv_name,a.* from  interviewmx2 a 
LEFT JOIN interview b on a.flow_id = b.flow_id
LEFT JOIN user u on u.user_byid = a.rzgh
where a.zw = ''  and a.rzgh != '' and u.dept_id = 0
-- select * from user_priv where FUNC_ID_STR like '%1012%'  -- 报表管理
1,2,4,6,7,8,12,13,19,24,27,35,40,41,47,50,52,59,63,64,65,67,68,69,70,71,82,103
select * from sys_function where func_name like '%报表管理%'
select * from user where user_priv= 18
select * from user where user_id= 'INHE-0857'
select * from user where dept_id= '0'
select * from user where user_name like '%丽春%';  -- 商务专员  工程师I
select * from sms where sms_id = 698357
select * from sms_body where remind_url like '%repid=220%' and from_id = 'szdfm' limit 0,10

-- 
select * from inhe_contract_protocol where serial_number = 288
select * from inhe_attachment where type = 'contract_protocol' and relatedId = 559
select * from inhe_contract_review   where form_id = 'INHESC2021012901';   
select * from inhe_product_detail where form_id = 'INHESC2021012901';   
update inhe_contract_review a left join crscell.crs_tabledata696 b on a.title = b.title set a.dept_id = b.organid where a.type = 'other'
-- insert into inhe_product_detail(title) select title from crscell.crs_tabledata705
select * from inhe_contract_detail where form_id = 'INHESC2021012901';   
select * from inhe_contract_settle where form_id = ''
select * from inhe_contract_settle_model;
-- 主表、电表类明细、电表明细表2、电表明细表3、非电表类产品
-- order 
select * from inhe_oa_paras where type like '%sale_model%'
select * from inhe_production_order;
select * from inhe_production_detail

select * from inhe_order_detail 
-- select * from inhe_production_detail
where data_no = 2;
select * from inhe_detail_item where table_id = 'non_meter'
-- 主表、电表类产品发货明细、非电表类产品明细、软件明细
select * from inhe_shipping_notice;
select * from inhe_shipping_detail;
select * from inhe_oa_paras where type_desc like '%按照表号顺序包装%'
-- 项目参与人
select * from inhe_project_participant;
select * from inhe_project_participant_item;
select * from 

select * from inhe_number_participant;
select * from inhe_number_participant_item;
--
select * from inhe_control_library
select * from user where user_id ='INHE-0697' 
select * from department where dept_id ='171' 
select * from company 
select * from interview where flow_id = 'MS20201123001'
select * from interview where create_user_id = 'INHE-0808'
select * from inhe_system_admin  where user_id ='INHE-0697' 
select * from inhe_system_admin where type = 'control_library' or type='sales_contract' or type ='authorization_letter' or type ='guarantee_letter'  user_id ='INHE-0674' 
-- insert into inhe_attachment (filename,path,relatedId,type) 
-- select attach_name filename,concat('D:/MYOA/webroot/attachment/reportshop/attachment/',attach_name) path,id relatedId,'contract_protocol' type from inhe_contract_protocol where id not in(551,552,557,558) and attach_name != ''
-- DEMO
select * from inhe_attachment where type ='foreign_attach'  -- chinese_attach

select * from crs_tabledata1922;
select * from inhe_sales_contract;

select * from crs_tabledata2924;
select * from inhe_guarantee_letter order by id desc;
select * from inhe_contract_protocol where serial_number >=270;
select * from inhe_contract_protocol_ip ;
create table inhe_contract_protocol_ip as 
select * from crscell.crs_tabledata1527  where col20164 >270;
select id,attach_id,attach_name from inhe_contract_protocol where serial_number >270;
select * from inhe_attachment where type = 'contract_protocol' and id >330  -- D:/MYOA/webroot/attachment/reportshop/attachment/
-- 
select * from inhe_report_column where module_id = 'contract_protocol';
select * from inhe_report_column where module_id = 'sales_contract';
select * from inhe_report_column where module_id = 'authorization_letter';
select * from inhe_dynamic_page
select * from inhe_dynamic_page_field

desc inhe_contract_protocol;
select * from inhe_report_column;

select * from inhe_attachment where type = 'authorization_letter'

select * from inhe_flow_manage where type = 'shipping_notice';
select * from inhe_flow_opinion where flow_id = 'fhtz2020090401' ;
select * from inhe_flow_manage where type = 'project_review' and if_show != 0
select * from inhe_oa_paras where type_desc like '%管理部门%'

desc inhe_contract_detail

select * from interview where flow_id  = 'MS20201015001';
select * from interview where user_bu = 'INHENERGY'
select * from inhe_hr_flow_prcs where flow_id  = 'MS20201015001';
select * from yijian where flow_id  = 'MS20201015001';

select * from company where ID='3'
select * from user where user_id = 'INHE-0622'
select * from user where user_name like '%李林%'

select * from department where dept_id=133
select * from user where dept_id = 133

 select * from mail where mail_id = '166465'  -- SLC项目保函咨询方案
 select * from mail_body where mail_id = 166465

 select * from mail where mail_id = 156410
 select * from mail_body where subject like '%TTD项目商务支持申请%' -- INHE-0634
 select * from mail where from_id = ''
 select * from mail limit 0,1
select * from vi_mail_star
select * from star_pm

select * from rsyd where name like '%杨松%'
select * from inhe_hr_flow_prcs where flow_id= 'RY20201021001'
select * from department where dept_id = 3

select * from rzdj where dz like '%半岛%'

select a.gh,a.name,a.bm,u.dept_id from rzdj a
left join  user u on a.gh = u.user_byid
where a.bm != u.dept_id

select * from sys_function where func_id = 1071
select * from user_priv where user_priv=89
select * from user where user_id = 'INHENERGY-0050'
select * from department where dept_name like '%AC%';
select * from interview where flow_id = 'MS20201026001'
select * from inhe_hr_flow_prcs where flow_id = 'MS20201026001'
select * from yijian where flow_id = 'MS20201026001'
select * from canzhao where id=5
select * from company

create table inhe_sales_receipt_detail as 
select * from crscell.crs_tabledata746 

select * from inhe_sales_receipt
select * from inhe_sales_receipt_detail where title like '%J.N.F.X.LIMITED%'

select * from department where dept_name like '%人力资源部%'

select * from inhe_contract_protocol order by serial_number desc; 
select * from inhe_attachment

select * from inhe_system_admin where type = 'F01'
select * from user where user_name like '%熊茂%'  JXINHE-1753  JXINHE-2860
select * from user where user_id = 'INHE-0284'
