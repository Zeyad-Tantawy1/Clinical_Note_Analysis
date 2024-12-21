// Function to check if an element is in the viewport
// Function to check if an element is in the viewport
function isElementInView(el) {
    const rect = el.getBoundingClientRect();
    return rect.top <= window.innerHeight && rect.bottom >= 0;
}

// Function to add 'visible' class to elements when they enter the viewport
function checkScroll() {
    const sections = document.querySelectorAll('section'); // Target sections on your page
    sections.forEach(section => {
        // Only add 'visible' class for sections other than the hero section
        if (section !== document.querySelector('.hero-section') && isElementInView(section)) {
            section.classList.add('visible'); // Add 'visible' class when section is in view
        } else if (section !== document.querySelector('.hero-section')) {
            section.classList.remove('visible'); // Remove 'visible' class when out of view
        }
    });
}

// Listen for scroll events
window.addEventListener('scroll', checkScroll);

// Check on initial load
document.addEventListener('DOMContentLoaded', checkScroll);


// Listen for scroll events
window.addEventListener('scroll', checkScroll);

// Check on initial load
document.addEventListener('DOMContentLoaded', checkScroll);

// Create an IntersectionObserver instance
const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible'); // Add 'visible' class when section is in view
            observer.unobserve(entry.target); // Stop observing once the section is visible
        }
    });
}, { threshold: 0.5 }); // 50% of the section must be in view to trigger the effect

// Target sections to observe
document.querySelectorAll('section').forEach(section => {
    observer.observe(section);
});

