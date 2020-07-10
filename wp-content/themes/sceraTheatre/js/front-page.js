/* global Swiper */

document.addEventListener('DOMContentLoaded', () => {
	const mySwiper = new Swiper('.swiper-container', {
		// Optional parameters
		// direction: 'vertical',
		loop: true,
		height: 300,
		// If we need pagination
		pagination: {
			el: '.swiper-pagination',
			clickable: 'true',
		},
	});
	const eventCarouselDates = new Swiper('.event-carousel-dates', {
		loop: false,
		height: 156,
		slidesPerView: 'auto',
		spaceBetween: 20,
		centeredSlides: true,
		initialSlide: 1,
		centeredSlidesBounds: false,
		// watchSlidesVisibility: true,
		watchSlidesProgress: true,
	});
	const eventCarousel = new Swiper('.event-carousel', {
		loop: false,
		height: 156,
		slidesPerView: 1,
		spaceBetween: 20,
		centeredSlides: true,
		initialSlide: 1,
		centeredSlidesBounds: true,
		thumbs: {
			swiper: eventCarouselDates,
		},
	});
	eventCarouselDates.on('slideChange', (event) => {
		eventCarousel.slideTo(event.activeIndex);
	});
	eventCarouselDates.on('click', (event) => {
		console.log('clickedIndex:', event.clickedIndex);
		eventCarouselDates.slideTo(event.clickedIndex);
	});
	eventCarousel.on('slideChange', (event) => {
		eventCarouselDates.slideTo(event.activeIndex);
	});
});
