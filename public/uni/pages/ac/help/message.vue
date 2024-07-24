<template>

	<view>

		<view class="recommend">
			<input type="number" v-model="tel" placeholder="联系手机" class="shouji" />
			<textarea placeholder="评价内容"  v-model="content" class="sh-in"></textarea>
			<view class="up-list">

				<view class="up-item" v-for="(img_item, img_index) in images">
					<image :src="img_item" class="up-image" width="180" height="180"></image>
					<view class="up-del" @tap.stop="del_img" :data-index="img_index">删除</view>
				</view>

				<view class="up-item">
					<image :src="cdn+'shagn-chuan.png'" @click="up_img" class="up-icon" width="180" height="180">
					</image>
				</view>
			</view>

		</view>
		<view class="recommend">

			<view url="#" @click="sub" class="shiming-btn">提交</view>
		</view>
	</view>
</template>

<script>
	const app = getApp();
	var app_images = [];
	// #ifdef H5
	window.set_img = function(url) {
		app_images.push(url);
	}
	// #endif
	export default {
		data() {
			return {
				cdn: app.globalData.com_cdn,
				is_anonymous: 1,
				data: null,
				images: [],
				star: 5,
				content: '',
				item: null,
				id: 0,
				tel:''
			};
		},

		components: {},
		props: {},

		/**
		 * 生命周期函数--监听页面加载
		 */
		onLoad: function(options) {
			var t = this;
			


		},
		methods: {
			sub: function() {
				var t = this;
				t.util.ajax('/comapi/help/message', {
					id: t.id,
					imgs: t.images,
					content: t.content,
					tel: t.tel,
					
				}, function(res) {

					uni.redirectTo({
						url: '/pages/user/index/index'
					})

				});
			},
			get_images() {
				var t = this;
				console.log(app_images);
				if (app_images.length > 0) {
					t.images = app_images;
				} else {
					setTimeout(t.get_images, 100);
				}
			},
			up_img() {
				var t = this;
				if (window) {
					if (window.jsInterface) {
						t.get_images();
						window.jsInterface.takePicImg();
					} else if (window.webkit) {
						t.get_images();
						window.webkit.messageHandlers.iOSMessageHandler.postMessage(
							'{"method":"takePicImg","data":{"callback":"set_img"}}');
					} else {
						t.upload_img();
					}
				} else {
					t.upload_img();
				}
			},
			upload_img: function() {
				var t = this;
				if (t.images.length >= 5) {
					t.util.show_msg('最多5张');
					return;
				}
				t.util.upload_img(function(res) {
					t.images.push(res.url);
				})
			},
			del_img: function(e) {
				var t = this;
				var d = e.currentTarget.dataset;

				t.images.splice(d.index, 1);


			},
			change_star: function(e) {
				var t = this;
				var d = e.currentTarget.dataset;

				t.setData({
					star: d.star
				});
			},
		}
	};
</script>
<style lang="scss">
	.shouji {
		width: 100%;
		height: 70rpx;
		line-height: 70rpx;

		margin-bottom: 30rpx;

		border-radius: 6rpx;
		border: 1px #e6e6e6 solid;
		box-sizing: border-box;
		font-size: 28rpx;
		padding-left: 20rpx;
	}

	page {
		background: #fff;
	}

	.sh-item {
		padding: 30rpx;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.sh-img {
		width: 90rpx;
		height: 90rpx;
	}

	.sh-image {
		width: 90rpx;
		height: 90rpx;
	}

	.sh-info {
		width: 580rpx;
	}

	.sh-name {
		margin-bottom: 10rpx;
		font-size: 28rpx;
		color: #333;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.sh-bar {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.sh-type {
		font-size: 24rpx;
		color: #999;
	}

	.sh-price {
		font-size: 28rpx;
		color: #333;
	}

	.recommend {
		padding: 30rpx;
	}

	.stars {
		margin-bottom: 30rpx;
		display: flex;
		width: 290rpx;
		justify-content: space-between;
		align-items: center;
	}



	.sh-in {
		width: 100%;
		height: 270rpx;
		margin-bottom: 30rpx;
		padding: 20rpx;
		border-radius: 6rpx;
		border: 1px #e6e6e6 solid;
		box-sizing: border-box;
		font-size: 28rpx;
	}

	.up-list {
		margin-bottom: 30rpx;
		display: flex;
		flex-wrap: wrap;
	}

	.up-item {
		width: 180rpx;
		height: 180rpx;
		margin-right: 20rpx;
		margin-bottom: 20rpx;
		border-radius: 10rpx;
		overflow: hidden;
		position: relative;
	}

	.up-image {
		width: 180rpx;
		height: 180rpx;
	}

	.up-icon {
		width: 180rpx;
		height: 180rpx;
	}

	.up-del {
		width: 100%;
		height: 50rpx;
		text-align: center;
		line-height: 50rpx;
		font-size: 24rpx;
		color: #fff;
		background: rgba(0, 0, 0, .5);
		position: absolute;
		bottom: 0;
		left: 0;
		z-index: 9;
	}

	.shiming-btn {
		width: 100%;
		height: 80rpx;
		margin: 0 auto;
		border-radius: 10rpx;
		background: $themeColor;
		text-align: center;
		line-height: 80rpx;
		font-size: 32rpx;
		color: #fff;
	}

	.unname {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 60rpx;
	}


	.unname-tips {
		font-size: 24rpx;
		color: #999;
	}
</style>
