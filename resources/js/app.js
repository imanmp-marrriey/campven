import './bootstrap';

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (!prefersReducedMotion) {
    document.documentElement.classList.add('js-motion-ready');
}

document.addEventListener('DOMContentLoaded', () => {
    const revealElements = document.querySelectorAll('.reveal-on-scroll');

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        revealElements.forEach((el) => el.classList.add('is-visible'));
    } else if (revealElements.length > 0) {
        const observer = new IntersectionObserver(
            (entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        obs.unobserve(entry.target);
                    }
                });
            },
            {
                threshold: 0.08,
                rootMargin: '0px 0px -24px 0px',
            }
        );

        revealElements.forEach((el) => observer.observe(el));
    }

    // Smooth quota progress bars on first view
    const progressBars = document.querySelectorAll('.quota-progress-bar[data-target-width]');
    if (progressBars.length > 0) {
        if (prefersReducedMotion || !('IntersectionObserver' in window)) {
            progressBars.forEach((bar) => {
                bar.style.width = bar.getAttribute('data-target-width') + '%';
            });
        } else {
            const barObserver = new IntersectionObserver(
                (entries, obs) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            const bar = entry.target;
                            requestAnimationFrame(() => {
                                bar.style.width = bar.getAttribute('data-target-width') + '%';
                            });
                            obs.unobserve(bar);
                        }
                    });
                },
                { threshold: 0.1 }
            );
            progressBars.forEach((bar) => barObserver.observe(bar));
        }
    }
});
