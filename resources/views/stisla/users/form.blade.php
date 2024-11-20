@extends('stisla.layouts.app')

@section('title')
{{ $fullTitle }}
@endsection

@section('content')
@include('stisla.includes.breadcrumbs.breadcrumb-form')

<div class="section-body">

  <h2 class="section-title">{{ $fullTitle }}</h2>
  <p class="section-lead">{{ __('Merupakan halaman yang menampilkan form ' . $title) }}.</p>

  {{-- gunakan jika ingin menampilkan sesuatu informasi --}}
  <div class="alert alert-info alert-has-icon">
    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
    <div class="alert-body">
      <div class="alert-title">{{ __('Informasi') }}
      </div>
      Pastikan email di input dengan benar untuk keperluan verifikasi dari peserta
    </div>
  </div>


  <div class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-header">
          <h4><i class="fa fa-users"></i> {{ $fullTitle }}</h4>
        </div>
        <div class="card-body">
          <form action="{{ $action }}" method="POST" enctype="multipart/form-data">

            @isset($d)
            <!-- @if ($d->id != null) -->
            @method('PUT')
            <!-- @endif -->
            @endisset

            <div class="row">
              <div class="col-md-6">
                @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'first_name', 'name'=>'first_name', 'label'=>__('Nama Depan')])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'last_name', 'name'=>'last_name', 'label'=>__('Nama Belakang')])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.inputs.input-email', ['required'=>true, 'type'=>'email', 'id'=>'email', 'name'=>'email', 'label'=>__('Email')])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.selects.select', ['required'=>true, 'id'=>'gender', 'name'=>'gender', 'label'=>__('Jenis Kelamin'), 'options'=>[
                'male' => 'Laki-laki',
                'female' => 'Perempuan',
                ], 'multiple'=>false])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'ktp', 'name'=>'ktp', 'label'=>__('KTP')])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'npwp', 'name'=>'npwp', 'label'=>__('NPWP')])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'date', 'id'=>'date_of_birth', 'name'=>'date_of_birth', 'label'=>__('Tanggal Lahir')])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.selects.select2', ['required'=>true, 'type'=>'text', 'id'=>'region', 'name'=>'region', 'label'=>__('Kota Asal'), 'options'=>$regions, 'multiple'=>false])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.selects.select2', ['required'=>true, 'type'=>'text', 'id'=>'role_id', 'name'=>'role_id', 'label'=>__('Divisi'), 'options'=>$roles, 'multiple'=>false])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'phone', 'name'=>'phone', 'label'=>__('Nomor Telepon')])
              </div>
              <div class="col-md-6">
                @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'nik', 'name'=>'nik', 'label'=>__('NIK')])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.selects.select', ['required'=>true, 'id'=>'religion', 'name'=>'religion', 'label'=>__('Agama'), 'options'=>[
                'islam' => 'Islam',
                'hindu' => 'Hindu',
                'budha' => 'Budha',
                'kristen' => 'Kristen',
                'katolik' => 'Katolik',
                'atheis' => 'Atheis',
                ],
                'multiple'=>false])
              </div>



              <div class="col-md-12">
                <br>

                @csrf

                @include('stisla.includes.forms.buttons.btn-save')
                @include('stisla.includes.forms.buttons.btn-reset')
                @isset($d)
                @if ($notyet && $d->approved_status == 1)
                <a href="{{ route('users.resendActivation', ['users' => $d, 'new' => false]) }}" class="btn btn-secondary"><i class="fa fa-undo"></i> {{ __('Resend Email') }}</a>
                @endif

                @endisset
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>


</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush