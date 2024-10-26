<template>
  <view>
    <view class="user-vip">
      <view class="header">
        <view class="user-vip-info row">
          <custom-image
            width="110rpx"
            height="110rpx"
            round
            :src="userInfo.avatar"
          ></custom-image>
          <view class="ml20 column">
            <view class="user-text white xxl row">{{ userInfo.nickname }}</view>
            <view class="user-level white xs row-center"
              >{{ $t("当前等级") }}：{{
                userInfo.level ? ('V' + userInfo.level) : $t("无")
              }}</view
            >
          </view>
        </view>
      </view>
      <view class="content">
        <view class="vip-swiper-container">
          <swiper
            class="swiper"
            style="height: 320rpx"
            previous-margin="60rpx"
            next-margin="60rpx"
            display-multiple-items="1"
            :current="currentIndex"
            @change="bindchange"
          >
            <swiper-item v-for="(item, index) in levelList" :key="index">
              <view
                class="vip-card-item"
                :style="'background-image: url(' + item.background_image + ');'"
              >
                <view class="row-between">
                  <view>
                    <view
                      class="row grade white sm"
                      >{{ $t("会员") }}</view
                    >
                  </view>
                  <image class="grade-icon" :src="item.image"></image>
                </view>
                <view class="row-between vip-name white">
                  <view class="bold">{{ $t(item.name) }}</view>
                  <view class="bold">{{ $t(item.money_value) }}</view>
                  <view class="sm">{{ item.next_level }}</view>
                </view>
                <view class="row-center" v-if="item.diff_growth_percent">
                  <view class="vip-progress bg-white row">
                    <view
                      class="vip-progress-bar"
                      :style="'width: ' + item.diff_growth_percent * 100 + '%'"
                    ></view>
                  </view>
                </view>
                <!-- <view class="row-between mt20" style="padding: 0 30rpx">
                  <navigator hover-class="none" class="row">
                    <view class="sm white" style="line-height: 36rpx">
                      {{
                        item.current_level_status == 0
                          ? $t("当前高于该等级")
                          : $t("当前成长值") + " " + userInfo.user_growth
                      }}
                    </view>
                  </navigator>
                  <view class="white">{{item.diff_growth_tips}}</view>
                </view> -->
              </view>
            </swiper-item>
          </swiper>
        </view>
        <view class="vip-grade-rule">
          <view class="title row">
            <view class="line br60"></view>
            <view class="xl ml20 bold">{{ $t("说明") }}</view>
          </view>
          <text v-if="mylevel && mylevel.discount" class="rule-content column lighter ml20">
             {{ $t('会员商品专属') }} {{ mylevel.discount }}{{ $t('折扣优惠')  }}
          </text>
        </view>
        <view class="vip-rights" style="display: none">
          <view class="title row">
            <view class="line br60"></view>
            <view class="xl ml20 bold">{{ $t("会员权益") }}</view>
          </view>
          <view class="rights-list row">
            <view
              v-for="(item, index) in privilegeList"
              :key="index"
              class="rights-item column-center"
            >
              <image class="mb10" :src="item.image"></image>
              <view class="sm">{{ item.name }}</view>
            </view>
          </view>
        </view>
      </view>
      <view class="vip-body" v-if="check_has_applay == 0">
        <view
          class="btn bg-primary white row-center"
          @click="offlinePayInfoShowClick"
          >{{ $t("开通会员") }}
        </view>
      </view>
      <view class="vip-body" v-else>
        <view class="btn bg-gray white row-center">{{ $t("等待审核") }}</view>
      </view>
    </view>
    <loading-view v-if="!userInfo.nickname"></loading-view>

    <u-modal
      v-model="showPay"
      :confirm-text="$t('提交')"
      :cancelText="$t('取消')"
      :showCancelButton="false"
      :show-title="false"
      confirm-color="#FF2C3C"
      @confirm="clickBuyVip"
      @cancel="hideDialog"
    >
      <view class="column-center tips-dialog" style="padding: 20rpx 0">
        <view class="inputbox" v-if="payBankInfo">
          <view class="title2">{{ $t("银行卡") }}: </view>
          <view class="value">{{ payBankInfo.config.bank_name }}</view>
        </view>
        <view class="inputbox" v-if="payBankInfo">
          <view class="title2">{{ $t("银行帐户") }}: </view>
          <view class="value">{{ payBankInfo.config.banck_account }}</view>
        </view>
        <view class="inputbox" v-if="payBankInfo">
          <view class="title2">{{ $t("公司名称") }}: </view>
          <view class="value">{{ payBankInfo.config.commpay_name }}</view>
        </view>
        <view class="inputbox" v-if="payBankInfo">
          <view class="title2">{{ $t("上传支付凭证") }}: </view>
          <view class="user-avatar-box column-center">
            <uploader @after-read="afterRead" useSlot>
              <image
                class="user-avatar"
                :src="
                  img
                    ? baseDomain + '/' + img
                    : null || '/static/images/icon_float_more.png'
                "
              />
            </uploader>
          </view>
        </view>
      </view>
    </u-modal>
  </view>
</template>

<script>
import { getLevelList, buyVip } from "@/api/user";
import { getOrderDetail, offlinePayInfo, updateOrderImg } from "@/api/order";
import { uploadFile, isWeixinClient, trottle } from "@/utils/tools";
import { baseDomain } from "@/config/app";

export default {
  data() {
    return {
      userInfo: {},
      currentIndex: 0,
      levelList: [],
      growthRule: "",
      privilegeList: [],
      // 支付凭证
      payInfo: {},
      showPay: false,
      payBankInfo: null,
      img: "",
      //
      check_has_applay: 0, // 已经提交的申请数量
      mylevel: null,
    };
  },

  components: {},
  props: {},

  onLoad: function (options) {
    console.log("userInfo", this.userInfo);
    this.getLevelListFun();
  },
  onReady() {
    uni.setNavigationBarTitle({
      title: this.$t("会员中心"),
    });
  },
  computed: {
    baseDomain() {
      return baseDomain;
    },
  },

  methods: {
    // 文件上传读取
    afterRead(e) {
      let _this = this;
      const file = e;
      console.log(file);
      uni.showLoading({
        title: "正在上传中...",
        mask: true,
      });
      file.forEach((item) => {
        uploadFile(item.path).then((res) => {
          uni.hideLoading();
          _this.img = res.base_url;
        });
      });
    },
    async offlinePayInfoShow() {
      const res = await offlinePayInfo();
      if (res?.code == 1) {
        this.payBankInfo = res.data;
        console.log(res.data);
      }
      console.log('showPay');
      this.showPay = true;
    },
    bindchange(e) {
      let { current } = e.detail;
      let currentLevel = this.levelList[current];
      this.currentIndex = current;
      this.privilegeList = currentLevel.level_privilege;
    },

    getLevelListFun() {
      getLevelList().then((res) => {
        const { code, data } = res;
        if (code != 1) return;
        const { user, growth_rule, level_list, check_has_applay, mylevel } = data;
        let index = level_list.findIndex(
          (item) => item.current_level_status == 1
        );
        if (index == -1) index = 0;
        this.userInfo = user;
        this.growthRule = growth_rule;
        this.levelList = level_list;
        this.mylevel = mylevel;
        this.currentIndex = index;
        this.privilegeList = level_list[index].level_privilege;
        this.check_has_applay = check_has_applay;
      });
    },
    offlinePayInfoShowClick() {
      this.offlinePayInfoShow();
    },
    clickBuyVip() {
      var _this = this;
      buyVip({
        img: _this.img,
      }).then((res) => {
        // if (res.code == 0) {
        //   uni.showToast({
        //     title: res.msg,
        //     icon: "none",
        //   });
        // }
        _this.$store.dispatch("getUser");
        _this.getLevelListFun();
        uni.showToast({
          title: res.msg,
          icon: "none",
        });
      });
    },
  },
};
</script>
<style lang="scss">
page {
  background-color: #fff;

  .user-vip {
    .header {
      background-image: url(../../static/images/vip_grade_bg.png);
      padding-top: 30rpx;
      background-size: 100% 100%;
      height: 382rpx;

      .user-vip-info {
        padding-left: 30rpx;

        .user-level {
          border: 1px solid white;
          border-radius: 100rpx;
          padding: 4rpx 20rpx;
          line-height: 30rpx;
        }

        .user-text {
          line-height: 50rpx;
          font-weight: bold;
        }
      }
    }

    .content {
      margin-top: -200rpx;

      .vip-card-item {
        height: 320rpx;
        width: 600rpx;
        position: relative;
        background-size: 100% 100%;

        .grade {
          line-height: 36rpx;
          background-color: rgba(0, 0, 0, 0.5);
          border-top-right-radius: 100rpx;
          border-bottom-right-radius: 100rpx;
          height: 50rpx;
          padding: 0 28rpx;
        }

        .user-grade {
          line-height: 36rpx;
          margin-left: 30rpx;
        }

        .grade-icon {
          width: 120rpx;
          height: 100rpx;
        }

        .vip-name {
          padding: 10rpx 30rpx;
          font-size: 46rpx;
          text-align: center;
          align-items: flex-end;
          margin-bottom: 30rpx;
        }

        .vip-progress {
          height: 8rpx;
          width: 540rpx;

          .vip-progress-bar {
            background-color: #f8d07c;
            height: 100%;
          }
        }
      }

      .vip-grade-rule {
        margin: 24rpx 40rpx;

        .title {
          .line {
            width: 8rpx;
            height: 34rpx;
            background-color: #f79c0c;
          }
        }
      }

      .vip-rights {
        margin: 24rpx 40rpx;

        .title {
          padding: 28rpx 0;

          .line {
            width: 8rpx;
            height: 34rpx;
            background-color: #f79c0c;
          }
        }

        .rights-item {
          width: 25%;
          padding-bottom: 30rpx;

          image {
            width: 82rpx;
            height: 82rpx;
          }
        }
      }
    }
  }
}
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
.inputbox {
  display: block;
  text-align: left;
  width: 100%;
  padding-left: 40rpx;
  .title2,
  .value {
    display: inline-block;
  }
  .value {
    padding-left: 12rpx;
  }
  padding-bottom: 22rpx;
  .user-avatar-box {
    // width: 120rpx;
  }
  .user-avatar {
    width: 420rpx;
  }
}
</style>
