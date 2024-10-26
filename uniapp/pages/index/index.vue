<template>
  <view class="index home-bg" :style="[navBackground]">
    <!-- #ifdef H5 -->
    <download-nav v-if="isShowDownload"></download-nav>
    <!-- #endif -->
    <!-- 系统语言: {{ systemLocale }} 标题: {{ $t("妆品") }} {{ $i18n.locale }} -->
    <!-- <view class="locale-list">
      <view
        class="locale-item"
        v-for="(item, index) in locales"
        :key="index"
        @click="onLocaleChange(item)"
      >
        <text class="text">{{ item.text }}</text>
        <text class="icon-check" v-if="item.code == applicationLocale"></text>
      </view>
    </view> -->
    <u-sticky
      offset-top="0"
      h5-nav-height="0"
      bg-color="transparent"
      :enable="enable"
    >
      <u-navbar
        :border-bottom="false"
        :is-fixed="false"
        custom-class="home-bg"
        :background="navBackground"
        :is-back="false"
        :height="56"
      >
        <view class="row logo-wrap flex1" v-show="navBg < 1 && showLogo">
          <image :src="logo" mode="heightFix" class="logo ml30"></image>
        </view>
        <view class="flex1 row nav-search" v-show="navSearch">
          <!-- this -->
          <!-- <navigator
            hover-class="none"
            @click="goPage('/pages/bundle/message_center/message_center')"
          >
            <image class="icon-md ml30" src="/static/images/icon_news.png">
            </image>
          </navigator> -->
          <navigator
            class="ml20 flex1 mr20"
            hover-class="none"
            url="/pages/goods_search/goods_search"
          >
            <u-search
              wrap-bg-color="transparent"
              :bg-color="'#fff'"
              :disabled="true"
              :height="62"
              :placeholder="$t('请输入关键字')"
              shape="square"
              inputAlign="center"
            >
            </u-search>
          </navigator>
        </view>
      </u-navbar>
    </u-sticky>
    <view class="flex1 row" v-if="showLogo">
      <!-- <navigator
        hover-class="none"
        @click="goPage('/pages/bundle/message_center/message_center')"
      >
        <image class="icon-md ml30" src="/static/images/icon_news.png"> </image>
      </navigator> -->
      <navigator
        class="ml20 flex1 mr20"
        hover-class="none"
        url="/pages/goods_search/goods_search"
      >
        <u-search
          wrap-bg-color="transparent"
          :bg-color="'#fff'"
          :disabled="true"
          :height="62"
          :placeholder="$t('请输入关键字')"
        ></u-search>
      </navigator>
    </view>
    <view class="contain">
      <view class="main">
        <bubble-tips top="50rpx" :discharge="isDischarge"></bubble-tips>
      </view>
      <swipers radius="0" :pid="1" height="322rpx" padding="0 0 0 0"></swipers>
      <view class="main">
        <!-- 导航入口 -->
        <view class="nav bg-white" v-if="navList.length">
          <swiper
            :style="'height:' + navSwiperH + 'rpx;'"
            @change="swiperChange"
          >
            <swiper-item v-for="(items, index) in navList" :key="index">
              <view class="nav-list row wrap">
                <view
                  v-for="(item, index2) in items"
                  :key="index2"
                  @tap="tapMenu(item)"
                  class="nav-item column-center"
                >
                  <image class="nav-icon" :src="item.image"></image>
                  <view class="name xs namecenter">{{  $t(item.name)  }}</view>
                </view>
              </view>
            </swiper-item>
          </swiper>
          <view class="dots" v-if="navList.length > 1">
            <view
              v-for="(item, index) in navList"
              :key="index"
              :class="'dot ' + (index == currentSwiper ? 'active' : '')"
            ></view>
          </view>
        </view>
        <view class="barimg">
          <view
            class="barimgitem2"
            @click="jump('/pages/sort/sortpage?is_vip=1')"
          >
            <image
              class="bgung"
              mode="widthFix"
              src="/static/images/homeimg/vipbg.png"
            >
            </image>
          </view>
          <view
            class="barimgitem1"
            @click="jump('/pages/bundle/goods_seckill/goods_seckill')"
          >
            <image
              class="bgung"
              mode="widthFix"
              src="/static/images/homeimg/miaoshaumg.png"
            >
            </image>
          </view>
        </view>
        <!-- 资讯 -->
        <navigator
          v-if="news.length"
          class="information bg-white row"
          hover-class="none"
          open-type="navigate"
          url="/pages/news_list/news_list"
        >
          <!-- <image
            class="icon-toutiao"
            src="/static/images/icon_toutiao.png"
          ></image> -->
          <view class="gap-text">{{ $t("公告") }}</view>

          <text class="gap-line"></text>
          <view class="news flex1">
            <view class="shade"></view>
            <swiper
              autoplay="true"
              style="height: 76rpx; flex: 1"
              vertical="true"
              circular="true"
              :interval="3000"
            >
              <swiper-item
                v-for="(item, index) in news"
                :key="index"
                class="row"
              >
                <view class="flexnone">
                  <u-tag
                    v-if="item.is_new"
                    shape="circle"
                    :text="$t('最新')"
                    size="mini"
                    type="primary"
                    mode="plain"
                  />
                </view>
                <view class="text-swiper ml10 line1">{{ item.title }}</view>
              </swiper-item>
            </swiper>
          </view>
          <image class="icon-sm ml20" src="/static/images/arrow_right.png" />
        </navigator>
        <!-- 领卷 -->
        <swipers
          :is-swiper="false"
          :pid="23"
          height="170rpx"
          padding="20rpx 0 0"
        ></swipers>
        <!-- 图片 -->
        <view class="img001 mg mt20" v-if="false">
          <view class="one-item">
            <view
              class="img-padding img-padding2"
              @click="jump('/pages/sort/sortpage')"
            >
              <image
                class="img-o"
                mode="widthFix"
                src="/static/images/homeimg/bg1.png"
              />
              <view class="img-title">{{ $t("推荐好物") }}</view>
            </view>
            <view
              class="img-padding img-padding1"
              @click="jump('/pages/bundle/goods_seckill/goods_seckill')"
            >
              <image
                class="img-o"
                mode="widthFix"
                src="/static/images/homeimg/bg2.png"
              />
              <view class="img-title">{{ $t("秒杀专区") }}</view>
            </view>
          </view>
          <view class="one-item">
            <view
              class="img-padding img-padding1"
              @click="jump('/pages/sort/sortpage?category_id=4')"
            >
              <image
                class="img-o"
                mode="widthFix"
                src="/static/images/homeimg/bg3.png"
              />
              <view class="img-title black">{{ $t("电子五金") }}</view>
            </view>
            <view
              class="img-padding img-padding1"
              @click="jump('/pages/sort/sortpage?category_id=5')"
            >
              <image
                class="img-o"
                mode="widthFix"
                src="/static/images/homeimg/bg4.png"
              />
              <view class="img-title black">{{ $t("进口茶叶") }}</view>
            </view>
            <view
              class="img-padding img-padding1"
              @click="jump('/pages/sort/sortpage?category_id=6')"
            >
              <image
                class="img-o"
                mode="widthFix"
                src="/static/images/homeimg/bg5.png"
              />
              <view class="img-title black">{{ $t("生鲜食品") }}</view>
            </view>
          </view>
        </view>
        <!-- 图片end -->
        <!-- 活动专区 -->
        <!-- <view class="special-area mt20">
          <view>
            <scroll-view style="white-space: nowrap" :scroll-x="true">
              <navigator
                class="item bg-white"
                v-for="(item, index) in activityArea"
                :key="index"
                hover-class="none"
                :url="`/pages/bundle/activity_detail/activity_detail?id=${item.id}&name=${item.title}&title=${item.name}`"
              >
                <view class="column-center">
                  <custom-image
                    width="300rpx"
                    height="436rpx"
                    :src="item.image"
                  ></custom-image> -->
        <!-- <view class="title xxl">{{item.name}}</view>
									<view class="desc primary xxs line1">{{item.title}}</view> -->
        <!-- </view>
              </navigator>
            </scroll-view>
          </view>
        </view> -->
        <!-- 秒杀 -->
        <view class="seckill mt20" v-if="seckillGoods.length">
          <active-area
            type="seckill"
            progressBarColor="#FF2C3C"
            :lists="seckillGoods"
          >
            <navigator
              slot="header"
              hover-class="none"
              class="row activity-header white"
              open-type="navigate"
              url="/pages/bundle/goods_seckill/goods_seckill"
            >
              <view class="title xxl bold lightheight">{{
                $t("优惠专区")
              }}</view>
              <view class="row flex1">
                <text class="eee xs ml10 line1 padding12">{{
                  seckill.start_time
                }}</text>
                <view class="dec ml20 row-center mr20 countdown">
                  <u-count-down
                    :timestamp="remainTime"
                    separator-color="#fff"
                    color="#fff"
                    :separator-size="24"
                    :font-size="24"
                    bg-color="transparent"
                    border-color="#ff563c"
                  ></u-count-down>
                </view>
              </view>
              <view class="row xs">
                {{ $t("更多") }}
                <u-icon name="arrow-right" size="28"></u-icon>
              </view>
            </navigator>
          </active-area>
        </view>
        <!-- 热销 -->
        <view class="hot mt20" v-if="hotGoods.length && seting.hots">
          <active-area type="hot" progressBarColor="#9912FE" :lists="hotGoods">
            <navigator
              slot="header"
              hover-class="none"
              class="row activity-header"
              open-type="navigate"
              url="/pages/bundle/hot_list/hot_list"
            >
              <view class="title flex1">
                <view class="title2 xxl bold lightheight">{{
                  $t("热销榜单")
                }}</view>
              </view>
              <view class="row xs">
                {{ $t("更多") }}
                <u-icon name="arrow-right" size="28"></u-icon>
              </view>
            </navigator>
          </active-area>
        </view>
        <!-- 新品推荐 -->
        <view v-if="newGoods.length && seting.news" class="new-goods">
          <active-area type="news" progressBarColor="#9912FE" :lists="newGoods">
            <navigator
              slot="header"
              hover-class="none"
              class="row activity-header"
              open-type="navigate"
              url=""
            >
              <view class="title flex1">
                <image
                  class="title-image"
                  src="/static/images/new_title.png"
                ></image>
              </view>
              <!-- 	<view class="row xs">
								更多
								<u-icon name="arrow-right" size="28"></u-icon>
							</view> -->
            </navigator>
          </active-area>
        </view>
      </view>
      <!-- 好物优选 -->
      <view class="goods mt20" v-if="goodsList.length">
        <!-- <view class="goods-title row-center">
          <text class="line"></text>
          <view class="row">
            <image class="mr10 icon" src="/static/images/icon_like.png"></image>
            <text class="bold xxl">{{ $t("好物优选") }}</text>
          </view>
          <text class="line"></text>
        </view> -->
        <goods-list :list="goodsList"></goods-list>
        <loading-footer :status="status"></loading-footer>
      </view>
    </view>

    <u-popup class="coupons-popup" v-model="showCoupop" mode="center">
      <view class="wrap">
        <image
          class="coupon-bg"
          src="/static/images/home_coupon_bg.png"
        ></image>
        <scroll-view
          :scroll-y="true"
          style="height: 460rpx; margin-top: 300rpx"
        >
          <view
            class="item"
            v-for="(item, index) in couponPopList"
            :key="item.id"
          >
            <image class="img" src="/static/images/pop_bg_coupon.png"></image>
            <view class="row item-con">
              <view class="row-center price">
                <price-format
                  color="#FF2C3C"
                  :showSubscript="true"
                  :subscriptSize="32"
                  :first-size="56"
                  :second-size="40"
                  :price="item.money"
                />
              </view>
              <view class="ml20 mr20">
                <view class="bold md lighter">{{ item.name }}</view>
                <view class="xs muted mt10">{{ item.use_goods_type }}</view>
              </view>
            </view>
          </view>
        </scroll-view>
      </view>
      <view
        class="white row-center bg-primary lg btn br60"
        @click="showCoupop = false"
        >立即领取</view
      >
    </u-popup>
    <!-- #ifdef APP-PLUS -->
    <lyg-popup
      v-if="appConfig.app_agreement"
      title="服务协议和隐私政策"
      protocolPath="/pages/bundle/server_explan/server_explan?type=0"
      policyPath="/pages/bundle/server_explan/server_explan?type=1"
      policyStorageKey="has_read_privacy"
    >
    </lyg-popup>
    <!-- #endif -->
  </view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import { getHome, getMenu, getBestList } from "@/api/store";
import { loadingType } from "@/utils/type";
import {
  loadingFun,
  menuJump,
  arraySlice,
  setTabbar,
  getRect,
} from "@/utils/tools";
import { toLogin } from "@/utils/login";
import Cache from "@/utils/cache";
import { getRegisterCoupon } from "@/api/app";
const app = getApp();
export default {
  data() {
    return {
      navSwiperH: 374,
      logo: "",
      navHeight: 0,
      currentSwiper: 0,
      page: 1,
      status: loadingType.LOADING,
      remainTime: "",
      navBg: 0,
      navList: [],
      news: [],
      goodsList: [],
      seckill: {},
      seckillGoods: [],
      hotGoods: [],
      newGoods: [],
      activityArea: [],
      showCoupop: false,
      couponPopList: [],
      coupon: [],
      isDischarge: true,
      enable: true,
      isShowDownload: false,
      // 语言
      systemLocale: "",
      applicationLocale: "",
    };
  },
  async onLoad(options) {
    // 语言
    let systemInfo = uni.getSystemInfoSync();
    this.systemLocale = systemInfo.language;
    // zh-CN
    this.applicationLocale = uni.getLocale();
    this.isAndroid = systemInfo.platform.toLowerCase() === "android";
    uni.onLocaleChange((e) => {
      this.applicationLocale = e.locale;
    });

    this.headerAction = wx.createAnimation({
      delay: 0,
      duration: 100,
      timingFunction: "ease-in-out",
    });

    setTabbar(this);
    this.navHeight = app.globalData.navHeight;
    this.isDischarge = false;
    await this.getMenuFun();
    this.getBestListFun();
    console.log(this.appConfig);
    // #ifdef H5
    if (options && options.isapp == 1) {
      this.isShowDownload = true;
    }
    // #endif
  },
  async onShow() {
    this.$nextTick(function () {
      getRect(".index").then((res) => {
        if (res.top == 0) {
          this.navBg = 0;
        }
      });
    });

    // #ifdef H5
    this.enable = true;
    // #endif
    await this.getHomeFun();
    this.getCartNum();
    this.isLogin && this.getRegisterCouponFun();
  },
  onHide() {
    // #ifdef H5
    this.enable = false;
    // #endif
  },
  onReachBottom() {
    this.getBestListFun();
  },
  onPullDownRefresh() {
    this.getHomeFun();
    this.getMenuFun();
  },
  onShareAppMessage() {
    console.log(this.inviteCode);
    const shareInfo = Cache.get("shareInfo");
    return {
      title: shareInfo.mnp_share_title,
      path: "pages/index/index?invite_code=" + this.inviteCode,
    };
  },
  onPageScroll(e) {
    const top = uni.upx2px(100);
    const { scrollTop } = e;
    if (!this.enable) return;
    let percent = scrollTop / top;
    this.navBg = percent > 1 ? 1 : percent;
  },
  destroyed() {
    this.isDischarge = true;
  },
  methods: {
    ...mapActions(["getCartNum"]),
    jump(url) {
      uni.navigateTo({
        url: url,
        fail: (res) => {
          console.log(res);
        },
      });
    },
    onLocaleChange(e) {
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
      }
    },
    async getHomeFun() {
      const { code, data } = await getHome();
      uni.stopPullDownRefresh();
      if (code == 1) {
        const {
          coupon,
          news,
          seckill,
          host_goods,
          shop_logo,
          new_goods,
          activity_area,
        } = data;
        this.remainTime = Math.abs(
          seckill.end_time - Date.parse(new Date()) / 1000
        );
        this.logo = shop_logo;
        this.news = news;
        this.seckillGoods = seckill.goods;
        this.seckill = seckill;
        this.hotGoods = host_goods;
        this.coupon = coupon;
        this.newGoods = new_goods;
        this.activityArea = activity_area;
      }
    },
    async getMenuFun() {
      const { code, data } = await getMenu({
        type: 1,
      });
      uni.stopPullDownRefresh();
      if (code == 1) {
        //   截取数组
        if (data.length <= 5) {
          this.navSwiperH = 200;
        } else {
          this.navSwiperH = 374;
        }
        this.navList = arraySlice(data, [], 8);
      }
    },
    async getBestListFun() {
      let { page, goodsList, status } = this;
      const data = await loadingFun(getBestList, page, goodsList, status);
      if (!data) return;
      this.page = data.page;
      this.goodsList = data.dataList;
      this.status = data.status;
    },
    async tapMenu(item) {
      if (!this.isLogin) return toLogin();
      menuJump(item);
    },
    goPage(url) {
      if (!this.isLogin) return toLogin();
      uni.navigateTo({
        url,
      });
    },
    swiperChange(e) {
      this.currentSwiper = e.detail.current;
    },
    getRegisterCouponFun() {
      getRegisterCoupon().then((res) => {
        if (res.code == 1) {
          if (res.data && res.data.length) {
            this.showCoupop = true;
          }
          this.couponPopList = res.data;
        }
      });
    },
  },
  computed: {
    ...mapGetters(["cartNum", "inviteCode", "appConfig"]),
    locales() {
      return [
        {
          text: this.$t("locale.auto"),
          code: "auto",
        },
        {
          text: this.$t("locale.en"),
          code: "en",
        },
        {
          text: this.$t("locale.zh-hans"),
          code: "zh-Hans",
        },
        {
          text: this.$t("locale.zh-hant"),
          code: "zh-Hant",
        },
        {
          text: this.$t("locale.ja"),
          code: "ja",
        },
      ];
    },
    navBackground() {
      return this.seting.top_bg_image
        ? {
            "background-image": `url(${this.seting.top_bg_image})`,
          }
        : {};
    },
    showLogo() {
      return this.seting.logo;
    },
    navSearch() {
      if (this.showLogo === 0) return true;

      return this.navBg < 1 ? false : true;
    },
    seting() {
      const { index_setting } = this.appConfig;
      return index_setting;
    },
  },
};
</script>

<style lang="scss">
page {
  background-color: #f7a590;
}
// #ifdef H5
::v-deep .home-bg {
  background: url(../../static/images/bg_hometop.png) no-repeat;
  background-size: 100% auto;
}

// #endif
.home-bg {
  background: url(../../static/images/bg_hometop.png) no-repeat;
  background-size: 100% auto;
}

.index {
  background-size: 100% auto;

  .logo-wrap {
    position: absolute;

    .logo {
      width: auto;
      height: 52rpx;
    }
  }

  .header {
    .navigation-bar {
      padding-top: var(--status-bar-height);
      box-sizing: border-box;
    }
  }

  .contain {
    .main {
      position: relative;
      z-index: 9;
      padding: 0;

      .nav {
        position: relative;
        border-radius: 14rpx;

        .nav-item {
          width: 25%;
          margin-top: 30rpx;

          .nav-icon {
            width: 82rpx;
            height: 82rpx;
            margin-bottom: 15rpx;
          }
        }

        .dots {
          position: absolute;
          left: 50%;
          transform: translateX(-50%);
          bottom: 20rpx;
          display: flex;

          .dot {
            width: 10rpx;
            height: 6rpx;
            border-radius: 6rpx;
            margin-right: 10rpx;
            background-color: rgba(255, 44, 60, 0.4);

            &.active {
              width: 20rpx;
              background-color: $-color-primary;
            }
          }
        }
      }

      .information {
        height: 60rpx;
        box-shadow: 0px 0px 14px rgba(0, 0, 0, 0.06);
        padding: 0 20rpx;
        box-sizing: border-box;
        border-radius: 0;

        .news {
          position: relative;

          .shade {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 100;
          }
        }

        .icon-toutiao {
          width: 114rpx;
          height: 34rpx;
        }

        .gap-line {
          height: 28rpx;
          width: 1px;
          background-color: #dcdddc;
          margin: 0 30rpx;
        }
      }

      .special-area {
        .item {
          width: 300rpx;
          border-radius: 20rpx;
          display: inline-block;
          overflow: hidden;
          margin-right: 20rpx;

          .title {
            padding: 10rpx 0;
          }

          .desc {
            background-color: #fee9eb;
            padding: 6rpx 20rpx;
            border-radius: 60rpx;
            max-width: 260rpx;
          }
        }
      }

      .activity-header {
        height: 90rpx;
        padding: 0 20rpx;
      }

      .seckill {
        .dec {
          background-color: #000;
          width: 150rpx;
          height: 1.8rem;
        }
      }

      .hot,
      .new-goods {
        .title-image {
          width: 144rpx;
          height: 55rpx;
        }
      }
    }

    .goods {
      .goods-title {
        height: 100rpx;

        .line {
          width: 58rpx;
          height: 1px;
          background-color: #ccc;
          margin: 0 22rpx;
        }

        .icon {
          width: 38rpx;
          height: 38rpx;
        }
      }
    }
  }

  .coupon-pop-container {
    background-image: url(../../static/images/home_coupon_bg.png);
    width: 638rpx;
    height: 804rpx;
    background-size: 100% 100%;
    padding-top: 323rpx;

    .coupon-pop-lists {
      .coupon-pop-item {
        background-image: url(../../static/images/pop_bg_coupon.png);
        width: 488rpx;
        height: 150rpx;
        background-size: 100% 100%;
        margin-top: 20rpx;

        .coupon-left {
          width: 160rpx;
          height: 100%;
        }

        .coupon-right {
          padding-left: 30rpx;
          border-left: 1rpx dashed $-color-primary;
        }
      }
    }
  }

  .coupons-popup {
    .wrap {
      position: relative;
      width: 638rpx;
      height: 803rpx;
      overflow: hidden;

      .coupon-bg {
        position: absolute;
        top: 0;
        width: 100%;
        height: 100%;
      }

      .item {
        position: relative;
        width: 488rpx;
        height: 150rpx;
        margin: 0 auto 20rpx;
      }

      .img {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 0;
      }

      .item-con {
        z-index: 10;
        position: relative;
        padding: 20rpx 0;
        height: 100%;
        box-sizing: border-box;

        .price {
          width: 160rpx;
          border-right: 1px dashed $-color-primary;
          height: 100%;
        }
      }
    }

    .btn {
      width: 478rpx;
      height: 84rpx;
      margin: 20rpx auto;
      box-sizing: border-box;
      border: 3px solid #f8d07c;
    }
  }
}
.gap-text {
  color: #ff563c;
  border: solid 1px #ff563c;
  font-size: 12px;
  padding: 2px;
}
.text-swiper {
  font-size: 14px;
}

.lightheight {
  color: #ff563c;
}
.eee {
  color: #6e6e6e;
}
.countdown {
  border-radius: 8px;
}
.padding12 {
  padding-left: 12px;
}
.img001 {
  height: 480rpx;
  .one-item {
    padding: 0;
    display: flex;
    .img-o {
      width: 100%;
      min-height: 240rpx;
      max-height: 240rpx;
      overflow: hidden;
    }
    .img-padding {
      position: relative;
      height: 100%;
    }
    .img-title {
      position: absolute;
      top: 15px;
      left: 22px;
      font-size: 18px;
      color: #ff563c;
    }
    .img-padding2 {
      flex: 2;
    }
    .img-padding1 {
      flex: 1;
    }
  }
}
.black {
  color: #000 !important;
}
.barimg {
  background: #f36b47;
  display: flex;
  height: 240rpx;
  .barimgitem1 {
    flex: 1;
  }
  .barimgitem2 {
    flex: 2;
  }
}
.bgung {
  width: 100%;
  min-height: 100%;
  max-height: 100%;
}
.namecenter {
  text-align: center;
}
</style>
