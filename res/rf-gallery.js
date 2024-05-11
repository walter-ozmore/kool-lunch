// Define the Gallery constructor
function Gallery(images, navMode, timerInterval = null) {
    // Image and index
    this.images = Object.entries(images); // {src : alt, src : alt}
    this.totalImages = this.images.length;
    this.currentIndex = 0;

    // Other information
    this.navMode = navMode; // "manual" or "auto"
    if (timerInterval) {
        this.timerInterval = timerInterval; // In milliseconds
        this.timerID = null;
    }

    this.init();
}

// Method to show previous image
Gallery.prototype.prevImage = function() {
    this.currentIndex = (this.currentIndex - 1 + this.totalImages) % this.totalImages;
    this.showImage();
};

// Method to show next image
Gallery.prototype.nextImage = function() {
    this.currentIndex = (this.currentIndex + 1) % this.totalImages;
    this.showImage();
};

// Method to display current image
Gallery.prototype.showImage = function() {
    this.imageInfo = this.images[this.currentIndex];
    $("#current-img").attr("src", this.imageInfo[0]).attr("alt", this.imageInfo[1]);
};

// Methods for the timer
Gallery.prototype.startTimer = function() {
    let _this = this;

    this.timerID = setInterval(function() {
        _this.nextImage();
    }, this.timerInterval);
};

Gallery.prototype.stopTimer = function() {
    clearInterval(this.timerID);
};

// Method to attach event listeners to buttons
Gallery.prototype.attachButtonEventListeners = function() {
    let _this = this;

    $("#prev-button").click(function() {
        _this.prevImage();
    });

    $("#next-button").click(function() {
        _this.nextImage();
    });
};

// Initialize the gallery
Gallery.prototype.init = function() {
    this.showImage();
    
    // Set nav up
    if (this.navMode === "manual") {
        this.attachButtonEventListeners();
    } else if (this.navMode === "auto") {
        this.startTimer();
    }
};
