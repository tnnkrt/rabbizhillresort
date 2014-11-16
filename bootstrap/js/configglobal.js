var objectDeleteImage = {image:"",galleryId:""};
var wwwdirectory = "/rabbizhillresort"
bootstrap_alert = function() {}
bootstrap_alert.warning = function(message) {
	$('#alert_placeholder').append('<div class="alert alert-danger alert-dismissible fade in alertDelete" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+message+'</div>');
}
function alertMessage(msg){
	bootstrap_alert.warning(msg);
	window.setTimeout(function() { $(".alertDelete").alert('close'); }, 5000);
}
function initGallery(gallerybox,galleryId){
	$.ajax({
        type: 'POST',
        url: '../php/imageQuery.php',
        data: "galleryId="+galleryId,
    }).done(function(datas) {
        console.log(datas);
        var items = $.parseJSON(datas);
        console.log(items);
        $(items).each(function(index,item){
        	$(gallerybox).append(ImageDOM(wwwdirectory+'/php/uploadimage/',item['name_image'],galleryId));
        });
        alertMessage('<strong>Complete!</strong> Download images complete.');
    }).fail(function(data) {
        console.log(data);
        alertMessage('<strong>'+data.responseText+'!</strong> please upload some image.');
    });
}
function uploadImage(gallerybox,data,galleryId){
    console.log(data);
    $.ajax({
        type: 'POST',
        url: '../php/imageUpload.php',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
        	alertMessage('<strong>Uploading!</strong> please wait.');
        }
    }).done(function(data) {
        console.log(data);
        $('#btnRemoveUpload').click();
        alertMessage('<strong>Success!</strong> image was upload successfully.');
        $(gallerybox).append(ImageDOM(wwwdirectory+'/php/uploadimage/',data.substring(0,23),galleryId));
    }).fail(function(data) {
        alertMessage('<strong>'+data.responseText+'!</strong> please refresh and try again.');
    });
}
function prepareDeleteImage(name,galleryId){
	console.log(name+"&"+galleryId);
	objectDeleteImage.image=name;
	objectDeleteImage.galleryId=galleryId;
	$('#modalimage').attr({src:wwwdirectory+'/php/uploadimage/'+name});
}
function deleteImage(){
	console.log(objectDeleteImage);
	$.ajax({
        type: 'POST',
        url: '../php/imageDelete.php',
        data: "name_image="+objectDeleteImage.image+"&galleryId="+objectDeleteImage.galleryId,
        beforeSend: function(){
        	console.log("deleting");
        	alertMessage('<strong>Deleting!</strong> please wait.');
        }
    }).done(function(data) {
        console.log(data);
        alertMessage('<strong>Delete!</strong> image was deleted successfully.');
		$('#ConfirmDeleteModal').modal('hide');
		$('#'+objectDeleteImage.image.substring(0,19)).remove();
    }).fail(function(jqXHR,status, errorThrown) {
        console.log(errorThrown);
        console.log(jqXHR.responseText);
        console.log(jqXHR.status);
        alertMessage('<strong>Delete Error!</strong> please refresh and try again.');
    });
}
function ImageDOM(prefix,img,galleryId){
	return '<div class="col-xs-6 col-md-3" id="'+img.substring(0,19)+'"><div href="#" class="thumbnail"><img src="'+prefix+img+'" alt="loading .." style="height:200px;"><br/><a type="button" class="btn btn-danger center-block" data-toggle="modal" data-target="#ConfirmDeleteModal" onclick="prepareDeleteImage(\''+img+'\',\''+galleryId+'\')">Delete</a></div></div>';
}