<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ __('Quizzes') }}</title>

  <link rel="stylesheet" href="{{ asset('assets/css/export-pdf.min.css') }}">
</head>

<body>
  <h1>{{ __('Quizzes') }}</h1>
  <h3>{{ __('Total Data:') }} {{ $data->count() }}</h3>
  <table>
    <thead>
      <tr>
        <th>{{ __('#') }}</th>
        <th class="text-center">{{ __('Modul') }}</th>
        <th class="text-center">{{ __('Judul') }}</th>
        <th class="text-center">{{ __('Deskripsi') }}</th>
        <th class="text-center">{{ __('Durasi (Menit)') }}</th>
        <th class="text-center">{{ __('Nilai Kelulusan') }}</th>
        <th class="text-center">{{ __('Waktu Mulai') }}</th>
        <th class="text-center">{{ __('Waktu Selesai') }}</th>
        <th class="text-center">{{ __('Acak Soal') }}</th>
        <th class="text-center">{{ __('Dihapus Pada') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->module_id }}</td>
          <td>{{ $item->title }}</td>
          <td>{{ $item->description }}</td>
          <td>{{ $item->duration_minutes }}</td>
          <td>{{ $item->passing_score }}</td>
          <td>{{ $item->start_time }}</td>
          <td>{{ $item->end_time }}</td>
          <td>{{ $item->is_randomize }}</td>
          <td>{{ $item->deleted_at }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  @if (($isPrint ?? false) === true)
    <script>
      window.print();
    </script>
  @endif

</body>

</html>
