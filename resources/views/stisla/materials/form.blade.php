<div class="section-body">

  <h2 class="section-title">Buat Materi</h2>

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

              <div class="col-md-6">
                @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'type', 'name'=>'type', 'label'=>__('Tipe'), 'options'=>['video' => 'Video', 'text' => 'Bacaan', 'document' => 'Dokumen'], 'multiple'=>false])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.inputs.input', ['name' => 'file_path', 'type' => 'file', 'label' => 'Files / Video', 'accept' => '*'])
                <!-- @include('stisla.includes.forms.inputs.input', ['required' => isset($d) ? false : true, 'name' => 'image', 'type' => 'file', 'label' => 'Cover Image', 'accept' => '*']) -->
              </div>

              <div class="col-md-4">
                @include('stisla.includes.forms.inputs.input-radio-toggle', [
                'required' => true,
                'id' => 'progress',
                'name' => 'is_progress',
                'label' => 'Status Materi',
                'options' => [
                0 => "Tidak Terhitung Progress",
                1 => "Terhitung Progress",
                ],
                ])
              </div>

              <div class="col-md-4">
                @include('stisla.includes.forms.inputs.input', ['type'=>'number', 'id'=>'duration_minutes', 'name'=>'duration_minutes', 'label'=>__('Durasi (Menit)')])
              </div>

              <div class="col-md-4">
                @include('stisla.includes.forms.inputs.input', ['type'=>'number', 'id'=>'order', 'name'=>'order', 'label'=>__('Urutan')])
              </div>

              <div class="col-md-12">
                @include('stisla.includes.forms.editors.summernote', [
                'name' => 'content',
                'label' => 'Content',
                'id' => 'content',
                ])
              </div>
              <div class="col-md-12">
                <br>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>

  </div>
</div>