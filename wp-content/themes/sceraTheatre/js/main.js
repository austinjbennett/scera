/* global $ */

// function fadeIn(element) {
// 	let op = 0.1; // initial opacity
// 	element.style.display = 'initial';
// 	const timer = setInterval(() => {
// 		if (op >= 1) {
// 			clearInterval(timer);
// 		}
// 		element.style.opacity = op;
// 		op += 0.1;
// 	}, 10);
// }
// function fadeOut(element) {
// 	let op = 1; // initial opacity
// 	const timer = setInterval(() => {
// 		if (op <= 0.1) {
// 			clearInterval(timer);
// 		}
// 		element.style.opacity = op;
// 		op -= 0.1;
// 	}, 50);
// 	element.style.display = 'none';
// }

function toggleNav() {
	const nav = document.getElementById('nav-wrapper');
	if (!this.classList.contains('open')) {
		this.classList.add('open');
		// shortstack.nextElementSibling.classList.add('open');
		// fadeIn(nav);
		nav.classList.add('open');
	} else {
		this.classList.remove('open');
		// shortstack.nextElementSibling.classList.remove('open');
		// fadeOut(nav);
		nav.classList.remove('open');
	}
}
document.addEventListener('DOMContentLoaded', () => {
	document.getElementById('nav-toggle').addEventListener('click', toggleNav);
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
});

// // ASSIGN CLASSES TO PARENT AND SUB NAV
// const navPars = document.querySelectorAll('.menu-item-has-children');
// for (let i = 0; i < navPars.length; i += 1) {
// 	const navPar = navPars[i];
// 	navPar.classList.add('navParent');
// 	const parA = navPar.querySelector('a');
// 	// parA.setAttribute('onclick','toggleSubNav()');
// 	// parA.onclick = toggleSubNav;
// 	parA.classList.add('notPage');
// 	const subUl = navPar.querySelector('.sub-menu');
// 	// var subUl = navPar.querySelector('.dropdown-menu');
// 	subUl.classList.add('subNav');
// 	const subLi = subUl.querySelector('li');
// 	subLi.classList.add('subLi');
// }

// var hbMenu = document.getElementById('hbMenu');
// subNav toggle
// $('li.navParent').hover(function () {
// 	if ($('#hbMenu:hidden')) {
// 		console.log('is hide');
// 		$(this).children('.subNav').slideToggle('fast');
// 	}
// });
// $('li.navParent').click(function () {
// 	$(this).children('.subNav').slideToggle('fast');
// });
// function toggleSubNav(){
// $('.subNav').slideToggle('normal');
// };

// Slide Toggle Mobile Nav
// $(() => {
// 	$('#hbMenu').click(() => {
// 		$('#myTopNav').slideToggle('normal');
// 		console.log('we did it');
// 	// $('.searchArea').slideToggle('normal');
// 	});
// });

// const searchWrap = document.createElement('div');
// searchWrap.classList.add('searchWrap');
// searchWrap.innerHTML = '<form id="searchForm" class="form-inline searchArea" method="get" action="/">'
// 	+ '<input class="sfield" type="search" name="s" placeholder="Search SCERA.org">'
// 	+ '<i class="fas fa-search fa-lg"></i>'
// + '</form>';
// $('#myTopNav').prepend(searchWrap);
//
// const myPag = $('.myPag');
// $('.aboveTitle').append(myPag);
//
// // LAZY LOAD GALLERY
// const lazyImgNodes = document.querySelectorAll('.lazyImg1');
// const lazyImgs = [];
// lazyImgNodes.forEach((laz) => {
// 	lazyImgs.push(laz);
// });
// // console.log(lazyImgs);
// const inAdvnce = 0;
// let i;
//
// function lazyLoad() {
// 	for (i = 0; i < lazyImgs.length; i++) {
// 		if (lazyImgs[i].offsetTop < window.innerHeight + window.pageYOffset + inAdvnce) {
// 			const lazNum = i + 1;
// 			lazyImgs[i].src = `/03WordPress/lazyGal/lazy${lazNum}.jpg`;
// 		}
// 	}
// }
// lazyLoad();
//
// window.addEventListener('scroll', lazyLoad);
// window.addEventListener('resize', lazyLoad);
