<template>
  <view class="goods-comment-list">
    <tabs :active="active" line-width="40" @change="changeActive">
      <tab :title="$t('待评价')">
        <comment-list type="1" v-if="active == 0"></comment-list>
      </tab>
      <tab :title="$t('已评价')">
        <comment-list type="2" v-if="active == 1"></comment-list>
      </tab>
    </tabs>
  </view>
</template>

<script>
export default {
  data() {
    return {
      active: 0,
    };
  },

  components: {},
  props: {},

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.type = options.type;

    if (this.type) {
      this.active = parseInt(this.type);
    }
  },
  onReady() {
    uni.setNavigationBarTitle({
      title: this.$t("评价商品"),
    });
  },

  methods: {
    changeActive(e) {
      this.active = e;
    },
  },
};
</script>
<style>
</style>
