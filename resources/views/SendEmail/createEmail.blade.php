@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Send Email')) }} @endsection
@section('sendEmailPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
          <form  id="sendEmailForm" class="form" method="post" action="{{route('sendEmail')}}">
          @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('SEND EMAIL') }}</h4>
                            <hr />

                            <div class="row">
                                <div class="col-md-6 mt-2 bg-light pb-3">
                                    <label> Sender Name</label>
                                    <input type="text" value="{{ (isset($senderName) ? $senderName : 'School Eportal' )}}" class="form-control" id="senderName" required name="senderName" placeholder="Enter Sender's Name">
                                    @if ($errors->has('senderName'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('senderName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 mt-2 bg-light pb-3">
                                    <label> Sender Email </label>
                                    <input type="text"  value="{{ (isset($senderEmail) ? $senderEmail : '' )}}" class="form-control" required id="senderEmail"  name="senderEmail" placeholder="Enter Send Email">
                                    @if ($errors->has('senderEmail'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('senderEmail') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 mt-3">
                                        <label> Subject</label>
                                        <input type="text" autofocus class="form-control" name="subject" id="subject" value="{{old('subject')}}" placeholder="Enter Subject">
                                        @if ($errors->has('subject'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('subject') }}</strong>
                                            </span>
                                        @endif
                                </div>
                                <div class="col-md-6 mt-3">
                                        <label>Recipients Can Reply to this email (Optional)</label>
                                        <select class="form-control" name="replyToEmail">
                                            <option ="1" {{old('replyToEmail') == 1 ? 'selected' : 'selected'}}>Reply To this Email</option>
                                            <option ="0" {{old('replyToEmail') == 0 ? 'selected' : ''}}>Do Not Reply To this Email</option>
                                        </select>
                                        @if ($errors->has('replyToEmail'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('replyToEmail') }}</strong>
                                            </span>
                                        @endif
                                </div>      
                            </div>
                            
                            <div class="row">
                                    <div class="col-md-6 mt-2"> 
                                        <label> Message</label>  <i><span id="getCountChar" class="text-right pull-right"></span></i> 
                                        <textarea id="textareaCharacter" maxlength ="90000" class="form-control textareaCharacter" name="message" required placeholder="Enter Message" style="min-height:300px;">{{old('message')}}</textarea>
                                        @if ($errors->has('message'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('message') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                        
                                    <div class="col-md-6 mt-2">
                                        <label> Recipients:</label>
                                        <span class="input-group-btn">
                                            <button class="btn bg-warning pull-right" type="button"><i class="fa fa-user-plus white"></i></button>
                                        </span>
                                        <div class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
                                            <a href="javascript:;">
                                                <div class="form-group">    
                                                    <div class="input-group input-group-md">
                                                        <input type="text" autocomplete="true" id="example_emailBS" name="recipients" class="form-control recipients" placeholder="Enter Recipients...">
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <script type="text/javascript">
                                            //Plug-in function for the bootstrap version of the multiple email
                                            $(function() {
                                                //To render the input device to multiple email input using BootStrap icon
                                                ///$('#example_emailBS').multiple_emails({position: "bottom"});
                                            	//OR $('#example_emailBS').multiple_emails("Bootstrap");
                                            	//Shows the value of the input device, which is in JSON format
                                            	$('#current_emailsBS').text($('#example_emailBS').val());
                                            		$('#example_emailBS').change( function(){
                                            		    $('#current_emailsBS').text($(this).val());
                                            	});
                                            });
                                        </script>
                                    </div>
                            </div><!--//row-->
                        <hr />
                        <div class="card-body">
                            <div class="px-3">
                                    <div class="form-actions top">
                                        <div class="buttons-group text-center">
                                            <button type="button" class="btn btn-sm btn-success checkAllFelds" data-toggle="modal" data-backdrop="false" data-target="#sendEmailModal">
                                                <i class="fa fa-check-square-o"></i> {{ __('Send Email') }}
                                            </button>
                                        </div>
                                    </div>
                            </div>
                        </div>

                    </div><!--//card-->
                </div><!--col-12-->
            </div><!--//row-->
            </form>
    
                                <!-- Confirm send Email Modal -->
                                <div class="modal fade text-left d-print-none" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="sendSMS" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                            <h5 class="modal-title" id="sendEmail"><i class="fa fa-send"></i> {{ __('Confirm Sending Email')}}  </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                <h5 class="text-center"> {{ __('Are you sure you want to send this Email to the recipient(s)')}} </h5>
                                            <p>
                                                <div class="text-danger text-center"> {{ __('Your email will be sent to the selected recipient(s)')}} </div>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                                <button type="button" id="sendNow" class="btn btn-outline-success">{{ __('Send Email Now') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!--end Modal-->

    </div><!--end content-wrapper-->
</div><!--end main content-->

@endsection

@section('scripts')
<script>
    $(document).ready(function(){
       
        //Confirm Sending Email
        $('.checkAllFelds').click(function(){
            if($('#senderName').val() == '')
            {
                alert("Please enter Send Name!");
                $('#senderName').focus();
                return false;
            }
            //
            if($('#senderEmail').val() == '')
            {
                alert("Please enter Send Email!");
                $('#senderEmail').focus();
                return false;
            }
            //
            if(($('#subject').val()) == '')
            {
                if(confirm("Sorry you have not added subject to your email! Do you want to continue?"))
                {
                    
                }else{
                    $('#subject').focus();
                    return false;
                }
            }
            //
            if($('.textareaCharacter').val() == '')
            {
                alert("Please you have not enter any message to be sent!");
                $('.textareaCharacter').focus();
                return false;
            }
            //
            if($('.recipients').val() == '')
            {
                alert("Please enter at least one recipient!");
                $('.recipients').focus();
                return false;
            }
            
        });

        //SEND SMS
        $('#sendNow').click(function(){
            $('#sendEmailForm').submit();
        });
        
        //COUNT CHARACTERS  
        $('#getCountChar').html( '(90000 Chars. Left)' ).css('color', 'grey');
        $('#textareaCharacter').keyup(function () {
          var max = 90000;
          var len = $(this).val().length;
          if (len >= max) {
                $('#getCountChar').html(' Max. Chars. Limit Reached!').css('color', 'red');
          }else if(len == max) {
                $('#getCountChar').html( '(90000 Chars. Left)' ).css('color', 'grey');
          } else {
                var char = max - len;
                var textChar
                if(char > 1)
                {
                    textChar = " Chars. Left";
                }else{
                    textChar = " Char. Left";
                }
                if(char > 9)
                {
                     $('#getCountChar').html(char +  textChar).css('color', 'green');
                }else{
                    $('#getCountChar').html(char +  textChar).css('color', 'red');
                }
          }
        });
        
    });//end document
    
    (function( $ ){
 
	    $.fn.multiple_emails = function(options) {
		// Default options
		var defaults = {
			checkDupEmail: true,
			theme: "Bootstrap",
			position: "top"
		};
		// Merge send options with defaults
		var settings = $.extend( {}, defaults, options );
		var deleteIconHTML = "";
		if (settings.theme.toLowerCase() == "Bootstrap".toLowerCase())
		{
			deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><span class="glyphicon glyphicon-remove"></span></a>';
		}
		else if (settings.theme.toLowerCase() == "SemanticUI".toLowerCase() || settings.theme.toLowerCase() == "Semantic-UI".toLowerCase() || settings.theme.toLowerCase() == "Semantic UI".toLowerCase()) {
			deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><i class="remove icon"></i></a>';
		}
		else if (settings.theme.toLowerCase() == "Basic".toLowerCase()) {
			//Default which you should use if you don't use Bootstrap, SemanticUI, or other CSS frameworks
			deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><i class="basicdeleteicon">Remove</i></a>';
		}
		
		return this.each(function() {
			//$orig refers to the input HTML node
			var $orig = $(this);
			var $list = $('<ul class="multiple_emails-ul" />'); // create html elements - list of email addresses as unordered list

			if ($(this).val() != '' && IsJsonString($(this).val())) {
				$.each(jQuery.parseJSON($(this).val()), function( index, val ) {
					$list.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + val.toLowerCase() + '">' + val + '</span></li>')
					  .prepend($(deleteIconHTML)
						   .click(function(e) { $(this).parent().remove(); refresh_emails(); e.preventDefault(); })
					  )
					);
				});
			}
			
			var $input = $('<input type="text" class="multiple_emails-input text-left" />').on('keyup', function(e) { // input
				$(this).removeClass('multiple_emails-error');
				var input_length = $(this).val().length;
				
				var keynum;
				if(window.event){ // IE					
					keynum = e.keyCode;
				}
				else if(e.which){ // Netscape/Firefox/Opera					
					keynum = e.which;
                }
				
				//if(event.which == 8 && input_length == 0) { $list.find('li').last().remove(); } //Removes last item on backspace with no input
				
				// Supported key press is tab, enter, space or comma, there is no support for semi-colon since the keyCode differs in various browsers
				if(keynum == 9 || keynum == 32 || keynum == 188) { 
					display_email($(this), settings.checkDupEmail);
				}
				else if (keynum == 13) {
					display_email($(this), settings.checkDupEmail);
					//Prevents enter key default
					//This is to prevent the form from submitting with  the submit button
					//when you press enter in the email textbox
					e.preventDefault();
				}

			}).on('blur', function(event){ 
				if ($(this).val() != '') { display_email($(this), settings.checkDupEmail); }
			});

			var $container = $('<div class="multiple_emails-container" />').click(function() { $input.focus(); } ); // container div
 
			// insert elements into DOM
			if (settings.position.toLowerCase() === "top")
				$container.append($list).append($input).insertAfter($(this));
			else
				$container.append($input).append($list).insertBefore($(this));

			/*
			t is the text input device.
			Value of the input could be a long line of copy-pasted emails, not just a single email.
			As such, the string is tokenized, with each token validated individually.
			
			If the dupEmailCheck variable is set to true, scans for duplicate emails, and invalidates input if found.
			Otherwise allows emails to have duplicated values if false.
			*/
			function display_email(t, dupEmailCheck) {
				
				//Remove space, comma and semi-colon from beginning and end of string
				//Does not remove inside the string as the email will need to be tokenized using space, comma and semi-colon
				var arr = t.val().trim().replace(/^,|,$/g , '').replace(/^;|;$/g , '');
				//Remove the double quote
				arr = arr.replace(/"/g,"");
				//Split the string into an array, with the space, comma, and semi-colon as the separator
				arr = arr.split(/[\s,;]+/);
				
				var errorEmails = new Array(); //New array to contain the errors
				
				var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
				
				for	(var i = 0; i < arr.length; i++) {
					//Check if the email is already added, only if dupEmailCheck is set to true
					if ( dupEmailCheck === true && $orig.val().indexOf(arr[i]) != -1 ) {
				        if (arr[i] && arr[i].length > 0) {
							new function () {
								var existingElement = $list.find('.email_name[data-email=' + arr[i].toLowerCase().replace('.', '\\.').replace('@', '\\@') + ']');
								existingElement.css('font-weight', 'bold');
								setTimeout(function() { existingElement.css('font-weight', ''); }, 1500);
							}(); // Use a IIFE function to create a new scope so existingElement won't be overriden
						}
					}
					else if (pattern.test(arr[i]) == true) {
						$list.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + arr[i].toLowerCase() + '">' + arr[i] + '</span></li>')
							  .prepend($(deleteIconHTML)
								   .click(function(e) { $(this).parent().remove(); refresh_emails(); e.preventDefault(); })
							  )
						);
					}
					else
						errorEmails.push(arr[i]);
				}
				// If erroneous emails found, or if duplicate email found
				if(errorEmails.length > 0)
					t.val(errorEmails.join("; ")).addClass('multiple_emails-error');
				else
					t.val("");
				refresh_emails ();
			}
			
			function refresh_emails () {
				var emails = new Array();
				var container = $orig.siblings('.multiple_emails-container');
				container.find('.multiple_emails-email span.email_name').each(function() { emails.push($(this).html()); });
				$orig.val(JSON.stringify(emails)).trigger('change');
			}
			function IsJsonString(str) {
				try { JSON.parse(str); }
				catch (e) {	return false; }
				return true;
			}
			return $(this).hide();
		});
	};
	
})(jQuery);

		//Plug-in function for the bootstrap version of the multiple email
		$(function() {
			//To render the input device to multiple email input using BootStrap icon
			$('#example_emailBS').multiple_emails({position: "bottom"});
			//OR $('#example_emailBS').multiple_emails("Bootstrap");
			//Shows the value of the input device, which is in JSON format
		$('#current_emailsBS').text($('#example_emailBS').val());
            //$('#example_emailBS').change( function(){
            //$('#current_emailsBS').text($(this).val());
		});
		
</script>
@endsection

@section('styles')
<style>
    .multiple_emails-container { 
	border:1px #00ff00 solid; 
	border-radius: 1px; 
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075); 
	padding:0; margin: 0; cursor:text; width:100%; 
}

.multiple_emails-container input { 

	width:100%; 
	border:0; 
	outline: none; 
	margin-bottom:30px; 
	padding-left: 5px; 
	
}

,,.multiple_emails-container input{
	border: 0 !important;
}

.multiple_emails-container input.multiple_emails-error {	
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px red !important; 
	outline: thin auto red !important; 
}

.multiple_emails-container ul {	
	list-style-type:none; 
	padding-left: 0; 
}

.multiple_emails-email { 
	margin: 3px 5px 3px 5px; 
	padding: 3px 5px 3px 5px; 
	border:1px #BBD8FB solid;	
	border-radius: 3px; 
	background: #F3F7FD; 
}

.multiple_emails-close { 
	float:left; 
	margin:0 3px;
}

</style>
@endsection



