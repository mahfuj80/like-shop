<template>
  <view class="body">
    <view class="tipsbody">
      <view class="title red"> {{ $t("赠送积分") }}* </view>
      <view class="info">
        {{ $t("当前帐户积分") }}: {{ userInfo.user_integral }}
      </view>
    </view>

    <view class="d-container wrapper">
      <view class="wx-login">
        <view class="title2" v-if="mobile"
          >{{ $t("赠送对象") }}: {{ mobile }}</view
        >

        <view class="xw-login-form">
          <form>
            <view class="xw-login-form-item">
              <view class="xw-login-form-label">{{ $t("赠送积分") }}</view>
              <input
                class="xw-login-form-input"
                :placeholder="$t('请填写数量')"
                type="number"
                name="score"
                v-model="score"
              />
            </view>
            <view class="vip-body">
              <view
                class="btn bg-primary white row-center"
                @click="clickSendScoreToUid"
                >{{ $t("赠送") }}</view
              >
            </view>
          </form>
        </view>
      </view>
    </view>
  </view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

import { sendScoreToUid } from "@/api/user";

export default {
  components: {},
  data() {
    return {
      score: 0,
      touserinfo: null,
      uid: 0,
      mobile: "",
    };
  },
  computed: {
    ...mapGetters(["userInfo", "inviteCode", "appConfig"]),
  },
  onLoad(e) {
    if (e && e.uid) {
      this.uid = e.uid;
    }
    if (e && e.mobile) {
      this.mobile = e.mobile;
    }
    // this.vipList();
  },
  onReady() {
    uni.setNavigationBarTitle({
      title: this.$t("积分转赠"),
    });
  },
  methods: {
    clickSendScoreToUid() {
      let _this = this;
      sendScoreToUid({
        score: _this.score,
        touid: _this.uid,
      }).then((res) => {
        if (res.code == 1) {
          // _this.$store.dispatch("get_UserInfo");
          uni.showToast({
            title: "操作成功!",
            icon: "none",
            duration: 3000,
          });
          _this.$store.dispatch("getUser");
        } else {
          uni.showToast({
            title: res.msg,
            icon: "none",
            duration: 3000,
          });
        }
      });
    },
  },

  onShow() {},
};
</script>
<style lang="scss" scoped>
.vip-body {
  width: 100%;
  margin-top: 60rpx;
  margin-bottom: 12rpx;
  text-align: center;
}
.btn {
  width: 80%;
  display: inline-block;
  height: 2rem;
  line-height: 2rem;
  border-radius: 5px;
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

.blue {
  color: #226fbd;
}

.d-container {
  padding-left: 12px;
  padding-right: 12px;
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
