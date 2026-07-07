<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\RajaOngkirService;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $transactionModel;
    protected $transactionDetailModel;

    public function __construct()
    {
        helper(['number', 'form']);
        $this->cart = service('cart');
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
    }
    public function index()
    {
        $data = [
            'items' => $this->cart->contents(),
            'total' => $this->cart->total()
        ];

        return view('v_keranjang', $data);
    }

    public function cart_add()
    {
        $this->cart->insert([
            'id' => $this->request->getPost('id'),
            'qty' => 1,
            'price' => $this->request->getPost('harga'),
            'name' => $this->request->getPost('nama'),
            'options' => [
                'foto' => $this->request->getPost('foto')
            ]
        ]);

        session()->setFlashdata(
            'success',
            'Produk berhasil ditambahkan ke keranjang. 
	    <a href="' . base_url('keranjang') . '">Lihat</a>'
        );

        return redirect()->to(base_url('/'));
    }

    public function cart_edit()
    {
        $i = 1;
        foreach ($this->cart->contents() as $item) {
            $this->cart->update([
                'rowid' => $item['rowid'],
                'qty' => $this->request->getPost('qty' . $i++)
            ]);
        }

        session()->setFlashdata('success', 'Keranjang berhasil diperbarui.');

        return redirect()->to(base_url('keranjang'));
    }

    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);

        session()->setFlashdata(
            'success',
            'Produk berhasil dihapus dari keranjang'
        );

        return redirect()->to(base_url('keranjang'));
    }

    public function cart_clear()
    {
        $this->cart->destroy();

        session()->setFlashdata(
            'success',
            'Keranjang berhasil dikosongkan'
        );

        return redirect()->to(base_url('keranjang'));
    }

    public function checkout()
    {
        $service = new RajaOngkirService();
        $response = $service->getDestination('semarang');
        $response2 = $service->getCost('64999', '65042', '1000', 'jne');
        $data = [
            'items' => $this->cart->contents(),
            'total' => $this->cart->total(),
            'response' => $response,
            'response2' => $response2
        ];

        return view('v_checkout', $data);
    }

    public function destinations()
    {
        $search = $this->request->getGet('q');

        if (empty($search)) {
            return $this->response->setJSON([
                'results' => []
            ]);
        }

        $service = new RajaOngkirService();
        $response = $service->getDestination($search);

        $results = [];
        $data = $response['data'] ?? [];

        foreach ($data as $item) {
            $results[] = [
                'id' => $item['id'],
                'text' => $item['label']
            ];
        }
        return $this->response->setJSON([
            'results' => $results
        ]);
    }
    public function costs()
    {
        $origin = '64999';
        $destination = $this->request->getGet('destination');
        $weight = '1000';
        $courier = 'jne';

        $service = new RajaOngkirService();
        $response = $service->getCost($origin, $destination, $weight, $courier);

        $results = [];
        $data = $response['data'] ?? [];

        foreach ($data as $item) {
            $results[] = [
                'service' => $item['service'],
                'description' => $item['description'],
                'cost' => $item['cost'],
                'etd' => $item['etd']
            ];
        }

        return $this->response->setJSON($results);
    }
    public function buy()
    {
        $cartItems = $this->cart->contents();

        if (empty($cartItems)) {
            return redirect()->back();
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['qty'] * $item['price'];
        }

        $ongkir = (int) $this->request->getPost('ongkir');

        // Hitung diskon menggunakan helper
        require_once APPPATH . 'Helpers/DiskonHelper.php';
        
        $voucher_code = $this->request->getPost('voucher_code');
        $biaya_jasa = hitung_biaya_jasa($subtotal);
        $diskon_voucher = hitung_diskon_voucher($subtotal, $voucher_code);
        $free_mouse = hitung_free_mouse($subtotal);
        
        $diskon_data = hitung_diskon($subtotal);
        $nominal_diskon = $diskon_voucher + $free_mouse;

        $transaction = [
            'username' => $this->request->getPost('username'),
            'alamat' => $this->request->getPost('alamat'),
            'ongkir' => $ongkir,
            'diskon' => $nominal_diskon,
            'biaya_jasa' => $biaya_jasa,
            'voucher_code' => $voucher_code,
            'diskon_voucher' => $diskon_voucher,
            'free_mouse' => $free_mouse,
            'total_harga' => $subtotal + $biaya_jasa - $diskon_voucher - $free_mouse + $ongkir,
            'status' => 0,
        ];

        // insert transaction
        if (!$this->transactionModel->insert($transaction)) {
            $errors = $this->transactionModel->errors();
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal Header: ' . implode(', ', $errors));
        }

        $transactionId = $this->transactionModel->getInsertID();

        // insert transaction detail
        $productModel = new \App\Models\ProductModel();
        foreach ($cartItems as $item) {
            $resDetail = $this->transactionDetailModel->insert([
                'transaction_id' => $transactionId,
                'product_id' => $item['id'],
                'jumlah' => $item['qty'],
                'diskon' => 0,
                'subtotal_harga' => $item['qty'] * $item['price']
            ]);

            if (!$resDetail) {
                $errors = $this->transactionDetailModel->errors();
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Gagal Detail: ' . implode(', ', $errors));
            }

            // Kurangi stok produk
            $product = $productModel->find($item['id']);
            if ($product) {
                $newStock = max(0, $product['jumlah'] - $item['qty']);
                $productModel->update($item['id'], ['jumlah' => $newStock]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal transaksi database (Rollback)');
        }

        // hapus session keranjang belanja 
        $this->cart->destroy();
        session()->setFlashdata('success', 'Pesanan Anda dengan ID #' . $transactionId . ' berhasil dibuat! Silakan cek detailnya di sini.');
        return redirect()->to(base_url('history'));
    }

    public function history()
    {
        $username = session()->get('username');
        $role = session()->get('role');

        if ($role == 'admin') {
            // Admin melihat semua transaksi
            $transactions = $this->transactionModel
                ->orderBy('id', 'DESC')
                ->findAll();
        } else {
            // Pengguna biasa hanya melihat miliknya sendiri
            $transactions = $this->transactionModel
                ->where('username', $username)
                ->orderBy('id', 'DESC')
                ->findAll();
        }

        foreach ($transactions as &$tx) {
            $tx['details'] = $this->transactionDetailModel
                ->select('transaction_detail.*, product.nama, product.foto, product.harga')
                ->join('product', 'product.id = transaction_detail.product_id', 'left')
                ->where('transaction_id', $tx['id'])
                ->findAll();
        }

        return view('v_history', ['transactions' => $transactions]);
    }

    public function updateStatus($id, $status)
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('/'));
        }

        $tx = $this->transactionModel->find($id);
        if (!$tx) {
            session()->setFlashdata('failed', 'Transaksi tidak ditemukan.');
            return redirect()->to(base_url('/'));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->transactionModel->update($id, ['status' => $status]);

        // Kirim notifikasi ke user
        $notifModel = new \App\Models\NotificationModel();
        $statusText = ($status == 1) ? 'DISETUJUI' : 'DIBATALKAN';
        $notifModel->insert([
            'username' => $tx['username'],
            'message' => "Pesanan Anda #$id telah $statusText oleh Admin.",
            'is_read' => 0
        ]);

        // Jika status diubah menjadi batal (2) dan sebelumnya bukan batal, kembalikan stok produk
        if ($status == 2 && $tx['status'] != 2) {
            $productModel = new \App\Models\ProductModel();
            $details = $this->transactionDetailModel->where('transaction_id', $id)->findAll();
            foreach ($details as $detail) {
                $product = $productModel->find($detail['product_id']);
                if ($product) {
                    $productModel->update($detail['product_id'], [
                        'jumlah' => $product['jumlah'] + $detail['jumlah']
                    ]);
                }
            }
        }

        $db->transComplete();

        if ($db->transStatus()) {
            session()->setFlashdata('success', 'Status transaksi #' . $id . ' berhasil diperbarui.');
        } else {
            session()->setFlashdata('failed', 'Gagal memperbarui status transaksi.');
        }

        return redirect()->to(base_url('/'));
    }
}
