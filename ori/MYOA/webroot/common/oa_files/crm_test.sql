-- crm system manage - 202006
select * from crscell.crs_tableindex where repid = 420
select * from crscell.crs_tabledata4 where col13388 like '%%'
select * from crscell.crs_archive_reportstate where repid = 420 and userid = 'INHE-0680' and writetime like '%2020-09-07%'   
select * from crscell.crs_reportstate where repid = 420 and userid = 'INHE-0680' and writetime like '%2020-09-07%'  
select label from crscell.crs_workflow where repid = 420 and is_begin = '是'
update crscell.crs_reportstate set useridlist='t03' where repid = 958 and userid = 'INHE-0900' and id = 77794
select * from crscell.crs_quick_reportstate limit 0,10
select * from crscell.crs_quick_reportstate  where repid= 691 and userid = 'INHENERGY-0066' and writetime like '%2020-09-07 %'  order by id 
select * from crscell.crs_reportstate where repid = 691 and userid = 'INHENERGY-0066' and writetime like '%2020-09-07 %' order by id
-- col13388  - 772   col13385 - 771  696 - col12867 
select * from crscell.crs_tabledata719 order by id desc limit 0,10
-- customer_data
select * from continent_code
select * from country_code

desc inhe_contract_settle_model
select * from inhe_customer_data where form_id = 'khxx202010121157';
select * from inhe_customer_contact where form_id = 'khxx202010121157'
select * from inhe_customer_company where customer_id = 887
select * from inhe_customer_share; 
select * from inhe_project_code where id >591
-- update inhe_project_code a left join user u on u.user_name = a.user_name set a.user_id=u.user_id,a.dept_id=u.dept_id where a.id >515
select project_code,count(1) sum from inhe_project_code GROUP BY project_code order by sum desc
select * from inhe_project_code where project_code = 'GAB'
-- POL  SRS  GAB
select * from vi_mail_star
select * from mail -- PM_ID
select * from star_pm -- PM_ID

select * from country_code where iso_three = 'COL'
desc inhe_customer_contact;
update inhe_customer_company a
left join inhe_customer_data b on b.title = a.title 
set a.customer_id = b.id,a.form_id = b.form_id
-- project_review
select * from inhe_attachment order by id desc;
select * from inhe_project_main where project_code is null; 
select k.* from inhe_project_main k where 1=1 and k.project_code = 'NFD';
select * from inhe_project_code
select * from company
select * from inhe_oa_paras where type_desc like '%付款%'  type='project_star' type_desc = '客户验货类型' paras_desc like '%ODM%' 
select project_code,count(1) as sum from inhe_project_code group by project_code order by sum

select * from inhe_form_change
-- select * from inhe_project_code_ip;
select * from inhe_project_main;
select * from inhe_customer_info;
select * from inhe_project_info;
select * from inhe_project_detail;
select * from inhe_bidding_strategy;
select * from inhe_project_manager;
select * from inhe_temp_code;
select * from mail -- PM_ID
select * from star_pm -- PM_ID
-- sales_contract_review
select * from inhe_contract_review  where form_id = 'INHESC2020102301';
select * from inhe_product_detail;
select * from inhe_contract_detail;
select * from inhe_contract_settle;
select * from inhe_contract_settle_model;
-- 主表、电表类明细、电表明细表2、电表明细表3、非电表类产品
-- order 
select * from inhe_production_order;
-- insert into inhe_order_detail (form_id,table_id,data_no,item_no,item_id)
-- select form_id,table_id,'2' as data_no,item_no,item_id from inhe_order_detail;
select * from inhe_order_detail where data_no = 2;
select * from inhe_detail_item;
-- 主表、电表类产品发货明细、非电表类产品明细、软件明细
select * from inhe_shipping_notice;
select * from inhe_shipping_detail;
-- 项目参与人
select * from inhe_project_participant;
select * from inhe_project_participant_item;
-- 销售收款登记
select * from inhe_sales_receipt;
select * from inhe_sales_receipt_detail where title like '%J.N.F.X.LIMITED%'
select * from inhe_oa_paras where type like '%process_status%'

-- 合同/协议登记表 174  东北非NEAC  INHE-0642  潘正豪
select * from inhe_contract_protocol order by serial_number desc; where manage_dept not in(01,02,03,04,05) order by serial_number desc;
select * from inhe_sales_contract order by serial_number desc; where manage_dept not in(01,02,03,04,05) 
select * from  inhe_guarantee_letter where id = 292;
select * from inhe_authorization_letter  order by id desc; where manage_dept not in(01,02,03,04,05) 
select * from inhe_attachment where id = 14
desc inhe_authorization_letter
select * from inhe_control_library
select * from inhe_customer_data where user_id ='INHE-0500'
select * from inhe_system_admin where user_id ='INHE-0900' and type = 'customer_data' or type='sales_contract' or type ='authorization_letter' or type ='guarantee_letter'
-- insert into inhe_attachment (filename,path,relatedId,type) 
-- select attach_name filename,concat('D:/MYOA/webroot/attachment/reportshop/attachment/',attach_name) path,id relatedId,'contract_protocol' type from inhe_contract_protocol where id not in(551,552,557,558) and attach_name != ''
-- DEMO
select * from inhe_attachment where type ='contract_protocol'  -- chinese_attach
select * from inhe_demo
select * from inhe_system_admin
 select * from inhe_oa_paras where type like '%company%'
select * from kpikh where user_id = 'INHE-0774'

select user_bu, dept_ids, user_ids, is_admin from inhe_system_admin where  order by id desc 

select * from crs_tabledata1922;
select * from inhe_sales_contract;

select * from crs_tabledata2924;
select * from inhe_authorization_letter order by id desc

-- 
select * from inhe_report_column where module_id = 'contract_protocol';
select * from inhe_report_column where module_id = 'sales_contract';
select * from inhe_report_column where module_id = 'authorization_letter';
select * from inhe_dynamic_page
select * from inhe_dynamic_page_field

-- select max(serial_number)+1 from inhe_contract_protocol
desc inhe_contract_protocol;
select * from inhe_report_column;

create table inhe_oa_paras_temp as 
select * from inhe_oa_paras

select * from inhe_attachment where type = 'authorization_letter'

select * from inhe_flow_manage where type = 'customer_data';
select * from inhe_flow_opinion where flow_id = 'khxx202010121157' ;
select * from inhe_oa_paras where type_desc like '%管理部门%'
select * from user where user_id='INHE-0853'
select * from department where dept_id = 144
select * from inhe_flow_manage where type = 'project_review' and if_show != 0
select uid,user_id,user_name,dept_id from user where dept_id = 81

select * from inhe_control_library

select * from country_area
select * from country_code where zh_name like '%中国%'
select * from continent_code
select * from inhe_attachment
-- 
select * from inhe_system_admin where type = 'customer_data'
select * from sys_function
select * from user_priv
select * from company
select * from department where dept_id = 150
select * from user where user_name like '%胡亚%'  -- kenny,INHE-0159,INHE-0364,

-- select * from interviewmx2 where rzgh = 'INHE-0860'
-- update user set limit_login=0,NOT_MOBILE_LOGIN=0
select a.BZ,a.role,b.SHR,b.SJ from canzhao a LEFT JOIN yijian b ON a.BZ=b.BZ WHERE b.flow_id='MS20200807002' and a.ID=$GS and a.BZ<=$BZ
select * from canzhao where ID='2' 
select * from user where user_id='INHE-0314'
-- inhe_control_library
select * from inhe_control_library where id in(1,2,3,4) chinese like '%电表%' and type = 'message_lang'
select * from inhe_control_library_history where term_id = 5339 order by id desc limit 0,2
desc inhe_control_library
select chinese,count(1) sum from inhe_control_library group by chinese order by sum desc
select * from inhe_control_library where  chinese = '分支机构'
-- 
select * from inhe_dynamic_page;
select * from inhe_dynamic_page_field;
2	term	名词	control_library	input	1	1	1	1	pass			

select * from inhe_hr_flow_prcs where flow_id = 'YK20200701001'
select DISTINCT p.*
    from inhe_hr_flow_prcs p
    LEFT JOIN inhe_kpikh k on k.flow_id = p.flow_id 
WHERE 1=1 AND p.FLOW_PRCS = 'P'  and k.final_score is null
and k.zq = '2020年06月'

select DISTINCT p.*, k.from_user
    from inhe_kpikh p
    LEFT JOIN inhe_hr_flow_prcs k on k.flow_id = p.flow_id 
WHERE 1=1  and p.final_score is null
and p.zq = '2020年06月'

show tables like '%attach%'
-- select * from inhe_worker_attach
select * from country_area
select * from country_code where zh_name like '%中国%'
select * from continent_code

update country_code a 
left join country_code b on b.id = a.id+1
set a.name = b.country;
delete from country_code where iso_two = ''
--  insert into inhe_attachment (filename, path, size, related_id, type)  values ('20200703155939-技术生产链.jpg', 'D:/MYOA/webroot/general/attachment/control_library/20200703155939-技术生产链.jpg', '32133', '0', 'control_library')
select * from inhe_attachment where relatedId = '5333' and type = 'control_library' 

select * from user where user_id = 'INHE-0900'

select * from department where dept_id = '64'
select * from inhe_system_admin where type = 'control_library'
select * from inhe_system_admin where type = 'dept_select'

select * from inhe_control_library where chinese = '电压等级1'
select * from inhe_control_library where type = '电表术语'
select * from inhe_control_library_history where type = '电表术语'

select t.* from inhe_control_library_temp t
where not exists (select * from inhe_control_library k where k.chinese = t.chinese and k.english = t.english)

 insert into inhe_control_library_history (term_id, type, term, chinese, english, french, spanish, remark, create_user, create_time)
select id, type, term, chinese, english, french, spanish, remark, update_user create_user,update_time create_time from inhe_control_library where chinese = '电压合格率1' 

select * from user where user_name like '%郭林%'
select * from user_ext where user_id = 'INHE-0662'
select * from canzhao where user_id = 'INHE-0662'

insert into inhe_system_admin values (null,'INHE-0622','INHE',1,'control_library');

select * from flow_data_34 where run_id = 68738

select * from user where user_name like '%宋宁%'
select * from department where dept_id = 133

select * from rsyd where name like '%鲁%'

select * from inhe_hr_flow_prcs

select * from inhe_flow_manage

select * from yijian
select * from canzhao

133
select * from sales_performance where cycle  = '2020年09月' order by id desc;

 select p.* from sales_performance p 
left join department d on d.dept_name = p.bm_name
where d.dept_id = '" . $deptId . "' and cycle = '" . $cycle . "'

select * from inhe_system_admin where type ='F05'
