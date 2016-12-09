<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Member;

class MemberController extends Controller
{
    public function index()
    {
      $member = Member::where('nama_member','!=','Guest')->get();

      return view('backend.member',[
        'member' => $member
      ]);
    }

    public function postAddMember(Request $request)
    {
      $member = new Member;
      $member->no_member = $this->generateNoMember();
      $member->nama_member = $request->input('nama_member');
      $member->handphone = $request->input('handphone');
      $member->save();

      if ($member) {
        return redirect()->back();
      }
    }

    public function generateNoMember()
    {
      $tahun = date('y');
      $bulan = date('m');
      $tanggal = date('d');
      $acak = rand(000,999);

      return $no = $tahun.$bulan.$tanggal.$acak;
    }
}
