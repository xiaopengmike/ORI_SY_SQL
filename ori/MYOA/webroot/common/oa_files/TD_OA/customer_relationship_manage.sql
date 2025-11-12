-- crm system manage - 202006
select * from inhe_customer_data;
select * from inhe_customer_contacts;
select * from inhe_customer_company;

desc inhe_customer_company

select * from sys_function
select * from user_priv

-- inhe_control_library
select * from inhe_control_library where id in(1,2,3,4) chinese like '%电表%' and type = 'message_lang'
select * from inhe_control_library_history where term_id = 5334
desc inhe_control_library
select * from inhe_control_library order by english

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

select * from CUSTOMER

--  insert into inhe_attachment (filename, path, size, related_id, type)  values ('20200703155939-技术生产链.jpg', 'D:/MYOA/webroot/general/attachment/control_library/20200703155939-技术生产链.jpg', '32133', '0', 'control_library')
select * from inhe_attachment where relatedId = '5333' and type = 'control_library' 

select * from user where user_id = 'INHENERGY-0042'
