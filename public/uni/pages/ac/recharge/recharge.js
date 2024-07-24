export default {
		data() {
			return {
				finance: {},
				list: [],
				id: 0,
				parameters: null,
				ordernum: null,
				data: null,
				val: '',
				money: 0,
				payval: '',
				code:'',
			}
		},
		onLoad(options) {
			this.load_data();
			this.buBottom = getApp().globalData.buBottom
		},
		methods: {
			load_data() {
				var t = this
				var post_data = {
					m: 'comapi',
					c: 'Recharge',
					a: 'index'
				};

				t.util.ajax(post_data, function(res) {
					t.data = res.data
				});
			},
			inputedit(e) {
				var t = this
				var value = e.detail.value
				t.money = Number(value)
				t.id = 0
			},
			change_cate(e) {
				var t = this;
				var d = e.currentTarget.dataset;
				if (d.id == t.id) {
					return false;
				}
				t.id = d.id,
				t.money = d.money
			},
                         pay_pp: function () {
                            var t = this;
                            uni.requestPayment({
                              'timeStamp': t.parameters.timeStamp,
                              'nonceStr': t.parameters.nonceStr,
                              'package': t.parameters.package,
                              'signType': t.parameters.signType,
                              'paySign': t.parameters.paySign,
                              'success': function (e) {
                                uni.showToast({
                                  title: '付款成功',
                                  duration: 2000,
                                  mask: true,
                                  complete: function () {
                                    t.load_data();
                                  }
                                })

                              },
                              'fail': function (res) { 
                                t.util.show_msg('支付取消');
                              },
                              'complete': function (res) {}
                            })
                          },
			pay() {
				var t = this;
				var post_data = {
					money: t.money,
					id: t.id,
					code:t.code,
				};
				t.util.ajax('/comapi/Recharge/pay_sub', post_data, function(res) {
					t.parameters = res.data.parameters;
				});

			}
		}
	};