$( document ).ready(function() {
	//http://bootstrap-datepicker.readthedocs.org/en/release/events.html
	$('#datepicker').datepicker({
    format: 'dd/mm/yyyy',
	});
	$("#btnformsubmit").click(function(){
		var price=$('#inputPrice').val();
		var room=$('#inputRoom').val();
		var from;
		var to;
		if ('Invalid Date'==$('#dateFrom').datepicker('getDate')){
			to=-1;
			from=-1;
		}
		else {
			from=$('#dateFrom').datepicker('getDate')
			from.setDate(from.getDate()+1);
			//console.log(from);
			from=from.toISOString();
			if ('Invalid Date'==$('#dateTo').datepicker('getDate')){
				to=from;
			}
			else {
				to=$('#dateTo').datepicker('getDate');
				to.setDate(to.getDate()+1);
				to=to.toISOString();
				}
			from = from[0]+from[1]+from[2]+from[3]+from[5]+from[6]+from[8]+from[9];
			to = to[0]+to[1]+to[2]+to[3]+to[5]+to[6]+to[8]+to[9];
			}
		var amount=$('#inputAlotment').val();
		showData(price,room,from,to,amount);
	});
	$("#btnforsave").click(function(){
		//console.log($("#showquery").nextAll().length);
		var check=0;
		for (var i=0;i<$("#showquery").nextAll().length;i++){
			if (($("#inputAlotment"+i).val())&&$("#inputAlotment"+i).val()<$("#reserv"+i).val()){
				check++;
				alertMessage("Error: Row:"+(i+1)+" Alotment < Reserved");
			}
		}
		if (check==0){
			for (var i=0;i<$("#showquery").nextAll().length;i++){
				//console.log("update"+i);
				savedata(i);
			}
		}
	});
	initRoom();	
});
function initRoom(){
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else { // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			str=xmlhttp.responseText;
			//console.log(str);
			$("#inputRoom").append(str);
		}
	}
	  xmlhttp.open("GET","../php/query.php?init=true",true);
	  xmlhttp.send();
	
}
bootstrap_alert = function() {}
bootstrap_alert.warning = function(message) {
	$('#alert_placeholder').append('<div class="alert alert-danger alert-dismissible fade in alertDelete" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+message+'</div>');
}
function alertMessage(msg){
	bootstrap_alert.warning(msg);
	window.setTimeout(function() { $(".alertDelete").alert('close'); }, 5000);
}
function showData(price,room,from,to,amount){
	if (price==''){
		price=-1;
	}
	if (amount==''){
		amount=-1;
	}
	//console.log(price,room,from,to,amount);
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else { // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			str=xmlhttp.responseText;
			//console.log(str);
			//document.getElementById("showquery").innerHTML=str;
			$("#showquery").nextAll().remove();
			//console.log($("#showquery"));
			$(str).insertAfter("#showquery");
			//xmlhttp.responseText;
			//console.log($("#showquery").nextAll().length);
			if (str=="NODATA"){
				alertMessage("NO DATA");
			}
		}
	}
	  xmlhttp.open("GET","../php/query.php?query=true&price="+price+"&room="+room+"&from="+from+"&to="+to+"&amount="+amount,true);
	  xmlhttp.send();
}
function savedata(i){
		//console.log(i);
		if (i==0)i='0';
		//console.log("update");
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				//console.log($("#showquery").nextAll().length);
				str=xmlhttp.responseText;
				//console.log(str);
			}
		}
		var price=$("#inputPrice"+i).val();
		var room=$("#roomtype"+i).val();
		var from=$("#from"+i).text();
		var to=$("#to"+i).text();
		var amount=$("#inputAlotment"+i).val();
		if (amount=='')
			amount=$("#inputAlotment"+i).attr("placeholder");
		xmlhttp.open("GET","../php/query.php?update=true&price="+price+"&room="+room+"&from="+from
					+"&to="+to+"&amount="+amount,true);
		xmlhttp.send();
		console.log(price,room,from,to,amount);
		//console.log($("#inputAlotment"+i).attr("placeholder"));
}