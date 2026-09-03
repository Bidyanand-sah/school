document.addEventListener('DOMContentLoaded', function(){
  const stats=document.querySelectorAll('.stat-box .num');
  if(!stats.length) return;

  const animate=(el)=>{
    const raw = (el.dataset.target || '').trim();
    // Value ke andar se leading number nikalo, baaki sab suffix ("+" , "%", " Cr" wagera)
    const match = raw.match(/^(\d+)(.*)$/);

    if(!match){
      // Agar admin ne pure number ke bina kuch likha hai (e.g. "New!"), animate mat karo, seedha dikha do
      el.textContent = raw;
      return;
    }

    const target = parseInt(match[1], 10) || 0;
    const suffix = match[2] || '';
    let current = 0;
    const step = Math.max(1, Math.ceil(target/60));
    const timer = setInterval(()=>{
      current += step;
      if(current >= target){ current = target; clearInterval(timer); }
      el.textContent = current + suffix;
    },20);
  };

  const observer=new IntersectionObserver((entries)=>{
    entries.forEach(entry=>{
      if(entry.isIntersecting){ animate(entry.target); observer.unobserve(entry.target); }
    });
  },{threshold:.4});
  stats.forEach(el=>observer.observe(el));
});