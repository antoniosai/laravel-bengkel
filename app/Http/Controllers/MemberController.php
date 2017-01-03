<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Member;

use DB;

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

      $messages = [
        'nama_member.required' => 'Nama member harus diisi',
        'handphone.required' => 'Nomor handphone harus diisi',
        'alamat.required' => 'Nomor handphone harus diisi'
      ];

      $rules = [
        'nama_member' => 'required',
        'handphone' => 'required',
        'alamat' => 'required'
      ];

      $this->validate($request, $rules, $messages);

      $member = new Member;
      $member->no_member = $this->generateNoMember();
      $member->nama_member = $request->input('nama_member');
      $member->handphone = $request->input('handphone');
      $member->alamat = $request->input('alamat');
      $member->save();

      if ($member) {
        $request->session()->flash('success', 'Member baru telah berhasil ditambahkan');

        return redirect()->back();
      }
    }

    public function postEditMember(Request $request)
    {

      $messages = [
        'nama_member.required' => 'Nama member harus diisi',
        'handphone.required' => 'Nomor handphone harus diisi',
        'alamat.required' => 'Nomor handphone harus diisi'
      ];

      $rules = [
        'nama_member' => 'required',
        'handphone' => 'required',
        'alamat' => 'required'
      ];

      $this->validate($request, $rules, $messages);
      
      $member = Member::findOrFail($request->input('id'));

      $member->nama_member = $request->input('nama_member');
      $member->handphone = $request->input('handphone');
      $member->alamat = $request->input('alamat');
      $member->save();

      if ($member) {
        $request->session()->flash('success', 'Member baru telah berhasil diedit');

        return redirect()->back();
      }

    }

    public function generateNoMember()
    {
      $tahun = date('y');
      $tanggal = date('d');
      $acak = rand(000,999);

      return $no = $tahun.$tanggal.$acak;
    }

    public function deleteMember($id)
    {
      $member = Member::findOrFail($id);
      

      try {
        if ($member->delete()) {
          return redirect()->back()->with('successMessage', 'Member berhasil dihapus');
        }
      } catch (\Illuminate\Database\QueryException $e) {
        return redirect()->back()->with('errorMessage', 'Member '.$member->nama_member.' tidak bisa dihapus karena sedang ada di dalam daftar Order');
      }
    }
}
