@extends('layout')

@section('container')
    <div class="row">
        <div class="col-md-8">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>
                            {{Form::label('Ragione Sociale')}}
                        </td>
                        <td>
                            {{Form::text('ragione_sociale', '', ['id' => 'ragione_sociale', 'class' => 'form-control', 'placeholder' => 'Ragione Sociale'])}}
                        </td>
                        <td id="ragione_sociale_err"></td>
                    </tr>
                    <tr>
                        <td>{{Form::label('Indirizzo')}}</td>
                        <td>
                            {{Form::text('indirizzo', '', ['id' => 'indirizzo', 'class' => 'form-control', 'placeholder' => 'Indirizzo'])}}
                        </td>
                        <td id="indirizzo_err"></td>
                    </tr>
                    <tr>
                        <td>Aperto</td>
                        <td>
                            {{Form::select('stato', ['0' => 'Chiuso', '1' => 'Aperto'], '', ['id' => 'stato', 'class' => 'form-control'])}}
                        </td>
                        <td id="stato_err"></td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan=2>
                            {{Form::button('Crea SHOP', ['class' => 'btn btn-info', 'id' => 'btnsubmit'])}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection            

@section('extrascripts')
    <script type="text/javascript">
        $('#btnsubmit').bind('click', function(){
            $('#ragione_sociale_err').text();
            $('#indirizzo_err').text();
            $('#stato_err').text();
            ragione_sociale = $('#ragione_sociale').val();
            indirizzo = $('#indirizzo').val();
            aperto = $('#stato').val();
            $.ajax({
                url: "{{route('apiShopCreate')}}"
                ,method:'post'
                ,data: {ragione_sociale: ragione_sociale, indirizzo: indirizzo, stato: aperto}
                ,statusCode:{
                    200: function(){
                        alert("Lo shop è stato creato correttamente");
                    }
                    ,422: function(res, a, b){
                        $('#ragione_sociale_err').text(res.responseJSON.errors.ragione_sociale[0]);
                        $('#indirizzo_err').text(res.responseJSON.errors.indirizzo[0]);
                        $('#stato_err').text(res.responseJSON.errors.stato[0]);
                    }
                }
            });
        });
    </script>
@endsection