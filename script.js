const weatherContainers = document.querySelectorAll('.weather-container');

weatherContainers.forEach(container => {
    container.addEventListener('mouseover', () => {
        container.style.transform = 'scale(1.1)';
    });

    container.addEventListener('mouseout', () => {
        container.style.transform = 'scale(1)';
    });
});
