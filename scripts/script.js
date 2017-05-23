$(document).ready(function(){


	var img = getRandomIntInclusive(1,9);

	$(document.body).css({'background':'url(images/background-' + img + '.jpg) no-repeat center center fixed','-webkit-background-size': 'cover',
    '-moz-background-size' : 'cover',
    '-o-background-size' : 'cover',
    'background-size' : 'cover'});

	$('.selectedItem').parent().css({'border-bottom-style' : 'solid', 'border-bottom-width' : '2px', 'border-bottom-color' : 'red'});
});

function getRandomIntInclusive(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min + 1)) + min;
}