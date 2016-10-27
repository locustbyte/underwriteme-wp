// Add padding to content
function mcw_header_padding(){
  var header_size = $('[@header]').height();
  if ($('body').hasClass('admin-bar'))
  {
    var ah = $('#wpadminbar').height();
    $('[@header]').css('top', ah + 'px');
  }
  $('.fp-enabled .mcw_fp_section_pad').css('padding-top', header_size + 'px');
}

/*$('[@selector]').wrapInner("<div class='mcw_fp_section_pad'><div class='mcw_fp_section_pad_inner'></div></div>");*/