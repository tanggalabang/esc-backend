<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>

  <div class="container">
    <h1>Data Jadwal Kelas</h1>

    @foreach ($timesTableData as $classData)
    <h2>{{ $classData['class'] }}</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Number Period</th>
          @foreach ($classData['periods'] as $period)
          <th>{{ $period['day'] }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @for ($i = 1; $i <= 2; $i++) {{-- Mengganti angka 2 sesuai dengan jumlah periode yang sesuai --}} <tr>
          <td>{{ $i }}</td>
          @foreach ($classData['periods'] as $period)
          @if ($period['number_period'] == $i)
          <td>{{ $period['subject'] }}<br>{{ $period['teacher_id'] }}</td>
          @else
          <td></td>
          @endif
          @endforeach
          </tr>
          @endfor
      </tbody>
    </table>
    @endforeach
  </div>

</body>

</html>