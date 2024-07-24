<style type="text/css">
    * {padding: 0;margin: 0}
    ol,ul {list-style: none;}
    h1,h2,h3,h4,h5,h6 {font-weight: 400;line-height: 1;}
    a {text-decoration: none;transition: color .1s linear;color: #333;}
    em,i {font-style: normal;}
    img {border: none}
    input,textarea {font-family: "微软雅黑", Arial;outline: 0;font-size: 14px;-webkit-appearance: none;border: none;background: 0 0}
    :focus{outline:none}
    .clear {clear: both}
    .fl{float: left}
    .fr {float: right}
    .ellipsis {text-overflow: ellipsis;overflow: hidden;white-space: nowrap;display: block}
    .clearfix:after {clear: both;display: block;content: ''}
    .clearfix {zoom: 1}
    html,body{ height:100%; background-color:#f8f8f8}

    #login_header{    height: 66px;border-bottom: 1px solid #ededed;text-align: center;-webkit-box-shadow: 0 2px 4px rgba(0,0,0,.08);box-shadow: 0 2px 4px rgba(0,0,0,.08);background-color: #4fa0ec;display: none}
    #login_header .h_logo{ display: inline-block;width: 120px; margin-top: 16px}
    #login_header .h_logo img{width: 100%; height: 100%}
    #login_content .login_main{width: 500px;height: auto;background-color: #fff;webkit-transform: translateX(-50%) translateY(-72%);transform: translateX(-50%) translateY(-72%);webkit-box-shadow: 0 10px 40px 0 rgba(0,0,0,.1);box-shadow: 0 10px 40px 0 rgba(0,0,0,.1);border-radius: 3px;text-align: center;position: fixed;left: 50%;top: 50%;}
    #login_content .login_main .title {font-size: 21px;color: #444;text-align: center;padding-top: 35px;margin-bottom: 20px;}
    #login_content .login_main  .login_form{display: inline-block;padding-top: 20px;margin-bottom: 40px;width: 80%;}
    #login_content .login_main .inputBox{height: 49px;border-bottom: 1px solid #dfdfdf;position: relative;margin-bottom: 15px;border-radius: 3px;text-align: left;}
    #login_content .login_main .passwork .inputBox{ margin-bottom: 5px;}
    #login_content .login_main .inputBox input{position: absolute;height: 100%;width: calc(100% - 55px);margin-left: 35px;margin-right: 20px;}
    #login_content .login_main .submit{ text-align: right;margin-top: 15px;}
    #login_content .login_main .submit button{border: none; background:none;color: #fff;padding: 0 10px;line-height: 30px;font-size: 16px;width: 100%;height: 50px;cursor: pointer;border-radius: 4px;background-color: #46be8a;border-color: #46be8a;}
    #login_content .login_main .red{ border-color: red}

    .login_main .header{position: relative;height: 80px;border-radius: 2px 2px 0 0;text-align: center;height: 80px;background: #677ae4;}
    .login_main .header img {margin: 26px 0;height: calc(100% - 52px);}
    .login_main  .footer {position: absolute;bottom: -50px;width: 100%;text-align: center;color: #acacac;font-family: arial,'微软雅黑';font-size: 12px;background: none;}
    .el-icon-user{ position: absolute; width: 16px; height:16px; background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA6klEQVQ4jbWSoVLDQBCGK5AIBDhEBRKBqKiIqED2AZAIBALBA/QtkDgqKxAIZDyIzN5+P0wmIhLJA1QcJsMESJqLYGdW3Xzf3f57k8l/VQjhBngCnoHbPM/3kmFJd0D81fep8EkHHIEo6WxQAFz0CUIIl4MCd5/tEJynjLDv7g8dgk1VVUc7YTM7BVZmNm+C/AA+JT1KyoCVu8/6nr4Ats1tW0lrd78GriSt22fuvuwK77Vv9o5+/wGXZXk4Ao5ALIpi2g4uGytw90U7vPlYgaTsW1DX9QHwMkLwZmbHf7bQpF0P9CbpQ6XWFzBl2vN4ey1LAAAAAElFTkSuQmCC) no-repeat;background-size: cover;top: 17px;}
    .el-icon-unlock-alt{ position: absolute; width: 16px; height:16px; background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAwUlEQVQ4jd2RIQ7CQBREEQgkogKJ4AAcoIdAoxDgEEgECUdAIJEIjrFH2OTnjdhU7AEQCCRiMQ1poKGFBMMkY34yb5L5nc4vJWlsZhNJg4+C3vsh4IFU+gZsWoWdc12gAJKko6Q9cAGSmc0aAcC8bH00hhCyEnJuAzgBKcbYq97NbAk4SaMmgANiY9OzvPd94ABcy9FijQvgEELI6ppXldXf2szWLwBJ27YAYPengOkHgEXtKyXlZjZ7Z0l5bfhb3QHXnZa5efuV8wAAAABJRU5ErkJggg==) no-repeat;background-size: cover;top: 17px;}
</style>

<div id="login_content">
    <div class="content">
        <div class="login_main">

            <div class="header">
                <h2 style="height: 80px;line-height: 80px;text-align:center;color: #fff ">后台管理系统</h2>
            </div>
            <div class="login_form">
                <form method="post"  id="loginForm">
                    <input name="auto_login"  type="hidden" value="1" />
                    <div class="username">
                        <div class="inputBox">
                            <i class="el-icon-user"></i>
                            <input type="text" name="username" autocomplete="off" class="inputUser" placeholder="用户名">
                        </div>
                    </div>
                    <div class="passwork">

                        <div class="inputBox"><i class="el-icon-unlock-alt"></i><input type="password"  name="pwd"  autocomplete="off" class="inputPass" placeholder="密码"></div>

                    </div>

                    <?php if(C('admin_login_verify')>0){ ?>
                        <div class="passwork">

                            <div class="inputBox"><i class="el-icon-unlock-alt"></i>
                                <input type="text"  name="verify" autocomplete="off" class="inputPass" placeholder="验证码">
                            </div>
                        </div>
                        <div><img id="code" src="{:url('verify')}" alt="captcha" onclick="this.src='{:url('verify')}?'+Math.random();"/></div>
                    <?php } ?>

                    <p class="error" style="height:30px; line-height:30px; text-align:left;color:red; font-size:14px;"></p>
                    <div class="submit">
                        <a href="javascript:;">
                            <button type="button" onclick="login()" id="formSubmit" autocomplete="off">登录</button>
                        </a>
                    </div>
                </form>
            </div>
            <div class="footer">
                <span>Copyright © <?php echo date('Y'); ?> </span>
            </div>

        </div>
    </div>
</div>

<script>
    var flag = 1;
    function login(){
        if(flag==0){
            return false;
        }
        // flag = 0;
        load();
        ajax('__SELF__',$("form").serialize(),function(data){

        },function(data){
            flag = 1;
            change_code('{:url('verify')}');
        });

    }
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            login();
        }
    });
</script>