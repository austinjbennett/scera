// JS FILE

// Mobile Menu Button
function menuClick(x){
	x.classList.toggle('change');
};

// ASSIGN CLASSES TO PARENT AND SUB NAV
var navPars = document.querySelectorAll('.menu-item-has-children');
for(var i=0;i<navPars.length;i++){
	var navPar = navPars[i];
	navPar.classList.add('navParent');
	var parA = navPar.querySelector('a');
	// parA.setAttribute('onclick','toggleSubNav()');
	// parA.onclick = toggleSubNav;
	parA.classList.add('notPage');
	var subUl = navPar.querySelector('.sub-menu');
	// var subUl = navPar.querySelector('.dropdown-menu');
	subUl.classList.add('subNav');
	var subLi = subUl.querySelector('li');
	subLi.classList.add('subLi'); 
}

// var hbMenu = document.getElementById('hbMenu');
// subNav toggle
$('li.navParent').hover(function(){
	if($('#hbMenu:hidden')){
		console.log('is hide');
		$(this).children('.subNav').slideToggle('fast');	
	}
});
$('li.navParent').click(function(){
	$(this).children('.subNav').slideToggle('fast');	
});
// function toggleSubNav(){
	// $('.subNav').slideToggle('normal');
// };

// Slide Toggle Mobile Nav
$(function(){
	$('#hbMenu').click(function(){
	$("#myTopNav").slideToggle('normal');
	console.log('we did it');
	// $('.searchArea').slideToggle('normal');
	});
});

var searchWrap = document.createElement('div');
searchWrap.classList.add('searchWrap');
searchWrap.innerHTML='<form id="searchForm" class="form-inline searchArea" method="get" action="/">'+
	'<input class="sfield" type="search" name="s" placeholder="Search SCERA.org">'+
	'<i class="fas fa-search fa-lg"></i>'+
'</form>';
$('#myTopNav').prepend(searchWrap);


var myPag = $('.myPag');
$('.aboveTitle').append(myPag);

// SLICK SLIDER
// $('.slider').slick({
// autoplay: true,
// arrows: false,
// dots: true
// });


// ACTIVATE MENU
// GET PAGE NAME
// function getPageFromUrl(url){
// 	var urlArr = url.split('/');
// 	var partsNum = urlArr.length;
// 	var pg = urlArr[partsNum-2];
// 	var pgNmArr = pg.split('.');
// 	var pgNm = pgNmArr[0];
// 	return pgNm;
// };

// var curUrl = location.href;
// var curPage = getPageFromUrl(curUrl);

// var navOpt = document.querySelectorAll(".topNav li a");
// navOpt.forEach(opt=>{
// 	var menuPg = getPageFromUrl(opt.href);
// 	console.log(menuPg+' and '+curPage);
// 	if(curPage == ""){
// 		curPage = 'index';
// 	}
// 	if(menuPg == curPage && !opt.classList.contains('notPage')){
// 		opt.parentNode.classList.add('active');
// 		// console.log(opt.parentNode.parentNode);
// 		if(opt.parentNode.parentNode.classList.contains("subNav")){
// 			opt.parentNode.parentNode.parentNode.classList.add('active');
// 		}
// 	}
// }); // end activate menu


// LAZY LOAD GALLERY
var lazyImgNodes = document.querySelectorAll('.lazyImg1');
var lazyImgs = [];
lazyImgNodes.forEach(laz=>{
	lazyImgs.push(laz);
});
// console.log(lazyImgs);
var inAdvnce = 0;
var i;

function lazyLoad(){
	for(i=0; i<lazyImgs.length; i++){
		if(lazyImgs[i].offsetTop < window.innerHeight + window.pageYOffset + inAdvnce){
			var lazNum = i+1;
			lazyImgs[i].src = '/03WordPress/lazyGal/lazy'+lazNum+'.jpg';
		}
	};
};
lazyLoad();

window.addEventListener('scroll', lazyLoad);
window.addEventListener('resize', lazyLoad);


// // ICON MENUS
// var accIco = document.querySelector('.icon.account');
// var accIcoInner = document.querySelector('.icon.account i');
// var accDD = document.querySelector('.accDD');
// accIco.onclick = function(){
// 	accDD.classList.toggle('hide');
// 	accIcoInner.classList.toggle('activeIco');
// };

// // HANDLING SEARCHBAR
// var searchIco = document.querySelector('.icon.search i');
// var searchBar = document.querySelector('.searchArea input');
// var searchForm = document.getElementById('searchForm');
// // console.log(searchBar);
// searchIco.onclick=function(){
// 	if(searchBar.value.length>0){
// 		console.log('searching');
// 		searchForm.submit();
// 	}else{
// 		searchBar.classList.toggle('show');
// 		searchIco.classList.toggle('show');
// 		searchIco.classList.toggle('fa-search');
// 		searchIco.classList.toggle('fa-times');
// 	}
// }
// searchBar.oninput=function(){
// 	if(searchBar.value.length==0){
// 		searchIco.classList.remove('fa-search');
// 		searchIco.classList.add('fa-times');
// 	}else{
// 		searchIco.classList.add('fa-search');
// 		searchIco.classList.remove('fa-times');
// 	}
// }

// var evDiv = document.querySelector('')