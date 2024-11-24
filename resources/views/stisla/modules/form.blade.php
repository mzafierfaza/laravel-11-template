<div class="section-body">

  <h2 class="section-title">Buat Bab</h2>

  <div class="row">
    <div class="col-12">

      <div class="card">
        @isset($d)
        @method('PUT')
        @endisset

        <div class="row">

          <div class="col-md-12">
            @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'title', 'name'=>'title', 'label'=>__('Judul')])
          </div>

          <div class="col-md-4">
            @include('stisla.includes.forms.inputs.input', ['type'=>'number', 'id'=>'order', 'name'=>'order', 'label'=>__('Urutan')])
          </div>

          <div class="col-md-12">
            <br>

          </div>
        </div>
      </div>
    </div>

  </div>

</div>