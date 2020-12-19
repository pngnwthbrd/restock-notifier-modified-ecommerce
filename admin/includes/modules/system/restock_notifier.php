<?php
/**
 * restock_notifier
 * @author Daniel Kuester (pngnwthbrd</>)
 * @github: https://github.com/pngnwthbrd/restock-notifier-modified-ecommerce
 */

defined( '_VALID_XTC' ) or die( 'Direct Access to this location is not allowed.' );

class restock_notifier
{
    var $code, $title, $description, $enabled;

    function __construct()
    {
        $this->code = 'restock_notifier';
        $this->title = MODULE_SYSTEM_RESTOCK_NOTIFIER_TEXT_TITLE;
        $this->description = MODULE_SYSTEM_RESTOCK_NOTIFIER_TEXT_DESCRIPTION;
        $this->enabled = ((defined('MODULE_SYSTEM_RESTOCK_NOTIFIER_STATUS') && MODULE_SYSTEM_RESTOCK_NOTIFIER_STATUS == 'true') ? true : false);
        $this->sort_order = '';
    }

    function process()
    {
        // nothing to do
    }

    function display()
    {
        return array('text' => '<br>' . xtc_button(BUTTON_SAVE) . '&nbsp;' .
                               xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT,
                               'set=' . $_GET['set'] . '&module='.$this->code)));
    }

    function check()
    {
        if(!isset($this->_check)) {
          $check_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_SYSTEM_RESTOCK_NOTIFIER_STATUS'");
          $this->_check = xtc_db_num_rows($check_query);
        }
        return $this->_check;
    }

    function install()
    {
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SYSTEM_RESTOCK_NOTIFIER_STATUS', 'true',  '6', '1', 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");

        // create email restock notifier module table
        xtc_db_query('CREATE TABLE IF NOT EXISTS restock_notifier(
                        products_id int(11) NOT NULL,
                        email varchar(50))');

        xtc_db_query('ALTER TABLE restock_notifier
                        ADD PRIMARY KEY (products_id)');
    }

    function remove()
    {
        xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key LIKE 'MODULE_SYSTEM_RESTOCK_NOTIFIER_%'");

        // remove email restock notifier module table
        xtc_db_query('DROP TABLE restock_notifier');
    }

    function keys()
    {
        return array('MODULE_SYSTEM_RESTOCK_NOTIFIER_STATUS');
    }
}
