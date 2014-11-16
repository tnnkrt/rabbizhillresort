$( document ).ready(function() {
	//http://bootstrap-datepicker.readthedocs.org/en/release/events.html
	$('#datepicker').datepicker({
    format: 'yyyy/mm/dd',
	});
	$("#btnformsubmit").click(function(){
		var from;
		var to;
		if ('Invalid Date'==$('#dateArraival').datepicker('getDate')){
			to=-1;
			from=-1;
		}
		else {
			from=$('#dateArraival').datepicker('getDate')
			from.setDate(from.getDate()+1);
			//console.log(from);
			from=from.toISOString();
			if ('Invalid Date'==$('#dateDeparture').datepicker('getDate')){
				to=from;
			}
			else {
				to=$('#dateDeparture').datepicker('getDate');
				to.setDate(to.getDate()+1);
				to=to.toISOString();
				}
			from = from[0]+from[1]+from[2]+from[3]+from[5]+from[6]+from[8]+from[9];
			to = to[0]+to[1]+to[2]+to[3]+to[5]+to[6]+to[8]+to[9];
			}
		var email = $('#inputEmail').val();
		var name = $('#inputName').val();
		var roomtype = $('#inputRoom').val();
		var person = $('#inputPerson').val();
		//console.log(from,to,email,name,roomtype,person);
		query(from,to,roomtype,email,name,person);
	});
});
function deleteorder(id){
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else { // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			str=xmlhttp.responseText;
			console.log(str);
		}
	}
	  xmlhttp.open("GET","../php/query.php?delete=true&id="+id,true);
	  xmlhttp.send();
}
bootstrap_alert = function() {}
bootstrap_alert.warning = function(message) {
	$('#alert_placeholder').append('<div class="alert alert-danger alert-dismissible fade in alertDelete" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+message+'</div>');
}
bootstrap_success = function() {}
bootstrap_success.warning = function(message) {
	$('#alert_placeholder').append('<div class="alert alert-success alert-dismissible fade in alertDelete" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+message+'</div>');
}
function alertMessage(msg){
	bootstrap_alert.warning(msg);
	window.setTimeout(function() { $(".alertDelete").alert('close'); }, 5000);
}
function successMessage(msg){
	bootstrap_success.warning(msg);
}
function confirm(email,name,from,to,roomtype,person,s){
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
			console.log(str);
			successMessage("ID Transaction (for Pay and Cancel) : "+str+" Price = "+s);
		}
	}
	  xmlhttp.open("GET","../php/query.php?reserv=true&email="+email+"&name="+name+"&from="+from+"&to="+to+"&room="+roomtype+"&person="+person,true);
	  xmlhttp.send();
}
function query(from,to,roomtype,email,name,person){
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
			//console.log(str.charAt(str.length-1));
			//console.log(str);
			if (str[0]=='C'){
				var s=str.substr(3);
				console.log(s);
				confirm(email,name,from,to,roomtype,person,s);
			}
			else {var s=str;
				console.log(s);
				//console.log(str.replace(/\d/g,"").length);
				for (var i=0;i<str.replace(/\d/g,"").length;i++){
					if (i==0){
						s=str.substr(0,str.indexOf(' '));
						alertMessage("Can not reserv Date:"+s);
						str=str.substr((str.indexOf(' ')));
						str=str.substr(1);
						console.log(str);
					}
					else {
						s=str.substr(0,str.indexOf(' '));
						alertMessage("Can not reserv Date:"+s);
						str=str.substr((str.indexOf(' ')));
						str=str.substr(1);
						console.log(str);
					}
				}
			}
		}
	}
	  xmlhttp.open("GET","../php/query.php?query_check=true&from="+from+"&to="+to+"&room="+roomtype+"&amount=0",true);
	  xmlhttp.send();
}