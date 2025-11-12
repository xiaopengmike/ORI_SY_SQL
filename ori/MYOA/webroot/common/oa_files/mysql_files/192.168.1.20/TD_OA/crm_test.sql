-- customer_data  project_review  sales_contract_review  production_order  shipping_notice  project_participant  project_number_participant  sales_receipt
-- INHE INHEGRID INHENERGY WITLINK JXINHE INHEJX
select * from inhe_flow_manage where type like 'sales_contract_review'  and user_bu = 'INHE' order by step;
select * from inhe_flow_manage where type like 'sales_contract_review_%'  and user_bu = 'INHE' order by step;
select * from inhe_flow_manage where type like 'project_review'  and user_bu = 'INHEGRID' order by step;
select * from inhe_flow_manage where user_bu = 'INHEGRID' order by step;
select * from inhe_flow_manage where user_bu = 'INHEGRID' order by step;
select * from inhe_system_admin where type = 'project_review'
select * from inhe_flow_manage 
select * from user where user_name like '%徐周泽弈%' and user_id like 't0%'
select * from user where user_id = 'INHE-0900'
select * from user_priv where priv_name like '%CRM%'
select * from sys_function where func_id > 1081 and func_id<1999
-- select * from rzdj where gh = 'INHE-0622'
t01	测试（业务） - INHE-0662   lhz  INHE-0249  syz  ,INHE-0067,INHE-0796,INHE-0249,  JXINHE-0385
t02	测试（总监） - INHE-0051   INHE-0091
t03	测试（编号）- INHE-0036,INHE-0702,IM-0044,szyaj,WL-0048,INHE-0768
t04	测试（宋） - INHE-0260
t05	测试（杨） - INHE-0144
t06	测试（黄） - szdfm   
t07	测试（王） - meterw
t08	测试（财务） -  INHE-0800  
t09	测试（营）- kenny   INHE-0671  INHE-0038  INHE-0957

select * from inhe_contract_review where  form_id =  'lxdj2021020102';
select * from inhe_flow_opinion where  work_flow = '财务会计' and type = 'production_order' form_id =  'lxdj2021020102';
select * from inhe_flow_manage where type = 'shipping_notice'  and user_bu = 'INHE' order by step;  
select * from inhe_project_participant_item where form_id = 'menb2021020101'

select * from inhe_oa_paras where type_desc like '%立项项目类型%'
select * from inhe_oa_paras where type like '%2%'
select type_desc,type from inhe_oa_paras group by type_desc

select * from inhe_oa_paras where type_desc like '%合同版本号%'  

select * from inhe_attachment

select * from inhe_oa_paras where type like '%KPI%'
select * from inhe_sales_receipt order by id desc

select * from inhe_oa_paras where type = 'crm_process_type'
select * from inhe_oa_paras where type = 'crm_process_sort'

select * from inhe_oa_paras where type = 'other_condition'

select * from user where user_id = 'INHE-0797'
select * from department where dept_name like '%东非区EAC%'
select * from department where dept_id = 97


select * from department where dept_name like '%审计%'

select * from rsyd where bm = '17'
