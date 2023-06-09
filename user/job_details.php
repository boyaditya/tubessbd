<?php
session_start();

if (!isset($_SESSION["login_pelamar"])) {
    header("Location: ../login-pelamar.php");
    exit;
}

require '../functions.php';

$id_pekerjaan = $_GET["id"];

$info = query("SELECT
	info_pekerjaan.*, 
	perusahaan.nama_perusahaan, 
	perusahaan.tentang, 
	perusahaan.website_perusahaan, 
	perusahaan.email_perusahaan, 
	perusahaan.logo_perusahaan, 
	lokasi_pekerjaan.nama_lokasi, 
	kategori_pekerjaan.nama_kategori
FROM
	info_pekerjaan
	INNER JOIN
	kategori_pekerjaan
	ON 
		info_pekerjaan.id_kategori = kategori_pekerjaan.id_kategori
	INNER JOIN
	perusahaan
	ON 
		info_pekerjaan.id_perusahaan = perusahaan.id_perusahaan
	INNER JOIN
	lokasi_pekerjaan
	ON 
		info_pekerjaan.id_lokasi = lokasi_pekerjaan.id_lokasi WHERE id_pekerjaan = $id_pekerjaan")[0];

$id_pelamar = $_SESSION['id_pelamar'];
$pelamar = query("SELECT * FROM pelamar WHERE id_pelamar = $id_pelamar")[0];


// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST['lamar'])) {
    // cek apakah data berhasil diubah atau tidak
    if (tambah_detail_lamaran($_POST) > 0) {
        echo "
            <script>
                alert('Berhasil melamar pekerjaan!');
                document.location.href = 'index.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Gagal melamar pekerjaan!');
                document.location.href = 'index.php';
            </script>
        ";
    }
}


?>

<?php $title = "Lowongan Kerja " . $info['judul'] . " " . $info['nama_perusahaan']; ?>
<?php include 'navbar.php' ?>

<!-- breadcumb -->
<section class="breadcrumb" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7 mt-10 mb-10">
                <h1 class="text-black font-weight-bold">Dashboard</h1>
                <div class="custom-breadcrumbs">
                    <a href="index.php"><span style="color: black;">Dashboard</span></a> <span class="mx-2 slash">/</span>
                    <a href="job_listing.php"><span style="color: black;">Cari Lowongan Kerja</span></a> <span class="mx-2 slash">/</span>
                    <span class="text-black"><strong>Detail Pekerjaan</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- breadcumb end -->

<main>
    <!-- job post company Start -->
    <div class="job-post-company pt-20 pb-20">
        <div class="container">
            <div class="row justify-content-between">
                <!-- Left Content -->
                <div class="col-xl-7 col-lg-8">
                    <!-- job single -->
                    <div class="shadow-sm">
                        <div class="single-job-items mb-50">
                            <div class="job-items">
                                <div class="company-img">
                                    <a href="job_details.php?id=<?= $info['id_pekerjaan'] ?>"><img src="../upload/perusahaan/logo/<?= $info['logo_perusahaan'] ?>" alt="" width="100px" class="img-thumbnail"></a>
                                </div>
                                <div class="job-tittle job-tittle2">
                                    <a href="job_details.php?id=<?= $info['id_pekerjaan'] ?>">
                                        <h4><?= $info['judul'] ?></h4>
                                    </a>
                                    <ul>
                                        <li><strong>
                                                <?= $info['nama_perusahaan'] ?>
                                            </strong></li>
                                        <li><i class="fas fa-money-bill-alt"></i><?= $info['gaji'] ?></li>
                                    </ul>
                                    <ul>
                                        <li><i class="fas fa-map-marker-alt"></i><?= $info['nama_lokasi'] ?></li>
                                        <li><i class="fas fa-graduation-cap"></i><?= $info['pendidikan'] ?></li>
                                    </ul>
                                    <ul>
                                        <li><i class="fas fa-list-alt"></i><?= $info['nama_kategori'] ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- job single End -->

                    <div class="job-post-details">
                        <div class="post-details1 mb-50">
                            <p><?= $info['deskripsi'] ?></p>
                        </div>
                        <div class="post-details2  mb-50">
                            <!-- Small Section Tittle -->
                            <div class="small-section-tittle">
                                <h4>Tanggung Jawab Pekerjaan : </h4>
                            </div>
                            <div style="white-space: pre-wrap;"><?= $info['tanggung_jawab'] ?></div>
                        </div>
                        <div class="post-details2  mb-50">
                            <!-- Small Section Tittle -->
                            <div class="small-section-tittle">
                                <h4>Keahlian : </h4>
                            </div>
                            <div style="white-space: pre-wrap;"><?= $info['keahlian'] ?></div>
                        </div>
                        <div class="post-details2  mb-50">
                            <!-- Small Section Tittle -->
                            <div class="small-section-tittle">
                                <h4>Waktu Bekerja : </h4>
                            </div>
                            <p><?= $info['waktu_bekerja'] ?></p>
                        </div>
                    </div>

                </div>
                <!-- Right Content -->
                <div class="col-xl-4 col-lg-4">
                    <div class="post-details3  mb-50">
                        <!-- Small Section Tittle -->
                        <div class="small-section-tittle">
                            <h4>Ringkasan Pekerjaan</h4>
                        </div>
                        <ul>
                            <li><strong>Posisi : </strong><span><?= $info['judul'] ?></span></li>
                            <li><strong>Kategori Pekerjaan : </strong><span><?= $info['nama_kategori'] ?></span></li>
                            <li><strong>Tingkat Pendidikan : </strong><span><?= $info['pendidikan'] ?></span></li>
                            <li><strong>Lokasi : </strong><span><?= $info['nama_lokasi'] ?></span></li>
                            <li><strong>Gender : </strong><span><?= $info['gender'] ?></span></li>
                            <!-- <li>Vacancy : <span>02</span></li> -->
                            <li><strong>Status Kerja : </strong><span><?= $info['tipe'] ?></span></li>
                            <li><strong>Besaran Gaji : </strong><span><?= $info['gaji'] ?></span></li>
                            <!-- <li>Application date : <span>12 Sep 2020</span></li> -->
                        </ul>
                        <!-- Button trigger modal -->
                        <div class="apply-btn2">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo ($pelamar['status_akun'] == 1) ? "formLamar" : "formLamar2" ?>">
                                Lamar Pekerjaan
                            </button>
                        </div>
                    </div>
                    <div class="post-details4  mb-50">
                        <!-- Small Section Tittle -->
                        <div class="small-section-tittle">
                            <h4>Informasi Perusahaan</h4>
                        </div>
                        <span><?= $info['nama_perusahaan'] ?></span>
                        <p><?= $info['tentang'] ?></p>
                        <ul>
                            <li>Name: <span><?= $info['nama_perusahaan'] ?> </span></li>
                            <li>Web : <span><?= $info['website_perusahaan'] ?></span></li>
                            <li>Email: <span><?= $info['email_perusahaan'] ?></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- job post company End -->
</main>

<!-- Modal -->
<div class="modal fade" id="formLamar" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">Resume Anda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <input type="hidden" name="id_pelamar" id="id_pelamar" value="<?= $id_pelamar ?>">
                    <input type="hidden" name="id_pekerjaan" id="id_pekerjaan" value="<?= $id_pekerjaan ?>">
                    <input type="hidden" name="nama_pelamar" id="nama_pelamar" value="<?= $pelamar['nama_pelamar'] ?>">
                    <input type="hidden" name="email_pelamar" id="email_pelamar" value="<?= $pelamar['email_pelamar'] ?>">
                    <input type="hidden" name="telepon_pelamar" id="telepon_pelamar" value="<?= $pelamar['telepon_pelamar'] ?>">
                    <input type="hidden" name="jenis_kelamin" id="jenis_kelamin" value="<?= $pelamar['jenis_kelamin'] ?>">
                    <input type="hidden" name="tahun_kelahiran" id="tahun_kelahiran" value="<?= $pelamar['tahun_kelahiran'] ?>">
                    <input type="hidden" name="alamat_pelamar" id="alamat_pelamar" value="<?= $pelamar['alamat_pelamar'] ?>">
                    <input type="hidden" name="kota_kab_pelamar" id="kota_kab_pelamar" value="<?= $pelamar['kota_kab_pelamar'] ?>">
                    <input type="hidden" name="pendidikan_terakhir" id="pendidikan_terakhir" value="<?= $pelamar['pendidikan_terakhir'] ?>">
                    <input type="hidden" name="lama_bekerja" id="lama_bekerja" value="<?= $pelamar['lama_bekerja'] ?>">
                    <input type="hidden" name="resume" id="resume" value="<?= $pelamar['resume'] ?>">
                    <input type="hidden" name="foto_pelamar" id="foto_pelamar" value="<?= $pelamar['foto_pelamar'] ?>">

                    <div class="container">
                        <div class="alert alert-warning" role="alert">
                            Pastikan data Anda sudah terisi dengan benar.
                        </div>
                        <div class="mb-3">
                            <img src="../upload/user/foto/<?= $pelamar['foto_pelamar'] ?>" class=" mb-3 img-responsive center-block d-block img-thumbnail" style="width: 170px;" alt="Avatar" />
                        </div>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th scope="row">Nama Lengkap</th>
                                    <td class="w-75" <?php echo ($pelamar['nama_pelamar'] == "") ? "class='table-danger'" : "" ?>><?= $pelamar['nama_pelamar'] ?></td>
                                </tr>

                                <tr>
                                    <th scope="row">Email</th>
                                    <td <?php echo ($pelamar['email_pelamar'] == "") ? "class='table-danger'" : "" ?>><?= $pelamar['email_pelamar'] ?></td>
                                </tr>

                                <tr>
                                    <th scope="row">Nomor Telepon / WA </th>
                                    <td <?php echo ($pelamar['telepon_pelamar'] == "") ? "class='table-danger'" : "" ?>><?= $pelamar['telepon_pelamar'] ?></td>
                                </tr>

                                <tr>
                                    <th scope="row">Jenis Kelamin</th>
                                    <td <?php echo ($pelamar['jenis_kelamin'] == "") ? "class='table-danger'" : "" ?>><?= $pelamar['jenis_kelamin'] ?></td>
                                </tr>

                                <tr>
                                    <th scope="row">Tahun Kelahiran</th>
                                    <td <?php echo ($pelamar['tahun_kelahiran'] == "") ? "class='table-danger'" : "" ?>><?= $pelamar['tahun_kelahiran'] ?></td>
                                </tr>

                                <tr>
                                    <th scope="row">Alamat</th>
                                    <td <?php echo ($pelamar['alamat_pelamar'] == "") ? "class='table-danger'" : "" ?>><?= $pelamar['alamat_pelamar'] ?></td>
                                </tr>

                                <tr>
                                    <th scope="row">Kota / Kabupaten</th>
                                    <td <?php echo ($pelamar['kota_kab_pelamar'] == "") ? "class='table-danger'" : "" ?>><?= $pelamar['kota_kab_pelamar'] ?></td>
                                </tr>

                                <tr>
                                    <th scope="row">Pendidikan Terakhir</th>
                                    <td <?php echo ($pelamar['pendidikan_terakhir'] == "") ? "class='table-danger'" : "" ?>><?= $pelamar['pendidikan_terakhir'] ?></td>
                                </tr>

                                <tr>
                                    <th scope="row">Lama Bekerja (Pengalaman)</th>
                                    <td <?php echo ($pelamar['lama_bekerja'] == "") ? "class='table-danger'" : "" ?>><?= $pelamar['lama_bekerja'] ?></td>
                                </tr>

                                <tr>
                                    <th scope="row">File CV</th>
                                    <td <?php echo ($pelamar['resume'] == "") ? "class='table-danger'" : "" ?>><a title="Unduh CV Anda" href="../upload/user/resume/<?= $pelamar['resume'] ?>" download="CV_<?= $pelamar['nama_pelamar'] ?>" class="btn-link"><?= $pelamar['resume'] ?></a></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="col-md-12 mt-5 mb-3">
                            <label class="text-black" for="pesan_promosi"><strong>Promosikan diri Anda! </strong><span style="color:red"> *</span></label>
                            <p><small>Beritahu perusahaan mengapa anda cocok untuk posisi ini. Sebutkan keterampilan khusus dan bagaimana anda berkontribusi. Hindari hal umum seperti "Saya bertanggung jawab ..."</small></p>
                            <textarea name="pesan_promosi" id="pesan_promosi" cols="20" rows="5" class="form-control" required></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary rounded" data-dismiss="modal">Close</button>
                <button type="submit" name="lamar" class="btn-primary rounded" onclick="return confirm('Yakin untuk melamar pekerjaan?')">Lamar Pekerjaan</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2 -->
<div class="modal fade" id="formLamar2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Informasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Profil Anda belum Lengkap!</strong> Untuk melamar pekerjaan, pastikan profil Anda telah dilengkapi dengan baik dan CV terbaru Anda telah diunggah.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary rounded" data-dismiss="modal">Close</button>
                <button type="button" class="btn-primary rounded"><a href="edit_profil.php">Lengkapi Profil</a></button>
            </div>
        </div>
    </div>
</div>



<?php include 'js.php' ?>