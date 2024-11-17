<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ __('Materials') }}</title>

  <link rel="stylesheet" href="{{ asset('assets/css/export-pdf.min.css') }}">
</head>

<body>
  <h1>{{ __('Materials') }}</h1>
  <h3>{{ __('Total Data:') }} {{ $data->count() }}</h3>
  <table>
    <thead>
      <tr>
        <th>{{ __('#') }}</th>
        <th class="text-center">{{ __('Modul') }}</th>
        <th class="text-center">{{ __('Judul') }}</th>
        <th class="text-center">{{ __('Konten') }}</th>
        <th class="text-center">{{ __('File') }}</th>
        <th class="text-center">{{ __('Durasi (Menit)') }}</th>
        <th class="text-center">{{ __('Tipe') }}</th>
        <th class="text-center">{{ __('Urutan') }}</th>
        <th class="text-center">{{ __('Hitung Progress') }}</th>
        <th class="text-center">{{ __('Dihapus Pada') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->module_id }}</td>
          <td>{{ $item->title }}</td>
          <td>{{ $item->content }}</td>
          <td>{{ $item->file_path }}</td>
          <td>{{ $item->duration_minutes }}</td>
          <td>{{ $item->type }}</td>
          <td>{{ $item->order }}</td>
          <td>{{ $item->is_progress }}</td>
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
