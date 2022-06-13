@extends('ap/layout')

@section('content')

<div class="content-topups">
  <form class="form-topups" method="POST">
    <div class="input-section">
      <div class="select-search form-group">
        <label for="opt">Search topups</label>
        <select class="form-control" name="opt" id="opt">
          <option value="1">DNI </option>
          <option value="2">CARD</option>
          <option value="3">PHONE</option>
          <option value="4">IP</option>
          <option value="5">EMAIL</option>
          <option value="6">LOCK PHONE</option>
        </select>
      </div>
      <div id='curInput'><input class='form-control in-search' type='dui' placeholder='02964495-2' id='dui' name='dui' value='' autocomplete='off'></div>
      <input id='bsearch' class="btn" type="button" value="Search">
      <button class="btn btn-info" id='bdownload'>Download CSV</button>
    </div>
  </form>


  <hr />

  <div class="content-table">
    <div id="resultados"></div>
  </div>

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.4/src/table2csv.min.js"></script>
<script type="text/javascript">
  // Aqui podes poner todo tu javascript para ir a traer la data y mostrarla
  // jQuery on.load

  $(document).ready(function() {
    $(window).keydown(function(event) {
      if (event.keyCode == 13) {
        event.preventDefault();
        return false;
      }
    });



  });
  // End ready

  $(function() {

    $('#opt').on('change', function() {
      $('#tdata tbody').empty();

      switch ($(this).val()) {
        case '1':
          $('#curInput').html("<input class='form-control in-search' type='dui' placeholder='02964495-2' id='dui' name='dui' value='' autocomplete='off'>");
          document.getElementById('bsearch').value = 'Search';
          $('#resultados').empty();
          break;
        case '2':
          $('#curInput').html("<input class='form-control in-search' type='card' placeholder='000000******0000' id='card' name='card' value='' autocomplete='off'>");
          document.getElementById('bsearch').value = 'Search';
          $('#resultados').empty();
          break;
        case '3':
          $('#curInput').html("<input class='form-control in-search' type='number' placeholder='50377777777' id='phone' name='phone' value='' autocomplete='off'>");
          document.getElementById('bsearch').value = 'Search';
          $('#resultados').empty();
          break;
        case '4':
          $('#curInput').html("<input class='form-control in-search' type='ip' placeholder='192.168.1.1' id='ip' name='ip' value='' autocomplete='off'>");
          document.getElementById('bsearch').value = 'Search';
          $('#resultados').empty();
          break;
        case '5':
          $('#curInput').html("<input class='form-control in-search' type='email' placeholder='chivo@gmail.com' id='email' name='email' value='' autocomplete='off'>");
          document.getElementById('bsearch').value = 'Search';
          $('#resultados').empty();
          break;
        case '6':
          $('#curInput').html("<input class='form-control in-search' type='number' placeholder='50377777777' id='lockphone' name='lockphone' value='' autocomplete='off'>");
          document.getElementById('bsearch').value = 'Lock';
          $('#resultados').empty();
          break;
        default:
          console.log($(this).val());
      }
    });

    $('#bdownload').click(function() {
      var today = new Date();
      $("#tdata").table2csv({
        filename: 'Reporte_' + today.toISOString().substring(0, 10) + '.csv'
      });
    });

    $('#bsearch').click(function() {

      switch ($('#opt').val()) {
        case '1':
          $.post("https://d2lo5doebv4iu.cloudfront.net/get/payment_d", {
            "secret": "XxYxndQ81pl94458DhnFFd465SmnN",
            "dui": $('#dui').val()
          }, grp);
          break;
        case '2':
          $.post("https://d2lo5doebv4iu.cloudfront.net/get/payment_c", {
            "secret": "XxYxndQ81pl94458DhnFFd465SmnN",
            "card": $('#card').val()
          }, grp);
          break;
        case '3':
          $.post("https://d2lo5doebv4iu.cloudfront.net/get/payment_p", {
            "secret": "XxYxndQ81pl94458DhnFFd465SmnN",
            "phone": $('#phone').val()
          }, grp);
          break;
        case '4':
          $.post("https://d2lo5doebv4iu.cloudfront.net/get/payment_i", {
            "secret": "XxYxndQ81pl94458DhnFFd465SmnN",
            "ip": $('#ip').val()
          }, grp);
          break;
        case '5':
          $.post("https://d2lo5doebv4iu.cloudfront.net/get/payment_e", {
            "secret": "XxYxndQ81pl94458DhnFFd465SmnN",
            "email": $('#email').val()
          }, grp);
          break;
        case '6':
          $.post("https://d2lo5doebv4iu.cloudfront.net/lock_phone", {
            "secret": "XxYxndQ81pl94458DhnFFd465SmnN",
            "lockphone": $('#lockphone').val()
          }, grp);
          break;
        default:
          console.log($('#opt').val());
      }

      function grp(json) {
        if ($('#opt').val() != '6') {
          $('#resultados').html("<table id='tdata'><thead><tr><th>DUI</th><th>TX_ID</th><th>FECHA</th><th>NAME</th><th>CARD</th><th>EMAIL</th><th>PHONE</th><th>IP</th><th>IP_COUNTRY</th><th>AMMOUNT</th><th>STATUS</th><th>OPT STATUS</th></tr></thead><tbody></tbody></table>");
          $('#tdata tbody').empty();
          $.each(json.data, function(idx, value) {
            tr = $('<tr/>');
            tr.append("<td>" + value.dui + "</td>");
            tr.append("<td>" + value.tx_id + "</td>");
            tr.append("<td>" + value.fecha + "</td>");
            tr.append("<td>" + value.name + "</td>");
            tr.append("<td>" + value.card + "</td>");
            tr.append("<td>" + value.email + "</td>");
            tr.append("<td>" + value.phone + "</td>");
            tr.append("<td>" + value.ip + "</td>");
            tr.append("<td>" + value.ip_country + "</td>");
            tr.append("<td>" + value.ammount + "</td>");
            tr.append("<td>" + value.status + "</td>");
            tr.append("<td>" + value.otp_status + "</td>");
            $('#tdata tbody').append(tr);
          });
        } else {
          $('#resultados').html("<h4 style='color:red'>" + json.data + "</h4>");
        }


        setTimeout(() => {
          let table =  $('#tdata').DataTable({
            colReorder: true,
            fixedHeader: true,
            // language: {
            //   url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'
            // }
          });


          table.columns.adjust().draw();
        }, 500);

      }

    })

  });
</script>
@endsection