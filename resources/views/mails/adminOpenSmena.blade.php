<h2 style="text-align: center">Невідповідність в актах</h2>
<p>Зміна №: <strong>{{$results['change_id']}}</strong></p>
<p>Зміну відкрив: <strong>{{$results['admin']}}</strong></p>
<p>Дата: <strong>{{ date('Y-m-d H:i:s') }}</strong></p>
<table style="border-collapse: collapse;  border: 1px solid black;">
    <thead>
       <tr style="border: 1px solid black;">
           <td style="border: 1px solid black;">Назва</td>
           <td style="border: 1px solid black;">Акт ID:{{$results['old_act_id']}} перед зміною(кіл.)</td>
           <td style="border: 1px solid black;">Акт ID:{{$results['act_id']}} вказано(кiл.)</td>
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
    </tbody>
</table>