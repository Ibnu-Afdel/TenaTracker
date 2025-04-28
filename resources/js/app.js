import './bootstrap';

// Import Alpine.js
import Alpine from 'alpinejs';

// Import WireUI components
import '@wireui/wireui/dist/alpine';

// Register Alpine.js components globally
window.Alpine = Alpine;

// Initialize WireUI dropdown component
document.addEventListener('alpine:init', () => {
    Alpine.data('wireui_dropdown', (options) => ({
        position: options.position || 'bottom-start',
        open: false,
        
        init() {
            this.positionable = {
                state: this.open,
                open: () => {
                    this.positionable.state = true;
                    this.open = true;
                },
                close: () => {
                    this.positionable.state = false;
                    this.open = false;
                }
            };
        },
        
        toggle() {
            if (this.open) {
                this.positionable.close();
            } else {
                this.positionable.open();
            }
        }
    }));
});

// Start Alpine.js
Alpine.start();
