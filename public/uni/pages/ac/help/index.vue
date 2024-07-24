<template>
  <view>
    <view class="bg"></view>
    <view class="wrapper">
      <view class="serve">
        <image class="" :src="com_cdn+'help/serve.png'"></image>
        <view class="serve_info">
          <view class="name">联系客服</view>
          <view class="dec">有问题 找客服解答</view>
          <view class="btn" style="position: relative">立即咨询
            <button open-type="contact" class="kefu" style="width:100%">联系客服</button>
          </view>
        </view>
      </view>

      <!-- 常见问题 -->
      <view class="issues">
        <view class="title">常见问题</view>
        <navigator class="item" v-for="(item,index) in list" :url="'./item?id='+item.id">
          <view class="name">{{item.name}}</view>
          <image class="" :src="com_cdn+'help/ra.png'"></image>
        </navigator>
        <msg v-if="list.length <= 0" />
      </view>

    </view>


  </view>
</template>

<script>
export default {
  data() {
    return {
      com_cdn: getApp().globalData.com_cdn,
      is_scroll: false,

      list: [],
      is_load: 1,
      page: 1,
      load_other: 1,
      data: null,
      cate_token: '',
    };
  },
  onLoad(op) {
    var t = this;
    if (op.cate_token) {
      t.cate_token = op.cate_token;
    }
    t.search();
  },
  methods: {
    change_show: function(index) {
      var t = this;
      t.list[index].show = !t.list[index].show;
    },
    onPageScroll(e) {
      if (e.scrollTop > 0) {
        this.is_scroll = true
      } else {
        this.is_scroll = false
      }
    },

    search: function() {
      var t = this;
      t.page = 1;
      t.list = [];
      t.is_load = 1;
      t.load_data();
    },
    load_data() {
      var t = this;
      if (t.is_load == 0) {
        return;
      }
      t.util.ajax('/comapi/Help/load_list', {
        page: t.page,
        cate_token: t.cate_token,
      }, function(res) {
        if (t.load_other == 1) {
          t.data = res.data;
          t.load_other = 0;
        }
        if (res.data.count <= 0) {
          t.is_load = 0
        } else {
          t.list = t.list.concat(res.data.list);
          t.page = t.page + 1;
        }

      });
    },
  }
}
</script>

<style lang="scss">
.kefu {
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
}

page {
  background-color: #fff;
}
.bg {
  height: 366rpx;
  background: $themeColor;
}
.wrapper {
  padding: 20rpx;
  margin-top: -180rpx;
  position: relative;
}
.serve {
  padding: 50rpx;
  margin-bottom: 50rpx;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-radius: 20rpx;
  background-color: #fff;
  box-shadow: 0 10rpx 10rpx rgba(0, 0, 0, 0.1);
}
.serve image {
  width: 220rpx;
  height: 220rpx;
}
.serve .serve_info {
  width: 300rpx;
  text-align: center;
}
.serve .serve_info .name {
  font-size: 42rpx;
  font-weight: bold;
}
.serve .serve_info .dec {
  margin: 20rpx 0;
  font-size: 30rpx;
  color: #999;
}
.serve .serve_info .btn {
  width: 100%;
  height: 78rpx;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50rpx;
  background: $themeColor;
  font-size: 34rpx;
  font-weight: bold;
  color: #fff;
}
.issues .title {
  margin-bottom: 20rpx;
  font-weight: bold;
  font-size: 36rpx;
}
.issues .item {
  height: 100rpx;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1PX #e6e6e6 solid;
}
.issues .item .name {
  font-size: 28rpx;
}
.issues .item image {
  width: 12rpx;
  height: 20rpx;
}


.kefu {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
}

</style>
