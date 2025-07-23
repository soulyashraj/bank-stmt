<?php 
    
    $count = $_POST['count'];

?>

<tr class="supplierDetalis">

    <td>
                                                                          
        <input type="text" name="medicine_name[<?php echo $count; ?>]" class="form-control form-control-sm rounded-0 removeItem" required>
    
    </td>
    
    <td>
                                                                        
        <select class="form-control form-control-sm rounded-0 removeItem" name="medicine_taken_process[<?php echo $count; ?>]" required>
    
            <option value="">Select Medicine Schedule</option>
                                
            <option value="1-1-1">1-1-1</option>
            
            <option value="1-0-1">1-0-1</option>
            
            <option value="0-0-1">0-0-1</option>

            <option value="1-0-0">1-0-0</option>

            <option value="0-1-0">0-1-0</option>

        </select>
    
    </td>
    
    <td>
            
        <input type="text" name="medicine_af_bf[<?php echo $count; ?>]" class="form-control form-control-sm rounded-0 removeItem" required>                     

    </td>
    
    <td>
        
        <input type="text" name="medicine_days[<?php echo $count; ?>]" class="form-control form-control-sm rounded-0 removeItem" required>                     

    </td>
                    
    <td>
        
        <a href="javascript:void(0);" onclick="removeitem(this,'supplierDetalis','removeItem')" class="btn btn-sm btn-danger remove-rows"><i class="fa fa-times"></i></a>
    
    </td>

</tr>