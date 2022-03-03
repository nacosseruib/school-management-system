<script>
    $(document).ready( function(){

         //get students name on page load
        var classID = $('#getClassID').val();
        if(classID != ''){
            getStudent(classID);
        }
        //Function to get student names in a class
        function getStudent(classID)
        {
            if (classID != ""){
                if(classID == 'All'){
                    $('#studentName').empty();
                    $('#studentName').append($('<option>').text(" Select Student ").attr('value',""));
                    $('#studentName').append($('<option>').text(" All Students ").attr('value',"All"));
                }else{
                    $.ajax({ 
                            url: '{{url("/")}}' +  '/get_student_in_class_for_result_from_mark_json/' + classID,
                            type: 'get',
                            //data: {'classID': classID, '_token': $('input[name=_token]').val()},
                            data: { format: 'json' },
                            dataType: 'json',
                            success: function(data) { 
                                $('#studentName').empty();
                                $('#studentName').append($('<option>').text(" Select Student ").attr('value',""));
                                $.each(data, function(model, list) {
                                    $('#studentName').append($('<option>').text(list.student_regID +' '+ list.student_lastname +' '+ list.student_firstname).attr('value', list.studentIDs));
                                });
                            },
                            error: function(error) {
                                alert("Please we are having issue getting student names. Check your network/refresh this page !!!");
                            }
                    });
                }
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
            getStudent(classID);
        });

    });
</script>