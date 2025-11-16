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

{if $float_enabled}
  <style>
    button#cleanshare-btn {
      background: {$float_bg} !important;
      color: {$float_text} !important;
    }

    button#cleanshare-btn:hover {
      background: {$float_bg_hover} !important;
      color: {$float_text_hover} !important;
    }
    /* CleanShare floating button positioning */
    #share-float {
      position: fixed;
      bottom: {$float_bottom}px;
      z-index: {$float_zindex};
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
