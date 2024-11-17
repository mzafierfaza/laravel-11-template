<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ __('Quiz Attempts') }}</title>

  <link rel="stylesheet" href="{{ asset('assets/css/export-pdf.min.css') }}">
</head>

<body>
  <h1>{{ __('Quiz Attempts') }}</h1>
  <h3>{{ __('Total Data:') }} {{ $data->count() }}</h3>
  <table>
    <thead>
      <tr>
        <th>{{ __('#') }}</th>
        <th class="text-center">{{ __('Pendaftaran') }}</th>
        <th class="text-center">{{ __('Quiz') }}</th>
        <th class="text-center">{{ __('Waktu Mulai') }}</th>
        <th class="text-center">{{ __('Waktu Selesai') }}</th>
        <th class="text-center">{{ __('Nilai') }}</th>
        <th class="text-center">{{ __('Lulus') }}</th>
        <th class="text-center">{{ __('Dihapus Pada') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->enrollment_id }}</td>
          <td>{{ $item->quiz_id }}</td>
          <td>{{ $item->start_time }}</td>
          <td>{{ $item->submit_time }}</td>
          <td>{{ $item->score }}</td>
          <td>{{ $item->is_passed }}</td>
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
