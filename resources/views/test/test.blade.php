<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>

  <style media="screen">
  .whatever {
    width:200px;
    height:50px;
    overflow:auto
  }
  </style>
</head>
<body>
  <div>
    <select multiple id="select1" class="whatever">
      <option value="1">Option 1</option>
      <option value="2">Option 2</option>
      <option value="3">Option 3</option>
      <option value="4">Option 4</option>
    </select>
    <img src="../Common/MultiSelect/img/switch.png">
    <select multiple id="select2" class="whatever"></select>
  </div>

  <div>
    <select multiple id="select3" class="whatever">
      <option value="1"><a href="#">Test</a><b>Test</b></option>
      <option value="2">Option 2</option>
      <option value="3">Option 3</option>
      <option value="4">Option 4</option>
    </select>
    <img src="../Common/MultiSelect/img/switch.png">
    <select multiple id="select4" class="whatever"></select>
  </div>

  <script src="{{ asset('js/jquery-1.12.4.min.js') }}"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    $('select').change(function() {
      var $this = $(this);
      $this.siblings('select').append($this.find('option:selected')); // append selected option to sibling
      $('select', $this.parent()).each(function(i,v){ // loop through relative selects
        var $options = $(v).find('option'); // get all options
        $options = $options.sort(function(a,b){ // sort by value of options
          return a.value - b.value;
        });
        $(this).html($options); // add new sorted options to select
      });
    });
  });
  </script>
</body>

</html>
