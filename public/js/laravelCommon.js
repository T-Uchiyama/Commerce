$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
});

function addUpload() {
    $('.add_button_area').css('margin-bottom', '15px');
    $('.add_button_area').append('<div class="form-group"><input multiple="" name="file[]" type="file"></div>');
}

window.addEventListener('load', function() {
    var swiper = new Swiper('.swiper-container', {
        loop: true,
        slidesPerView: 'auto',
        spaceBetween: 2,
        speed: 200,
        // autoplay: 2000
    });
}, false);