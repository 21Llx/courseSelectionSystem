/*
SQLyog Professional v12.08 (64 bit)
MySQL - 5.5.53 : Database - courseselectionsystem
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`courseselectionsystem` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `courseselectionsystem`;

/*Table structure for table `admin` */

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `adminID` int(6) unsigned zerofill NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`adminID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `admin` */

insert  into `admin`(`adminID`,`password`,`name`) values (000001,'123321','admin'),(000002,'123321','admin2');

/*Table structure for table `applycur` */

DROP TABLE IF EXISTS `applycur`;

CREATE TABLE `applycur` (
  `curID` int(8) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `curName` varchar(255) NOT NULL,
  `curTeacher` varchar(255) NOT NULL,
  `teacherID` int(6) unsigned zerofill NOT NULL,
  `curDuration` varchar(255) NOT NULL,
  `curBeginTime` varchar(255) NOT NULL,
  `curEndTime` varchar(255) NOT NULL,
  `curPeoples` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `least` int(11) NOT NULL DEFAULT '1',
  `evaluate` float NOT NULL DEFAULT '0.1',
  `curDetail` varchar(255) NOT NULL,
  `state` int(1) NOT NULL DEFAULT '0' COMMENT '0为审核中，1为成功，2为失败',
  `applyfail` varchar(255) DEFAULT NULL COMMENT '申请失败理由',
  PRIMARY KEY (`curID`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `applycur` */

insert  into `applycur`(`curID`,`curName`,`curTeacher`,`teacherID`,`curDuration`,`curBeginTime`,`curEndTime`,`curPeoples`,`image`,`least`,`evaluate`,`curDetail`,`state`,`applyfail`) values (00000012,'Webpack教程','戴应',000004,'10','2020-02-13-00:00:00','2020-03-25-00:00:00',80,'images/curImgs/1582356504.jpg',10,2,'学习webpack4.0的操作和使用',0,NULL),(00000013,'node.js','赵丽伟',000001,'12','2020-02-27-00:00:00','2020-03-12-00:00:00',80,'images/curImgs/1582436505.jpg',10,2.3,'学习node的操作和使用',2,'课程时间过长');

/*Table structure for table `choosecur` */

DROP TABLE IF EXISTS `choosecur`;

CREATE TABLE `choosecur` (
  `curID` int(8) unsigned zerofill NOT NULL,
  `curName` varchar(255) NOT NULL,
  `stuID` int(6) unsigned zerofill NOT NULL,
  `chooseTime` varchar(255) NOT NULL,
  `endTime` varchar(255) NOT NULL,
  `curTeacher` varchar(255) NOT NULL,
  `curTime` varchar(255) NOT NULL,
  `state` int(1) NOT NULL DEFAULT '1' COMMENT '课程状态，1为未结束',
  `StuEvaluate` varchar(255) DEFAULT NULL COMMENT '学生对课程的评价',
  `teacherEva` int(2) DEFAULT '0' COMMENT '教师给学生的评分',
  `StuEvaluateTime` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `choosecur` */

insert  into `choosecur`(`curID`,`curName`,`stuID`,`chooseTime`,`endTime`,`curTeacher`,`curTime`,`state`,`StuEvaluate`,`teacherEva`,`StuEvaluateTime`) values (00000005,'HTML+CSS 轻松入门教程',000001,'2020-02-21-19:47:39','2020-02-22-19:47:39','赵丽伟','16',1,NULL,0,NULL),(00000001,'Web前端开发极速入门',000002,'2020-02-15-14:24:10','2020-02-16-14:24:10','赵丽伟','20',0,NULL,0,NULL),(00000001,'Web前端开发极速入门',000001,'2020-02-15-14:23:49','2020-02-16-14:23:49','赵丽伟','20',0,NULL,0,NULL),(00000001,'Web前端开发极速入门',000123,'2020-02-15-14:19:02','2020-02-16-14:19:02','赵丽伟','20',0,NULL,0,NULL),(00000005,'HTML+CSS 轻松入门教程',000123,'2020-02-15-14:19:08','2020-02-16-14:19:08','赵丽伟','16',1,NULL,0,NULL),(00000007,'JQuery 基础入门教程',000001,'2020-02-21-19:47:46','2020-02-22-19:47:46','杨朝来','15',1,NULL,0,NULL);

/*Table structure for table `curriculum` */

DROP TABLE IF EXISTS `curriculum`;

CREATE TABLE `curriculum` (
  `curID` int(8) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `curName` varchar(255) DEFAULT NULL,
  `curTeacher` varchar(255) DEFAULT NULL,
  `teacherID` int(6) unsigned zerofill DEFAULT NULL,
  `curDuration` varchar(255) DEFAULT NULL,
  `curBeginTime` varchar(255) DEFAULT NULL,
  `curEndTime` varchar(255) DEFAULT NULL,
  `curPeoples` int(11) DEFAULT NULL,
  `curPeople` int(11) DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `least` int(11) DEFAULT '1',
  `evaluate` float DEFAULT '5',
  `curDetail` varchar(255) DEFAULT NULL,
  `curState` varchar(255) DEFAULT '未结束',
  PRIMARY KEY (`curID`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

/*Data for the table `curriculum` */

insert  into `curriculum`(`curID`,`curName`,`curTeacher`,`teacherID`,`curDuration`,`curBeginTime`,`curEndTime`,`curPeoples`,`curPeople`,`image`,`least`,`evaluate`,`curDetail`,`curState`) values (00000001,'Web前端开发极速入门','赵丽伟',000001,'20','2020-02-01-00:00:00','2020-02-06-00:00:00',100,3,'images/curImgs/1580560098.jpg',1,0.8,'学习内容： (1)HTML常用标签的使用技巧; (2)CSS元素样式控制与网站布局的技巧; (3)大型商业网站前端页面的搭建流程; (4)HTML+CSS综合运用与经验分享','已结束'),(00000002,'CSS的美化功能','赵丽伟',000001,'18','2020-02-02-00:00:00','2020-02-03-00:00:00',100,0,'images/curImgs/1580560417.jpg',1,1,'css教程之《CSS的美化功能》使用CSS技术对网页进行美化处理，通过实际案例讲解让大家掌握并灵活运用CSS知识','已结束'),(00000003,'HTML开发个人博客教程','赵丽伟',000001,'18','2020-02-01-00:00:00','2020-02-02-00:00:00',70,0,'images/curImgs/1580560532.jpg',10,3,'综合运用html+css进行博客整站的设计,其中包括登录页面、注册页面、首页、列表也和详细页面。','已结束'),(00000005,'HTML+CSS 轻松入门教程','赵丽伟',000001,'16','2020-02-01-00:00:00','2020-02-29-00:00:00',100,2,'images/curImgs/1580561265.jpg',1,0.7,'在《HTML+CSS 轻松入门教程》课程当中，将讲解html的基础知识，通过基础知识，了解网页的基础构成','未结束'),(00000006,'javascript初级教程','杨朝来',000002,'20','2020-02-01-00:00:00','2020-02-29-00:00:00',100,1,'images/curImgs/1580561464.jpg',1,0.8,'《javascript初级教程》是JavaScript的入门课程，旨在让大家认识，了解JavaScript的常见知识','未结束'),(00000007,'JQuery 基础入门教程','杨朝来',000002,'15','2020-02-01-00:00:00','2020-02-29-00:00:00',90,1,'images/curImgs/1580561593.jpg',10,1.5,'JQuery教程之《JQuery 基础入门教程》是php中文网为大家提供的jQuery基础课程，将详细向大家介绍jQuery的基础知识知识包括jQuery选择器，jQuery的语法 ，jQuery节点操作，jQuery事件处理等','未结束'),(00000008,'Vue框架视频教程','杨朝来',000002,'20','2020-02-01-00:00:00','2020-02-29-00:00:00',80,0,'images/curImgs/1580562117.jpg',15,2,'Vue (读音 /vjuː/，类似于 view) 是一套用于构建用户界面的渐进式框架。与其它大型框架不同的是，Vue 被设计为可以自底向上逐层应用','未结束'),(00000009,'React从入门到精通视频教程','杨朝来',000002,'20','2020-02-01-00:00:00','2020-02-29-00:00:00',80,0,'images/curImgs/1580562391.jpg',15,2,'React 起源于 Facebook 的内部项目，因为该公司对市场上所有 JavaScript MVC 框架，都不满意，就决定自己写一套，用来架设Instagram 的网站。做出来以后，发现这套东西很好用，于是在2013年5月开源了。由于 React的设计思想极其独特，属于革命性创新，性能出众，代码逻辑却非常简单。所以，越来越多的人开始关注和使用，认为它可能是将来 Web 开发的主流工具。','未结束'),(00000010,'Javascript - ES6','杨朝来',000002,'10','2020-02-01-00:00:00','2020-02-29-00:00:00',90,0,'images/curImgs/1580562579.jpg',10,1.2,'《Javascript - ES6实战视频课程》讲解了运行环境已支持的 ES6 特性，而忽略掉未支持的特性，随着 Node.js 、babel 和浏览器等 ES6 运行环境的逐步支持，本套视频课程的内容也会补充。这样授课的好处是可以让学员直接在开发中实际的应用 ES6 新特性，也避免学习那些未支持的特性造成的混淆。','未结束'),(00000011,'PHP零基础教程','李毅',000003,'21','2020-02-01-00:00:00','2020-02-29-00:00:00',100,0,'images/curImgs/1580563060.jpg',1,0.8,'PHP是通用的服务器端脚本语言，全球约有70%的网站后台采用PHP进行开发,在国内的Web开发领域，PHP也处于领先地位，所以掌握PHP开发技能, 不仅是从事Web开发的基本要求,也是升职加薪的有效途径~~ 为帮助0基础学员在学习的时候少走弯路，php中文网制定了一个清晰完整的php学习路径，包含视频图文教程、工具资源等，想快速成为php开发工程师的同学们按照路径学习即可！','未结束'),(00000012,'Thinkphp','李毅',000003,'16','2020-02-01-00:00:00','2020-02-29-00:00:00',80,0,'images/curImgs/1580563168.jpg',15,2,'通过对ThinkPHP框架基础知识的讲解，带领大家由浅入深轻松掌握ThinkPHP的理论基础，更加全面的掌握ThinkPHP框架运行机制，帮助大家在实际工作中使用ThinkPHP框架实现快速开发。','未结束'),(00000013,'Laravel电商项目实战课程','李毅',000003,'16','2020-02-01-00:00:00','2020-02-29-00:00:00',50,0,'images/curImgs/1580563328.jpg',20,3,'《Laravel电商项目实战课程》课程讲解了Laravel项目的开发，学完该课程，就可以模仿开发一个电商项目了','未结束'),(00000014,'MySQL基础教程','周高华',000004,'15','2020-02-01-00:00:00','2020-02-29-00:00:00',100,0,'images/curImgs/1580563443.jpg',5,0.6,'在课程中，将讲解MySQL的基础知识，让学习者充分的认识mysql！','未结束'),(00000015,'MySQLi面向对象编程极速入门','周高华',000004,'14','2020-02-01-00:00:00','2020-02-29-00:00:00',60,0,'images/curImgs/1580563545.jpg',15,2.5,'mysql教程之《MySQLi面向对象编程极速入门》为php中文网原创录制的视频教程。MySQLi做为传统MySQL的增强版本,使用越来越广泛,根据程序员编程习惯分为:面向过程和面向对象二种类型,本套课程以面向对象为主,详细介绍了常用的属性和方法，进行CURD操作。','未结束'),(00000016,'Python教程','戴应',000005,'20','2020-02-01-00:00:00','2020-02-29-00:00:00',100,0,'images/curImgs/1580563685.jpg',1,0.8,'它将介绍Python语言的特点和适用范围，Python基本的数据类型，条件判断和循环，函数，以及Python特有的切片和列表生成式。希望本python教程能够让您快速入门并编写简单的Python程序。','未结束'),(00000017,'Python Scrapy 网络爬虫实战视频教程','戴应',000005,'17','2020-02-01-00:00:00','2020-02-29-00:00:00',80,0,'images/curImgs/1580563755.jpg',10,1.3,'《Python Scrapy 网络爬虫实战视频教程》让我们带你揭开爬虫的神秘面纱,掌握爬虫的基本原理,深入理解scrapy框架,使用scrapy进行项目实战,解决在数据抓取过程中遇到的常见问题','未结束'),(00000018,'Python高级运维实战精品进阶教程','戴应',000005,'13','2020-02-01-00:00:00','2020-02-22-00:00:00',50,0,'images/curImgs/1580563876.jpg',20,3.4,'Python, 是一种面向对象、解释型计算机程序设计语言，由Guido van Rossum于1989年发明，第一个公开发行版发行于1991年。 Python是纯粹的自由软件， 源代码和解释器CPython遵循 GPL(GNU General Public License)协议 。 Python语法简洁清晰，特色之一是强制用空白符(white space)作为语句缩进。 Python具有丰富和强大的库。它常被昵称为胶水语言，能够把用其他语言制作的各种模块','未结束');

/*Table structure for table `students` */

DROP TABLE IF EXISTS `students`;

CREATE TABLE `students` (
  `ID` int(6) unsigned zerofill NOT NULL,
  `userName` varchar(255) NOT NULL,
  `password` varchar(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` char(11) DEFAULT NULL,
  `qq` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `university` varchar(255) DEFAULT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `paid` varchar(255) NOT NULL DEFAULT '5',
  `state` varchar(20) NOT NULL DEFAULT '正常',
  `choosed` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `students` */

insert  into `students`(`ID`,`userName`,`password`,`name`,`phone`,`qq`,`email`,`university`,`grade`,`paid`,`state`,`choosed`) values (123321,'QQ','123321','赖林靓','12345678902',NULL,NULL,NULL,NULL,'5','正常',1),(000001,'PX','123456','郑贤琳','12345678903','823145731','PX@qq.com','北京大学','计算1692','19','正常',1),(000002,'PP','123321','Peter','12345678901',NULL,NULL,NULL,NULL,'20','正常',1),(000003,'ZZZ','123321','梁勇','12345678901','111111','12321@13.com','集美大学','计算17','5','正常',1),(000010,'','123321','张三','12345670111','123321','qwe@qq.com','','财经1792','5','正常',0),(000123,'','123456','赖林翔','12345678901','123456','wqe@qq.com','','计算16','22','正常',-1);

/*Table structure for table `teacher` */

DROP TABLE IF EXISTS `teacher`;

CREATE TABLE `teacher` (
  `ID` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(22) DEFAULT NULL,
  `state` varchar(255) NOT NULL DEFAULT '正常',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `teacher` */

insert  into `teacher`(`ID`,`name`,`password`,`phone`,`state`) values (000003,'李毅','123321','12345678903','正常'),(000001,'赵丽伟','123456','12345678909','正常'),(000002,'杨朝来','123321','12345678902','正常'),(000004,'周高华','123321','12345678904','正常'),(000005,'戴应','123321','12345678905','正常'),(000006,'黄良英','123321','12345678906','正常'),(000007,'杨云宵','123321','12345678907','正常'),(000008,'张月阳','123321','12345678908','正常'),(000009,'彭召龙','123321','12345678909','正常'),(000010,'欧阳俊','123321','12345678910','正常'),(000011,'沈秋爱','123321','12345678911','正常'),(000012,'肖柳岩','123321','12345678912','正常'),(000013,'杨秀武','123321','12345678913','正常'),(000014,'谢飞','123321','12345678914','正常'),(000015,'林彬','123321','12345678915','正常'),(000016,'黄代明','123321','12345678916','正常'),(000017,'宋涛','123321','12345678917','正常'),(000018,'魏小萍','123321','12345678918','正常');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
