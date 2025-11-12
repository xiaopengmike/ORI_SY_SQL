select * from inhe_kpikh



update inhe_kpikh kk
left join inhe_public_kh k on k.uid = kk.uid and k.cycle='2019年11月'
set kk.final_score = kk.final_score - k.total
where kk.zq = '2019年11月' and k.total is not null and k.cycle='2019年11月'



select final_score from inhe_kpikh


select * from inhe_public_kh 
update inhe_kpikh set zq ='2019年11月'
desc inhe_public_kh
desc inhe_kpikh


select kk.uid,kk.name,kk.final_score,k.total,kk.final_score-k.total from inhe_kpikh kk
left join inhe_public_kh k on k.uid = kk.uid 
where k.total is not null 


update inhe_kpikh kk
left join inhe_public_kh k on k.uid = kk.uid and k.cycle='2019年11月'
set kk.final_score = kk.final_score - k.total
where kk.zq = '2019年11月' and k.total is not null and k.cycle='2019年11月'


update inhe_kpikh  set final_score = FORMAT(final_score,2)
where zq = '2019年11月' and final_score is not null and  final_score!=''


-- ---


select user_name,count(user_name) from inhe_public_kh where cycle = '2019年11月'
group by user_name 

delete from inhe_public_kh where cycle != '2019年10月'


select * from inhe_public_kh


update inhe_public_kh a left join department b on a.bm_name = b.dept_name set a.bm_id = b.dept_id



select * from department_pro where dept_name like '%商务%'

select * from user 


update inhe_public_kh set bm_name = '客户服务中心' where bm_name = '客户服务部'


select u.user_id,u.user_name,d.dept_id,d.dept_name from user u
left join department d on u.dept_id = d.dept_id
where u.user_name = '陈金珠'


update inhe_public_kh k 
left join user d on d.user_id = k.user_id 
set k.uid=d.uid


alter table inhe_public_kh add uid int(11);

update inhe_kpikh kk
left join inhe_public_kh k on k.uid = kk.uid 
set kk.final_score = kk.final_score - k.total
where kk.zq = '2019年11月'

select * from inhe_kpikh


select * from inhe_kpigl
select * from inhe_kpimb


select a.user_name,a.id,a.name,a.user_bu,b.user_bu from inhe_kpigl a
left join inhe_kpimb b on b.id =a.id 


update inhe_kpimb set user_bu = 'INHEGRID' where id ='55'

select * from inhe_kpimb  where id ='55'


select * from inhe_kpimb where id not in(
select id from inhe_kpigl where id != '' GROUP BY id
) 




select name,count(1) from inhe_kpimb GROUP BY name 
