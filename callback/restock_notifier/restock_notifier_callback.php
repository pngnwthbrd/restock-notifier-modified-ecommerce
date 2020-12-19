<?php

chdir('../../');
require_once('includes/application_top.php');

if (
    isset($_POST['restock_notifier']) && $_POST['restock_notifier'] == 'new_entry'
) {
    $response = "";
    if (isset($_POST['privacy']) && $_POST['privacy'] == true) {
        $check_q = xtc_db_query('select * from restock_notifier where email="' . $_POST['email'] . '"
        and products_id=' . (int) $_POST['products_id']);

        if (xtc_db_num_rows($check_q) < 1) {
            xtc_db_query('insert into restock_notifier(email, products_id)
            values ("' . $_POST['email'] . '", "' . $_POST['products_id'] . '")');

            $response = RESTOCK_NOTIFIER_SUCCESSFULL;
        } else {
            $response = RESTOCK_NOTIFIER_FAILURED;
        }
    } else {
        $response = RESTOCK_NOTIFIER_PRIVACY_ERR;
    }
    
    echo json_encode($response);
    die;
}
