/**
 * NAVBAR MODERNE - INTERACTIONS
 * Gestion du menu responsive, dropdowns et effets au scroll
 */

(function() {
    'use strict';

    // ============================================
    // CONFIGURATION
    // ============================================
    
    const config = {
        scrollThreshold: 50,
        mobileBreakpoint: 992
    };

    // ============================================
    // VARIABLES GLOBALES
    // ============================================
    
    let navbar = null;
    let navbarToggle = null;
    let navbarMain = null;
    let dropdowns = [];

    // ============================================
    // INITIALISATION
    // ============================================
    
    function init() {
        navbar = document.querySelector('.navbar-modern');
        navbarToggle = document.getElementById('navbar-toggle');
        navbarMain = document.getElementById('navbar-main');
        
        if (!navbar) return;

        setupMobileMenu();
        setupDropdowns();
        setupScrollEffects();
        setupThemeToggle();
        setupAccessibility();
    }

    // ============================================
    // MENU MOBILE
    // ============================================
    
    function setupMobileMenu() {
        if (!navbarToggle || !navbarMain) return;

        navbarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleMobileMenu();
        });

        // Fermer le menu en cliquant sur un lien
        const navLinks = navbarMain.querySelectorAll('.nav-link-modern');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= config.mobileBreakpoint) {
                    closeMobileMenu();
                }
            });
        });

        // Fermer le menu en cliquant à l'extérieur
        document.addEventListener('click', function(e) {
            if (!navbar.contains(e.target) && navbarMain.classList.contains('active')) {
                closeMobileMenu();
            }
        });
    }

    function toggleMobileMenu() {
        const isExpanded = navbarToggle.getAttribute('aria-expanded') === 'true';
        
        navbarMain.classList.toggle('active');
        navbarToggle.setAttribute('aria-expanded', !isExpanded);
        
        // Animation de l'icône
        const icon = navbarToggle.querySelector('i');
        if (icon) {
            icon.className = navbarMain.classList.contains('active') 
                ? 'ph ph-x' 
                : 'ph ph-list';
        }

        // Empêcher le scroll du body quand le menu est ouvert
        document.body.style.overflow = navbarMain.classList.contains('active') 
            ? 'hidden' 
            : '';
    }

    function closeMobileMenu() {
        if (!navbarMain || !navbarToggle) return;
        
        navbarMain.classList.remove('active');
        navbarToggle.setAttribute('aria-expanded', 'false');
        
        const icon = navbarToggle.querySelector('i');
        if (icon) {
            icon.className = 'ph ph-list';
        }
        
        document.body.style.overflow = '';
    }

    // ============================================
    // DROPDOWNS
    // ============================================
    
    function setupDropdowns() {
        dropdowns = Array.from(document.querySelectorAll('[data-dropdown]'));
        
        dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.dropdown-toggle');
            const menu = dropdown.querySelector('.dropdown-menu-modern');
            
            if (!toggle || !menu) return;

            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleDropdown(dropdown, toggle);
            });

            // Fermer le dropdown en cliquant à l'extérieur
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    closeDropdown(dropdown, toggle);
                }
            });

            // Fermer avec Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
                    closeDropdown(dropdown, toggle);
                    toggle.focus();
                }
            });
        });
    }

    function toggleDropdown(dropdown, toggle) {
        const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
        
        // Fermer tous les autres dropdowns
        dropdowns.forEach(otherDropdown => {
            if (otherDropdown !== dropdown) {
                const otherToggle = otherDropdown.querySelector('.dropdown-toggle');
                closeDropdown(otherDropdown, otherToggle);
            }
        });

        if (isExpanded) {
            closeDropdown(dropdown, toggle);
        } else {
            openDropdown(dropdown, toggle);
        }
    }

    function openDropdown(dropdown, toggle) {
        toggle.setAttribute('aria-expanded', 'true');
        dropdown.classList.add('active');
        
        // Focus sur le premier élément du menu
        const firstItem = dropdown.querySelector('.dropdown-item-modern');
        if (firstItem) {
            setTimeout(() => firstItem.focus(), 100);
        }
    }

    function closeDropdown(dropdown, toggle) {
        if (!toggle) return;
        toggle.setAttribute('aria-expanded', 'false');
        dropdown.classList.remove('active');
    }

    // ============================================
    // EFFETS DE SCROLL
    // ============================================
    
    function setupScrollEffects() {
        let lastScroll = 0;
        let ticking = false;

        window.addEventListener('scroll', function() {
            lastScroll = window.scrollY;

            if (!ticking) {
                window.requestAnimationFrame(function() {
                    handleScroll(lastScroll);
                    ticking = false;
                });

                ticking = true;
            }
        });
    }

    function handleScroll(scrollPos) {
        if (!navbar) return;

        if (scrollPos > config.scrollThreshold) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }

    // ============================================
    // TOGGLE THÈME
    // ============================================
    
    function setupThemeToggle() {
        const themeToggle = document.getElementById('theme-toggle');
        if (!themeToggle) return;

        // Charger le thème sauvegardé
        const savedTheme = localStorage.getItem('theme') || 'light';
        applyTheme(savedTheme);

        themeToggle.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            applyTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        
        const themeToggle = document.getElementById('theme-toggle');
        if (!themeToggle) return;

        const icon = themeToggle.querySelector('i');
        if (icon) {
            icon.className = theme === 'light' ? 'ph ph-moon' : 'ph ph-sun';
        }
    }

    // ============================================
    // ACCESSIBILITÉ
    // ============================================
    
    function setupAccessibility() {
        // Navigation au clavier dans les dropdowns
        dropdowns.forEach(dropdown => {
            const items = dropdown.querySelectorAll('.dropdown-item-modern');
            
            items.forEach((item, index) => {
                item.addEventListener('keydown', function(e) {
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const nextItem = items[index + 1] || items[0];
                        nextItem.focus();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        const prevItem = items[index - 1] || items[items.length - 1];
                        prevItem.focus();
                    }
                });
            });
        });

        // Trap focus dans le menu mobile
        if (navbarMain) {
            navbarMain.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' && navbarMain.classList.contains('active')) {
                    const focusableElements = navbarMain.querySelectorAll(
                        'a[href], button:not([disabled])'
                    );
                    const firstElement = focusableElements[0];
                    const lastElement = focusableElements[focusableElements.length - 1];

                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            });
        }
    }

    // ============================================
    // GESTION DU RESIZE
    // ============================================
    
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            if (window.innerWidth > config.mobileBreakpoint) {
                closeMobileMenu();
            }
        }, 250);
    });

    // ============================================
    // DÉMARRAGE
    // ============================================
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();

/**
 * UTILITAIRES SUPPLÉMENTAIRES
 */

// Animation de ripple pour les boutons
document.addEventListener('click', function(e) {
    const button = e.target.closest('.btn-icon-modern');
    if (!button) return;

    const ripple = button.querySelector('.btn-icon-ripple');
    if (!ripple) return;

    ripple.style.animation = 'none';
    setTimeout(() => {
        ripple.style.animation = '';
    }, 10);
});

// Smooth scroll pour les ancres
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href === '#' || href === '') return;

        const target = document.querySelector(href);
        if (target) {
            e.preventDefault();
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});