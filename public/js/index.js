"use strict";
define(function(require, exports, module) {
	var pVueConfig = {
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
											click:function(){
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
				proModel: 'V100R003C00',
				proModels: getProModelsTestData(),
				docList: getDocListTestData()
			}
		},
		methods: {
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

	var Component = Vue.extend(pVueConfig);
	var pVue = new Component().$mount('.app');
	pVue.proModel = pVue.proModels[parseInt(Math.random() * pVue.proModels.length) - 1];

	function initTestData() {
		Mock.mock('@cparagraph')
	}

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

	function getProModelsTestData() {
		var len = Math.random() * 30,
			arr = [];

		for(var i = 0; i < len; i++) {
			arr.push(Mock.mock('@word(10, 50)'));
		}
		return arr;
	}
});