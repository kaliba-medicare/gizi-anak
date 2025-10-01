document.addEventListener("DOMContentLoaded", function () {
    const canvas = document.getElementById('signature-pad');
    const ctx = canvas.getContext('2d');
    const ratio = window.devicePixelRatio || 1;
    let isDrawing = false;

    function resizeCanvas() {
        const parent = canvas.parentNode;
        const styleWidth = parent.offsetWidth;
        const styleHeight = styleWidth * 0.375;

        canvas.style.width = styleWidth + "px";
        canvas.style.height = styleHeight + "px";

        canvas.width = styleWidth * ratio;
        canvas.height = styleHeight * ratio;

        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.scale(ratio, ratio);
    }

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    ctx.lineWidth = 2.5;
    ctx.lineCap = "round";
    ctx.strokeStyle = "#000";

    function getPosition(e) {
        const rect = canvas.getBoundingClientRect();
        return {
            x: (e.clientX - rect.left),
            y: (e.clientY - rect.top)
        };
    }

    function startDrawing(x, y) {
        isDrawing = true;
        ctx.beginPath();
        ctx.moveTo(x, y);
    }

    function draw(x, y) {
        if (!isDrawing) return;
        ctx.lineTo(x, y);
        ctx.stroke();
    }

    function stopDrawing() {
        isDrawing = false;
    }

    canvas.addEventListener('mousedown', e => {
        const pos = getPosition(e);
        startDrawing(pos.x, pos.y);
    });

    canvas.addEventListener('mousemove', e => {
        const pos = getPosition(e);
        draw(pos.x, pos.y);
    });

    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseleave', stopDrawing);

    canvas.addEventListener('touchstart', e => {
        if (e.targetTouches.length === 1) {
            const touch = e.targetTouches[0];
            const rect = canvas.getBoundingClientRect();
            startDrawing(touch.clientX - rect.left, touch.clientY - rect.top);
        }
    });

    canvas.addEventListener('touchmove', e => {
        if (e.targetTouches.length === 1 && isDrawing) {
            const touch = e.targetTouches[0];
            const rect = canvas.getBoundingClientRect();
            draw(touch.clientX - rect.left, touch.clientY - rect.top);
        }
        e.preventDefault();
    });

    canvas.addEventListener('touchend', stopDrawing);
    canvas.addEventListener('touchcancel', stopDrawing);
});

function clearSignature() {
    const canvas = document.getElementById('signature-pad');
    const ctx = canvas.getContext('2d');
    const ratio = window.devicePixelRatio || 1;
    ctx.clearRect(0, 0, canvas.width / ratio, canvas.height / ratio);
}

function saveSignature() {
    const canvas = document.getElementById('signature-pad');
    const dataURL = canvas.toDataURL('image/png');

    Livewire.dispatch('callSignatureSave', { signature: dataURL });
    clearSignature();
}