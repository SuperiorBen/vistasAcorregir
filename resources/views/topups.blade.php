@extends('layout')
@section('content')
<style>
thead tr th:first-child,
tbody tr td:first-child {
  width: 6em;
  min-width: 6em;
  max-width: 6em;
  text-align:center;
}
</style>

<form method="POST">
    <div class="row">
        <div class="twelve columns">
            <label for="dui">Search topups by DNI or Card</label>
            <select name="opt" id="opt">
                <option value="1">DNI</option>
                <option value="2">CARD</option>
            </select>
            <div id='curInput'><input class='u-full-width' type='dui' placeholder='02964495-2' id='dui' name='dui' value='' autocomplete='off'></div>    
        </div>
    </div>
    <input id='bsearch' class="button-primary" type="button" value="Search">
</form>

<hr />

<div id="resultados">
    <table id='tdata' style='font-size: small;' class="table table-bordered table-hover">
    <thead>
    <tr>
      <th>DUI</th>
      <th>TX_ID</th>
      <th>FECHA</th>
      <th>NAME</th>
      <th>CARD</th>
      <th>EMAIL</th>
      <th>PHONE</th>
      <th>IP</th>            
      <th>AMMOUNT</th>
      <th>STATUS</th>
    </tr>
  </thead>
    <tbody></tbody></table>
</div>
@endsection

@section('script')
<script type="text/javascript">
// Aqui podes poner todo tu javascript para ir a traer la data y mostrarla
// jQuery on.load

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});

$(function(){

$('#opt').on('change', function() {
    $('#tdata tbody').empty();
  if (this.value == 1){
        // console.log(this.value);
        $('#curInput').html("<input class='u-full-width' type='dui' placeholder='02964495-2' id='dui' name='dui' value='' autocomplete='off'>");
  } if (this.value == 2){
        // console.log(this.value);
        $('#curInput').html("<input class='u-full-width' type='card' placeholder='000000******0000' id='card' name='card' value='' autocomplete='off'>");
  } if (this.value == 3){
        // console.log(this.value);
        $('#curInput').html("<input class='u-full-width' type='phone' placeholder='77777777' id='phone' name='phone' value='' autocomplete='off'>");
  } if (this.value == 4){
        // console.log(this.value);
        $('#curInput').html("<input class='u-full-width' type='ip' placeholder='192.168.1.1' id='ip' name='ip' value='' autocomplete='off'>");
  } else{
        // console.log(this.value);
        $('#curInput').html("<input class='u-full-width' type='card' placeholder='000000******0000' id='card' name='card' value='' autocomplete='off'>");
  }
});    

$('#bsearch').click(function(){

    if($('#opt').val() == 1) {

        $.post( "https://d2lo5doebv4iu.cloudfront.net/get/payment_d", {
        "secret": "XxYxndQ81pl94458DhnFFd465SmnN",
        "dui": $('#dui').val()
        }, grp );

    }
    if($('#opt').val() == 2) {

      $.post( "https://d2lo5doebv4iu.cloudfront.net/get/payment_c", {
        "secret": "XxYxndQ81pl94458DhnFFd465SmnN",
        "card": $('#card').val()
        }, grp );

    }    
    if($('#opt').val() == 3) {

    $.post( "https://d2lo5doebv4iu.cloudfront.net/get/payment_p", {
      "secret": "XxYxndQ81pl94458DhnFFd465SmnN",
      "phone": $('#phone').val()
      }, grp );

    }    
    if($('#opt').val() == 4) {

    $.post( "https://d2lo5doebv4iu.cloudfront.net/get/payment_i", {
      "secret": "XxYxndQ81pl94458DhnFFd465SmnN",
      "ip": $('#ip').val()
      }, grp );

    }        
    else {

        $.post( "https://d2lo5doebv4iu.cloudfront.net/get/payment_c", {
        "secret": "XxYxndQ81pl94458DhnFFd465SmnN",
        "card": $('#card').val()
        }, grp );

    }

    function grp(data){
            $('#tdata tbody').empty();
            $.each(data.data, function(idx, value) {
                //console.log(value);
                tr = $('<tr/>');
                tr.append("<td>" + value.dui + "</td>");  
                tr.append("<td>" + value.tx_id + "</td>");  
                tr.append("<td>" + value.fecha + "</td>");  
                tr.append("<td>" + value.name + "</td>");  
                tr.append("<td>" + value.card + "</td>");  
                tr.append("<td>" + value.email + "</td>");  
                tr.append("<td>" + value.phone + "</td>");  
                tr.append("<td>" + value.ip + "</td>");                                  
                tr.append("<td>" + value.ammount + "</td>");  
                tr.append("<td>" + value.status + "</td>");  
                $('#tdata tbody').append(tr);
            });
        }

})


});

</script>
@endsection