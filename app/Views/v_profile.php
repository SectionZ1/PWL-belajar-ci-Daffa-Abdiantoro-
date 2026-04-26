<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="pagetitle">
    <h1>Profile</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Home</a></li>
            <li class="breadcrumb-item active">Profile</li>
        </ol>
    </nav>
</div><section class="section profile">
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body pt-3">
                    <h5 class="card-title">Profile Information</h5>

                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label">Username</div>
                        <div class="col-lg-9 col-md-8">
                            <?= session('username') ?> 
                            <span class="badge bg-danger"><?= session('role') ?></span> </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label">Email</div>
                        <div class="col-lg-9 col-md-8 text-primary">
                            <?= session('email') ?> </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label">Login Time</div>
                        <div class="col-lg-9 col-md-8">
                            <?= session('login_time') ?> </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label">Status</div>
                        <div class="col-lg-9 col-md-8">
                            <?php if (session('isLoggedIn')) : ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Sudah Login</span> <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>