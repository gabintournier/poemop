$(document).ready(function() {
  $('.logo-partenaires').slick({
    autoplay: true,
    infinite: true,
    autoplaySpeed: 0,
    slidesToScroll: 1,
    slidesToShow: 5,
    arrows: false,
    cssEase: 'linear',
    speed: 6500,
    initialSlide: 1,
    draggable: false,
  });

  $('.contenu-groupements').slick();
});
