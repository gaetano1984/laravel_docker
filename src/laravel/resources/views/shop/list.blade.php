@extends('layout')

@section('container')
    <div class="row">
        <div class="col-md-8">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ragione Sociale</th>
                        <th>Indirizzo</th>
                        <th>Aperto</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="table_body"></tbody>
            </table>            
        </div>
    </div>
@endsection

@section('extrascripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $.ajax({
                url: "{{route('apiShopList')}}"
                ,success: function(res){
                    $(res).each(function(k, v){
                        $('\
                            <tr>\
                                <td>'+v.id+'</td>\
                                <td>'+v.ragione_sociale+'</td>\
                                <td>'+v.indirizzo+'</td>\
                                <td>'+v.aperto+'</td>\
                                <td align="right">\
                                    <a class="btn btn-info" href="shop/edit/'+v.id+'">Edit</a>\
                                    <a class="btn btn-danger" href="shop/delete/'+v.id+'">Delete</a>\
                                </td>\
                            </tr>\
                        ').appendTo('.table_body');
                    });
                }
            });
        });
    </script>
@endsection