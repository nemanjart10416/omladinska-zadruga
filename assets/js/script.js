/*
 * USAGE: new lightBox("class").init();
 */
var lightBox = function (imgClass) {
    this.galleryItems = document.querySelectorAll('.'+imgClass);
    this.currentIndex = 0;

    this.createModal = () => {
        this.modal = document.createElement("div");
        this.modal.classList.add("lightbox-modal");

        this.closeBtn = document.createElement("span");
        this.closeBtn.classList.add("lightbox-close");
        this.closeBtn.innerHTML = "&times;";
        this.modal.appendChild(this.closeBtn);

        var navigation = document.createElement("div");
        navigation.classList.add("lightbox-navigation");

        this.prevBtn = document.createElement("button");
        this.prevBtn.classList.add("lightbox-prev");
        this.prevBtn.innerHTML="&lt;";
        navigation.appendChild(this.prevBtn);

        this.nextBtn = document.createElement("button");
        this.nextBtn.classList.add("lightbox-next");
        this.nextBtn.innerHTML="&gt;";
        navigation.appendChild(this.nextBtn);

        this.modal.appendChild(navigation);

        this.modalImage = document.createElement("img");
        this.modalImage.classList.add("lightbox-modal-content");
        this.modal.appendChild(this.modalImage);

        document.body.appendChild(this.modal);
    }

    this.setListeners = () => {
        this.galleryItems.forEach((item, index) => {
            item.style.cursor = "pointer";
            item.addEventListener('click', () => {
                this.openModal(index);
            });
        });

        this.closeBtn.addEventListener('click', () => {
            this.modal.style.display = 'none';
        });

        this.prevBtn.addEventListener('click', () => {
            this.currentIndex = (this.currentIndex - 1 + this.galleryItems.length) % this.galleryItems.length;
            this.openModal(this.currentIndex);
            console.log("button")
            console.log(this.galleryItems)
        });

        this.nextBtn.addEventListener('click', () => {
            this.currentIndex = (this.currentIndex + 1) % this.galleryItems.length;
            this.openModal(this.currentIndex);
            console.log("button")
            console.log(this.galleryItems)
        });

        window.addEventListener('click', (event) => {
            if (event.target === this.modal) {
                this.modal.style.display = 'none';
            }
        });
    }

    this.init = () => {
        this.createModal();
        this.setListeners();
    }

    this.openModal = (index) => {
        this.modal.style.display = 'block';
        this.modalImage.src = this.galleryItems[index].src;
        this.currentIndex = index;
    }
}

/*
* USAGE: new bootstrapCarousel('carouselID').init();
* */
var bootstrapCarousel = function(customID) {
    this.carousel = document.getElementById(customID);
    this.slides = this.carousel.querySelectorAll('.carousel-item');
    this.indicators = this.carousel.querySelectorAll('.carousel-indicators button');
    this.currentSlide = 0;

    this.showSlide = (slideIndex) => {
        this.slides[this.currentSlide].classList.remove('active');
        this.slides[slideIndex].classList.add('active');
        this.indicators[this.currentSlide].classList.remove('active');
        this.indicators[slideIndex].classList.add('active');
        this.currentSlide = slideIndex;
    };

    this.init = () => {
        this.carousel.querySelector('.carousel-control-prev').addEventListener('click', () => {
            const prevSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
            this.showSlide(prevSlide);
        });

        this.carousel.querySelector('.carousel-control-next').addEventListener('click', () => {
            const nextSlide = (this.currentSlide + 1) % this.slides.length;
            this.showSlide(nextSlide);
        });

        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                this.showSlide(index);
            });
        });
    }
}

/*
* USAGE: new bootstrapNavbar().init();
* */
var bootstrapNavbar = function () {
    this.navbarTogglerButton = document.getElementsByClassName('navbar-toggler')[0];
    this.navbarCollapse = document.getElementById('navbarSupportedContent');
    this.dropdowns = document.querySelectorAll('.nav-item.dropdown');

    this.init = () => {
        this.navbarTogglerButton.addEventListener('click', () => {
            this.navbarCollapse.classList.toggle('show');
        });

        this.dropdowns.forEach(dropdown => {
            const dropdownToggle = dropdown.querySelector('.nav-link.dropdown-toggle');
            const dropdownMenu = dropdown.querySelector('.dropdown-menu');

            dropdownToggle.addEventListener('click', (e) => {
                e.preventDefault();
                dropdownMenu.classList.toggle('show');
            });
        });

        document.addEventListener('click', (e) => {
            this.dropdowns.forEach(dropdown => {
                const dropdownToggle = dropdown.querySelector('.nav-link.dropdown-toggle');
                const dropdownMenu = dropdown.querySelector('.dropdown-menu');

                if (!dropdown.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                }
            });
        });
    }
}

new bootstrapNavbar().init();
