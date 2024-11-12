function App() {}

window.onload = function(event) {
    var app = new App();
    window.app = app;

    document.getElementById('button-prev').addEventListener('click', app.processingButton);
    document.getElementById('button-next').addEventListener('click', app.processingButton);
}

App.prototype.processingButton = function(event) {
    const btn = event.currentTarget;
    const track = document.getElementById('track');
    const carrusel = track.querySelectorAll('.carrusel');
    const carruselWidth = carrusel[0].offsetWidth;
    const trackWidth = track.scrollWidth;
    const listWidth = document.getElementById('carrusel-list').offsetWidth;

    let leftPosition = track.style.left === "" ? -carruselWidth : parseFloat(track.style.left.slice(0, -2)) * -1;

    if (btn.dataset.button === "button-prev") {
        prevAction(leftPosition, carruselWidth, track, trackWidth, listWidth);
    } else {
        nextAction(leftPosition, carruselWidth, track, trackWidth, listWidth);
    }
}

let prevAction = (leftPosition, carruselWidth, track, trackWidth, listWidth) => {
    if (leftPosition === 0) {
        track.style.left = `${-1 * (trackWidth - carruselWidth)}px`;
    } else {
        track.style.left = `${-1 * (leftPosition - carruselWidth)}px`;
    }
}

let nextAction = (leftPosition, carruselWidth, track, trackWidth, listWidth) => {
    if (leftPosition >= (trackWidth - carruselWidth)) {
        track.style.left = `0px`;
    } else {
        track.style.left = `${-1 * (leftPosition + carruselWidth)}px`;
    }
}
