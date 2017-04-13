$(function(){




	//延迟加载




	$(".lazy").lazyload({




		effect : "fadeIn" 




	});	




	//返回顶部




	$(".back2top").click(function(){




		$('body,html').animate({scrollTop:0},500);




		return false;




	});




	//导航




	$(".nav").slide({ 




		type:"menu", 




		titCell:".nav-box li", 




		targetCell:".navdrop", 




		effect:"slideDown", 




		delayTime:500, 




		triggerTime:0, 




		titOnClassName:"nav-current", 




		returnDefault:true, 




		startFun:function(i,c){




		 	$(".navdrop").hide(); 




		}




	});









	$(".navdrop a").hover(function(){




		$(this).stop().animate({paddingLeft:"35px"},500);




		$(this).children("span").fadeIn();




	},function(){




		$(this).stop().animate({paddingLeft:"25px"},500);




		$(this).children("span").fadeOut();




	});






















	




	//右侧悬浮




	$(".float-close").click(function(){




		$(".float-show").css("display","block");




		$(".float-right").fadeOut(500);




	});




	$(".float-show").click(function(){




		$(".float-show").hide();




		$(".float-right").fadeIn(500);




	});





	//产品滚动




	$(".shixun-slide").slide({titCell:".hd li",mainCell:".bd ul",triggerTime:0,effect:"fold",delayTime:"200",switchLoad:"_src"});






    //








    $(".jiaoao-local").hover(function(){




    	$(this).children('.local-hover').fadeIn();




    },function(){




    	$(this).children('.local-hover').fadeOut();




    });




});
























$(window).scroll(function () {




	//css3效果（使用方法：添加class：ani-view ）




	var _ismobile = false;




	var windowTop = $(window).scrollTop();




	var windowBottom = windowTop + $(window).height();




	var showNum = !_ismobile ? 4 : 16;




	$('.ani-view').each(function(){




		var pageQ1 = $(this).offset().top + $(this).height() / showNum;




		var pageQ3 = $(this).offset().top  + $(this).height() / 1;




		if( ( pageQ1 <= windowBottom ) && ( pageQ3 >= windowTop ) ){




			if( $(this).hasClass("fade-in-down") ) $(this).addClass('fadeInDown');




			if( $(this).hasClass("fade-in-left") )  $(this).addClass('fadeInLeft');




			if( $(this).hasClass("fade-in-right") )  $(this).addClass('fadeInRight');




			if( $(this).hasClass("indCon2-fade-in-down") )  $(this).addClass('indCon2fadeInDown');




			if( $(this).hasClass("indCon2-fade-in-up") )  $(this).addClass('indCon2fadeInUp');				




		}else {




			// if( $(this).hasClass('fadeInDown') ) $(this).removeClass(' fadeInDown');




			// if( $(this).hasClass('fadeInLeft') ) $(this).removeClass('fadeInLeft');




			// if( $(this).hasClass('fadeInRight') ) $(this).removeClass(' fadeInRight');




			if( $(this).hasClass('fadeInDown') ) $(this).removeClass('ani-view fade-in-down fadeInDown');




			if( $(this).hasClass('fadeInLeft') ) $(this).removeClass('ani-view fade-in-left fadeInLeft');




			if( $(this).hasClass('fadeInRight') ) $(this).removeClass('ani-view fade-in-right fadeInRight');




			if( $(this).hasClass("indCon2fadeInDown") )  $(this).addClass('ani-view indCon2-fade-in-down indCon2fadeInDown');




			if( $(this).hasClass("indCon2fadeInUp") )  $(this).addClass('ani-view indCon2-fade-in-up indCon2fadeInUp');




		}




	});




	




	//回到顶部显示隐藏




	if (windowTop>300){




		$(".float-show").hide();




		$(".float-right").fadeIn(500);




		




	}




	else




	{




		$(".float-show").show();




		$(".float-right").hide();




	}	




});