(function () {
    const markers = document.querySelectorAll('.section-about-history__marker');
    if (markers.length < 2) return;
    
    function updateGaps() {
        markers.forEach((marker, i) => {
            if (i === markers.length - 1) return;

            const nextMarker = markers[i + 1];
            const fromRect = marker.getBoundingClientRect();
            const toRect = nextMarker.getBoundingClientRect();

            const gap = toRect.top - fromRect.bottom;
            marker.style.setProperty('--marker-gap', gap + 'px');
        });
    }

    function updateProgress() {
        const viewportTrigger = window.innerHeight * 0.6;

        markers.forEach((marker, i) => {
            if (i === markers.length - 1) return;

            const nextMarker = markers[i + 1];
            const fromRect = marker.getBoundingClientRect();
            const toRect = nextMarker.getBoundingClientRect();

            const lineStart = fromRect.bottom; 
            const lineEnd = toRect.top;         
            const lineHeight = lineEnd - lineStart;

            const scrolled = viewportTrigger - lineStart;
            const progress = Math.min(Math.max(scrolled / lineHeight, 0), 1);

            marker.style.setProperty('--marker-fill', (lineHeight * progress) + 'px');
        });
    }

    function onScroll() {
        updateGaps();
        updateProgress();
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    onScroll();
})();