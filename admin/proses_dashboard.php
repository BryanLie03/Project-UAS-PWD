<?php
session_start();

// Keamanan: Hanya admin yang boleh mengeksekusi file ini
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== 'admin') {
    die("Akses ditolak.");
}

$aksi = $_GET['aksi'] ?? '';
$admin_aktif = $_SESSION['nama_admin'];

// ==============================================================================
// 1. PROSES VIDEO YOUTUBE
// ==============================================================================
if ($aksi == 'set_video') {
    // Mendapatkan URL thumbnail secara manual dari ID video
    $thumbnail_url = "https://img.youtube.com/vi/" . $_POST['id_video'] . "/maxresdefault.jpg";
    
    $data_video = [
        'id_video' => $_POST['id_video'],
        'judul' => $_POST['judul_video'], // Judul asli dari input
        'thumbnail' => $thumbnail_url,
        'diupload_oleh' => $admin_aktif,
        'waktu' => date('Y-m-d H:i:s')
    ];
    file_put_contents('data_video.json', json_encode($data_video));
    header("Location: admin_dashboard.php?msg=video_sukses");
    exit;
}

elseif ($aksi == 'edit_judul_video') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && file_exists('data_video.json')) {
        // Ambil data lama
        $data_video = json_decode(file_get_contents('data_video.json'), true);
        
        // Update hanya bagian judul
        $data_video['judul'] = $_POST['judul_diedit'];
        $data_video['waktu'] = date('Y-m-d H:i:s'); // Update waktu edit
        
        // Simpan kembali
        file_put_contents('data_video.json', json_encode($data_video));
        header("Location: admin_dashboard.php?msg=video_edit_sukses");
        exit;
    }
}

elseif ($aksi == 'hapus_video') {
    if (file_exists('data_video.json')) unlink('data_video.json');
    header("Location: admin_dashboard.php?msg=video_hapus");
    exit;
}

// ==============================================================================
// 2. PROSES EVENT / KEGIATAN (Tetap)
// ==============================================================================
elseif ($aksi == 'tambah_event') {
    $event_baru = [
        'id' => time(),
        'nama' => $_POST['nama_event'],
        'tanggal' => $_POST['tanggal_event'],
        'dibuat_oleh' => $admin_aktif
    ];
    
    $data_event = file_exists('data_event.json') ? json_decode(file_get_contents('data_event.json'), true) : [];
    array_unshift($data_event, $event_baru);
    file_put_contents('data_event.json', json_encode($data_event));
    header("Location: admin_dashboard.php?msg=event_sukses");
    exit;
}
elseif ($aksi == 'hapus_event') {
    $id_hapus = $_GET['id'];
    $data_event = json_decode(file_get_contents('data_event.json'), true);
    $data_event = array_filter($data_event, function($e) use ($id_hapus) { return $e['id'] != $id_hapus; });
    file_put_contents('data_event.json', json_encode(array_values($data_event)));
    header("Location: admin_dashboard.php?msg=event_hapus");
    exit;
}

// ==============================================================================
// 3. PROSES GALERI FOTO (Tetap)
// ==============================================================================
elseif ($aksi == 'tambah_galeri') {
    if (!is_dir('uploads')) mkdir('uploads', 0777, true);
    $file_tmp = $_FILES['foto']['tmp_name'];
    $nama_file = time() . '_' . $_FILES['foto']['name'];
    $tujuan = 'uploads/' . $nama_file;
    
    if (move_uploaded_file($file_tmp, $tujuan)) {
        $galeri_baru = [
            'id' => time(),
            'file' => $tujuan,
            'judul' => $_POST['judul_foto'],
            'diupload_oleh' => $admin_aktif
        ];
        
        $data_galeri = file_exists('data_galeri.json') ? json_decode(file_get_contents('data_galeri.json'), true) : [];
        array_unshift($data_galeri, $galeri_baru);
        file_put_contents('data_galeri.json', json_encode($data_galeri));
        header("Location: admin_dashboard.php?msg=galeri_sukses");
        exit;
    }
}
elseif ($aksi == 'hapus_galeri') {
    $id_hapus = $_GET['id'];
    $data_galeri = json_decode(file_get_contents('data_galeri.json'), true);
    foreach ($data_galeri as $key => $g) {
        if ($g['id'] == $id_hapus) {
            if (file_exists($g['file'])) unlink($g['file']);
            unset($data_galeri[$key]);
            break;
        }
    }
    file_put_contents('data_galeri.json', json_encode(array_values($data_galeri)));
    header("Location: admin_dashboard.php?msg=galeri_hapus");
    exit;
}
?>