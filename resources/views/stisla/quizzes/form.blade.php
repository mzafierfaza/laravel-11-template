<div class="section-body">

  <h2 class="section-title">Buat Test</h2>
  <div class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-body">
          <form action="{{ $action }}" method="POST" enctype="multipart/form-data">

            @isset($d)
            @method('PUT')
            @endisset

            <div class="row">
              <div class="col-md-12">
                @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'title', 'name'=>'title', 'label'=>__('Judul')])
              </div>
              <div class="col-md-4">
                @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'type', 'name'=>'type', 'label'=>__('Tipe'), 'options'=>['pra test' => 'Pra Test', 'pasca test' => 'Pasca Test'], 'multiple'=>false])
              </div>

              <div class="col-md-4">
                @include('stisla.includes.forms.inputs.input', ['type'=>'number', 'id'=>'duration_minutes', 'name'=>'duration_minutes', 'label'=>__('Durasi (Menit)')])
              </div>

              <div class="col-md-4">
                @include('stisla.includes.forms.inputs.input', ['type'=>'number', 'id'=>'passing_score', 'name'=>'passing_score', 'label'=>__('Nilai Kelulusan')])
              </div>

              <div class="col-md-12">
                @include('stisla.includes.forms.editors.summernote', [
                'name' => 'description',
                'label' => 'Deskripsi',
                'id' => 'description',
                ])
              </div>
              <div class="col-md-6">
                @include('stisla.includes.forms.inputs.input', ['name' => 'questions', 'type' => 'file', 'label' => 'Upload Soal Test', 'accept' => '*'])
                @include('stisla.includes.forms.buttons.btn-csv-download', ['link' => route('quizzes.import_example'), 'label' => 'Contoh Import Soal'])
              </div>
              <div class="col-md-6">
                <div class="col-md-6">
                  @include('stisla.includes.forms.inputs.input-radio-toggle', [
                  'required' => true,
                  'id' => 'is_essay',
                  'name' => 'is_essay',
                  'label' => 'Tipe Soal',
                  'options' => [
                  0 => "Pilihan Ganda",
                  1 => "Essay",
                  ],
                  ])
                </div>
                <label class="custom-switch">
                  <input type="checkbox" name="is_randomize" class="custom-switch-input">
                  <span class="custom-switch-indicator"></span>
                  <span class="custom-switch-description">Acak Soal Test</span>
                </label>
              </div>
            </div>
        </div>
      </div>
      </form>
    </div>
  </div>

</div>