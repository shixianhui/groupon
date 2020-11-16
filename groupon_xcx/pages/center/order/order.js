// pages/center/order/order.js
var app = getApp();
const base_url = app.d.base_url;
var list = [];
var warnImg = '/image/tishi.png';
var s='';
var per_page = 20;
var page = 1;
var is_next_page = 0;
var order_id=''
// 获取收货地址列表
var GetList = function (that) {
  that.setData({
    hidden: false
  });
  var url="";
  if (s == 'a') {
    url = base_url + 'napi/get_record_list_0/' + per_page + '/' + page + '?sid=' + app.d.sid
  } else if (s == 'b') {
    url = base_url + 'napi/get_record_list/' + per_page + '/' + page + '?sid=' + app.d.sid
  } else if (s == 'c') {
    url = base_url + 'napi/get_order_list/' + per_page + '/' + page + '?sid=' + app.d.sid
  } else if (s == 'd') {
    url = base_url + 'napi/get_exchange_list/' + per_page + '/' + page + '?sid=' + app.d.sid

  }
  console.log(url)
  wx.request({
    url: url,
    method: 'GET',
    data: {},
    header: {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'ci_session=' + app.d.sid 
    },
    success: function (res) {
      console.log(res)
      if (res.data.success) {
        list = that.data.list;
        is_next_page = res.data.data.is_next_page;
        for (var i = 0; i < res.data.data.item_list.length; i++) {
          list.push(res.data.data.item_list[i]);
        }
        that.setData({
          list: list,
          s:s,
          hidden: true
        });
        console.log(list)
        page++;
      } else {
        wx.showToast({
          title: res.data.message,
          image: '/image/tishi.png',
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

var apply_exchange=function(that){
  that.setData({
    hidden: false
  });
  var url = base_url + 'napi/apply_exchange/' + order_id + '?sid=' + app.d.sid
  console.log(url)
  wx.request({
    url: url,
    method: 'GET',
    data: {},
    header: {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'ci_session=' + app.d.sid
    },
    success: function (res) {
      console.log(res)
      if (res.data.success) {
        s='d';
        that.setData({
          show: '',
          currentTab: 3,
          list: []
        });
        list = [];
        per_page = 20;
        page = 1;
        GetList(that)
      } else {
        wx.showToast({
          title: res.data.message,
          image: '/image/tishi.png',
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
    list: [],
    navbar: [{ 'name': '待付款', 'value': 'a' }, { 'name': '拼团中', 'value': 'b' }, { 'name': '拼团成功', 'value': 'c' }, { 'name': '售后/客服', 'value': 'd' }],
    currentTab: 0,
    s:'a',
    show:''
  },
  navbarTap: function (e) {
    this.setData({
      currentTab: e.currentTarget.dataset.idx
    });
    s = e.currentTarget.dataset.value;
    page = 1;
    var that = this;
    that.data.list = [];
    GetList(that);
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    // is_next_page = 0;
    var that = this;
    if (options) {
      s = options.type;
    } else {
      s = 'a';
    }
    var index = 0;
    if (s == 'a') {
      index = 0;
    }
    else if (s == 'b') {
      index = 1;
    }
    else if (s == 'c') {
      index = 2;
    }
    else if (s == 'd') {
      index = 3;
    }
    this.setData({
      currentTab: index,
      list: []
    });
    list = [];
    per_page = 20;
    page = 1;
    GetList(that);
    wx.showShareMenu({
      withShareTicket: true
    })
  },
  pay_all:function(e){
    if (e.currentTarget.dataset.info.groups_id) {
      var groups_id = e.currentTarget.dataset.info.groups_id;
    }else{
      var groups_id = '';
    }
    var is_deposit = e.currentTarget.dataset.info.is_deposit;
    var groupon_id = e.currentTarget.dataset.info.groupon_id;
    var record_id = e.currentTarget.dataset.info.record_id;
    var is_order = e.currentTarget.dataset.info.is_order;
      console.log(is_deposit + '+' + groups_id);
      wx.navigateTo({
        url: '../../index/index-detail/settlement/settlement?is_deposit=' + is_deposit + '&&groups_id=' + groups_id + '&&groupon_id=' + groupon_id + '&&record_id=' + record_id + '&&is_buy=' + is_order
      })
  },

  gotoindex:function(){
    wx.switchTab({
      url: '../../index/index',
    })
  },
  
  to_apply_exchange:function(e){
    apply_exchange(this)
  },
  close_show: function (e) {
    this.setData({
      show: ''
    })
  },
  open_show: function (e) {
    order_id = e.currentTarget.dataset.order_id;
    this.setData({
      show:'active'
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
    // console.log('1')
    // var that = this;
    // that.setData({
    //   list: []
    // });
    // list = [];
    // per_page = 20;
    // page = 1;
    // GetList(that);
  },

  callphone: function () {
    wx.makePhoneCall({
      phoneNumber: '1340000' //仅为示例，并非真实的电话号码
    })
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
    var that = this;
    wx.showNavigationBarLoading();

    setTimeout(function () {
      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
      that.setData({
        list: []
      });
      list = [];
      per_page = 20;
      page = 1;
      GetList(that);
    }, 1000);
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    if (is_next_page == '1') {
      var that = this;
      GetList(that);
    } else {
      wx.showToast({
        title: '没有下一页',
        image: '/image/tishi.png',
        duration: 1000
      });
    }
  },
  to_order_view:function(e){
    var data = e.currentTarget.dataset.info;
    console.log(data)
    if (data.type == '1') {
      var item_id = data.groups_id
      wx.navigateTo({
        url: '../../index/index-open-group/index-open-group?item_id=' + item_id,
      })
    }else{
      var item_id = data.groupon_id
      wx.navigateTo({
        url: '../../index/index-join-group/index-join-group?item_id=' + item_id,
      })
    }
  },
  to_index_detail:function(e) {
    var item_id = e.currentTarget.dataset.item_id;
    wx.navigateTo({
      url: '../../index/index-detail/index-detail?item_id=' + item_id,
    })
  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function (res) {

    console.log(res)
    var data = res.target.dataset.info;
    if (data.type == '1') {
      var item_id = data.groups_id
      return {
        title: '拼团详情',
        path: 'pages/index/index-open-group/index-open-group?item_id=' + item_id + '&&parent_id=' + app.d.user_id
      }
    } else {
      var item_id = data.groupon_id
      return {
        title: '拼团详情',
        path: 'pages/index/index-join-group/index-join-group?item_id=' + item_id + '&&parent_id=' + app.d.user_id

      }
    }
    
  }
  
})