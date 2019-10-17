/**
 * 目录说明
 */
document: 项目相关文档
weapp:    项目代码目录
	|-- model                 数据模型层
		|-- control_settings  遥控页面设置model
	|-- utils                 组件类js模块
		|-- ui.js             ui封装
		|-- util.js           util方法封装
		|-- wapi.js           小程序api封装
		|-- tvnetwork.js      与TV端wensocket通信封装
	|-- pages                 页面文件夹
		|-- device_discovery  设备发现/链接页面
		|-- remote_control    遥控页面
		|-- proxy             代理页面, 微信扫码或直接打开小程序的着陆页
		|-- web_view          通用h5承载页面
	|-- app.js                启动入口
	|-- config.js             项目相关配置文件


/**
 * 开发接入说明
 * 小程序文档: https://developers.weixin.qq.com/miniprogram/dev/framework/
 * 视图层说明: https://developers.weixin.qq.com/miniprogram/dev/framework/view/
 */
 
1、开发工具
	下载微信开发者工具: https://developers.weixin.qq.com/miniprogram/dev/devtools/devtools.html

2、本地跑起项目
	2.1 扫码微信登录开发者工具
	2.2 项目路径指向: weapp文件夹
	2.3 appid输入: wx341201a1ea2b0376 (内部测试小程序appid)、wx7095eb6ab01998b0 (正式环境小程序)

3、管理台登录
	帐号/密码: mattxie@tencent.com/kt2019


/**
 * 版本更新说明
 */
1.0.0:
	上线小程序
	mDNS设备发现
	上下左右按键/语音/文字遥控
	
	
	
	
	
	
	
	
	
