$(() => {
    let sidebar = $('aside');
    let sidebar_callers = $('.sidebar-button');
    let content = $('section.content');

    let is_opened = false;
    let is_moving = false;

    let touch_enabled = false;

    let open_tolerance = 20;

    let sidebar_width;

    let hammer_content = null, hammer_sidebar = null;

    $(window).resize(() => {
        sidebar_width = sidebar.outerWidth();

        /* @todo Корректная проверка на ширину окна */
        if (window.matchMedia("(max-width: 800px)").matches) {
            enable_touch();
        } else {
            disable_touch();
        }
    });

    /* Проверка при загрузке страницы */
    /* @todo Корректная проверка на ширину окна */
    if (window.matchMedia("(max-width: 800px)").matches) {
        enable_touch();
    } else {
        disable_touch();
    }

    sidebar_callers.click(() => {
        toggle_sidebar();
    });
    $(document).click((e) => {
        let target = $(e.target);

        if(target.closest('.sidebar-button').length === 0 && target.closest('aside').length === 0) {
            close_sidebar();
        }
    });

    function open_sidebar() {
        clean_sidebar();

        sidebar_callers.addClass('_sidebar-showing');
        content.addClass('_darker');
        sidebar.addClass('_sidebar-showing');

        is_opened = true;
    }

    function close_sidebar() {
        clean_sidebar();

        sidebar_callers.removeClass('_sidebar-showing');
        content.removeClass('_darker');
        sidebar.removeClass('_sidebar-showing');

        is_opened = false;
    }

    function enable_moving() {
        content.addClass('_sidebar-moving');
        sidebar.addClass('_moving');

        is_moving = true;
    }

    function disable_moving() {
        content.removeClass('_sidebar-moving');
        sidebar.removeClass('_moving');

        is_moving = false;
    }

    /** Полная очистка навигации от любых инлайновых стилей */
    function clean_sidebar() {
        content.css('filter', '');

        if(sidebar.hasClass('toc'))
            sidebar.attr('class', 'toc').css('left', '');
        else
            sidebar.removeClass().css('left', '');
    }

    /* Открытие/закрытие сайдабара */
    function toggle_sidebar() {
        if(sidebar.hasClass('_sidebar-showing')) {
            close_sidebar();
        } else {
            open_sidebar();
        }
    }

    /* Находится ли заданное число в данном диапозоне */
    function between(value, range_a, range_b) {
        let min, max;

        if(range_a > range_b) {
            min = range_b;
            max = range_a;
        } else {
            min = range_a;
            max = range_b;
        }

        return value >= min && value <= max;
    }

    /* Перемещение края сайдбара вслед за пальцем */
    function sidebar_right(value = null) {
        if(value === null) {
            let sidebar_pos = sidebar.position();

            return sidebar_pos.left + sidebar_width;
        } else {
            sidebar.css('left', `${value - sidebar_width}px`);
        }
    }

    /** Затемнение фона при открытии/закрытии сайдбара */
    function darker_step() {
        let brightness = 1-(sidebar_right()/(2*sidebar_width));

        content.css('filter', `brightness(${brightness})`);
    }

    function disable_touch() {
        touch_enabled = false;
    }

    function enable_touch() {
        touch_enabled = true;
    }

    delete Hammer.defaults.cssProps.userSelect;

    sidebar_width = sidebar.outerWidth();

    hammer_content = new Hammer(content.get(0), {
        dragLockToAxis: true,
        dragBlockHorizontal: true
    });
    hammer_sidebar = new Hammer(sidebar.get(0), {
        dragLockToAxis: true,
        dragBlockHorizontal: true
    });

    /* Открытие навигации свайпом */
    hammer_content.on('swiperight', e => {
        if(!touch_enabled) return;

        let start_X = e.changedPointers[0].clientX - e.deltaX;

        if(between(start_X, 0, open_tolerance)) {
            open_sidebar();
        }
    });

    /* Закрытие навигации свайпом */
    hammer_sidebar.on('swipeleft', () => {
        if(!touch_enabled) return;

        close_sidebar();
    });

    /* Открытие навигации перемещением */
    hammer_content.on('panleft panright', e => {
        if(!touch_enabled) return;

        if(is_opened) {
            return;
        }

        if(!is_moving) {
            if(between(e.changedPointers[0].clientX - e.deltaX, 0, open_tolerance)) {
                enable_moving();
            }
        } else {
            darker_step();
            sidebar_right((e.changedPointers[0].clientX > sidebar_width ? sidebar_width : e.changedPointers[0].clientX));
        }
    });

    /* Закрытие навигации перемещением */
    hammer_sidebar.on('panleft panright', e => {
        if(!touch_enabled) return;

        if(e.isFinal) {
            return;
        }

        if(!is_moving) {
            enable_moving();
        } else {
            darker_step();
            sidebar_right((sidebar_width + e.deltaX > sidebar_width ? sidebar_width : sidebar_width + e.deltaX));
        }
    });

    /* Прекращение открытия навигации */
    hammer_content.on('panend pancancel', () => {
        if(!touch_enabled) return;

        disable_moving();

        if(sidebar.hasClass('_sidebar-showing')) {
            return;
        }

        if(sidebar_right() > sidebar_width/2) {
            open_sidebar();
        } else {
            close_sidebar();
        }
    });

    /* Прекращение закрытия навигации */
    hammer_sidebar.on('panend pancancel', () => {
        if(!touch_enabled) return;

        disable_moving();

        if(!sidebar.hasClass('_sidebar-showing')) {
            return;
        }

        if(sidebar_right() < sidebar_width/2) {
            close_sidebar();
        } else {
            open_sidebar();
        }
    });
});