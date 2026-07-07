<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<style>
/* CSS Khusus Cetak */
@media print {
    /* Sembunyikan semua elemen di layar */
    body * {
        visibility: hidden;
    }
    
    /* Hanya tampilkan modal yang sedang terbuka */
    .modal.show, .modal.show * {
        visibility: visible;
    }
    
    /* Atur agar modal muncul di pojok kiri atas kertas saat diprint */
    .modal.show {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
    }

    /* Paksa modal-dialog untuk mengisi seluruh lebar kertas */
    .modal-dialog {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        transform: none !important; /* Hapus efek centered */
    }

    /* Hilangkan bayangan, border, dan padding berlebih */
    .modal-content {
        border: none !important;
        box-shadow: none !important;
        width: 100% !important;
    }
    
    .modal-header .btn-close, .modal-footer, .header, .sidebar, .pagetitle {
        display: none !important;
    }
    
    main {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    /* Memastikan teks terlihat tajam di kertas */
    .modal-body {
        padding: 0 !important;
    }
}
</style>

<div class="table-responsive">
    <?php if (empty($transactions)): ?>
        <div class="alert alert-info text-center" role="alert">
            <i class="bi bi-info-circle me-1"></i> Anda belum memiliki riwayat transaksi belanja.
        </div>
    <?php else: ?>
        <table class="table table-borderless datatable">
            <thead>
                <tr>
                    <th scope="col" class="text-center">ID</th>
                    <?php if (session()->get('role') == 'admin'): ?>
                        <th scope="col">User</th>
                    <?php endif; ?>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Alamat Pengiriman</th>
                    <th scope="col" class="text-end">Ongkir</th>
                    <th scope="col" class="text-end">Diskon</th>
                    <th scope="col" class="text-end">Total Bayar</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($transactions as $tx): 
                    $curr_no = $no++;
                    ?>
                    <tr>
                        <td class="text-center fw-bold text-primary">#<?= $curr_no ?></td>
                        <?php if (session()->get('role') == 'admin'): ?>
                            <td><span class="badge bg-light text-dark shadow-sm border"><i class="bi bi-person me-1"></i><?= $tx['username'] ?></span></td>
                        <?php endif; ?>
                        <td style="white-space: nowrap;"><?= date('d M Y, H:i', strtotime($tx['created_at'])) ?></td>
                        <td><span class="small"><?= esc($tx['alamat']) ?></span></td>
                        <td class="text-end"><?= number_to_currency($tx['ongkir'], 'IDR') ?></td>
                        <td class="text-end text-danger">- <?= number_to_currency($tx['diskon'] ?? 0, 'IDR') ?></td>
                        <td class="text-end fw-bold"><?= number_to_currency($tx['total_harga'], 'IDR') ?></td>
                        <td class="text-center">
                            <?php if ($tx['status'] == 0): ?>
                                <span class="badge rounded-pill bg-warning text-dark px-3 mt-1"><i class="bi bi-clock me-1"></i>Pending</span>
                            <?php elseif ($tx['status'] == 1): ?>
                                <span class="badge rounded-pill bg-success px-3 mt-1"><i class="bi bi-check-circle me-1"></i>Sukses</span>
                            <?php else: ?>
                                <span class="badge rounded-pill bg-secondary px-3 mt-1">Batal</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $tx['id'] ?>" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                
                                <?php if (session()->get('role') == 'admin' && $tx['status'] == 0): ?>
                                    <a href="<?= base_url('admin/transaction/status/' . $tx['id'] . '/1') ?>" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" title="Setujui" onclick="return confirm('Setujui pesanan ini?')">
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                    <a href="<?= base_url('admin/transaction/status/' . $tx['id'] . '/2') ?>" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" title="Batalkan" onclick="return confirm('Batalkan pesanan ini?')">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <!-- Modal Detail -->
                            <div class="modal fade" id="detailModal-<?= $tx['id'] ?>" tabindex="-1" aria-labelledby="detailModalLabel-<?= $tx['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-sm modal-dialog-centered"> <!-- Ukuran Small (Mungil) -->
                                    <div class="modal-content shadow border-0">
                                        <div class="modal-header border-bottom py-2 px-3">
                                            <h6 class="modal-title fw-bold text-dark" id="detailModalLabel-<?= $tx['id'] ?>" style="font-size: 0.85rem;">
                                                Detail #<?= $curr_no ?>
                                            </h6>
                                            <button type="button" class="btn-close" style="font-size: 0.6rem;" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-3">
                                            <!-- Compact Info -->
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-muted" style="font-size: 0.7rem;">Status:</span>
                                                    <?php if ($tx['status'] == 0): ?>
                                                        <span class="badge bg-warning text-dark p-1" style="font-size: 0.65rem;">Pending</span>
                                                    <?php elseif ($tx['status'] == 1): ?>
                                                        <span class="badge bg-success p-1" style="font-size: 0.65rem;">Sukses</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary p-1" style="font-size: 0.65rem;">Batal</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted" style="font-size: 0.7rem;">Tanggal:</span>
                                                    <span class="fw-bold text-dark" style="font-size: 0.7rem;"><?= date('d/m/y H:i', strtotime($tx['created_at'])) ?></span>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-1" style="font-size: 0.7rem;">Tujuan:</small>
                                                <p class="mb-0 text-dark fw-bold" style="font-size: 0.75rem;"><?= esc($tx['alamat']) ?></p>
                                            </div>

                                            <hr class="my-2 border-light">

                                            <!-- Mini Product List -->
                                            <?php foreach ($tx['details'] as $detail): ?>
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="<?= base_url() . "img/" . esc($detail['foto']) ?>" class="rounded border p-1" style="width: 35px; height: 35px; object-fit: cover;" alt="...">
                                                    <div class="ms-2 flex-grow-1 overflow-hidden">
                                                        <p class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.75rem;"><?= esc($detail['nama']) ?></p>
                                                        <small class="text-muted" style="font-size: 0.65rem;"><?= $detail['jumlah'] ?>x <?= number_to_currency($detail['harga'], 'IDR') ?></small>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>

                                            <?php if (($tx['free_mouse'] ?? 0) > 0): ?>
                                                <div class="d-flex align-items-center mb-2 text-success">
                                                    <div class="bg-success bg-opacity-10 rounded border border-success border-opacity-25 p-1 d-flex align-items-center w-100">
                                                        <i class="bi bi-gift-fill me-2 ms-1"></i>
                                                        <div class="flex-grow-1">
                                                            <p class="mb-0 fw-bold" style="font-size: 0.75rem;">Hadiah: Free Mouse</p>
                                                            <small style="font-size: 0.65rem;">Bonus Belanja > 15 Juta</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <hr class="my-2 border-light">

                                            <?php
                                            $subtotal_produk = 0;
                                            if (!empty($tx['details'])) {
                                                foreach ($tx['details'] as $detail) {
                                                    $subtotal_produk += $detail['harga'] * $detail['jumlah'];
                                                }
                                            }
                                            ?>

                                            <!-- Compact Total -->
                                            <div class="bg-light p-2 rounded">
                                                <div class="d-flex justify-content-between" style="font-size: 0.7rem;">
                                                    <span class="text-muted">Subtotal:</span>
                                                    <span><?= number_to_currency($subtotal_produk, 'IDR') ?></span>
                                                </div>
                                                <?php if (($tx['biaya_jasa'] ?? 0) > 0): ?>
                                                    <div class="d-flex justify-content-between" style="font-size: 0.7rem;">
                                                        <span class="text-muted">Biaya Jasa:</span>
                                                        <span><?= number_to_currency($tx['biaya_jasa'], 'IDR') ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="d-flex justify-content-between" style="font-size: 0.7rem;">
                                                    <span class="text-muted">Ongkir:</span>
                                                    <span><?= number_to_currency($tx['ongkir'], 'IDR') ?></span>
                                                </div>
                                                <?php if (($tx['diskon_voucher'] ?? 0) > 0): ?>
                                                    <div class="d-flex justify-content-between text-danger" style="font-size: 0.7rem;">
                                                        <span>Diskon (<?= $tx['voucher_code'] ?>):</span>
                                                        <span>-<?= number_to_currency($tx['diskon_voucher'], 'IDR') ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (($tx['free_mouse'] ?? 0) > 0): ?>
                                                    <div class="d-flex justify-content-between text-success" style="font-size: 0.7rem;">
                                                        <span class="fw-bold"><i class="bi bi-gift me-1"></i>Free Mouse:</span>
                                                        <span>-<?= number_to_currency($tx['free_mouse'], 'IDR') ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="d-flex justify-content-between mt-1 pt-1 border-top border-secondary border-opacity-10 fw-bold">
                                                    <span style="font-size: 0.8rem;">TOTAL:</span>
                                                    <span class="text-primary" style="font-size: 0.8rem;"><?= number_to_currency($tx['total_harga'], 'IDR') ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 p-2 justify-content-center">
                                            <button type="button" class="btn btn-secondary btn-sm py-1 px-3" style="font-size: 0.65rem;" data-bs-dismiss="modal">Tutup</button>
                                            <button type="button" class="btn btn-primary btn-sm py-1 px-3 shadow-sm" style="font-size: 0.65rem;" onclick="window.print()">
                                                <i class="bi bi-printer me-1"></i>Cetak
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
