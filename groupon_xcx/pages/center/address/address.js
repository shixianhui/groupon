// pages/center/address/address.js
var app = getApp();
const base_url = app.d.base_url;
var list = [];
var address_ids = '';
var thats = '';
var address_id = '';
var is_default = 0;
var warnImg = '/image/tishi.png';

// 获取收货地址列表
var GetList = function (that) {
  that.setData({
    hidden: false
  });
  wx.request({
    url: base_url + 'napi/get_user_address_list?sid=' + app.d.sid,
    method: 'POST',
    data: {},
    header: { 'content-type': 'application/x-www-form-urlencoded', 'Cookie': 'ci_session=' + app.d.sid },
    success: function (res) {
      console.log(res)
      if (res.data.success) {
        list = that.data.list;
        for (var i = 0; i < res.data.data.item_list.length; i++) {
          list.push(res.data.data.item_list[i]);
        }
        that.setData({
          list: list,
        })

        that.setData({
          hidden: true
        });
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

// 删除收货地址
var deleteUserAddress = function (that) {
  wx.request({
    url: base_url + 'napi/delete_user_address?sid=' + app.d.sid,
    method: 'POST',
    data: {
      address_id: address_ids
    },
    header: { 'content-type': 'application/x-www-form-urlencoded', 'Cookie': 'ci_session=' + app.d.sid },
    success: function (res) {
      if (res.data.success) {
        list = [];
        thats.setData({
          list: list,
        });
        GetList(thats);
        wx.showToast({
          title: '已删除',
          duration: 1000
        });
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

// 设为默认
var defaultUserAddress = function (that) {
  wx.request({
    url: base_url + 'napi/set_default_user_address?sid=' + app.d.sid,
    method: 'POST',
    data: {
      id: address_id
    },
    header: { 'content-type': 'application/x-www-form-urlencoded', 'Cookie': 'ci_session=' + app.d.sid },
    success: function (res) {
      if (res.data.success) {
        list = [];
        thats.setData({
          list: list,
        });
        GetList(thats);
        wx.showToast({
          title: '设置成功',
          duration: 1000
        });
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
    list: []
  },

  // 设置默认 
  setDefault: function (e) {
    address_id = e.currentTarget.dataset.info;
    var flag = e.currentTarget.dataset.flag;
    if (flag) {
      wx.showToast({
        title: '已经是默认地址',
        image: warnImg,
        duration: 1000
      });
      return;
    }
    if (address_id) {
      var that = this;
      defaultUserAddress(that);
    }
  },

  // 编辑地址
  edit: function (e) {
    var data = e.currentTarget.dataset.info;
    if (data) {
      wx.navigateTo({
        url: 'addAddress/addAddress?respone=' + JSON.stringify(data),
        success: function (res) {

        },
      });
    }
  },

  // 删除地址
  delete: function (e) {
    address_ids = e.currentTarget.dataset.info;
    if (address_ids) {
      wx.showModal({
        title: '提示',
        content: '您确定要删除该地址吗？',
        success: function (res) {
          if (res.confirm) {
            var that = this;
            deleteUserAddress(that);
          }
        }
      });
    }
  },

  // 选择地址
  choose_address: function (e) {
    console.log('11')
    if (is_default == '1') {
      var that = this;
      var id = e.currentTarget.dataset.id;
      var newList = that.data.list;
      for (var i = 0; i < newList.length; i++) {
        if (newList[i].id == id) {
          var address_list = [];
          address_list.push(newList[i]);
          app.d.userInfo['address_id'] = address_list[0].id;
          var pages = getCurrentPages();
          var prevPage = pages[pages.length - 2];
          console.log(address_list)
          prevPage.setData({
            address_info: address_list[0]
          });
          app.d.userInfo['is_default'] = '0';
          is_default = '0';
          wx.navigateBack();
        }
      }
    }
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    is_default = app.d.userInfo['is_default'];
    list = [];
    var that = this;
    thats = that;
    that.setData({
      list: list
    });
    GetList(that);
  },
  readload:function(){

    is_default = app.d.userInfo['is_default'];
    list = [];
    var that = this;
    thats = that;
    that.setData({
      list: list
    });
    GetList(that);
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
    var that = this;
    if (is_default == '1') {
      app.d.userInfo['is_default'] = '0';
      is_default = '0';
      if (!that.data.list.length) {
        app.d.userInfo['address_id'] = '0';
        var pages = getCurrentPages();
        var prevPage = pages[pages.length - 2];
        prevPage.setData({
          address_info: []
        });
      } else {
        var new_list = that.data.list;
        var flag = 0;
        for (var i = 0; i < new_list.length; i++) {
          if (app.d.userInfo['address_id'] == new_list[i].id) {
            flag = 1;
          }
        }
        if (!flag) {
          app.d.userInfo['address_id'] = '0';
          var pages = getCurrentPages();
          var prevPage = pages[pages.length - 2];
          prevPage.setData({
            address_info: []
          });
        }
      }
    }
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