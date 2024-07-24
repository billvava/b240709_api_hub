<template>
	<view v-if="data">
		<view class="wrap">
			<view class="title">{{data.info.title}}</view>
			<view class="article-time">发布时间：{{data.info.time}}</view>

			<rich-text class="article-con" :nodes="data.info.html"></rich-text>

		</view>
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				data: null
			}
		},
		onLoad: function(options) {

			var t = this;
			t.util.ajax('/comapi/help/item', {
				id: options.id
			}, function(res) {
				t.setData({
					data: res.data
				})
				uni.setNavigationBarTitle({
					title:res.data.info.title
				})
			})
		},
		onShareAppMessage: function() {
			return t.util.share();
		},
		methods: {

		}
	}
</script>

<style>
	page {
		background: #fff;
	}

	.wrap {
		padding: 30rpx;
	}

	.title {
		margin-bottom: 30rpx;
		font-size: 16px;
	}

	.article-time {
		margin-bottom: 30rpx;
		font-size: 12px;
		color: #999;
	}

	.article-btm {
		width: 100%;
		background: #fff;
		border-top: 1px #e6e6e6 solid;
		position: fixed;
		bottom: 0;
		left: 0;
		z-index: 9;
	}

	.article-btm-inner {
		height: 90rpx;
		padding: 0 30rpx;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.article-margin {
		height: 90rpx;
		box-sizing: content-box;
	}

	.share-btn {
		width: 160rpx;
		height: 60rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 50rpx;
		background: #4b818a;
		font-size: 12px;
		color: #fff;
	}

	.read {
		font-size: 12px;
		color: #999;
	}

	.link {
		font-size: 12px;
		color: #4b818a;
	}
</style>
