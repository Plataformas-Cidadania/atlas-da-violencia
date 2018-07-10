
<div style="margin:auto; width: 600px;">
    <table>
        <tr>
            <th>tabela</th>
            <th>id_seq</th>
            <th>ultimo id</th>
        </tr>
        @foreach($rows as $row)
            <tr>
                <td>{{$row[0]}}</td>
                <td>{{$row[1]}}</td>
                <td>{{$row[2]}}</td>
            </tr>
        @endforeach
    </table>
</div>