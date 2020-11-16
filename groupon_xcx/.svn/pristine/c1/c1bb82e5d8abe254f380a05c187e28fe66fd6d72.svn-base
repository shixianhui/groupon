// pages/center/address/addAddress/addAddress.js
var address = require('../../../../utils/city.js');
var app = getApp();
const base_url = app.d.base_url;
var animation;
var buyer_name = '';
var mobile = '';
var txt_address = '';
var province_id = '';
var city_id = '';
var area_id = '';
var is_default = 0;
var respone = new Object();
var content = new Object();
var item_id = '';
var warnImg = '/image/tishi.png';

// 保存地址
var saveUserAddress = function (that) {
  wx.request({
    url: base_url + 'napi/add_user_address/' + item_id + '?sid=' + app.d.sid,
    data: {
      buyer_name: buyer_name,
      mobile: mobile,
      province_id: province_id,
      city_id: city_id,
      area_id: area_id,
      address: txt_address,
      is_default: is_default
    },
    method: 'POST',
    header: {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'ci_session=' + app.d.sid
    },
    success: function (res) {
      if (res.data.success) {
        var pages = getCurrentPages();
        var prevPage = pages[pages.length - 2];
        wx.navigateBack({
          success: function () {
            prevPage.onLoad({ is_default: is_default }); // 执行前一个页面的onLoad方法  
          }
        });
        wx.showToast({
          title: '操作成功',
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
    menuType: 0,
    begin: null,
    status: 1,
    end: null,
    isVisible: false,
    animation: {},
    animationAddressMenu: {},
    addressMenuIsShow: false,
    value: [0, 0, 0],
    provinces: [],
    citys: [],
    areas: [],
    province: '',
    city: '',
    area: '',
    content: []
  },

  switchChange: function (e) {
    if (e.detail.value) {
      is_default = 1;
    } else {
      is_default = 0;
    }
  },

  //获取用户输入内容
  input_buyer_name: function (e) {
    buyer_name = e.detail.value;
  },

  input_mobile: function (e) {
    mobile = e.detail.value;
  },

  input_address: function (e) {
    txt_address = e.detail.value;
  },

  saveAddress: function (e) {
    var that = this;
    saveUserAddress(that);
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var animation = wx.createAnimation({
      duration: 300,
      transformOrigin: "50% 50%",
      timingFunction: 'ease',
    });
    this.animation = animation;
    // 默认联动显示北京
    var id = address.provinces[0].id
    this.setData({
      provinces: address.provinces,
      citys: address.citys[id],
      areas: address.areas[address.citys[id][0].id],
    });
    var that = this;
    var city = that.data.city;
    var value = that.data.value;
    that.startAddressAnimation(false);

    if (options.respone) {
      respone = options.respone;
      respone = JSON.parse(respone);
      item_id = respone.id;
      buyer_name = respone.buyer_name;
      mobile = respone.mobile;
      txt_address = respone.address;
      province_id = respone.province_id;
      city_id = respone.city_id;
      area_id = respone.area_id;
      that.setData({
        content: respone
      });
      var areaInfo = respone.txt_address;
      that.setData({
        areaInfo: areaInfo
      });
    } else {
      item_id = 0;
      buyer_name = '';
      mobile = '';
      txt_address = '';

      // 将选择的城市信息显示到输入框
      var areaInfo = that.data.provinces[value[0]].name + ' ' + that.data.citys[value[1]].name + ' ' + that.data.areas[value[2]].name;
      province_id = that.data.provinces[value[0]].id,
        city_id = that.data.citys[value[1]].id,
        area_id = that.data.areas[value[2]].id;
      that.setData({
        areaInfo: areaInfo
      });
    }
  },

  selectDistrict: function (e) {
    var that = this;
    if (that.data.addressMenuIsShow) {
      return;
    }
    that.startAddressAnimation(true);
  },
  // 执行动画
  startAddressAnimation: function (isShow) {
    var that = this
    if (isShow) {
      that.animation.translateY(0 + 'vh').step();
    } else {
      that.animation.translateY(40 + 'vh').step();
    }
    that.setData({
      animationAddressMenu: that.animation.export(),
      addressMenuIsShow: isShow,
    })
  },
  // 点击地区选择取消按钮
  cityCancel: function (e) {
    this.startAddressAnimation(false)
  },
  // 点击地区选择确定按钮
  citySure: function (e) {
    var that = this;
    var city = that.data.city;
    var value = that.data.value;
    that.startAddressAnimation(false);
    // 将选择的城市信息显示到输入框
    var areaInfo = that.data.provinces[value[0]].name + ' ' + that.data.citys[value[1]].name + ' ' + that.data.areas[value[2]].name;
    province_id = that.data.provinces[value[0]].id,
    city_id = that.data.citys[value[1]].id,
    area_id = that.data.areas[value[2]].id;
    that.setData({
      areaInfo: areaInfo,
    })
  },
  hideCitySelected: function (e) {
    this.startAddressAnimation(false)
  },
  // 处理省市县联动逻辑
  cityChange: function (e) {
    var value = e.detail.value
    var provinces = this.data.provinces
    var citys = this.data.citys
    var areas = this.data.areas
    var provinceNum = value[0]
    var cityNum = value[1]
    var countyNum = value[2]
    if (this.data.value[0] != provinceNum) {
      var id = provinces[provinceNum].id
      console.log(id)
      this.setData({
        value: [provinceNum, 0, 0],
        citys: address.citys[id],
        areas: address.areas[address.citys[id][0].id],
      })
    } else if (this.data.value[1] != cityNum) {
      var id = citys[cityNum].id
      this.setData({
        value: [provinceNum, cityNum, 0],
        areas: address.areas[citys[cityNum].id],
      })
    } else {
      this.setData({
        value: [provinceNum, cityNum, countyNum]
      })
    }
  },
  /*地区结束 */

  wxAddress:function(){
    var that=this;
    wx.chooseAddress({
      success: function (res) {
        console.log(res)
        buyer_name = res.userName;
        mobile = res.telNumber;
        txt_address = res.detailInfo;
        var areaInfo = res.provinceName + ' ' + res.cityName + ' ' + res.countyName;
        var addressuserinfo = { 'buyer_name': res.userName, 'mobile': res.telNumber, 'address': res.detailInfo}; 

        var provinces = that.data.provinces
        var citys = that.data.citys
        var areas = that.data.areas

        for (var i = 0; i < provinces.length;i++){
          if (res.provinceName==provinces[i].name){
            province_id = provinces[i].id;
            citys = address.citys[province_id];
            areas = address.areas[address.citys[province_id][0].id];

            for (var j = 0; j < citys.length; j++) {
              if (res.cityName == citys[j].name) {
                city_id = citys[j].id; 
                for (var z = 0; z < areas.length; z++) {
                  if (res.countyName == areas[z].name) {
                    area_id = areas[z].id;
                    that.setData({
                      value: [i, j, z],
                      citys: address.citys[province_id],
                      areas: address.areas[citys[j].id],
                    })
                  }
                }
              }
            }
          }
        }
        
        that.setData({
          areaInfo: areaInfo,
          content: addressuserinfo
        })
      }
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
  // onShareAppMessage: function () {

  // }
})