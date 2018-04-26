var optionsString = "<option selected=\"selected\">0</option>";
var total = 0.00;
for(var i = 1; i <= 10; i++)
    optionsString += "<option>"+i+"</option>";


//function to be used to default
//the date to todays date
function myFunction() {
    var currentDt = new Date();
    var mm =   ("0" + (currentDt.getMonth() + 1)).slice(-2);
    var dd = currentDt.getDate();
    var yyyy = currentDt.getFullYear();
    var date = yyyy + "-" + mm + '-' + dd;
    $('#date').val(date);
}
myFunction();


//add drop down to categories(reasons for deposit)
$(document).on('click', 'label.category', function (){
    var category = $(this).attr('id');
    $.ajax({
        type: "POST",
        url: "http://troemer.greenriverdev.com/355/kgc-crm-portal-team/views/skus_temp.php",
        dataType:"json",
        data: { category: category },
        success: function (data) {
            $('.subCategory').remove();
            $('#add').remove();
            if(category == 'class' || category == 'event'){
                $('#' + category).after(
                    "<div class='subCategory'>"+
                        "<select id='quantity' name='quantity' class='form-control'>" +
                            optionsString +
                        "</select>"+
                        "<select id='subCategory' name='subCategory' class='form-control'></select>"+
                    "</div>");
                for(var i = 0; i < data.length; i++) {
                    $('#subCategory').append(
                        "<option value='" + data[i]['sku_id'] + "'> " +
                            data[i]['name'] + " - $" + data[i]['price'] +
                        "  </option>"
                    );
                }
            }
            else if(category == 'donation'){
                $('#'+category).after(
                    "<div class='subCategory'>"+
                        "<select id='donation' name='subCategory' class='form-control'>"+
                            "<option id='item'"+
                            "value='"+data[0]['sku_id']+"'>"+data[0]['name']+"</option>"+
                            "<option id='cash'"+
                            "value='"+data[1]['sku_id']+"'>"+data[1]['name']+"</option>"+
                        "</select>"+
                        "<select id='quantity' name='quantity' class='form-control'>"+
                            optionsString+
                        "</select>"+
                        "<input id='itemDonation' type='text' class='form-control' placeholder='Donated Item'>" +
                        "<div class='hidden input-group'>"+
                            "<div class='input-group-prepend'>"+
                                "<span class='input-group-text'>$</span>"+
                            "</div>"+
                            "<input id='cashDonation' type='text' class='form-control'>"+
                        "</div>"+
                    "</div>");
            };
        },
        error: function(xhr, textStatus, thrownError, data) {
            alert("Error: " + thrownError);
        }
    });
});


//when amount/description is typed in for item or cash donation it will
//automatically click radio button(if it isn't already).
$(document).on('change', '#donation', function () {
    $('#add').remove();

    if($(this).find('option:selected').text() == "Item"){
        $('div.subCategory div.input-group').addClass("hidden");

        $('div.subCategory select#quantity').removeClass("hidden");
        $('input#itemDonation').removeClass("hidden");
    }
    else{
        $('div.subCategory select#quantity').addClass("hidden");
        $('input#itemDonation').addClass("hidden");

        $('div.subCategory div.input-group').removeClass('hidden');
    }
});


//add drop downs for check & credit card payment methods
$(document).on('click', 'input.payMethod', function () {
    $('.hidden').css("display", "none");

    if($(this).val() == "check"){
        $('#checkNum').css("display", "block");
    }
    else if($(this).val() == "credit"){
        $('#paypal').css("display", "block");
        $('#square').css("display", "block");
    }
})


//display add button after subcategory is selected
$(document).on('change input', '#quantity, #cashDonation', function () {
    $('#add').remove();

    if(parseInt($('#quantity').val()) > 0 || $('#cashDonation').val())
        $('#categories').append("<button id='add' class='btn btn-success'>Add</button>");
});


//add row to table when add button is clicked
$(document).on('click', '#add', function (e) {
    e.preventDefault();
    var valid = true;
    var category = $('input[name=category]:checked', '#transactionForm').val();
    var desc = $( "select#subCategory option:selected" ).text().split(' - $');
    var quantity = 1, price = 'N/A';

    if(category == 'donation'){
        if($("select#donation option:selected").text() == 'Item'){
            quantity = $('select#quantity').val();
            desc = $('#itemDonation').val();
        }

        else
            price = '$' + $('#cashDonation').val();

        // alert(category + " : " + id + " : " + desc + " : " + quantity + " : " + price);

        if(valid){
            $('#lineItems').after(
                "<tr>"+
                    "<td>"+quantity+"</td>"+
                    "<td>"+category+"</td>"+
                    "<td>"+desc+"</td>"+
                    "<td>"+price+"</td>"+
                    "<td class='price'>"+price+"</td>"+
                    "<td class='delete'>X</td>"+
                "</tr>"
            );
        }
    }
    else{
        price = desc[1];
        desc = desc[0];
        quantity = $('select#quantity').val();
        if(valid){
            $('#lineItems').after(
                "<tr>"+
                "<td>"+quantity+"</td>"+
                "<td>"+category+"</td>"+
                "<td>"+desc+"</td>"+
                "<td>$"+price+"</td>"+
                "<td class='price'>$"+(quantity*price)+"</td>"+
                "<td class='delete'>X</td>"+
                "</tr>"
            );
        }
    }

    $('#add').remove();
    $('select#quantity').val(0);
    $('thead.hidden').removeClass('hidden');
    total = 0.00;
    $('.price').each(function() {
        if($(this).html() == 'N/A')
            total += parseInt(0);
        else
            total += parseInt($(this).html().slice(1));
    });
    $('#total').html('$'+total);
});


//auto generate discount/amount paid based on the others input
$(document).on('input', '#paid, #discount', function () {
    var value = $(this).val();
    var other = parseInt($('#total').html().slice(1)) - parseInt(value);
    if($(this).attr('id') == 'discount')
        $('#paid').val(other);
    else
        $('#discount').val(other);
});