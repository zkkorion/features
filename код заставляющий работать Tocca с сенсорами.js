 //без этого не работает сенсор на tocca
 $('element').bind('touchmove',function(e){
            e.preventDefault();
            var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
});