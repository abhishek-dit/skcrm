$(document).ready(function(){
    $(document).on('change','#fy_year',function(){
    var fy_id = $(this).val();
    $('.fy_id').val(fy_id);
    });
});