-- 20191008
-- 
select * from user

select * from SYS_LOG

select * from USER_ONLINE 

select * from user


select * from hukou
select * from company

select * from user where DEPT_ID='15'
SELECT * from department
select * from interview a LEFT JOIN interviewmx2 b on a.JLDH=b.JLDH 

SELECT * FROM wenhua
SELECT * FROM rzdj

select * from interviewmx1
select * from interviewmx2
desc interview
desc interviewmx2

select * from interview a LEFT JOIN interviewmx2 b on a.JLDH=b.JLDH where RZGH='t05'


select * from interview
select * from interviewmx2 where RZGH='t05'
select * from interviewmx1 where JLDH='MS20191009001'

-- 
SELECT * from rsyd
select * from canzhao2 where ID='1'
select * from yijian1 where DH='' and BZ=1


-- 
select * from user
select * from department
select b.MANAGER from user a,department b where a.DEPT_ID=b.DEPT_ID

select b.MANAGER from user a,department b where a.DEPT_ID=b.DEPT_ID and a.USER_NAME='易**'
select DEPT_ID from user where USER_NAME='易**'
select * from department where DEPT_ID='48'






