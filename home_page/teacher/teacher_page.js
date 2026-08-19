// async function loadTeachers() {
//     const grid = document.getElementById('teacherGrid');
//     try {
//       const res = await fetch('/api/teachers');
//       const teachers = await res.json();
//       if (teachers.length === 0) {
//         grid.innerHTML = '<div class="empty-msg"><i class="bi bi-person-x-fill" style="font-size:2rem;"></i><p>No teachers available.</p></div>';
//         return;
//       }
//       grid.innerHTML = teachers.map(t => `
//         <div class="teacher-card">
//           <div class="img-wrap">
//             ${t.image_path ? `<img src="${t.image_path}" alt="${t.name}" loading="lazy" />` : `<i class="bi bi-person-circle" style="font-size:4rem; color:#94a3b8;"></i>`}
//           </div>
//           <div class="name">${t.name}</div>
//           <div class="subject">${t.subject}</div>
//           <div class="bio">${t.text || '—'}</div>
//         </div>
//       `).join('');
//     } catch (err) {
//       grid.innerHTML = `<div class="empty-msg text-danger">Failed to load data: ${err.message}</div>`;
//     }
//   }
//   loadTeachers();

// teacher_page.js — sirf scroll arrows, koi add/edit/delete nahi

document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('teacherScrollContainer');
    const leftBtn = document.getElementById('scrollLeftBtn');
    const rightBtn = document.getElementById('scrollRightBtn');
    if (!container || !leftBtn || !rightBtn) return;

    const scrollAmount = () => {
        const w = container.clientWidth;
        return Math.min(w * 0.8, 300);
    };

    leftBtn.addEventListener('click', () => container.scrollBy({ left: -scrollAmount(), behavior: 'smooth' }));
    rightBtn.addEventListener('click', () => container.scrollBy({ left: scrollAmount(), behavior: 'smooth' }));

    function toggleArrows() {
        const canScroll = container.scrollWidth > container.clientWidth;
        leftBtn.style.display = canScroll ? 'flex' : 'none';
        rightBtn.style.display = canScroll ? 'flex' : 'none';
    }

    const ro = new ResizeObserver(toggleArrows);
    ro.observe(container);
    window.addEventListener('resize', toggleArrows);
    setTimeout(toggleArrows, 100);
});