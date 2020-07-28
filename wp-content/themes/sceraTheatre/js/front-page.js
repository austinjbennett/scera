/* global Swiper */

document.addEventListener('DOMContentLoaded', () => {
	const mySwiper = new Swiper('.swiper-container', {
		loop: true,
		height: 300,
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
		initialSlide: 6, // Slider starts 7 days in the past. So start on index 6 = today
		centeredSlidesBounds: false,
	});
	const eventCarousel = new Swiper('.event-carousel', {
		effect: 'slide',
		allowTouchMove: false,
		loop: false,
		slidesPerView: 1,
		spaceBetween: 20,
		centeredSlides: true,
		initialSlide: 6, // Slider starts 7 days in the past. So start on index 6 = today
		centeredSlidesBounds: true,
		thumbs: {
			swiper: eventCarouselDates,
		},
	});
	eventCarouselDates.on('slideChange', (event) => {
		eventCarousel.slideTo(event.activeIndex);
	});
	eventCarouselDates.on('click', (event) => {
		if (event.clickedIndex > -1) {
			eventCarouselDates.slideTo(event.clickedIndex);
		}
	});
});
