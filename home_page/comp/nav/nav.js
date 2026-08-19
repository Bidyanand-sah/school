document.addEventListener('DOMContentLoaded', function(){
  const toggle=document.getElementById('navToggle');
  const links=document.getElementById('navLinks');
  const nav=document.getElementById('siteNav');
  toggle?.addEventListener('click',()=>{toggle.classList.toggle('open');links.classList.toggle('open');});
  links?.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{toggle.classList.remove('open');links.classList.remove('open');}));
  window.addEventListener('scroll',()=>{nav.style.boxShadow=window.scrollY>30?'0 4px 20px rgba(0,0,0,.25)':'none';});
});