<?php 
 
    $count = $_POST['count'];
    
?>

<tr class="casePaperDetails">

    <td>
                                  
        <input type="text" name="disease_name[<?php echo $count; ?>]" class="form-control form-control-sm rounded-0 removeCasePaper" required>

    </td>

    <td>
                    
        <input type="text" name="disease_days[<?php echo $count; ?>]" class="form-control form-control-sm rounded-0 removeCasePaper" required>

    </td>

    <td>
                            
        <a href="javascript:void(0);" onclick="removeitem(this,'casePaperDetails','removeCasePaper')" class="btn btn-sm btn-danger remove-rows"><i class="fa fa-times"></i></a>
        
    </td>

</tr>