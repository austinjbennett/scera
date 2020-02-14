// Can also be used with $(document).ready()
jQuery(window).load(function() {
  jQuery('.flexslider').flexslider({
    animation: 'slide',
    animationLoop: true,
    itemWidth: 190,
    itemMargin: 35,
    minItems: 4,
    maxItems: 4
  });
});
