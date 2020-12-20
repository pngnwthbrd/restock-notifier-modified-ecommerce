<?php
#!/usr/bin/php

/**
 * restock_notifier cron file
 *
 * crontab example:
 *
 * crontab -e
 * 00 00 * * * /usr/bin/curl http(s)://(www.)shop-url.(com|de|at|it|ru|co)/restock_notifier.cron.php
 *
 */

include 'includes/application_top.php';


$notify = array();
$notify_q = xtc_db_query('SELECT * FROM restock_notifier');

if (xtc_db_num_rows($notify_q)) {
    $module_smarty = new Smarty();
    $template = CURRENT_TEMPLATE .
                '/mail/' . $_SESSION['language'] . '/restock_notifier.html';

    while ($row = xtc_db_fetch_array($notify_q)) {
        array_push($notify, $row);
    }

    $customers_notified = 0;
    foreach ($notify as $subject) {
        $p_id = $subject['products_id'];
        $customers_email = $subject['email'];

        $product = new product($p_id);
        $products_qty = (int) $product->data['products_quantity'];

        if ($products_qty > 0) {
            // notify customer
            $module_smarty->assign('$product', $product);
            $html_mail = $module_smarty->fetch($template);

            xtc_php_mail(
                        STORE_OWNER_EMAIL_ADDRESS, STORE_NAME,
                        $customers_email, $to_name = '', $forwarding_to = '',
                        STORE_OWNER_EMAIL_ADDRESS, STORE_NAME,
                        $path_to_attachments = '', $path_to_more_attachments = '',
                        RESTOCK_NOTIFIER_EMAIL_SUBJECT, $html_mail, $message_body_plain = '',
                        $priority = null
            );

            $customers_notified++;
        }
    }
}

echo json_encode(array('customers_notified' => $customers_notified));

die();
