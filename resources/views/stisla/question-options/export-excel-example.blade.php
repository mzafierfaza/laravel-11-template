<table>
  <thead>
    <tr>
      <th>{{ __('#') }}</th>
      <th class="text-center">{{ __('Pertanyaan') }}</th>
      <th class="text-center">{{ __('Teks Pilihan') }}</th>
      <th class="text-center">{{ __('Jawaban Benar') }}</th>
      <th class="text-center">{{ __('Dihapus Pada') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->question_id }}</td>
        <td>{{ $item->option_text }}</td>
        <td>{{ $item->is_correct }}</td>
        <td>{{ $item->deleted_at }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
