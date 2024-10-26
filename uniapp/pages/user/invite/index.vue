<template>
  <view>
    <swiper class="swiper" circular @change="swiperchange">
      <swiper-item
        v-for="(item, index) in swiperList"
        :key="index"
        class="swiper-item"
      >
        <image
          class="img"
          :src="swiperList[index]['path']"
          mode="scaleToFill"
        ></image>
        <l-painter
          isCanvasToTempFilePath
          @success="saveok($event, index)"
          hidden
          css="width: 750rpx; padding-bottom: 0; background: linear-gradient(,#ff971b 0%, #ff5000 100%)"
        >
          <!-- :src="baseUrl + appConfig.share_poster" -->

          <l-painter-img
            :src="item.url"
            css="width: 750rpx;  height: 1250rpx;"
          />
          <l-painter-text
            v-if="item.csstext != false"
            :text="userInfo.distribution_code"
            :css="item.csstext"
          />
          <l-painter-qrcode
            :css="item.cssqr"
            :text="baseUrl + '/ivt?pcode=' + userInfo.distribution_code"
          ></l-painter-qrcode>
        </l-painter>
      </swiper-item>
    </swiper>
    <view class="btn">
      <view class="btnone" @click="save">{{ $t("保存") }}</view>
    </view>
  </view>
</template>
  <script>
//   import tuiButton from "@/components/thorui/tui-button/tui-button.vue";
import lPainter from "@/components/lime-painter/components/l-painter/l-painter.vue";
import lPainterImg from "@/components/lime-painter/components/l-painter-image/l-painter-image.vue";
import lPainterText from "@/components/lime-painter/components/l-painter-text/l-painter-text.vue";
import lPainterQrcode from "@/components/lime-painter/components/l-painter-qrcode/l-painter-qrcode.vue";
import {
  ejudgeIosPermission,
  erequestAndroidPermission,
  echeckSystemEnableLocation,
  egotoAppPermissionSetting,
} from "@/js_sdk/wa-permission/permission.js";
import { baseDomain } from "@/config/app.js";
import { mapGetters, mapActions } from "vuex";
export default {
  components: {
    //   tuiButton,
    lPainter,
    lPainterImg,
    lPainterText,
    lPainterQrcode,
  },
  data() {
    return {
      // 封面
      path: "",
      swiperList: [
        // {
        //   url: "/static/share/bg1.png",
        //   path: null,
        //   cssqr:
        //     "width: 60px; height: 60px;position: absolute;top: 83.655%; left: 72.5555%; display: block; padding-bottom: 10rpx; color: #000; font-size: 32rpx; fontWeight: bold",
        //   csstext:
        //     "position: absolute; left: 10.5555%;top: 89.255%; display: block; padding-bottom: 10rpx; color: #fff; font-size: 32rpx; fontWeight: bold",
        // },
        // {
        //   url: "/static/share/bg2.png",
        //   path: null,
        //   cssqr:
        //     "width: 60px; height: 60px;position: absolute;top:  82.355%; left: 73.5555%; display: block; padding-bottom: 10rpx; color: #000; font-size: 32rpx; fontWeight: bold",
        //   csstext:
        //     "position: absolute; left: 10.5555%;top: 87.855%; display: block; padding-bottom: 10rpx; color: #fff; font-size: 32rpx; fontWeight: bold",
        // },
        // {
        //   url: "/static/share/bg3.jpg",
        //   path: null,
        //   cssqr:
        //     "width: 60px; height: 60px;position: absolute;top:  88.355%; left: 73.5555%; display: block; padding-bottom: 10rpx; color: #000; font-size: 32rpx; fontWeight: bold",
        //   csstext:
        //     "position: absolute; left: 11.5555%;top: 93.855%; display: block; padding-bottom: 10rpx; color: #fff; font-size: 32rpx; fontWeight: bold",
        // },
        // {
        //   url: "/static/share/bg4.jpg",
        //   path: null,
        //   cssqr:
        //     "width: 60px; height: 60px;position: absolute;top:  83.355%; left: 73.5555%; display: block; padding-bottom: 10rpx; color: #000; font-size: 32rpx; fontWeight: bold",
        //   csstext:
        //     "position: absolute; left: 10.5555%;top: 88.855%; display: block; padding-bottom: 10rpx; color: #fff; font-size: 32rpx; fontWeight: bold",
        // },
        {
          url: "/static/share/bg5.png?v=2",
          path: null,
          cssqr:
            "width: 120px; height: 120px;position: absolute;top: 75.555%; left: 56.5555%; display: block; padding: 10rpx; color: #000; background: #fff; font-size: 32rpx; fontWeight: bold",
          csstext: false,
        },
      ],
      swiperindex: 0,
    };
  },
  computed: {
    ...mapGetters(["userInfo", "inviteCode", "appConfig"]),

    baseUrl() {
      console.log("baseDomain", baseDomain, this.appConfig);
      return baseDomain;
    },
  },
  onLoad(e) {
    uni.showLoading();
    setTimeout(() => {
      uni.hideLoading();
    }, 3000);
  },
  methods: {
    saveok(e, index) {
      this.swiperList[index]["path"] = e;
      // console.log(e, index);
    },
    swiperchange(e) {
      this.swiperindex = e.detail.current;
    },
    async requestAndroidPermission(permisionID) {
      let _this = this;
      var result = await erequestAndroidPermission(permisionID);
      var strStatus;
      if (result == 1) {
        strStatus = "已获得授权";
        uni.saveImageToPhotosAlbum({
          filePath: _this.swiperList[_this.swiperindex]["path"],
          success: function () {
            uni.showToast({
              title: "保存成功!",
              icon: "none",
            });
          },
          fail: function (e) {
            uni.showToast({
              title: "保存失败!" + JSON.stringify(e),
              icon: "none",
            });
          },
        });
      } else if (result == 0) {
        uni.showToast({
          title: "未获得授权!",
          icon: "none",
        });
      } else {
        uni.showToast({
          title: "被拒绝权限!",
          icon: "none",
        });
      }
      // uni.showModal({
      //   content: permisionID + strStatus,
      //   showCancel: false,
      // });
    },
    save() {
      this.requestAndroidPermission(
        "android.permission.WRITE_EXTERNAL_STORAGE"
      );
    },
  },

  onShow() {},
  onReady() {
    uni.setNavigationBarTitle({
      title: this.$t("邀请海报"),
    });
  },
};
</script>
  <style lang="scss" scoped>
.swiper {
  margin-top: 0;
  margin-bottom: 40px;
}

.swiper,
.swiper-item {
  height: 100vh;
  text-align: center;
}
.btn {
  position: fixed;
  display: block;
  width: 80vw;
  text-align: center;
  bottom: 0;
  left: 0;
  padding-top: 12px;
  padding-bottom: 12px;
  padding-left: 12px;
  margin-left: 60rpx;
  padding-right: 12px;
  text-align: center;
}
.btnone {
  display: block;
  background: linear-gradient(135deg, #f66948, #f5303c);
  border-radius: 25px;
  height: 45px;
  line-height: 45px;
  color: #fff;
}
.bg {
  width: 100%;
  height: 100vh;
}
.img {
  width: 750rpx;
  height: 1250rpx;
}
.wx-login-title {
  text-align: center;
  padding-top: 120rpx;
  font-size: 42rpx;
  padding-bottom: 24rpx;
}

.xw-login-form {
  padding: 34rpx;
}

.xw-login-form-item {
  position: relative;
  padding: 0 12rpx;
  border-bottom: 1px #eee solid;
  display: flex;
  flex-direction: row;
  align-items: center;
  line-height: 100rpx;
  height: 100rpx;
}

.xw-login-form-label {
  width: 9rem;
  min-width: 9rem;
}

.xw-login-form-input {
}

.login-agree {
  margin-top: 34rpx;
  justify-content: center;
  display: flex;
  flex-direction: row;
  align-items: center;
}

.login-agree-text,
.login-agree-btn {
  font-size: 24rpx;
  color: #222;
}

.login-agree-text {
  color: #8295a5;
}

.login-form-icon {
  width: 50rpx;
  height: 50rpx;
}

.login-form-seepass {
  position: absolute;
  right: 20rpx;
  top: 50%;
  transform: translateY(-50%);
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
}

.login-form-icon image {
  float: left;
  width: 50rpx;
  height: 50rpx;
}

.xw-login-form-code {
  padding: 0 12rpx;
  height: 100rpx;
  line-height: 100rpx;
  color: #8295a5;
}

.wx-btn {
  min-width: 200rpx;
  height: 75rpx;
  line-height: 75rpx;
  text-align: center;
  border-radius: 12rpx;
  background-color: #007aff;
  color: #fff;
  font-size: 32rpx;
}

.wx-btn-info {
}

.xw-login-form-btn {
  width: 300rpx;
  margin-top: 120rpx;
}

.body {
  background-color: #fafafa;
  height: 100vh;
}
.numview {
  background-color: #fff;
  margin: 12px;
  padding: 12px;
  border-radius: 12px;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
.numview .num {
  flex: 1;
}

.red {
  color: red;
}

.tipsbody {
  padding-left: 12px;
  padding-top: 12px;
  font-size: 12px;
  line-height: 1.2rem;
}
.tipsbody .info {
  display: block;
  text-align: center;
  font-size: 22px;
  line-height: 1.2rem;
}

.blue {
  color: #226fbd;
}

.d-container {
  display: block;
  padding-left: 12px;
  padding-right: 12px;

  text-align: center;
  margin-top: 12px;
}
.canvas {
  margin: auto;
  position: absolute;
  top: 400px;
  left: 25%;
}
.d-container .title2 {
  padding: 12px;
}

.wrapper .content {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  width: 100%;
  align-content: flex-start;
}
.wrapper .content .item {
  flex: 0 0 25%;
  box-sizing: border-box;
  text-align: center;
}
.wrapper .content .box {
  padding: 6px;
  border-radius: 5px;
  margin: 6px;
  background-color: #fff;
  height: 90rpx;
  padding-top: 25px;
}
.wrapper .content .item .title {
  font-size: 12px;
  color: #000;
}
.wrapper .content .item .yuan {
  font-size: 12px;
  color: red;
  font-weight: bold;
}
.wrapper .content .item .info {
  font-size: 12px;
  background-color: #bfc1cd;
  color: #fff;
  border-radius: 15px;
  padding: 2px 4px;
  margin: 2px 4px;
}

.active .box {
  border: solid 1px red !important;
}

.btn-view {
  padding-top: 12px;
  padding-left: 12px;
  padding-right: 12px;
}

.addBtn {
  width: 4rem;
  height: 4rem;
  position: fixed;
  bottom: 10%;
  right: 10%;
  z-index: 9999;
  background-color: #5677fc;
  border-radius: 4rem;
  text-align: center;
}
.addBtn .icon {
  text-align: center;
  width: 1.4rem;
  height: 1.4rem;
  margin-top: 6px;
  margin-left: 0;
}
.addBtn .title {
  text-align: center;
  color: #fff;
  font-size: 12px;
}
.number {
  position: absolute;
  right: 24px;
}
</style>
