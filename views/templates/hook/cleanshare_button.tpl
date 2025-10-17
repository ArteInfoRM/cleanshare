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

<button
        type="button"
        id="cleanshare-btn"
        class="btn btn-outline-secondary"
        data-clean-url="{$cleanshare_clean_url|escape:'html':'UTF-8'}"
        aria-label="{$cleanshare_button_text|escape:'html':'UTF-8'}"
>
    <i class="material-icons">share</i> {$cleanshare_button_text|escape:'html':'UTF-8'}
</button>

<div id="cleanshare-toast">
    {l s='Copied URL to clipboard' mod='cleanshare'}
</div>
