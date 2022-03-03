@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper('Class Fees Setup') }} @endsection
@section('classFeeSetupHeaderTitle') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
            @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
            <form class="form" method="post" action="{{route('postClassFeeSetup')}}">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('CLASS FEES SETUP') }}</h4>
                            <hr />
                            <div >
                                <div align="center" class="col-md-12 d-print-none">
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Update</button>
                                </div>
                                <div align="center" class="col-md-12">
                                    <table class="table table-hover table-stripped table-responsive table-condensed"> 
                                    <thead>
                                        <tr style="background:#d9d9d9">
                                            <th>{{ __('S/N') }}</th>
                                            <th>{!! __("Fee's&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;") !!}</th>
                                            <th>{{'Amount'}}</th>
                                            <th>{!! __("Fee's&nbsp;Duration&nbsp;&nbsp;&nbsp;") !!}</th>
                                            @forelse($allActiveClass as $key=>$listClass)
                                            <th>{{$listClass->class_code }}</th>
                                            @empty
                                            @endforelse
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($allActiveFee) and $allActiveFee)
                                        @forelse($allActiveFee as $key=>$listFee)
                                        <tr>
                                            <td>{{($allActiveFee->currentpage()-1) * $allActiveFee->perpage() + (1 + $key ++)}}</td>
                                            <td bgcolor="#f9f9f9" class="text-success text-left"><b>{{ $listFee->fees_name}}</b></td>
                                            <th bgcolor="#f9f9f9" class="text-success text-left"><b>{{number_format($listFee->amount, 2)}}</b></th>
                                            <td class="text-left"> 
                                                {{ ($listFee->fees_occurent_type ==1 ? '1st Term Only' :'') . ($listFee->fees_occurent_type ==2 ? '2nd Term Only' :'')  . ($listFee->fees_occurent_type ==3 ? '3rd Term Only' :'') . ($listFee->fees_occurent_type ==4 ? 'Per Session Only' :'') }} 
                                            </td>  
                                            @if(isset($allActiveClass) and $allActiveClass)
                                                @forelse($allActiveClass as $key2=>$listClass)
                                                <td> 
                                                    <input type="hidden" name="feeNameAndClassName[]" value="{{$listFee->feessetupID .'-'. $listClass->classID }}" id="getFeeAndClassID{{$listFee->feessetupID .'-'. $listClass->classID }}" />
                                                    <input type="hidden" name="setFeeSetup[]" value="{{ ($activeFeeSetup[$listFee->feessetupID . $listClass->classID]) ? 1  : 0 }}" id="{{$listFee->feessetupID .'-'. $listClass->classID }}" />
                                                    <div align="center" class="custom-control form-control-lg custom-checkbox ml-2">
                                                        <input type="checkbox" value="{{$key}}" class="custom-control-input getClassFeeItem" id="item{{$listFee->feessetupID .'-'. $listClass->classID}}" {!! ($activeFeeSetup[$listFee->feessetupID . $listClass->classID]) ? 'checked'  : '' !!} />
                                                        <label class="custom-control-label" for="item{{$listFee->feessetupID .'-'. $listClass->classID}}"></label>
                                                    </div>  
                                                </td>
                                                @empty
                                                @endforelse
                                            @endif
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-danger"> No Record found ! </td>
                                            </tr>
                                        @endforelse

                                        @endif
                                    </thead>
                                    </table> 
                                </div>
                                <div align="center" class="col-md-12">
                                    <button type="submit" class="btn btn-success d-print-none"> <i class="fa fa-save"></i> Update</button>
                                </div>
                                @if(isset($allActiveFee) and $allActiveFee and ($allActiveFee->hasPages()))
                                <div align="right" class="col-md-12"><hr />
                                    Showing {{($allActiveFee->currentpage()-1)*$allActiveFee->perpage()+1}}
                                            to {{$allActiveFee->currentpage()*$allActiveFee->perpage()}}
                                            of  {{$allActiveFee->total()}} entries
                                </div>
                                <div class="d-print-none">{{ $allActiveFee->links() }}</div>
                                @endif
                            </div><!--//row-->
                    </div>
                </div>
            </div>
        </div>
        </form>	
    </div><!--end content-wrapper-->
</div><!--end main content-->   
@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        //Select or diselect
        $(".getClassFeeItem" ).click(function() { 
            var getItemID = this.id; 
            var getID = getItemID.replace('item', '');
            if($(this).prop("checked")) { 
                $('#' + getID).val(1);
                $('#getFeeAndClassID' + getID).val(getID);
            }else{ 
                $('#' + getID).val(0);
                $('#getFeeAndClassID' + getID).val(getID);
            }
        }); //end function

    });//end document
</script>
@endsection