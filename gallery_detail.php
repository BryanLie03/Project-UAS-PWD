<?php
include 'header.php';

// ===== DATA GALERI (edit manual di sini kalau ada foto baru) =====
$data_galeri = [
    'kpi' => [
        'judul' => 'Kebaktian Penyegaran Iman',
        'foto'  => [
            'assets/img/KPI/SKKphotobersama1.jpg',
            'assets/img/KPI/SKKphotobersama.jpg',
            'assets/img/KPI/SKKphotobersama2.jpg',
            'assets/img/KPI/photobooth.jpg',
            'assets/img/KPI/photobooth1.jpg',
            'assets/img/KPI/photobooth2.jpg',
            'assets/img/KPI/photobooth3.jpg',
            'assets/img/KPI/photobooth4.jpg',
            'assets/img/KPI/photobooth5.jpg',
            'assets/img/KPI/photobooth6.jpg',
            'assets/img/KPI/photobooth7.jpg',
            'assets/img/KPI/photobooth8.jpg',
            'assets/img/KPI/photobooth9.jpg',
            'assets/img/KPI/Sabatpagi.jpg',
            'assets/img/KPI/Sabatpagi1.png',
            'assets/img/KPI/Sabatpagi2.png',
            'assets/img/KPI/Sabatpagi3.png',
            'assets/img/KPI/Sabatpagi4.png',
            'assets/img/KPI/Sabatpagi5.png',
            'assets/img/KPI/SKK.jpg',
            'assets/img/KPI/SKK1.jpg',
            'assets/img/KPI/SKK2.jpg',
            'assets/img/KPI/SKK3.jpg',
            'assets/img/KPI/SKKpadus.jpg',
            'assets/img/KPI/SKKpadus1.jpg',
        ]
    ],
    'kkr' => [
        'judul' => 'Kebaktian Kebangunan Rohani',
        'foto'  => [
            'assets/img/KKR/fotobersamakkr.jpg',
            'assets/img/KKR/fotobersama1.jpg',
            'assets/img/KKR/fotobersama2.JPG',
            'assets/img/KKR/fotobersama3.jpg',
            'assets/img/KKR/fotobersama4.jpg',
            'assets/img/KKR/fotobersama5.jpg',
            'assets/img/KKR/fotobersama6.jpg',
            'assets/img/KKR/fotobersama7.jpg',
            'assets/img/KKR/jemaat.jpg',
            'assets/img/KKR/kebersamaan.jpg',
            'assets/img/KKR/khotbah.jpg',
            'assets/img/KKR/pemuda1.jpg',
            'assets/img/KKR/tv.jpg',
            'assets/img/KKR/doa.jpg',
            'assets/img/KKR/doa1.jpg',
            'assets/img/KKR/doa2.jpg',
            'assets/img/KKR/doa3.jpg',
        ]
    ],
    'bi' => [
        'judul' => 'Bina Iman',
        'foto'  => [
            'assets/img/BI/templatebiman.png',
            'assets/img/BI/sesi.jpg',
            'assets/img/BI/sesi1.jpg',
            'assets/img/BI/presenkelompok.jpg',
            'assets/img/BI/presenkelompok1.jpg',
            'assets/img/BI/presenkelompok2.jpg',
            'assets/img/BI/presenkelompok3.jpg',
            'assets/img/BI/perkenalan.jpg',
            'assets/img/BI/perkenalan1.jpg',
            'assets/img/BI/juara1.jpg',
            'assets/img/BI/juara2.jpg',
            'assets/img/BI/juara3.jpg',
            'assets/img/BI/games.jpg',
            'assets/img/BI/games2.jpg',
            'assets/img/BI/fotoutama.jpeg',
            'assets/img/BI/diskusi.jpg',
            'assets/img/BI/CFD.jpg',
            'assets/img/BI/CFD1.jpg',
            'assets/img/BI/bbq.jpg',
            'assets/img/BI/bbq1.jpg',
            'assets/img/BI/bbq2.jpg',
            'assets/img/BI/bbq3.jpg',
        ]
    ],
    'panti' => [
        'judul' => 'Kunjungan Panti Asuhan',
        'foto'  => [
            'assets/img/panti/fotobersama2.jpg',
            'assets/img/panti/fotobersama.jpg',
            'assets/img/panti/fotobersama1.jpg',
            'assets/img/panti/fotobersama3.jpg',
            'assets/img/panti/hadiah.jpg',
            'assets/img/panti/hadiah1.jpg',
            'assets/img/panti/hadiah2.jpg',
            'assets/img/panti/hadiah3.jpg',
            'assets/img/panti/hadiah4.jpg',
            'assets/img/panti/hadiah5.jpg',
            'assets/img/panti/jjb.jpg',
            'assets/img/panti/jjb1.jpg',
            'assets/img/panti/souvenir.jpg',
        ]
    ],
    'donor' => [
        'judul' => 'Donor Darah',
        'foto'  => [
            'assets/img/DD/SKK1.jpg',
            'assets/img/DD/SKK.jpg',
            'assets/img/DD/souvenir.jpg',
            'assets/img/DD/souvenir1.png',
            'assets/img/DD/souvenir2.png',
            'assets/img/DD/panitia.jpg',
            'assets/img/DD/panitia1.jpg',
            'assets/img/DD/peserta1.jpg',
            'assets/img/DD/peserta.jpg',
            'assets/img/DD/kantong.jpg',
            'assets/img/DD/donor.jpg',
            'assets/img/DD/donor1.jpg',
            'assets/img/DD/donor1.png',
        ]
    ],
];

// Ambil kategori dari URL
$kategori = $_GET['kategori'] ?? '';

// Kalau kategori tidak ada di data, kembali ke gallery.php
if (!isset($data_galeri[$kategori])) {
    header('Location: gallery.php');
    exit;
}

$judul_halaman = $data_galeri[$kategori]['judul'];
$daftar_foto   = $data_galeri[$kategori]['foto'];
?>

<section class="hero-galeri">
    <div class="slide active" style="background-image: url('<?= htmlspecialchars($daftar_foto[0]) ?>');"></div>

    <div class="hero-overlay">
        <h1><?= htmlspecialchars($judul_halaman) ?></h1>
        <p>-Dokumentasi Kegiatan Gereja-</p>
    </div>
</section>

<section class="galeri-container">
    <div class="breadcrumb">
        <a href="index.php">Beranda</a>
        <span> > </span>
        <a href="gallery.php">Galeri</a>
        <span> > </span>
        <span class="active"><?= htmlspecialchars($judul_halaman) ?></span>
    </div>

    <div class="foto-grid">
        <?php foreach ($daftar_foto as $foto): ?>
            <div class="foto-item">
                <img src="<?= htmlspecialchars($foto) ?>" alt="<?= htmlspecialchars($judul_halaman) ?>">
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include 'footer.php'; ?>