document.addEventListener('DOMContentLoaded', function(){
    
    // =========================================
    // 1. HAMBURGER TOGGLE (Purana code)
    // =========================================
    const toggle = document.getElementById('navToggle');
    const links = document.getElementById('navLinks');
    const nav = document.getElementById('siteNav');
    
    toggle?.addEventListener('click', () => {
        toggle.classList.toggle('open');
        links.classList.toggle('open');
    });
    
    links?.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
        toggle.classList.remove('open');
        links.classList.remove('open');
    }));

    // =========================================
    // 2. SCROLL SHADOW (Purana code)
    // =========================================
    window.addEventListener('scroll', () => {
        nav.style.boxShadow = window.scrollY > 30 ? '0 4px 20px rgba(0,0,0,.25)' : '0 1px 3px rgba(0,0,0,0.08)';
    });

    // =========================================
    // 3. SMART STACKING LOGIC (Naya code)
    // Jab tak teeno items fit hain, row me rakho.
    // Jab fit na ho, column (stacked) kar do.
    // =========================================
    const topStrip = document.getElementById('topStrip');
    if (topStrip) {
        const items = topStrip.querySelectorAll('.info-item');

        function checkFit() {
            // Pehle reset karo
            topStrip.classList.remove('stacked');
            
            // Agar screen bahut chhoti hai (phone), to seedha stack kar do
            if (window.innerWidth < 768) {
                topStrip.classList.add('stacked');
                return;
            }

            // Calculate karo ki teeno items ki total width kitni hai
            let totalWidth = 0;
            items.forEach(item => {
                totalWidth += item.offsetWidth;
            });

            // Agar total width + padding screen se zyada hai, to stack kar do
            // (80px padding + 40px gap buffer)
            if (totalWidth + 120 > window.innerWidth) {
                topStrip.classList.add('stacked');
            }
        }

        // Page load par check karo
        checkFit();

        // Window resize par check karo
        window.addEventListener('resize', checkFit);
    }
});