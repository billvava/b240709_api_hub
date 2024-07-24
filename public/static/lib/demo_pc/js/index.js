$(document).ready(function(){	
/*首页*/		
function adhr(adhrs){
	$(adhrs).hover(function(){
		$(this).addClass("act");	
	},function(){
		$(this).removeClass("act");	
	})	
}
$(".hdr-wh").hover(function(){
	$(this).find("p").show();
},function(){
	$(this).find("p").hide();
})
$(".hdr-list").hover(function(){
	$(this).find(".hdrl-hide").show();
},function(){
	$(this).find(".hdrl-hide").hide();
})
var ishava=0;
$(".hdn").hover(function(){
	var aa=$(this).find(".hdn-show").children("a").hasClass("act");
	if(aa==true){
		ishava=1;
	}else{
		ishava=0;
	}
	$(this).find(".hdn-show").children("a").addClass("act");	
	$(this).find(".hdn-hide").filter(':not(:animated)').slideDown(300);
},function(){
	if(ishava==0){
		$(this).find(".hdn-show").children("a").removeClass("act");	
	}
	$(this).find(".hdn-hide").slideUp(200);
})
adhr(".iontf-bottom input");
adhr("div.mes-zta div.mi-linkf input");
adhr(".qyscrf-st input");
adhr("form.xggr-form .cpf-st input");
$(".ion-top").hover(function(){
	$(this).find(".iont-form").stop().animate({top:"0"},300);
},function(){
	$(this).find(".iont-form").stop().animate({top:"-219px"},200);
})
/*注册与登陆*/ 
$(".mask").css({height:$("body").height()}); 
adhr(".inpo-st input");
$(".hdr-lo a").click(function(){
	$(".mask").show();
	$(".inpo-js1").show();
})
$(".hdr-re a").click(function(){
	$(".mask").show();
	$(".inpo-js0").show();
})
$(".inpo-off").click(function(){
	$(".mask").hide();
	$(".inpo").hide();
})
$(".mask").click(function(){
	$(".mask").hide();
	$(".inpo").hide();
})
/*蛋糕列表*/
$(".plfr-nav li").click(function(){
	$(this).addClass("act").siblings("li").removeClass("act");	
	$(this).siblings("input").attr({value:$(this).attr("plfrns")});
})
var plfrns=0;
$(".plfr-nav li").hover(function(){
	var aa=$(this).hasClass("ant");
	if(aa==true){
		plfrns=1;
	}else{
		plfrns=0;
	}
	$(this).addClass("ant");	
},function(){
	if(plfrns==0){
		$(this).removeClass("ant");	
	}
})
adhr(".plfr-bt input");
/*蛋糕详情*/
$(".stic-lt").click(function(){
	var numebero=parseInt($(this).siblings("input").val());
	if(numebero<2){
		$(this).siblings("input").val(1);
	}else{
		numebero=numebero-1;	
		$(this).siblings("input").val(numebero);
	}
})		
$(".stic-rt").click(function(){
	var numebert=parseInt($(this).siblings("input").val());
	if(numebert<0){
		$(this).siblings("input").val(1);
	}else{
		numebert=numebert+1;	
		$(this).siblings("input").val(numebert);
	}
})	
$(".sttc-number input").blur(function () {
	$(".sttc-number input").each(function () {
		 if (/[^0-9]{1,}/.test($(this).val())) {
		  $(this).val(1);
			return false;
		}
	});
	var numeberf=parseInt($(this).val());
	if(numeberf==0){
		$(this).val(1);
	}
});
adhr(".cdtr-st span input");
adhr(".cdtr-st em input");

$(".cd-col li").click(function(){
	$(this).addClass("act").siblings("li").removeClass("act");
	$(".cd-bottom .cdb").hide().eq($(this).index()).show();
})
/*购物车*/
adhr(".sc-link em");
adhr(".sc-link input");
$(".sc-link i").click(function(){
	if($(this).hasClass("act")){
		$(this).removeClass("act");
		$(".scf-xz span").removeClass("act");
	}else{
		$(this).addClass("act");
		$(".scf-xz span").addClass("act");
	}
})
$(".scf-xz span").click(function(){
	if($(this).hasClass("act")){
		$(this).removeClass("act");
	}else{
		$(this).addClass("act");
	}
})
/*收货地址*/
$(".add-info").hover(function(){
	$(this).find(".add-btn").animate({bottom: "0px"}, "fast");
},function(){
	$(this).find(".add-btn").animate({bottom: "-33px"}, "fast");
});
$(".add-icon1").click(function(){
	$(this).parents(".add-info").siblings().find("span").hide();
	$(this).parent().siblings("span").css({display:"block"});
});

var mh = $("body").height();
$(".mark").css({height: mh});

$(".add-icon2").click(function(){
	$(this).parents(".content").siblings(".mark").show();
});
$(".add-close").click(function(){
	$(this).parents(".mark").hide();
});
$(".add-info2").hover(function(){
	$(this).find(".add-info2-mark").hide();
},function(){
	$(this).find(".add-info2-mark").show();
});
$(".add-info2 a").click(function(){
	$(this).parents(".content").siblings(".mark").show();
});
adhr(".add-edit ul li .walt0 input");
adhr(".add-edit ul li .walt1 input");
//提交订单     
$(".pay-new-btn").click(function(){
	$(this).parents(".content").siblings(".mark").show();
});
adhr(".pay-bill-btn input"); 		
})

