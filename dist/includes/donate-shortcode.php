<?php

function neuralnet_donation_form() {

    $label = md5($_SERVER["REMOTE_ADDR"] + time());

    ob_start();
?>

    <form id="donation-form" method="post" action="https://money.yandex.ru/quickpay/confirm.xml">

        <!-- Получатель платежа -->
        <input type="hidden" name="receiver" value="410011473013839">

        <!-- Тип транзакции -->
        <input type="hidden" name="quickpay-form" value="donate">

        <!-- Назначение платежа -->
        <input type="hidden" name="targets" value="На поддержку портала">

        <!-- Название платежа в истории отправителя -->
        <input type="hidden" name="formcomment" value="Радько Петр">

        <!-- Название платежа на странице подтверждения -->
        <input type="hidden" name="short-dest" value="Поддержка портала &#171;Нейронные сети&#187;">

        <!-- Метка платежа -->
        <input type="hidden" name="label" value="<?php echo $label; ?>">

        <!-- Способ оплаты -->
        <input type="hidden" name="paymentType" value="AC">

        <!-- ||| Визуальная часть формы ||| -->

        <div class="sum-and-comment">
            <label><input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="sum" min="50" value="100" title="Сумма для перевода"><i class="ruble"></i></label>
            <input type="text" name="comment" maxlength="200" placeholder="Комментарий...">
        </div>

        <div class="payment-types">

            <div class="payment-type card _selected" title="VISA, MasterCard, Maestro"><img src="<?php echo NEURALNET_IMAGES . 'payment-types/card.png'; ?>"></div>
            <div class="payment-type yandex-money" title="Яндекс.Деньги"><img src="<?php echo NEURALNET_IMAGES . 'payment-types/yandex-money.png'; ?>"></div>
            <div class="payment-type phone" title="MTC, Билайн, Tele2"><img src="<?php echo NEURALNET_IMAGES . 'payment-types/phone.png'; ?>"></div>

        </div>

        <button type="submit"><i class="thumbs-o-up"></i><span>Поддержать портал!</button>

    </form>

    <script>
        (function($) {

            $('.payment-type').click(function (e) {
                // Получение payment-type
                var payment_type = $(e.target).closest('.payment-type');

                if(payment_type.hasClass('_selected')) {
                    return;
                }

                $('.payment-type').removeClass('_selected');
                payment_type.addClass('_selected');

                payment_type_change(get_payment_type_from_classes(payment_type.attr('class')));
            });

            function get_payment_type_from_classes(classes) {
                return classes.replace('payment-type', '').replace('_selected', '').trim();
            }

            function payment_type_change(type) {
                switch (type) {
                    case 'card':
                        set_input_payment_type('AC');
                        break;
                    case 'yandex-money':
                        set_input_payment_type('PC');
                        break;
                    case 'phone':
                        set_input_payment_type('MC');
                        break;
                }
            }

            function set_input_payment_type(type) {
                $('input[name=paymentType]').attr('value', type);
            }

        })(jQuery);
    </script>

<?
    return ob_get_clean();
}
add_shortcode('donation-form', 'neuralnet_donation_form');