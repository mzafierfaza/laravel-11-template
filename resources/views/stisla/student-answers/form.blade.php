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
            <h4><i class="fa fa-edit-3"></i> {{ $fullTitle }}</h4>
          </div>
          <div class="card-body">
            <form action="{{ $action }}" method="POST" enctype="multipart/form-data">

              @isset($d)
                @method('PUT')
              @endisset

              <div class="row">
				<div class="col-md-6">
                  @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'quiz_attempt_id', 'name'=>'quiz_attempt_id', 'label'=>__('Pengerjaan Quiz'), 'options'=>[], 'multiple'=>false])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'question_id', 'name'=>'question_id', 'label'=>__('Pertanyaan'), 'options'=>[], 'multiple'=>false])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'selected_option_id', 'name'=>'selected_option_id', 'label'=>__('Pilihan Jawaban'), 'options'=>[], 'multiple'=>false])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.editors.textarea', ['required'=>true, 'type'=>'textarea', 'id'=>'essay_answer', 'name'=>'essay_answer', 'label'=>__('Jawaban Essay')])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'number', 'id'=>'score', 'name'=>'score', 'label'=>__('Nilai')])
                </div>

				<div class="col-md-6">
                  @include('stisla.includes.forms.editors.textarea', ['required'=>true, 'type'=>'textarea', 'id'=>'teacher_comment', 'name'=>'teacher_comment', 'label'=>__('Komentar Pengajar')])
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
