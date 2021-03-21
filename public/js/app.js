$(function () {
    
    // Slideshow 4
    $('#slider4').responsiveSlides({
        auto: true,
        pager: true,
        nav: true,
        speed: 500,
        namespace: 'callbacks',
        before: function () {
            $('.events').append('<li>before event fired.</li>');
        },
        after: function () {
            $('.events').append('<li>after event fired.</li>');
        },
    });
    
    // Слайдер
    $('.flexslider').flexslider({
        animation: 'slide',
        controlNav: 'thumbnails',
    });
    
    // Аккордион
    var menu_ul = $('.menu_drop > li > ul'),
        menu_a  = $('.menu_drop > li > a');
    
    menu_ul.hide();
    menu_a.click(function (e) {
        e.preventDefault();
        if ( ! $(this).hasClass('active')) {
            menu_a.removeClass('active');
            menu_ul.filter(':visible').slideUp('normal');
            $(this).addClass('active').next().stop(true, true).slideDown('normal');
        } else {
            $(this).removeClass('active');
            $(this).next().stop(true, true).slideUp('normal');
        }
    });
    
    
    // Переключение валют
    $('[data-js="currency"]').change(function () {
        window.location = '/currency/change?curr=' + $(this).val();
    });
    
    
    // Выбор модификации
    $('[data-js="product-mod"]').change(function () {
        
        let mod_id     = $(this).val(),
            color      = $(this).find('option').filter(':selected').data('mod-color'),
            price      = $(this).find('option').filter(':selected').data('mod-price'),
            base_price = $('[data-js="base-price"]').data('base-price');
        
        if (price) {
            $('[data-js="base-price"]').text(symbol_left + price + symbol_right);
        } else {
            $('[data-js="base-price"]').text(symbol_left + base_price + symbol_right);
        }
        
    });
    
    
    // Корзина
    $('body').on('click', '.add-to-cart-link', function (e) {
        e.preventDefault();
        
        let id  = $(this).data('id'),
            qty = $('.quantity input').val() ? $('.quantity input').val() : 1,
            mod = $('[data-js="product-mod"]').val();
        
        $.ajax({
            url: '/cart/add',
            data: {id: id, qty: qty, mod: mod},
            type: 'GET',
            success: function (res) {
                if (res !== 'error') {
                    showCart(res);
                } else {
                    alert('Ошибка! Попробуйте позже');
                }
            },
            error: function () {
                alert('Ошибка! Попробуйте позже');
            },
        });
    });
    
    function showCart(res) {
        console.log(res);
    }
    
});


















