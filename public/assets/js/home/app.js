let nextButton = document.getElementById('next');
let prevButton = document.getElementById('prev');
let carousel = document.querySelector('.carousel');
let listHTML = document.querySelector('.carousel .list');
let seeMoreButtons = document.querySelectorAll('.seeMore');
let backButton = document.getElementById('back');

nextButton.onclick = function(){
    showSlider('next');
}
prevButton.onclick = function(){
    showSlider('prev');
}
let unAcceppClick;
const showSlider = (type) => {
    nextButton.style.pointerEvents = 'none';
    prevButton.style.pointerEvents = 'none';

    carousel.classList.remove('next', 'prev');
    let items = document.querySelectorAll('.carousel .list .item');
    if(type === 'next'){
        listHTML.appendChild(items[0]);
        carousel.classList.add('next');
    }else{
        listHTML.prepend(items[items.length - 1]);
        carousel.classList.add('prev');
    }
    clearTimeout(unAcceppClick);
    unAcceppClick = setTimeout(()=>{
        nextButton.style.pointerEvents = 'auto';
        prevButton.style.pointerEvents = 'auto';
    }, 2000)
}
seeMoreButtons.forEach((button) => {
    button.onclick = function(){
        carousel.classList.remove('next', 'prev');
        carousel.classList.add('showDetail');
    }
});
backButton.onclick = function(){
    carousel.classList.remove('showDetail');
}

// Events carousel controls (appended to reuse logic)
let eventsNext = document.getElementById('events-next');
let eventsPrev = document.getElementById('events-prev');

const showSliderFor = (containerSelector, type, nextBtn, prevBtn) => {
    const container = document.querySelector(containerSelector);
    if(!container) return;
    const list = container.querySelector('.list');
    const items = container.querySelectorAll('.list .item');
    if(!items.length) return;

    if(nextBtn) nextBtn.style.pointerEvents = 'none';
    if(prevBtn) prevBtn.style.pointerEvents = 'none';

    container.classList.remove('next', 'prev');
    if(type === 'next'){
        list.appendChild(items[0]);
        container.classList.add('next');
    } else {
        list.prepend(items[items.length - 1]);
        container.classList.add('prev');
    }

    setTimeout(()=>{
        if(nextBtn) nextBtn.style.pointerEvents = 'auto';
        if(prevBtn) prevBtn.style.pointerEvents = 'auto';
    }, 800);
}

if(eventsNext){
    eventsNext.addEventListener('click', function(){
        showSliderFor('.events-carousel', 'next', eventsNext, eventsPrev);
    });
}
if(eventsPrev){
    eventsPrev.addEventListener('click', function(){
        showSliderFor('.events-carousel', 'prev', eventsNext, eventsPrev);
    });
}