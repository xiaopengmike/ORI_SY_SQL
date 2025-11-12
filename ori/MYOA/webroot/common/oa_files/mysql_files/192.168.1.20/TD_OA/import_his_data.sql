select * from crscell.crs_tableindex where repid = 569

desc crscell.crs_tabledata2263

select * from crscell.crs_tabledata2263 where userid = 'INHE-0900'

select col27154, col27192, col27193, col27194, col27195, col27196, col27197, col27198, 'INHEGRID'
from crscell.crs_tabledata2188 a left join crscell.crs_tabledata2185 b on a.writetime = b.writetime

select * from inhe_number_participant 

desc inhe_number_participant_item

insert into inhe_number_participant_item(form_id,serial_number,user_id,user_name,proportion,user_bu)
select col28559,col28550,'',col28552,col28551,'INHEGRID'
from crscell.crs_tabledata2338 a left join crscell.crs_tabledata2339 b on a.writetime = b.writetime
