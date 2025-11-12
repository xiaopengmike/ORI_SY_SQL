-- 更新销售排名数据
select * from sales_performance where cycle  = '2020年06月' order by id desc;

update sales_performance set sales_proportion = sales_ranking where cycle  = '2020年06月';

update sales_performance a left join(
select @rd := @rd+1 as ROW_NUM, b.id,b.sales_proportion from (
select 
@rd:=0 row_init,  
	 s.* from sales_performance s where s.cycle = '2020年06月' 
order by sales_proportion*1 desc 
 ) b ) t on t.id = a.id 
set a.sales_ranking = t.ROW_NUM
where a.sales_proportion > 0 and a.cycle  = '2020年06月';


update sales_performance set sales_ranking = CONCAT(FORMAT(sales_ranking*100/8,2),'%')  where cycle  = '2020年06月' and sales_ranking>0 ;

-- 备份回滚数据使用
update sales_performance set sales_proportion = CONCAT(FORMAT(person_actual/com_actual*100,2),'%') where cycle  = '2020年06月';
update sales_performance set sales_ranking = sales_proportion where cycle  = '2020年06月';

update sales_performance a 
left join user u on u.user_name = a.user_name 
left join department d on d.dept_name = a.bm_name 
set a.user_id = u.user_id, a.bm_id = d.dept_id
where a.cycle  = '2020年06月' 

select * from sales_performance_director where cycle  = '2020年06月' order by id desc;

update sales_performance_director a 
left join user u on u.user_name = a.user_name 
left join department d on d.dept_name = a.bm_name 
set a.user_id = u.user_id, a.bm_id = d.dept_id
where a.cycle  = '2020年06月' 
-- 公共考核分数
select * from inhe_public_kh where cycle = '2020年06月'
-- 一键纠正分数，已有自评分和审核分，未正确计算核定分及总分
select * from inhe_kpikh where ZQ='2020年06月' and final_score = '0.00';

update inhe_kpikhmx1 set hdf = format(shf*byzd5/100,2) where hdf= '' and jldh =11510;

select sum(hdf) from inhe_kpikhmx1 where jldh = 11510;

update inhe_kpikh set zhd ='88.00',final_score='88.00' where jldh = 11510;

-- 
select jldh,format(sum(hdf),2) zhd from inhe_kpikhmx1  where jldh >= 11313 group by jldh order by jldh

select name,jldh,zhd,final_score from inhe_kpikh where jldh >= 11313

select * from user where uid = 785
select * from inhe_kpikh where uid = 785


