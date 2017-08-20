$(() => {
    /* -------------------------------------------------------------------------------------------------------------- */
    /* Тень от шапки при прокрутке страницы */
    /* -------------------------------------------------------------------------------------------------------------- */

    let header = $('header');
    $(window).scroll(() => {
        let is_scrolled = $(window).scrollTop() > 0;

        if(is_scrolled) {
            header.addClass('_scrolling');
        } else {
            header.removeClass('_scrolling');
        }
    });

    /* -------------------------------------------------------------------------------------------------------------- */
    /* Подсветка левой границы кнопки поиска */
    /* -------------------------------------------------------------------------------------------------------------- */

    let search_form = $('header .search-form, .search-form-sidebar');
    let search_field = search_form.find('.search-field');
    search_field.focus(() => {
        search_form.addClass('_focus');
    }).blur(() => {
        search_form.removeClass('_focus');
    });

    /* -------------------------------------------------------------------------------------------------------------- */
    /* Расширение поля поиска */
    /* -------------------------------------------------------------------------------------------------------------- */

    let main_menu = $('header .main-menu');
    search_field.focus(() => {
        main_menu.animate({
            width: 0,
            opacity: '0'
        }, 200);
    }).blur(() => {
        main_menu.animate({
            width: main_menu.get(0).scrollWidth,
            opacity: 1
        }, 200, () => {
            main_menu.css('width', '');
        });
    });

    /* -------------------------------------------------------------------------------------------------------------- */
    /* Поведение подменю */
    /* -------------------------------------------------------------------------------------------------------------- */

    let submenu_timeout;
    let submenu_hide_timeout;
    header.find('.has-submenu').hover(
        (e) => {
            let container = $(e.target).closest('.has-submenu');
            let submenu = container.find('.submenu');
            let menu_item = container.find('.menu-item');

            if(submenu.hasClass('_showing')) {
                clearTimeout(submenu_hide_timeout);
                return;
            }

            submenu_timeout = setTimeout(() => {
                submenu.addClass('_showing').fadeIn(200);
                menu_item.addClass('_submenu-showing');
            }, 200);
        },
        (e) => {
            let container = $(e.target).closest('.has-submenu');
            let submenu = container.find('.submenu');
            let menu_item = container.find('.menu-item');

            clearTimeout(submenu_timeout);

            if(submenu.hasClass('_showing')) {
                submenu_hide_timeout = setTimeout(() => {
                    submenu.removeClass('_showing').fadeOut(200);
                    menu_item.removeClass('_submenu-showing');
                }, 200);
            }
        }
    ).click((e) => {
        let container = $(e.target).closest('.has-submenu');
        let submenu = container.find('.submenu');
        let menu_item = container.find('.menu-item');

        if(submenu.hasClass('_showing')) {
            submenu.removeClass('_showing').fadeOut(200);
            menu_item.removeClass('_submenu-showing');
        } else {
            submenu.addClass('_showing').fadeIn(200);
            menu_item.addClass('_submenu-showing');
        }
    });
});