select * from kpikh 
where user_id='INHE-0067'
ORDER by id

delete from kpikh where flow_id =''

update kpikh set stu = '3',user_bu='INHE'
where id >='224'


select * from crs_tabledata2366
desc crs_tabledata2366


update kpikh
set td11 = (td1+td2+td3+td4+td5+td6+td7+td8+td9+td10),
 gz15 = (gz1+gz2+gz3+gz4+gz5+gz6+gz7+gz8+gz9+gz10+gz11+gz12+gz13+gz14),
 sj11 = (sj1+sj2+sj3+sj4+sj5+sj6+sj7+sj8+sj9+sj10),
 sj26 = (sj12+sj13+sj14+sj15+sj16+sj17+sj18+sj19+sj20+sj21+sj22+sj23+sj24+sj25)
where td11 =''

update inhe_kpikh
set td11 = (td1+td2+td3+td4+td5+td6+td7+td8+td9+td10)
where td11 =''

select * from inhe_kpikh where td11 =''
TRUNCATE TABLE inhe_kpikh

update kpikh
set bz11 = 'B1'
where sj11>=90
sj11 >= 75 and sj11<90
sj26 < 75


update kpikh
set bz26 = 'S1'
where sj26>=90
sj26 >= 75 and sj26<90
sj26 < 75



select * from inhe_kpikh where name='王占颍';
select * from inhe_kpikhmx2 where jldh in(select jldh from inhe_kpikh where name='王占颍');

select * from inhe_kpikhmx1 where jldh=(select jldh from inhe_kpikh where name='何素贞');
select * from inhe_kpikhmx2

select * from user


select * from inhe_kpikh
where jldh not in(
select jldh from inhe_kpikhmx1
union all 
select jldh from inhe_kpikhmx2
union all 
select jldh from inhe_kpikhmx3
)


select * from inhe_hr_flow_prcs

insert into inhe_hr_flow_prcs(flow_ID,flow_type_id,user_id,);





select * from user where dept_id in('48',(select d.dept_parent from department d
							where d.dept_id = '48')) 

union 

SELECT * FROM user WHERE find_in_set('48', dept_id_other)
union 

select * from user where user_id in ('admin1','ysr')



select * from user  where dept_id in('17',(select d.dept_parent from department d
							where d.dept_id = '17')) or find_in_set('17', dept_id_other)  or user_id in ('INHE-0450','IM-0015','') 


select * from user  where dept_id in('48',(select d.dept_parent from department d
							where d.dept_id = '48')) or find_in_set('48', dept_id_other)  or user_id in ('glp') 


select * from inhe_kpimb where name ='' 

select * from inhe_kpimbmx1 where dh='1007';
union
select * from inhe_kpimbmx2 where dh='1007';

select * from inhe_kpimbmx3 




