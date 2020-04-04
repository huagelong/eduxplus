@if($searchField)
    <div class="row" style="background-color: #f7f7f7;padding-top: 10px;margin-bottom:10px;">
        <div class="col-sm-12">
            <form class="form-inline" action="@uri($route)" method="get">
                @foreach($searchField as $k=>$v)
                    @php
                        $initData = isset($v[2])?call_user_func($v[2]):'';
                    @endphp
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label style="margin-left: 10px;margin-right: 10px;">{{$k}}:</label>
                            @if($v[0] == 'text')
             <input type="{{$v[0]}}" name="{{$v[1]}}" class="form-control k-input" id="{{$v[1]}}" placeholder="{{$k}}" value="{{$request[$v[1]] or ''}}">
                            @endif
                            @if($v[0] == 'select')
                                @dropDownListLocal($initData,$v[1],isset($request[$v[1]])?$request[$v[1]]:'',["optionLabel"=>"请选择","ignoreCase"=>false,"filter"=>"contains","dataTextField"=>"text", "dataValueField"=>"value"])
                            @endif
                            @if($v[0] == 'datetime')
                               @dateTimePicker($v[1], isset($request[$v[1]])?$request[$v[1]]:'')
                            @endif
                            @if($v[0] == 'select_holder')
                                <input type="text" name="{{$v[1]}}" id="{{$v[1]}}" value="{{$request[$v[1]] or ''}}">
                            @endif
                    </div>
                @endforeach
                    <div class="form-group"  style="margin-bottom: 10px;">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>

            </form>
        </div>
    </div>
@endif