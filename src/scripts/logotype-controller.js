/* Контроллер анимации логотипа */

const START_ACTIVATION_RADIUS = 22.5;
const END_ACTIVATION_RADIUS = 45.8;

const neuron_attr = {
    fill: '#4492FD'
};

const line_attr = {
    fill: 'none',
    stroke: '#4492FD',
    'stroke-width': 7
};

const activation_attr = {
    fill: 'none',
    stroke: '#4492FD',
    'stroke-width': 5
};

$(() => {
    let logotype = Snap('header .logotype');

    /* Нейроны */
    logotype.circle(128, 64, 25).attr(neuron_attr).addClass('neuron top-neuron');
    logotype.circle(64, 192, 25).attr(neuron_attr).addClass('neuron left-neuron');
    logotype.circle(192, 192, 25).attr(neuron_attr).addClass('neuron right-neuron');

    /* Связи */
    logotype.line(64, 192, 128, 64).attr(line_attr).addClass('connection left-top-connection');
    logotype.line(128, 64, 192, 192).attr(line_attr).addClass('connection right-top-connection');
    logotype.line(64, 192, 192, 192).attr(line_attr).addClass('connection left-right-connection');

    /* Активационные круги */
    let top_activation = logotype.circle(128, 64, START_ACTIVATION_RADIUS).attr(activation_attr).addClass('activation-circle top-activation');
    let left_activation = logotype.circle(64, 192, START_ACTIVATION_RADIUS).attr(activation_attr).addClass('activation-circle left-activation');
    let right_activation = logotype.circle(192, 192, START_ACTIVATION_RADIUS).attr(activation_attr).addClass('activation-circle right-activation');

    /* Сигналы */
    let left_signal = logotype.circle(64, 192, 11).attr(neuron_attr).addClass('signal left-signal');
    let right_signal = logotype.circle(192, 192, 11).attr(neuron_attr).addClass('signal right-signal');

    /* Анимация */
    looping_logo_animation([left_activation, right_activation, top_activation], left_signal, right_signal);
});

function looping_logo_animation(activation_array, left_signal, right_signal) {
    $.each([activation_array[0], activation_array[1]], (index, element) => {
        element.animate({
            r: END_ACTIVATION_RADIUS,
            opacity: 0
        }, 600);
    });

    left_signal.animate({
        cx: 128,
        cy: 64
    }, 600);

    right_signal.animate({
        cx: 128,
        cy: 64
    }, 600, () => {
        $.each([activation_array[0], activation_array[1]], (index, element) => {
            element.attr({
                r: START_ACTIVATION_RADIUS,
                opacity: 1
            });
        });

        left_signal.attr({
            cx: 64,
            cy: 192
        });

        right_signal.attr({
            cx: 192,
            cy: 192
        });

        activation_array[2].animate({
            r: END_ACTIVATION_RADIUS
        }, 1200, () => {
            setTimeout(() => {
                activation_array[2].animate({
                    opacity: 0
                }, 600, () => {
                    activation_array[2].attr({
                        r: START_ACTIVATION_RADIUS,
                        opacity: 1
                    });

                    looping_logo_animation(activation_array, left_signal, right_signal)
                });
            }, 6000);
        });
    });
}