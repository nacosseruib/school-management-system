<script>
    $(document).ready( function(){

    var classID = $('#getClassID').val();
    if(classID != ''){
        getSubjectList(classID);
    }
    //Function to get student names in a class
    function getSubjectList(classID)
    {
        if (classID != ""){
            $.ajax({ 
                    url: '{{url("/")}}' +  '/get_subject_list_by_class_json/' + classID,
                    type: 'get',
                    //data: {'classID': classID, '_token': $('input[name=_token]').val()},
                    data: { format: 'json' },
                    dataType: 'json',
                    success: function(data) { 
                        $('#getClassSubject').empty();
                        $('#getClassSubject').append($('<option>').text(" Select Subject ").attr('value',""));
                        $.each(data, function(model, list) {
                            $('#getClassSubject').append($('<option>').text(list.subject_name).attr('value', list.subjectID));
                        });
                    },
                    error: function(error) {
                        alert("Please we are having issue getting subject list. Check your network/refresh this page !!!");
                    }
            });
        }//end if
    };//end function
    //calling a function to get student names
    $('#getClassID').change(function() {
        var classID = $('#getClassID').val();
        if (classID == "")
        {
            alert('Please select a class in order to get student names in that class.');
            return false;
        }
        getSubjectList(classID);
    });


    });
</script>