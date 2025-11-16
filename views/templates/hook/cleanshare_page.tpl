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

{capture name='copy_failed'}{l s='Copy failed. URL: %s' js=1 mod='cleanshare'}{/capture}
{capture name='copy_not_supported'}{l s='Copy not supported. URL: %s' js=1 mod='cleanshare'}{/capture}
{capture name='url_copied'}{l s='URL copied to clipboard' js=1 mod='cleanshare'}{/capture}

<script type="text/javascript">
  window.cleanshare = window.cleanshare || {};
  window.cleanshare.translations = {
    copy_failed: {$smarty.capture.copy_failed|@json_encode nofilter},
    copy_not_supported: {$smarty.capture.copy_not_supported|@json_encode nofilter},
    url_copied: {$smarty.capture.url_copied|@json_encode nofilter}
  };
</script>
