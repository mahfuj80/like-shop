// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------

<template>
  <view class="pay-result column-center">
    <view class="contain bg-white">
      <view class="header column-center">
        <view v-if="payInfo.order_status == 1">
          <image
            class="tips-icon"
            src="/static/images/icon_paySuccess.png"
          ></image>
        </view>
        <view v-if="payInfo.order_status == 0 && !payInfo.pay_img">
          <image
            class="tips-icon"
            src="/static/images/icon_payFail.png"
          ></image>
        </view>
        <view v-if="payInfo.order_status == 0 && payInfo.pay_img">
          <image
            class="tips-icon"
            src="/static/images/icon_paySuccess.png"
          ></image>
        </view>
        <view v-if="payInfo.order_status == 1" class="xl mt20">{{
          $t("订单支付成功")
        }}</view>
        <view
          v-if="payInfo.order_status == 0 && !payInfo.pay_img"
          class="xl mt20"
          >{{ $t("待上传支付凭证") }}</view
        >
        <view
          v-if="payInfo.order_status == 0 && payInfo.pay_img"
          class="xl mt20"
          >{{ $t("已经上传支付凭证, 等待订单审核") }}
        </view>
      </view>
      <view style="height: 181rpx"></view>
      <view class="info">
        <view class="order-num row-between mt20">
          <view class="ml20">{{ $t("订单编号") }}</view>
          <view class="mr20">
            {{ payInfo.order_sn }}
          </view>
        </view>
        <view v-if="payInfo.pay_time" class="order-time row-between mt20">
          <view class="ml20">{{ $t("付款时间") }}</view>
          <view class="mr20">{{ payInfo.pay_time }}</view>
        </view>
        <view class="order-pay-type row-between mt20">
          <view class="ml20">{{ $t("支付方式") }}</view>
          <view class="mr20">{{ $t(payInfo.pay_way_text) }}</view>
        </view>
        <view class="order-pay-money row-between mt20">
          <view class="ml20">{{ $t("支付金额") }}</view>
          <view class="mr20">
            <price-format :price="payInfo.order_amount"></price-format>
          </view>
        </view>
      </view>
      <view class="line ml20"></view>
      <view class="opt-btn-contain row-center wrap">
        <navigator
          open-type="redirect"
          hover-class="none"
          class="check-order-btn row-center bg-primary br60 mt20"
          url="/pages/user_order/user_order"
        >
          <view class="white bg-primary lg">{{ $t("查看订单") }}</view>
        </navigator>
        <navigator
          hover-class="none"
          class="go-back-btn row-center br60 mt20"
          open-type="switchTab"
          url="/pages/index/index"
        >
          <view class="primary br60 lg">{{ $t("返回首页") }}</view>
        </navigator>
      </view>
    </view>

    <!-- <view class="xs muted" style="margin: 50rpx 0;">
			<view class="row-center">
				由 likeshop 提供免费开源商城系统
			</view>
			<view class="row-center">
				© likeshop.cn
			</view>
		</view> -->
    <u-modal
      v-model="showPay"
      :confirm-text="$t('提交')"
      :cancelText="$t('取消')"
      :showCancelButton="false"
      :show-title="false"
      confirm-color="#FF2C3C"
      @confirm="cancelApplyFun"
      @cancel="hideDialog"
    >
      <view class="column-center tips-dialog" style="padding: 20rpx 0">
        <view class="inputbox" v-if="payBankInfo">
          <view class="title2">{{ $t('银行卡') }}: </view>
          <view class="value">{{ payBankInfo.config.bank_name }}</view>
        </view>
        <view class="inputbox" v-if="payBankInfo">
          <view class="title2">{{ $t('银行帐户') }}: </view>
          <view class="value">{{ payBankInfo.config.banck_account }}</view>
        </view>
        <view class="inputbox" v-if="payBankInfo">
          <view class="title2">{{ $t('公司名称') }}: </view>
          <view class="value">{{ payBankInfo.config.commpay_name }}</view>
        </view>
        <view class="inputbox" v-if="payBankInfo">
          <view class="title2">{{ $t('上传支付凭证') }}: </view>
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
import { baseDomain } from "@/config/app";
import { uploadFile, isWeixinClient, trottle } from "@/utils/tools";
import { getOrderDetail, offlinePayInfo, updateOrderImg } from "@/api/order";
export default {
  data() {
    return {
      payInfo: {},
      showPay: false,
      payBankInfo: null,
      img: "",
    };
  },
  computed: {
    baseDomain() {
      return baseDomain;
    },
  },

  components: {},
  props: {},
  onLoad: function (options) {
    this.id = options.id;
    this.getOrderResultFun();
  },

  methods: {
    async cancelApplyFun() {
      let _this = this;
      if (this.img == "") {
        uni.showToast({
          title: _this.$t('请上传支付凭证'),
          icon: "none",
          duration: 2000,
        });
        _this.showPay = true;
      }
      const res = await updateOrderImg({
        id: _this.id,
        pay_img: _this.img,
      });
      _this.getOrderResultFun();
    },
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
      this.showPay = true;
    },
    getOrderResultFun() {
      let _this = this;
      getOrderDetail(this.id).then((res) => {
        if (res.code == 1) {
          this.payInfo = res.data;
          if (
            this.payInfo.order_status == 0 &&
            this.payInfo.pay_way == 4 &&
            !this.payInfo.pay_img
          ) {
            _this.offlinePayInfoShow();
          }
        }
      });
    },
  },
};
</script>
<style lang="scss">
.pay-result {
  .contain {
    width: 682rpx;
    margin-left: 20rpx;
    margin-right: 20rpx;
    border-radius: 10rpx;
    margin-top: 78rpx;
    padding-left: 20rpx;
    padding-right: 20rpx;
    padding-bottom: 40rpx;
    position: relative;

    .tips-icon {
      width: 112rpx;
      height: 112rpx;
    }

    .header {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      top: -50rpx;
    }

    .order-num {
      align-items: flex-start;
    }

    .info {
      margin-bottom: 40rpx;
    }

    .opt-btn-contain {
      margin-top: 40rpx;

      .check-order-btn {
        width: 650rpx;
        height: 84rpx;
      }

      .go-back-btn {
        width: 650rpx;
        height: 84rpx;
        border: 1px solid $-color-primary;
        box-sizing: border-box;
      }
    }

    .line {
      width: 650rpx;
      border-top: 1px solid rgba(229, 229, 229, 1);
    }
  }
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
