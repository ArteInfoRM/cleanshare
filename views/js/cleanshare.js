/**
 *  2009-2025 Tecnoacquisti.com
 *
 *  For support feel free to contact us on our website at http://www.tecnoacquisti.com
 *
 *  @author    Arte e Informatica <helpdesk@tecnoacquisti.com>
 *  @copyright 2009-2025 Arte e Informatica
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  @version   2.3.0
 */

document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('cleanshare-btn');
  const toast = document.getElementById('cleanshare-toast');

  // carica traduzioni da window.cleanshare.translations (se disponibili)
  const translations = (window.cleanshare && window.cleanshare.translations) || {};

  function t(key, value) {
    const raw = translations[key];
    const val = typeof value === 'undefined' ? '' : String(value);
    if (typeof raw === 'string') {
      return raw.replace(/%s/g, val);
    }
    // fallback hardcoded in inglese
    const fallbacks = {
      copy_failed: 'Copy failed. URL: %s',
      copy_not_supported: 'Copy not supported. URL: %s',
      url_copied: 'URL copied to clipboard'
    };
    return (fallbacks[key] || key).replace(/%s/g, val);
  }

  if (!btn) return;

  btn.addEventListener('click', async function (e) {
    e.preventDefault();

    const url = btn.dataset.cleanUrl;
    const title = document.title || '';
    const text = btn.getAttribute('aria-label') || '';

    if (!url) {
      console.warn('CleanShare: no clean url provided');
      return;
    }

    // Web Share API (mobile modern browsers)
    if (navigator.share) {
      try {
        await navigator.share({
          title: title,
          text: text,
          url: url
        });
        // opzionale: feedback all'utente
      } catch (err) {
        // user cancelled or error — fallback al copia
        fallbackCopy(url);
      }
      return;
    }

    // Fallback: copia negli appunti (HTTPS richiesto)
    fallbackCopy(url);
  });

  function fallbackCopy(textToCopy) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(textToCopy).then(function () {
        showToast();
      }).catch(function (err) {
        // ultimo fallback: seleziona e copia textarea
        legacyCopy(textToCopy);
      });
    } else {
      legacyCopy(textToCopy);
    }
  }

  function legacyCopy(text) {
    const ta = document.createElement('textarea');
    ta.value = text;
    // evita scorrimenti
    ta.style.position = 'fixed';
    ta.style.left = '-9999px';
    document.body.appendChild(ta);
    ta.focus();
    ta.select();

    try {
      const successful = document.execCommand('copy');
      if (successful) showToast();
      else {
        // usa traduzione
        const msg = t('copy_failed', text);
        alert(msg);
      }
    } catch (err) {
      const msg = t('copy_not_supported', text);
      alert(msg);
    }
    document.body.removeChild(ta);
  }

  function showToast() {
    const message = t('url_copied', btn.dataset.cleanUrl);
    if (!toast) {
      alert(message);
      return;
    }
    // aggiorna contenuto del toast con la traduzione
    if ('textContent' in toast) toast.textContent = message;
    else toast.innerText = message;

    toast.style.display = 'block';
    setTimeout(() => {
      toast.style.opacity = '1';
    }, 10);
    setTimeout(() => {
      toast.style.transition = 'opacity 300ms';
      toast.style.opacity = '0';
      setTimeout(() => {
        toast.style.display = 'none';
      }, 300);
    }, 2000);
  }
});
