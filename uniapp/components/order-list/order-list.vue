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
  <view>
    <view class="order-list">
      <navigator
        v-for="(item, index) in orderList"
        :key="index"
        hover-class="none"
        class="order-item bg-white mt20"
        :url="'/pages/order_details/order_details?id=' + item.id"
      >
        <view class="order-header row-between">
          <view class="row">
            <view v-if="item.order_type == 1" class="mr10">
              <u-tag
                :text="$t('秒杀')"
                size="mini"
                type="primary"
                mode="plain"
              />
            </view>
            <view v-if="item.order_type == 2" class="mr10">
              <u-tag
                :text="$t('拼团')"
                size="mini"
                type="primary"
                mode="plain"
              />
            </view>
            <view v-if="item.order_type == 3" class="mr10">
              <u-tag
                :text="$t('砍价')"
                size="mini"
                type="primary"
                mode="plain"
              />
            </view>
            {{ $t("订单编号") }}：{{ item.order_sn }}
          </view>
          <view :class="item.order_status == 4 ? 'muted' : 'primary'">{{
            $t(getOrderStatus(item.order_status, item))
          }}</view>
        </view>
        <view class="order-con">
          <order-goods :list="item.order_goods"></order-goods>
          <view class="all-price row-end">
            <text class="muted xs"
              >{{ $t("共") }}{{ item.goods_count }}{{ $t("件")
              }}{{ $t("商品") }}，{{ $t("总金额") }}：</text
            >
            <price-format
              :subscript-size="30"
              :first-size="30"
              :second-size="30"
              :price="item.order_amount"
            ></price-format>
          </view>
        </view>
        <view
          class="order-footer row"
          v-if="
            item.cancel_btn ||
            item.delivery_btn ||
            item.take_btn ||
            item.del_btn ||
            item.pay_btn ||
            item.comment_btn ||
            item.img_btn
          "
        >
          <view style="flex: 1">
            <view
              class="primary sm row"
              v-if="getCancelTime(item.order_cancel_time) > 0"
              >{{ $t("支付剩余") }}
              <u-count-down
                separator="zh"
                :show-hours="false"
                :show-seconds="false"
                :timestamp="getCancelTime(item.order_cancel_time)"
                separator-color="#FF2C3C"
                color="#FF2C3C"
                :separator-size="26"
                :font-size="26"
                bg-color="transparent"
                @end="reflesh"
              ></u-count-down
              >{{ $t("自动关闭") }}</view
            >
          </view>
          <view v-if="item.cancel_btn">
            <button
              size="sm"
              class="plain br60 lighter"
              hover-class="none"
              @tap.stop="cancelOrder(item.id)"
            >
              {{ $t("取消订单") }}
            </button>
          </view>
          <view
            v-if="item.delivery_btn && false"
            @tap.stop="
              goPage(
                '/pages/bundle/goods_logistics/goods_logistics?id=' + item.id
              )
            "
          >
            <button size="sm" class="btn plain br60 lighter" hover-class="none">
              {{ $t("查看物流") }}
            </button>
          </view>
          <view v-if="item.del_btn">
            <button
              size="sm"
              class="btn plain br60 lighter"
              hover-class="none"
              @tap.stop="delOrder(item.id)"
            >
              {{ $t("删除订单") }}
            </button>
          </view>
          <view v-if="item.pay_btn" class="ml20">
            <button
              size="sm"
              class="btn bg-primary br60 white"
              @tap.stop="payNow(item.id)"
            >
              {{ $t("立即付款") }}
            </button>
          </view>
          <view v-if="item.img_btn" class="ml20">
            <button
              size="sm"
              class="btn bg-primary br60 white"
              @tap.stop="imgNow(item.id)"
            >
              {{ $t("上传支付凭证") }}
            </button>
          </view>
          <view v-if="item.comment_btn" class="ml20">
            <button
              size="sm"
              hover-class="none"
              class="btn plain btn br60 primary red"
            >
              {{ $t("去评价") }}
            </button>
          </view>
          <view v-if="item.take_btn" class="ml20">
            <button
              size="sm"
              class="btn plain br60 primary red"
              hover-class="none"
              @tap.stop="comfirmOrder(item.id)"
            >
              {{ $t("确认收货") }}
            </button>
          </view>
        </view>
      </navigator>
      <loading-footer :status="status" :slot-empty="true" @refresh="reload">
        <view slot="empty" class="column-center" style="padding-top: 200rpx">
          <image class="img-null" src="/static/images/goods_null.png"></image>
          <text class="lighter">{{ $t("暂无订单") }}</text>
        </view>
      </loading-footer>
    </view>
    <order-dialog
      ref="orderDialog"
      :order-id="orderId"
      :type="type"
      @refresh="reflesh"
    ></order-dialog>
    <loading-view
      v-if="showLoading"
      background-color="transparent"
      :size="50"
    ></loading-view>
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
import {
  getOrderList,
  cancelOrder,
  delOrder,
  confirmOrder,
  offlinePayInfo,
  updateOrderImg,
} from "@/api/order";
import { prepay } from "@/api/app";
import { loadingType } from "@/utils/type";
import { baseDomain } from "@/config/app";

import { wxpay, alipay } from "@/utils/pay";
import { loadingFun, uploadFile } from "@/utils/tools";
export default {
  data() {
    return {
      page: 1,
      orderList: [],
      status: loadingType.LOADING,
      showCancel: false,
      type: 0,
      orderId: "",
      showLoading: false,

      // 支付凭证相关
      payInfo: {},
      showPay: false,
      payBankInfo: null,
      img: "",
      _id: "",
    };
  },

  components: {},
  props: {
    orderType: {
      type: String,
    },
  },
  created: function () {
    uni.$on("refreshorder", () => {
      this.reflesh();
    });
  },
  beforeMount: function () {
    this.getOrderListFun();
  },
  destroyed: function () {
    uni.$off("refreshorder");
  },
  methods: {
    reflesh() {
      this.page = 1;
      this.orderList = [];
      this.status = loadingType.LOADING;
      this.type = 0;
      this.getOrderListFun();
    },

    reload() {
      this.status = loadingType.LOADING;
      this.getOrderListFun();
    },

    orderDialog() {
      this.$refs.orderDialog.open();
    },

    delOrder(id) {
      this.orderId = id;
      this.type = 1;
      this.$nextTick(() => {
        this.orderDialog();
      });
    },

    comfirmOrder(id) {
      this.orderId = id;
      this.type = 2;
      this.$nextTick(() => {
        this.orderDialog();
      });
    },

    cancelOrder(id) {
      this.orderId = id;
      this.type = 0;
      this.$nextTick(() => {
        this.orderDialog();
      });
    },

    payNow(id) {
      this.showLoading = true;
      prepay({
        from: "order",
        order_id: id,
      }).then((res) => {
        let args = res.data;
        this.showLoading = false;
        if (res.code == 1) {
          wxpay(args).then((resPay) => {
            if (resPay == "success") {
              this.$toast({
                title: "支付成功",
              });
              uni.$emit("refreshorder");
            }
          });
        } else if (res.code == 20001) {
          alipay(args).then((resPay) => {
            if (resPay == "success") {
              this.$toast({
                title: "支付成功",
              });
              uni.$emit("refreshorder");
            }
          });
        }
      });
    },
    async imgNow(id) {
      this._id = id;
      const res = await offlinePayInfo();
      if (res?.code == 1) {
        this.payBankInfo = res.data;
        this.showPay = true;
      } else {
        uni.showToast({
          title: "请重试!",
          icon: "none",
          duration: 2000,
        });
      }
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
    // 提交图片凭证
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
        id: _this._id,
        pay_img: _this.img,
      });
      if (res.code == 1) {
        _this.reflesh();
		// 清空数据
        _this.img = null;
        _this._id = 0;
      } else {
        uni.showToast({
          title: res.msg,
          icon: "none",
          duration: 2000,
        });
      }
    },
    async getOrderListFun() {
      let { page, orderType, orderList, status } = this;
      const data = await loadingFun(getOrderList, page, orderList, status, {
        type: orderType,
      });
      if (!data) return;
      this.page = data.page;
      this.orderList = data.dataList;
      this.status = data.status;
    },
    goPage(url) {
      uni.navigateTo({
        url,
      });
    },
  },
  computed: {
    baseDomain() {
      return baseDomain;
    },
    getOrderStatus() {
      return (status, item) => {
        let text = "";
        switch (status) {
          case 0:
		  	text = "待支付";
			if (item && item.pay_img) {
				text = "待审核";
			}
            break;
          case 1:
            text = "待发货";
            break;
          case 2:
            text = "待收货";
            break;
          case 3:
            text = "已完成";
            break;
          case 4:
            text = "订单已关闭";
            break;
        }
        return text;
      };
    },
    getCancelTime() {
      return (time) => time - Date.now() / 1000;
    },
  },
};
</script>
<style lang="scss">
.order-list {
  min-height: calc(100vh - 80rpx);
  padding: 0 20rpx;
  overflow: hidden;

  .order-item {
    border-radius: 10rpx;

    .order-header {
      height: 80rpx;
      padding: 0 24rpx;
      border-bottom: 1px dotted #e5e5e5;
    }

    .all-price {
      text-align: right;
      padding: 0 24rpx 20rpx;
    }

    .order-footer {
      height: 100rpx;
      border-top: $-solid-border;
      padding: 0 24rpx;

      .plain {
        border: 1px solid #bbbbbb;

        &.red {
          border-color: $-color-primary;
        }
      }
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
