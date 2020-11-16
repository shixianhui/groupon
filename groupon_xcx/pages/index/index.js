//index.js
//获取应用实例
const app = getApp()
var base_url = app.d.base_url;
var warnImg = '/image/tishi.png';
const updateManager = wx.getUpdateManager()
var timer=5;
var outtime1 = "";
var outtime2 = "";
var outtime3 = "";
let goodsList = []
let goodsList1 = []
updateManager.onCheckForUpdate(function (res) {
  // 请求完新版本信息的回调
  console.log(res.hasUpdate);
  // console.log(res);
  
});

updateManager.onUpdateReady(function () {
  wx.showModal({
    title: '温馨提示',
    content: '有新的版本哦！点击确定重启应用',
    showCancel: false,
    success: function (res) {
      if (res.confirm) {
        updateManager.applyUpdate();
      }
    }
  })
});
updateManager.onUpdateFailed(function () {
  // 新的版本下载失败
  wx.showToast({
    title: '版本更新失败',
    image: '/image/tishi.png',
    duration: 1000
  });
})


// 
var new_record = function (that) {
  // 首页轮播
  wx.request({
    url: base_url + 'napi/new_record',
    method: 'GET',
    header: {
      'content-type': 'application/json'
    },
    success: function (res) {
      // console.log(res.data)
      
      if (res.data.success) {
        // that.setData({
        //   imgBanner: res.data.data.item_list
        // });
        if (JSON.stringify(res.data.data)!='[]'){
          if (timer <2) {
            that.setData({
              mantissa: res.data.data,
              style_class:'active'
            })
          }else{
            timer = 0;
            that.setData({
              style_class: ''
            })
          }
        }else{
          // if(timer>0){
          //   that.setData({
          //     style_class: ''
          //   })
          // }
        }
        timer=timer+1
        outtime1=setTimeout(function(){
          new_record(that)
        }, 3000)
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


// 首页数据
var Getbanner = function (that) {
  // 首页轮播
  wx.request({
    url: base_url + 'napi/get_index_ad_list',
    method: 'GET',
    header: {
      'content-type': 'application/json'
    },
    success: function (res) {
      console.log(res)
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

var get_groupon_list = function (that) {
  // 首页活动
  wx.request({
    url: base_url + 'napi/get_groupon_list',
    method: 'GET',
    header: {
      'content-type': 'application/json'
    },
    success: function (res) {
      console.log(res)
      if (res.data.success) {
        that.setData({
          item_list_0: res.data.data.item_list_0,
          item_list_1: res.data.data.item_list_1,
          item_list_2: res.data.data.item_list_2
        });


        for (var i = 0; i < res.data.data.item_list_0.length; i++) {
          goodsList.push(res.data.data.item_list_0[i].end_time);
        }

        let endTimeList = [];
        // 将活动的结束时间参数提成一个单独的数组，方便操作
        goodsList.forEach(o => {endTimeList.push(o) })
        that.setData({ actEndTimeList: endTimeList });

        // 执行倒计时函数
        that.countDown();


        for (var i = 0; i < res.data.data.item_list_1.length; i++) {
          goodsList1.push(res.data.data.item_list_1[i].end_time);
        }
        // console.log(goodsList1)

        let endTimeList1 = [];
        // console.log(goodsList1)
        // 将活动的结束时间参数提成一个单独的数组，方便操作
        goodsList1.forEach(o => {endTimeList1.push(o) })
        that.setData({ actEndTimeList1: endTimeList1 });
        // 执行倒计时函数
        that.countDown1();

      } else {

        wx.showToast({
          title: res.data.message,
          image: '/image/tishi.png',
          duration: 1000
        });
      }
      wx.stopPullDownRefresh()
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
  data: {
    countDownList: [],
    actEndTimeList: [],

    countDownList1: [],
    actEndTimeList1: [],

    imgBanner: ['/image/banner2.png', '/image/banner2.png', '/image/banner2.png'],
    indicatorDots: true,
    vertical: false,
    autoplay: true,
    circular: true,
    interval: 3000,
    duration: 500,
    previousMargin: 0,
    nextMargin: 0,
    style_class:'',
    mantissa:'',
    //菜单切换
    menuvalue: 2
  },
  //菜单切换
  fnMenuBtn:function(e){
    var dataId = e.currentTarget.id;
    this.setData({
      menuvalue : dataId,
    });
  },
  onLoad: function (options) {
    app.d.parent_id = options.parent_id
    // Getbanner(this);
    // get_groupon_list(this);
    // new_record(this);
  },


  timeFormat(param) {//小于10的格式化函数
    return param < 10 ? '0' + param : param;
  },
  countDown:function() {//倒计时函数
    // 获取当前时间，同时得到活动结束时间数组
    let newTime = new Date().getTime();
    let endTimeList = this.data.actEndTimeList;
    let countDownArr = [];

    // console.log(newTime + '/2')
    // console.log(newTime)
    // 对结束时间进行处理渲染到页面
    endTimeList.forEach(o => {
      let endTime = o*1000;
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
    outtime2=setTimeout(this.countDown, 1000);
  },

  countDown1: function () {//倒计时函数
    // 获取当前时间，同时得到活动结束时间数组
    let newTime = new Date().getTime();
    let endTimeList1 = this.data.actEndTimeList1;
    let countDownArr = [];

    // 对结束时间进行处理渲染到页面
    endTimeList1.forEach(o => {
      let endTime = o*1000;
      let obj = null;
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

    this.setData({ countDownList1: countDownArr })
    outtime3=setTimeout(this.countDown1, 1000);
  },
  callphone:function(){
    wx.makePhoneCall({
      phoneNumber: '0571-86691616' //仅为示例，并非真实的电话号码
    })
  },

  shopping: function (e) {
    // var types = e.currentTarget.dataset.type;
    // if (types == '1') {
    //   var item_id = e.currentTarget.dataset.groups_id
    //   wx.navigateTo({
    //     url: 'index-open-group/index-open-group?item_id=' + item_id,
    //   })
    // } else {
    //   var item_id = e.currentTarget.dataset.groupon_id
    //   wx.navigateTo({
    //     url: 'index-join-group/index-join-group?item_id=' + item_id,
    //   })
    // }
    var item_id = e.currentTarget.dataset.groupon_id
    wx.navigateTo({
      url: 'index-detail/index-detail?item_id=' + item_id,
    })
  },

  onShareAppMessage: function () {
    return {
      title: '拼团详情',
      path: 'pages/index/index?parent_id=' + app.d.user_id

    }
  },

  /**
    * 生命周期函数--监听页面隐藏
    */
  onHide: function () {
    clearTimeout(outtime1);
    clearTimeout(outtime2);
    clearTimeout(outtime3);
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    Getbanner(this);
    get_groupon_list(this);
    new_record(this);
  },
  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
    clearTimeout(outtime1);
    clearTimeout(outtime2);
    clearTimeout(outtime3);
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */

  onPullDownRefresh: function () {

    goodsList = []
    goodsList1 = []
    Getbanner(this);
    get_groupon_list(this);
  }
})
