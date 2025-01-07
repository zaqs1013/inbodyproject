document.addEventListener('DOMContentLoaded', () => {
  const sliderWrapper = document.querySelector('.ad-slider-wrapper');
  const slides = document.querySelectorAll('.ad-slide');
  const prevBtn = document.querySelector('.ad-slider-btn.prev');
  const nextBtn = document.querySelector('.ad-slider-btn.next');
  let currentIndex = 0;

  // 요소 확인
  if (!sliderWrapper || slides.length === 0 || !prevBtn || !nextBtn) {
    console.error('Slider elements not found. Check HTML structure.');
    return;
  }

  // 슬라이더 이동 함수
  function setSlidePosition() {
    sliderWrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
  }

  function moveToNextSlide() {
    currentIndex = (currentIndex + 1) % slides.length;
    setSlidePosition();
  }

  function moveToPrevSlide() {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    setSlidePosition();
  }

  // 자동 슬라이드 (5초마다 이동)
  let autoSlide = setInterval(moveToNextSlide, 5000);

  // 버튼 이벤트 추가
  prevBtn.addEventListener('click', () => {
    clearInterval(autoSlide); // 자동 슬라이드 일시 정지
    moveToPrevSlide();
    autoSlide = setInterval(moveToNextSlide, 5000); // 자동 슬라이드 재시작
  });

  nextBtn.addEventListener('click', () => {
    clearInterval(autoSlide); // 자동 슬라이드 일시 정지
    moveToNextSlide();
    autoSlide = setInterval(moveToNextSlide, 5000); // 자동 슬라이드 재시작
  });

  // 드래그 기능 추가
  let isDragging = false;
  let startPos = 0;
  let currentTranslate = 0;
  let prevTranslate = 0;

  sliderWrapper.addEventListener('mousedown', (e) => {
    isDragging = true;
    startPos = e.pageX;
    clearInterval(autoSlide); // 드래그 시 자동 슬라이드 정지
  });

  sliderWrapper.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    const currentPosition = e.pageX;
    currentTranslate = prevTranslate + currentPosition - startPos;
    sliderWrapper.style.transform = `translateX(${currentTranslate}px)`;
  });

  sliderWrapper.addEventListener('mouseup', () => {
    isDragging = false;
    const movedBy = currentTranslate - prevTranslate;
    if (movedBy < -50 && currentIndex < slides.length - 1) moveToNextSlide();
    else if (movedBy > 50 && currentIndex > 0) moveToPrevSlide();
    else setSlidePosition(); // 제자리로 돌아감
    prevTranslate = -currentIndex * 100;
    autoSlide = setInterval(moveToNextSlide, 5000); // 드래그 종료 후 자동 슬라이드 재시작
  });

  sliderWrapper.addEventListener('mouseleave', () => {
    isDragging = false;
  });
});
