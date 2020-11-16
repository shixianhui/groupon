// pages/index/index-explain/index-explain.js
const app = getApp()
var base_url = app.d.base_url;
var warnImg = '/image/tishi.png';
var WxParse = require('../../../wxParse/wxParse.js');
var types;
// 单页面
var get_page_detail = function (that) {
  var url
  if (types=='0'){
    url = base_url + 'napi/get_page_detail/8'
  }else{
    url = base_url + 'napi/get_page_detail/9'
  }
  wx.request({
    url: url,
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
    types = options.type;
    var that=this;
    get_page_detail(that);
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
  // onShareAppMessage: function () {
  
  // }
})