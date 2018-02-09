$(function() {
  $('#ZdelalZakaz').submit(function(e) {
    var $form = $(this);
    $.ajax({
      type: $form.attr('method'),
      url: $form.attr('action'),
      data: $form.serialize()
    }).done(function() {
      console.log('success');
	alert("Отправлено!");
    }).fail(function() {
      console.log('fail');
    });
    e.preventDefault(); 
  });
});