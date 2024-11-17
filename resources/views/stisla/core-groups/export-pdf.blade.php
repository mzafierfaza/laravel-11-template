<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ __('Groups') }}</title>

  <link rel="stylesheet" href="{{ asset('assets/css/export-pdf.min.css') }}">
</head>

<body>
  <h1>{{ __('Groups') }}</h1>
  <h3>{{ __('Total Data:') }} {{ $data->count() }}</h3>
  <table>
    <thead>
      <tr>
        <th>{{ __('#') }}</th>
        <th class="text-center">{{ __('Nama Group') }}</th>
        <th class="text-center">{{ __('Jenis Badan Usaha') }}</th>
        <th class="text-center">{{ __('Nama Badan Usaha') }}</th>
        <th class="text-center">{{ __('Nama Pemilik') }}</th>
        <th class="text-center">{{ __('No. KTP Pemilik') }}</th>
        <th class="text-center">{{ __('NPWP Pemilik') }}</th>
        <th class="text-center">{{ __('Alamat') }}</th>
        <th class="text-center">{{ __('Nama PIC') }}</th>
        <th class="text-center">{{ __('No. Telepon PIC') }}</th>
        <th class="text-center">{{ __('Email PIC') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->name }}</td>
          <td>{{ $item->jenis_badan_usaha }}</td>
          <td>{{ $item->badan_usaha }}</td>
          <td>{{ $item->owner_name }}</td>
          <td>{{ $item->owner_ktp }}</td>
          <td>{{ $item->owner_npwp }}</td>
          <td>{{ $item->address }}</td>
          <td>{{ $item->pic_name }}</td>
          <td>{{ $item->pic_phone }}</td>
          <td>{{ $item->pic_email }}</td>
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
