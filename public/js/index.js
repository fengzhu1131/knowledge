"use strict";
define(function(require, exports, module) {
	var avh = (window.innerHeight) ? window.innerHeight : (document.documentElement && document.documentElement.clientHeight) ? document.documentElement.clientHeight : document.body.offsetHeight;
	avh = avh - 64 - 43;
	var Component = Vue.extend(getVueConfig());
	var pVue = new Component().$mount('.app');
	//pVue.proModel = pVue.proModels[parseInt(Math.random() * pVue.proModels.length) - 1];
	var um = UM.getEditor('editor', {
		//imageUrl: serverPath + "imageUp.php",
		//imagePath: serverPath,
		lang: /^zh/.test(navigator.language || navigator.browserLanguage || navigator.userLanguage) ? 'zh-cn' : 'en',
		langPath: UMEDITOR_CONFIG.UMEDITOR_HOME_URL + "lang/",
		autoHeightEnabled: true,
		initialFrameHeight: 240
	});
	/**
	 * 获取初始化界面配置参数
	 */
	function getVueConfig() {
		return {
			data() {
				return {
					modal_loading: false,
					delComfirm: false,
					modalAdd: false,
					item: { //添加编辑对象
						ispublish: false, //编辑是否发布
						title: '', //编辑标题
						proModel: '',
						tree: [{
							title: 'parent 1',
							expand: true,
							render: function(h, {
								root,
								node,
								data
							}) {
								return h('span', {
									style: {
										display: 'inline-block',
										width: '100%'
									}
								}, [
									h('span', [
										h('Icon', {
											props: {
												type: 'ios-folder-outline'
											},
											style: {
												marginRight: '8px'
											}
										}),
										h('span', data.title)
									]),
									h('span', {
										style: {
											display: 'inline-block',
											float: 'right',
											marginRight: '32px'
										}
									}, [
										h('Button', {
											props: Object.assign({}, this.buttonProps, {
												icon: 'ios-plus-empty',
												type: 'primary'
											}),
											style: {
												width: '52px'
											},
											on: {
												click: function() {
													pVue.appendNode(data)
												}
											}
										})
									])
								]);
							},
							children: [{
									title: 'child 1-1',
									expand: true,
									children: [{
											title: 'leaf 1-1-1',
											expand: true
										},
										{
											title: 'leaf 1-1-2',
											expand: true
										}
									]
								},
								{
									title: 'child 1-2',
									expand: true,
									children: [{
											title: 'leaf 1-2-1',
											expand: true
										},
										{
											title: 'leaf 1-2-1',
											expand: true
										}
									]
								}
							]
						}], //导航树
						content: '' //内容
					},
					keyword: '',
					proModel: '',
					proModels: getProModelsTestData(),
					docList: [],
					layoutContentHeight: avh
				}
			},
			methods: {
				proChange: function(e) {
					this.proModel.length > 0 ? (this.keyword = '') : '';
				},
				btnSearch: function() {
					this.keyword.trim().length > 0 ? this.docList = getDocListTestData() : '';
				},
				editItem: function(e) {
					this.modalAdd = true;
					document.body.classList.add('noscroll');
				},
				delItem: function(e) {
					this.delComfirm = true;
				},
				del: function() {
					this.modal_loading = true;
					setTimeout(() => {
						this.modal_loading = false;
						this.delComfirm = false;
						this.$Message.success('删除成功');
					}, 2000);
				},
				menuSelect:function(){
					console.log(arguments);
				},
				renderContent: function(h, {
					root,
					node,
					data
				}) {
					return h('span', {
						style: {
							display: 'inline-block',
							width: '100%'
						}
					}, [
						h('span', [
							h('Icon', {
								props: {
									type: 'ios-paper-outline'
								},
								style: {
									marginRight: '8px'
								}
							}),
							h('span', data.title)
						]),
						h('span', {
							style: {
								display: 'inline-block',
								float: 'right',
								marginRight: '32px'
							}
						}, [
							h('Button', {
								props: Object.assign({}, this.buttonProps, {
									icon: 'ios-plus-empty'
								}),
								style: {
									marginRight: '8px'
								},
								on: {
									click: function() {
										pVue.appendNode(data)
									}
								}
							}),
							h('Button', {
								props: Object.assign({}, this.buttonProps, {
									icon: 'ios-minus-empty'
								}),
								on: {
									click: function() {
										pVue.removeNode(root, node, data)
									}
								}
							})
						])
					]);
				},
				appendNode: function(data) {
					const children = data.children || [];
					children.push({
						title: 'appended node',
						expand: true
					});
					this.$set(data, 'children', children);
				},
				removeNode: function(root, node, data) {
					const parentKey = root.find(el => el === node).parent;
					const parent = root.find(el => el.nodeKey === parentKey).node;
					const index = parent.children.indexOf(data);
					parent.children.splice(index, 1);
				}
			}
		}
	}
	/**
	 * 获取查询结果数据列表
	 */
	function getDocListTestData() {
		var len = Math.random() * 30,
			arr = [];
		for(var i = 0; i < len; i++) {
			arr.push({
				id: Mock.mock('@id()'),
				title: Mock.mock('@title'),
				desc: Mock.mock('@cparagraph'),
				date: Mock.mock('@datetime("yyyy-MM-dd HH:mm:ss")')
			});
		}
		return arr;
	}
	/**
	 * 获取产品型号列表
	 */
	function getProModelsTestData() {
		/*var len = Math.random() * 30,
			arr = [];

		for(var i = 0; i < len; i++) {
			arr.push(Mock.mock('@word(10, 50)'));
		}
		return arr;*/
		var proList = getProList(),
			len = proList.length,
			arr = [];
		for(var i = 0; i < len; i++) {
			proList[i].family == '交换机' ? arr.push(proList[i].type) : '';
		}
		return arr;
	}

	function getProList() {
		return [{
				"family": "AntiDDoS",
				"name": "AntiDDoS1500",
				"type": "AntiDDoS1500-D"
			},
			{
				"family": "AntiDDoS",
				"name": "AntiDDoS1500",
				"type": "AntiDDoS1550"
			},
			{
				"family": "AntiDDoS",
				"name": "AntiDDoS1500",
				"type": "AntiDDoS1520"
			},
			{
				"family": "AntiDDoS",
				"name": "AntiDDoS1600",
				"type": "AntiDDoS1650"
			},
			{
				"family": "AntiDDoS",
				"name": "AntiDDoS1600",
				"type": "AntiDDoS1680"
			},
			{
				"family": "AntiDDoS",
				"name": "AntiDDoS8000",
				"type": "AntiDDoS8030"
			},
			{
				"family": "AntiDDoS",
				"name": "AntiDDoS8000",
				"type": "AntiDDoS8080"
			},
			{
				"family": "AntiDDoS",
				"name": "AntiDDoS8000",
				"type": "AntiDDoS8160"
			},
			{
				"family": "FireHunter",
				"name": "FireHunter 6000",
				"type": "FireHunter6000"
			},
			{
				"family": "IVS",
				"name": "VCN500",
				"type": "VCN500"
			},
			{
				"family": "IVS",
				"name": "eSpace VCN3000",
				"type": "eSpace VCN3000"
			},
			{
				"family": "LogCenter",
				"name": "LogCenter",
				"type": "LogCenter"
			},
			{
				"family": "MDU",
				"name": "MA561x",
				"type": "MA5612(H831CCFE)"
			},
			{
				"family": "MDU",
				"name": "MA561x",
				"type": "MA5612(H832CCFE)"
			},
			{
				"family": "MDU",
				"name": "MA561x",
				"type": "MA5612(H835CCFE)"
			},
			{
				"family": "MDU",
				"name": "MA561x",
				"type": "MA5616(CCUB)"
			},
			{
				"family": "MDU",
				"name": "MA561x",
				"type": "MA5616(CCUC)"
			},
			{
				"family": "MDU",
				"name": "MA561x",
				"type": "MA5616(CCUD)"
			},
			{
				"family": "MDU",
				"name": "MA561x",
				"type": "MA5616(CCUE)"
			},
			{
				"family": "MDU",
				"name": "MA562x",
				"type": "MA5620(H821EPUB)"
			},
			{
				"family": "MDU",
				"name": "MA562x",
				"type": "MA5620(H822EPUB)"
			},
			{
				"family": "MDU",
				"name": "MA562x",
				"type": "MA5620(H825EPUB)"
			},
			{
				"family": "MDU",
				"name": "MA562x",
				"type": "MA5621(4FE)"
			},
			{
				"family": "MDU",
				"name": "MA562x",
				"type": "MA5621(4GE/FE)"
			},
			{
				"family": "MDU",
				"name": "MA562x",
				"type": "MA5623A"
			},
			{
				"family": "MDU",
				"name": "MA562x",
				"type": "MA5626(H821EPUB)"
			},
			{
				"family": "MDU",
				"name": "MA562x",
				"type": "MA5626(H822EPUB)"
			},
			{
				"family": "MDU",
				"name": "MA562x",
				"type": "MA5626(H825EPUB)"
			},
			{
				"family": "MDU",
				"name": "MA563x",
				"type": "MA5633(第一代)"
			},
			{
				"family": "MDU",
				"name": "MA563x",
				"type": "MA5633(第二代)"
			},
			{
				"family": "MDU",
				"name": "MA582x",
				"type": "MA5821(24GE,POE)"
			},
			{
				"family": "MDU",
				"name": "MA582x",
				"type": "MA5821(FE)"
			},
			{
				"family": "MDU",
				"name": "MA582x",
				"type": "MA5821(GE)"
			},
			{
				"family": "MDU",
				"name": "MA582x",
				"type": "MA5822(FE)"
			},
			{
				"family": "MDU",
				"name": "MA582x",
				"type": "MA5822(GE)"
			},
			{
				"family": "OLT",
				"name": "MA5600T系列",
				"type": "MA5680T/MA5683T/MA5608T"
			},
			{
				"family": "OLT",
				"name": "MA5800系列",
				"type": "MA5800"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 12800",
				"type": "CE12804"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 12800",
				"type": "CE12804S"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 12800",
				"type": "CE12808"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 12800",
				"type": "CE12808S"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 12800",
				"type": "CE12812"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 12800",
				"type": "CE12816"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 5800",
				"type": "CE5810EI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 5800",
				"type": "CE5850EI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 5800",
				"type": "CE5850HI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 5800",
				"type": "CE5855EI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 6800",
				"type": "CE6810EI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 6800",
				"type": "CE6810LI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 6800",
				"type": "CE6850EI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 6800",
				"type": "CE6850HI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 6800",
				"type": "CE6850U-HI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 6800",
				"type": "CE6851HI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 6800",
				"type": "CE6855HI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 6800",
				"type": "CE6870EI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 7800",
				"type": "CE7850EI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 7800",
				"type": "CE7855EI"
			},
			{
				"family": "交换机",
				"name": "CloudEngine 8800",
				"type": "CE8860EI"
			},
			{
				"family": "交换机",
				"name": "E600 Switch",
				"type": "E600"
			},
			{
				"family": "交换机",
				"name": "S12700",
				"type": "S12704"
			},
			{
				"family": "交换机",
				"name": "S12700",
				"type": "S12708"
			},
			{
				"family": "交换机",
				"name": "S12700",
				"type": "S12710"
			},
			{
				"family": "交换机",
				"name": "S12700",
				"type": "S12712"
			},
			{
				"family": "交换机",
				"name": "S2700",
				"type": "S2700EI"
			},
			{
				"family": "交换机",
				"name": "S2700",
				"type": "S2700SI"
			},
			{
				"family": "交换机",
				"name": "S2700",
				"type": "S2710SI"
			},
			{
				"family": "交换机",
				"name": "S2700",
				"type": "S2720EI"
			},
			{
				"family": "交换机",
				"name": "S2700",
				"type": "S2750EI"
			},
			{
				"family": "交换机",
				"name": "S3700",
				"type": "S3700EI"
			},
			{
				"family": "交换机",
				"name": "S3700",
				"type": "S3700HI"
			},
			{
				"family": "交换机",
				"name": "S3700",
				"type": "S3700SI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5700EI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5700HI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5700LI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5700S-LI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5700SI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5710-C-LI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5710-X-LI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5710EI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5710HI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5720-LI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5720EI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5720HI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5720S-LI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5720S-SI"
			},
			{
				"family": "交换机",
				"name": "S5700",
				"type": "S5720SI"
			},
			{
				"family": "交换机",
				"name": "S600-E",
				"type": "S600-E"
			},
			{
				"family": "交换机",
				"name": "S6700",
				"type": "S6700EI"
			},
			{
				"family": "交换机",
				"name": "S6700",
				"type": "S6720EI"
			},
			{
				"family": "交换机",
				"name": "S7700",
				"type": "S7703"
			},
			{
				"family": "交换机",
				"name": "S7700",
				"type": "S7706"
			},
			{
				"family": "交换机",
				"name": "S7700",
				"type": "S7710"
			},
			{
				"family": "交换机",
				"name": "S7700",
				"type": "S7712"
			},
			{
				"family": "交换机",
				"name": "S9300",
				"type": "S9303"
			},
			{
				"family": "交换机",
				"name": "S9300",
				"type": "S9306"
			},
			{
				"family": "交换机",
				"name": "S9300",
				"type": "S9312"
			},
			{
				"family": "交换机",
				"name": "S9700",
				"type": "S9703"
			},
			{
				"family": "交换机",
				"name": "S9700",
				"type": "S9706"
			},
			{
				"family": "交换机",
				"name": "S9700",
				"type": "S9712"
			},
			{
				"family": "交换机",
				"name": "SPU",
				"type": "SPU"
			},
			{
				"family": "防火墙",
				"name": "ASG2050",
				"type": "ASG2050"
			},
			{
				"family": "防火墙",
				"name": "ASG2100",
				"type": "ASG2100"
			},
			{
				"family": "防火墙",
				"name": "ASG2150",
				"type": "ASG2150"
			},
			{
				"family": "防火墙",
				"name": "ASG2200",
				"type": "ASG2200"
			},
			{
				"family": "防火墙",
				"name": "ASG2600",
				"type": "ASG2600"
			},
			{
				"family": "防火墙",
				"name": "ASG2800",
				"type": "ASG2800"
			},
			{
				"family": "防火墙",
				"name": "NGFW Module",
				"type": "NGFW-Module"
			},
			{
				"family": "防火墙",
				"name": "NIP2050",
				"type": "NIP2050"
			},
			{
				"family": "防火墙",
				"name": "NIP2050D",
				"type": "NIP2050D"
			},
			{
				"family": "防火墙",
				"name": "NIP2100",
				"type": "NIP2100"
			},
			{
				"family": "防火墙",
				"name": "NIP2100D",
				"type": "NIP2100D"
			},
			{
				"family": "防火墙",
				"name": "NIP2130",
				"type": "NIP2130"
			},
			{
				"family": "防火墙",
				"name": "NIP2130D",
				"type": "NIP2130D"
			},
			{
				"family": "防火墙",
				"name": "NIP2150",
				"type": "NIP2150"
			},
			{
				"family": "防火墙",
				"name": "NIP2150D",
				"type": "NIP2150D"
			},
			{
				"family": "防火墙",
				"name": "NIP2200",
				"type": "NIP2200"
			},
			{
				"family": "防火墙",
				"name": "NIP2200D",
				"type": "NIP2200D"
			},
			{
				"family": "防火墙",
				"name": "NIP5000I",
				"type": "NIP5000I"
			},
			{
				"family": "防火墙",
				"name": "NIP5100",
				"type": "NIP5100"
			},
			{
				"family": "防火墙",
				"name": "NIP5100D",
				"type": "NIP5100D"
			},
			{
				"family": "防火墙",
				"name": "NIP5200",
				"type": "NIP5200"
			},
			{
				"family": "防火墙",
				"name": "NIP5200D",
				"type": "NIP5200D"
			},
			{
				"family": "防火墙",
				"name": "NIP5500",
				"type": "NIP5500"
			},
			{
				"family": "防火墙",
				"name": "NIP5500D",
				"type": "NIP5500D"
			},
			{
				"family": "防火墙",
				"name": "SVN2230",
				"type": "SVN2230"
			},
			{
				"family": "防火墙",
				"name": "SVN2260",
				"type": "SVN2260"
			},
			{
				"family": "防火墙",
				"name": "SVN5530",
				"type": "SVN5530"
			},
			{
				"family": "防火墙",
				"name": "SVN5530-C1",
				"type": "SVN5530-C1"
			},
			{
				"family": "防火墙",
				"name": "SVN5530-C3",
				"type": "SVN5530-C3"
			},
			{
				"family": "防火墙",
				"name": "SVN5560",
				"type": "SVN5560"
			},
			{
				"family": "防火墙",
				"name": "SVN5600",
				"type": "SVN5630"
			},
			{
				"family": "防火墙",
				"name": "SVN5600",
				"type": "SVN5660"
			},
			{
				"family": "防火墙",
				"name": "SVN5800",
				"type": "SVN5830"
			},
			{
				"family": "防火墙",
				"name": "SVN5800",
				"type": "SVN5850"
			},
			{
				"family": "防火墙",
				"name": "SVN5800",
				"type": "SVN5860"
			},
			{
				"family": "防火墙",
				"name": "SVN5800",
				"type": "SVN5880"
			},
			{
				"family": "防火墙",
				"name": "SVN5800-C",
				"type": "SVN5880-C"
			},
			{
				"family": "防火墙",
				"name": "USG2100",
				"type": "USG2100"
			},
			{
				"family": "防火墙",
				"name": "USG2100",
				"type": "USG2110-A-GW-C"
			},
			{
				"family": "防火墙",
				"name": "USG2100",
				"type": "USG2110-A-GW-W"
			},
			{
				"family": "防火墙",
				"name": "USG2100",
				"type": "USG2110-A-W"
			},
			{
				"family": "防火墙",
				"name": "USG2100",
				"type": "USG2110-F"
			},
			{
				"family": "防火墙",
				"name": "USG2100",
				"type": "USG2110-F-W"
			},
			{
				"family": "防火墙",
				"name": "USG2100",
				"type": "USG2120BSR"
			},
			{
				"family": "防火墙",
				"name": "USG2100",
				"type": "USG2130BSR"
			},
			{
				"family": "防火墙",
				"name": "USG2100",
				"type": "USG2130HSR"
			},
			{
				"family": "防火墙",
				"name": "USG2100",
				"type": "USG2160BSR"
			},
			{
				"family": "防火墙",
				"name": "USG2100",
				"type": "USG2160HSR"
			},
			{
				"family": "防火墙",
				"name": "USG2200",
				"type": "USG2200"
			},
			{
				"family": "防火墙",
				"name": "USG2200",
				"type": "USG2205BSR"
			},
			{
				"family": "防火墙",
				"name": "USG2200",
				"type": "USG2205HSR"
			},
			{
				"family": "防火墙",
				"name": "USG2200",
				"type": "USG2220BSR"
			},
			{
				"family": "防火墙",
				"name": "USG2200",
				"type": "USG2220HSR"
			},
			{
				"family": "防火墙",
				"name": "USG5100",
				"type": "USG5100"
			},
			{
				"family": "防火墙",
				"name": "USG5100",
				"type": "USG5120BSR"
			},
			{
				"family": "防火墙",
				"name": "USG5100",
				"type": "USG5120HSR"
			},
			{
				"family": "防火墙",
				"name": "USG5100",
				"type": "USG5150BSR"
			},
			{
				"family": "防火墙",
				"name": "USG5100",
				"type": "USG5150HSR"
			},
			{
				"family": "防火墙",
				"name": "USG5500",
				"type": "USG5500"
			},
			{
				"family": "防火墙",
				"name": "USG6300",
				"type": "USG6306"
			},
			{
				"family": "防火墙",
				"name": "USG6300",
				"type": "USG6308"
			},
			{
				"family": "防火墙",
				"name": "USG6300",
				"type": "USG6310"
			},
			{
				"family": "防火墙",
				"name": "USG6300",
				"type": "USG6320"
			},
			{
				"family": "防火墙",
				"name": "USG6300",
				"type": "USG6330"
			},
			{
				"family": "防火墙",
				"name": "USG6300",
				"type": "USG6350"
			},
			{
				"family": "防火墙",
				"name": "USG6300",
				"type": "USG6360"
			},
			{
				"family": "防火墙",
				"name": "USG6300",
				"type": "USG6370"
			},
			{
				"family": "防火墙",
				"name": "USG6300",
				"type": "USG6380"
			},
			{
				"family": "防火墙",
				"name": "USG6300",
				"type": "USG6390"
			},
			{
				"family": "防火墙",
				"name": "USG6500",
				"type": "USG6507"
			},
			{
				"family": "防火墙",
				"name": "USG6500",
				"type": "USG6510-SJJ"
			},
			{
				"family": "防火墙",
				"name": "USG6500",
				"type": "USG6530"
			},
			{
				"family": "防火墙",
				"name": "USG6500",
				"type": "USG6550"
			},
			{
				"family": "防火墙",
				"name": "USG6500",
				"type": "USG6570"
			},
			{
				"family": "防火墙",
				"name": "USG6600",
				"type": "USG6620"
			},
			{
				"family": "防火墙",
				"name": "USG6600",
				"type": "USG6630"
			},
			{
				"family": "防火墙",
				"name": "USG6600",
				"type": "USG6650"
			},
			{
				"family": "防火墙",
				"name": "USG6600",
				"type": "USG6660"
			},
			{
				"family": "防火墙",
				"name": "USG6600",
				"type": "USG6670"
			},
			{
				"family": "防火墙",
				"name": "USG6600",
				"type": "USG6680"
			}
		];
	}
});