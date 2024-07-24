<template>
	<view v-if="data">
		<view class="grid">
			<view class="myleast">
				<image :src="cdn3+'chongzhi.jpg'" class="chongzhi-bg"></image>
				<view class="myleast-box">
					<view class="myleast-title">我的余额</view>
					<view class="myleast-bar">
						<view class="myleast-left">
							<view class="myleast-num">￥{{data.finance.money}}</view>
							<view class="myleast-p">账户余额</view>
						</view>
						<navigator url="/pages/user/log/log?flag=money" class="myleast-btn">
							<view class="myleast-btn-p">收支明细</view>
							<image :src="cdn3+'ra_06.png'" class="ra"></image>
						</navigator>
					</view>
				</view>
			</view>

			<view class="quick">
				<view class="chongzhi-title">充值套餐</view>
				<view class="quick-list">

					 <view :class=" (0==id)?'quick-item on' :'quick-item'" data-id="0" data-money="0"
						@click="change_cate">
						自定义</view> 

					<view v-for="(item, index) in data.list" :key="index" :class="(item.id==id) ? 'quick-item on' :'quick-item'"
						:data-id="item.id" :data-money="item.money" @click="change_cate">{{item.name}}</view>

				</view>
			</view>
			<!-- <view class="pay-way">
			  <view class="pay-title">支付方式</view>
			  <view class="pay-item">
			    <image src="{{cdn3}}wechat.png" class="wechat-icon"></image>
			    <view class="pay-p">微信支付</view>
			  </view>
			</view> -->

			 <view class="chongzhi" v-if="id==0">
				<view class="chongzhi-title">充值金额</view>
				<input type="number" maxlength="7" @input="inputedit" placeholder="请输入充值金额" class="chongzhi-txt"
					:value="money" />
			</view> 
			<view class="chongzhi" v-if="data.show_code==1">
				<view class="chongzhi-title">邀请码</view>
				<input type="number"  placeholder="请输入邀请码" class="chongzhi-txt" v-model="code" />
			</view>
			
		</view>

		<view class="pay-btn-wrap" :style="{'padding-bottom':''+buBottom+'rpx'}">
			<view class="pay-btn">
				<view class="pay-btn1" @click="pay">立即支付 ￥{{(money)?money:0}}</view>
			</view>
		</view>

		<view class="pay-margin" :style="{'padding-bottom':''+buBottom+'rpx'}"></view>
	</view>
</template>
<script src="./recharge.js">
	
</script>

<style lang="scss">
	page {
	  background-color: #f2f2f2;
	}
	
	.grid {
	  padding: 30rpx;
	  margin-bottom: 20rpx;
	  background-color: #fff;
	}
	
	.myleast {
	  width: 100%;
	  height: 337rpx;
	  position: relative;
	}
	
	.chongzhi-bg {
	  height: 337rpx;
	  width: 690rpx;
	}
	
	.myleast-box {
	  width: 100%;
	  height: 337rpx;
	  padding: 30rpx;
	  position: absolute;
	  top: 0;
	  left: 0;
	  z-index: 9;
	}
	
	.myleast-title {
	  margin-bottom: 70rpx;
	  font-size: 28rpx;
	  color: #cc9520;
	}
	
	.myleast-bar {
	  display: flex;
	  justify-content: space-between;
	  align-items: center;
	}
	
	.myleast-num {
	  font-size: 62rpx;
	  color: #cc9520;
	}
	
	.myleast-p {
	  font-size: 24rpx;
	  color: #cc9520;
	}
	
	.myleast-btn {
	  display: flex;
	  align-items: center;
	  font-size: 28rpx;
	  color: #cc9520;
	}
	
	.ra {
	  width: 14rpx;
	  height: 24rpx;
	  margin-left: 10rpx;
	}
	
	.chongzhi {
	  margin-bottom: 30rpx;
	}
	
	.chongzhi-title {
	  margin-bottom: 10rpx;
	  font-size: 24rpx;
	  color: #333;
	}
	
	.chongzhi-txt {
	  width: 100%;
	  height: 80rpx;
	  padding: 0 20rpx;
	  box-sizing: border-box;
	  border-radius: 10rpx;
	  background-color: #f2f2f2;
	  font-size: 28rpx;
	}
	
	.quick-list {
	  display: flex;
	  justify-content: space-between;
	  flex-wrap: wrap;
	}
	
	.quick-list:after {
	  display: block;
	  content: '';
	  width: 200rpx;
	}
	
	.quick-item {
	  width: 220rpx;
	  height: 70rpx;
	  margin-bottom: 20rpx;
	  border: 1px #e6e6e6 solid;
	  border-radius: 5px;
	  text-align: center;
	  line-height: 70rpx;
	  font-size: 32rpx;
	  color: #333;
	}
	
	.quick-item.on {
	  background-color: $themeColor;
	  border: 1px $themeColor solid;
	  color: #fff;
	}
	
	.pay-way {
	  padding: 30rpx;
	  border-bottom: 1px #e6e6e6 solid;
	  background-color: #fff;
	}
	
	.pay-title {
	  margin-bottom: 30rpx;
	  font-size: 28rpx;
	  color: #333;
	}
	
	.pay-item {
	  display: flex;
	  align-items: center;
	  font-size: 30rpx;
	  color: #333;
	}
	
	.wechat-icon {
	  width: 50rpx;
	  height: 50rpx;
	  margin-right: 20rpx;
	}
	
	.info {
	  padding: 30rpx;
	  margin-bottom: 20rpx;
	  background-color: #fff;
	}
	
	.title {
	  margin-bottom: 30rpx;
	  font-size: 28rpx;
	  color: #333;
	  font-weight: bold;
	}
	
	.pay-btn-wrap {
	  width: 100%;
	  border-top: 1px #e6e6e6 solid;
	  box-shadow: 0 -10rpx 10rpx rgba(0,0,0,.1);
	  background-color: #fff;
	  position: fixed;
	  bottom: 0;
	  left: 0;
	  z-index: 20;
	}
	
	.pay-margin {
	  height: 120rpx;
	  box-sizing: content-box;
	}
	
	.pay-btn {
	  padding: 0 30rpx;
	  height: 120rpx;
	  display: flex;
	  justify-content: center;
	  align-items: center;
	}
	
	.pay-btn1 {
	  flex: 1;
	  height: 100rpx;
	  border-radius: 50rpx;
	  background-color: $themeColor;
	  text-align: center;
	  line-height: 100rpx;
	  font-size: 32rpx;
	  color: #FFFFFF;
	  font-weight: bold;
	}
</style>
