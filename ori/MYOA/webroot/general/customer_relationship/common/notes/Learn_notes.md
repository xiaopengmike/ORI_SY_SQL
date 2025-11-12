## 学习笔记
Lang: 
Python Java Js C/C++ 
Go Rust Ada Dart Lua Julia Delphi 
Ts Kotlin OC

### resource
* Java项目实践[https://how2j.cn/frontroute]
* CS基础[https://www.cyc2018.xyz/]
* CS-leetcode[https://labuladong.gitbook.io/algo/]
* Java基础[http://hollischuang.gitee.io/tobetopjavaer/#/menu]
* leetcode[https://leetcode-cn.com/leetbook/read/top-interview-questions-easy/x2i30g/]

### Plan

### Notes
#### tobetopjavaer
##### 基础篇
**面向对象**
面向过程：问题分解细化，一个步骤 = 一个函数 ，
面向对象：将事务抽象化，对象调用组合解决问题
一个对象 = 行为 + 属性
合理构造，不重复造轮子，一个函数适用重复使用
三大特征：封装 继承 多态
五大原则：
单一职责原则 - 低耦合高内聚
开放封闭原则 - 可扩展但不可修改
Liskov替换原则 - 子类必须能够替换其基类
依赖倒置原则 - 依赖于抽象而不依赖于模块，针对接口编程不针对实现
接口隔离原则 - 使用多个小的专门的接口，而不要使用一个大的总接口，接口内聚化

## 实现财富自由
1.控制消费欲望 - 资产增值
2.建立第二种收入 - 拥有核心能力的前提下发掘副业(可预见性工作和不可预见性工作)
3.把工作当作一种投资 - 给期权股票的靠谱公司
4.打造价值杠杆 - 
5.认知套利 - 思考总结方法论
* road:
  * 1、加入独角兽公司，拿到大量期权，并坚守到上市套现。
  * 2、找到靠谱的创业公司和诚信的创始人，拿到百分比左右的股份，坚守到出售或者上市。
  * 3、通过技术创业，公司出售或者上市。
  * 4、建立影响力，通过技术咨询、自媒体、技术入股等方式实现财富自由。
  * 5、经历长时间职场竞争，最终成长为公司高管，通过股份实现财富自由。
  * 6、积累一定的原始资本，通过投资理财（炒股、btc、投资朋友公司）致富发家。
独角兽公司:
大疆 微众银行 货拉拉 柔宇科技 魅族 兴盛优选 云从科技 优必选 
新瑞鹏 华大智造 云网万店 
955: 酷安 Red Hat Shopee Snap SUSE ThoughtWorks 

## Mysql
* MySQL索引分为普通索引、唯一索引、主键索引、组合索引、全文索引。
索引不会包含有null值的列，索引项可以为null（唯一索引、组合索引等），但是只要列中有null值就不会被包含在索引中
（1）普通索引： create index index_name on table(column)；or create table(..., index index_name column);
（2）唯一索引： 类似普通索引，索引列的值必须唯一（可以为空） create unique index index_name on table(column)；
（3）主键索引： 特殊的唯一索引，不允许为空，只能有一个，一般是在建表时指定primary key(column)
（4）组合索引： 在多个字段上创建索引，遵循最左前缀原则。alter table t add index index_name(a,b,c);
（5）全文索引： 主要用来查找文本中的关键字，不是直接与索引中的值相比较，像是一个搜索引擎，配合match against使用，现在只有char，varchar，text上可以创建全文索引。
    在数据量较大时，先将数据放在一张没有全文索引的表里，然后再利用create index创建全文索引，比先生成全文索引再插入数据快很多。
* 何时使用索引:
    * 主键，unique字段；
    * 和其他表做连接的字段需要加索引；关联字段
    * 在where里使用＞，≥，＝，＜，≤，is null和between等字段；判断条件字段
    * 使用不以通配符开始的like，where A like 'China%'；
    * 聚集函数MIN()，MAX()中的字段；
    * order by和group by字段；
* 不使用索引情况：
    * 表记录太少；
    * 数据重复且分布平均的字段（只有很少数据值的列）；
    * 经常插入、删除、修改的表要减少索引；
    * text，image等类型不应该建立索引，这些列的数据量大（假如text前10个字符唯一，也可以对text前10个字符建立索引）；
    * MySQL能估计出全表扫描比使用索引更快时，不使用索引；
* explain语句 type字段为All，未使用索引；为ref，使用索引
  * ALL： 全表扫描
  * index： 索引全扫描
  * range： 索引范围扫描，常用语<,<=,>=,between等操作
  * ref： 使用非唯一索引扫描或唯一索引前缀扫描，返回单条记录，常出现在关联查询中
  * eq_ref： 类似ref，区别在于使用的是唯一索引，使用主键的关联查询
  * const/system： 单条记录，系统会把匹配行中的其他列作为常数处理，如主键或唯一索引查询
  * null： MySQL不访问任何表或索引，直接返回结果
  * 还有key字段表示用到的索引，没有用到为null

