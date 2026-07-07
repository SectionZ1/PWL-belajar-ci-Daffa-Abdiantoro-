<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="row gy-4">
    <!-- Contact Info Cards -->
    <div class="col-xl-6">
        <div class="row">
            <div class="col-lg-6">
                <div class="info-box card p-4 mb-4 text-center border-0 shadow-sm">
                    <i class="bi bi-geo-alt text-primary fs-2 mb-2"></i>
                    <h5 class="fw-bold">Alamat</h5>
                    <p class="text-secondary mb-0">Jl. Imam Bonjol No. 207,<br>Semarang, Jawa Tengah</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="info-box card p-4 mb-4 text-center border-0 shadow-sm">
                    <i class="bi bi-telephone text-success fs-2 mb-2"></i>
                    <h5 class="fw-bold">Hubungi Kami</h5>
                    <p class="text-secondary mb-0">+62 812-3456-7890<br>+62 24 7654321</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="info-box card p-4 mb-4 text-center border-0 shadow-sm">
                    <i class="bi bi-envelope text-warning fs-2 mb-2"></i>
                    <h5 class="fw-bold">Email</h5>
                    <p class="text-secondary mb-0">info@jipon.com<br>support@jipon.com</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="info-box card p-4 mb-4 text-center border-0 shadow-sm">
                    <i class="bi bi-clock text-danger fs-2 mb-2"></i>
                    <h5 class="fw-bold">Jam Kerja</h5>
                    <p class="text-secondary mb-0">Senin - Jumat<br>09:00 - 17:00 WIB</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="col-xl-6">
        <div class="card p-4 border-0 shadow-sm">
            <form action="#" method="post" class="php-email-form" onsubmit="event.preventDefault(); alert('Pesan Anda berhasil dikirim! Kami akan menghubungi Anda segera.'); this.reset();">
                <div class="row gy-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Nama Anda</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email Anda</label>
                        <input type="email" class="form-control" name="email" placeholder="Alamat Email" required>
                    </div>

                    <div class="col-md-12">
                        <label for="subject" class="form-label fw-semibold">Subjek</label>
                        <input type="text" class="form-control" name="subject" placeholder="Subjek Pesan" required>
                    </div>

                    <div class="col-md-12">
                        <label for="message" class="form-label fw-semibold">Pesan</label>
                        <textarea class="form-control" name="message" rows="5" placeholder="Tulis pesan Anda di sini..." required></textarea>
                    </div>

                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill"><i class="bi bi-send me-1"></i> Kirim Pesan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
