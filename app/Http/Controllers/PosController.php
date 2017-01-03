<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Barang;
use App\Member;
use App\Order;
use App\Poin;
use App\Diskon;
use App\OrderTemp;
use App\Tranksaksi;
use App\TukarPoin;
use App\BarangKeluar;
use App\TranksaksiTemp;
use App\LabaRugi;
use App\Hadiah;

class PosController extends Controller
{
  private $totalBelanja;
  private $grandTotal;
  private $idMember;
  private $diskon;
  private $nota;
  private $poin;
  private $hargaBarang;

  public function dashboard()
  {
    $member = Member::where('nama_member', '!=', 'Guest')
                    ->get();

    $barang = Hadiah::where('bobot_poin', '!=', null)
                    ->where('stok', '!=', 0)
                    ->get();

    $orderQuery = "
    SELECT order_temps.created_at,order_temps.nota_id, members.nama_member, order_temps.total, members.id
    FROM order_temps, members
    WHERE order_temps.member_id = members.id
    ";

    $order = DB::select(DB::raw($orderQuery));

    return view('backend.index',[
      'order' => $order,
      'barang' => $barang,
      'member' => $member
    ]);
  }

  public function index($nota = null, $idMember = null)
  {
    if ($nota == null && $idMember == null)
    {
      $generateNota = $this->generateNota();
      $notaId = $this->setNota($generateNota);
      $nota = $this->getNota();

      $member = $this->memberGuest();

      $this->setMember($member->id);
      $idMember = $this->getMember();

      return redirect()->to('admin/pos/'.$nota.'/'.$member->id);
    }

    $barang = Barang::where('stok', '!=', 0)->get();

    $member = Member::where('nama_member', '!=', 'Guest')->get();

    $queryOrder = "SELECT orders.nota_id, barangs.nama_barang, barangs.harga_jual, barangs.id as barang_id, orders.qty, orders.total, orders.id
                   FROM barangs, orders
                   WHERE orders.barang_id = barangs.id
                   AND orders.nota_id = $nota";


    $poinQuery = "
      SELECT orders.total, barangs.id as barang_id
      FROM orders, barangs
      WHERE nota_id = $nota
      AND orders.barang_id = barangs.id
    ";

    $poinList = DB::select(DB::raw($poinQuery));

    $harga = 0;

    $memberGuest = $this->memberGuest();

    if ($idMember != $memberGuest->id) {
      foreach ($poinList as $poin) {
        $opsi_tukarpoin = Barang::findOrFail($poin->barang_id);
        if ($opsi_tukarpoin->opsi_tukarpoin == 'yes') {
          $harga = $harga + $poin->total;
        }
      }
    }

    $this->setPoin($harga, $idMember);

    $this->hitungSubtotal($nota);

    $this->hitungGrandTotal($this->getDiskon(), $this->getSubTotal());

    $order = DB::select(DB::raw($queryOrder));

    return view('backend.posbackup',[
      'nota' => $this->setNota($nota),
      'barang' => $barang,
      'member' => $member,
      'order' => $order,
      'grand_total' => $this->getGrandTotal(),
      'total' => $this->getSubTotal(),
      'diskon' => $this->getDiskon(),
      'member_id' => $idMember,
      'poin' => $this->getPoin()
    ]);
  }

  public function detailNota($nota)
  {

    $tranksaksiTempQuery = "
      SELECT tranksaksi_temps.created_at, tranksaksi_temps.nota_id, tranksaksi_temps.faktur_id, users.name, members.nama_member, tranksaksi_temps.total, tranksaksi_temps.subtotal
      FROM tranksaksi_temps, members, users
      WHERE tranksaksi_temps.member_id = members.id
      AND tranksaksi_temps.user_id = users.id
      AND tranksaksi_temps.nota_id = $nota
    ";

    $tranksaksiQuery = "
      SELECT tranksaksis.created_at, tranksaksis.nota_id ,barangs.nama_barang, tranksaksis.harga_pokok, tranksaksis.harga_umum, tranksaksis.harga_khusus, tranksaksis.qty, tranksaksis.total, barangs.id as barang_id, members.id as member_id
      FROM barangs, tranksaksis, members
      WHERE tranksaksis.nota_id = $nota
      AND tranksaksis.member_id = members.id
      AND tranksaksis.barang_id = barangs.id
      ORDER BY tranksaksis.created_at DESC
    ";

    $tranksaksi = DB::select(DB::raw($tranksaksiQuery));
    // $tranksaksi_temp = DB::select(DB::raw($tranksaksiTempQuery));
    $tranksaksi_temp = TranksaksiTemp::where('nota_id', '=', $nota)->first();


    return view('backend.report.tranksaksibyid',[
      'tranksaksi' => $tranksaksi,
      'ket_tranksaksi' => $tranksaksi_temp,
    ]);
  }

  private function getGuest()
  {
    $member = Member::where('nama_member','=','Guest')->get();
    return $member->id;
  }

  private function setMember($idMember)
  {
    return $this->idMember = $idMember;
  }

  private function memberGuest()
  {
    return $member = Member::where('nama_member', '=', 'Guest')->first();
  }

  public function applyMember(Request $request)
  {
    $nota = $request->input('nota');
    $idMember = $request->input('member_id');

    if ($idMember == "") {
      $request->session()->flash('alert-warning', 'Silahkan pilih salah satu member');
      return redirect()->back();
    }

    //Logikanya... Jika belum ada data dalam table order berdasarkan id atau nota makan muncul alert
    $orderQuery = "SELECT *
                   FROM orders
                   WHERE nota_id = $nota
                  ";
    $orders = DB::select(DB::raw($orderQuery));
    $count = count($orders);

    $order = "";

    if ($count == 1) {
      $order = Order::where('nota_id', '=', $nota)->get();
    }
    if ($count > 1) {
      $order = Order::where('nota_id', '=', $nota)->first();
    }

    if (!$order) {
      $request->session()->flash('alert-warning', 'Silahkan tambahkan barang terlebih dahulu sebelum menambahkan member');
      return redirect()->back();
    } else {
      $this->setOrder($idMember, $nota);

      return redirect()->to('admin/pos/'.$nota.'/'.$idMember);
    }
  }

  private function setHarga($member_id, $barang_id)
  {
    $member = Member::findOrFail($member_id);
    $barang = Barang::findOrFail($barang_id);

    $hargaBarang = $barang->harga_khusus;

    if ($member->nama_member != 'Guest'){
      if ($hargaBarang == null) {
        return $this->hargaBarang = $barang->harga_jual;
      } else {
        return $this->hargaBarang = $hargaBarang;
      }
    } else {
      return $this->hargaBarang = $barang->harga_jual;
    }

  }

  private function getHarga()
  {
    return $this->hargaBarang;
  }

  private function setOrder($idMember, $nota)
  {
    $order = Order::where('nota_id', $nota)->get();

    $total = 0;

    foreach ($order as $listOrder) {
      //
      $barang = Barang::findOrFail($listOrder->barang_id);
      //
      $this->setHarga($idMember, $barang->id);

      $listOrder->total = $listOrder->qty * $this->getHarga();
      $listOrder->member_id = $idMember;
      $listOrder->save();

      $total = $total + $listOrder->total;
    }

    $orderTemp = OrderTemp::where('nota_id', '=', $nota)->first();
    $orderTemp->member_id = $idMember;
    $orderTemp->total = $total;
    $orderTemp->save();
  }

  private function unsetOrder($idMember, $nota)
  {
    $order = Order::where('nota_id', $nota)->get();

    $total = 0;

    foreach ($order as $listOrder) {
      $barang = Barang::findOrFail($listOrder->barang_id);
      //
      $this->setHarga($idMember, $barang->id);

      $listOrder->total = $listOrder->qty * $this->getHarga();

      $listOrder->member_id = $idMember;
      $listOrder->save();

      $total = $total + $listOrder->total;
    }

    $orderTemp = OrderTemp::where('nota_id', $nota)->first();
    $orderTemp->member_id = $idMember;
    $orderTemp->total = $total;
    $orderTemp->poin_temp = 0;
    $orderTemp->save();
  }

  public function unsetMember(Request $request)
  {
    $idMember = $request->input('member_id');

    $nota = $request->input('nota');

    $this->unsetOrder($idMember, $nota);

    return redirect()->to('admin/pos/'.$nota.'/'.$idMember);
  }

  private function getMember()
  {
    return $this->idMember;
  }

  private function setNota($nota)
  {
    return $this->nota = $nota;
  }

  private function getNota()
  {
    return $this->nota;
  }

  //Save temporary order. Table: Orders
  public function saveOrder(Request $request)
  {

    $messages = [
      'barang_id.required' => 'Pilih barang untuk dimasukkan kedalam order',
      'qty.required' => 'Qty (qty) tidak boleh kurang dari 1'
    ];

    $rules = [
      'barang_id' => 'required|integer',
      'qty' => 'required|integer'
    ];

    $this->validate($request, $rules, $messages);

    $this->setHarga($request->input('member_id'), $request->input('barang_id'));
    
    $memberId = $request->input('member_id');

    $this->setMember($memberId);
    $this->getMember();

    $barang = Barang::find($request->input('barang_id'));
    $order = Order::where('barang_id', $request->barang_id)
                  ->where('nota_id', $request->nota_id)
                  ->first();

    if ($request->input('qty') > $barang->stok) {
      $request->session()->flash('alert-danger', 'Oppps! Quantity melebih stok yang tersedia');
      return redirect()->back()->withInput();
    }

    $poin = 0;
    $hargaUntukPoin = 0;

    if ($barang->opsi_tukarpoin == 'yes') {
      $hargaUntukPoin = $hargaUntukPoin + $this->getHarga();
    } else {
      $hargaUntukPoin = 0;
    }

    $this->setPoin($hargaUntukPoin, $request->input('member_id'));

    // return $hargaUntukPoin;
    // return $this->getPoin();

    if ($order) {
      $order->qty = $order->qty + $request->input('qty');
      $total = $request->input('qty') * $this->getHarga();
      $order->total = $order->total + $total;
      $order->save();

      //Hitung OrderTemp

      $orderTemp = OrderTemp::where('nota_id', $request->input('nota_id'))->first();

      if ($orderTemp) {
        $orderTemp->total = $orderTemp->total + $total;
        $orderTemp->poin_temp = $orderTemp->poin_temp + $this->getPoin();
        $orderTemp->save();
      }
      //End Hitung ORderTemp

      return redirect()->back();

    } else {

      if ($memberId == "") {
        $member = Member::where('nama_member', 'Guest')->first();
      } else {
        $member = Member::findOrFail($request->input('member_id'));
      }

      $order = new Order;
      $order->nota_id = $request->input('nota_id');
      $order->member_id = $member->id;
      $order->barang_id = $request->input('barang_id');
      $order->qty = $request->input('qty');
      $order->total = $this->getHarga() * $request->input('qty');
      $order->save();

      $nota = $request->input('nota_id');

      //Hitung OrderTemp
      $totalQuery = "SELECT orders.total as total
      FROM orders
      WHERE orders.nota_id = $nota";

      $totalBelanja = 0;

      $total = DB::select(DB::raw($totalQuery));

      foreach ($total as $listTotal) {
        $totalBelanja = $totalBelanja + $listTotal->total;
      }



      // $this->hitungPoin($request->input('member_id'), $request->input('barang_id'), $request->input('qty'), $nota);



      $orderTemp = OrderTemp::where('nota_id', $request->input('nota_id'))->first();

      if ($orderTemp) {
        $orderTemp->nota_id = $request->input('nota_id');
        $orderTemp->member_id = $request->input('member_id');
        $orderTemp->total = $totalBelanja;
        $orderTemp->poin_temp = $orderTemp->poin_temp + $this->getPoin();
        $orderTemp->save();
      } else {
        $orderTemp = new OrderTemp;
        $orderTemp->nota_id = $request->input('nota_id');
        $orderTemp->member_id = $request->input('member_id');
        $orderTemp->total = $totalBelanja;

        $orderTemp->save();
      }
      //End Hitung OrderTemp
      return redirect()->back();
    }

  }

  private function hitungPoin($member_id, $barang_id, $qty, $nota)
  {
    return $this->setHarga($member_id, $barang_id);

    $barang = Barang::findOrFail($barang_id);
    $member = Member::findOrFail($member_id);
    $poin = Poin::all();

    $totalPoin = OrderTemp::where('nota_id', $nota)->first();

    $pointotal = 0;

    if ($totalPoin) {
      if (!$barang->opsi_tukarpoin == 'yes') {
        return $this->poin = $totalPoin->poin_temp + $pointotal;
      } else {
        if ($member->nama_member == 'Guest') {
          return $this->poin = $totalPoin->poin_temp + $this->getHarga() * $qty;
        } else {
          return $this->poin = $totalPoin->poin_temp + $this->getHarga() * $qty;
        }
      }
    } else {
      $poinTotal = 0;
    }

  }

  private function getPoin()
  {
    return $this->poin;
  }

  public function deleteItem($order_id, $barang_id)
  {
    $order = Order::where('nota_id', $order_id)
                  ->where('barang_id', $barang_id)
                  ->first();
    
    $penguranganHarga = $order->total;

    $orderTemp = OrderTemp::where('nota_id', $order_id)->first();
    $orderTemp->total = $orderTemp->total - $penguranganHarga;
    $orderTemp->save();

    $order->delete();

    if ($orderTemp->total == 0) {
      $orderTemp->delete();
    }

    if($order)
    {
      return redirect()->back();
    }
  }

  public function deleteOrder($nota)
  {
    $order = Order::where('nota_id', '=', $nota);
    $order->delete();
    $orderTemp = OrderTemp::where('nota_id', '=', $nota);
    $orderTemp->delete();

    if ($order && $orderTemp) {
      return redirect()->back()->with('successMessage', 'Sukses menghapus order ');
    }
  }

  public function updateQty(Request $request)
  {
    // return $request->all();

    if ($request->input('qty') < 1) {
      $request->session()->flash('alert-danger', 'Quantity (qty) tidak boleh kurang dari 1');
      return redirect()->back();
    }

    $this->setHarga($request->input('member_id'), $request->input('barang_id'));

    $barangId = $request->input('barang_id');
    $barang = Barang::findOrFail($barangId);

    $order = Order::findOrFail($request->input('id'));
    $order->qty = $request->input('qty');
    $order->total = $this->getHarga() * $request->input('qty');
    $order->save();

    $this->hitungPoin($request->input('member_id'), $request->input('barang_id'), $request->input('qty'), $request->input('nota'));

    $orderTemp = OrderTemp::where('nota_id', $request->input('nota'))->first();
    $orderTemp->total = $orderTemp->total - $this->getHarga();
    $orderTemp->save();

    if($order)
    {
      return redirect()->back();
    }
  }
  //Save permanent Tranksasksi, table: Tranksaksi
  public function saveTranksaksi(Request $request)
  {
    if ($request->input('grand_total') == 0) {
      $request->session()->flash('success', 'Keranjang belanja masih kosong, silahkan tambah');
      return redirect()->back();
    }

    $user_id = $request->input('user_id');
    $nota_id = $request->input('nota_id');
    $member_id = $request->input('member_id');
    $barang_id = $request->input('barang_id');
    $qty = $request->input('qty');
    $total = $request->input('total');

    if (!$request->input('diskon') == "") {
      $diskon = $request->input('diskon');
    } else {
      $diskon = 0;
    }

    $this->hitungGrandTotal($request->input('diskon'), $request->input('grand_total'));
    $grandTotal = $this->getGrandTotal();

    if ($request->input('bayar') < $grandTotal) {
      $request->session()->flash('alert-danger', 'Jumlah bayar kurang');
      return redirect()->back();
    }

    $count = count($barang_id);

    $grandTotal = 0;
    $omset = 0;

    $modal = 0;

    for ($i=0; $i < $count ; $i++) {

      $this->hitungGrandTotal($request->input('diskon'), $total[$i]);

      $barang = Barang::findOrFail($barang_id[$i]);
      $barang->stok = $barang->stok - $qty[$i];
      $barang->save();

      $member = Member::findOrFail($member_id);
      //Menentukan Harga Barang
      $harga_khusus = 0;

      if ($member->nama_member != "Guest") {
        if ($barang->harga_khusus == "") {
          $harga_khusus = 0;
        } else {
          $harga_khusus = $barang->harga_khusus;
        }
      }

      
      //Selesai menentukan harga barang

      $tranksaksi = new Tranksaksi;
      $tranksaksi->user_id = $user_id;
      $tranksaksi->nota_id = $nota_id;
      $tranksaksi->member_id = $member_id;
      $tranksaksi->barang_id = $barang_id[$i];
      $tranksaksi->qty = $qty[$i];
      $tranksaksi->harga_pokok = $barang->harga;
      $tranksaksi->harga_umum = $barang->harga_jual;
      $tranksaksi->harga_khusus = $harga_khusus;
      $tranksaksi->total = $this->getGrandTotal();
      $tranksaksi->save();

      

      $grandTotal = $grandTotal + $this->getGrandTotal();
      $omset = $omset + ($barang->harga * $qty[$i]);

      $barangKeluar = new BarangKeluar;
      $barangKeluar->user_id = $user_id;
      $barangKeluar->member_id = $member_id;
      $barangKeluar->barang_id = $barang_id[$i];
      $barangKeluar->stok_keluar = $qty[$i];
      $barangKeluar->tranksaksi = 'Bayar';
      $barangKeluar->save();

      $modal = $modal + $barang->harga * $qty[$i];

    }

    //Menentukan Poin
    
    //Selesai Menentukan Poin

    //Memasukkan Tranksaksi Ke LabaRugi
    $laba = [
      'omset' => $grandTotal,
      'modal' => $omset,
      'laba' => $grandTotal - $omset
    ];

    $omsetData = $grandTotal;
    $modalData = $omset;
    $labaData = $grandTotal - $omset;

    $thisYear = date('Y');
    $thisMonth = date('m');
    $thisDay = date('d');

    $labaRugi = LabaRugi::whereYear('created_at', $thisYear)
                        ->whereMonth('created_at', $thisMonth)
                        ->whereDay('created_at', $thisDay)
                        ->first();

    if ($labaRugi) {
      $labaRugi->omset = $labaRugi->omset + $omsetData;
      $labaRugi->modal = $labaRugi->modal + $modalData;
      $labaRugi->laba = $labaRugi->laba + $labaData;
      $labaRugi->save();
    } else {
      $labaRugi = new LabaRugi;
      $labaRugi->omset = $omsetData;
      $labaRugi->modal = $modalData;
      $labaRugi->laba = $labaData;
      $labaRugi->save();
    }
    //Selesai Memasukkan

    $member = Member::findOrFail($member_id);
    $member->poin = $member->poin + $request->input('poin');
    $member->save();

    $this->hitungGrandTotal($request->input('diskon'), $request->input('grand_total'));

    $tranksaksiTemp = new TranksaksiTemp;
    $tranksaksiTemp->nota_id = $nota_id;
    $tranksaksiTemp->faktur_id = $this->generateFaktur();
    $tranksaksiTemp->user_id = $user_id;
    $tranksaksiTemp->member_id = $member_id;
    $tranksaksiTemp->modal = $modal;
    $tranksaksiTemp->subtotal = $request->input('grand_total');
    $tranksaksiTemp->laba = $request->input('grand_total') - $modal;    
    $tranksaksiTemp->total = $this->getGrandTotal();
    $tranksaksiTemp->save();

    $order = Order::where('nota_id', '=', $nota_id)->get();
    foreach ($order as $listOrder) {
      $listOrder->delete();
    }
    $orderTemp = OrderTemp::where('nota_id', '=', $nota_id)->first();
    $orderTemp->delete();

    $request->session()->flash('alert-success', 'Tranksaksi telah berhasil diproses');
    return redirect()->to('/');
  }

  public function tukarPoin(Request $request)
  {
    $barang = Hadiah::findOrFail($request->input('barang_id'));

    $member = Member::findOrFail($request->input('member_id'));

    if ($member->poin < $barang->bobot_poin) {
      $request->session()->flash('alert-danger', 'Oppsss! Poin tidak mencukupi untuk menukarkan barang dengan poin');
      return redirect()->back();
    }

    $tukarPoin = new TukarPoin;
    $tukarPoin->member_id = $member->id;
    $tukarPoin->barang_id = $barang->id;
    $tukarPoin->user_id = $request->input('user_id');
    $tukarPoin->save();

    $member->poin = $member->poin - $barang->bobot_poin;
    $member->save();

    $barang->stok = $barang->stok - 1;
    $barang->save();

    if ($tukarPoin) {
      $request->session()->flash('alert-success', 'Member '.$member->nama_member.' telah menukarkan poin sebanyak '.$barang->bobot_poin.' dengan barang '.$barang->nama_barang);
      return redirect()->back();
    }
  }

  private function generateNota()
  {
    $acak = rand(0000,9999);
    $tanggal = date('d');
    $bulan = date('m');
    $tahun = date('y');

    $nota = $tanggal.$bulan.$tahun.$acak;

    return $nota;
  }

  private function generateFaktur()
  {
    $acak = rand(000,999);
    $tanggal = date('d');
    $bulan = date('m');
    $tahun = date('y');

    $faktur = 'F'.$tahun.$bulan.$acak;

    return $faktur;
  } 

  private function setPoin($belanja, $idMember = null)
  {
    $member = $this->memberGuest();

    if ($idMember == $member->id) {
      return 0;
    } else {
      $poin = Poin::all()->sortByDesc('harga_belanja');

      foreach ($poin as $poinList) {
        if ($belanja > $poinList->harga_belanja) {
          $this->poin = $poinList->poin;
          return $this->poin;
        }
      }
    }

  }

  

  private function setDiskon($belanja, $idMember = null)
  {
    $member = $this->memberGuest();

    if ($idMember == $member->id) {
      return 0;
    } else {
      $diskon = Diskon::all()->sortByDesc('harga_belanja');

      foreach ($diskon as $diskonList) {
        if ($belanja > $diskonList->harga_belanja) {
          $this->diskon = $diskonList->diskon;
          return $this->diskon;
        }
      }
    }

  }

  public function getDiskon()
  {
    return $this->diskon;
  }

  private function hitungSubtotal($nota)
  {
    $totalQuery = "SELECT orders.total as total
    FROM orders
    WHERE orders.nota_id = $nota";

    $totalBelanja = 0;

    $total = DB::select(DB::raw($totalQuery));
    foreach ($total as $listTotal) {
      $totalBelanja = $totalBelanja + $listTotal->total;
    }

    return $this->totalBelanja = $totalBelanja;
  }

  private function getSubTotal()
  {
    return $this->totalBelanja;
  }

  private function hitungGrandTotal($diskon, $totalBelanja)
  {
    $grandTotal = $totalBelanja - ($totalBelanja * $diskon/100);
    return $this->grandTotal = $grandTotal;
  }

  private function getGrandTotal()
  {
    return $this->grandTotal;
  }

}
