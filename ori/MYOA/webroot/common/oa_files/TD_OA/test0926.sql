select DEPT_ID,GS from Interview where JLDH='MS20190924001'
select * from department where DEPT_ID='48'


select * from kpiqx
insert into kpiqx VALUES('tj','tj')

select * from kpikh

select * from user
select * from department

select * from Interview


select * from inhe_kpikh where ZQ = '2019年09月'
select * from kpikh

select @rd := @rd+1 as ROW_NUM, b.* from (select @rd:=0 row_init,  
d.DEPT_NAME, u.USER_NAME, u.USER_PRIV_NAME, yd.ZHD P_SCORE, jd.sj11 S_SCORE, jd.sj26 B_SCORE 
from user u
left join department d on d.DEPT_ID = u.dept_id
LEFT JOIN inhe_kpikh yd on yd.NAME = u.USER_NAME and yd.BM = d.DEPT_NAME and yd.ZQ = '2019年09月'
left join kpikh jd on jd.NAME = u.USER_NAME and jd.BM = d.DEPT_NAME and jd.BT = '2019第3季度'
-- where yd.ZQ = '2019年09月'
) b


select max(JLDH) from Interview


MS20190924002


select * from rzdj



select *  from rsyd

SELECT * FROM LZSQ

select * from gzjj


select * from department





