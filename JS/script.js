window.addEventListener("scroll", function () {
  const navbar = document.querySelector(".navbar");
  if (navbar) {
    if (window.scrollY > 90) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  }
});

const track = document.getElementById("slider-track");
const btnLeft = document.getElementById("btn-left");
const btnRight = document.getElementById("btn-right");
let spam = false;

if (btnRight) {
  btnRight.addEventListener("click", function () {
    if (!track || spam) return;
    const card = track.querySelector(".box-card").offsetWidth;
    const scrollStep = card + 30;
    track.scrollBy({ left: scrollStep, behavior: "smooth" });
  });
}

if (btnLeft) {
  btnLeft.addEventListener("click", function () {
    if (!track || spam) return;
    const card = track.querySelector(".box-card").offsetWidth;
    const scrollStep = card + 30;
    track.scrollBy({ left: -scrollStep, behavior: "smooth" });
  });
}

const sejarahTrack = document.getElementById("sejarah-track");
const dots = document.querySelectorAll(".sejarah-dot .dot");
let autoScrollTimer;

function autoScrollSejarah() {
  if (!sejarahTrack) return;
  const timelineWidth =
    sejarahTrack.querySelector(".sejarah-timeline").offsetWidth;
  const scrollStep = timelineWidth + 30;
  if (
    sejarahTrack.scrollLeft + sejarahTrack.clientWidth >=
    sejarahTrack.scrollWidth - 10
  ) {
    sejarahTrack.scrollTo({ left: 0, behavior: "smooth" });
  } else {
    sejarahTrack.scrollBy({ left: scrollStep, behavior: "smooth" });
  }
}

function startAutoScroll() {
  autoScrollTimer = setInterval(autoScrollSejarah, 10000);
}

function resetAutoScroll() {
  clearInterval(autoScrollTimer);
  startAutoScroll();
}

startAutoScroll();

function updateDots() {
  if (!sejarahTrack) return;
  const timelineWidth =
    sejarahTrack.querySelector(".sejarah-timeline").offsetWidth;
  const currentIndex = Math.round(
    sejarahTrack.scrollLeft / (timelineWidth + 30),
  );
  dots.forEach((dot) => dot.classList.remove("active"));
  if (dots[currentIndex]) dots[currentIndex].classList.add("active");
}

if (sejarahTrack) sejarahTrack.addEventListener("scroll", updateDots);

dots.forEach((dot, index) => {
  dot.addEventListener("click", () => {
    if (!sejarahTrack) return;
    const cardWidth =
      sejarahTrack.querySelector(".sejarah-timeline").offsetWidth;
    const scrollStep = cardWidth + 30;
    sejarahTrack.scrollTo({ left: index * scrollStep, behavior: "smooth" });
    resetAutoScroll();
  });
});

function muncul() {
  var elements = document.querySelectorAll(".animasi-muncul");
  for (var i = 0; i < elements.length; i++) {
    var windowHeight = window.innerHeight;
    var elementTop = elements[i].getBoundingClientRect().top;
    var elementVisible = 150;
    if (elementTop < windowHeight - elementVisible) {
      elements[i].classList.add("active");
    }
  }
}
window.addEventListener("scroll", muncul);
muncul();

const formDoa = document.getElementById("doa-form");
const inputTanggal = document.getElementById("tanggal_doa");
const inputDoa = document.getElementById("isi-doa");
const btnSubmitDoa = document.getElementById("btn-submit-doa");

if (formDoa && inputTanggal && inputDoa && btnSubmitDoa) {
  // Mencegah pengetikan manual di keyboard
  inputTanggal.addEventListener("keypress", (e) => e.preventDefault());
  inputTanggal.addEventListener("paste", (e) => e.preventDefault());

  function cekFormDoa() {
    if (inputTanggal.value !== "" && inputDoa.value.trim() !== "") {
      btnSubmitDoa.removeAttribute("disabled");
    } else {
      btnSubmitDoa.setAttribute("disabled", "true");
    }
    if (inputTanggal.value !== "")
      inputTanggal.parentElement.classList.remove("invalid");
    if (inputDoa.value.trim() !== "")
      inputDoa.parentElement.classList.remove("invalid");
  }

  inputTanggal.addEventListener("input", cekFormDoa);
  inputDoa.addEventListener("input", cekFormDoa);

  formDoa.addEventListener("submit", function (e) {
    let isValid = true;
    if (inputTanggal.value === "") {
      inputTanggal.parentElement.classList.add("invalid");
      isValid = false;
    }
    if (inputDoa.value.trim() === "") {
      inputDoa.parentElement.classList.add("invalid");
      isValid = false;
    }

    if (!isValid) {
      e.preventDefault();
    } else {
      btnSubmitDoa.textContent = "Mengirim...";
      btnSubmitDoa.style.opacity = "0.7";
      btnSubmitDoa.style.pointerEvents = "none";
    }
  });
}

const btnScrollTop = document.getElementById("btn-scroll-top");

if (btnScrollTop) {
  window.addEventListener("scroll", function () {
    if (window.scrollY > 300) {
      btnScrollTop.classList.add("muncul");
    } else {
      btnScrollTop.classList.remove("muncul");
    }
  });

  btnScrollTop.addEventListener("click", function () {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
}
