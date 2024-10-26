<template>
  <view class="cate-one">
    <tabs
      :active="selectIndex"
      @change="changeShow"
      :config="{ itemWidth: 150 }"
      :height="positiontype == 'tabbar2' ? '0' : '80rpx'"
    >
      <tab v-for="(item, index) in cateList" :key="index" :title="item.name">
        <cate-list
          :positiontype="positiontype"
          v-if="item && item.isShow"
          :cate="item"
        ></cate-list>
      </tab>
    </tabs>
  </view>
</template>

<script>
export default {
  name: "cate-one",
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    positiontype: {
      type: String,
      default: "",
    },
    category_id: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      selectIndex: 0,
      cateList: [],
    };
  },
  methods: {
    changeShow(index) {
      this.selectIndex = index;
      this.cateList[this.selectIndex].isShow = true;
    },
  },
  mounted() {
    let _this = this;
    if (this.category_id) {
      uni.showLoading({});
      setTimeout(() => {
        if (_this.list && _this.list[0]) {
          // console.log('ok');
        } else {
          // reload
          uni.reLaunch();
        }
        _this.cateList = _this.list;
        let _has = false;
        _this.cateList.forEach((element, _index) => {
          if (element.id == _this.category_id) {
            _this.selectIndex = _index;
            _this.cateList[_index].isShow = true;
            _has = true;
          } else {
            _this.cateList[_index].isShow = false;
          }
        });
        if (_has == false) {
          _this.selectIndex = 0;
          _this.cateList[0].isShow = true;
        }
        uni.hideLoading();
      }, 1000);
    }
  },
  watch: {
    list: {
      immediate: true,
      handler(val) {
        if (this.category_id) {
          return;
        }
        let index = val.findIndex((item) => item.type == 1);
        this.selectIndex = index == -1 ? 0 : index;
        val.forEach(
          (item, index) =>
            (item.isShow = this.selectIndex == index ? true : false)
        );
        this.cateList = val;
      },
    },
  },
};
</script>

<style lang="scss">
</style>
