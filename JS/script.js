window.addEventListener("scroll", function () {
  /* Membaca input scroll */
  const navbar =
    document.querySelector(
      ".navbar",
    ); /* Deklarasi variabel navar yang datanya diambil dari html */
  if (window.scrollY > 90) {
    /* jika scroll vertilkal lebih dari 90 px makamenjalankan perintah dibawah */
    navbar.classList.add(
      "scrolled",
    ); /* Menambah class .srollled pada .navbar */
  } else {
    navbar.classList.remove(
      "scrolled",
    ); /* Menghapus class .srollled pada .navbar */
  }
});

const track = document.getElementById("slider-track");
const btnLeft = document.getElementById("btn-left");
const btnRight = document.getElementById("btn-right");

let spam = false; /* Memerintahkan untuk mengabaikan input spam */

btnRight.addEventListener("click", function () {
  if (!track || spam)
    return; /* Jika card habis atau ada spam maka langsung keluar dari fungsi tersebut*/

  const card =
    track.querySelector(
      ".box-card",
    ).offsetWidth; /* Menghitung lebar keseluruhan kartu dan menyimpanya pada variabel card */
  const scrollStep =
    card +
    30; /* Memajukan lebar kartu keseluruhan yang muncul dilayar sebanyak 30px sesuai gap antar card */

  track.scrollBy({
    left: scrollStep /* Menggeser card ke kiri */,
    behavior: "smooth",
  });
});

btnLeft.addEventListener("click", function () {
  if (!track || spam) return;

  const card = track.querySelector(".box-card").offsetWidth;
  const scrollStep = card + 30;

  track.scrollBy({
    left: -scrollStep /* Menggeser card ke kanan */,
    behavior: "smooth",
  });
});

const sejarahTrack = document.getElementById("sejarah-track");
const dots = document.querySelectorAll(".sejarah-dot .dot");
let autoScrollTimer; /* Menjalankan fungsi auto scroll */

function autoScrollSejarah() {
  if (!sejarahTrack) return;

  const timelineWidth =
    sejarahTrack.querySelector(".sejarah-timeline").offsetWidth;

  const scrollStep = timelineWidth + 30;

  if (
    sejarahTrack.scrollLeft + sejarahTrack.clientWidth >=
    sejarahTrack.scrollWidth -
      10 /* jika total scroll left lebih besar dari lebar track sejarah maka track akan kembali ke awal */
  ) {
    sejarahTrack.scrollTo({
      left: 0,
      behavior: "smooth",
    });
  } else {
    sejarahTrack.scrollBy({
      left: scrollStep,
      behavior: "smooth",
    });
  }
}

function startAutoScroll() {
  autoScrollTimer = setInterval(
    autoScrollSejarah,
    10000,
  ); /* Interval 10 detik */
}

function resetAutoScroll() {
  clearInterval(
    autoScrollTimer,
  ); /* Jika user mengganti halaman menggunakann dot maka timer interval direset ke 0 */
  startAutoScroll();
}

startAutoScroll();

function updateDots() {
  if (!sejarahTrack) return;

  const timelineWidth =
    sejarahTrack.querySelector(".sejarah-timeline").offsetWidth;

  const currentIndex = Math.round(
    sejarahTrack.scrollLeft /
      (timelineWidth +
        30) /* Menghitung dengan pembulatan untuk menentukan indeks dot pada sejarah */,
  );

  dots.forEach((dot) =>
    dot.classList.remove("active"),
  ); /* Memastikan semua dot statusnya tidak aktif */

  if (dots[currentIndex]) {
    dots[currentIndex].classList.add(
      "active",
    ); /* Menabah class active sesuai dengan index dari perhitungan sebelumnya */
  }
}

if (sejarahTrack) {
  sejarahTrack.addEventListener("scroll", updateDots);
}

dots.forEach((dot, index) => {
  dot.addEventListener("click", () => {
    if (!sejarahTrack) return;

    const cardWidth =
      sejarahTrack.querySelector(".sejarah-timeline").offsetWidth;

    const scrollStep = cardWidth + 30;

    sejarahTrack.scrollTo({
      left: index * scrollStep,
      behavior: "smooth",
    });

    resetAutoScroll();
  });
});

function muncul() {
  var elements = document.querySelectorAll(".animasi-muncul");
  for (var i = 0; i < elements.length; i++) {
    /* Fungsi Perulangan */
    var windowHeight = window.innerHeight; /* Menhitung tinggi layar pengguna */
    var elementTop =
      elements[i].getBoundingClientRect()
        .top; /* Mengecek elemen sudah muncul dilayar atau masih dibawah */
    var elementVisible = 150; /* Menentukan berapa jarak elemen dalam elemen yang masuk dalam layar sebelum muncul */

    if (elementTop < windowHeight - elementVisible) {
      elements[i].classList.add("active");
    }
  }
}
window.addEventListener(
  "scroll",
  muncul,
); /* MMemastikan fungsi muncul otomatis dijalankan setiap scroll */
muncul(); /* langsung memunculkan bagian atas saat reload sehingga tidak perlu scroll ke atas lagi untuk mentriger fungsi muncul */

const formDoa = document.getElementById("doa-form");
const inputTanggal = document.getElementById("tanggal");
const inputDoa = document.getElementById("isi-doa");
const btnSubmitDoa = document.getElementById("btn-submit-doa");

if (formDoa) {

  function cekFormDoa() {
    if (inputTanggal.value !== "" && inputDoa.value.trim() !== "") {
      btnSubmitDoa.removeAttribute("disabled");
    } else {
      btnSubmitDoa.setAttribute("disabled", "true");
    }

    if (inputTanggal.value !== "") {
      inputTanggal.parentElement.classList.remove("invalid");
    }
    if (inputDoa.value.trim() !== "") {
      inputDoa.parentElement.classList.remove("invalid");
    }
  }

  inputTanggal.addEventListener("input", cekFormDoa);
  inputDoa.addEventListener("input", cekFormDoa);

  formDoa.addEventListener("submit", function (e) {
    e.preventDefault();

    let isValid = true;

    if (inputTanggal.value === "") {
      inputTanggal.parentElement.classList.add("invalid");
      isValid = false;
    }

    if (inputDoa.value.trim() === "") {
      inputDoa.parentElement.classList.add("invalid");
      isValid = false;
    }

    if (isValid) {
      alert("Terima kasih! Permohonan doa Anda telah kami terima.");
      formDoa.reset();
      btnSubmitDoa.setAttribute("disabled", "true");
    }
  });
}



