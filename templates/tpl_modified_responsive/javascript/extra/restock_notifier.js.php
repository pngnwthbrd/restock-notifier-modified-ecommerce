<?php

if (
    STOCK_ALLOW_CHECKOUT == 'false'
    && $product->data['products_quantity'] < 1
) {
    $restock_notifier_smarty = new Smarty();

    $privacy_link = '<a rel="nofollow" href="/shop_content.php?coID=2" target="_blank">' . PRIVACY_LINK_TXT . '</a>';
    $restock_notifier_smarty->assign('privacy_info', sprintf(PRIVACY_ACCEPT_CHECKBOX_TXT, $privacy_link));
    $restock_notifier_smarty->assign('privacy_checkbox', '<input type="checkbox" value="privacy" name="privacy" id="privacy" />');
    $html = $restock_notifier_smarty->fetch(CURRENT_TEMPLATE.'/module/restock_notifier/product_info_form.html');

    ?>
    <script type="text/javascript" src="/templates/<?= CURRENT_TEMPLATE; ?>/javascript/jquery.simplelightbox.min.js"></script>

    <script type="text/javascript">
    $('#restock-notifier-button').on('click', function(e)
    {
        e.preventDefault();

        $.simplelightbox('open', '<?= $html; ?>');
        $('.errormessage').hide();

        $('#restock-notifier-form form').on('submit', function(v)
        {
            v.preventDefault();
            var products_id = $('input[name="products_id"]').val(),
            email = $('input[name="restock-notifier-email"]').val(),
            err = false;

            if ($('input[name="privacy"]').is(':checked'))
                var privacy = true;
            else
            {
                var privacy = false;
                err = '<?= RESTOCK_NOTIFIER_PRIVACY_ERR; ?><br />';

            }

            if (typeof email == 'undefined' || email == '')
                err += '<?= RESTOCK_NOTIFIER_EMAIL_ERR; ?>';

            if (typeof err !== 'undefined' && err !== false)
            {
                $('.errormessage').html(err).show();
                return 0;
            }

            $.ajax({
                url: '/callback/restock_notifier/restock_notifier_callback.php',
                data: {
                    'restock_notifier': 'new_entry',
                    'products_id': products_id,
                    'email': email,
                    'privacy': privacy
                },
                type: 'POST',
                success: function(response)
                {
                    $.simplelightbox('close');
                    $.simplelightbox('open', $.parseJSON(response));
                }
            });
        });
    });
</script>';

<?php
}
