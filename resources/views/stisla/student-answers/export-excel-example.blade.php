<table>
  <thead>
    <tr>
      <th>{{ __('#') }}</th>
      <th class="text-center">{{ __('Pengerjaan Quiz') }}</th>
      <th class="text-center">{{ __('Pertanyaan') }}</th>
      <th class="text-center">{{ __('Pilihan Jawaban') }}</th>
      <th class="text-center">{{ __('Jawaban Essay') }}</th>
      <th class="text-center">{{ __('Nilai') }}</th>
      <th class="text-center">{{ __('Komentar Pengajar') }}</th>
      <th class="text-center">{{ __('Dihapus Pada') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->quiz_attempt_id }}</td>
        <td>{{ $item->question_id }}</td>
        <td>{{ $item->selected_option_id }}</td>
        <td>{{ $item->essay_answer }}</td>
        <td>{{ $item->score }}</td>
        <td>{{ $item->teacher_comment }}</td>
        <td>{{ $item->deleted_at }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
