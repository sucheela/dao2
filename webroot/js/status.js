$(function(){

  $('#activate-modal .modal-footer .btn-primary').click(function(e){
    e.preventDefault();
    $('#status').val('Active');
    $('#status-form').find('form').submit();
  });

  $('#deactivate-modal .modal-footer .btn-primary').click(function(e){
    e.preventDefault();
    $('#status').val('Inactive');
    $('#status-form').find('form').submit();
  });

  $('#delete-modal .modal-footer .btn-primary').click(function(e){
    e.preventDefault();
    $('#status').val('Deleted');
    $('#status-form').find('form').submit();
  });

});
