<script>
(function () {
  const message = 'Akses ini dinonaktifkan untuk menjaga keamanan halaman.';

  function showBlockedMessage() {
    if (window.__antiInspectToast) {
      clearTimeout(window.__antiInspectToast);
    }

    let toast = document.getElementById('antiInspectToast');

    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'antiInspectToast';
      toast.innerHTML = message;
      document.body.appendChild(toast);
    }

    toast.classList.add('show');

    window.__antiInspectToast = setTimeout(function () {
      toast.classList.remove('show');
    }, 1800);
  }

  document.addEventListener('contextmenu', function (e) {
    e.preventDefault();
    showBlockedMessage();
  });

  document.addEventListener('keydown', function (e) {
    const key = String(e.key || '').toLowerCase();

    const blocked =
      key === 'f12' ||
      (e.ctrlKey && key === 'u') ||
      (e.ctrlKey && key === 's') ||
      (e.ctrlKey && key === 'i') ||
      (e.ctrlKey && e.shiftKey && key === 'i') ||
      (e.ctrlKey && e.shiftKey && key === 'j') ||
      (e.ctrlKey && e.shiftKey && key === 'c');

    if (blocked) {
      e.preventDefault();
      e.stopPropagation();
      showBlockedMessage();
      return false;
    }
  }, true);

  document.addEventListener('dragstart', function (e) {
    e.preventDefault();
  });

  document.addEventListener('selectstart', function (e) {
    const tag = e.target.tagName.toLowerCase();

    if (!['input', 'textarea'].includes(tag)) {
      e.preventDefault();
    }
  });
})();
</script>

<style>
  #antiInspectToast{
    position:fixed;
    left:50%;
    bottom:92px;
    z-index:999999;
    max-width:calc(100% - 28px);
    min-height:44px;
    padding:0 15px;
    border-radius:999px;

    display:flex;
    align-items:center;
    justify-content:center;

    color:#06110e;
    background:
      radial-gradient(circle at 30% 0%, rgba(255,255,255,.62), transparent 34%),
      linear-gradient(135deg, #00DF82, #72ffab);

    box-shadow:
      0 18px 42px rgba(0,0,0,.34),
      0 0 28px rgba(0,223,130,.18);

    font-family:Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-size:12px;
    font-weight:850;
    text-align:center;
    white-space:nowrap;

    opacity:0;
    pointer-events:none;
    transform:translateX(-50%) translateY(12px);
    transition:.22s ease;
  }

  #antiInspectToast.show{
    opacity:1;
    transform:translateX(-50%) translateY(0);
  }

  body{
    -webkit-user-select:none;
    user-select:none;
  }

  input,
  textarea,
  [contenteditable="true"]{
    -webkit-user-select:text;
    user-select:text;
  }
</style>