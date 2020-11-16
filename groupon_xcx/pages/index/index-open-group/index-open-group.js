// pages/index/index-open-group/index-open-group.js
var app = getApp();
const base_url = app.d.base_url;
var warnImg = '/image/tishi.png';
var item_id = '';
var parent_id = '0';
var timer = '';


var group_detail = function (that) {
  // console.log(item_id);
  wx.request({
    url: base_url + 'napi/group_detail/'+item_id+'?sid=' + app.d.sid,
    data: {},
    method: 'POST',
    header: {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'ci_session=' + app.d.sid 
    },
    success: function (res) {
      if (res.data.success) {
        console.log(res);
        console.log(that.data.group_info);
        clearInterval(timer);
        
        that.setData({
          group_info: res.data.data.group_info,
          product_info: res.data.data.product_info,
          user_path_list: res.data.data.user_path_list
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
    flag:true,
    user_path_list:[],
    product_info: [],
    group_info: []
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    app.d.parent_id = options.parent_id
    console.log(app.d.parent_id)
    // console.log(app.d.sid);
    if (options.parent_id != '0' && options.parent_id != 'undefined') {
      app.d.parent = 'open_group';
      wx.setStorage({
        key: 'open_group_id',
        data: options.item_id,
      })
      console.log(wx.getStorageSync('open_group_id'))
    }
    item_id = options.item_id;
    var that = this;

    timer = setInterval(function () {
      if (app.d.sid != null && app.d.sid != '' && app.d.sid != undefined) {
        group_detail(that);
      }
    }, 500)

  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  shopping: function (e) {
    var is_deposit = e.currentTarget.dataset.is_deposit;
    var groups_id = e.currentTarget.dataset.groups_id;
    var groupon_id = e.currentTarget.dataset.groupon_id;
    console.log(is_deposit + '+' + groups_id);
    wx.navigateTo({
      url: '../index-detail/settlement/settlement?is_deposit=' + is_deposit + '&&groups_id=' + groups_id + '&&groupon_id=' + groupon_id
    })
  },
  to_index_detail:function(e){
    var groupon_id = e.currentTarget.dataset.groupon_id;
    wx.navigateTo({
      url: '../index-detail/index-detail?item_id=' + groupon_id
    })
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    var that=this
    setTimeout(function() {
      that.setData({
        flag: false
      });
    }, 3000)
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
    clearInterval(timer);
    console.log(timer)
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
    clearInterval(timer);
    console.log(timer)
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
    return {
      title: '拼团详情',
      path: 'pages/index/index-open-group/index-open-group?item_id=' + item_id + '&&parent_id=' + app.d.user_id

    }
  }
})