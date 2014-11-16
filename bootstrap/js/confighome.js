var galleryNumber = 2;
$( document ).ready(function() {
	//http://bootstrap-datepicker.readthedocs.org/en/release/events.html
    //load
    initGallery($("#galleryitem"),galleryNumber);
    $('#formImageUpload').submit(function(e) {
        e.preventDefault();
        data = new FormData($('#formImageUpload')[0]);
        console.log('Submitting');
        uploadImage($("#galleryitem"),data,galleryNumber);
    });
    //$('#btnImageUpload').click();
});