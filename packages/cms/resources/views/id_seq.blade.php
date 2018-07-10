
<div style="margin:auto; width: 600px;">
    <table>
        {{--@foreach($rows as $row)
            <tr>
                <td>{{$row[0]->table}}</td>
                <td>{{$row[0]->last_value}}</td>
            </tr>
        @endforeach--}}
    </table>
</div>

@foreach($rows as $row)
    <pre>
        <?php print_r($row)?>;
    </pre>
@endforeach