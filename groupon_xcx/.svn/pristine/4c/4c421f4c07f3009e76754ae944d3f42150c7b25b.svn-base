// pages/product/product.js
const app = getApp()
var base_url = app.d.base_url;
var warnImg = '/image/tishi.png';
var WxParse = require('../../wxParse/wxParse.js');
var list=[]
// 单页面
var get_page_list = function (that) {
  wx.request({
    url: base_url + 'napi/get_page_list',
    data: {},
    method: 'POST',
    header: {
      'content-type': 'application/x-www-form-urlencoded',
    },
    success: function (res) {
      console.log(res)
      if (res.data.success) {
        
        var content1 = res.data.data.item_list[0].content;
        WxParse.wxParse('article1', 'html', content1, that, 5);
        var content2 = res.data.data.item_list[1].content;
        WxParse.wxParse('article2', 'html', content2, that, 5);
        var content3 = res.data.data.item_list[2].content;
        WxParse.wxParse('article3', 'html', content3, that, 5);
        
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
// 列表
var get_news_list = function (that) {
  wx.request({
    url: base_url + 'napi/get_news_list',
    data: {},
    method: 'GET',
    header: {
      'content-type': 'application/x-www-form-urlencoded',
    },
    success: function (res) {
      console.log(res)
      if (res.data.success) {
        that.setData({
          list: res.data.data.item_list
        })
        // var content1 = res.data.data.item_list[0].content;
        // WxParse.wxParse('article1', 'html', content1, that, 5);
        // var content2 = res.data.data.item_list[1].content;
        // WxParse.wxParse('article2', 'html', content2, that, 5);
        // var content3 = res.data.data.item_list[2].content;
        // WxParse.wxParse('article3', 'html', content3, that, 5);

      } else {
        wx.showToast({
          title: res.data.message,
          image: warnImg,
          duration: 1000
        });
      }
      wx.stopPullDownRefresh()
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
    // tab 切换
    tabArr: {
      curHdIndex: 0,
      curBdIndex: 0
    },
    list:[],
    product:{
      bannerImage: '/image/list2.png',
      bannerAdTxt: ["合资产品","高可靠性","极致性价比"],
      qsrlTitle: "传承日系企业百年的坚持",
      qsrl:{
        title: "专利的空调涡旋压缩机\n技术引领行业能效提升",
        history:[
          {
            time: "1983年",
            txt: "日立发明了\n第一台空调涡旋压缩机"
          },{
            time: "1985年",
            txt: "首次开始生产\n超低温泵用涡旋压缩机"
          },{
            time: "1987年",
            txt: "首次开始生产\n列车空调机压缩机"
          },{
            time: "1987年",
            txt: "日立发明了\n第一台空调涡旋压缩机"
          },{
            time: "1985年",
            txt: "首次开始生产\n超低温泵用涡旋压缩机"
          },{
            time: "1987年",
            txt: "首次开始生产\n列车空调机压缩机"
          }
        ],
        detail:{
          image1: "/image/qsrl1.png",
          txt1: "2013年，日立将新一代最高效的涡旋压缩机应用于\n相关系列产品上，实现了其在日本国内最高的APF值和国内最高水平的\nIPLV(C)值",
          title: "独特的压力除霜模式\n制热强劲",
          image2: "/image/qsrl2.png",
          txt2: "制热快速启动，日立直流变频快速启动技术\n底部防结霜技术，避免底部冰霜堆积导致制热效果差\n冷媒高效利用技术，保证重组的冷媒，保证制热量",
        },
        },
      rlcpTitle: "日立EX-Pro 系列",
      rlcp: [
        {
          title: "日立ex-pro 系列",
          span: "超强性价比，适用于50-160方公寓，高\n静音，全直流变频",
          ul:[
            {
              image: "/image/rlcp1.png",
              time: "90秒",
              span: "只需90秒\n快速启动",
            },{
              image: "/image/rlcp2.png",
              time: "智能化",
              span: "拒绝制热\n倒春寒现象",
            },
            {
              image: "/image/rlcp3.png",
              time: "节能",
              span: "省电就是\n省钱",
            },],
          image: "/image/rlcp4.png",

        },
        {
          title: "VAM 公寓系列",
          span: "日立高端技术结晶，适合60-200方\n公寓，强大的拖带比",
          ul: [
            {
              image: "/image/rlcp1.png",
              time: "精准",
              span: "无极变频\n超精准温度",
            }, {
              image: "/image/rlcp2.png",
              time: "静音",
              span: "无忧静\n音环境",
            },
            {
              image: "/image/rlcp3.png",
              time: "节能",
              span: "超节能\n经济运行",
            },],
          image: "/image/rlcp4.png",
        },{
          title: "VAM 别墅系列",
          span: "适合150方以上的大户型\n省电高效",
          ul: [
            {
              image: "/image/rlcp1.png",
              time: "变频",
              span: "全直流变\n频尊享专属",
            }, {
              image: "/image/rlcp2.png",
              time: "功率",
              span: "外机从8匹到\n48匹自由组合",
            },
            {
              image: "/image/rlcp3.png",
              time: "压缩机",
              span: "高效涡旋\n式压缩机",
            },],
          image: "/image/rlcp4.png",
        }],
      zsrlTitle: "臻芯科技 真心为家",
      zsrl:[
        {
          image: '/image/zsrl1.png',
          h6: "日立家用中央空调里面哪款最好?",
          span: "经常会有朋友问我，不知道自己应该选择哪个品牌的中...",
          lookNumber: "1.7k人已阅读",
        },
        {
          image: '/image/zsrl2.png',
          h6: "选择日立中央空调的几个理由: 全面分析优缺点",
          span: "品牌实力：日立品牌创立于1910年拥百年历史，位列世界500...",
          lookNumber: "1.7k人已阅读",
        },
        {
          image: '/image/zsrl3.png',
          h6: "日立商用中央空调简介",
          span: "日立电器有限公司与空调系统集团协调，生产广泛应用的轻型商...",
          lookNumber: "1.7k人已阅读",
        },
        ],
    }
  },
  //tab切换
  tab: function (e) {
    //var dataId = e.currentTarget.dataset.id;
    var dataId = e.currentTarget.id;
    var obj = {};
    obj.curHdIndex = dataId;
    obj.curBdIndex = dataId;
    this.setData({
      tabArr: obj
    })
    //console.log(e);
  },
  
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    app.d.parent_id = options.parent_id
    get_news_list(this);
  },
  to_detail:function(e){
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: 'news_detail/news_detail?id=' + id
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
    get_news_list(this);
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
      path: 'pages/product/product?parent_id=' + app.d.user_id

    }
  },
  callphone: function () {
    wx.makePhoneCall({
      phoneNumber: '0571-86691616' //仅为示例，并非真实的电话号码
    })
  }
  
})