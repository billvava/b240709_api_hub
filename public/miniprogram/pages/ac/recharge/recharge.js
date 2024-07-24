const util = require('../../../utils/util.js');
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    finance: '',
    list: [],
    id: 0,
    parameters: null,
    ordernum: null,
    val: '',
    payval: ''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.load_data();
    var buBottom = app.globalData.buBottom;
    this.setData({
      buBottom: buBottom
    })
  },

  load_data: function () {
    var t = this
    var post_data = {
      m: 'comapi',
      c: 'Recharge',
      a: 'index'
    };

    util.ajax(post_data, function (res) {
      t.setData({
        data: res.data
      });
    });
  },
  inputedit: function (e) {
    var t = this
    let dataset = e.currentTarget.dataset
    let value = e.detail.value
    t.setData({
      money: Number(value),
      id: 0,
    })
  },
  change_cate: function (e) {
    var t = this;
    var d = e.currentTarget.dataset;
    if (d.id == t.data.id) {
      return false;
    }
    t.setData({
      id: d.id,
      money: d.money
    });
  },
  pay: function (flag) {
    var t = this;
    var post_data = {
      m: 'comapi',
      c: 'recharge',
      a: 'pay_sub',
      money: t.data.money,
      id: t.data.id
    };
    util.ajax(post_data, function (data) {
      if (data.data.is_pay == 0) {
        t.setData({
          ordernum: data.data.ordernum,
          parameters: data.data.parameters
        });
        t.pay_pp();
      }
    });

  },
  pay_pp: function () {
    var t = this;
    wx.requestPayment({
      'timeStamp': t.data.parameters.timeStamp,
      'nonceStr': t.data.parameters.nonceStr,
      'package': t.data.parameters.package,
      'signType': t.data.parameters.signType,
      'paySign': t.data.parameters.paySign,
      'success': function (e) {
        wx.showToast({
          title: '付款成功',
          duration: 2000,
          mask: true,
          complete: function () {
            t.load_data();
          }
        })

      },
      'fail': function (res) { 
        util.show_msg('支付取消');
      },
      'complete': function (res) {}
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})