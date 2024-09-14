$(document).ready(function (){
    //banner-swiper
    var swiperV = new Swiper('.banner-swiper', {
         autoplay: {
            delay: 3000,
        },
        preloadImages: false,
        lazy: true,
        pagination: {
            el: '.swiper-pagination',
        },
        loop: true,
        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
    
    /*
    const observer = new IntersectionObserver(entries => {
      const firstEntry = entries[0];
      if (firstEntry.isIntersecting) {
        swiperV.autoplay.start();
      } else {
        swiperV.autoplay.stop();
      }
    });
    
    const bannerswiperContainer = document.querySelector('.banner-swiper');
    observer.observe(bannerswiperContainer);
    */
});