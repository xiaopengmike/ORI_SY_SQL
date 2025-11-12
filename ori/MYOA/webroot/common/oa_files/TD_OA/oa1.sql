select 
d.DEPT_NAME, u.USER_NAME, u.USER_PRIV_NAME
from user u
left join department d on d.DEPT_ID = u.dept_id


select @rd := @rd+1  as rownum, b.DEPT_NAME, b.USER_NAME, b.USER_PRIV_NAME from (select @rd:=0,  
d.DEPT_NAME, u.USER_NAME, u.USER_PRIV_NAME
from user u
left join department d on d.DEPT_ID = u.dept_id
) b


select @rd := @rd+1  as rownum, b.name from (select @rd:=0, name from test) b


select * from inhe_kpikh
select * from inhe_kpikhmx1


select @rd := @rd+1 as ROW_NUM, b.* from (select @rd:=0 row_init, 
d.DEPT_NAME, u.USER_NAME, u.USER_PRIV_NAME, yd.ZHD P_SCORE, jd.sj11 S_SCORE, 
jd.sj26 B_SCORE from user u left join department d on d.DEPT_ID = u.dept_id
 LEFT JOIN inhe_kpikh yd on yd.NAME = u.USER_NAME and yd.BM = d.DEPT_NAME 
left join kpikh jd on jd.NAME = u.USER_NAME and jd.BM = d.DEPT_NAME 
ORDER BY d.dept_no
) b 


select * from department ORDER BY dept_no



