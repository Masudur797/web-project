// ==================== SMOOTH SCROLL FUNCTION ====================
function scrollToSection(selector) {
    const element = document.querySelector(selector);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

// ==================== NAVIGATION ACTIVE STATE ====================
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('section');

    window.addEventListener('scroll', function() {
        let current = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;

            if (scrollY >= sectionTop - 200) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').slice(1) === current) {
                link.classList.add('active');
            }
        });
    });

    // Add smooth scroll for nav links
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href.startsWith('#')) {
                e.preventDefault();
                scrollToSection(href);
            }
        });
    });
});

// ==================== TYPING ANIMATION ====================
class TypingEffect {
    constructor(elementSelector, texts, speed = 100, deletingSpeed = 50) {
        this.element = document.querySelector(elementSelector);
        this.texts = texts;
        this.speed = speed;
        this.deletingSpeed = deletingSpeed;
        this.textIndex = 0;
        this.charIndex = 0;
        this.isDeleting = false;

        if (this.element) {
            this.type();
        }
    }

    type() {
        const currentText = this.texts[this.textIndex];
        
        if (this.isDeleting) {
            this.element.textContent = currentText.substring(0, this.charIndex--);
        } else {
            this.element.textContent = currentText.substring(0, this.charIndex++);
        }

        let typeSpeed = this.speed;

        if (this.isDeleting) {
            typeSpeed = this.deletingSpeed;
        }

        if (!this.isDeleting && this.charIndex === currentText.length) {
            typeSpeed = 2000; // Pause before deleting
            this.isDeleting = true;
        } else if (this.isDeleting && this.charIndex === 0) {
            this.isDeleting = false;
            this.textIndex = (this.textIndex + 1) % this.texts.length;
            typeSpeed = 500; // Pause before typing next
        }

        setTimeout(() => this.type(), typeSpeed);
    }
}

// Initialize typing effect (uncomment if you want to use it)
// const typing = new TypingEffect('.greeting', [
//     'Hi, I am [Your Name] 👋',
//     'I am a Developer',
//     'I build amazing things'
// ]);

// ==================== SCROLL REVEAL ANIMATION ====================
class ScrollReveal {
    constructor() {
        this.reveals = document.querySelectorAll('.reveal');
        this.observe();
    }

    observe() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        this.reveals.forEach(reveal => {
            observer.observe(reveal);
        });
    }
}

// Add CSS for reveal animations
const style = document.createElement('style');
style.textContent = `
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
    }

    .reveal.revealed {
        opacity: 1;
        transform: translateY(0);
    }

    .nav-link.active {
        color: #00ff41;
        text-shadow: 0 0 10px #00ff41;
        border-bottom: 2px solid #00ff41;
    }
`;
document.head.appendChild(style);

// Initialize scroll reveal
// const scrollReveal = new ScrollReveal();

// ==================== MOUSE FOLLOW EFFECT (Optional) ====================
class MouseFollowCursor {
    constructor() {
        this.cursor = document.createElement('div');
        this.cursor.style.cssText = `
            position: fixed;
            width: 20px;
            height: 20px;
            border: 2px solid #00ff41;
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            opacity: 0.5;
            transition: all 0.1s ease-out;
        `;
        document.body.appendChild(this.cursor);

        document.addEventListener('mousemove', (e) => {
            this.cursor.style.left = e.clientX - 10 + 'px';
            this.cursor.style.top = e.clientY - 10 + 'px';
        });
    }
}

// Uncomment to enable cursor effect
// const mouseFollow = new MouseFollowCursor();

// ==================== GLITCH EFFECT ON HOVER ====================
function addGlitchEffect(selector) {
    const elements = document.querySelectorAll(selector);

    elements.forEach(el => {
        el.addEventListener('mouseenter', function() {
            this.style.textShadow = `
                3px 0px 0px rgba(255, 0, 0, 0.7),
                -3px 0px 0px rgba(0, 255, 0, 0.7)
            `;
        });

        el.addEventListener('mouseleave', function() {
            this.style.textShadow = 'none';
        });
    });
}

// Apply glitch effect to headings
addGlitchEffect('.section-header h2');
addGlitchEffect('.greeting');

// ==================== FORM HANDLING (if needed) ====================
function handleContactForm(formSelector) {
    const form = document.querySelector(formSelector);
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(this);
            const name = formData.get('name');
            const email = formData.get('email');
            const message = formData.get('message');

            // Here you can add backend integration
            console.log('Form submitted:', { name, email, message });

            // Example: Send to backend using fetch
            // fetch('backend-script.php', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json'
            //     },
            //     body: JSON.stringify({ name, email, message })
            // })
            // .then(response => response.json())
            // .then(data => {
            //     console.log('Success:', data);
            //     form.reset();
            // })
            // .catch(error => console.error('Error:', error));

            // Show success message
            alert('Message sent successfully! (Demo mode)');
            form.reset();
        });
    }
}

// ==================== COPY TO CLIPBOARD ====================
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        console.log('Copied to clipboard: ' + text);
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}

// ==================== PAGE LOAD ANIMATION ====================
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation
    document.body.style.opacity = '0';
    setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease-in';
        document.body.style.opacity = '1';
    }, 100);

    // Log to console (Easter egg)
    console.log('%cWelcome to [Your Name]\'s Portfolio!', 
        'color: #00ff41; font-size: 20px; font-weight: bold; text-shadow: 0 0 10px #00ff41');
    console.log('%cFeel free to check out the code and let\'s connect!', 
        'color: #00ff41; font-size: 14px');
});

// ==================== PARALLAX EFFECT (Optional) ====================
function initParallax() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');

    window.addEventListener('scroll', () => {
        parallaxElements.forEach(element => {
            const scrollPosition = window.pageYOffset;
            const elementOffset = element.offsetTop;
            const distance = scrollPosition - elementOffset;
            element.style.transform = `translateY(${distance * 0.5}px)`;
        });
    });
}

// Uncomment to enable parallax
// initParallax();

// ==================== ACCESSIBILITY: SKIP TO MAIN CONTENT ====================
document.addEventListener('DOMContentLoaded', function() {
    const skipLink = document.createElement('a');
    skipLink.href = '#home';
    skipLink.textContent = 'Skip to main content';
    skipLink.style.cssText = `
        position: absolute;
        left: -9999px;
        z-index: 999;
    `;

    skipLink.addEventListener('focus', function() {
        this.style.left = '0';
        this.style.top = '0';
    });

    skipLink.addEventListener('blur', function() {
        this.style.left = '-9999px';
    });

    document.body.insertBefore(skipLink, document.body.firstChild);
});





// ==================== CONTACT FORM HANDLING ====================

document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const formMessage = document.getElementById('formMessage');
    const submitBtn = document.getElementById('submitBtn');
    
    if (contactForm) {
        contactForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get form data
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const message = document.getElementById('message').value.trim();
            
            // Validate
            if (!name || !email || !message) {
                showMessage('All fields are required', 'error');
                return;
            }
            
            if (message.length < 10) {
                showMessage('Message must be at least 10 characters', 'error');
                return;
            }
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = '[Sending...]';
            
            try {
                // Send to backend
                const response = await fetch('contact-handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        message: message
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showMessage('✓ Message sent successfully! I will get back to you soon.', 'success');
                    contactForm.reset();
                } else {
                    showMessage('✗ ' + (data.message || 'Failed to send message'), 'error');
                }
                
            } catch (error) {
                console.error('Error:', error);
                showMessage('✗ Network error. Please try again.', 'error');
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = '[Send Message]';
            }
        });
    }
    
    function showMessage(message, type) {
        formMessage.textContent = message;
        formMessage.className = 'alert alert-' + type;
        formMessage.style.display = 'block';
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            formMessage.style.display = 'none';
        }, 5000);
    }
});

