<template>
  <scroll-view
    scroll-y="true"
    :class="[positiontype == 'tabbar2' ? 'cate-scroll2' : 'cate-scroll']"
    @scrolltolower="getGoodsSearchFun"
  >
    <view class="cate-list">
      <swipers
        v-if="positiontype != 'tabbar2'"
        :pid="4"
        height="267rpx"
        previous-margin="0"
        padding="20rpx 20rpx 0"
        radius="10rpx"
      ></swipers>

      <!-- 图片 -->
      <view class="img001 mg mt20" v-if="positiontype == 'tabbar2'">
        <view class="one-item">
          <view class="img-padding img-padding4" @tap="changeCate({ id: 4 })">
            <image
              class="img-o"
              mode="widthFix"
              src="/static/images/homeimg/bg6.png"
            />
            <view class="img-title">{{ $t("推荐好物") }}</view>
          </view>
          <view class="img-padding img-padding6">
            <view class="one-item one-item2">
              <view
                class="img-padding img-padding-col"
                @tap="changeCate({ id: 5 })"
              >
                <image
                  class="img-o2"
                  mode="widthFix"
                  src="/static/images/homeimg/bg7.png"
                />
                <view class="img-title">{{ $t("特卖专区") }}</view>
              </view>
              <view
                class="img-padding img-padding-col"
                @tap="changeCate({ id: 4 })"
              >
                <image
                  class="img-o2"
                  mode="widthFix"
                  src="/static/images/homeimg/bg8.png"
                />
                <view class="img-title">{{ $t("推荐好物") }}</view>
              </view>
            </view>
          </view>
        </view>
      </view>
      <!-- 图片end -->
      <view
        class="cate-nav bg-white"
        v-if="
          navList.length &&
          (type == 2 || cate.type == 0) &&
          positiontype != 'tabbar2'
        "
      >
        <swiper :style="'height:' + navSwiperH + 'rpx;'" @change="swiperChange">
          <swiper-item v-for="(items, index) in navList" :key="index">
            <view class="nav-list row wrap">
              <view
                v-for="(item, index2) in items"
                :key="index2"
                @tap="changeCate(item)"
                class="nav-item column-center"
              >
                <view class="icon-wrap" :class="{ active: id == item.id }">
                  <u-image
                    width="82rpx"
                    height="82rpx"
                    border-radius="50%"
                    :src="item.image"
                  ></u-image>
                </view>
                <view
                  style="width: 90%"
                  class="name xs line1 u-text-center"
                  :class="{ primary: id == item.id }"
                  >{{ item.name }}</view
                >
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

      <view v-if="cate.type !== 0">
        <view
          v-if="positiontype != 'tabbar2'"
          class="row condition bg-white mt20"
        >
          <view
            :class="'tag row-center ' + (comprehensive ? 'primary' : '')"
            @tap="onNormal"
            >{{ $t('综合') }}</view
          >
          <view class="tag row-center" data-type="priceSort" @tap="onPriceSort">
            <text :class="priceSort ? 'primary' : ''">{{ $t('价格') }}</text>
            <view>
              <trigonometry
                direction="up"
                size="small"
                :color="priceSort == 'desc' ? '#FF5058' : '#333'"
              >
              </trigonometry>
              <trigonometry
                size="small"
                :color="priceSort == 'asc' ? '#FF5058' : '#333'"
              ></trigonometry>
            </view>
          </view>
          <view class="tag row-center" data-type="saleSort" @tap="onSaleSort">
            <text :class="saleSort ? 'primary' : ''">{{ $t('销量') }}</text>
            <view>
              <trigonometry
                direction="up"
                size="small"
                :color="saleSort == 'desc' ? '#FF5058' : '#333'"
              >
              </trigonometry>
              <trigonometry
                size="small"
                :color="saleSort == 'asc' ? '#FF5058' : '#333'"
              ></trigonometry>
            </view>
          </view>
          <view class="tag row-center" @tap="changeType">
            <image
              class="icon-sm"
              :src="
                goodsType === 'one'
                  ? '/static/images/icon_double.png'
                  : '/static/images/icon_one.png'
              "
            >
            </image>
          </view>
        </view>
        <view class="goods">
          <view class="goods-list">
            <view v-show="goodsType == 'double'" class="double">
              <goods-list type="double" :list="goodsList"></goods-list>
            </view>
            <view
              v-show="goodsType == 'one'"
              class="one"
              style="padding: 0 20rpx"
            >
              <goods-list :list="goodsList" type="one"></goods-list>
            </view>
          </view>
          <loading-footer :status="status" :slot-empty="true">
            <view slot="empty" class="column-center" style="padding: 200rpx 0">
              <image
                class="img-null"
                src="/static/images/goods_null.png"
              ></image>
              <text class="lighter">{{ $t('暂无商品') }}</text>
            </view>
          </loading-footer>
        </view>
      </view>
    </view>
  </scroll-view>
</template>


<script>
import { mapGetters, mapActions } from "vuex";
import { arraySlice, trottle, loadingFun } from "@/utils/tools";
import { getGoodsSearch } from "@/api/store";
import { loadingType } from "@/utils/type";
export default {
  props: {
    cate: {
      type: Object,
      default: () => ({}),
    },
    type: {
      type: Number,
      default: 1,
    },
    // 位置
    positiontype: {
      type: String,
      default: "",
    },
    is_vip: {
      type: Number,
      default: -1,
    },
  },
  data() {
    return {
      navSwiperH: 374,
      navList: [],
      currentSwiper: 0,
      status: loadingType.LOADING,
      page: 1,
      goodsType: "double",
      goodsList: [],
      priceSort: "",
      saleSort: "",
      id: "",
      catid: '',
    };
  },

  created() {
    this.onNormal = trottle(this.onNormal, 500, this);
    this.onPriceSort = trottle(this.onPriceSort, 500, this);
    this.onSaleSort = trottle(this.onSaleSort, 500, this);
    this.getGoodsSearchFun();
  },
  watch: {
    cate: {
      immediate: true,
      handler(val) {
        let navList = [];
        if (val.type === 0) {
          navList = val.sons[0].sons || [];
        } else {
          navList = val.sons || [];
        }
        if (navList.length <= 5) {
          this.navSwiperH = 200;
        } else {
          this.navSwiperH = 374;
        }
        this.id = val.id;
        this.navList = arraySlice(navList);
      },
    },
  },
  computed: {
    ...mapGetters(["appConfig"]),
    comprehensive() {
      const { priceSort, saleSort } = this;
      if (priceSort == "" && saleSort == "") {
        return true;
      }

      return false;
    },
  },
  methods: {
    changeCate(item) {
      if (item && item.id) {
        if (this.catid == item.id) {
          return
        }
        this.catid = item.id
      }
      console.log('tuijian', item);
      if (this.cate.type === 0) {
        uni.navigateTo({
          url: `/pages/goods_search/goods_search?id=${item.id}&name=${item.name}&type=${item.type}`,
        });
        return;
      }

      if (this.id == item.id) {
        this.id = this.cate.id;
        this.onRefresh();
        return;
      }
      this.id = item.id;
      this.onRefresh();
    },
    swiperChange(e) {
      this.currentSwiper = e.detail.current;
    },
    changeType() {
      this.goodsType = this.goodsType === "one" ? "double" : "one";
    },

    onNormal() {
      this.priceSort = "";
      this.saleSort = "";
      this.onRefresh();
    },

    onPriceSort() {
      let { priceSort } = this;
      this.saleSort = "";
      this.priceSort = priceSort == "asc" ? "desc" : "asc";
      this.onRefresh();
    },

    onSaleSort() {
      let { saleSort } = this;
      this.priceSort = "";
      this.saleSort = saleSort == "desc" ? "asc" : "desc";
      this.onRefresh();
    },

    onRefresh() {
      this.showHistory = false;
      this.page = 1;
      this.goodsList = [];
      this.status = loadingType.LOADING;
      this.$nextTick(() => {
        this.getGoodsSearchFun();
      });
    },

    async getGoodsSearchFun() {
      let { page, goodsList, priceSort, saleSort, status } = this;
      if (status == loadingType.FINISHED) return;
      const params = {
        category_id: this.id,
        is_vip: this.is_vip,
        page_no: page,
        price: priceSort,
        sales_sum: saleSort,
      };
      const data = await loadingFun(
        getGoodsSearch,
        page,
        goodsList,
        status,
        params
      );
      if (!data) return;
      this.page = data.page;
      this.goodsList = data.dataList;
      this.status = data.status;
    },
  },
};
</script>
<style lang="scss">
.cate-scroll {
  height: calc(100vh - 174rpx - var(--window-bottom));
}
.cate-scroll2 {
  height: calc(100vh - 174rpx - var(--window-bottom) + 70rpx);
}
.cate-list {
  .cate-nav {
    position: relative;
    border-radius: 20rpx;
    margin: 20rpx 20rpx 0;

    .nav-item {
      width: 20%;
      margin-top: 30rpx;
      .icon-wrap {
        border: 1px solid transparent;
        border-radius: 50%;
        &.active {
          border-color: $-color-primary;
        }
      }
      .name {
        margin-top: 14rpx;
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

  .condition {
    height: 80rpx;
    margin: 20rpx 20rpx 0;
    border-radius: 10rpx;

    .tag {
      flex: 1;
    }
  }
}

.img001 {
  height: 480rpx;
  // #ifdef APP-PLUS
  // #endif
  .one-item {
    padding: 0;
    display: flex;
    .img-o {
      width: 100%;
      min-height: 480rpx;
      max-height: 480rpx;
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
    .img-padding4 {
      flex: 4;
    }
    .img-padding6 {
      flex: 6;
    }
    .img-padding1 {
      flex: 1;
    }
  }
  .one-item2 {
    display: flex;
    flex-direction: column;
  }
  .img-padding-col {
    height: 240rpx !important;
    margin-bottom: 1px;
  }
  .img-o2 {
    width: 100%;
    min-height: 240rpx;
    max-height: 240rpx;
    overflow: hidden;
  }
}
.black {
  color: #000 !important;
}
</style>
