$(() => {

    /* Плавное перемещение к якорю */
    $("a[href^=#]").click(function (e) {
        e.preventDefault();

        let dest = $(this).attr('href');

        $('html, body').animate({scrollTop: $(dest).offset().top - 80}, 350, () => {
            window.location.hash = dest;
        });
    });

    /* Отслеживание активного заголовка */
    let headers = $('section.content .inner h2, section.content .inner h3');
    let headers_amount = headers.length;

    let should_track = ($('body').hasClass('single-chapter') || $('body').hasClass('single-article')) && headers_amount !== 0;

    $(window).on('DOMContentLoaded load resize scroll', () => {
        if(!should_track) return;

        let edge = window.scrollY + 230;

        let anchor = '#' + get_active_header(edge).getAttribute('id');

        $('.toc-item').removeClass('_reading');
        $(`.toc-item[href='${anchor}']`).addClass('_reading');
    });

    /**
     * Бинарный поиск активного заголовка
     *
     * @param edge_Y
     */
    function get_active_header(edge_Y) {
        let left_index = 0;
        let right_index = headers_amount;
        let active_header_i = 0;

        while(left_index < right_index) {
            let middle_i = (left_index + right_index)/2|0;
            let middle_header = headers.get(middle_i);
            let middle_header_Y = middle_header.getBoundingClientRect().top + window.scrollY;

            if(edge_Y <= middle_header_Y) {
                right_index = middle_i;
            } else {
                active_header_i = middle_i;
                left_index = middle_i + 1;
            }
        }

        return headers.get(active_header_i);
    }

});