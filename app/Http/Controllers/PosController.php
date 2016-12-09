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

class PosController extends Controller
{
  private $totalBelanja;
  private $grandTotal;
  private $idMember;
  private $diskon;
  private $nota;
  private $poin;

  public function dashboard()
  {
    $member = Member::where('nama_member', '!=', 'Guest')
                    ->where('poin', '!=', null)
                    ->get();

    $barang = Barang::where('bobot_poin', '!=', null)
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
    $barang = Barang::where('stok', '!=', 0)
                    ->get();

    $member = Member::where('nama_member', '!=', 'Guest')->get();

    $queryOrder = "SELECT orders.nota_id, barangs.nama_barang, barangs.harga_jual, barangs.id as barang_id, orders.qty, orders.total, orders.id
    FROM barangs, orders
    WHERE orders.barang_id = barangs.id
    AND orders.nota_id = $nota
    ";

    $this->hitungSubtotal($nota);

    $this->setDiskon($this->getSubTotal(), $idMember);

    $this->setPoin($this->getSubTotal(), $idMember);

    $this->hitungGrandTotal($this->getDiskon(), $this->getSubTotal());

    $this->getPoin();

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

    $tranksaksiQuery = "
      SELECT tranksaksis.created_at, tranksaksis.nota_id ,barangs.nama_barang, barangs.harga_jual as harga ,tranksaksis.qty, tranksaksis.total, tranksaksis.diskon, tranksaksis.subtotal
      FROM barangs, tranksaksis, members
      WHERE tranksaksis.nota_id = $nota
      AND tranksaksis.member_id = members.id
      AND tranksaksis.barang_id = barangs.id
    ";

    $tranksaksi = DB::select(DB::raw($tranksaksiQuery));
    return view('backend.report.tranksaksibyid',[
      'tranksaksi' => $tranksaksi
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

  private function setOrder($idMember, $nota)
  {
    $order = Order::where('nota_id', $nota)->get();

    foreach ($order as $listOrder) {
      $listOrder->member_id = $idMember;
      $listOrder->save();
    }

    $orderTemp = OrderTemp::where('nota_id', '=', $nota)->first();
    $orderTemp->member_id = $idMember;
    $orderTemp->save();
  }

  private function unsetOrder($idMember, $nota)
  {
    $order = Order::where('nota_id', $nota)->get();
    foreach ($order as $listOrder) {
      $listOrder->member_id = $idMember;
      $listOrder->save();
    }

    $orderTemp = OrderTemp::where('nota_id', $nota)->first();
    $orderTemp->member_id = $idMember;
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
    if ($request->barang_id == null) {
      $request->session()->flash('alert-warning', 'Pilih barang untuk dimasukkan kedalam order');
      return redirect()->back()->withInputs();
    } else if ($request->input('qty') < 1) {
      $request->session()->flash('alert-danger', 'Quantity (qty) tidak boleh kurang dari 1');
      return redirect()->back();
    }

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

    if ($order) {
      $order->qty = $order->qty + $request->input('qty');
      $total = $request->input('qty') * $barang->harga_jual;
      $order->total = $order->total + $total;
      $order->save();

      //Hitung OrderTemp

      $orderTemp = OrderTemp::where('nota_id', $request->input('nota_id'))->first();

      if ($orderTemp) {
        $orderTemp->total = $orderTemp->total + $total;
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
      $order->total = $barang->harga_jual * $request->input('qty');
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

      $orderTemp = OrderTemp::where('nota_id', $request->input('nota_id'))
      ->first();

      if ($orderTemp) {
        $orderTemp->nota_id = $request->input('nota_id');
        $orderTemp->member_id = $request->input('member_id');
        $orderTemp->total = $totalBelanja;
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

  public function deleteItem($id)
  {
    $order = Order::findOrFail($id);
    $order->delete();

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
      return redirect()->back();
    }
  }

  public function updateQty(Request $request)
  {
    if ($request->input('qty') < 1) {
      $request->session()->flash('alert-danger', 'Quantity (qty) tidak boleh kurang dari 1');
      return redirect()->back();
    }

    $barangId = $request->input('barang_id');
    $barang = Barang::findOrFail($barangId);
    $order = Order::findOrFail($request->input('id'));

    $order->qty = $request->input('qty');
    $order->total = $barang->harga_jual * $request->input('qty');
    $order->save();
    if($order)
    {
      return redirect()->back();
    }
  }
  //Save permanent Tranksasksi, table: Tranksaksi
  public function saveTranksaksi(Request $request)
  {

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
    for ($i=0; $i < $count ; $i++) {

      $this->hitungGrandTotal($request->input('diskon'), $total[$i]);

      $tranksaksi = new Tranksaksi;
      $tranksaksi->user_id = $user_id;
      $tranksaksi->nota_id = $nota_id;
      $tranksaksi->member_id = $member_id;
      $tranksaksi->barang_id = $barang_id[$i];
      $tranksaksi->qty = $qty[$i];
      $tranksaksi->subtotal = $total[$i];
      $tranksaksi->diskon = $diskon;
      $tranksaksi->total = $this->getGrandTotal();
      $tranksaksi->save();

      $barang = Barang::findOrFail($barang_id[$i]);
      $barang->stok = $barang->stok - $qty[$i];
      $barang->save();

      $barangKeluar = new BarangKeluar;
      $barangKeluar->user_id = $user_id;
      $barangKeluar->member_id = $member_id;
      $barangKeluar->barang_id = $barang_id[$i];
      $barangKeluar->stok_keluar = $qty[$i];
      $barangKeluar->tranksaksi = 'Bayar';
      $barangKeluar->save();



    }

    $member = Member::findOrFail($member_id);
    $member->poin = $member->poin + $request->input('poin');
    $member->save();

    $this->hitungGrandTotal($request->input('diskon'), $request->input('grand_total'));

    $tranksaksiTemp = new TranksaksiTemp;
    $tranksaksiTemp->nota_id = $nota_id;
    $tranksaksiTemp->user_id = $user_id;
    $tranksaksiTemp->member_id = $member_id;
    $tranksaksiTemp->diskon = $diskon;
    $tranksaksiTemp->subtotal = $request->input('grand_total');
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
    $barang = Barang::findOrFail($request->input('barang_id'));

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

    $barangKeluar = new BarangKeluar;
    $barangKeluar->user_id = $request->input('user_id');
    $barangKeluar->member_id = $request->input('member_id');
    $barangKeluar->barang_id = $request->input('barang_id');
    $barangKeluar->tranksaksi = 'Tukar Poin';
    $barangKeluar->stok_keluar = 1;
    $barangKeluar->save();

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

  private function getPoin()
  {
    return $this->poin;
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
