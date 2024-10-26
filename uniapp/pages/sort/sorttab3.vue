<template>
  <view class="sort">
    <!-- <navigator
      class="header"
      hover-class="none"
      url="/pages/goods_search/goods_search"
    >
      <u-search
        :placeholder="$t('请输入关键字')"
        bg-color="#F4F4F4"
        :disabled="true"
      ></u-search>
    </navigator> -->
    <u-sticky
      offset-top="0"
      h5-nav-height="0"
      bg-color="transparent"
      :enable="true"
    >
      <u-navbar
        :border-bottom="false"
        :is-fixed="false"
        custom-class="home-bg"
        :background="navBackground"
        :is-back="false"
        :height="56"
      >
        <view class="flex1 row nav-search" v-show="true">
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
    <view class="content">
      <!-- <cate-one  v-if="appConfig.cate_style == 2" :list="cateList"></cate-one> -->
      <cate-two :list="cateList"></cate-two>
      <!-- <cate-three v-if="appConfig.cate_style == 4" :list="cateList"></cate-three> -->
      <!-- <cate-four v-if="appConfig.cate_style == 1" :list="cateList"></cate-four> -->
    </view>
  </view>
</template>

<script>
import { getCatrgory } from "@/api/store";
import { getRect, setTabbar } from "@/utils/tools";
import { mapGetters, mapActions } from "vuex";
import Cache from "@/utils/cache";
export default {
  data() {
    return {
      cateList: [],
    };
  },

  components: {},
  onLoad(options) {
    setTabbar(this);
    this.getCatrgoryFun();
  },
  onShow() {
    this.getCartNum();
  },
  onShareAppMessage() {
    const shareInfo = Cache.get("shareInfo");
    return {
      title: shareInfo.mnp_share_title,
      path: "pages/index/index?invite_code=" + this.inviteCode,
    };
  },
  methods: {
    ...mapActions(["getCartNum"]),
    getCatrgoryFun() {
      getCatrgory().then((res) => {
        if (res.code == 1) {
          this.cateList = res.data;
        }
      });
    },
  },
  computed: {
    ...mapGetters(["cartNum", "inviteCode", "appConfig"]),
    navBackground() {
      return this.seting.top_bg_image
        ? {
            "background-image": `url(${this.seting.top_bg_image})`,
          }
        : {};
    },
    seting() {
      const { index_setting } = this.appConfig;
      return index_setting;
    },
  },
};
</script>
<style lang="scss">
$header-height: 94rpx;
$nav-height: 80rpx;
page {
  .sort {
    .header {
      box-sizing: border-box;
      height: $header-height;
      border-bottom: $-solid-border;

      .search {
        flex: 1;
        height: 60rpx;

        input {
          flex: 1;
          height: 100%;
        }
      }
    }
    .content {
      height: calc(
        100vh - #{$header-height} - var(--window-top) - var(--window-bottom)
      );
    }
  }
}
</style>
