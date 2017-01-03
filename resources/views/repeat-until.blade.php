@extends('layouts.main')

@section('title')
	Contoh Pengulangan Repeat Until
@endsection

@section('content')
	<div class="well">
		<h2>Contoh Pengulangan Repeat & Until</h2>
	</div>
	<h3>Repeat-Until</h3>
	<hr>
	<p>Repeat untuk di dalam  </p>


	<ul>
	<?php 

		$bensin = 10;
		$jarakTempulPerliter = 40;

		while ($bensin > 0) { ?>
			<li>Bensin masih ada {{ $bensin }} Liter, Masih bisa berjalan sejauh {{ $bensin * $jarakTempulPerliter }} KM</li>	
	<?php 
		$bensin--;
	} ?>
	</ul>
@endsection

@section('custom_scripts')

@endsection
