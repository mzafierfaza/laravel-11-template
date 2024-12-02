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


@push('scripts')
<script>
  var file_element = document.getElementById('select_file');
  var progress_bar = document.getElementById('progress_bar');
  var progress_bar_process = document.getElementById('progress_bar_process');
  var uploaded_image = document.getElementById('uploaded_image');

  file_element.onchange = function() {

    if (!['image/jpeg', 'image/png'].includes(file_element.files[0].type)) {
      uploaded_image.innerHTML = '<div class="alert alert-danger">Selected File must be .jpg or .png Only</div>';

      file_element.value = '';
    } else {
      var form_data = new FormData();

      form_data.append('sample_image', file_element.files[0]);

      form_data.append('_token', document.getElementsByName('_token')[0].value);

      progress_bar.style.display = 'block';

      var ajax_request = new XMLHttpRequest();

      ajax_request.open("POST", "{{ route('upload_file.upload') }}");

      ajax_request.upload.addEventListener('progress', function(event) {

        var percent_completed = Math.round((event.loaded / event.total) * 100);

        progress_bar_process.style.width = percent_completed + '%';

        progress_bar_process.innerHTML = percent_completed + '% completed';

      });

      ajax_request.addEventListener('load', function(event) {

        var file_data = JSON.parse(event.target.response);

        uploaded_image.innerHTML = '<div class="alert alert-success">Files Uploaded Successfully</div><img src="' + file_data.image_path + '" class="img-fluid img-thumbnail" />';

        file_element.value = '';

      });

      ajax_request.send(form_data);


    }

  };
</script>
@endpush