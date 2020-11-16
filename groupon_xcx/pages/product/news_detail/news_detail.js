// pages/product/news_detail/news_detail.js
const app = getApp()
var base_url = app.d.base_url;
var warnImg = '/image/tishi.png';
var WxParse = require('../../../wxParse/wxParse.js');
var types;
// 单页面
var get_news_detail = function (that,id) {
  wx.request({
    url: base_url + 'napi/get_news_detail/'+id,
    data: {},
    method: 'POST',
    header: {
      'content-type': 'application/x-www-form-urlencoded',
    },
    success: function (res) {
      console.log(res)
      if (res.data.success) {

        var content = res.data.data.item_info.content;
        WxParse.wxParse('article', 'html', content, that, 5);
        wx.setNavigationBarTitle({
          title: res.data.data.item_info.title,
        })
      } else {
        wx.showToast({
          title: res.data.message,
          image: warnImg,
          duration: 1000
        });
      }
    },
    fail: function (e) {
      wx.showToast({
        title: '网络异常！',
        image: warnImg,
        duration: 1000
      });
    }
  });
}

Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var id = options.id;
    var that = this;
    get_news_detail(that,id);
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