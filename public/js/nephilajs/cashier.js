    $("table input:not([readonly])").keydown(function(e){
        var key = e.which || e.keyCode;

        if(key == 13){
            key.preventDefault();
            return false;            
        }
    });
    
    $("#assess .other,input").keyup(function(e){
        var key = e.which || e.keyCode;
        var thisIndex = $(this).index("table input:text:not([readonly])");
        var nextTextbox = $("table input:text:not([readonly]):eq(" + (thisIndex + 1) + ")");
        
        if( nextTextbox.length == 0 ){nextTextbox = $("table input:text:not([readonly]):eq(0)")}
        if(key == 13){
            nextTextbox.focus();
        }
    });
    
    $("#assess .credit").not('.other').keyup(function(e){
        var key = e.which || e.keyCode;
        if(key == 13){
            $('#bank_branch').focus();
        }
    });
    
    $("#receivecheck").keyup(function(e){
        var key = e.which || e.keyCode;
        var totalCredit = parseFloat($('#totalamount').val())
        var checkamount = 0;
        
        if($("#receivecheck").val() != ""){
            checkamount = parseFloat($(this).val());
        }
        
        if(key == 13){
            if(checkamount > totalCredit){
                alert("Amount Rereceived should not be greater than the amount to be collected!")
                $(this).focus().val('');
                
            }else if(checkamount < totalCredit){
                $('#receivecash').focus();
            }else{
                $('#remarks').focus();
            }
        }
    });
    
    $('#iscbc').change(function(){
        if ($(this).is(':checked')){
            $('#bank_branch').val("CBC");
        }else{
            $('#bank_branch').val("");
        }
    });
    
    $('.credit').keyup(function(){
        var credits = 0;
        var lesses = 0;
        var total = 0;
        
        $('.credit').each(function(){
            var addcredit = 0;
            if($(this).val() !=""){
                addcredit = parseFloat(parseFloat($(this).val()).toFixed(2));
            }
            credits = parseFloat(credits) + addcredit;
        });
        
        $('.less').each(function(){
            var addless = 0;
            if($(this).val() !=""){
                addless = parseFloat(parseFloat($(this).val()).toFixed(2));
            }
                
            lesses = parseFloat(lesses) + addless;
        })        
        if(credits < lesses){
            total = 0;
        }else{
            total = credits - lesses;
        }
         
        
        $('#totalamount').val(total.toFixed(2));
        changeDiff();
    });
    
    $('.debit').keyup(function(){
        changeDiff();
    });
    
    function changeDiff(){
        var debit = 0;
        var change = 0;
        var diff = 0;
        var totalCredit = parseFloat($('#totalamount').val())
        
        $('.debit').each(function(){
            var adddebit = 0;
            if($(this).val() !=""){
                adddebit = parseFloat(parseFloat($(this).val()).toFixed(2));
            }
            debit = parseFloat(debit) + adddebit;
        });
        
        change = debit-parseFloat($('.fade').val()) - totalCredit;
        diff   = totalCredit - debit;
        
        //Take account the change
        if(debit > totalCredit){ 
            $('#change').val(change);
        }else{
            $('#change').val('');
        }
        
        //Take account the diff
        if(debit < totalCredit){ 
            $('#cashdiff').html(diff.toFixed(2));
        }else{
            $('#cashdiff').html('');
        }
    }
    
    $('#remarks').keypress
    (function(e){
        alert('me')
        var key = e.which || e.keyCode;

        if(key == 13){
            alert('me2')
            submitform();
        }
    });
    
    function submitform(){
        var debit = 0;
        var credit = parseFloat($('#totalamount').val());
        
        $('.debit').each(function(){
            var adddebit = 0;
            if($(this).val() !=""){
                adddebit = parseFloat(parseFloat($(this).val()).toFixed(2));
            }
            debit = parseFloat(debit) + adddebit;
        });
        
        if(debit >= credit){
            if(confirm("Continue to process payment now?")){
                $('#assess').submit();
            }

        }else{
            alert('Payment is smaller than the amount to be payed. Please check!');
        }
    }