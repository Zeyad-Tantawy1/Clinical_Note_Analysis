function isElementInView(el) {
    const rect = el.getBoundingClientRect();
    return rect.top <= window.innerHeight && rect.bottom >= 0;
}
function checkScroll() {
    const sections = document.querySelectorAll('section');
    sections.forEach(section => {
        if (section !== document.querySelector('.hero-section') && isElementInView(section)) {
            section.classList.add('visible');
        } else if (section !== document.querySelector('.hero-section')) {
            section.classList.remove('visible');
        }
    });
}
window.addEventListener('scroll', checkScroll);
document.addEventListener('DOMContentLoaded', checkScroll);
window.addEventListener('scroll', checkScroll);
document.addEventListener('DOMContentLoaded', checkScroll);
const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });
document.querySelectorAll('section').forEach(section => {
    observer.observe(section);
});

