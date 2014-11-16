$( document ).ready(function() {
	//////////////////////////////////////////
	var _width = $(window).width();
	//////////////////////////////////////////
	navmenu();
	$("#viewport").text("viewport:"+$( window ).width()+"x"+$( window ).height());
	$("#scroll").text("scroll:"+$( window ).scrollTop());
	$(window).smartresize(function(){
		var winwidth = $( window ).width();
		var winheight = $( window ).height();
		$("#viewport").text("viewport:"+winwidth+"x"+winheight);
		if(_width !== winwidth ) _width = winwidth;
		else return;
		navmenu();
		if(winwidth>1000){
			$("#navlist").show();
			$("#headerspace").height(30);
		}
		else $("#navlist").hide();
	});
	$(window).scroll(function(){
		var scroll = $(window).scrollTop();
		$("#scroll").text("scroll:"+scroll);
		if(scroll > 190) {
			if($("#navbar").width()!=1200) $("#headerspace").show();
			$("#navparent").css({position:"fixed",top:"10px"});
			//$("#headerspace").show();
			console.log("show");
		}else{
			$("#navparent").css({position:"static",marginLeft:"auto"});
			//$("#headerspace").hide();
			console.log("hide");
		}
	}).scroll();
});
function navmenu(){
	if($( window ).width()<1001){
		$("#navlist").slideToggle({
			duration:"fast",
			step:function(){
				//$("#headerspace").height($("#navbar").height());
			},
			complete:function(){
				if($("#navlist").css("display")==="none"){
					$("#navbtnmenu > img").attr({src:"img/bars-white.png"});
					$("#bgspace").fadeOut("fast");
				}
				else {
					$("#navbtnmenu > img").attr({src:"img/bullets-white.png"});
					$("#bgspace").fadeIn("fast");
				}
			},
			start:function(){

			}
		});
	}
}