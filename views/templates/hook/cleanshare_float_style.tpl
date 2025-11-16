{*
**
*  2009-2025 Arte e Informatica
*
*  For support feel free to contact us on our website at http://www.tecnoacquisti.com
*
*  @author    Arte e Informatica <helpdesk@tecnoacquisti.com>
*  @copyright 2009-2025 Arte e Informatica
*  @version   1.0.0
*  @license   One Paid Licence By WebSite Using This Module. No Rent. No Sell. No Share.
*
*}

{*
**
*  2009-2025 Arte e Informatica
*
*  For support feel free to contact us on our website at http://www.tecnoacquisti.com
*
*  @author    Arte e Informatica <helpdesk@tecnoacquisti.com>
*  @copyright 2009-2025 Arte e Informatica
*  @version   1.0.1
*  @license   One Paid Licence By WebSite Using This Module. No Rent. No Sell. No Share.
*
*}
<style>
  button#cleanshare-btn {
    background: {$float_bg|escape:'html':'UTF-8'} !important;
    color: {$float_text|escape:'html':'UTF-8'} !important;
  }

  button#cleanshare-btn:hover {
    background: {$float_bg_hover|escape:'html':'UTF-8'} !important;
    color: {$float_text_hover|escape:'html':'UTF-8'} !important;
  }
</style>

{if $float_enabled}
  <style>
    /* CleanShare floating button positioning */
    #share-float {
      position: fixed;
      bottom: {$float_bottom|intval}px;
      z-index: {$float_zindex|intval};
    {if $float_position == 'left'}
      left: 20px;
      right: auto;
      transform: none;
    {elseif $float_position == 'centered'}
      left: 50%;
      right: auto;
      transform: translateX(-50%);
    {else}
      right: 20px;
      left: auto;
      transform: none;
    {/if}
    }
  </style>
{/if}

