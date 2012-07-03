$(document).ready(function(){
  $('.gc-results').hide();

  $('#gc-result').click(function(e) {
    e.preventDefault();
    $('#css-result').parent().removeClass('active');
    $(this).parent().addClass('active');

    $('.css-results').hide();
    $('.gc-results').show();
  });

  $('#css-result').click(function(e) {
    e.preventDefault();
    $('#gc-result').parent().removeClass('active');
    $(this).parent().addClass('active');

    $('.gc-results').hide();
    $('.css-results').show();
  });
});