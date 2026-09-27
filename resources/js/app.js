import './bootstrap';
import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';
import L from 'leaflet';
import Lenis from 'lenis';
import flatpickr from 'flatpickr';
import { Indonesian } from 'flatpickr/dist/l10n/id.js';
import Swal from 'sweetalert2';
import Chart from 'chart.js/auto';

window.Chart = Chart;

// Global Alpine Live Filter Component for Admin CMS
document.addEventListener('alpine:init', () => {
    Alpine.data('adminLiveFilter', () => ({
        init() {
            // Restore focus and cursor position in search bar after live reload
            const searchInput = this.$el.querySelector('input[name="q"]');
            if (searchInput && searchInput.value) {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('q') && urlParams.get('q') === searchInput.value) {
                    searchInput.focus();
                    const len = searchInput.value.length;
                    searchInput.setSelectionRange(len, len);
                }
            }
        },
        submitForm() {
            this.$el.submit();
        }
    }));
});

window.Alpine = Alpine;
Alpine.start();

// SweetAlert2 Modern Toast Engine for Habar Etam
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3800,
    timerProgressBar: true,
    customClass: {
        popup: 'habar-toast-popup',
        title: 'habar-toast-title',
        htmlContainer: 'habar-toast-html',
        timerProgressBar: 'habar-toast-progress',
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

window.Swal = Swal;
window.Toast = Toast;
window.showToast = (icon, title, html = null, timer = 3800) => {
    Toast.fire({
        icon: icon, // 'success' | 'error' | 'warning' | 'info'
        title: title,
        html: html,
        timer: timer,
    });
};

window.confirmDelete = (message, formElement, title = 'Konfirmasi Penghapusan') => {
    Swal.fire({
        title: title,
        text: message || 'Data yang dihapus tidak dapat dipulihkan kembali.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#4b5563',
        confirmButtonText: 'Ya, Hapus Sekarang',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-3xl border border-gray-100 shadow-2xl p-6 font-sans',
            confirmButton: 'rounded-2xl px-5 py-2.5 font-bold text-xs shadow-xs',
            cancelButton: 'rounded-2xl px-5 py-2.5 font-bold text-xs shadow-xs',
            title: 'text-lg font-black text-gray-900',
        }
    }).then((result) => {
        if (result.isConfirmed && formElement) {
            formElement.submit();
        }
    });
    return false;
};

// Global data-confirm listener for forms
document.addEventListener('submit', (e) => {
    const form = e.target.closest('form[data-confirm]');
    if (form && !form.dataset.confirmed) {
        e.preventDefault();
        const msg = form.getAttribute('data-confirm') || 'Apakah Anda yakin ingin melanjutkan aksi ini?';
        const title = form.getAttribute('data-confirm-title') || 'Konfirmasi Tindakan';
        const isDelete = form.getAttribute('data-confirm-type') === 'delete' || form.querySelector('input[name="_method"][value="DELETE"]');

        Swal.fire({
            title: title,
            text: msg,
            icon: isDelete ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: isDelete ? '#e11d48' : '#d97706',
            cancelButtonColor: '#4b5563',
            confirmButtonText: isDelete ? 'Ya, Hapus Sekarang' : 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3xl border border-gray-100 shadow-2xl p-6 font-sans',
                confirmButton: 'rounded-2xl px-5 py-2.5 font-bold text-xs shadow-xs',
                cancelButton: 'rounded-2xl px-5 py-2.5 font-bold text-xs shadow-xs',
                title: 'text-lg font-black text-gray-900',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.dataset.confirmed = 'true';
                form.submit();
            }
        });
    }
});

// 1. Initialize Lenis Smooth Scroll only if not on an auth/app-contained layout
const isLenisPrevented = document.body && (document.body.hasAttribute('data-lenis-prevent') || document.body.classList.contains('overflow-hidden'));

let lenis = null;
if (!isLenisPrevented) {
    lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        orientation: 'vertical',
        gestureOrientation: 'vertical',
        smoothWheel: true,
        touchMultiplier: 1.6,
        wheelMultiplier: 1,
    });

    window.lenis = lenis;

    // Lenis RAF loop
    function raf(time) {
        if (lenis) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
    }
    requestAnimationFrame(raf);

    // 2. Velocity Tracking and Dynamic Physics
    let currentVelocity = 0;
    lenis.on('scroll', (e) => {
        currentVelocity = Math.min(Math.max(e.velocity, -20), 20);
        document.documentElement.style.setProperty('--scroll-velocity', currentVelocity);
        document.documentElement.style.setProperty('--scroll-speed', Math.abs(currentVelocity));

        // Dynamic skew / velocity scaling on elements with data-velocity
        document.querySelectorAll('[data-velocity-skew]').forEach((el) => {
            const skewFactor = parseFloat(el.getAttribute('data-velocity-skew')) || 0.15;
            const skewAngle = currentVelocity * skewFactor;
            el.style.transform = `skewY(${skewAngle}deg)`;
        });
    });
} else {
    window.lenis = null;
}

// 3. Modern Kinetic Scroll Reveal Engine (Blur + Spring + Stagger)
function initScrollReveal() {
    const revealElements = document.querySelectorAll('.reveal-blur-spring, .reveal-skew-spring, .reveal-stagger');

    // Assign stagger index dynamically to children
    document.querySelectorAll('.reveal-stagger').forEach((container) => {
        Array.from(container.children).forEach((child, idx) => {
            child.style.setProperty('--stagger-idx', idx);
        });
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                // Optional: keep observing or unobserve if one-way
                // observer.unobserve(entry.target);
            }
        });
    }, {
        root: null,
        rootMargin: '0px 0px -8% 0px',
        threshold: 0.1,
    });

    revealElements.forEach((el) => observer.observe(el));
}

// 4. Initialize Lucide icons on page load and dynamic updates
window.lucide = { 
    createIcons: (opts) => createIcons({ icons, ...(opts || {}) }), 
    icons 
};
window.initIcons = () => {
    createIcons({ icons });
};

// Global Password Visibility Toggle
window.togglePasswordVisibility = (inputId, btn) => {
    const input = document.getElementById(inputId);
    if (!input) return;
    const isCurrentlyPassword = input.type === 'password';
    input.type = isCurrentlyPassword ? 'text' : 'password';
    
    // Update button icon HTML
    const iconName = isCurrentlyPassword ? 'eye-off' : 'eye';
    if (btn) {
        btn.innerHTML = `<i data-lucide="${iconName}" class="w-4 h-4 ${isCurrentlyPassword ? 'text-amber-600' : 'text-gray-400'}"></i>`;
        window.initIcons();
    }
};

// 5. Smart Auto-hiding Navbar on Scroll Down / Reveal on Scroll Up
function initSmartNavbar() {
    const navbar = document.getElementById('main-navbar-header');
    if (!navbar) return;

    let lastScrollY = window.scrollY || 0;
    let isTicking = false;
    const threshold = 60; // minimum scroll distance from top before hiding
    const delta = 6; // minimum movement to detect direction

    const handleScroll = (currentY) => {
        const mobileMenu = document.getElementById('mobile-menu');
        const isMobileMenuOpen = mobileMenu && mobileMenu.classList.contains('is-open');

        // Always show if at top of page or if mobile menu is currently open
        if (currentY <= threshold) {
            navbar.classList.remove('navbar--hidden');
        } else if (!isMobileMenuOpen) {
            const diff = currentY - lastScrollY;
            if (diff > delta) {
                // User scrolling DOWN -> hide navbar
                navbar.classList.add('navbar--hidden');
                if (typeof window.closeAllDropdowns === 'function') {
                    window.closeAllDropdowns();
                }
            } else if (diff < -delta) {
                // User scrolling UP -> reveal navbar
                navbar.classList.remove('navbar--hidden');
            }
        }

        lastScrollY = Math.max(0, currentY);
        isTicking = false;
    };

    // Integrate with Lenis smooth scroll
    if (lenis) {
        lenis.on('scroll', (e) => {
            if (!isTicking) {
                requestAnimationFrame(() => {
                    handleScroll(e.scroll);
                });
                isTicking = true;
            }
        });
    }

    // Native scroll event fallback
    window.addEventListener('scroll', () => {
        if (!lenis && !isTicking) {
            requestAnimationFrame(() => {
                handleScroll(window.scrollY);
            });
            isTicking = true;
        }
    }, { passive: true });
}

// Icon helper for custom selects
const getSelectIconSvg = (name, isSelected) => {
    const colorClass = isSelected ? 'text-amber-600' : 'text-gray-400';
    switch (name) {
        case 'sparkles':
            return `<svg class="w-3.5 h-3.5 ${colorClass} shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>`;
        case 'calendar':
            return `<svg class="w-3.5 h-3.5 ${colorClass} shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>`;
        case 'history':
            return `<svg class="w-3.5 h-3.5 ${colorClass} shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>`;
        case 'clock':
            return `<svg class="w-3.5 h-3.5 ${colorClass} shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`;
        case 'layers':
            return `<svg class="w-3.5 h-3.5 ${colorClass} shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.9a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 12.5-8.58 3.9a2 2 0 0 1-1.66 0L2 12.5"/><path d="m22 17.5-8.58 3.9a2 2 0 0 1-1.66 0L2 17.5"/></svg>`;
        case 'map-pin':
        default:
            return `<svg class="w-3.5 h-3.5 ${colorClass} shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>`;
    }
};

// 6. Modern Custom Select UI/UX Enhancer with Search & Top Closest Matches
function initCustomSelects() {
    document.querySelectorAll('select:not([data-no-custom]):not(.swal2-select):not([class*="swal2"]):not(.flatpickr-monthDropdown-months):not([class*="flatpickr"])').forEach((select) => {
        if (select.dataset.customEnhanced === 'true') return;
        if (select.closest('.swal2-container, .swal2-popup, .swal2-html-container, .flatpickr-calendar, .flatpickr-months, .flatpickr-current-month') || select.classList.contains('swal2-select') || select.classList.contains('flatpickr-monthDropdown-months')) return;
        select.dataset.customEnhanced = 'true';

        const isSearchable = select.dataset.searchable === 'true' || select.options.length > 5;
        const maxLimit = parseInt(select.dataset.searchLimit || '50', 10);

        // Visually hide native select while keeping it functional for forms/validation
        select.classList.add('sr-only');
        select.setAttribute('tabindex', '-1');
        select.setAttribute('aria-hidden', 'true');

        // Wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select-wrapper relative w-full inline-block';

        // Trigger button
        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'custom-select-trigger form-input text-xs h-[42px] sm:h-[46px] py-2 sm:py-2.5 pl-3.5 pr-9 rounded-xl sm:rounded-2xl border-gray-200/90 bg-gray-50/60 hover:bg-white focus:bg-white text-left font-semibold text-gray-800 flex items-center justify-between cursor-pointer w-full transition-all shadow-2xs hover:border-brand-gold/60 focus:ring-4 focus:ring-brand-gold/15';
        
        const triggerText = document.createElement('span');
        triggerText.className = 'truncate block w-full';
        
        const updateTriggerDisplay = () => {
            const selectedOption = select.options[select.selectedIndex] || select.options[0];
            if (selectedOption) {
                const iconType = selectedOption.dataset.icon || (select.name === 'district' ? 'map-pin' : (select.name.includes('time') ? 'clock' : 'layers'));
                triggerText.innerHTML = `
                    <span class="flex items-center gap-2 truncate">
                        ${getSelectIconSvg(iconType, false)}
                        <span class="truncate font-semibold">${selectedOption.text}</span>
                    </span>
                `;
            } else {
                triggerText.textContent = 'Pilih...';
            }
        };

        updateTriggerDisplay();
        
        const chevron = document.createElement('div');
        chevron.className = 'custom-select-chevron absolute right-3.5 pointer-events-none text-gray-400 transition-transform duration-200 flex items-center justify-center';
        chevron.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="m6 9 6 6 6-6"/></svg>`;

        trigger.appendChild(triggerText);
        trigger.appendChild(chevron);

        // Dropdown Menu Container
        const menu = document.createElement('div');
        menu.setAttribute('data-lenis-prevent', 'true');
        menu.className = 'custom-select-menu custom-dropdown dropdown-splash absolute z-50 left-0 right-0 mt-2 bg-white/95 backdrop-blur-md border border-gray-200/90 rounded-2xl shadow-2xl ring-1 ring-black/5 p-2 scrollbar-none max-h-72 overflow-y-auto';

        let searchInput = null;
        if (isSearchable) {
            const searchBox = document.createElement('div');
            searchBox.className = 'p-1.5 pb-2 border-b border-gray-100/80 sticky top-0 bg-white/95 backdrop-blur-md rounded-t-xl z-10';
            searchBox.innerHTML = `
                <div class="relative flex items-center w-full">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 pointer-events-none z-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" 
                           class="custom-select-search-input" 
                           placeholder="Ketik pencarian..." 
                           autocomplete="off" 
                           spellcheck="false" />
                </div>
            `;
            searchInput = searchBox.querySelector('input');
            searchInput.addEventListener('click', (e) => e.stopPropagation());
            searchInput.addEventListener('input', (e) => {
                renderOptions(e.target.value.trim());
            });
            menu.appendChild(searchBox);
        }

        const optionsList = document.createElement('div');
        optionsList.className = 'options-list p-1 space-y-0.5';
        menu.appendChild(optionsList);

        // Render options based on search query
        const renderOptions = (query = '') => {
            optionsList.innerHTML = '';
            const allOptions = Array.from(select.options).map((opt, idx) => ({ opt, idx }));
            
            let filtered = [];

            if (!isSearchable) {
                // If not searchable (e.g. short list like time_filter), display all options
                filtered = allOptions;
            } else {
                // Filter out empty placeholder options for search suggestions
                const validOptions = allOptions.filter(item => item.opt.value !== '');

                if (!query) {
                    const currentIdx = select.selectedIndex;
                    const currentItem = validOptions.find(item => item.idx === currentIdx);
                    
                    if (currentItem) {
                        filtered.push(currentItem);
                    }
                    validOptions.forEach(item => {
                        if (filtered.length < maxLimit && !filtered.some(f => f.idx === item.idx)) {
                            filtered.push(item);
                        }
                    });
                } else {
                    const qLower = query.toLowerCase();
                    const startsWith = [];
                    const contains = [];
                    validOptions.forEach(item => {
                        const text = item.opt.text.toLowerCase();
                        if (text.startsWith(qLower)) {
                            startsWith.push(item);
                        } else if (text.includes(qLower)) {
                            contains.push(item);
                        }
                    });
                    filtered = [...startsWith, ...contains].slice(0, maxLimit);
                }
            }

            if (filtered.length === 0) {
                const emptyState = document.createElement('div');
                emptyState.className = 'px-3 py-4 text-center text-xs text-gray-400 flex flex-col items-center gap-1';
                emptyState.innerHTML = `<span class="font-medium text-gray-500">Tidak ada opsi yang cocok</span><span class="text-[10px] text-gray-400">Coba gunakan kata kunci lain</span>`;
                optionsList.appendChild(emptyState);
                return;
            }

            if (isSearchable && query) {
                const headerNote = document.createElement('div');
                headerNote.className = 'px-2 py-1 text-[10px] font-semibold text-gray-400 flex items-center justify-between select-none';
                headerNote.innerHTML = `<span>Hasil Pencarian</span><span class="text-[9px] text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded-md font-bold border border-amber-200/50">${filtered.length} opsi</span>`;
                optionsList.appendChild(headerNote);
            }

            filtered.forEach(({ opt, idx }) => {
                const optBtn = document.createElement('button');
                optBtn.type = 'button';
                const isSelected = select.selectedIndex === idx;
                optBtn.className = `custom-select-option ${
                    isSelected 
                    ? 'bg-amber-400/20 text-brand-black font-black border-amber-400/40 shadow-2xs' 
                    : 'text-gray-700 hover:bg-amber-50 hover:text-brand-black'
                }`;

                const iconType = opt.dataset.icon || (select.name === 'district' ? 'map-pin' : (select.name.includes('time') ? 'clock' : 'layers'));

                optBtn.innerHTML = `
                    <span class="flex items-center gap-2 truncate">
                        ${getSelectIconSvg(iconType, isSelected)}
                        <span class="truncate font-semibold">${opt.text}</span>
                    </span>
                    ${isSelected ? '<svg class="w-3.5 h-3.5 text-brand-gold-dark shrink-0 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>' : ''}
                `;

                optBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    select.value = opt.value;
                    select.selectedIndex = idx;
                    updateTriggerDisplay();

                    closeMenu();

                    // Dispatch change event to trigger inline or framework handlers
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                    select.dispatchEvent(new Event('input', { bubbles: true }));
                    
                    if (typeof select.onchange === 'function') {
                        select.onchange();
                    }
                });

                optionsList.appendChild(optBtn);
            });
        };

        // Listen for external value updates on native select (e.g. from Alpine or JS)
        select.addEventListener('change', () => {
            updateTriggerDisplay();
        });

        renderOptions();

        const openMenu = () => {
            if (typeof window.closeAllDropdowns === 'function') {
                window.closeAllDropdowns();
            }
            document.querySelectorAll('.custom-select-menu.is-open').forEach(m => {
                if (m !== menu) {
                    m.classList.remove('is-open');
                    const w = m.closest('.custom-select-wrapper');
                    if (w) {
                        w.classList.remove('is-open');
                        w.querySelector('.custom-select-chevron svg')?.classList.remove('rotate-180');
                        w.querySelector('.custom-select-trigger')?.classList.remove('ring-4', 'ring-brand-gold/15', 'border-brand-gold');
                    }
                    const p = m.closest('.is-dropdown-active');
                    if (p) p.classList.remove('is-dropdown-active');
                }
            });

            wrapper.classList.add('is-open');
            menu.classList.add('is-open');
            chevron.querySelector('svg')?.classList.add('rotate-180');
            trigger.classList.add('ring-4', 'ring-brand-gold/15', 'border-brand-gold');

            const parentCard = wrapper.closest('.reveal-blur-spring, .bg-white, form, .relative');
            if (parentCard) {
                parentCard.classList.add('is-dropdown-active');
            }

            if (searchInput) {
                searchInput.value = '';
                renderOptions('');
                setTimeout(() => searchInput.focus(), 60);
            }
        };

        const closeMenu = () => {
            wrapper.classList.remove('is-open');
            menu.classList.remove('is-open');
            chevron.querySelector('svg')?.classList.remove('rotate-180');
            trigger.classList.remove('ring-4', 'ring-brand-gold/15', 'border-brand-gold');
            const parentCard = wrapper.closest('.is-dropdown-active');
            if (parentCard) {
                parentCard.classList.remove('is-dropdown-active');
            }
            if (searchInput) {
                searchInput.value = '';
            }
        };

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            if (menu.classList.contains('is-open')) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        // Insert wrapper into DOM
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
        wrapper.appendChild(trigger);
        wrapper.appendChild(menu);

        // Synchronize when changed externally
        select.addEventListener('change', () => {
            const currentOpt = select.options[select.selectedIndex];
            if (currentOpt) {
                triggerText.textContent = currentOpt.text;
            }
        });
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.custom-select-wrapper')) {
            document.querySelectorAll('.custom-select-menu.is-open').forEach(m => {
                m.classList.remove('is-open');
                m.closest('.custom-select-wrapper')?.classList.remove('is-open');
                m.closest('.custom-select-wrapper')?.querySelector('.custom-select-chevron svg')?.classList.remove('rotate-180');
                m.closest('.custom-select-wrapper')?.querySelector('.custom-select-trigger')?.classList.remove('ring-4', 'ring-brand-gold/15', 'border-brand-gold');
            });
            document.querySelectorAll('.is-dropdown-active').forEach(p => p.classList.remove('is-dropdown-active'));
        }
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.custom-select-menu.is-open').forEach(m => {
                m.classList.remove('is-open');
                m.closest('.custom-select-wrapper')?.classList.remove('is-open');
                m.closest('.custom-select-wrapper')?.querySelector('.custom-select-chevron svg')?.classList.remove('rotate-180');
                m.closest('.custom-select-wrapper')?.querySelector('.custom-select-trigger')?.classList.remove('ring-4', 'ring-brand-gold/15', 'border-brand-gold');
            });
            document.querySelectorAll('.is-dropdown-active').forEach(p => p.classList.remove('is-dropdown-active'));
        }
    });
}

// 7. Global Clean GET Query String URL Optimizer
function initCleanQueryOptimizer() {
    // A. Clean up active URL query string in browser address bar (remove empty params & defaults)
    if (window.location.search) {
        const urlParams = new URLSearchParams(window.location.search);
        let hasChanged = false;
        const keysToDelete = [];

        urlParams.forEach((val, key) => {
            const trimmed = val ? val.trim() : '';
            if (!trimmed || (key === 'sort' && trimmed === 'latest') || (key === 'page' && trimmed === '1')) {
                keysToDelete.push(key);
                hasChanged = true;
            }
        });

        keysToDelete.forEach(k => urlParams.delete(k));

        if (hasChanged) {
            const newSearch = urlParams.toString();
            const cleanUrl = window.location.pathname + (newSearch ? '?' + newSearch : '') + window.location.hash;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    }

    // B. Intercept all GET form submissions across the entire website
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (!form || (form.method && form.method.toUpperCase() !== 'GET')) return;

        const disabledInputs = [];
        const elements = form.querySelectorAll('input, select, textarea');

        elements.forEach((el) => {
            if (!el.name || el.disabled) return;
            const val = el.value ? el.value.trim() : '';
            
            // If value is empty or default sort ('latest') or page 1, disable before submitting
            if (!val || (el.name === 'sort' && val === 'latest') || (el.name === 'page' && val === '1')) {
                el.disabled = true;
                disabledInputs.push(el);
            }
        });

        // Re-enable elements after a short delay so back-forward cache or cancellations remain functional
        setTimeout(() => {
            disabledInputs.forEach(el => el.disabled = false);
        }, 1000);
    });
}

// 8. Modern Flatpickr Datepicker with Habar Etam Theme
function initModernDatepickers() {
    document.querySelectorAll('input.modern-date-input, input[type="date"], input[type="datetime-local"], [data-datepicker]').forEach((input) => {
        if (input.dataset.flatpickrEnhanced === 'true' || input.classList.contains('flatpickr-input') || input._flatpickr) return;
        if (input.closest('.swal2-container, .swal2-popup, .swal2-html-container') || input.classList.contains('swal2-input')) return;
        input.dataset.flatpickrEnhanced = 'true';

        const placeholderText = input.getAttribute('placeholder') || 'Pilih Tanggal...';
        const isDateTime = input.type === 'datetime-local' || input.dataset.enableTime === 'true';
        const isRange = input.dataset.mode === 'range';
        const maxAttr = input.getAttribute('max') || input.dataset.max;
        const minAttr = input.getAttribute('min') || input.dataset.min;
        const defaultDateAttr = input.getAttribute('value') || input.dataset.defaultDate;

        let maxDateVal = null;
        if (maxAttr === 'today') {
            maxDateVal = 'today';
        } else if (maxAttr) {
            maxDateVal = maxAttr;
        }

        let minDateVal = null;
        if (minAttr === 'today') {
            minDateVal = 'today';
        } else if (minAttr) {
            minDateVal = minAttr;
        }

        // Convert to text type to disable browser native ugly popup
        input.type = 'text';

        const fp = flatpickr(input, {
            locale: Indonesian,
            dateFormat: isDateTime ? 'Y-m-d H:i' : 'Y-m-d',
            altInput: true,
            altFormat: isDateTime ? 'j F Y, H:i' : 'j F Y',
            altInputClass: 'modern-date-picker-rendered form-input text-xs h-[42px] sm:h-[46px] rounded-xl sm:rounded-2xl border-gray-200/90 bg-gray-50/60 hover:bg-white focus:bg-white text-left font-semibold text-gray-800 placeholder-gray-400 transition-all shadow-2xs hover:border-brand-gold/60 focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 cursor-pointer w-full',
            allowInput: false,
            enableTime: isDateTime,
            time_24hr: true,
            mode: isRange ? 'range' : 'single',
            maxDate: maxDateVal,
            minDate: minDateVal,
            defaultDate: defaultDateAttr || undefined,
            disableMobile: true,
            monthSelectorType: 'dropdown',
            prevArrow: '<svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>',
            nextArrow: '<svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>',
            onOpen: (selectedDates, dateStr, instance) => {
                instance.calendarContainer.setAttribute('data-lenis-prevent', 'true');
            },
            onChange: (selectedDates, dateStr, instance) => {
                if (input.form && (input.form.hasAttribute('x-data') || input.form.classList.contains('admin-live-filter'))) {
                    input.form.submit();
                }
            }
        });

        if (fp && fp.altInput) {
            fp.altInput.placeholder = placeholderText;
            fp.altInput.setAttribute('placeholder', placeholderText);
            fp.altInput.dataset.flatpickrEnhanced = 'true';

            // If the input is wrapped in a modern-date-picker-wrapper, make clicking anywhere on the wrapper focus/open it
            const wrapper = input.closest('.modern-date-picker-wrapper');
            if (wrapper && !wrapper.dataset.hasDateClick) {
                wrapper.dataset.hasDateClick = 'true';
                wrapper.addEventListener('click', (e) => {
                    if (e.target !== fp.altInput) {
                        e.stopPropagation();
                        e.preventDefault();
                        fp.open();
                    }
                });
            }
        }
    });
}

window.initCleanQueryOptimizer = initCleanQueryOptimizer;
window.initModernDatepickers = initModernDatepickers;
window.initCustomSelects = initCustomSelects;

document.addEventListener('DOMContentLoaded', () => {
    window.initIcons();
    initScrollReveal();
    initSmartNavbar();
    initCustomSelects();
    initModernDatepickers();
    initCleanQueryOptimizer();
});

// Expose Leaflet globally for mapping components
window.L = L;

// Mobile menu toggle helper
window.toggleMobileMenu = () => {
    const menu = document.getElementById('mobile-menu');
    if (menu) {
        menu.classList.toggle('is-open');
    }
};

// Admin sidebar toggle helper
window.toggleAdminSidebar = () => {
    const sidebar = document.getElementById('admin-sidebar');
    if (sidebar) {
        sidebar.classList.toggle('-translate-x-full');
    }
};




