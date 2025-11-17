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
  // supporta sia il markup vecchio (id) sia quello nuovo (classi)
  var nodeList = document.querySelectorAll('.cleanshare-btn, #cleanshare-btn');

  // deduplica eventuali doppioni (es. id + classe sullo stesso elemento)
  var buttons = Array.prototype.filter.call(nodeList, function (el, index) {
    return Array.prototype.indexOf.call(nodeList, el) === index;
  });

  if (!buttons.length) {
    return;
  }

  // traduzioni da window.cleanshare.translations (settate da cleanshare_page.tpl)
  var translations = (window.cleanshare && window.cleanshare.translations) || {};

  function t(key, value) {
    var raw = translations[key];
    var val = typeof value === 'undefined' ? '' : String(value);

    if (typeof raw === 'string') {
      return raw.replace(/%s/g, val);
    }

    // fallback hardcoded in inglese
    var fallbacks = {
      copy_failed: 'Copy failed. URL: %s',
      copy_not_supported: 'Copy not supported. URL: %s',
      url_copied: 'URL copied to clipboard'
    };

    return (fallbacks[key] || key).replace(/%s/g, val);
  }

  buttons.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();

      var url = btn.dataset.cleanUrl || btn.getAttribute('data-clean-url');
      var title = document.title || '';
      //var text = btn.getAttribute('aria-label') || '';

      if (!url) {
        console.warn('CleanShare: no clean url provided');
        return;
      }

      // Web Share API (mobile / browser moderni)
      if (navigator.share) {
        navigator.share({
          title: title,
          url: url
        }).then(function () {
          // opzionale: feedback
        }).catch(function () {
          // utente ha annullato o errore -> fallback copia
          fallbackCopy(url, btn);
        });
        return;
      }

      // fallback: copia negli appunti
      fallbackCopy(url, btn);
    });
  });

  function fallbackCopy(textToCopy, btn) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(textToCopy).then(function () {
        showToast(btn, textToCopy);
      }).catch(function () {
        legacyCopy(textToCopy, btn);
      });
    } else {
      legacyCopy(textToCopy, btn);
    }
  }

  function legacyCopy(text, btn) {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.left = '-9999px';
    document.body.appendChild(ta);
    ta.focus();
    ta.select();

    try {
      var successful = document.execCommand('copy');
      if (successful) {
        showToast(btn, text);
      } else {
        alert(t('copy_failed', text));
      }
    } catch (err) {
      alert(t('copy_not_supported', text));
    }

    document.body.removeChild(ta);
  }

  function showToast(btn, url) {
    var message = t('url_copied', url);

    // 1) prova a trovare un toast "vicino" al bottone
    var toast = null;
    if (btn.closest) {
      var wrapper = btn.closest('.cleanshare-wrapper');
      if (wrapper) {
        toast = wrapper.querySelector('.cleanshare-toast');
      }
    }

    // 2) fallback: toast globale con id (markup vecchio)
    if (!toast) {
      toast = document.getElementById('cleanshare-toast');
    }

    // 3) se ancora nulla, usa alert
    if (!toast) {
      alert(message);
      return;
    }

    // aggiorna il testo con la traduzione
    if ('textContent' in toast) {
      toast.textContent = message;
    } else {
      toast.innerText = message;
    }

    // animazione stile file originale
    toast.style.display = 'block';
    toast.style.opacity = '0';
    toast.style.transition = 'none';

    setTimeout(function () {
      toast.style.transition = 'opacity 300ms';
      toast.style.opacity = '1';
    }, 10);

    setTimeout(function () {
      toast.style.opacity = '0';
      setTimeout(function () {
        toast.style.display = 'none';
      }, 300);
    }, 2000);
  }
});


