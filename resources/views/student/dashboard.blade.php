<main>
    <section>
        <div class="cardCHAT">
            <div class="carousel-containerCHAT">
                <div class="carouselCHAT">
                    <!-- Actual slides -->
                    <div class="carousel-slideCHAT">
                        <img src="images_dept/ds.png" alt="Image 1">
                    </div>
                    <div class="carousel-slideCHAT">
                        <img src="images_dept/mikasa.png" alt="Image 2">
                    </div>
                    <div class="carousel-slideCHAT">
                        <img src="images_dept/kk.jpg" alt="Image 3">
                    </div>
                </div>
                <button class="carousel-buttonCHAT prevCHAT">&lt;</button>
                <button class="carousel-buttonCHAT nextCHAT">&gt;</button>
            </div>
        </div>
    </section>
</main>

<style>
    .cardCHAT {
    position: relative;
    width: 100%;
    max-width: 1500px; /* Set a max width for your carousel */
    overflow: hidden;
    margin: auto; /* Center the carousel */
}

.carousel-containerCHAT {
    display: flex;
    align-items: center;
    justify-content: center;
}

.carouselCHAT {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.carousel-slideCHAT {
    min-width: 100%; /* Ensures each slide takes the full width */
}

.carousel-slideCHAT img {
    width: 100%;
    height: auto; /* Maintain aspect ratio */
}

.carousel-buttonCHAT {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(255, 255, 255, 0.8); /* Button background */
    border: none;
    cursor: pointer;
    font-size: 24px; /* Button size */
    padding: 10px;
    z-index: 1; /* Make sure buttons are above slides */
}

.prevCHAT {
    left: 10px; /* Position left button */
}

.nextCHAT {
    right: 10px; /* Position right button */
}

</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.querySelector('.carouselCHAT');
    const slides = document.querySelectorAll('.carousel-slideCHAT');
    const nextButton = document.querySelector('.nextCHAT');
    const prevButton = document.querySelector('.prevCHAT');
    let currentIndex = 0;

    function updateCarousel() {
        carousel.style.transform = `translateX(${-currentIndex * 100}%)`;
    }

    nextButton.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % slides.length; // Wrap around
        updateCarousel();
    });

    prevButton.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length; // Wrap around
        updateCarousel();
    });

    // Optionally: Auto-play
    setInterval(() => {
        currentIndex = (currentIndex + 1) % slides.length; // Wrap around
        updateCarousel();
    }, 5000); // Change slides every 5 seconds
});

</script>