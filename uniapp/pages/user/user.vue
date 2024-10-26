<template>
  <view class="user" :style="[background]">
    <view class="header">
      <!-- #ifndef  H5 -->
      <u-sticky offset-top="0" h5-nav-height="0" bg-color="transparent">
        <u-navbar
          :is-back="false"
          :title="$t('个人中心')"
          :title-bold="true"
          :is-fixed="false"
          :border-bottom="false"
          :background="{ background: 'rgba(256,256, 256,' + navBg + ')' }"
          :title-color="navBg > 0.5 ? '#000' : '#fff'"
        ></u-navbar>
      </u-sticky>
      <!-- #endif -->
      <view class="user-info row-between">
        <view class="info row">
          <image
            class="avatar mr20 flexnone"
            @tap="goLogin"
            :src="
              isLogin ? userInfo.avatar : '/static/images/head.png'
            "
          ></image>
          <view class="white" v-if="isLogin">
            <view class="name xxl line1">{{ userInfo.nickname }}</view>
            <view class="name xxl line1" v-if="userInfo.is_storer">{{
              $t("店长")
            }}</view>
            <view class="user-id row-between" v-if="userInfo.distribution_code">
              <view class="xs white ml20 mr20"
                >{{ $t("会员邀请码") }}:
                {{ userInfo.distribution_code || "" }}</view
              >
              <view
                class="xs normal copy-btn row-center ml5"
                @tap.stop="onCopy"
                >{{ $t("复制") }}</view
              >
            </view>
          </view>
          <view class="white" v-else @tap="goLogin">
            <view style="font-size: 42rpx">{{ $t("点击登录") }}</view>
            <view class="sm">{{ $t("登录体验更多功能") }}</view>
          </view>
        </view>
        <view class="row" style="align-self: flex-start">
          <!-- <view
            class="user-opt"
            style="margin-right: 30rpx"
            @tap="goPage('/pages/bundle/message_center/message_center')"
          >
            <view class="dot row-center" v-if="userInfo.notice_num"></view>
            <image
              style="width: 58rpx; height: 58rpx"
              src="/static/images/icon_my_news.png"
            ></image>
          </view> -->
          <view
            class="user-opt"
            @tap="goPage('/pages/bundle/user_profile/user_profile')"
          >
            <image
              style="width: 58rpx; height: 58rpx"
              src="/static/images/icon_my_setting.png"
            ></image>
          </view>
        </view>
      </view>
      <view v-if="false" class="member column-end" @tap="goPage('/pages/user_vip/user_vip')">
        <view class="member-entery row-between">
          <view class="row">
            <image class="icon-md" src="/static/images/icon_member.png"></image>
            <view class="ml10">{{
              isLogin
                ? $t("会员") + "  V" + $t(userInfo.level)
                : $t("登录查看会员权益")
            }}</view>
          </view>
          <view class="row">
            <view class="sm">{{ $t("查看会员权益") }}</view>
            <u-icon name="arrow-right"></u-icon>
          </view>
        </view>
      </view>

      <view class="my-assets bg-red">
        <view class="nav row">
          <view
            class="column-center mb20 assets-item"
            @tap="goPage('/pages/bundle/user_wallet/user_wallet')"
          >
            <view class="xl primary">{{ userInfo.today_y }}</view>
            <view class="sm">{{ $t("今日预估") }}</view>
          </view>
          <view
            class="column-center mb20 assets-item"
            @tap="goPage('/pages/bundle/user_wallet/user_wallet')"
          >
            <view class="xl primary">{{ userInfo.today_j }}</view>
            <view class="sm">{{ $t("今日结算") }}</view>
          </view>
          <view
            class="column-center mb20 assets-item"
            @tap="goPage('/pages/bundle/user_wallet/user_wallet')"
          >
            <view class="xl primary">{{ userInfo.week_y }}</view>
            <view class="sm">{{ $t("本周预估") }}</view>
          </view>
          <view
            class="column-center mb20 assets-item"
            @tap="goPage('/pages/bundle/user_wallet/user_wallet')"
          >
            <view class="xl primary">{{ userInfo.week_j }}</view>
            <view class="sm">{{ $t("本周结算") }}</view>
          </view>
        </view>
      </view>
    </view>
    <view class="my-assets bg-white">
      <view class="title row lg">{{ $t("我的资产") }}</view>
      <view class="nav row">
        <view
          class="column-center mb20 assets-item"
          @tap="goPage('/pages/bundle/user_wallet/user_wallet')"
        >
          <view class="xl primary">{{ userInfo.user_money }}</view>
          <view class="sm">{{ $t("积分") }}</view>
        </view>
        <view
          v-if="false"
          class="column-center mb20 assets-item"
          @tap="goPage('/pages/bundle/user_sign/user_sign')"
        >
          <view class="xl primary">{{ userInfo.user_integral }}</view>
          <view class="sm">{{ $t("积分") }}</view>
        </view>
        <view
          v-if="false"
          class="column-center mb20 assets-item"
          @tap="goPage('/pages/user_coupon/user_coupon')"
        >
          <view class="xl primary">{{ userInfo.coupon }}</view>
          <view class="sm">{{ $t("优惠券") }}</view>
        </view>
      </view>
    </view>
    <view class="order-nav bg-white">
      <view
        class="title row-between"
        @tap="goPage('/pages/user_order/user_order')"
      >
        <view class="lg">{{ $t("我的订单") }}</view>
        <view class="muted sm row">
          {{ $t("全部订单") }}
          <image
            class="icon-sm ml10"
            src="/static/images/arrow_right.png"
          ></image>
        </view>
      </view>
      <view class="nav row">
        <view
          class="item column-center mb20"
          @tap="goPage('/pages/user_order/user_order?type=pay')"
        >
          <view class="icon-contain">
            <view v-if="userInfo.wait_pay" class="badge xs row-center bg-white">
              {{ userInfo.wait_pay }}
            </view>
            <image
              class="nav-icon"
              src="/static/images/ordericon/daifujkuan.png"
            ></image>
          </view>
          <view class="sm mt10">{{ $t("待付款") }}</view>
        </view>
        <view
          class="item column-center mb20"
          @tap="goPage('/pages/user_order/user_order?type=delivery')"
        >
          <view class="icon-contain">
            <view
              v-if="userInfo.wait_delivery"
              class="badge xs row-center bg-white"
            >
              {{ userInfo.wait_delivery }}
            </view>
            <image
              class="nav-icon mb10"
              src="/static/images/ordericon/daifahuo.png"
            ></image>
          </view>
          <view class="sm">{{ $t("待发货") }}</view>
        </view>
        <view
          class="item column-center mb20"
          @tap="goPage('/pages/user_order/user_order?type=delivery')"
        >
          <view class="icon-contain">
            <view
              v-if="userInfo.wait_take"
              class="badge xs row-center bg-white"
            >
              {{ userInfo.wait_take }}
            </view>
            <image
              class="nav-icon"
              src="/static/images/ordericon/daishouhuo.png"
            ></image>
          </view>
          <view class="sm mt10">{{ $t("待收货") }}</view>
        </view>
        <view
          class="item column-center mb20"
          @tap="goPage('/pages/bundle/goods_comment_list/goods_comment_list')"
        >
          <view class="icon-contain">
            <view
              v-if="userInfo.wait_comment"
              class="badge xs row-center bg-white"
            >
              {{ userInfo.wait_comment }}
            </view>
            <image
              class="nav-icon"
              src="/static/images/ordericon/pingjia.png"
            ></image>
          </view>
          <view class="sm mt10">{{ $t("商品评价") }}</view>
        </view>
        <view
          class="item column-center mb20"
          @tap="goPage('/pages/bundle/post_sale/post_sale')"
        >
          <view class="icon-contain">
            <view
              v-if="userInfo.after_sale"
              class="badge xs row-center bg-white"
            >
              {{ userInfo.after_sale }}
            </view>
            <image
              class="nav-icon"
              src="/static/images/ordericon/tuikuan.png"
            ></image>
          </view>
          <view class="sm mt10">{{ $t("退款") }}/{{ $t("售后") }}</view>
        </view>
      </view>
    </view>
    <view class="server-nav bg-white" v-if="menuList && menuList.length > 0">
      <view>
        <view class="title row-between">
          <view class="lg">{{ $t("我的功能") }}</view>
        </view>
      </view>
      <view class="nav row wrap">
        <button
          v-for="(item, index) in menuList"
          :key="index"
          class="item column-center mb20"
          hover-class="none"
          :open-type="item.link_type == 3 ? 'contact' : ''"
          @tap="tapMenu(item)"
          style="width: 25%"
        >
          <image class="nav-icon" :src="item.image"></image>
          <view class="sm mt10">{{ $t(item.name) }}</view>
        </button>
        <button
          class="item column-center mb20"
          hover-class="none"
          @tap="changeLang()"
          style="width: 25%"
        >
          <image class="nav-icon" src="/static/images/homeimg/lang.png"></image>
          <view class="sm mt10">{{ $t("语言") }}</view>
        </button>
      </view>
    </view>
    <!-- <recommend /> -->

    <u-picker
      :cancelText="$t('取消')"
      :confirmText="$t('确定')"
      mode="selector"
      v-model="showPickerLang"
      :default-selector="[0]"
      :range="locales"
      @confirm="onConfirmLang"
    />

    <!-- <view class="xs muted" style="margin: 50rpx 0;">
			<view class="row-center">
				由 likeshop 提供免费开源商城系统
			</view>
			<view class="row-center">
				© likeshop.cn
			</view>
		</view> -->
  </view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import { getUser } from "@/api/user";
import { getMenu } from "@/api/store";
import store from "../../store";

import { toLogin, wxMnpLogin } from "@/utils/login";
import { menuJump, copy, setTabbar } from "@/utils/tools";
import Cache from "@/utils/cache";
const app = getApp();
export default {
  data() {
    return {
      showNav: false,
      navH: 0,
      navBg: 0,
      menuList: [],
      statusBarH: "",

      // 语言
      systemLocale: "",
      applicationLocale: "",
      showPickerLang: false,
    };
  },

  components: {},
  props: {},

  onLoad(options) {
    setTabbar(this);
    this.getMenuFun();
  },

  onShow() {
    this.getUser();
    this.getCartNum();
  },
  onPageScroll(e) {
    const top = uni.upx2px(100);
    const { scrollTop } = e;
    let percent = scrollTop / top > 1 ? 1 : scrollTop / top;
    this.navBg = percent;
  },

  onUnload() {},
  onPullDownRefresh() {
    this.getUser().then(() => {
      uni.stopPullDownRefresh();
    });
    this.getMenuFun();
  },
  onShareAppMessage() {
    const shareInfo = Cache.get("shareInfo");
    return {
      title: shareInfo.mnp_share_title,
      path: "pages/index/index?invite_code=" + this.inviteCode,
    };
  },
  methods: {
    ...mapActions(["getCartNum", "getUser"]),
    changeLang(e) {
      this.showPickerLang = true;
    },
    onConfirmLang(value) {
      this.showPickerLang = false;
      this.onLocaleChange({
        code: this.locales[value],
      });
    },
    onLocaleChange(e) {
      if (e.code == "English") {
        e.code = "en";
      }
      if (e.code == "中文") {
        e.code = "zh-Hans";
      }
      console.log("onLocaleChange", e.code);
      if (this.isAndroid) {
        uni.showModal({
          content: this.$t("index.language-change-confirm"),
          success: (res) => {
            if (res.confirm) {
              uni.setLocale(e.code);
            }
          },
        });
      } else {
        uni.setLocale(e.code);
        this.$i18n.locale = e.code;
        try {
          // 保存当前语言
          if (this.$i18n && this.$i18n.locale) {
            store.commit("setLang", this.$i18n.locale);
          }
        } catch (error) {}
      }
    },
    goLogin() {
      let { isLogin } = this;
      if (isLogin) {
        uni.navigateTo({
          url: "/pages/bundle/user_profile/user_profile",
        });
        return;
      }
      toLogin();
    },

    goPage(url) {
      if (!this.isLogin) return toLogin();
      uni.navigateTo({
        url,
      });
    },
    tapMenu(item) {
      if (!this.isLogin) return toLogin();
      console.log(item);
      menuJump(item);
    },
    async getMenuFun() {
      const { data, code } = await getMenu({
        type: 2,
      });
      if (code == 1) {
        this.menuList = data;
      }
    },

    onCopy(e) {
      copy(this.userInfo.distribution_code);
    },
  },
  computed: {
    ...mapGetters(["cartNum", "userInfo", "inviteCode", "appConfig"]),
    locales() {
      return ["English", "中文"];
    },
    background() {
      const { center_setting } = this.appConfig;
      return center_setting.top_bg_image
        ? {
            "background-image": `url(${center_setting.top_bg_image})`,
          }
        : {};
    },
  },
};
</script>
<style lang="scss">
page {
  background: #e86363;
}
.user {
  background-image: url(../../static/images/my_topbg.png);
  background-size: 100% 420rpx;
  background-repeat: no-repeat;
  .header {
    display: flex;
    flex-direction: column;
    // height: 420rpx;
    .user-info {
      padding: 10rpx 30rpx;
      //#ifdef  H5
      padding-top: 90rpx;
      //#endif
      .avatar {
        height: 110rpx;
        width: 110rpx;
        border-radius: 50%;
        overflow: hidden;
      }
      .name {
        text-align: left;
        margin-bottom: 5rpx;
        max-width: 400rpx;
      }

      .user-id {
        border: 1px solid white;
        border-radius: 100rpx;

        .copy-btn {
          background-color: #ffdfda;
          height: 40rpx;
          width: 90rpx;
          border-radius: 100rpx;
        }
      }

      .user-opt {
        position: relative;

        .dot {
          position: absolute;
          background-color: #ee0a24;
          border: 2rpx solid #ffffff;
          color: $-color-primary;
          border-radius: 100%;
          top: 6rpx;
          right: 0rpx;
          font-size: 22rpx;
          min-width: 16rpx;
          height: 16rpx;
        }
      }

      .buyer-type {
        background-color: #ffa200;
        height: 38rpx;
        padding: 0 10rpx;
      }
    }
    .member {
      flex: 1;
      padding: 0 20rpx;
      .member-entery {
        color: #ffe0a1;
        padding: 0 16rpx;
        width: 100%;
        height: 80rpx;
        background: url(../../static/images/bg_member_grade.png);
        background-size: 100%;
      }
    }
  }

  .order-nav {
    .icon-contain {
      position: relative;
    }
  }

  .order-nav,
  .my-assets {
    margin: 10rpx 10rpx 0;
    border-radius: 8rpx;
  }

  .server-nav {
    margin: 10rpx;
    border-radius: 8rpx;
  }

  .title {
    height: 88rpx;
    padding: 0 30rpx;
    border-bottom: $-dashed-border;
  }

  .nav {
    padding: 26rpx 0 0;

    .assets-item {
      flex: 1;
    }

    .item {
      width: 25%;
    }

    .badge {
      padding: 0 6rpx;
      min-width: 28rpx;
      height: 28rpx;
      border-radius: 28rpx;
      box-sizing: border-box;
      border: 1rpx solid $-color-primary;
      color: $-color-primary;
      position: absolute;
      left: 33rpx;
      top: -10rpx;
      z-index: 2;
    }

    .nav-icon {
      width: 40rpx;
      height: 40rpx;
    }
  }
}
.bg-red {
  border-radius: 0 !important;
  border-top-left-radius: 15px !important;
  border-top-right-radius: 15px !important;
  background: #e8433b;
  .sm,
  .primary {
    color: #fff;
  }
  .primary {
    font-weight: bold;
    font-size: 15px;
  }
  .sm {
    font-size: 11px;
  }
}
</style>
