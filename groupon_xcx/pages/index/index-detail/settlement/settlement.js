// pages/index/index-detail/settlement/settlement.js

var app = getApp();
const base_url = app.d.base_url;
var warnImg = '/image/tishi.png';

var is_deposit = '';
var groupon_id = '';
var groups_id='';
var address_id = '';
var types='';
var is_buy='0';
var is_full='0';
var remark='';
// 获取详情
var confirm = function (that, item_id, is_deposit, record_id) {
  // console.log(item_id + '/' + is_deposit);
  that.setData({
    hidden: false
  });
  wx.request({
    url: base_url + 'napi/confirm/' + item_id + '/' + is_deposit + '/' + record_id + '/' + is_buy+'?sid='+app.d.sid,
    method: 'GET',
    data: {},
    header: {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'ci_session=' + app.d.sid
    },
    success: function (res) {
      console.log(res)
      if (res.data.success) {
        types= res.data.data.groupon_info.type;
        var default_user_address=[];
        if (res.data.data.default_user_address.length>0){
          default_user_address = res.data.data.default_user_address[0];
          address_id = res.data.data.default_user_address[0].id;
          app.d.userInfo['address_id'] = res.data.data.default_user_address[0].id;
        }else{
          default_user_address = res.data.data.default_user_address;
          address_id='0';
          app.d.userInfo['address_id']='0';
        }
        that.setData({
          address_info: default_user_address,
          total: res.data.data.total,
          groupon_info: res.data.data.groupon_info,
          product_info: res.data.data.product_info
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

// 添加订单
var add_group = function (that, groupon_id) {
  console.log(groupon_id + '/' +address_id)
  address_id = app.d.userInfo['address_id'];
  that.setData({
    hidden: false
  });
  wx.request({
    url: base_url + 'napi/add_group?sid=' + app.d.sid,
    method: 'POST',
    data: {
      groupon_id: groupon_id,
      address_id: address_id
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'ci_session=' + app.d.sid
    },
    success: function (res) {
      console.log(res);
      if (res.data.success) {
        var record_id = res.data.data
        wx.login({
          success: function (res) {
            var code = res.code;
            // console.log(res.code)
            depositInfo(that, record_id, code)
          }
        });
      } else {
        wx.showModal({
          title: '温馨提示',
          content: res.data.message,
          showCancel: false,
        })
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

// 添加订单(小型参团)
var add_group2 = function (that, groups_id) {
  that.setData({
    hidden: false
  });

  address_id = app.d.userInfo['address_id'];
  wx.request({
    url: base_url + 'napi/join_group/' + groups_id+'?sid=' + app.d.sid,
    method: 'POST',
    data: {
      address_id: address_id
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'ci_session=' + app.d.sid
    },
    success: function (res) {
      console.log(res)
      if (res.data.success) {
        var record_id = res.data.data.record_id;
        is_full = res.data.data.is_full;
        if (res.data.data.is_success=='1'){
          wx.showModal({
            title: '温馨提示',
            content: '该团已满员，重新发起拼团',
            showCancel: false,
            complete: function (res) {
              add_group(that, groupon_id) 
            }
          })
        }else{
          wx.login({
            success: function (res) {
              var code = res.code;
              // console.log(res.code)
              depositInfo(that, record_id, code)
            }
          });

        }
      } else {
        wx.showModal({
          title: '温馨提示',
          content: res.data.message,
          showCancel: false,
        })
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

// 微信支付信息生成
var depositInfo = function (that, record_id, code) {
  console.log('record_id:'+record_id)
  wx.request({
    url: base_url + 'napi/deposit_xcx_wx_pay?sid=' + app.d.sid,
    method: 'POST',
    data: {
      record_id: record_id,
      code: code
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'ci_session=' + app.d.sid
    },
    success: function (res) {

      console.log(res)
      if (res.data.success) {
        var data = res.data.data;
        wx.hideLoading();
        wx.requestPayment({
          'timeStamp': data.timeStamp,
          'nonceStr': data.nonceStr,
          'package': data.package,
          'signType': data.signType,
          'paySign': data.paySign,
          'success': function (res) {
            if(types==0){
              wx.navigateTo({
                url: '../../../index/index-join-group/index-join-group?item_id=' + groupon_id
              })
            }else{
              if (is_full == 1){
                wx.navigateTo({
                  url: '../../../center/order/order?type=a'
                })
              }else{
                get_record_info(that,record_id)
              }
            }
          },
          'fail': function (res) {
            console.log(res)
          }
        })
      } else {
        wx.showModal({
          title: '温馨提示',
          content: res.data.message,
          showCancel: false,
        })
      }
    },
    fail: function (e) {
      wx.showToast({
        title: '网络异常！',
        image: '/image/tishi.png',
        duration: 1000
      });
    }
  });
}

// 立即支付 --下订单
var add_now_order = function (that, record_id) {
  console.log(record_id + '/' + address_id)

  address_id = app.d.userInfo['address_id'];
  wx.request({
    url: base_url + 'napi/add_now_order?sid=' + app.d.sid,
    method: 'POST',
    data: {
      record_id: record_id,
      address_id: address_id,
      remark:remark
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'ci_session=' + app.d.sid
    },
    success: function (res) {
      console.log(res)
      if (res.data.success) {
        var order_id = res.data.data;
        wx.login({
          success: function (res) {
            var code = res.code;
            // console.log(res.code)
            orderInfo(that, order_id, code)
          }
        });
      } else {
        wx.showModal({
          title: '温馨提示',
          content: res.data.message,
          showCancel: false,
        })
      }
    },
    fail: function (e) {
      wx.showToast({
        title: '网络异常！',
        image: '/image/tishi.png',
        duration: 1000
      });
    }
  });
}

// 单独购买 --下订单
var add_order_price = function (that, groupon_id) {
  console.log(groupon_id + '/' + address_id)

  address_id = app.d.userInfo['address_id'];
  wx.request({
    url: base_url + 'napi/add_order?sid=' + app.d.sid,
    method: 'POST',
    data: {
      groupon_id: groupon_id,
      address_id: address_id,
      remark: remark
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'ci_session=' + app.d.sid
    },
    success: function (res) {
      console.log(res)
      if (res.data.success) {
        var order_id = res.data.data;
        wx.login({
          success: function (res) {
            var code = res.code;
            // console.log(res.code)
            orderInfo(that, order_id, code)
          }
        });
      } else {
        wx.showModal({
          title: '温馨提示',
          content: res.data.message,
          showCancel: false,
        })
      }
    },
    fail: function (e) {
      wx.showToast({
        title: '网络异常！',
        image: '/image/tishi.png',
        duration: 1000
      });
    }
  });
}

// 自由拼团详情
var get_record_info = function (that,record_id) {
  console.log(record_id)
  wx.request({
    url: base_url + 'napi/get_record_info/' + record_id+'?sid=' + app.d.sid,
    method: 'POST',
    data: {},
    header: {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'ci_session=' + app.d.sid
    },
    success: function (res) {
      console.log(res)
      if (res.data.success) {

        wx.redirectTo({
          url: '../../../index/index-open-group/index-open-group?item_id=' + res.data.data.groups_id
        })
      } else {
        wx.showModal({
          title: '温馨提示',
          content: res.data.message,
          showCancel: false,
        })
      }
    },
    fail: function (e) {
      wx.showToast({
        title: '网络异常！',
        image: '/image/tishi.png',
        duration: 1000
      });
    }
  });
}

// 微信支付信息生成--尾款
var orderInfo = function (that, order_id, code) {
  console.log('order_id:' + order_id + ',code:' + code)
  wx.request({
    url: base_url + 'napi/order_xcx_wx_pay/' + order_id+'?sid=' + app.d.sid,
    method: 'POST',
    data: {
      code: code
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'ci_session=' + app.d.sid
    },
    success: function (res) {

      console.log(res)
      if (res.data.success) {
        var data = res.data.data;
        wx.hideLoading();
        wx.requestPayment({
          'timeStamp': data.timeStamp,
          'nonceStr': data.nonceStr,
          'package': data.package,
          'signType': data.signType,
          'paySign': data.paySign,
          'success': function (res) {
            if (types == 0) {
              wx.redirectTo({
                url: '../../../index/index-join-group/index-join-group?item_id=' + groupon_id
              })
            } else {
              get_record_info(that,that.data.order_record_id)
            }
          },
          'fail': function (res) {
            console.log(res)
          }
        })
      } else {
        wx.showModal({
          title: '温馨提示',
          content: res.data.message,
          showCancel: false,
        })
      }
    },
    fail: function (e) {
      wx.showToast({
        title: '网络异常！',
        image: '/image/tishi.png',
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
    order_record_id:'',
    is_deposit:'',
    is_buy:''
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    console.log(options)
    is_deposit = options.is_deposit;
    groupon_id = options.groupon_id;
    groups_id = options.groups_id;
    var record_id=0;
    if (options.record_id){
      record_id = options.record_id;
    }else{
      record_id = 0;
    }
    if (options.is_buy) {
      is_buy = options.is_buy;
    } else {
      is_buy = 0;
    }
    // console.log(is_buy)
    this.setData({
      is_deposit: is_deposit,
      order_record_id: record_id,
      is_buy: is_buy
    });
    confirm(this, groupon_id, is_deposit, record_id);

  },
  add_order:function(e){
    var order_record_id = e.currentTarget.dataset.order_record_id
    var that=this;

    console.log(is_buy)
    if (is_buy==1){
      add_order_price(that, groupon_id);
      console.log('1')
    }else{
      if (order_record_id==0){
        if (types == '1' && groups_id != '' && groups_id != '0' && groups_id != null && groups_id != undefined) {
          add_group2(that, groups_id);
        }else{
          add_group(that, groupon_id);
        }
      }else{
        add_now_order(that, order_record_id);
      }
    }
  },
  gotoaddress:function(){
    app.d.userInfo['is_default'] = '1';
    wx.navigateTo({
      url: '../../../center/address/address',
    })
  },
  input:function(e){
    console.log(e.detail.value)
    remark=e.detail.value
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