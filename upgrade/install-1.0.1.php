<?php
/**
 *  2009-2025 Tecnoacquisti.com
 *
 *  For support feel free to contact us on our website at https://www.tecnoacquisti.com
 *
@author    Arte e Informatica <helpdesk@tecnoacquisti.com>
 *  @copyright 2009-2025 Arte e Informatica
 *  @license   One Paid Licence By WebSite Using This Module. No Rent. No Sell. No Share.
 *  @version   1.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_0_1($module)
{
    if (!is_object($module) || !method_exists($module, 'registerHook')) {
        return false;
    }

    return (bool) $module->registerHook('displayHeader');
}
