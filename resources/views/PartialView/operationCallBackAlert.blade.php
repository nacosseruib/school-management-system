@if($showAlert == 1)
<div class="">
      <div class="col-md-12 mt-2 mb-2">
        
          @if ((count($errors) > 0) and ($showError == 1))
          <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
            <strong>Error!</strong> <br />
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
          </div>
          @endif

          @if((session('message')) and ($showMessage == 1))
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
            <strong>Completed!</strong><br /> 
            {!! session('message') !!}
          </div>                        
          @endif

          @if((session('info')) and ($showMessage == 1))
          <div class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
            <strong>Information!</strong><br /> 
            {!! session('info') !!}
          </div>                        
          @endif

          @if((session('error')) and ($showError == 1))
          <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
            <strong>Not Successful!</strong><br /> 
            {!! session('error') !!}
          </div>                        
          @endif
      </div>
</div>
@endif