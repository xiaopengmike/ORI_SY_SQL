* 来源：[PHP_QA](https://github.com/colinlet/PHP-Interview-QA)
* 时间周期：10周
## 网络篇 20201026 - 20201101
* 计算机网络体系结构
  >OSI(开放系统互联)模型：物理层-数据链路层-网络层-运输层-会话层-表示层-应用层
  >TCP/ID结构：网络接口层-网际层IP-运输层(TCP or UDP)-应用层(TELNET,FTP,SMTP等协议)
* UDP 的主要特点
  >无连接的，最大努力交付，面向报文，无拥塞控制，支持交互通信，首部开销小(8个字节)
* TCP 的主要特点
  >面向连接的运输层协议，只能有2个端点(点对点)，可靠交付，全双工通信，面向字节流
* 简述三报文握手建立 TCP 连接
  >服务器进程创建传输控制模块TCB，等待客户端请求
  >客户端创建TCB，向服务器发起连接请求报文段
  >服务器接收后同意建立连接发送确认报文段
  >客户端收到确认后回复确认报文段，进入已连接状态
  >服务器收到确认后进入已连接状态
* 建立 TCP 连接为什么最后还要发送确认
  >防止已失效的连接请求报文段又传到TCP服务器，避免产生错误
* 简述 TCP 连接的释放
  >客户端应用进程发出释放报文段，停止再送数据，进入FIN-WAIT-1(终止等待1)状态
  >服务器收到释放报文段后发送确认，进去CLOSE-WAIT(关闭等待)状态，
  >客户端收到确认后，进入FIN-WAIT-2状态，等待服务器发送连接释放报文段
  >服务器没有要发送的数据，发送释放报文段，进入LAST-ACK，等待客户端确认
  >客户端发出确认，进入TIME-WAIT状态，经过时间等待计时器设置的时间2MSL进入CLOSED状态
  >服务器收到确认后进去CLOSED
* TIME-WAIT 是什么，为什么必须等待 2MLS
  >一种TCP状态，等待2MSL可以保证客户端报文发送完整，若未发送完服务器会超时重传报文段使客户端、服务器都进入CLOSED
* TCP 粘包问题
  >
* UDP、TCP 区别，适用场景
* 建立 socket 需要哪些步骤
* DNS 主要作用是什么
* HTTP 报文组成
* HTTP 状态码
* 常见的 HTTP 方法
* GET 与 POST 请求方式区别
* HTTP 优缺点
* HTTPS 通信原理
* HTTP 2.0
* WebSocket
* IPv6 与 IPv4 有什么变化
* 什么是心跳机制
* 什么是长连接
## 数据结构与算法篇
* 概述
* 实现基础
* 线性结构
* 树
* 散列查找
* 图
* 排序
* 补充
* 经典算法题
## PHP 篇
* echo、print、print_r、var_dump 区别
* 单引号和双引号的区别
* isset 和 empty 的区别
* static、self、$this 的区别
* include、require、include_once、require_once 的区别
* 数组处理函数
* Cookie 和 Session
* 预定义变量
* 传值和传引用的区别
* 构造函数和析构函数
* 魔术方法
* public、protected、private、final 区别
* 客户端/服务端 IP 获取，了解代理透传 实际IP 的概念
* 类的静态调用和实例化调用
* PHP 不实例化调用方法
* php.ini 配置选项
* php-fpm.conf 配置
* 502、504 错误产生原因及解决方式
* 如何返回一个301重定向
* PHP 与 MySQL 连接方式
* MySQL、MySQLi、PDO 区别
* MySQL 连接池
* 代码执行过程
* base64 编码原理
* ip2long 实现
* MVC 的理解
* 主流 PHP 框架特点
* 对象关系映射/ORM
## Web 篇
* SEO 有哪些需要注意的
* img 标签的 title 和 alt 有什么区别
* CSS 选择器的分类
* CSS sprite 是什么，有什么优缺点
* display: none 与 visibility: hidden 的区别
* display: block 和 display: inline 的区别
* CSS 文件、style 标签、行内 style 属性优先级
* link 与 @import 的区别
* 盒子模型
* 容器包含若干浮动元素时如何清理(包含)浮动
* 如何水平居中一个元素
* 如何竖直居中一个元素
* flex 与 CSS 盒子模型有什么区别
* Position 属性
* PNG,GIF,JPG 的区别及如何选
* 为什么把 JavaScript 文件放在 Html 底部
* JavaScript 数据类型
* JavaScript 操作 DOM 的方法有哪些
* JavaScript 字符串方法有哪些
* JavaScript 字符串截取方法有哪些？有什么区别
* setTimeout 和 setInterval 的区别
* 使用 new 操作符实例化一个对象的具体步骤
* 如何实现 ajax 请求
* 同源策略是什么
* 如何解决跨域问题
* 引起内存泄漏的操作有哪些
* 闭包理解及应用
* 对 JavaScript 原型的理解
* 对 JavaScript 模块化的理解
* 如何判断网页中图片加载成功或者失败
* 如何实现懒加载
* JSONP 原理
* Cookie 读写
* 从浏览器地址栏输入 URL 到显示页面的步骤
* Vue.js 双向绑定原理
* 如何进行网站性能优化
* 渐进增强
## MySQL 篇
* 体系结构
* 基础操作
* 数据库设计范式
* 数据库设计原则
* CHAR 和 VARCHAR 数据类型区别
* LEFT JOIN 、RIGHT JOIN、INNER JOIN
* UNION、UNION ALL
* 常用 MySQL 函数
* 锁
* 事务
* 常见存储引擎
* 常见索引
* 聚族索引与非聚族索引的区别
* BTree 与 BTree-/BTree+ 索引原理
* 分表数量级
* EXPLAIN 输出格式
* my.cnf 配置
* 慢查询
## Redis 篇
* Redis 介绍
* Redis 特点
* Redis 支持哪些数据结构
* Redis 与 Memcache 区别
* 发布订阅
* 持久化策略
* Redis 事务
* 如何实现分布式锁
* Redis 过期策略及内存淘汰机制
* 为什么 Redis 是单线程的
* 如何利用 CPU 多核心
* 集合命令的实现方法
* 有序集合命令的实现方法
* redis.conf 配置
* 慢查询
## Linux 篇
* Linux 目录结构
* Linux 基础
* 命令与文件查找
* 数据流重定向
* sed
* awk
* 计划任务
* Vim
* 负载查看
* Linux 内存管理
* 进程、线程、协程区别
* 进程间通信与信号机制
## 安全篇
* 跨站脚本攻击(XSS)
* 跨站点请求伪造(CSRF)
* SQL 注入
* 应用层拒绝服务攻击
* PHP 安全
* 伪随机数和真随机数
## 设计模式篇
* 什么是设计模式
* 如何理解框架
* 主要设计模式
* 怎样选择设计模式
* 单例模式
* 抽象工厂模式
* 工厂方法模式
* 适配器模式
* 观察者模式
* 策略模式
* OOP 思想
* 抽象类和接口
* 控制反转
* 依赖注入
## 架构篇
* OAuth 2.0
* 单点登录
* REST
* API 版本兼容
* JWT
* 画出 PHP 业务架构图
* LVS
* Ngnix
* 服务化
* 微服务
* 服务注册发现
* 数据库读写分离
* 数据库拆分
* 分布式事务
* ID 生成器
* 一致性哈希
* Redis 集群
* 消息队列
* 穿透、雪崩
* 限流(木桶、令牌桶)
* 服务降级
* 语言对比