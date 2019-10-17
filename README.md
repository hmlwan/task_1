# weixinact
一、环境配置
------
###### 1、测试环境：test.tv.video.qq.com  机器：10.213.232.12
###### 2、正式环境：tv.video.qq.com  机器: d.webdev.com
###### 3、代码路径：/data/website/weixinact 
###### 4、正式公众号：
###### wx7b9044085d71f4f5:f76db8e389842e32d6a439d1ac5969ee
###### 测试公众号：
###### wx609e1bb1b1398a6d:206a6a78da40bcb6baca6fefe347f10b

二、项目代码目录
------
Application: 项目代码目录
		|-- Api											
		|   |-- Controller
		|   |   `-- CspController.class.php 			命中浏览器上报接口
		|-- Home
		|   |-- Controller
		|   |   |-- IndexController.class.php
		|   |   |-- SpeedController.class.php
		|   |   |-- StopController.class.php
		|   |   |-- WeixinController.class.php
		|   |   |-- WsController.class.php
		|-- Jump
		|   |-- Controller
		|   |   |-- IndexController.class.php
		|   |   |-- PayinfoController.class.php
		|   |   |-- PayjumpController.class.php
		|   |   |-- ProxyjumpController.class.php
		|   |   |-- TestController.class.php
		|   |   `-- WxloginjumpController.class.php
		|-- Signin
		|   |-- Controller
		|   |   |-- ActController.class.php
		|   |   |-- CommonController.class.php
		|   |   |-- DayController.class.php
		|   |   |-- IndexController.class.php
		|   |   `-- PrizeController.class.php
		|-- Statics										运营人工配置的静态页面模块
		|   |-- Controller
		|   |   `-- IndexController.class.php
		|-- Tizen										三星launcher数据接口模块
		|   |-- Controller
		|   |   |-- LauncherController.class.php
		|   |   `-- ProxyController.class.php
		|-- Weassistor									TV语音助手项目相关接口模块
		|   `-- Controller
		|       `-- UserController.class.php
		|-- Wechat
		|   |-- Controller
		|   |   |-- BoxController.class.php
		|   |   |-- CommonController.class.php
		|   |   |-- GopayController.class.php
		|   |   |-- GuideController.class.php
		|   |   |-- HbController.class.php
		|   |   |-- IndexController.class.php
		|   |   |-- MonitorController.class.php
		|   |   |-- ProjectionController.class.php
		|   |   |-- ProxyController.class.php
		|   |   |-- TestController.class.php
		|   |   |-- TvController.class.php
		|   |   |-- UpayController.class.php
		|   |   |-- Weixin.class.php
		|   |   |-- WelfareController.class.php 
		|   |   |-- WxController.class.php 
		|-- Wx											超级影视VIP微信公众号相关接口封装模块
		|   |-- Controller
		|   |   `-- WxbController.class.php				关注/取消关注,推送消息,发送红包等接口封装
		|-- Yuyin 										厂商语音相关接口封装模块
		|   |-- Controller	
		|   |   |-- PolicyController.class.php
		|   |   `-- RecoController.class.php








