App({
  d: {
    base_url: 'https://hz-tuan.rili.vip/index.php/',
    sid: '',
    user_id: '',
    userInfo: {
      sid: '', id: '', nickname: '', path: '', total: '', sex: '', score: '', mobile: '', username: '', is_id_card_auth: '', is_default: '', address_id: '', store_display: '', store_id: '', record_count: '', parent_id:0},
    parent_id: '0',
    parent:''
  },
  onLaunch: function () {
    // 展示本地存储能力
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    wx.setStorageSync('logs', logs);
    this.getUserInfo();
  },

  // 获取用户信息
  getUserInfo: function (cb) {
    var that = this;
    if (this.globalData.userInfo) {
      typeof cb == "function" && cb(this.globalData.userInfo)
    } else {
      //调用登录接口
      wx.login({
        success: function (res) {
          var code = res.code;
          console.log(res)
          wx.getUserInfo({
            success: function (res) {
              // console.log(res)
              that.globalData.userInfo = res.userInfo
              that.d.userInfo = res.userInfo
              typeof cb == "function" && cb(that.globalData.userInfo);
              var encryptedData = res.encryptedData;
              var iv = res.iv;
              that.login(code, iv, encryptedData);
            },
            fail: function (res) {
              that.checkSettingStatu();
            }
          });
        }, fail: function (res) {
          console.log(JSON.stringify(res));
        }
      });
    }
  },

  isEmptyObject: function (e) {
    //对象是否为空；判断是否是第一次授权，非第一次授权且授权失败则进行提醒
    var t;
    for (t in e)
      return !1;
    return !0
  },

  // 判断是否授权
  checkSettingStatu: function (cb) {
    //授权处理
    var that = this;
    // 判断是否是第一次授权，非第一次授权且授权失败则进行提醒
    wx.getSetting({
      success: function success(res) {
        var authSetting = res.authSetting;
        if (that.isEmptyObject(authSetting)) {
          console.log('首次授权');
          wx.showModal({
            title: '未登录',
            content: '需要获取您的公开信息(昵称、头像等)，请到会员页面进行登录',
            showCancel: true,
            cancelText: '取消',
            confirmText: '去登录',
            success: function (res) {
              // 此处为了用于 Android 系统区分点击蒙层关闭还是点击取消按钮关闭省去了res.confirm，res.cancel判断
              // 点击蒙层同样触发开启设置
              if (res.confirm) {

                wx.switchTab({
                  url: '/pages/center/center',
                })
              }
            }
          })
        } else {
          console.log('不是第一次授权', authSetting);
          // 没有授权的提醒
          if (authSetting['scope.userInfo'] === false) {
            wx.showModal({
              title: '打开设置页面进行授权',
              content: '需要获取您的公开信息(昵称、头像等)，请到小程序的设置中打开用户信息授权',
              showCancel: true,
              cancelText: '取消',
              confirmText: '去设置',
              success: function (res) {
                // 此处为了用于 Android 系统区分点击蒙层关闭还是点击取消按钮关闭省去了res.confirm，res.cancel判断
                // 点击蒙层同样触发开启设置
                if (res.confirm) {
                  wx.openSetting({
                    success: function success(res) {
                      if (res.authSetting['scope.userInfo'] === false) {

                      } else {
                        that.getUserInfo();
                      }
                    }
                  });
                }
              }
            })
          }
        }
      }
    });
  },

  // 登录授权
  login: function (code, iv, encryptedData) {
    // console.log(code + '   ' + iv + '   ' + encryptedData)
    var that = this;
    wx.request({
      url: that.d.base_url +'napi/wx_login',
      method: 'POST',
      data: {
        code: code,
        iv: iv,
        encryptedData: encryptedData,
        parent_id: that.d.parent_id
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res) {
        console.log(res)
        if (res.data.success) {
          var data = res.data.data;
          that.d.sid = data.session_id;
          that.d.user_id = data.id;
          that.d.userInfo['sid'] = data.session_id;
          that.d.userInfo['id'] = res.data.data.id;
          that.d.userInfo['nickname'] = res.data.data.nickname;
          that.d.userInfo['path_thumb'] = res.data.data.path_thumb;
          that.d.userInfo['path'] = res.data.data.path;
          that.d.userInfo['total'] = res.data.data.total;
          that.d.userInfo['sex'] = res.data.data.sex;
          that.d.userInfo['score'] = res.data.data.score;
          that.d.userInfo['mobile'] = res.data.data.mobile;
          that.d.userInfo['username'] = res.data.data.username;
          that.d.userInfo['store_id'] = res.data.data.store_id;
          that.d.userInfo['store_display'] = res.data.data.store_display;
          that.d.userInfo['record_count'] = res.data.data.record_count;
          // that.d.userInfo['is_default'] = '';
          // that.d.userInfo['address_id'] = '0';
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
  },

  // 核对session
  checkSession: function () {

  },

  globalData: {
    userInfo: null
  },

  onPullDownRefresh: function () {
    wx.stopPullDownRefresh();
  }
})
// wx.setEnableDebug({
//   enableDebug: true
// })
wx.setEnableDebug({
  enableDebug: false
})

