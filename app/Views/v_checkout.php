<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-6">
        <?= form_open('buy', 'class="row g-3"') ?>

        <?= form_hidden('username', session()->get('username')) ?>
        <?= form_input(['type' => 'hidden', 'name' => 'total_harga', 'id' => 'total_harga', 'value' => '']) ?>
        <div class="col-12">
            <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'nama',
                'id' => 'nama',
                'class' => 'form-control',
                'value' => session()->get('username'),
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'alamat',
                'id' => 'alamat',
                'class' => 'form-control'
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
            <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?>
            <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'ongkir',
                'id' => 'ongkir',
                'class' => 'form-control',
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Kode Voucher', 'voucher_code', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'voucher_code',
                'id' => 'voucher_code',
                'class' => 'form-control',
                'placeholder' => 'Contoh: PROMO2026'
            ]) ?>
            <small class="text-muted">Tersedia: PROMO2025 (10%), PROMO2026 (15%), AKHIRTAHUN (25%)</small>
        </div>
        <div class="col-12">
            <?= form_submit(
                'submit',
                'Buat Pesanan',
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

        <?= form_close() ?>
    </div>
    <div class="col-lg-6">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($items)):
                    foreach ($items as $index => $item):
                        ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
                <tr>
                    <td colspan="2"></td>
                    <td>Subtotal</td>
                    <td><?= number_to_currency($total, 'IDR') ?></td>
                </tr>

                <tr class="text-danger" id="row-diskon-voucher" style="display: none;">
                    <td colspan="2"></td>
                    <td>Diskon Voucher <span id="voucher_persen">(0%)</span></td>
                    <td>- IDR <span id="voucher_nominal">0</span></td>
                </tr>

                <tr class="text-info" id="row-biaya-jasa">
                    <td colspan="2"></td>
                    <td>Biaya Jasa</td>
                    <td>+ IDR <span id="biaya_jasa_nominal">0</span></td>
                </tr>

                <tr class="text-success" id="row-free-mouse" style="display: none;">
                    <td colspan="2"></td>
                    <td>Free Mouse</td>
                    <td>- IDR 150.000</td>
                </tr>

                <tr>
                    <td colspan="2"></td>
                    <td>Grand Total</td>
                    <td class="fw-bold"><span id="total_display"><?= number_to_currency($total, 'IDR') ?></span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->section('script') ?>
<script>
    $(document).ready(function () {
        let ongkir = 0;
        let subtotal = <?= $total ?>;
        hitungTotal();

        // Logika perhitungan UAS
        function hitungTotal() {
            let voucherCode = $("#voucher_code").val().toUpperCase();
            let voucherPersen = 0;
            
            if (voucherCode === 'PROMO2025') {
                voucherPersen = 10;
            } else if (voucherCode === 'PROMO2026') {
                voucherPersen = 15;
            } else if (voucherCode === 'AKHIRTAHUN') {
                voucherPersen = 25;
            }

            let diskonVoucher = (voucherPersen / 100) * subtotal;
            let biayaJasa = subtotal <= 10000000 ? subtotal * 0.01 : subtotal * 0.02;
            let freeMouse = subtotal > 15000000 ? 150000 : 0;

            let grandTotal = subtotal + biayaJasa - diskonVoucher - freeMouse + ongkir;

            // Update tampilan detail voucher
            if (voucherPersen > 0) {
                $("#row-diskon-voucher").show();
                $("#voucher_persen").text(`(${voucherPersen}%)`);
                $("#voucher_nominal").text(diskonVoucher.toLocaleString('id-ID'));
            } else {
                $("#row-diskon-voucher").hide();
            }

            // Update tampilan biaya jasa
            $("#biaya_jasa_nominal").text(biayaJasa.toLocaleString('id-ID'));

            // Update tampilan free mouse
            if (freeMouse > 0) {
                $("#row-free-mouse").show();
            } else {
                $("#row-free-mouse").hide();
            }

            // Update grand total
            $("#ongkir").val(ongkir);
            $("#total_display").text(`IDR ${grandTotal.toLocaleString('id-ID')}`);
            $("#total_harga").val(subtotal);
        }

        $("#voucher_code").on('input', function() {
            hitungTotal();
        });

        $('#kelurahan').select2({
            placeholder: 'Cari daerah tujuan',
            minimumInputLength: 3,
            ajax: {
                url: '<?= site_url('ajax/destinations') ?>',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return data;
                },
                cache: true
            }
        });
        $("#kelurahan").on('change', function () {
            let id_kelurahan = $(this).val();

            $("#layanan").empty();
            ongkir = 0;
            hitungTotal();

            console.log(id_kelurahan);
            $.ajax({
                url: "<?= site_url('ajax/costs') ?>",
                dataType: "json",
                data: {
                    destination: id_kelurahan
                },
                success: function (data) {
                    data.forEach(function (item) {
                        $("#layanan").append(
                            $('<option>', {
                                value: item.cost,
                                text: `${item.description} (${item.service}) : estimasi ${item.etd}`
                            })
                        );
                    });
                }
            });
        });
        $("#layanan").on('change', function () {
            ongkir = parseInt($(this).val());
            hitungTotal();
        });
    });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>