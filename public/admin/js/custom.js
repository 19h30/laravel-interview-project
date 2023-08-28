$(document).ready(function(){
    // $("#current_pwd").keyup(function(){
    //     var current_pwd = $("#current_pwd").val();
    //     // alert(current_pwd);
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type: 'post',
    //         url: '/admin/check-current-password',
    //         data: {current_pwd: current_pwd},
    //         success: function(resp){
    //             if(resp == 'false'){
    //                 $("#verifyCurrentPwd").html('Current Password is incorrect!');
    //             } else if(resp == 'true'){
    //                 $("#verifyCurrentPwd").html('Current Password is correct!');
    //             }
    //         }, error: function(){
    //             alert('Error');
    //         }
    //     })
    // });

    // Update Staff status
    $(document).on('click', '.updateStaffStatus', function(){
        var status = $(this).children('i').attr('status');
        var staffId = $(this).attr('staff_id');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/update-staff-status',
            data: {status: status, staff_id: staffId},
            success: function(resp){
                if(resp['status'] == 0){
                    $('#staff-'+staffId).html("<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>");
                } else if(resp['status'] == 1){
                    $('#staff-'+staffId).html("<i class='fas fa-toggle-on' style='color:#007bff' status='Active'></i>");
                }
            }, error: function(){
                alert('error');
            }
        });
    });

    // Confirm delete staff
    $(document).on('click', '.confirmDelete', function(){
        var name = $(this).attr('name');
        if(confirm('Are you sure to delete staff '+name+'?')){
            return true;
        }
        return false;
    });

    // Update Category status
    $(document).on('click', '.updateCategoryStatus', function(){
        var status = $(this).children('i').attr('status');
        var categoryId = $(this).attr('category_id');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/update-category-status',
            data: {status: status, category_id: categoryId},
            success: function(resp){
                if(resp['status'] == 0){
                    $('#category-'+categoryId).html("<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>");
                } else if(resp['status'] == 1){
                    $('#category-'+categoryId).html("<i class='fas fa-toggle-on' style='color:#007bff' status='Active'></i>");
                }
            }, error: function(){
                alert('error');
            }
        });
    });

    // Update Product status
    $(document).on('click', '.updateProductStatus', function(){
        var status = $(this).children('i').attr('status');
        var productId = $(this).attr('product_id');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/update-product-status',
            data: {status: status, product_id: productId},
            success: function(resp){
                if(resp['status'] == 0){
                    $('#product-'+productId).html("<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>");
                } else if(resp['status'] == 1){
                    $('#product-'+productId).html("<i class='fas fa-toggle-on' style='color:#007bff' status='Active'></i>");
                }
            }, error: function(){
                alert('error');
            }
        });
    });

    // Add product dynamically
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    
    var optionsHHTML = '';

    getProducts.forEach(product => {
        optionsHHTML += `<option value="` + product['id'] + `">` + product['product_name'] + `</option>`;
    });

    var fieldHTML = `
        <div class="input-group form-group">
        <select class="custom-select form-control" name="product[]" id="product" style="width:75%;">
            <option value="">Select</option>` + optionsHHTML +
        `</select>
        <input type="text" class="form-control" name="quantity[]" id="quantity" placeholder="Quantity" />
        <div class="input-group-append">
            <button class="btn btn-outline-secondary remove_button" type="button"><i class="fa fa-minus"></i></button>
        </div>
        </div>
    `;
    
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $('br:last').remove();
        $(this).parent('div').parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
});