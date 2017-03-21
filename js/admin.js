jQuery(document).ready(function($){
    // Hide substitute dropdown
    $(".sp-status-selector select:first-child").unbind("change").siblings().hide();
});