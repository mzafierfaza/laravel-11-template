<div class="section-body">

  <h2 class="section-title">Buat Test</h2>
  <div class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-header">
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
                @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'number', 'id'=>'duration_minutes', 'name'=>'duration_minutes', 'label'=>__('Durasi (Menit)')])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'number', 'id'=>'passing_score', 'name'=>'passing_score', 'label'=>__('Nilai Kelulusan')])
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