<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <?php foreach ($products as $key => $item): ?>
        <div class="col-lg-6">
            <?= form_open('keranjang') ?>
            <?= form_hidden([
                'id' => $item['id'],
                'nama' => $item['nama'],
                'harga' => $item['harga'],
                'foto' => $item['foto']
            ]) ?>
            <div class="card">
                <div class="card-body">
                    <img src="<?= base_url() . "img/" . $item['foto'] ?>" alt="..." width="50%">
                    <h5 class="card-title"><?= $item['nama'] ?><br><?= number_to_currency($item['harga'], 'IDR') ?></h5>
                    <button type="submit" class="btn btn-info rounded-pill">Beli</button>
                </div>
            </div>

            <?= form_close() ?>
        </div>
    <?php endforeach ?>
</div>
<?= $this->endSection() ?>