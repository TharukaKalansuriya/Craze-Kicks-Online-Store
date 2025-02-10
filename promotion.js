class PromoBanner extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: "open" });

        // Define promotional images
        this.images = [
            "images/promo1.jpg",
            "images/promo2.jpg",
            "images/promo3.jpg"
        ];
        this.currentIndex = 0;

        this.render();
    }

    render() {
        this.shadowRoot.innerHTML = `
            <style>
                .banner-container {
                    width: 80vw; 
                    height: 200px; 
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                    position: relative;
                    background: black;
                }

                .banner-container img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    position: absolute;
                    opacity: 0;
                    transition: opacity 2s ease-in-out;
                }

                .banner-container img.active {
                    opacity: 1;
                }
            </style>

            <div class="banner-container">
                ${this.images.map((img, index) => `
                    <img src="${img}" class="${index === 0 ? 'active' : ''}" alt="Promo">
                `).join('')}
            </div>
        `;

        this.startAnimation();
    }

    startAnimation() {
        const images = this.shadowRoot.querySelectorAll("img");
        setInterval(() => {
            images[this.currentIndex].classList.remove("active");
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
            images[this.currentIndex].classList.add("active");
        }, 3000); // Change every 3 seconds
    }
}

// Define the custom element
customElements.define("promo-banner", PromoBanner);
