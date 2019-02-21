$(function () {
  'use strict';
  $(window).on('load', function () {
    $('.multiple .multiple-boxes .box.first, .multiple .multiple-boxes .box.last').addClass('opened');
  });
  // $(window).on('scroll', function() {
  //   $('.transformed .card.one').css('transform', 'translateY(2rem)');
  //   $('.transformed .card.two').css('transform', 'translateY(4rem)');
  //   $('.transformed .card.three').css('transform', 'translateY(6rem)');
  //   $('.transformed .card.four').css('transform', 'translateY(8rem)');
  //   $('.transformed .card.five').css('transform', 'translateY(10rem)');
  // });
  var $animation_elements = $('.transformed');
  var $window = $(window);

  function check_if_in_view() {
    var window_height = $window.height();
    var window_top_position = $window.scrollTop();
    var window_bottom_position = (window_top_position + window_height);

    $.each($animation_elements, function() {
      var $element = $(this);
      var element_height = $element.outerHeight();
      var element_top_position = $element.offset().top;
      var element_bottom_position = (element_top_position + element_height);

      //check to see if this current container is within viewport
      if ((element_bottom_position >= window_top_position) &&
        (element_top_position <= window_bottom_position)) {
        $element.addClass('in-view');
        $('.transformed.in-view .one').css('')
      } else {
        $element.removeClass('in-view');
      }
    });
  }

  $window.on('scroll resize', check_if_in_view);
  $window.trigger('scroll');
  });