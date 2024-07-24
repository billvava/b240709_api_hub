<style>
	 #selSpecThumbSelDiv img{
        border:1px solid #ccc;    margin-right: 5px;
    }
    #selSpecThumbSelDiv img.act{
        border:2px solid red;
    }
    .ldh-instead-title {
                margin-top: 30px;
                padding-bottom: 10px;
                padding-right: 28px;
                font-size: 14px;
                line-height: 16px;
                border-bottom: 1px solid #f2f2fa;
            }
            .ldh-instead-title span {
                display: inline-block;
                padding-left: 10px;
                border-left: 3px solid #3d85cc;
            }
            .select2{ margin-bottom: 5px;
                      width: 120px;
                      height: 25px;
                      display: inline-block !important;
            }
            .select2:focus,.select2:hover{
             
                    outline: none;
            }
            .ldh-add-box {
                padding: 15px;
                border: 1px solid #dcdcdc; margin-top: 5px;
            }
            .input70{
                width: 70px;
            }
            .ldh-add-box .ldh-add-row-grey {
                margin-bottom: 18px;
                padding: 6px 15px;
                background-color: #E6E5E5;
               
                
            }
            .ldh-add-box .close {
               display: inline-block;
                float: right;
                font-size: 22px;
                text-align: center;
            }
            .operation-card-list.has-img {
                margin: 11px 15px;
            }
            .operation-card-list.has-img > li {
                padding: 1px;
            }
            .operation-card-list li {
                display: inline-block;
                position: relative;
                padding: 7px 15px;
                margin-bottom: 8px;
                margin-right: 20px;
                min-width: 30px;
                line-height: 18px;
                text-align: center;
                vertical-align: middle;
                background-color: #f7f7f7;
                border: 1px solid #dcdcdc;
                cursor: pointer;
            }
            .operation-card-list.has-img .small-img {
                position: relative;
                float: left;
                display: inline-block;
                width: 40px;
                height: 40px;
                line-height: 40px;
                background-color: #eee;
                overflow: hidden;
            }
            .operation-card-list.has-img .text {
                line-height: 40px;
                padding: 0 18px;
            }
            .operation-card-list li .icon-remove {
                position: absolute;
                top: -6px;
                right: -6px;
            }
            .qIcon {
              font-size: 18px !important;
            position: absolute;
            top: 0;
            right: 0;
                
            }
            
            
            
            
            
            .layui-form-label {
    color: #6a6f6c;
    width: 100px;
}
.layui-input-block {
    margin-left: 130px;
}

.add-spec-value {
    height: 38px;
    line-height: 38px;
    color: #4e8bff;
    cursor: pointer;
}

.add-spec-value:hover {
    color: #0641cb;
}

.goods-img-del-x {
    display: none;
    position: absolute;
    z-index: 100;
    top: -4px;
    right: -2px;
    width: 20px;
    height: 20px;
    font-size: 16px;
    line-height: 16px;
    color: #fff;
    text-align: center;
    cursor: pointer;
    background: hsla(0, 0%, 60%, .6);
    border-radius: 10px;
}

.goods-li {
    float: left;
    opacity: 1;
    position: relative;
}

.goods-img {
    width: 80px;
    height: 80px;
    padding: 4px;
}

.goods-img-add {
    font-size: 60px;
    vertical-align: middle
}
.master-image{
    margin-bottom:4px;
    float: left;
    position: relative;
}

.goods-spec-img {
    width: 48px;
    height: 48px;
}

.goods-spec-del-x {
    display: none;
    position: absolute;
    z-index: 100;
    top: 15px;
    right: 15px;
    width: 18px;
    height: 18px;
    font-size: 16px;
    line-height: 14px;
    color: #fff;
    text-align: center;
    cursor: pointer;
    background: hsla(0, 0%, 60%, .6);
    border-radius: 15px;
}

.goods-spec-value-del-x {
    display: none;
    position: absolute;
    z-index: 100;
    top: -8px;
    right: -8px;
    width: 20px;
    height: 20px;
    font-size: 15px;
    line-height: 20px;
    color: #fff;
    text-align: center;
    cursor: pointer;
    background: hsla(0, 0%, 60%, .6);
    border-radius: 15px;
}

.goods-spec-img-del-x {
    display: none;
    position: absolute;
    z-index: 100;
    top: -2px;
    right: 10px;
    width: 20px;
    height: 20px;
    font-size: 16px;
    line-height: 14px;
    color: #fff;
    text-align: center;
    cursor: pointer;
    background: hsla(0, 0%, 60%, .6);
    border-radius: 10px;
}

.goods-one-spec-img-del-x {
    display: none;
    position: absolute;
    z-index: 100;
    top: -2px;
    right: 10px;
    width: 20px;
    height: 20px;
    font-size: 16px;
    line-height: 14px;
    color: #fff;
    text-align: center;
    cursor: pointer;
    background: hsla(0, 0%, 60%, .6);
    border-radius: 10px;
}

.goods-spec-value-input {
    margin-bottom: 5px;
}

.goods-spec {
    margin-top: 10px;
    margin-bottom: 10px;
    background-color: #f3f5f9;
    padding: 10px;
}

.goods-spec-div {
    position: relative;
}

.form-label-asterisk {
    color: red;
    padding-right: 5px
}

.spec-lists-table th {
    text-align: center;
}

.spec-lists-table td {
    text-align: center;
}
.batch-div{
    padding-bottom: 10px;
    padding-top: 10px;
}
.click-a {
    color: #4685fd;
    padding-right: 10px;
    cursor: pointer;
}
.batch-spec-title{
    color: #6a6f6c;
    float: left;
}
.unit-tips{
    float: left;
    display: block;
    padding: 9px 0!important;
    line-height: 20px;
    margin-right: 10px;
}
</style>