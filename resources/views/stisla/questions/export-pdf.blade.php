<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ __('Questions') }}</title>

  <link rel="stylesheet" href="{{ asset('assets/css/export-pdf.min.css') }}">
</head>

<body>
  <h1>{{ __('Questions') }}</h1>
  <h3>{{ __('Total Data:') }} {{ $data->count() }}</h3>
  <table>
    <thead>
      <tr>
        <th>{{ __('#') }}</th>
        <th class="text-center">{{ __('Quiz') }}</th>
        <th class="text-center">{{ __('Pertanyaan') }}</th>
        <th class="text-center">{{ __('Tipe Soal') }}</th>
        <th class="text-center">{{ __('Poin') }}</th>
        <th class="text-center">{{ __('Jawaban Essay yang Benar') }}</th>
        <th class="text-center">{{ __('Dihapus Pada') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->quiz_id }}</td>
          <td>{{ $item->question_text }}</td>
          <td>{{ $item->type }}</td>
          <td>{{ $item->points }}</td>
          <td>{{ $item->correct_essay_answer }}</td>
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
