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

function plus_or_minus(param, limit, pm_flg)
{
    if (pm_flg == '+') {
        param.value++;
        if (param.value == (limit + 1)) {
            param.value = limit;
        }
    } else {
        if (param.value > 1) {
            param.value--;
        } 
    }
    
    $.ajax({
        url: '/display/changeCartNum',
        type: 'POST',
        dataType: 'json',
        data: {product_name:param.id , added: param.value}
    })
    .done(function(e) {
        console.log(e);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}