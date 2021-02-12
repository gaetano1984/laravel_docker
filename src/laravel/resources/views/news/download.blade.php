@extends('layout')

@section('container')
    <div class="row">
        <div class="col-md-6">
            <a href="{{route('downloads', $back_url)}}">torna indietro</a>
        </div>
        <div class="col-md-6">
            {{$path}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            &nbsp;
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            quotidiano
            <ul>
                @foreach($dir as $d)
                    <li><a href="{{route('downloads', $d)}}">{{$d}}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-8">
            <ul>
                <table class="table table-striped" border=1>
                    <tbody>
                        <tr>
                            @for($j=1; $j<=$offset; $j++)
                                <td>&nbsp;</td>
                            @endfor
                            @for($i=1; $i<$last_day; $i++)
                                @if(in_array(sprintf("%02d.pdf", $i), $files))
                                    <td>
                                        <a href="{{route('downloads', $route_path."/".sprintf("%02d.pdf", $i))}}">{{sprintf("%02d.pdf", $i)}}</a>
                                    </td>
                                @else
                                    <td>{{$i}}</td>
                                @endif
                                @if(date('w', strtotime($anno."/".$mese."/".$i))==0)
                                    </tr><tr>
                                @endif
                            @endfor                  
                        </tr>                        
                    </tbody>
                </table>
            </ul>  

        </div>
    </div>
@endsection