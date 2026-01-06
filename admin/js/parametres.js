(function(){
  function byId(id){ return document.getElementById(id); }
  function toggle(btn){
    var id = btn.getAttribute('data-target');
    var input = byId(id); if(!input) return;
    var isPwd = input.type === 'password';
    input.type = isPwd ? 'text' : 'password';

    // icône œil barré si caché, non barré si visible
    var icon = btn.querySelector('i');
    if(icon){
      if(isPwd){
        // on vient d'afficher -> œil non barré
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        btn.setAttribute('aria-label','Masquer le mot de passe');
      } else {
        // on vient de cacher -> œil barré
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        btn.setAttribute('aria-label','Afficher le mot de passe');
      }
    }
    btn.setAttribute('aria-pressed', (isPwd ? 'true' : 'false'));
  }
  Array.prototype.forEach.call(document.querySelectorAll('.param-toggle-pass'), function(b){
    b.addEventListener('click', function(){ toggle(b); });
  });

  var nouveau = byId('mdp_nouveau');
  var confirm = byId('mdp_confirm');
  var ruleLen = byId('ruleLength');
  var ruleUp  = byId('ruleUpper');
  var ruleSp  = byId('ruleSpecial');

  function setState(el, ok){
    if(!el) return;
    el.classList.remove(ok? 'invalid':'valid');
    el.classList.add(ok? 'valid':'invalid');
  }
  function validate(){
    var v = (nouveau && nouveau.value) ? nouveau.value : '';
    setState(ruleLen, v.length >= 8);
    setState(ruleUp, /[A-Z]/.test(v));
    setState(ruleSp, /[^A-Za-z0-9]/.test(v));
    if(confirm && confirm.value.length){
      if(confirm.value !== v){
        confirm.classList.add('is-invalid');
      } else {
        confirm.classList.remove('is-invalid');
      }
    }
  }
  if(nouveau){ nouveau.addEventListener('input', validate); validate(); }
  if(confirm){ confirm.addEventListener('input', validate); }
})();

