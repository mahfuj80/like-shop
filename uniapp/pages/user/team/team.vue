<template>
  <view class="body">
    <view v-if="config" class="d-container wrapper">
      <image
        class="person-wx-signday"
        src="/static/images/team-bg.png"
        mode="scaleToFill"
      ></image>
      <view class="box">
        <view class="flex">
          <view class="item">
            <view class="title fw">{{ $t("直推有效") }}</view>
            <view class="num fw">{{ config.直推有效 }}</view>
          </view>
          <view class="item">
            <view class="title fw">{{ $t("团队总人数") }}</view>
            <view class="num fw">{{ config.团队总人数 }}</view>
          </view>
        </view>
      </view>
    </view>

    <view class="tabbar">
      <tui-tabs
        :tabs="tabs"
        backgroundColor="f6f6f6"
        :currentTab="currentTab"
        itemWidth="50%"
        :padding="0"
        @change="change"
        sliderBgColor="#5677fc"
        selectedColor="#5677fc"
        :sliderWidth="120"
      ></tui-tabs>
    </view>

    <!-- 2个tab -->
    <!-- 2个tabend -->
    <!-- 搜索框 -->
    <input
      class="input_text"
      type="text"
      @confirm="handleEnterKey"
      v-model="key_words"
      :placeholder="$t('输入你想搜索的昵称或者手机号')"
    />
    <view class="userbody">
      <view v-for="(it, index) in list" class="item">
        <image class="img" :src="it.avatar" mode="aspectFill"></image>
        <view class="name">
          <text class="nameline"> {{ it.nickname }} </text>
          <text class="nameline"> {{ it.mobile }} </text>
          <text
            class="phone"
            @click="
              jump(
                '/pages/bundle/balance_transfer/balance_transfer?uid=' + it.id + '&mobile=' + it.mobile
              )
            "
          >
            {{ $t("转赠积分") }}
          </text>
        </view>
      </view>
      <view class="none" v-if="show"> {{ $t("没有了") }} </view>
    </view>
    <!-- 搜索框end -->
  </view>
</template>
<script>
import tuiButton from "@/components/thorui/tui-button/tui-button.vue";
import tuiTabs from "@/components/thorui/tui-tabs/tui-tabs.vue";
import { teamCount, teamList } from "@/api/user";
export default {
  components: {
    tuiButton,
    tuiTabs,
  },
  data() {
    return {
      config: null,
      currentTab: 0,
      page: 1,
      list: [],
      key_words: "",
      show: false,
    };
  },
  computed: {
    tabs() {
      return [
        {
          name: this.$t("直推有效"),
        },
        {
          name: this.$t("间推有效"),
        },
      ];
    },
  },
  onLoad(e) {
    this.getCount();
    this.getList();
  },
  methods: {
    jump(url) {
      uni.navigateTo({
        url: url,
      });
    },
    handleEnterKey() {
      this.getList(true);
    },
    change(e) {
      this.currentTab = e.index;
      this.getList(true);
    },

    getCount() {
      //好友详情
      teamCount().then((res) => {
        if (res.code == 1) {
          this.config = res.data;
          console.log("this.config", this.config);
        }
      });
    },
    getList(reflash) {
      let _this = this;
      if (reflash) {
        this.page = 1;
        this.list = [];
      }
      //好友详情
      teamList({
        type: _this.currentTab == 0 ? "yiji" : "erji",
        key_words: _this.key_words,
        page: _this.page,
      }).then((res) => {
        console.log("res", res);
        if (res.code == 1 && res.data.data.length > 0) {
          _this.page = _this.page + 1;
          if (reflash) {
            _this.list = res.data.data;
          } else {
            _this.list = _this.list.concat(res.data.data);
          }
        } else {
          _this.show = true;
        }
      });
    },
  },
  onReachBottom() {
    this.getList(false);
  },
  onShow() {},
  onReady() {
    uni.setNavigationBarTitle({
      title: this.$t("团队"),
    });
  },
};
</script>
<style>
page {
  background-color: #f6f6f6;
}
</style>
<style scoped>
.tabbar {
  margin-top: 22px;
  margin-bottom: 22px;
}
page {
  background-color: #f6f6f6;
}
.d-container {
  position: relative;
  height: 180rpx;
  /* background: linear-gradient(135deg, #617cf3, #5777fc); */
  margin-left: 12px;
  margin-right: 12px;
  border-radius: 10px;
  /* box-shadow: 1px 2px 2px 2px #5777fc; */
}
.person-wx-signday {
  position: absolute;
  top: 20rpx;
  left: 0;
  width: 100%;
  height: 200rpx;
}
.box {
  display: block;
  width: 98.9999%;
  position: absolute;
  top: 22rpx;
  left: 0.3955%;
}
.fw {
  color: #fff !important;
}
.title-line {
  display: flex;
  height: 40rpx;
  line-height: 40rpx;
  padding-top: 16rpx;
  padding-bottom: 16rpx;
}
.inbox {
  height: 162rpx;
  background-color: #fff;
}
.title-left,
.title-right {
  flex: 1;
  color: #fff;
  font-size: 16px;
}
.title-left {
  padding-left: 12px;
}

.title-right {
  text-align: right;
  font-size: 12px;
  padding-right: 12px;
}
.flex {
  display: flex;
  flex-direction: row;
  text-align: center;
}
.flex .item {
  flex: 1;
  padding-left: 12px;
  padding-right: 12px;
  text-align: center;
}
.flex .title,
.flex .num {
  display: inline-block;
}
.flex .title {
  display: block;
  font-size: 14px;
  padding-top: 20px;
}
.flex .num {
  display: block;
  padding-top: 12px;
  padding-left: 6px;
  font-size: 14px;
  color: red;
  font-weight: bold;
}
.line-title {
  display: block;
  text-align: center;
  font-size: 20px;
  padding-top: 22px;
}
.body2 {
  background-color: #e1e1e1;
  margin-left: 12px;
  margin-right: 12px;
  margin-top: 12px;
  padding-left: 12px;
  padding-right: 12px;
  padding-top: 6px;
  padding-bottom: 24px;
  border-radius: 15px;
}
.input_text {
  border: solid 1px #e1e1e1;
  padding: 12px;
  margin: 12px;
  background-color: #e1e1e1;
  border-radius: 26px;
}
.userbody .img {
  display: inline-block;
  width: 50px;
  height: 50px;
  padding-left: 24px;
  padding-top: 6px;
  padding-right: 12px;
  vertical-align: middle;
}
.userbody .item {
  padding-bottom: 6px;
  border-bottom: solid 4px #f6f6f6;
  position: relative;
  background: #fff;
  border-radius: 12px;
  margin-left: 12px;
  margin-right: 12px;
  height: 180rpx;
  padding-top: 12px;
  padding-bottom: 12px;
}
.userbody .name {
  display: inline-block;
  vertical-align: middle;
  font-size: 14px;
}

.none {
  display: block;
  text-align: center;
  padding-top: 12px;
  color: #ababab;
}
.id {
  font-size: 14px;
  color: #000;
}
.id2 {
  font-size: 11px;
  color: #bbbbbb;
  display: block;
}
.phone {
  display: inline-block;
  position: absolute;
  top: 32px;
  right: 12px;
  font-size: 14px;
}
.vip {
  color: #5777fc;
}

.vipicon {
  display: inline-block;
  vertical-align: initial;
  height: 12px;
  width: 80rpx;
  margin-left: 6px;
}
.rb {
  border-right: solid 1px #5777fc;
}
.nameline {
  display: block;
}
.phone {
  background: #f5303c;
  color: #fff;
  border-radius: 5px;
  font-size: 12px;
  padding: 6px;
}
</style>
