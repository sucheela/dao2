$(function(){

  $('#deactivate-modal .btn-primary').click(function(e){
    e.preventDefault();
    $('#status').val('Inactive');
    $('#status').closest('form').submit();
  });

  $('#delete-modal .btn-primary').click(function(e){
    e.preventDefault();
    $('#status').val('Deleted');
    $('#status').closest('form').submit();
  });

});
