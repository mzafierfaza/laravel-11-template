<div class="section-body">

  <h2 class="section-title">Tambah Trainings</h2>

  <div class="row">
    <div class="col-12">

      <div class="card">
        <div class="row">
          <div class="col-md-12">
            @include('stisla.includes.forms.selects.select2', ['id'=>'courses', 'name'=>'courses', 'label'=>__('Trainings'), 'options'=>$courses, 'multiple'=>true])
          </div>
          <div class="col-md-12">
            <br>
            @csrf
            <input type="hidden" name="quiz_id" value="{{ $competence_id }}">
          </div>
        </div>
        </form>
      </div>
    </div>

  </div>

</div>
</div>