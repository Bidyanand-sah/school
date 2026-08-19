document.addEventListener('DOMContentLoaded', function(){
  const stats=document.querySelectorAll('.stat-box .num');
  if(!stats.length) return;
  const animate=(el)=>{
    const target=parseInt(el.dataset.count,10)||0;
    let current=0;
    const step=Math.max(1,Math.ceil(target/60));
    const timer=setInterval(()=>{
      current+=step;
      if(current>=target){current=target;clearInterval(timer);}
      el.textContent=current+(el.dataset.suffix||'');
    },20);
  };
  const observer=new IntersectionObserver((entries)=>{
    entries.forEach(entry=>{
      if(entry.isIntersecting){animate(entry.target);observer.unobserve(entry.target);}
    });
  },{threshold:.4});
  stats.forEach(el=>observer.observe(el));
});