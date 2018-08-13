
<div style="margin:auto; width: 600px;">
    <table>
        <tr>
            <th>tabela</th>
            <th>id_seq</th>
            <th>ultimo id</th>
            <th>status</th>
        </tr>
        @foreach($rows as $row)
            <tr>
                <td>{{$row[0]}}</td>
                <td>{{$row[1]}}</td>
                <td>{{$row[2]}}</td>
                <td>@if($row[1] >= $row[2]) OK @else erro @endif</td>
            </tr>
        @endforeach
    </table>
</div>