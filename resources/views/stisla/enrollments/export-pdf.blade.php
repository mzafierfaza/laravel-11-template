<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ __('Enrollments') }}</title>

  <link rel="stylesheet" href="{{ asset('assets/css/export-pdf.min.css') }}">
</head>

<body>
  <h1>{{ __('Enrollments') }}</h1>
  <h3>{{ __('Total Data:') }} {{ $data->count() }}</h3>
  <table>
    <thead>
      <tr>
        <th>{{ __('#') }}</th>
        <th class="text-center">{{ __('Pengguna') }}</th>
        <th class="text-center">{{ __('Kompetensi') }}</th>
        <th class="text-center">{{ __('Tanggal Pendaftaran') }}</th>
        <th class="text-center">{{ __('Status') }}</th>
        <th class="text-center">{{ __('Dihapus Pada') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->user_id }}</td>
          <td>{{ $item->competence_id }}</td>
          <td>{{ $item->enrolled_date }}</td>
          <td>{{ $item->status }}</td>
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
