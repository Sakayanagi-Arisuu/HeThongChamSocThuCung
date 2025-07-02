const slides = Array.from(document.querySelectorAll('.slide-block'));
const leftBtn = document.querySelector('.slide-left');
const rightBtn = document.querySelector('.slide-right');
let curr = 0;
function showSlide(newIndex, dir) {
    if (newIndex === curr) return;
    slides[curr].classList.remove('active');
    slides[curr].classList.add(dir === 'left' ? 'slide-out-left' : 'slide-out-right');
    slides[newIndex].classList.remove('slide-out-left', 'slide-out-right');
    slides[newIndex].classList.add('active');
    curr = newIndex;
}
function nextSlide(dir = 'right') {
    let newIndex = (curr + (dir === 'right' ? 1 : slides.length - 1)) % slides.length;
    showSlide(newIndex, dir);
}
leftBtn.onclick = () => { nextSlide('left'); resetAuto(); }
rightBtn.onclick = () => { nextSlide('right'); resetAuto(); }
function autoSlide() { nextSlide('right'); }
function resetAuto() {
    clearInterval(intervalID);
    intervalID = setInterval(autoSlide, 5200);
}
let intervalID = setInterval(autoSlide, 5200);