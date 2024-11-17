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
    {{-- <div class="alert alert-info alert-has-icon">
      <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
      <div class="alert-body">
        <div class="alert-title">{{ __('Informasi') }}</div>
        This is a info alert.
      </div>
    </div> --}}

    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-header">
            <h4><i class="fa fa-book"></i> {{ $fullTitle }}</h4>
          </div>
          <div class="card-body">
            <form action="{{ $action }}" method="POST" enctype="multipart/form-data">

              @isset($d)
                @method('PUT')
              @endisset

              <div class="row">
				<div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'title', 'name'=>'title', 'label'=>__('Judul')])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.editors.textarea', ['required'=>true, 'type'=>'textarea', 'id'=>'description', 'name'=>'description', 'label'=>__('Deskripsi')])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.editors.textarea', ['required'=>true, 'type'=>'textarea', 'id'=>'procedurs', 'name'=>'procedurs', 'label'=>__('Prosedur')])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.editors.textarea', ['required'=>true, 'type'=>'textarea', 'id'=>'topic', 'name'=>'topic', 'label'=>__('Topik')])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'format', 'name'=>'format', 'label'=>__('Format'), 'options'=>["online","offline","webinar"], 'multiple'=>false])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'number', 'id'=>'price', 'name'=>'price', 'label'=>__('Harga')])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'created_by', 'name'=>'created_by', 'label'=>__('Dibuat Oleh'), 'options'=>[], 'multiple'=>false])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'time', 'id'=>'start_time', 'name'=>'start_time', 'label'=>__('Waktu Mulai')])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'time', 'id'=>'end_time', 'name'=>'end_time', 'label'=>__('Waktu Selesai')])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.editors.textarea', ['required'=>true, 'type'=>'textarea', 'id'=>'address', 'name'=>'address', 'label'=>__('Alamat')])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'number', 'id'=>'max_repeat_enrollment', 'name'=>'max_repeat_enrollment', 'label'=>__('Maksimal Pendaftaran Ulang')])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'number', 'id'=>'max_enrollment', 'name'=>'max_enrollment', 'label'=>__('Maksimal Pendaftaran')])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'number', 'id'=>'approved_status', 'name'=>'approved_status', 'label'=>__('Status Persetujuan')])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'approved_by', 'name'=>'approved_by', 'label'=>__('Disetujui Oleh'), 'options'=>[], 'multiple'=>false])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'teacher_id', 'name'=>'teacher_id', 'label'=>__('Pengajar'), 'options'=>[], 'multiple'=>false])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.editors.textarea', ['required'=>true, 'type'=>'textarea', 'id'=>'teacher_about', 'name'=>'teacher_about', 'label'=>__('Tentang Pengajar')])
                </div>



                <div class="col-md-12">
                  <br>

                  @csrf

                  @include('stisla.includes.forms.buttons.btn-save')
                  @include('stisla.includes.forms.buttons.btn-reset')
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
