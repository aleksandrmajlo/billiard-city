<h2 style="text-align: center">Невідповідність в кількості продуктів</h2>
<p>Зміна №: <strong>{{$results['change_id']}}</strong></p>
<p>Зміну закрив: <strong>{{$results['admin']}}</strong></p>
<p>Дата: <strong>{{ date('Y-m-d H:i:s') }}</strong></p>
<table style="border-collapse: collapse;  border: 1px solid black;">
    <thead>
    <tr style="border: 1px solid black;">
        <td style="border: 1px solid black;">Назва</td>
        <td style="border: 1px solid black;">Має бути (кіл.)</td>
        <td style="border: 1px solid black;">Акт ID:{{$results['act_id']}} заповнення (кіл.)</td>
    </tr>
    </thead>
    <tbody>
    @if($results['ingredients'])
        @foreach($results['ingredients'] as $ingredient)
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">{{$ingredient['title']}}</td>
                <td style="border: 1px solid black;">{{$ingredient['oldCount']}}</td>
                <td style="border: 1px solid black;">{{$ingredient['thisCount']}}</td>
            </tr>
        @endforeach
    @endif
    @if($results['stocks'])
        @foreach($results['stocks'] as $stock)
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">{{$stock['title']}}</td>
                <td style="border: 1px solid black;">{{$stock['oldCount']}}</td>
                <td style="border: 1px solid black;">{{$stock['thisCount']}}</td>

            </tr>
        @endforeach
    @endif

    @if($results['coffee'])
        <tr style="border: 1px solid black;">
            <td style="border: 1px solid black;">{{$results['coffee']['title']}}</td>
            <td style="border: 1px solid black;">{{$results['coffee']['thisCount']}}</td>
            <td style="border: 1px solid black;">{{$results['coffee']['oldCount']}}</td>
        </tr>
    @endif

    </tbody>
</table>