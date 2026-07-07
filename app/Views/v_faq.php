<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <p class="text-muted">Temukan jawaban atas pertanyaan-pertanyaan yang sering diajukan mengenai layanan JIPON.</p>
        
        <!-- Accordion without outline borders -->
        <div class="accordion accordion-flush" id="faqAccordion">
            <div class="accordion-item py-2">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed fw-semibold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne" aria-expanded="false" aria-controls="faqOne">
                        <i class="bi bi-question-circle me-2 text-info"></i> Apa itu JIPON?
                    </button>
                </h2>
                <div id="faqOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-secondary">
                        JIPON adalah platform e-commerce modern yang menyediakan berbagai macam produk berkualitas tinggi dengan sistem pemesanan yang cepat, aman, dan terpercaya.
                    </div>
                </div>
            </div>
            
            <div class="accordion-item py-2">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed fw-semibold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo" aria-expanded="false" aria-controls="faqTwo">
                        <i class="bi bi-question-circle me-2 text-info"></i> Bagaimana cara melakukan pemesanan?
                    </button>
                </h2>
                <div id="faqTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-secondary">
                        Anda hanya perlu memilih produk dari halaman utama, menambahkannya ke keranjang belanja, menekan tombol beli, mengisi detail alamat pengiriman, menghitung ongkos kirim, dan mengonfirmasi pemesanan.
                    </div>
                </div>
            </div>

            <div class="accordion-item py-2">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed fw-semibold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree" aria-expanded="false" aria-controls="faqThree">
                        <i class="bi bi-question-circle me-2 text-info"></i> Kurir pengiriman apa saja yang didukung?
                    </button>
                </h2>
                <div id="faqThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-secondary">
                        Saat ini kami menggunakan layanan integrasi RajaOngkir dengan kurir utama seperti JNE, POS Indonesia, dan TIKI untuk mengirimkan barang ke seluruh wilayah Indonesia.
                    </div>
                </div>
            </div>

            <div class="accordion-item py-2">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed fw-semibold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#faqFour" aria-expanded="false" aria-controls="faqFour">
                        <i class="bi bi-question-circle me-2 text-info"></i> Bagaimana cara mengetahui status pesanan saya?
                    </button>
                </h2>
                <div id="faqFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-secondary">
                        Anda dapat masuk ke halaman <strong>Riwayat Belanja</strong> untuk melihat detail pemesanan beserta status terkini dari pesanan Anda (Pending, Sukses, atau Batal).
                    </div>
                </div>
            </div>

            <div class="accordion-item py-2">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed fw-semibold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#faqFive" aria-expanded="false" aria-controls="faqFive">
                        <i class="bi bi-question-circle me-2 text-info"></i> Apakah saya bisa merubah alamat pengiriman setelah membeli?
                    </button>
                </h2>
                <div id="faqFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-secondary">
                        Perubahan alamat hanya dapat dilakukan sebelum status pesanan diproses menjadi "Sukses". Silakan hubungi kami segera melalui halaman Kontak jika Anda perlu merubah informasi pengiriman Anda.
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
