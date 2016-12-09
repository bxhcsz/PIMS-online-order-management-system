SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE `pims_acl` (
  `aclid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `controller` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acl_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`aclid`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=176 ;

CREATE TABLE `pims_adminuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acl` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qx` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logtime` datetime DEFAULT NULL,
  `logip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logarea` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

CREATE TABLE `pims_banks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bankname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `banknum` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `realname` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `pims_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sendaddr` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `smtpaddr` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `username` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `userpass` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `getaddr` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `is_open` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

CREATE TABLE `pims_fajian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sendname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sendgongsi` text COLLATE utf8_unicode_ci,
  `sendaddr` text COLLATE utf8_unicode_ci,
  `sendtel` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sendmob` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sendyoubian` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `daishou` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beizhu` text COLLATE utf8_unicode_ci,
  `huodao` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

CREATE TABLE `pims_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `fname` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `musts` tinyint(1) DEFAULT NULL,
  `types` int(2) DEFAULT NULL,
  `olds` tinyint(1) DEFAULT NULL,
  `elseoption1` tinyint(1) DEFAULT NULL,
  `elseoption2` tinyint(1) DEFAULT NULL,
  `paixu` int(3) DEFAULT NULL,
  `payment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paytype` tinyint(1) DEFAULT NULL,
  `yzm` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

CREATE TABLE `pims_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `zhekou` varchar(20) DEFAULT '0',
  `is_form` tinyint(1) DEFAULT NULL,
  `ticheng` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

CREATE TABLE `pims_guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `writter` varchar(50) DEFAULT NULL,
  `mess` text,
  `addtime` datetime DEFAULT NULL,
  `ips` varchar(50) DEFAULT NULL,
  `areas` varchar(100) DEFAULT NULL,
  `remess` text,
  `retime` datetime DEFAULT NULL,
  `gid` int(9) DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `pims_his` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kefu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `totle` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ticheng` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dotime` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE `pims_interfaces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `types` tinyint(1) DEFAULT NULL,
  `zhanghao` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `jianyanma` text,
  `hezuoid` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `mingcheng` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `urladdress` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

CREATE TABLE `pims_loginlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `logtime` datetime DEFAULT NULL,
  `logip` varchar(50) DEFAULT NULL,
  `logarea` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=299 ;

CREATE TABLE `pims_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dowhat` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `dotime` datetime DEFAULT NULL,
  `doip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `areas` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=387 ;

CREATE TABLE `pims_mac` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_open` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macaddr` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

CREATE TABLE `pims_mob` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zt` tinyint(1) DEFAULT NULL,
  `mail139` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE `pims_moban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `pims_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` varchar(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `writter` varchar(50) DEFAULT NULL,
  `addtime` date DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `pims_newsort` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sortname` varchar(100) DEFAULT NULL,
  `sortfile` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `pims_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` varchar(20) DEFAULT NULL,
  `ordernum` varchar(50) DEFAULT NULL,
  `pname` varchar(255) DEFAULT NULL,
  `price` varchar(20) DEFAULT NULL,
  `totle` varchar(20) DEFAULT NULL,
  `nums` varchar(20) DEFAULT NULL,
  `realname` varchar(100) DEFAULT NULL,
  `mob` varchar(20) DEFAULT NULL,
  `address` text,
  `beizhu` text,
  `useroption` text,
  `payment` int(2) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `ips` varchar(50) DEFAULT NULL,
  `areas` varchar(100) DEFAULT NULL,
  `zt1` tinyint(1) DEFAULT NULL,
  `zt2` tinyint(1) DEFAULT NULL,
  `wuliu` varchar(255) DEFAULT NULL,
  `wuliunum` varchar(255) DEFAULT NULL,
  `kefu` varchar(100) DEFAULT NULL,
  `beizhu2` text,
  `sendtime` datetime DEFAULT NULL,
  `url1` varchar(255) DEFAULT NULL,
  `url2` varchar(255) DEFAULT NULL,
  `fromdomain` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

CREATE TABLE `pims_passport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yuming` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `mima` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE `pims_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` varchar(20) DEFAULT NULL,
  `pname` varchar(255) DEFAULT NULL,
  `price` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

CREATE TABLE `pims_rings` (
  `id` int(11) NOT NULL,
  `types` tinyint(1) DEFAULT NULL,
  `usedsound` varchar(100) DEFAULT NULL,
  `urls` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `pims_rubbish` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` varchar(20) DEFAULT NULL,
  `ordernum` varchar(50) DEFAULT NULL,
  `pname` varchar(255) DEFAULT NULL,
  `price` varchar(20) DEFAULT NULL,
  `totle` varchar(20) DEFAULT NULL,
  `nums` varchar(20) DEFAULT NULL,
  `realname` varchar(100) DEFAULT NULL,
  `mob` varchar(20) DEFAULT NULL,
  `address` text,
  `beizhu` text,
  `useroption` text,
  `payment` int(2) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `ips` varchar(50) DEFAULT NULL,
  `areas` varchar(255) DEFAULT NULL,
  `zt1` tinyint(1) DEFAULT NULL,
  `zt2` tinyint(1) DEFAULT NULL,
  `wuliu` varchar(255) DEFAULT NULL,
  `wuliunum` varchar(255) DEFAULT NULL,
  `kefu` varchar(100) DEFAULT NULL,
  `beizhu2` text,
  `sendtime` datetime DEFAULT NULL,
  `url1` varchar(255) DEFAULT NULL,
  `url2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `pims_send` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `gid` int(9) DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `pims_sendtime` (
  `id` int(11) NOT NULL,
  `sendtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `pims_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_open` tinyint(1) DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `passwd` varchar(255) DEFAULT NULL,
  `message` text,
  `nums` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
