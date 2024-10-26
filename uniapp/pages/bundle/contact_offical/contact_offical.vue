<template>
  <view class="contact-offical">
    <view class="header"> </view>
    <view class="content column-center">
      <view class="content-view column-center bg-white">
        <img class="content-img" :src="server.image" />
        <view class="primary wechat-num lg">{{ $t("二维码") }}</view>
        <!-- <view class="row-center copy-btn xxl white" @click="onCopy(server.wechat)">
                    <image class="mr5" style="width: 32px;height: 25px;" src="/static/images/wechat-btn-icon.png" />
                    微信扫码添加
                </view> -->
        <view class="mt20 normal xs" style="line-height: 35px">{{
          server.time
        }}</view>
        <!-- #ifdef MP-WEIXIN -->
        <button open-type="contact" class="sm row-center br60">
          <text style="line-height: 50px">{{ $t("联系客服") }}</text>
        </button>
        <!-- #endif -->
        <!-- #ifndef MP-WEIXIN -->
        <view class="sm row-center br60" @click="tipsShow()">
          <text style="line-height: 50px">{{ $t("联系客服") }}</text>
        </view>
        <!-- #endif -->
      </view>
      <view class="xs white" style="margin-top: 40px; line-height: 49px">
        {{ $t("无法添加或疑难问题请联系工作人员") }}
      </view>
      <view class="row white">
        <view class="xs" style="line-height: 49px">{{ server.phone }}</view>
        <!-- #ifdef H5 -->
        <a
          class="ml10 phone-btn xs row-center white"
          :href="'tel:' + server.phone"
          >{{ $t("拨打") }}</a
        >
        <!-- #endif -->
        <!-- #ifdef MP-WEIXIN -->
        <a class="ml10 phone-btn xs row-center white" @click="showTelTips">{{
          $t("拨打")
        }}</a>
        <!-- #endif -->
        <view
          class="ml5 copy-phone-btn xs row-center"
          @click="onCopy(server.phone)"
          >{{ $t("复制") }}</view
        >
      </view>
    </view>
    <u-modal
      :content="content"
      v-model="showPhoneCall"
      show-cancel-button
      confirm-text="呼叫"
      :confirm-color="primaryColor"
      @confirm="onCall"
    >
    </u-modal>
  </view>
</template>

<script>
import { getService } from "@/api/app";
import { copy } from "@/utils/tools";
export default {
  name: "contactOffical",
  data() {
    return {
      server: {},
      showPhoneCall: false,
      content: "即将打电话给",
    };
  },

  onLoad() {
    this.$getService();
  },
  onReady() {
    uni.setNavigationBarTitle({
      title: this.$t("联系客服"),
    });
  },

  methods: {
    $getService() {
      getService().then((res) => {
        if (res.code == 1) {
          this.server = res.data;
        }
      });
    },
    tipsShow() {
      this.$toast({ title: "该功能暂未开放" });
    },
    onCopy(str) {
      copy(str);
    },
    showTelTips() {
      this.showPhoneCall = true;
      this.content = "即将打电话给" + this.server.phone;
    },
    onCall() {
      wx.makePhoneCall({
        phoneNumber: this.server.phone.toString(),
        success(e) {
          console.log("成功", e);
        },
        fail(err) {
          console.log("失败", err);
        },
      });
    },
  },
};
</script>

<style lang="scss">
.contact-offical {
  min-height: 100vh;
  background: linear-gradient(180deg, #f62318 0%, #f20407 100%);
  .header {
    height: 383px;
    width: 100%;
  }
  .content {
    .content-view {
      border: 5px solid #fa7949;
      width: 310px;
      border-radius: 10px;
      margin-top: -350px;
      .content-img {
        margin-top: 20px;
        height: 192px;
        width: 192px;
      }
      .wechat-num {
        line-height: 45px;
      }
      .copy-btn {
        background: linear-gradient(180deg, #ffa200 0%, #ff5e44 100%);
        width: 230px;
        height: 50px;
        border-radius: 50px;
        line-height: 49px;
        margin-top: 30px;
      }
      .contact-btn {
        width: 300rpx;
        height: 60rpx;
        margin-bottom: 20rpx;
      }
    }
    .phone-btn {
      background: linear-gradient(180deg, #ffa200 0%, #ff5e44 100%);
      height: 24px;
      width: 60px;
      line-height: 33px;
      border-radius: 50px;
    }
    .copy-phone-btn {
      background-color: rgba($color: #fff, $alpha: 0.5);
      height: 24px;
      width: 60px;
      line-height: 33px;
      border-radius: 50px;
    }
  }
}
</style>
