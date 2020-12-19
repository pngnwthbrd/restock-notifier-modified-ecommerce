<?php

if (
    defined('MODULE_SYSTEM_RESTOCK_NOTIFIER_STATUS')
    && MODULE_SYSTEM_RESTOCK_NOTIFIER_STATUS == 'true'
    && STOCK_ALLOW_CHECKOUT == 'false'
    && $product->data['products_quantity'] < 1
) {
    $info_smarty->assign('restock_notifier_info', RESTOCK_NOTIFIER_INFO);
    $info_smarty->assign('restock_notifier_button', '<a id="restock-notifier-button" href="#" class="button"><span class="cssButton cssButtonColor4">' . RESTOCK_NOTIFIER_BUTTON_TXT . '</span></a>');

    $info_smarty->assign('privacy_link', $main->getContentLink(2, MORE_INFO,'SSL'));
    $info_smarty->assign('privacy_checkbox', '<input type="checkbox" value="privacy_accept" name="privacy" id="privacy" />');
}
