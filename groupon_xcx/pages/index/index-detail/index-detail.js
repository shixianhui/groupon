// pages/index/index-detail/index-detail.js

var app = getApp();
const base_url = app.d.base_url;
var warnImg = '/image/tishi.png';
var item_id="";
var WxParse = require('../../../wxParse/wxParse.js');
var respone;
var goodsList = [];
var outtime2 = "";
// 获取详情
var get_groupon_detail = function (that, item_id) {
  that.setData({
    hidden: false
  });
  wx.request({
    url: base_url + 'napi/get_groupon_detail/'+item_id,
    method: 'GET',
    data: {},
    header: {
      'content-type': 'application/x-www-form-urlencoded'
    },
    success: function (res) {
      console.log(res)
      if (res.data.success) {
        that.setData({
          content:res.data.data
        })
        respone = res.data;
        var percentage = res.data.data.user_path_list.length / res.data.data.item_info.max_low*100;
        that.setData({
          percentage: percentage
        })
        var content = res.data.data.item_info.app_content;
        WxParse.wxParse('article', 'html', content, that, 5);

        goodsList.push(res.data.data.groupon_info.end_time);

        let endTimeList = [];
        // 将活动的结束时间参数提成一个单独的数组，方便操作
        goodsList.forEach(o => { endTimeList.push(o) })
        that.setData({ actEndTimeList: endTimeList });

        // 执行倒计时函数
        that.countDown();
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

var get_index_ad_list = function (that) {
  // 首页轮播
  wx.request({
    url: base_url + 'napi/get_index_ad_list/4',
    method: 'GET',
    header: {
      'content-type': 'application/json'
    },
    success: function (res) {
      console.log(res.data)

      if (res.data.success) {
        that.setData({
          imgBanner: res.data.data.item_list
        });
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
    countDownList: [],
    actEndTimeList: [],
    imgBanner: [],
    indicatorDots: true,
    vertical: false,
    autoplay: true,
    circular: true,
    interval: 3000,
    duration: 500,
    previousMargin: 0,
    nextMargin: 0,
    menuvalue: 1,
    banner_height_1: 0,
    banner_height: 0
  },
  //菜单切换
  fnMenuBtn: function (e) {
    var dataId = e.currentTarget.id;
    this.setData({
      menuvalue: dataId,
    });
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    app.d.parent_id = options.parent_id
    console.log(app.d.parent_id)
    goodsList = [];
    item_id = options.item_id;
    // console.log(item_id)
    get_index_ad_list(this);
    get_groupon_detail(this, item_id);
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  // 轮播图高度
  fnImgHeight1: function (e) {
    var winWid = wx.getSystemInfoSync().windowWidth;
    var imgh = e.detail.height;
    var imgw = e.detail.width;
    var swiperH = winWid * imgh / imgw + "px"
    console.log(swiperH)
    if (this.data.banner_height < winWid * imgh / imgw) {
      this.setData({
        banner_height_1: swiperH,
        banner_height: winWid * imgh / imgw
      })
    }
  },
/**订单页面*/
  shopping:function(e){
    var is_deposit = e.currentTarget.dataset.is_deposit;
    var groupon_id = e.currentTarget.dataset.groupon_id;
    
    wx.navigateTo({
      url: 'settlement/settlement?is_deposit=' + is_deposit + '&&groupon_id=' + groupon_id
    })
  },
  shopping3: function (e) {
    var is_deposit = e.currentTarget.dataset.is_deposit;
    var groupon_id = e.currentTarget.dataset.groupon_id;

    wx.navigateTo({
      url: 'settlement/settlement?is_deposit=' + is_deposit + '&&groupon_id=' + groupon_id + '&&is_buy=1'
    })
  },
  shopping2: function (e) {
    var is_deposit = e.currentTarget.dataset.is_deposit;
    var groups_id = e.currentTarget.dataset.groups_id;
    var groupon_id = e.currentTarget.dataset.groupon_id;
    console.log(is_deposit + '+' + groups_id);
    wx.navigateTo({
      url: 'settlement/settlement?is_deposit=' + is_deposit + '&&groups_id=' + groups_id + '&&groupon_id=' + groupon_id
    })
  },
  to_explain: function (e) {
    var types = e.currentTarget.dataset.type;
    wx.navigateTo({
      url: '../index-explain/index-explain?type=' + types
    })
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  },

  timeFormat(param) {//小于10的格式化函数
    return param < 10 ? '0' + param : param;
  },
  countDown: function () {//倒计时函数
    // 获取当前时间，同时得到活动结束时间数组
    let newTime = new Date().getTime();
    let endTimeList = this.data.actEndTimeList;
    let countDownArr = [];

    // console.log(newTime + '/2')
    // console.log(newTime)
    // 对结束时间进行处理渲染到页面
    endTimeList.forEach(o => {
      let endTime = o * 1000;
      let obj = null;
      // console.log(endTime+'/1')
      // 如果活动未结束，对时间进行处理
      if (endTime - newTime > 0) {
        let time = (endTime - newTime) / 1000;
        // 获取天、时、分、秒
        let day = parseInt(time / (60 * 60 * 24));
        let hou = parseInt(time % (60 * 60 * 24) / 3600);
        let min = parseInt(time % (60 * 60 * 24) % 3600 / 60);
        let sec = parseInt(time % (60 * 60 * 24) % 3600 % 60);
        obj = {
          day: this.timeFormat(day),
          hou: this.timeFormat(hou),
          min: this.timeFormat(min),
          sec: this.timeFormat(sec)
        }
      } else {//活动已结束，全部设置为'00'
        obj = {
          day: '00',
          hou: '00',
          min: '00',
          sec: '00'
        }
      }
      countDownArr.push(obj);
    })
    // 渲染，然后每隔一秒执行一次倒计时函数
    this.setData({ countDownList: countDownArr })
    outtime2 = setTimeout(this.countDown, 1000);
  },
  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
    clearTimeout(outtime2);
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
    clearTimeout(outtime2);
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
      title: '活动详情',
      path: 'pages/index/index-detail/index-detail?item_id=' + item_id + '&&parent_id=' + app.d.user_id

    }
  },
  callphone: function () {
    wx.makePhoneCall({
      phoneNumber: '0571-86691616' //仅为示例，并非真实的电话号码
    })
  }
})