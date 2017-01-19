<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Log;

use App\Member;
use App\Barang;
use App\Tranksaksi;
use App\Poin;

use DB;

class AkumulasiPoin extends Command
{
    private $hargaBarang;
    private $poin;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'akumulasi:poin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghitung total belanjaan member dan mulai mengakumulasi diskon. Dilakukan pada pukul 17:00';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->akumulasiPoin();
    }

  

    private function memberGuest()
    {
        return Member::where('nama_member', '=', 'Guest')->first();
    }

    private function setHarga($member_id, $barang_id)
    {
        $member = Member::findOrFail($member_id);
        $barang = Barang::findOrFail($barang_id);

        $hargaBarang = $barang->harga_khusus;

        if ($member->nama_member != 'Guest'){
            if ($member->type_member == 'grosir') {
                return $this->hargaBarang = $barang->harga_grosir;
            } else {
                if ($hargaBarang == null) {
                    return $this->hargaBarang = $barang->harga_jual;
                } else {
                    return $this->hargaBarang = $hargaBarang;
                }  
            }
        } else {
            return $this->hargaBarang = $barang->harga_jual;
        }

    }

    private function getHarga()
    {
        return $this->hargaBarang;
    }

    private function getPoin()
    {
        return $this->poin;
    }

    private function setPoin($belanja, $idMember = null)
    {
        $guest = $this->memberGuest();

        $member = Member::findOrFail($idMember);

        if ($idMember == $guest->id) {
            return $this->poin = 0;
        } else {
            if ($member->type_member == 'grosir') {
                return $this->poin = 0;
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
    }

    private function akumulasiPoin()
    {
        $query = "SELECT DISTINCT member_id FROM tranksaksis";
        $id = DB::select(DB::raw($query));

        $idmember = [];
        
        foreach ($id as $member_id) {
            array_push($idmember, $member_id->member_id);
        }

        // print_r($idmember);
        $count = count($idmember); 
        
        for ($i=0; $i < $count; $i++) { 

            $tranksaksi = Tranksaksi::where('member_id', $idmember[$i])->get();
            $total = 0;
            $totalHarga = 0;

            foreach ($tranksaksi as $tr) {
                $this->setHarga($idmember[$i], $tr->barang_id);
                $totalHarga = $totalHarga + $this->getHarga();

                $total = $total + ($totalHarga * $tr->qty);

                $this->setPoin($total, $idmember[$i]);
            }

            $member = Member::findOrFail($idmember[$i]);
            $member->poin = $member->poin + $this->getPoin();
            $member->sisa_poin = $member->sisa_poin + $this->getPoin();
            $member->save();

        }

        Log::info('Berhasil');
    }

}
