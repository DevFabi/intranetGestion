 <!-- Datatables -->
  <script src="./templates/vendor/plugins/datatables/media/js/jquery.dataTables.js"></script>

  <!-- Datatables Tabletools addon -->
  <script src="./templates/vendor/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

  <!-- Datatables ColReorder addon -->
  <script src="./templates/vendor/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
<!-- BEGIN:ajout tables -->  

  <!-- FileUpload JS -->
  <script src="./templates/vendor/plugins/fileupload/fileupload.js"></script>
  <script src="./templates/vendor/plugins/holder/holder.min.js"></script>

  <!-- PNotify -->
  <script src="./templates/vendor/plugins/pnotify/pnotify.js"></script>  
  
  <!-- Tagmanager JS -->
  <script src="./templates/vendor/plugins/tagsinput/tagsinput.min.js"></script>

  <!-- Theme Javascript -->
  <script src="./templates/assets/js/utility/utility.js"></script>
  <script src="./templates/assets/js/demo/demo.js"></script>
  <script src="./templates/assets/js/main.js"></script>
    
  <!-- Récupération du script PHP pour les demandes de doc -->
  <script type="text/javascript">


	
	
	
  </script>
  
  
  <script type="text/javascript">  
  jQuery(document).ready(function() {

    "use strict";

    // Init Theme Core    
    Core.init();

    // Init Demo JS  
    Demo.init();

	
    // select dropdowns - placeholder like creation
    var selectList = $('.admin-form select');
    selectList.each(function(i, e) {
      $(e).on('change', function() {
        if ($(e).val() == "0") $(e).addClass("empty");
        else $(e).removeClass("empty")
      });
    });
    selectList.each(function(i, e) {
      $(e).change();
    });

    // Init tagsinput plugin
    $("input#tagsinput").tagsinput({
      tagClass: function(item) {
        return 'label label-default';
      }
    });
	

 // A "stack" controls the direction and position
    // of a notification. Here we create an array w
    // with several custom stacks that we use later
    var Stacks = {
      stack_top_right: {
        "dir1": "down",
        "dir2": "left",
        "push": "top",
        "spacing1": 10,
        "spacing2": 10
      },
      stack_top_left: {
        "dir1": "down",
        "dir2": "right",
        "push": "top",
        "spacing1": 10,
        "spacing2": 10
      },
      stack_bottom_left: {
        "dir1": "right",
        "dir2": "up",
        "push": "top",
        "spacing1": 10,
        "spacing2": 10
      },
      stack_bottom_right: {
        "dir1": "left",
        "dir2": "up",
        "push": "top",
        "spacing1": 10,
        "spacing2": 10
      },
      stack_bar_top: {
        "dir1": "down",
        "dir2": "right",
        "push": "top",
        "spacing1": 0,
        "spacing2": 0
      },
      stack_bar_bottom: {
        "dir1": "up",
        "dir2": "right",
        "spacing1": 0,
        "spacing2": 0
      },
      stack_context: {
        "dir1": "down",
        "dir2": "left",
        "context": $("#stack-context")
      },
    }

    // PNotify Plugin Event Init
    $('.notification').on('click', function(e) {
      var noteStyle = $(this).data('note-style');
      var noteShadow = $(this).data('note-shadow');
      var noteOpacity = $(this).data('note-opacity');
      var noteStack = $(this).data('note-stack');
      var width = "290px";

      // If notification stack or opacity is not defined set a default
      var noteStack = noteStack ? noteStack : "stack_top_right";
      var noteOpacity = noteOpacity ? noteOpacity : "1";

      // We modify the width option if the selected stack is a fullwidth style
      function findWidth() {
        if (noteStack == "stack_bar_top") {
          return "100%";
        }
        if (noteStack == "stack_bar_bottom") {
          return "70%";
        } else {
          return "290px";
        }
      }

      // Create new Notification
      new PNotify({
        title: 'Bootstrap Themed',
        text: 'Look at my beautiful styling! ^_^',
        shadow: noteShadow,
        opacity: noteOpacity,
        addclass: noteStack,
        type: noteStyle,
        stack: Stacks[noteStack],
        width: findWidth(),
        delay: 1400
      });

    });	
	
    // Init DataTables	
      // Multi-Column Filtering
    $('#datatable5 thead th').each(function() {
      var title = $('#datatable5 tfoot th').eq($(this).index()).text();
      $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
    });

    // DataTable
    var table5 = $('#datatable5').DataTable({
      "sDom": 't<"dt-panelfooter clearfix"ip>',
      "ordering": true
    });

    // Apply the search
    table5.columns().eq(0).each(function(colIdx) {
      $('input', table5.column(colIdx).header()).on('keyup change', function() {
        table5
          .column(colIdx)
          .search(this.value)
          .draw();
      });
    });
  });
  </script>